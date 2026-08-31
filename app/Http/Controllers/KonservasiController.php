<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\SubBidang;
use App\Models\DataKonservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class KonservasiController extends Controller
{
    // Halaman Rekapitulasi Data
    public function index(Request $request)
    {
        $query = DataKonservasi::with('subBidang.bidang')->latest();

        // Filter berdasarkan user_id jika kolomnya ada di database
        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        // Fitur Pencarian / Filter Sederhana
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('subBidang', function ($subQ) use ($search) {
                    $subQ->where('nama_sub_bidang', 'like', "%{$search}%");
                })->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $data = $query->paginate(10);
        return view('konservasi.index', compact('data'));
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
            'jumlah'        => $request->jumlah,
            'keterangan'    => $request->keterangan,
        ];

        // Masukkan user_id jika kolom tersedia di database
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
        $totalLokasi = (clone $userQuery)->whereNotNull('latitude')->whereNotNull('longitude')->count();

        // 1. Ambil data konservasi beserta relasi bidangnya
        $allData = (clone $userQuery)->with('subBidang.bidang')->get();

        // 2. Kelompokkan jumlah entri data berdasarkan nama Bidang
        $stats = [];
        foreach ($allData as $item) {
            $namaBidang = $item->subBidang->bidang->nama_bidang ?? 'Lainnya';
            if (!isset($stats[$namaBidang])) {
                $stats[$namaBidang] = 0;
            }
            $stats[$namaBidang]++;
        }

        // 3. Pisahkan label dan nilainya untuk Chart.js
        $chartLabels = array_keys($stats);
        $chartData = array_values($stats);

        $recentData = (clone $userQuery)->with('subBidang.bidang')->latest()->take(5)->get();

        return view('konservasi.dashboard', compact(
            'totalData', 'totalVolume', 'totalLokasi', 'recentData', 'chartLabels', 'chartData'
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

        $item->update($request->only([
            'sub_bidang_id',
            'tahun',
            'bulan',
            'latitude',
            'longitude'
        ]));

        return redirect()->route('konservasi.index')->with('success', 'Data konservasi berhasil diperbarui!');
    }
}