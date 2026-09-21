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
                // 1. Cari berdasarkan Keterangan / Satwa / Catatan
                $q->where('keterangan', 'like', "%{$search}%")
                  // 2. Cari berdasarkan Tahun atau Jumlah
                  ->orWhere('tahun', 'like', "%{$search}%")
                  ->orWhere('jumlah', 'like', "%{$search}%")
                  // 3. Cari berdasarkan Bulan (berupa Angka)
                  ->orWhere('bulan', 'like', "%{$search}%");

                if ($monthNumber) {
                    $q->orWhere('bulan', $monthNumber)
                      ->orWhere('bulan', (int)$monthNumber);
                }

                // 4. Cari berdasarkan Nama Sub-Bidang & Bidang Utama
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

    // Fitur Unduh Excel (CSV/Excel Native Response)
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
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header Kolom Excel
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
        return view('konservasi.create', compact('bidang'));
    }

    public function getSubBidang($bidang_id)
    {
        $subBidang = SubBidang::where('bidang_id', $bidang_id)->get();
        return response()->json($subBidang);
    }

    // Proses Simpan Data
    public function store(Request $request)
    {
        $request->validate([
            'sub_bidang_id' => 'required|exists:ref_sub_bidang,id',
            'tahun'         => 'required|numeric',
        ]);

        $payload = [
            'sub_bidang_id' => $request->sub_bidang_id,
            'tahun'         => $request->tahun,
            'bulan'         => $request->bulan,
            'latitude'      => $request->latitude,
            'longitude'     => $request->longitude,
            'jumlah'        => $request->jumlah ?? 0,
            'keterangan'    => $request->keterangan,
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