<?php

namespace App\Http\Controllers;

use App\Models\Rekap;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    // Tampilkan Halaman Rekap berdasarkan Kode Sub-Bidang
    public function index($kode)
    {
        $data = Rekap::where('kode_sub_bidang', $kode)->get();
        return view('rekaps.index', compact('data', 'kode'));
    }

    // Simpan Data Rekap (Baku + Dinamis JSON)
    public function store(Request $request)
    {
        $request->validate([
            'kode_sub_bidang' => 'required',
            'tahun'           => 'required',
            'kawasan'         => 'required',
        ]);

        Rekap::create([
            'kode_sub_bidang' => $request->kode_sub_bidang,
            'tahun'           => $request->tahun,
            'kawasan'         => $request->kawasan,
            'data_kustom'     => $request->input('data_kustom', []), // Otomatis tersimpan sebagai JSON
        ]);

        return back()->with('success', 'Data berhasil disimpan!');
    }
}