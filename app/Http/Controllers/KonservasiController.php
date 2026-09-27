<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Rekap;
use App\Models\SubBidang;
use App\Models\DataKonservasi;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
                         ->orWhere('kode_sub', 'like', "%{$search}%")
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
        // 1. Array Master Bidang A-F Lengkap untuk Form Dropdown Filter Bertingkat
        $masterBidang = [
            'perencanaan_konservasi' => [
                'nama' => 'Perencanaan Konservasi',
                'subs' => [
                    'A.01' => 'A.01. Kawasan Konservasi',
                    'A.02' => 'A.02. Perencanaan Pengelolaan Kawasan Konservasi',
                    'A.03' => 'A.03. Monitoring Batas Kawasan Konservasi',
                    'A.04' => 'A.04. Hasil Evaluasi Kesesuaian Fungsi Kawasan Konservasi',
                    'A.05' => 'A.05. Ekosistem Kawasan Konservasi',
                    'A.06' => 'A.06. Penataan Kawasan Konservasi',
                    'A.07' => 'A.07. Kerja Sama Penyelenggaraan KSA dan KPA',
                ]
            ],
            'konservasi_kawasan' => [
                'nama' => 'Konservasi Kawasan',
                'subs' => [
                    'B.01' => 'B.01. Kelompok Binaan UPT dalam rangka Pemberdayaan Masyarakat',
                    'B.02' => 'B.02. Pemberian Akses Pemanfaatan Tradisional dan Kemitraan Konservasi',
                    'B.03' => 'B.03. Permasalahan Strategis Kawasan Konservasi',
                    'B.04' => 'B.04. Gangguan Kawasan Konservasi',
                    'B.06' => 'B.06. Penanganan Perkara Tindak Pidana',
                    'B.07' => 'B.07. Tenaga Pengamanan Hutan Per Satuan Kerja',
                    'B.08' => 'B.08. Tenaga Pengamanan Hutan Per Resor',
                    'B.09' => 'B.09. Sarana Pengamanan Hutan',
                    'B.10' => 'B.10. Kebakaran Hutan dan Lahan di Kawasan Konservasi',
                    'B.11' => 'B.11. Tenaga Pengendalian Kebakaran Hutan',
                    'B.12' => 'B.12. Peralatan Tangan Pengendalian Kebakaran Hutan',
                    'B.13' => 'B.13. Peralatan Lainnya untuk Kebutuhan Pengendalian Kebakaran Hutan',
                    'B.14' => 'B.14. Rekapitulasi Kader Bina Cinta Alam',
                ]
            ],
            'konservasi_spesies' => [
                'nama' => 'Konservasi Spesies dan Genetik',
                'subs' => [
                    'C.01' => 'C.01. Perjumpaan Spesies di dalam dan luar Kawasan Konservasi',
                    'C.02' => 'C.02. Lembaga Konservasi Umum dan Khusus',
                    'C.03' => 'C.03. Koleksi TSL di Lembaga Konservasi',
                    'C.04' => 'C.04. Penangkaran Tumbuhan dan Satwa Liar',
                    'C.05' => 'C.05. Jenis TSL yang ditangkarkan di Penangkaran',
                    'C.06' => 'C.06. Pengedar Tumbuhan dan Satwa Liar (Dalam dan Luar Negeri)',
                    'C.07' => 'C.07. Kuota Pemanfaatan Tumbuhan dan Satwa Liar',
                    'C.08' => 'C.08. Realisasi Penangkapan/ Pengambilan Tumbuhan dan Satwa Liar',
                    'C.09' => 'C.09. Realisasi Ekspor Tumbuhan dan Satwa Liar Hasil Penangkaran',
                    'C.10' => 'C.10. Realisasi Ekspor Tumbuhan dan Satwa Liar Pengambilan dari Alam',
                    'C.11' => 'C.11. Rekapitulasi Sitaan/ Penyerahan/ Penyelamatkan Satwa',
                    'C.12' => 'C.12. PNBP dari kegiatan Pemanfaatan Tumbuhan dan Satwa Liar',
                    'C.14' => 'C.14. Interaksi Negatif Satwa Liar dan Manusia',
                    'C.15' => 'C.15. Rekapitulasi Pelepasliaran Kembali Satwa',
                    'C.16' => 'C.16. Rekapitulasi Kelahiran Satwa',
                    'C.17' => 'C.17. Rekapitulasi Kematian Satwa Liar',
                ]
            ],
            'pemanfaatan_jasling' => [
                'nama' => 'Pemanfaatan Jasa Lingkungan',
                'subs' => [
                    'D.01' => 'D.01. Pengunjung Kawasan Konservasi',
                    'D.02' => 'D.02. PNBP Wisata Alam di Kawasan Konservasi',
                    'D.03' => 'D.03. Desain Tapak Pemanfaatan Jasa Lingkungan Wisata Alam',
                    'D.04' => 'D.04. Potensi Wisata Alam di Kawasan Konservasi',
                    'D.05' => 'D.05. Pemanfaatan Jasa Lingkungan Penyediaan Jasa Wisata Alam (PBPJWA)',
                    'D.06' => 'D.06. Pengusahaan Sarana Jasa Lingkungan Wisata Alam (PBPSWA)',
                    'D.07' => 'D.07. Sarana dan Prasarana Wisata Alam di Kawasan Konservasi',
                    'D.08' => 'D.08. Dampak Aktivitas Wisata Alam',
                    'D.09' => 'D.09. Potensi Pemanfaatan Air dan Energi Air di Kawasan Konservasi',
                    'D.10' => 'D.10. Areal Pemanfaatan Air dan Energi Air di Kawasan Konservasi',
                    'D.11' => 'D.11. Pemanfaatan Air dan Energi Air di Kawasan Konservasi',
                    'D.12' => 'D.12. Potensi Pemanfaatan Karbon di Kawasan Konservasi',
                    'D.13' => 'D.13. Potensi Pemanfaatan Energi Panas Bumi di Kawasan Konservasi',
                    'D.14' => 'D.14. Pemanfaatan Jasa Lingkungan Panas Bumi di Kawasan Konservasi',
                    'D.15' => 'D.15. Kejadian Kecelakaan di dalam Kawasan Konservasi',
                    'D.16' => 'D.16. Promosi dan Publikasi Jasa Lingkungan Kawasan Konservasi',
                ]
            ],
            'pemulihan_ekosistem' => [
                'nama' => 'Pemulihan Ekosistem dan Bina Area Preservasi',
                'subs' => [
                    'E.01' => 'E.01. Perencanaan Pemulihan Ekosistem',
                    'E.02' => 'E.02. Realisasi Pemulihan Ekosistem',
                    'E.03' => 'E.03. Hasil Inventarisasi Area dengan Potensi Kehati Tinggi di Luar Kawasan Konservasi',
                    'E.04' => 'E.04. Kawasan Ekosistem Esensial',
                    'E.05' => 'E.05. Perencanaan Kawasan Ekosistem Esensial',
                    'E.06' => 'E.06. Penilaian Efektivitas Pengelolaan KEE',
                    'E.07' => 'E.07. Situs Ramsar',
                ]
            ],
            'kesekretariatan' => [
                'nama' => 'Kesekretariatan',
                'subs' => [
                    'F.01' => 'F.01. Sebaran PNS/CPNS Menurut Jabatan dan Jenis Kelamin',
                    'F.02' => 'F.02. Sebaran PNS/CPNS Menurut Tingkat Pendidikan dan Jenis Kelamin',
                    'F.03' => 'F.03. Sebaran PNS/CPNS Menurut Golongan dan Jenis Kelamin',
                    'F.04' => 'F.04. Sebaran Pejabat Fungsional Tertentu Menurut Fungsi dan Jenis Kelamin',
                    'F.05' => 'F.05. Sebaran Pejabat Fungsional Tertentu Menurut Fungsi, Tingkat Pendidikan dan Jenis Kelamin',
                    'F.06' => 'F.06. Sebaran ASN P3K menurut Tingkat Pendidikan dan Jenis Kelamin',
                    'F.07' => 'F.07. Kerja Sama Teknik Bidang KSDAE',
                    'F.08' => 'F.08. Perijinan Masuk Kawasan Konservasi',
                ]
            ]
        ];

        // 2. Ambil parameter filter input
        $selectedBidang = $request->get('bidang');
        $selectedSub = $request->get('sub_bidang');

        // 3. Query utama data konservasi
        $query = DataKonservasi::with('subBidang.bidang')->latest();

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        // Filter berdasarkan Bidang Utama jika dipilih
        if ($selectedBidang && isset($masterBidang[$selectedBidang])) {
            $namaBidangSearch = $masterBidang[$selectedBidang]['nama'];
            $query->whereHas('subBidang.bidang', function ($q) use ($namaBidangSearch) {
                $q->where('nama_bidang', 'like', "%{$namaBidangSearch}%");
            });
        }

        // Filter jika user telah memilih sub_bidang tertentu
        if ($selectedSub) {
            $query->whereHas('subBidang', function ($q) use ($selectedSub) {
                $q->where('kode_sub', $selectedSub)
                  ->orWhere('id', $selectedSub);
            });
        }

        $query = $this->applySearchFilter($query, $request);
        $conservationRecords = $query->get();

        $secretariatQuery = Rekap::where('kode_sub_bidang', 'like', 'F.%');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $matchingCodes = SubBidang::where('nama_sub_bidang', 'like', "%{$search}%")
                ->pluck('kode_sub');

            $secretariatQuery->where(function ($recapQuery) use ($search, $matchingCodes) {
                $recapQuery->where('kode_sub_bidang', 'like', "%{$search}%")
                    ->orWhere('tahun', 'like', "%{$search}%")
                    ->orWhere('kawasan', 'like', "%{$search}%")
                    ->orWhereIn('kode_sub_bidang', $matchingCodes);
            });
        }

        $secretariatRecords = $secretariatQuery->latest()->get();

        $secretariatNames = SubBidang::whereIn(
    'kode_sub',
    $secretariatRecords->pluck('kode_sub_bidang')->unique()
)->pluck('nama_sub_bidang', 'kode_sub');

        $conservationRecords->each(function ($record) {
            $record->history_type = 'conservation';
        });

        $secretariatRecords->each(function ($record) use ($secretariatNames) {
            $record->history_type = 'secretariat';
            $record->history_sub_bidang =
                $secretariatNames[$record->kode_sub_bidang] ?? 'Data Sekretariat';
            $record->history_total =
                $this->sumSecretariatCounts($record->data_kustom ?? []);
        });

        $allRecords = $conservationRecords
            ->concat($secretariatRecords)
            ->sortByDesc('created_at')
            ->values();

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $data = new LengthAwarePaginator(
            $allRecords->forPage($currentPage, $perPage)->values(),
            $allRecords->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );

        $totalRecords = $allRecords->count();

           return view('konservasi.index', compact(
            'data',
            'totalRecords',
            'masterBidang',
            'selectedBidang',
            'selectedSub'
        ));
    }
    private function sumSecretariatCounts(array $values): int
{
    $total = 0;

    foreach ($values as $key => $value) {
        if (
            in_array(
                $key,
                ['laki_laki', 'perempuan', 'dalam_negeri', 'luar_negeri'],
                true
            ) && is_numeric($value)
        ) {
            $total += (int) $value;
        } elseif (is_array($value)) {
            $total += $this->sumSecretariatCounts($value);
        }
    }

    return $total;
}

    // Halaman Form Input Data
    public function create(Request $request)
    {
        $bidang = Bidang::all();


        $selectedSubBidang = $request->filled('sub_bidang')
            ? SubBidang::where('kode_sub', $request->query('sub_bidang'))->first()
            : null;

        if (!$selectedSubBidang && $request->session()->getOldInput('sub_bidang_id')) {
            $selectedSubBidang = SubBidang::find(
                $request->session()->getOldInput('sub_bidang_id')
            );
        }


        $fieldsC02 = $this->fieldsBySubBidang()['C.02'];
        $lembagaQuery = DataKonservasi::with('subBidang')
            ->whereHas('subBidang', fn ($query) => $query->where('kode_sub', 'C.02'));

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $lembagaQuery->where('user_id', auth()->id());
        }

        $lembagaKonservasiList = $lembagaQuery->latest()->get()
            ->map(fn ($record) => $this->parseDetailValues($record->keterangan, $fieldsC02)['nama_lembaga_c02'] ?? null)
            ->filter()
            ->unique()
            ->sort()
            ->values();
        $fieldsC04 = $this->fieldsBySubBidang()['C.04'];
        $penangkarQuery = DataKonservasi::with('subBidang')
            ->whereHas('subBidang', fn ($query) => $query->where('kode_sub', 'C.04'));

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $penangkarQuery->where('user_id', auth()->id());
        }

        $penangkarList = $penangkarQuery->latest()->get()
            ->map(fn ($record) => $this->parseDetailValues($record->keterangan, $fieldsC04)['nama_penangkar_c04'] ?? null)
            ->filter()
            ->unique()
            ->sort()
            ->values();
        $fieldsC06 = $this->fieldsBySubBidang()['C.06'];
        $pengedarQuery = DataKonservasi::with('subBidang')
            ->whereHas('subBidang', fn ($query) => $query->where('kode_sub', 'C.06'));

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $pengedarQuery->where('user_id', auth()->id());
        }

        $pengedarList = $pengedarQuery->latest()->get()
            ->map(fn ($record) => $this->parseDetailValues($record->keterangan, $fieldsC06)['nama_pengedar_c06'] ?? null)
            ->filter()
            ->unique()
            ->sort()
            ->values();
        $spesiesKoleksiC03 = [
            'Anoa dataran rendah', 'Anoa pegunungan', 'Babirusa', 'Maleo',
            'Yaki (Monyet hitam Sulawesi)', 'Tarsius', 'Kuskus beruang',
            'Kuskus kerdil', 'Rangkong Sulawesi', 'Kakatua kecil jambul kuning',
        ];
        $negaraTujuanC09 = [
            'Australia', 'Belanda', 'Hong Kong', 'Jepang', 'Jerman', 'Korea Selatan',
            'Malaysia', 'Singapura', 'Thailand', 'Tiongkok', 'Amerika Serikat', 'Lainnya',
        ];

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

        // Jika sedang mengedit data kesekretariatan
        $editRecord = null;

        if ($request->filled('edit_record')) {
            abort_unless($selectedSubBidang, 404);

            $editRecord = Rekap::where(
                'kode_sub_bidang',
                $selectedSubBidang->kode_sub
            )->findOrFail(
                $request->integer('edit_record')
            );
        }

        return view('konservasi.create', [
            'bidang' => $bidang,
            'kawasanKonservasi' => $kawasanKonservasi,
            'selectedSubBidang' => $selectedSubBidang,
            'editRecord' => $editRecord,

            // Data untuk form Kesekretariatan 
            'struktural' => RekapController::STRUKTURAL,
            'fungsional' => RekapController::FUNGSIONAL,
            'pendidikan' => RekapController::PENDIDIKAN,
            'pendidikanP3K' => RekapController::PENDIDIKAN_P3K,
            'perijinan' => RekapController::PERIJINAN,
            'tipeMitra' => RekapController::TIPE_MITRA,
            'dukunganKinerja' => RekapController::DUKUNGAN_KINERJA,
            'golongan' => RekapController::GOLONGAN,
            'jabatanFungsional' => RekapController::JABATAN_FUNGSIONAL,

            // Data form Konservasi terbaru
            'lembagaKonservasiList' => $lembagaKonservasiList,
            'spesiesKoleksiC03' => $spesiesKoleksiC03,
            'penangkarList' => $penangkarList,
            'pengedarList' => $pengedarList,
            'negaraTujuanC09' => $negaraTujuanC09,
        ]);

        return view('konservasi.create', compact('bidang', 'kawasanKonservasi', 'lembagaKonservasiList', 'spesiesKoleksiC03', 'penangkarList', 'pengedarList', 'negaraTujuanC09'));
    }


    // Mengambil Sub-Bidang Berdasarkan Bidang ID untuk Dropdown Dinamis
    public function getSubBidang($bidang_id)
    {
        $subBidang = SubBidang::where('bidang_id', $bidang_id)->get();
        return response()->json($subBidang);
    }

    private function fieldsBySubBidang(): array
    {
        $d02Definitions = $this->d02Definitions();
        $d02Fields = ['tahun', 'bulan', 'kawasan_nama_d02'];
        foreach ($d02Definitions['items'] as $kode => $definition) {
            $d02Fields[] = "d02_{$kode}_pnbp";
            $d02Fields[] = "d02_{$kode}_jumlah";
        }
        foreach ($d02Definitions['activities'] as $kode => $definition) {
            foreach (['dalam_negeri', 'luar_negeri'] as $wilayah) {
                foreach (['pnbp', 'jumlah', 'keterangan'] as $field) {
                    $d02Fields[] = "d02_{$kode}_{$wilayah}_{$field}";
                }
            }
        }

        return [
            'A.01' => ['tahun', 'kawasan_nama', 'ada_perubahan', 'sk_parsial_nomor', 'sk_parsial_tanggal', 'sk_parsial_luas', 'sk_parsial_file', 'sk_provinsi_tersedia', 'sk_provinsi_nomor', 'sk_provinsi_tanggal', 'sk_provinsi_luas', 'sk_provinsi_file', 'sk_penetapan_tersedia', 'sk_penetapan_nomor', 'sk_penetapan_tanggal', 'sk_penetapan_luas', 'sk_penetapan_file', 'shapefile_zip'],
            'A.02' => ['tahun_rpjp', 'kawasan_nama_rpjp', 'ketersediaan_rpjp', 'sk_rpjp_nomor', 'sk_rpjp_tanggal_pengesahan', 'sk_rpjp_periode_berakhir', 'sk_rpjp_file'],
            'A.03' => ['tahun_monitoring', 'kawasan_nama_monitoring', 'ada_kegiatan_monitoring', 'jenis_kegiatan', 'nomor_batb', 'tanggal_batb', 'pal_baik', 'pal_rusak', 'pal_hilang', 'pal_total', 'panjang_pal_km', 'dokumen_batb', 'shapefile_monitoring_zip'],
            'A.04' => ['tahun_evaluasi', 'kawasan_nama_evaluasi', 'ketersediaan_evaluasi', 'tanggal_pelaksanaan_evaluasi', 'rekomendasi_evaluasi', 'tindak_lanjut_evaluasi', 'file_dokumen_evaluasi'],
            'A.05' => ['tahun_ekosistem', 'kawasan_nama_ekosistem', 'ketersediaan_ekosistem', 'shapefile_ekosistem_zip'],
            'A.06' => ['tahun_zonasi', 'kawasan_nama_zonasi', 'ketersediaan_zonasi', 'nomor_sk_zonasi', 'tanggal_sk_zonasi', 'file_sk_zonasi', 'shapefile_zonasi_zip'],
            'D.01' => [
                'tahun', 'bulan', 'kawasan_nama_d01',
                'penelitian_dalam_negeri', 'penelitian_luar_negeri',
                'pendidikan_dalam_negeri', 'pendidikan_luar_negeri',
                'foto_video_dalam_negeri', 'foto_video_luar_negeri',
                'wisata_alam_dalam_negeri', 'wisata_alam_luar_negeri',
                'lain_lain_dalam_negeri', 'lain_lain_luar_negeri',
            ],
            'D.02' => $d02Fields,
            'B.01' => ['tahun_b01', 'periode_semester', 'kawasan_nama_b01', 'ada_kegiatan_b01', 'nama_kelompok', 'jumlah_laki', 'jumlah_perempuan', 'provinsi', 'kabupaten', 'kecamatan', 'desa', 'hhbk_nihil', 'jenis_hhbk', 'pertanian_nihil', 'jenis_pertanian', 'perkebunan_nihil', 'jenis_perkebunan', 'peternakan_nihil', 'jenis_peternakan', 'perikanan_nihil', 'jenis_perikanan', 'wisata_nihil', 'jenis_wisata', 'produk_nihil', 'jenis_produk', 'pembibitan_nihil', 'jenis_pembibitan', 'lainnya_nihil', 'jenis_lainnya', 'jenis_bantuan', 'jumlah_bantuan', 'sumber_dana'],
            'C.01' => ['satuan_kerja_c01', 'temuan_perjumpaan_spesies_c01', 'tahun_c01', 'bulan_c01', 'spesies_c01', 'spesies_lainnya_c01', 'jumlah_individu_c01', 'tanggal_perjumpaan_c01', 'latitude_c01', 'longitude_c01', 'kategori_perjumpaan_c01', 'keterangan_c01'],
            'C.02' => ['tahun_c02', 'satuan_kerja_c02', 'ada_data_lembaga_c02', 'nama_lembaga_c02', 'bentuk_lembaga_c02', 'latitude_c02', 'longitude_c02', 'alamat_lembaga_c02', 'nomor_dokumen_perizinan_c02', 'luas_areal_c02', 'tanggal_perizinan_c02', 'tanggal_berakhir_izin_c02', 'dokumen_perizinan_c02', 'keterangan_c02'],
            'C.03' => ['tahun_c03', 'periode_semester_c03', 'satuan_kerja_c03', 'ada_data_koleksi_c03', 'lembaga_konservasi_c03', 'spesies_koleksi_c03', 'nama_spesies_lainnya_c03', 'jantan_c03', 'betina_c03', 'belum_diketahui_c03', 'keterangan_c03'],
            'C.04' => ['tahun_c04', 'satuan_kerja_c04', 'ada_data_penangkaran_c04', 'nama_penangkar_c04', 'latitude_c04', 'longitude_c04', 'alamat_penangkaran_c04', 'lokasi_unit_penangkaran_c04', 'nomor_dokumen_perizinan_c04', 'luas_areal_c04', 'tanggal_perizinan_c04', 'tanggal_berakhir_izin_c04', 'dokumen_perizinan_c04', 'keterangan_c04'],
            'C.05' => ['tahun_c05', 'satuan_kerja_c05', 'ada_data_jenis_tsl_c05', 'penangkar_c05', 'spesies_ditangkar_c05', 'nama_spesies_lainnya_c05', 'jumlah_indukan_c05', 'hasil_penangkaran_c05', 'hasil_pemanfaatan_tahun_ini_c05', 'sisa_stok_hasil_pemanfaatan_c05', 'keterangan_c05'],
            'C.06' => ['tahun_c06', 'satuan_kerja_c06', 'ada_data_pengedar_c06', 'nama_pengedar_c06', 'kategori_pengedar_c06', 'latitude_c06', 'longitude_c06', 'lokasi_unit_pengedar_c06', 'nomor_dokumen_perizinan_c06', 'luas_areal_c06', 'tanggal_perizinan_c06', 'tanggal_berakhir_izin_c06', 'dokumen_perizinan_c06', 'spesies_diedarkan_c06', 'keterangan_c06'],
            'C.08' => ['tahun_c08', 'periode_triwulan_c08', 'satuan_kerja_c08', 'ada_realisasi_pengambilan_c08', 'nama_spesies_c08', 'jenis_tsl_c08', 'provinsi_c08', 'realisasi_volume_c08', 'realisasi_satuan_c08', 'keterangan_c08'],
            'C.09' => ['tahun_c09', 'periode_triwulan_c09', 'satuan_kerja_c09', 'ada_realisasi_ekspor_c09', 'penangkar_c09', 'nama_spesies_c09', 'jenis_tsl_c09', 'negara_tujuan_c09', 'realisasi_ekspor_volume_c09', 'realisasi_ekspor_satuan_c09', 'keterangan_c09'],
            'C.10' => ['tahun_c10', 'periode_triwulan_c10', 'satuan_kerja_c10', 'ada_realisasi_ekspor_c10', 'pengedar_c10', 'nama_spesies_c10', 'jenis_tsl_c10', 'negara_tujuan_c10', 'realisasi_ekspor_volume_c10', 'realisasi_ekspor_satuan_c10', 'keterangan_c10'],
            'C.11' => ['tahun_c11', 'bulan_c11', 'satuan_kerja_c11', 'ada_data_sitaan_c11', 'spesies_c11', 'asal_usul_c11', 'jantan_c11', 'betina_c11', 'belum_diketahui_c11', 'keterangan_c11'],
            'C.12' => ['tahun_c12', 'bulan_c12', 'satuan_kerja_c12', 'ada_pnb_tsl_c12', 'akun_pnb_c12', 'realisasi_pnb_c12', 'keterangan_c12'],
            'C.14' => ['tahun_c14', 'bulan_c14', 'satuan_kerja_c14', 'ada_interaksi_negatif_c14', 'spesies_c14', 'spesies_lainnya_c14', 'tanggal_kejadian_c14', 'jumlah_individu_c14', 'satwa_mati_c14', 'latitude_c14', 'longitude_c14', 'meninggal_c14', 'cedera_c14', 'kerusakan_kebun_c14', 'kerusakan_bangunan_c14', 'kambing_c14', 'sapi_c14', 'kerbau_c14', 'anjing_c14', 'babi_c14', 'unggas_c14', 'taksiran_kerugian_c14', 'upaya_penanggulangan_c14', 'keterangan_c14'],
        ];
    }

    private function d02Definitions(): array
    {
        return [
            'items' => [
                'dalam_negeri_hari_kerja' => ['group' => 'Tiket Masuk Pengunjung', 'label' => 'Dalam Negeri (Hari Kerja)', 'unit' => 'Orang'],
                'luar_negeri_hari_kerja' => ['group' => 'Tiket Masuk Pengunjung', 'label' => 'Luar Negeri (Hari Kerja)', 'unit' => 'Orang'],
                'pelajar_hari_kerja' => ['group' => 'Tiket Masuk Pengunjung', 'label' => 'Pelajar/Mahasiswa (Hari Kerja)', 'unit' => 'Orang'],
                'dalam_negeri_hari_libur' => ['group' => 'Tiket Masuk Pengunjung', 'label' => 'Dalam Negeri (Hari Libur/Cuti Bersama/Hari Raya)', 'unit' => 'Orang'],
                'pelajar_hari_libur' => ['group' => 'Tiket Masuk Pengunjung', 'label' => 'Pelajar/Mahasiswa (Hari Libur/Cuti Bersama/Hari Raya)', 'unit' => 'Orang'],
                'roda_2' => ['group' => 'Tiket Masuk Kendaraan Darat', 'label' => 'Roda 2', 'unit' => 'Unit'],
                'roda_4' => ['group' => 'Tiket Masuk Kendaraan Darat', 'label' => 'Roda 4', 'unit' => 'Unit'],
                'roda_6' => ['group' => 'Tiket Masuk Kendaraan Darat', 'label' => 'Roda 6 atau Lebih', 'unit' => 'Unit'],
                'sepeda' => ['group' => 'Tiket Masuk Kendaraan Darat', 'label' => 'Sepeda', 'unit' => 'Unit'],
                'kuda' => ['group' => 'Tiket Masuk Kendaraan Darat', 'label' => 'Kuda', 'unit' => 'Ekor'],
                'kapal_40_100' => ['group' => 'Tiket Masuk Kendaraan Air', 'label' => 'Kapal Motor 40 s.d. 100 PK', 'unit' => 'Unit'],
                'kapal_101_500' => ['group' => 'Tiket Masuk Kendaraan Air', 'label' => 'Kapal Motor 101 s.d. 500 PK', 'unit' => 'Unit'],
                'kapal_diatas_500' => ['group' => 'Tiket Masuk Kendaraan Air', 'label' => 'Kapal Motor diatas 500 PK', 'unit' => 'Unit'],
                'kapal_pesiar' => ['group' => 'Tiket Masuk Kendaraan Air', 'label' => 'Kapal Pesiar/Cruiser Ship', 'unit' => 'Unit'],
                'transportasi_khusus' => ['group' => 'Tiket Masuk Kendaraan Transportasi Khusus', 'label' => 'Transportasi Khusus', 'unit' => 'Unit'],
                'berkemah' => ['group' => 'Kegiatan Wisata Alam', 'label' => 'Berkemah', 'unit' => 'Orang'],
                'mendaki' => ['group' => 'Kegiatan Wisata Alam', 'label' => 'Mendaki Gunung (Hiking-Climbing)', 'unit' => 'Orang'],
                'gua' => ['group' => 'Kegiatan Wisata Alam', 'label' => 'Penelusuran Gua (Caving)', 'unit' => 'Orang'],
                'memancing' => ['group' => 'Kegiatan Wisata Alam', 'label' => 'Memancing (Sport Fishing)', 'unit' => 'Orang'],
                'menyelam' => ['group' => 'Kegiatan Wisata Alam', 'label' => 'Menyelam (Scuba Diving)', 'unit' => 'Orang'],
                'arung_jeram' => ['group' => 'Kegiatan Wisata Alam', 'label' => 'Arung Jeram (Tubbing)', 'unit' => 'Orang'],
                'paralayang' => ['group' => 'Kegiatan Wisata Alam', 'label' => 'Paralayang', 'unit' => 'Orang'],
                'balon_udara' => ['group' => 'Kegiatan Wisata Alam', 'label' => 'Balon Udara', 'unit' => 'Orang'],
            ],
            'activities' => [
                'videografi_komersil' => ['group' => 'Pengambilan Gambar Komersil', 'label' => 'Videografi untuk iklan, video clip, film, drama, dan sejenisnya'],
                'fotografi_komersil' => ['group' => 'Pengambilan Gambar Komersil', 'label' => 'Fotografi untuk paket wisata, majalah, iklan, dan sejenisnya'],
                'video_foto_pre_wedding' => ['group' => 'Video dan Foto Prewedding', 'label' => 'Video dan Foto Prewedding'],
                'penggunaan_drone' => ['group' => 'Kegiatan Penggunaan/Menerbangkan Drone', 'label' => 'Penggunaan/Menerbangkan Drone'],
                'penggunaan_fasilitas_wisata' => ['group' => 'Penggunaan Fasilitas untuk Kegiatan Wisata', 'label' => 'Penggunaan Fasilitas untuk Kegiatan Wisata'],
                'denda_pengunjung_kendaraan' => ['group' => 'Denda Pengunjung dan Kendaraan Ilegal/Tidak Memiliki Tiket Masuk', 'label' => 'Penunjang Dalam Negeri dan Luar Negeri'],
            ],
        ];
    }

    private function formFieldLabel(string $field): string
    {
        $d02Definitions = $this->d02Definitions();
        if (preg_match('/^d02_(.+)_(pnbp|jumlah)$/', $field, $match)
            && isset($d02Definitions['items'][$match[1]])) {
            $definition = $d02Definitions['items'][$match[1]];
            $metric = $match[2] === 'pnbp' ? 'Jumlah PNBP' : 'Jumlah ' . $definition['unit'];

            return $definition['group'] . ' - ' . $definition['label'] . ' - ' . $metric;
        }

        if (preg_match('/^d02_(.+)_(dalam_negeri|luar_negeri)_(pnbp|jumlah|keterangan)$/', $field, $match)
            && isset($d02Definitions['activities'][$match[1]])) {
            $definition = $d02Definitions['activities'][$match[1]];
            $wilayah = $match[2] === 'dalam_negeri' ? 'Dalam Negeri' : 'Luar Negeri';
            $metric = match ($match[3]) {
                'pnbp' => 'Jumlah PNBP',
                'jumlah' => 'Jumlah Orang',
                default => 'Keterangan',
            };

            return $definition['group'] . ' - ' . $definition['label'] . ' - ' . $wilayah . ' - ' . $metric;
        }

        $labels = [
            'tahun' => 'Tahun', 'tahun_rpjp' => 'Tahun', 'tahun_monitoring' => 'Tahun',
            'tahun_evaluasi' => 'Tahun', 'tahun_ekosistem' => 'Tahun', 'tahun_zonasi' => 'Tahun', 'tahun_b01' => 'Tahun',
            'bulan' => 'Periode Bulan',
            'kawasan_nama' => 'Kawasan Konservasi', 'kawasan_nama_rpjp' => 'Kawasan Konservasi',
            'kawasan_nama_monitoring' => 'Kawasan Konservasi', 'kawasan_nama_evaluasi' => 'Kawasan Konservasi',
            'kawasan_nama_ekosistem' => 'Kawasan Konservasi', 'kawasan_nama_zonasi' => 'Kawasan Konservasi',
            'kawasan_nama_b01' => 'Kawasan Konservasi', 'jumlah_laki' => 'Jumlah Laki-laki',
            'kawasan_nama_d01' => 'Kawasan Konservasi',
            'penelitian_dalam_negeri' => 'Penelitian & Pengembangan - Dalam Negeri',
            'penelitian_luar_negeri' => 'Penelitian & Pengembangan - Luar Negeri',
            'pendidikan_dalam_negeri' => 'Pendidikan & Ilmu Pengetahuan - Dalam Negeri',
            'pendidikan_luar_negeri' => 'Pendidikan & Ilmu Pengetahuan - Luar Negeri',
            'foto_video_dalam_negeri' => 'Pengambilan Foto & Video - Dalam Negeri',
            'foto_video_luar_negeri' => 'Pengambilan Foto & Video - Luar Negeri',
            'wisata_alam_dalam_negeri' => 'Wisata Alam - Dalam Negeri',
            'wisata_alam_luar_negeri' => 'Wisata Alam - Luar Negeri',
            'lain_lain_dalam_negeri' => 'Lain-lain (Keagamaan/Kunjungan Kedinasan) - Dalam Negeri',
            'lain_lain_luar_negeri' => 'Lain-lain (Keagamaan/Kunjungan Kedinasan) - Luar Negeri',
            'jumlah_perempuan' => 'Jumlah Perempuan', 'jenis_hhbk' => 'Pemungutan HHBK',
            'jenis_pertanian' => 'Pertanian', 'jenis_perkebunan' => 'Perkebunan',
            'jenis_peternakan' => 'Peternakan', 'jenis_perikanan' => 'Perikanan',
            'jenis_wisata' => 'Jasa Wisata', 'jenis_produk' => 'Usaha Penghasil Produk',
            'jenis_pembibitan' => 'Pembibitan', 'jenis_lainnya' => 'Jenis Usaha Lainnya',
            'satuan_kerja_c01' => 'Satuan Kerja', 'temuan_perjumpaan_spesies_c01' => 'Temuan Perjumpaan Spesies',
            'tahun_c01' => 'Tahun', 'bulan_c01' => 'Periode Bulan',
            'tahun_c02' => 'Tahun', 'satuan_kerja_c02' => 'Satuan Kerja', 'ada_data_lembaga_c02' => 'Ada Data Lembaga Konservasi',
            'tahun_c03' => 'Tahun', 'periode_semester_c03' => 'Periode Semester',
            'satuan_kerja_c03' => 'Satuan Kerja', 'ada_data_koleksi_c03' => 'Ada Data Koleksi TSL',
            'tahun_c04' => 'Tahun', 'satuan_kerja_c04' => 'Satuan Kerja', 'ada_data_penangkaran_c04' => 'Ada Data Penangkaran',
            'nama_penangkar_c04' => 'Nama Penangkar', 'latitude_c04' => 'Koordinat X', 'longitude_c04' => 'Koordinat Y',
            'alamat_penangkaran_c04' => 'Alamat Penangkaran', 'lokasi_unit_penangkaran_c04' => 'Lokasi Unit Penangkaran',
            'nomor_dokumen_perizinan_c04' => 'Nomor Dokumen Perizinan', 'luas_areal_c04' => 'Luas Areal',
            'tanggal_perizinan_c04' => 'Tanggal Perizinan', 'tanggal_berakhir_izin_c04' => 'Tanggal Berakhir Izin',
            'keterangan_c04' => 'Keterangan',
            'tahun_c05' => 'Tahun', 'satuan_kerja_c05' => 'Satuan Kerja', 'ada_data_jenis_tsl_c05' => 'Ada Jenis TSL yang Ditangkarkan',
            'penangkar_c05' => 'Nama Penangkar', 'spesies_ditangkar_c05' => 'Spesies yang Ditangkarkan',
            'nama_spesies_lainnya_c05' => 'Nama Spesies Lainnya', 'jumlah_indukan_c05' => 'Jumlah Indukan',
            'hasil_penangkaran_c05' => 'Hasil Penangkaran', 'hasil_pemanfaatan_tahun_ini_c05' => 'Hasil Pemanfaatan Tahun Ini',
            'sisa_stok_hasil_pemanfaatan_c05' => 'Sisa Stok Hasil Pemanfaatan', 'keterangan_c05' => 'Keterangan',
            'tahun_c06' => 'Tahun', 'satuan_kerja_c06' => 'Satuan Kerja', 'ada_data_pengedar_c06' => 'Ada Data Pengedar TSL',
            'nama_pengedar_c06' => 'Nama Pengedar TSL', 'kategori_pengedar_c06' => 'Kategori Pengedar',
            'latitude_c06' => 'Koordinat X', 'longitude_c06' => 'Koordinat Y', 'lokasi_unit_pengedar_c06' => 'Lokasi Unit Pengedar TSL',
            'nomor_dokumen_perizinan_c06' => 'Nomor Dokumen Perizinan', 'luas_areal_c06' => 'Luas Areal',
            'tanggal_perizinan_c06' => 'Tanggal Perizinan', 'tanggal_berakhir_izin_c06' => 'Tanggal Berakhir Izin',
            'spesies_diedarkan_c06' => 'Spesies yang Diedarkan', 'keterangan_c06' => 'Keterangan',
            'tahun_c08' => 'Tahun', 'periode_triwulan_c08' => 'Periode Triwulan',
            'satuan_kerja_c08' => 'Satuan Kerja', 'ada_realisasi_pengambilan_c08' => 'Ada Realisasi Pengambilan/Penangkapan TSL',
            'nama_spesies_c08' => 'Nama Species', 'jenis_tsl_c08' => 'Jenis', 'provinsi_c08' => 'Provinsi',
            'realisasi_volume_c08' => 'Realisasi Tangkap Volume', 'realisasi_satuan_c08' => 'Realisasi Tangkap Satuan',
            'keterangan_c08' => 'Keterangan',
            'tahun_c09' => 'Tahun', 'periode_triwulan_c09' => 'Periode Triwulan',
            'satuan_kerja_c09' => 'Satuan Kerja', 'ada_realisasi_ekspor_c09' => 'Ada Realisasi Ekspor TSL Penangkaran',
            'penangkar_c09' => 'Nama Penangkar', 'nama_spesies_c09' => 'Nama Spesies',
            'jenis_tsl_c09' => 'Jenis', 'negara_tujuan_c09' => 'Negara Tujuan',
            'realisasi_ekspor_volume_c09' => 'Realisasi Ekspor Volume', 'realisasi_ekspor_satuan_c09' => 'Realisasi Ekspor Satuan',
            'keterangan_c09' => 'Keterangan',
            'tahun_c10' => 'Tahun', 'periode_triwulan_c10' => 'Periode Triwulan',
            'satuan_kerja_c10' => 'Satuan Kerja', 'ada_realisasi_ekspor_c10' => 'Ada Realisasi Ekspor TSL Pengambilan dari Alam',
            'pengedar_c10' => 'Nama Pengedar TSL', 'nama_spesies_c10' => 'Nama Spesies',
            'jenis_tsl_c10' => 'Jenis', 'negara_tujuan_c10' => 'Negara Tujuan',
            'realisasi_ekspor_volume_c10' => 'Realisasi Ekspor Volume', 'realisasi_ekspor_satuan_c10' => 'Realisasi Ekspor Satuan',
            'keterangan_c10' => 'Keterangan',
            'tahun_c11' => 'Tahun', 'bulan_c11' => 'Periode Bulan',
            'satuan_kerja_c11' => 'Satuan Kerja', 'ada_data_sitaan_c11' => 'Ada Data Sitaan/Penyerahan/Penyelamatan Satwa',
            'spesies_c11' => 'Spesies', 'asal_usul_c11' => 'Asal-usul',
            'jantan_c11' => 'Jantan', 'betina_c11' => 'Betina',
            'belum_diketahui_c11' => 'Belum Diketahui', 'keterangan_c11' => 'Keterangan',
            'tahun_c12' => 'Tahun', 'bulan_c12' => 'Periode Bulan',
            'satuan_kerja_c12' => 'Satuan Kerja', 'ada_pnb_tsl_c12' => 'Ada PNB Pemanfaatan TSL',
            'akun_pnb_c12' => 'Akun PNB', 'realisasi_pnb_c12' => 'Realisasi PNB', 'keterangan_c12' => 'Keterangan',
            'tahun_c14' => 'Tahun', 'bulan_c14' => 'Periode Bulan', 'satuan_kerja_c14' => 'Satuan Kerja',
            'ada_interaksi_negatif_c14' => 'Ada Interaksi Negatif Satwa Liar dan Manusia',
            'spesies_c14' => 'Spesies yang Terlibat Interaksi Negatif', 'spesies_lainnya_c14' => 'Spesies Lainnya',
            'tanggal_kejadian_c14' => 'Tanggal Kejadian', 'jumlah_individu_c14' => 'Jumlah Individu',
            'satwa_mati_c14' => 'Satwa Mati', 'latitude_c14' => 'Koordinat X', 'longitude_c14' => 'Koordinat Y',
            'meninggal_c14' => 'Meninggal', 'cedera_c14' => 'Cedera',
            'kerusakan_kebun_c14' => 'Kerusakan Kebun/Lahan', 'kerusakan_bangunan_c14' => 'Kerusakan Bangunan',
            'kambing_c14' => 'Kambing', 'sapi_c14' => 'Sapi', 'kerbau_c14' => 'Kerbau',
            'anjing_c14' => 'Anjing', 'babi_c14' => 'Babi', 'unggas_c14' => 'Unggas',
            'taksiran_kerugian_c14' => 'Taksiran Kerugian', 'upaya_penanggulangan_c14' => 'Upaya Penanggulangan',
            'keterangan_c14' => 'Keterangan',
            'lembaga_konservasi_c03' => 'Nama Lembaga Konservasi', 'spesies_koleksi_c03' => 'Spesies Koleksi',
            'nama_spesies_lainnya_c03' => 'Nama Spesies Lainnya', 'jantan_c03' => 'Jantan',
            'betina_c03' => 'Betina', 'belum_diketahui_c03' => 'Belum Diketahui', 'keterangan_c03' => 'Keterangan',
            'nama_lembaga_c02' => 'Nama Lembaga Konservasi', 'bentuk_lembaga_c02' => 'Bentuk Lembaga',
            'latitude_c02' => 'Koordinat X', 'longitude_c02' => 'Koordinat Y',
            'alamat_lembaga_c02' => 'Alamat Lembaga Konservasi', 'nomor_dokumen_perizinan_c02' => 'Nomor Dokumen Perizinan',
            'luas_areal_c02' => 'Luas Areal', 'tanggal_perizinan_c02' => 'Tanggal Perizinan',
            'tanggal_berakhir_izin_c02' => 'Tanggal Berakhir Izin', 'keterangan_c02' => 'Keterangan',
            'spesies_c01' => 'Spesies', 'spesies_lainnya_c01' => 'Nama Spesies Lainnya',
            'jumlah_individu_c01' => 'Jumlah Individu', 'tanggal_perjumpaan_c01' => 'Tanggal Perjumpaan',
            'latitude_c01' => 'Koordinat X', 'longitude_c01' => 'Koordinat Y',
            'kategori_perjumpaan_c01' => 'Kategori Perjumpaan', 'keterangan_c01' => 'Keterangan',
        ];
        $label = $labels[$field] ?? ucwords(str_replace('_', ' ', $field));

        return str_replace(
            ['Sk ', 'Rpjp', 'Batb', 'Hhbk', 'Zip'],
            ['SK ', 'RPJP', 'BATB', 'HHBK', 'ZIP'],
            $label
        );
    }

    private function parseDetailValues(?string $keterangan, array $fields): array
    {
        $values = [];

        foreach (explode(' | ', $keterangan ?? '') as $detail) {
            $separator = strpos($detail, ': ');
            if ($separator === false) {
                continue;
            }

            $label = trim(substr($detail, 0, $separator));
            $value = trim(substr($detail, $separator + 2));

            foreach ($fields as $field) {
                if (strcasecmp($label, $this->formFieldLabel($field)) === 0) {
                    $values[$field] = $value;
                    break;
                }
            }
        }

        if ($values === [] && filled($keterangan) && !str_contains($keterangan, ': ')) {
            $values['keterangan'] = $keterangan;
        }

        return $values;
    }

    // Proses Simpan Data
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
            'dokumen_perizinan_c02'     => 'nullable|mimes:pdf|max:2048',
            'dokumen_perizinan_c04'     => 'nullable|mimes:pdf|max:2048',
            'dokumen_perizinan_c06'     => 'nullable|mimes:pdf|max:2048',
        ]);

        $subBidang = SubBidang::findOrFail($request->sub_bidang_id);
        $yearFields = [
            'A.01' => 'tahun',
            'A.02' => 'tahun_rpjp',
            'A.03' => 'tahun_monitoring',
            'A.04' => 'tahun_evaluasi',
            'A.05' => 'tahun_ekosistem',
            'A.06' => 'tahun_zonasi',
            'D.02' => 'tahun',
            'B.01' => 'tahun_b01',
            'C.01' => 'tahun_c01',
            'C.02' => 'tahun_c02',
            'C.03' => 'tahun_c03',
            'C.04' => 'tahun_c04',
            'C.05' => 'tahun_c05',
            'C.06' => 'tahun_c06',
            'C.08' => 'tahun_c08',
            'C.09' => 'tahun_c09',
            'C.10' => 'tahun_c10',
            'C.11' => 'tahun_c11',
            'C.12' => 'tahun_c12',
            'C.14' => 'tahun_c14',
        ];
        $tahun = $request->input($yearFields[$subBidang->kode_sub] ?? '', date('Y'));

        $fieldsBySubBidang = $this->fieldsBySubBidang();
        $jumlah = match ($subBidang->kode_sub) {
            'D.01' => array_sum(array_map(
                fn ($field) => (int) $request->input($field, 0),
                [
                    'penelitian_dalam_negeri', 'penelitian_luar_negeri',
                    'pendidikan_dalam_negeri', 'pendidikan_luar_negeri',
                    'foto_video_dalam_negeri', 'foto_video_luar_negeri',
                    'wisata_alam_dalam_negeri', 'wisata_alam_luar_negeri',
                    'lain_lain_dalam_negeri', 'lain_lain_luar_negeri',
                ]
            )),
            'A.03' => $request->filled('pal_baik') || $request->filled('pal_rusak') || $request->filled('pal_hilang')
                ? (int) $request->input('pal_baik', 0) + (int) $request->input('pal_rusak', 0) + (int) $request->input('pal_hilang', 0)
                : null,
            'B.01' => $request->filled('jumlah_laki') || $request->filled('jumlah_perempuan')
                ? (int) $request->input('jumlah_laki', 0) + (int) $request->input('jumlah_perempuan', 0)
                : null,
            'C.03' => $request->input('ada_data_koleksi_c03') === 'ya'
                ? (int) $request->input('jantan_c03', 0) + (int) $request->input('betina_c03', 0) + (int) $request->input('belum_diketahui_c03', 0)
                : null,
            'C.05' => $request->input('ada_data_jenis_tsl_c05') === 'ya'
                ? (int) $request->input('jumlah_indukan_c05', 0) + (int) $request->input('hasil_penangkaran_c05', 0) + (int) $request->input('hasil_pemanfaatan_tahun_ini_c05', 0) + (int) $request->input('sisa_stok_hasil_pemanfaatan_c05', 0)
                : null,
            'C.11' => $request->input('ada_data_sitaan_c11') === 'ya'
                ? (int) $request->input('jantan_c11', 0) + (int) $request->input('betina_c11', 0) + (int) $request->input('belum_diketahui_c11', 0)
                : null,
            'C.12' => $request->input('ada_pnb_tsl_c12') === 'ya'
                ? (int) $request->input('realisasi_pnb_c12', 0)
                : null,
            'C.14' => $request->input('ada_interaksi_negatif_c14') === 'ya'
                ? (int) $request->input('jumlah_individu_c14', 0)
                : null,
            default => null,
        };

        if ($subBidang->kode_sub === 'D.02') {
            $jumlah = array_sum(array_map(
                fn ($field) => (int) $request->input($field, 0),
                array_filter($fieldsBySubBidang['D.02'], fn ($field) => str_ends_with($field, '_jumlah'))
            ));
        }

        $details = [];
        $activeFields = array_merge($fieldsBySubBidang[$subBidang->kode_sub] ?? [], ['keterangan']);
        foreach ($request->only($activeFields) as $field => $value) {
            if (!is_scalar($value) || $value === '') {
                continue;
            }

            $label = $this->formFieldLabel($field);
            $details[] = $label . ': ' . ($value === 'on' ? 'Ya' : $value);
        }

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
            'dokumen_perizinan_c02'     => 'dokumen_perizinan',
            'dokumen_perizinan_c04'     => 'dokumen_perizinan',
            'dokumen_perizinan_c06'     => 'dokumen_perizinan',
        ];

        foreach ($fileInputs as $inputName => $folderPath) {
            if (in_array($inputName, $fieldsBySubBidang[$subBidang->kode_sub] ?? [], true) && $request->hasFile($inputName)) {
                $storedPath = $request->file($inputName)->store($folderPath, 'public');
                $details[] = ucwords(str_replace('_', ' ', $inputName)) . ': ' . $storedPath;
            }
        }

        $keteranganFinal = count($details) > 0 ? implode(' | ', $details) : '-';

        $payload = [
            'sub_bidang_id' => $request->sub_bidang_id,
            'tahun'         => $tahun,
            'bulan'         => $request->input('bulan_c14', $request->input('bulan_c12', $request->input('bulan_c11', $request->input('bulan_c01', $request->bulan)))),
            'latitude'      => $request->input('latitude_c14', $request->input('latitude_c06', $request->input('latitude_c04', $request->input('latitude_c02', $request->input('latitude_c01', $request->latitude))))),
            'longitude'     => $request->input('longitude_c14', $request->input('longitude_c06', $request->input('longitude_c04', $request->input('longitude_c02', $request->input('longitude_c01', $request->longitude))))),
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

        $allData = (clone $userQuery)->with('subBidang.bidang')->get();
        $secretariatRecords = Rekap::where('kode_sub_bidang', 'like', 'F.%')->get();
        $secretariatSubBidangs = SubBidang::with('bidang')
            ->whereIn('kode_sub', $secretariatRecords->pluck('kode_sub_bidang')->unique())
            ->get()
            ->keyBy('kode_sub');

        $totalData = $allData->count() + $secretariatRecords->count();
        $totalVolume = (int) $allData->sum('jumlah') + $secretariatRecords->sum(
            fn ($record) => $this->sumSecretariatCounts($record->data_kustom ?? [])
        );
        $totalLokasi = (clone $userQuery)->whereNotNull('latitude')
                                         ->whereNotNull('longitude')
                                         ->where('latitude', '!=', '')
                                         ->where('longitude', '!=', '')
                                         ->count();

        $totalKawasan = (clone $userQuery)->distinct('sub_bidang_id')->count('sub_bidang_id')
            + $secretariatRecords->pluck('kode_sub_bidang')->unique()->count();

        $statsVolume = [];
        foreach ($allData as $item) {
            $namaBidang = $item->subBidang->bidang->nama_bidang ?? 'Lainnya';
            if (!isset($statsVolume[$namaBidang])) {
                $statsVolume[$namaBidang] = 0;
            }
            $statsVolume[$namaBidang] += ($item->jumlah > 0 ? $item->jumlah : 1);
        }

        foreach ($secretariatRecords as $record) {
            $namaBidang = $secretariatSubBidangs->get($record->kode_sub_bidang)?->bidang->nama_bidang ?? 'Kesekretariatan';
            $jumlah = $this->sumSecretariatCounts($record->data_kustom ?? []);
            $statsVolume[$namaBidang] = ($statsVolume[$namaBidang] ?? 0) + ($jumlah > 0 ? $jumlah : 1);
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
        $fieldsBySubBidang = $this->fieldsBySubBidang();
        $currentKode = $item->subBidang->kode_sub ?? '';
        $currentFields = array_merge($fieldsBySubBidang[$currentKode] ?? [], ['keterangan']);
        $detailValues = $this->parseDetailValues($item->keterangan, $currentFields);
        $editFieldsBySubBidang = [];

        foreach ($fieldsBySubBidang as $kode => $fields) {
            if ($kode === 'D.02') {
                $fields = array_values(array_diff($fields, ['bulan']));
            }
            $editFieldsBySubBidang[$kode] = array_map(fn ($field) => [
                'name' => $field,
                'label' => $this->formFieldLabel($field),
            ], $fields);
        }

        return view('konservasi.edit', compact(
            'item', 'bidangs', 'subBidangs', 'currentBidangId', 'currentKode',
            'detailValues', 'editFieldsBySubBidang'
        ));
    }

    // Proses Update Data
    public function update(Request $request, $id)
    {
        $request->validate([
            'bidang_id'     => 'required|exists:ref_bidang,id',
            'sub_bidang_id' => 'required|exists:ref_sub_bidang,id',
            'tahun'         => 'nullable|numeric',
            'tahun_rpjp'    => 'nullable|numeric',
            'tahun_monitoring' => 'nullable|numeric',
            'tahun_evaluasi' => 'nullable|numeric',
            'tahun_ekosistem' => 'nullable|numeric',
            'tahun_zonasi'  => 'nullable|numeric',
            'tahun_b01'     => 'nullable|numeric',
            'sk_parsial_file' => 'nullable|mimes:pdf|max:2048',
            'sk_provinsi_file' => 'nullable|mimes:pdf|max:2048',
            'sk_penetapan_file' => 'nullable|mimes:pdf|max:2048',
            'shapefile_zip' => 'nullable|mimes:zip|max:10240',
            'sk_rpjp_file'  => 'nullable|mimes:pdf|max:20480',
            'dokumen_batb'  => 'nullable|mimes:pdf|max:20480',
            'shapefile_monitoring_zip' => 'nullable|mimes:zip|max:10240',
            'file_dokumen_evaluasi' => 'nullable|mimes:pdf|max:20480',
            'shapefile_ekosistem_zip' => 'nullable|mimes:zip|max:10240',
            'file_sk_zonasi' => 'nullable|mimes:pdf|max:2048',
            'shapefile_zonasi_zip' => 'nullable|mimes:zip|max:10240',
        ]);

        $query = DataKonservasi::query();

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        $item = $query->findOrFail($id);

        $subBidang = SubBidang::findOrFail($request->sub_bidang_id);
        if ((int) $subBidang->bidang_id !== (int) $request->bidang_id) {
            return back()->withErrors(['sub_bidang_id' => 'Sub-bidang tidak sesuai dengan bidang yang dipilih.'])->withInput();
        }

        $fieldsBySubBidang = $this->fieldsBySubBidang();
        $fields = $fieldsBySubBidang[$subBidang->kode_sub] ?? [];
        $existingCode = $item->subBidang->kode_sub ?? '';
        $detailValues = $existingCode === $subBidang->kode_sub
            ? $this->parseDetailValues($item->keterangan, $fields)
            : [];
        $details = [];

        foreach ($request->only(array_merge($fields, ['keterangan'])) as $field => $value) {
            if (!is_scalar($value) || $value === '') {
                continue;
            }

            $details[] = $this->formFieldLabel($field) . ': ' . ($value === 'on' ? 'Ya' : $value);
        }

        $fileInputs = [
            'sk_parsial_file' => 'dokumen_sk',
            'sk_provinsi_file' => 'dokumen_sk',
            'sk_penetapan_file' => 'dokumen_sk',
            'shapefile_zip' => 'shapefiles',
            'sk_rpjp_file' => 'dokumen_rpjp',
            'dokumen_batb' => 'dokumen_batb',
            'shapefile_monitoring_zip' => 'shapefiles',
            'file_dokumen_evaluasi' => 'dokumen_evaluasi',
            'shapefile_ekosistem_zip' => 'shapefiles',
            'file_sk_zonasi' => 'dokumen_zonasi',
            'shapefile_zonasi_zip' => 'shapefiles',
        ];

        foreach ($fileInputs as $field => $folder) {
            if (!in_array($field, $fields, true)) {
                continue;
            }

            if ($request->hasFile($field)) {
                $detailValues[$field] = $request->file($field)->store($folder, 'public');
            }

            if (!empty($detailValues[$field])) {
                $details[] = $this->formFieldLabel($field) . ': ' . $detailValues[$field];
            }
        }

        $yearFields = [
            'A.01' => 'tahun', 'A.02' => 'tahun_rpjp', 'A.03' => 'tahun_monitoring',
            'A.04' => 'tahun_evaluasi', 'A.05' => 'tahun_ekosistem', 'A.06' => 'tahun_zonasi',
            'B.01' => 'tahun_b01', 'D.02' => 'tahun',
        ];
        $yearField = $yearFields[$subBidang->kode_sub] ?? null;
        $year = $yearField && $request->filled($yearField)
            ? $request->input($yearField)
            : ($request->filled('tahun') ? $request->input('tahun') : $item->tahun);
        $jumlah = match ($subBidang->kode_sub) {
            'D.02' => array_sum(array_map(
                fn ($field) => (int) $request->input($field, 0),
                array_filter($fields, fn ($field) => str_ends_with($field, '_jumlah'))
            )),
            'A.03' => $request->filled('pal_baik') || $request->filled('pal_rusak') || $request->filled('pal_hilang')
                ? (int) $request->input('pal_baik', 0) + (int) $request->input('pal_rusak', 0) + (int) $request->input('pal_hilang', 0)
                : null,
            'B.01' => $request->filled('jumlah_laki') || $request->filled('jumlah_perempuan')
                ? (int) $request->input('jumlah_laki', 0) + (int) $request->input('jumlah_perempuan', 0)
                : null,
            default => $request->input('jumlah'),
        };

        $item->update([
            'sub_bidang_id' => $subBidang->id,
            'tahun'         => $year,
            'bulan'         => $request->bulan,
            'latitude'      => $request->input('latitude', $item->latitude),
            'longitude'     => $request->input('longitude', $item->longitude),
            'jumlah'        => $jumlah,
            'keterangan'    => count($details) ? implode(' | ', $details) : '-',
        ]);

        return redirect()->route('konservasi.index')->with('success', 'Data konservasi berhasil diperbarui!');
    }
}