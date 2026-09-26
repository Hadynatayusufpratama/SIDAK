<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\SubBidang;
use App\Models\DataKonservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Barryvdh\DomPDF\Facade\Pdf;

class KonservasiController extends Controller
{
    // Helper privat untuk memfilter query pencarian secara konsisten (pencarian multi-kolom + mapping bulan)
    private function applySearchFilter($query, Request $request)
    {
        if ($request->filled('search')) {
            $search = trim($request->search);

            // Mapping nama bulan Indonesia & Inggris ke format angka
            $monthsMap = [
                'januari' => '01', 'january' => '01', 'jan' => '01',
                'februari' => '02', 'february' => '02', 'feb' => '02',
                'maret' => '03', 'march' => '03', 'mar' => '03',
                'april' => '04', 'apr' => '04',
                'mei' => '05', 'may' => '05',
                'juni' => '06', 'june' => '06', 'jun' => '06',
                'juli' => '07', 'july' => '07', 'jul' => '07',
                'agustus' => '08', 'august' => '08', 'agu' => '08', 'aug' => '08',
                'september' => '09', 'sep' => '09',
                'oktober' => '10', 'october' => '10', 'okt' => '10', 'oct' => '10',
                'november' => '11', 'nov' => '11',
                'desember' => '12', 'december' => '12', 'des' => '12', 'dec' => '12',
            ];

            $searchLower = strtolower($search);
            $monthNumber = $monthsMap[$searchLower] ?? null;

            $query->where(function ($q) use ($search, $monthNumber) {
                $q->where('keterangan', 'like', "%{$search}%")
                  ->orWhere('tahun', 'like', "%{$search}%")
                  ->orWhere('jumlah', 'like', "%{$search}%")
                  ->orWhere('bulan', 'like', "%{$search}%");

                if ($monthNumber) {
                    $q->orWhere('bulan', $monthNumber)
                      ->orWhere('bulan', (int)$monthNumber);
                }

                $q->orWhereHas('subBidang', function ($subQ) use ($search) {
                    $subQ->where('nama_sub_bidang', 'like', "%{$search}%")
                         ->orWhereHas('bidang', function ($bidangQ) use ($search) {
                             $bidangQ->where('nama_bidang', 'like', "%{$search}%");
                         });
                });
            });
        }

        return $query;
    }

    // Halaman Rekapitulasi Data
    public function index(Request $request)
    {
        $query = DataKonservasi::with('subBidang.bidang')->latest();

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        $query = $this->applySearchFilter($query, $request);
        $data = $query->paginate(10)->withQueryString();
        
        return view('konservasi.index', compact('data'));
    }

    // Fitur Unduh PDF
    public function exportPdf(Request $request)
    {
        $query = DataKonservasi::with('subBidang.bidang')->latest();

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        $query = $this->applySearchFilter($query, $request);
        $datas = $query->get();

        $pdf = Pdf::loadView('konservasi.pdf', compact('datas'));
        return $pdf->download('Data_Konservasi_SIDAK_BKSDA_' . date('Y-m-d') . '.pdf');
    }

    // Fitur Unduh Excel (CSV)
    public function exportExcel(Request $request)
    {
        $query = DataKonservasi::with('subBidang.bidang')->latest();

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        $query = $this->applySearchFilter($query, $request);
        $datas = $query->get();

        $filename = 'Data_Konservasi_SIDAK_BKSDA_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($datas) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['No', 'Bidang Utama', 'Sub-Bidang / Kategori', 'Bulan', 'Tahun', 'Jumlah / Vol', 'Latitude', 'Longitude', 'Keterangan']);

            $no = 1;
            foreach ($datas as $item) {
                fputcsv($file, [
                    $no++,
                    $item->subBidang->bidang->nama_bidang ?? '-',
                    $item->subBidang->nama_sub_bidang ?? '-',
                    $item->bulan ?? '-',
                    $item->tahun ?? '-',
                    $item->jumlah ?? 0,
                    $item->latitude ?? '-',
                    $item->longitude ?? '-',
                    $item->keterangan ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Halaman Form Input Data
    public function create()
    {
        $bidang = Bidang::all();
        $kawasanKonservasi = collect([
            'TWA Wera',
            'SM Tanjung Santigi',
            'CA Tanjung Api',
            'TWA Pulau Tokobae',
            'TWA Pulau Pasoso',
            'SM Pulau Dolangan',
            'SM Pinjan Tanjung Matop',
            'SM Pati-Pati',
            'CA Pangi Binangga',
            'CA Pamona',
            'CA Morowali',
            'SM Lombuyan',
            'TB Landusa Tomata',
            'CA Gunung Tinombala',
            'CA Gunung Sojol',
            'CA Gunung Dako',
            'TWA Bancea',
            'SM Bakiriang',
        ])->map(fn ($nama) => [
            'nama' => $nama . ' (Satker: Balai KSDA Sulawesi Tengah)',
        ])->all();

        return view('konservasi.create', compact('bidang', 'kawasanKonservasi'));
    }

    public function getSubBidang($bidang_id)
    {
        $subBidang = SubBidang::where('bidang_id', $bidang_id)->get();
        return response()->json($subBidang);
    }

    // Proses Simpan Data (Sudah diperbarui penuh sesuai Form Sub-Bidang 1 s/d 6)
    public function store(Request $request)
    {
        $request->validate([
            'sub_bidang_id'             => 'required|exists:ref_sub_bidang,id',
            'sk_parsial_file'           => 'nullable|mimes:pdf|max:2048',
            'sk_provinsi_file'          => 'nullable|mimes:pdf|max:2048',
            'sk_penetapan_file'         => 'nullable|mimes:pdf|max:2048',
            'shapefile_zip'             => 'nullable|mimes:zip|max:10240',
            'sk_rpjp_file'              => 'nullable|mimes:pdf|max:20480',
            'dokumen_batb'              => 'nullable|mimes:pdf|max:20480',
            'shapefile_monitoring_zip'  => 'nullable|mimes:zip|max:10240',
            'file_dokumen_evaluasi'     => 'nullable|mimes:pdf|max:20480',
            'shapefile_ekosistem_zip'   => 'nullable|mimes:zip|max:10240',
            'file_sk_zonasi'            => 'nullable|mimes:pdf|max:2048',
            'shapefile_zonasi_zip'      => 'nullable|mimes:zip|max:10240',
            'dokumen_d03'               => 'nullable|mimes:pdf|max:10240',
            'shapefile_d03'             => 'nullable|mimes:zip|max:10240',
        ]);

        $details = [];
        $tahun = $request->tahun ?? $request->tahun_rpjp ?? $request->tahun_monitoring ?? $request->tahun_evaluasi ?? $request->tahun_ekosistem ?? $request->tahun_zonasi ?? date('Y');
        $jumlah = 0;

        // SUB-BIDANG: Pengunjung Kawasan Konservasi (D.01)
        if ($request->filled('kawasan_nama_d01')) {
            $details[] = "Kawasan Pengunjung: " . $request->kawasan_nama_d01;
            $kategoriPengunjung = [
                'penelitian' => 'Penelitian & Pengembangan',
                'pendidikan' => 'Pendidikan & Ilmu Pengetahuan',
                'foto_video' => 'Pengambilan Foto & Video',
                'wisata_alam' => 'Wisata Alam',
                'lain_lain' => 'Lain-lain',
            ];

            foreach ($kategoriPengunjung as $kodeKategori => $labelKategori) {
                $dalamNegeri = (int) $request->input($kodeKategori . '_dalam_negeri', 0);
                $luarNegeri = (int) $request->input($kodeKategori . '_luar_negeri', 0);
                $jumlah += $dalamNegeri + $luarNegeri;
                $details[] = "[$labelKategori] Dalam Negeri: $dalamNegeri, Luar Negeri: $luarNegeri orang";
            }
        }

        // SUB-BIDANG: PNBP Wisata Alam di Kawasan Konservasi (D.02)
        if ($request->filled('kawasan_nama_d02')) {
            $details[] = "Kawasan PNBP Wisata Alam: " . $request->kawasan_nama_d02;
            foreach ($request->all() as $field => $value) {
                if (!str_starts_with($field, 'd02_') || $value === null || $value === '') {
                    continue;
                }

                if (str_ends_with($field, '_jumlah') && is_numeric($value)) {
                    $jumlah += (int) $value;
                }

                if ($value !== '0' && !str_ends_with($field, '_keterangan')) {
                    $details[] = strtoupper(str_replace('_', ' ', $field)) . ": " . $value;
                } elseif (str_ends_with($field, '_keterangan') && trim((string) $value) !== '') {
                    $details[] = "Keterangan " . str_replace('_', ' ', $field) . ": " . $value;
                }
            }
        }

        // SUB-BIDANG: Desain Tapak Pemanfaatan Jasa Lingkungan Wisata Alam (D.03)
        if ($request->filled('kawasan_nama_d03')) {
            $details[] = "Kawasan Desain Tapak: " . $request->kawasan_nama_d03;
            if ($request->ada_pengesahan_d03 === 'ya') {
                $details[] = "[Desain Tapak] Zonasi/Blok: " . ($request->zonasi_blok_d03 ?? '-') . ", Bidang/Seksi: " . ($request->bidang_seksi_d03 ?? '-');
                $details[] = "[Desain Tapak] No SK: " . ($request->nomor_dokumen_d03 ?? '-') . ", Tanggal: " . ($request->tanggal_pengesahan_d03 ?? '-') . ", Judul: " . ($request->judul_sk_d03 ?? '-') . ", Luas: " . ($request->luas_zona_d03 ?? '0') . " Ha";
                if ($request->filled('keterangan_d03')) {
                    $details[] = "[Desain Tapak] Keterangan: " . $request->keterangan_d03;
                }
            } elseif ($request->ada_pengesahan_d03 === 'tidak') {
                $details[] = '[Desain Tapak] Status: Tidak ada (Nihil)';
            }
        }

        // 1. SUB-BIDANG: Kawasan Konservasi (A.01)
        if ($request->filled('kawasan_nama')) {
            $details[] = "Kawasan: " . $request->kawasan_nama;
            if ($request->ada_perubahan === 'ya') {
                $details[] = "[SK Parsial] No: " . ($request->sk_parsial_nomor ?? '-') . ", Tgl: " . ($request->sk_parsial_tanggal ?? '-') . ", Luas: " . ($request->sk_parsial_luas ?? '0') . " Ha";
                $jumlah = $request->sk_parsial_luas ?? $jumlah;
            }
            if ($request->sk_provinsi_tersedia === 'ya') {
                $details[] = "[SK Provinsi] No: " . ($request->sk_provinsi_nomor ?? '-') . ", Tgl: " . ($request->sk_provinsi_tanggal ?? '-') . ", Luas: " . ($request->sk_provinsi_luas ?? '0') . " Ha";
                $jumlah = $request->sk_provinsi_luas ?? $jumlah;
            }
            if ($request->sk_penetapan_tersedia === 'ya') {
                $details[] = "[SK Penetapan] No: " . ($request->sk_penetapan_nomor ?? '-') . ", Tgl: " . ($request->sk_penetapan_tanggal ?? '-') . ", Luas: " . ($request->sk_penetapan_luas ?? '0') . " Ha";
                $jumlah = $request->sk_penetapan_luas ?? $jumlah;
            }
        }

        // 2. SUB-BIDANG: Perencanaan Pengelolaan (A.02)
        if ($request->filled('kawasan_nama_rpjp')) {
            $details[] = "Kawasan RPJP: " . $request->kawasan_nama_rpjp;
            if ($request->ketersediaan_rpjp === 'ya') {
                $details[] = "[RPJP] No SK: " . ($request->sk_rpjp_nomor ?? '-') . ", Tgl Pengesahan: " . ($request->sk_rpjp_tanggal_pengesahan ?? '-') . ", Periode Berakhir: " . ($request->sk_rpjp_periode_berakhir ?? '-');
            } else {
                $details[] = "[RPJP] Status: Tidak tersedia (Nihil)";
            }
        }

        // 3. SUB-BIDANG: Monitoring Batas Kawasan (A.03)
        if ($request->filled('kawasan_nama_monitoring')) {
            $details[] = "Kawasan Monitoring: " . $request->kawasan_nama_monitoring;
            if ($request->ada_kegiatan_monitoring === 'ya') {
                $details[] = "[Monitoring BATB] Jenis: " . ($request->jenis_kegiatan ?? '-') . ", No BATB: " . ($request->nomor_batb ?? '-') . ", Tgl BATB: " . ($request->tanggal_batb ?? '-');
                $details[] = "[Pal Batas] Baik: " . ($request->pal_baik ?? 0) . ", Rusak: " . ($request->pal_rusak ?? 0) . ", Hilang: " . ($request->pal_hilang ?? 0) . ", Panjang: " . ($request->panjang_pal_km ?? 0) . " Km";
                $jumlah = $request->panjang_pal_km ?? $jumlah;
            } else {
                $details[] = "[Monitoring BATB] Status: Tidak ada (Nihil)";
            }
        }

        // 4. SUB-BIDANG: Evaluasi Kesesuaian Fungsi (A.04)
        if ($request->filled('kawasan_nama_evaluasi')) {
            $details[] = "Kawasan Evaluasi: " . $request->kawasan_nama_evaluasi;
            if ($request->ketersediaan_evaluasi === 'ya') {
                $details[] = "[Evaluasi] Tgl Pelaksanaan: " . ($request->tanggal_pelaksanaan_evaluasi ?? '-');
                $details[] = "Rekomendasi: " . ($request->rekomendasi_evaluasi ?? '-');
                $details[] = "Tindak Lanjut: " . ($request->tindak_lanjut_evaluasi ?? '-');
            } else {
                $details[] = "[Evaluasi] Status: Tidak tersedia (Nihil)";
            }
        }

        // 5. SUB-BIDANG: Ekosistem Kawasan (A.05)
        if ($request->filled('kawasan_nama_ekosistem')) {
            $details[] = "Kawasan Ekosistem: " . $request->kawasan_nama_ekosistem;
            $details[] = "[Ekosistem] Status Data: " . ($request->ketersediaan_ekosistem === 'ya' ? 'Tersedia' : 'Tidak tersedia (Nihil)');
        }

        // 6. SUB-BIDANG: Penataan Zonasi/Blok (A.06)
        if ($request->filled('kawasan_nama_zonasi')) {
            $details[] = "Kawasan Zonasi: " . $request->kawasan_nama_zonasi;
            if ($request->ketersediaan_zonasi === 'sudah') {
                $details[] = "[Zonasi] No SK: " . ($request->nomor_sk_zonasi ?? '-') . ", Tgl SK: " . ($request->tanggal_sk_zonasi ?? '-');
            } else {
                $details[] = "[Zonasi] Status: Tidak/Belum Penataan";
            }
        }

        // Unggah Dokumen Berkas & Shapefile
        $fileInputs = [
            'sk_parsial_file'           => 'dokumen_sk',
            'sk_provinsi_file'          => 'dokumen_sk',
            'sk_penetapan_file'         => 'dokumen_sk',
            'shapefile_zip'             => 'shapefiles',
            'sk_rpjp_file'              => 'dokumen_rpjp',
            'dokumen_batb'              => 'dokumen_batb',
            'shapefile_monitoring_zip'  => 'shapefiles',
            'file_dokumen_evaluasi'     => 'dokumen_evaluasi',
            'shapefile_ekosistem_zip'   => 'shapefiles',
            'file_sk_zonasi'            => 'dokumen_zonasi',
            'shapefile_zonasi_zip'      => 'shapefiles',
            'dokumen_d03'               => 'dokumen_desain_tapak',
            'shapefile_d03'             => 'shapefiles',
        ];

        foreach ($fileInputs as $inputName => $folderPath) {
            if ($request->hasFile($inputName)) {
                $request->file($inputName)->store($folderPath, 'public');
            }
        }

        // Gabungkan Keterangan Detail
        if ($request->filled('keterangan')) {
            $details[] = "Keterangan Tambahan: " . $request->keterangan;
        }

        $keteranganFinal = count($details) > 0 ? implode(" | ", $details) : ($request->keterangan ?? '-');

        $payload = [
            'sub_bidang_id' => $request->sub_bidang_id,
            'tahun'         => $tahun,
            'bulan'         => $request->bulan ?? null,
            'latitude'      => $request->latitude ?? null,
            'longitude'     => $request->longitude ?? null,
            'jumlah'        => $jumlah,
            'keterangan'    => $keteranganFinal,
        ];

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $payload['user_id'] = auth()->id();
        }

        DataKonservasi::create($payload);

        return redirect()->route('konservasi.index')->with('success', 'Data Konservasi Berhasil Disimpan ke Sistem SIDAK!');
    }

    // Proses Hapus Data
    public function destroy($id)
    {
        $query = DataKonservasi::query();

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        $data = $query->findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Data Konservasi berhasil dihapus!');
    }

    // Halaman Peta GIS Kawasan
    public function peta()
    {
        $query = DataKonservasi::with('subBidang.bidang')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        $locations = $query->get();

        return view('konservasi.peta', compact('locations'));
    }

    // Halaman Dashboard Analytics
    public function dashboard()
    {
        $userQuery = DataKonservasi::query();

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $userQuery->where('user_id', auth()->id());
        }

        $totalData = (clone $userQuery)->count(); 
        $totalVolume = (clone $userQuery)->sum('jumlah'); 
        $totalLokasi = (clone $userQuery)->whereNotNull('latitude')
                                         ->whereNotNull('longitude')
                                         ->where('latitude', '!=', '')
                                         ->where('longitude', '!=', '')
                                         ->count();

        $totalKawasan = (clone $userQuery)->distinct('sub_bidang_id')->count('sub_bidang_id');

        $allData = (clone $userQuery)->with('subBidang.bidang')->get();

        $statsVolume = [];
        foreach ($allData as $item) {
            $namaBidang = $item->subBidang->bidang->nama_bidang ?? 'Lainnya';
            if (!isset($statsVolume[$namaBidang])) {
                $statsVolume[$namaBidang] = 0;
            }
            $statsVolume[$namaBidang] += ($item->jumlah > 0 ? $item->jumlah : 1);
        }

        $chartLabels = array_keys($statsVolume);
        $chartData = array_values($statsVolume);

        $recentData = (clone $userQuery)->with('subBidang.bidang')->latest()->take(5)->get();

        return view('konservasi.dashboard', compact(
            'totalData', 
            'totalVolume', 
            'totalLokasi', 
            'totalKawasan',
            'recentData', 
            'chartLabels', 
            'chartData'
        ));
    }

    // Halaman Edit Data
    public function edit($id)
    {
        $query = DataKonservasi::query();

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        $item = $query->findOrFail($id);
        $bidangs = \App\Models\Bidang::all();
        
        $currentBidangId = $item->subBidang ? $item->subBidang->bidang_id : null;
        $subBidangs = \App\Models\SubBidang::where('bidang_id', $currentBidangId)->get();

        return view('konservasi.edit', compact('item', 'bidangs', 'subBidangs', 'currentBidangId'));
    }

    // Proses Update Data
    public function update(Request $request, $id)
    {
        $request->validate([
            'sub_bidang_id' => 'required',
            'tahun'         => 'required|numeric',
        ]);

        $query = DataKonservasi::query();

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        $item = $query->findOrFail($id);

        $item->update([
            'sub_bidang_id' => $request->sub_bidang_id,
            'tahun'         => $request->tahun,
            'bulan'         => $request->bulan,
            'latitude'      => $request->latitude,
            'longitude'     => $request->longitude,
            'jumlah'        => $request->jumlah ?? 0,
            'keterangan'    => $request->keterangan,
        ]);

        return redirect()->route('konservasi.index')->with('success', 'Data konservasi berhasil diperbarui!');
    }
}