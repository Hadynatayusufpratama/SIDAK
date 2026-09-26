<?php

namespace App\Http\Controllers;

use App\Models\Rekap;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    // Tampilkan Halaman Rekapitulasi dengan Filter Bidang & Subbidang
    public function index(Request $request)
    {
        // 1. Master Data Bidang & Subbidang untuk Dropdown Filter
        $masterBidang = [
            'perencanaan_konservasi' => [
                'nama' => 'Perencanaan Konservasi',
                'subs' => [
                    'A.01' => 'A.01 Kawasan Konservasi',
                    'A.02' => 'A.02 Perencanaan Pengelolaan Kawasan Konservasi',
                    'A.03' => 'A.03 Monitoring Batas Kawasan Konservasi',
                    'A.04' => 'A.04 Hasil Evaluasi Kesesuaian Fungsi Kawasan Konservasi',
                    'A.05' => 'A.05 Ekosistem Kawasan Konservasi',
                    'A.06' => 'A.06 Penataan Kawasan Konservasi',
                    'A.07' => 'A.07 Kerja Sama Penyelenggaraan KSA dan KPA',
                ]
            ],
            'konservasi_kawasan' => [
                'nama' => 'Konservasi Kawasan',
                'subs' => [
                    'B.01' => 'B.01 Kelompok Binaan UPT dalam rangka Pemberdayaan Masyarakat',
                    'B.02' => 'B.02 Pemberian Akses Pemanfaatan Tradisional dan Kemitraan Konservasi',
                    'B.03' => 'B.03 Permasalahan Strategis Kawasan Konservasi',
                    'B.04' => 'B.04 Gangguan Kawasan Konservasi',
                    'B.06' => 'B.06 Penanganan Perkara Tindak Pidana',
                    'B.07' => 'B.07 Tenaga Pengamanan Hutan Per Satuan Kerja',
                    'B.08' => 'B.08 Tenaga Pengamanan Hutan Per Resor',
                    'B.09' => 'B.09 Sarana Pengamanan Hutan',
                    'B.10' => 'B.10 Kebakaran Hutan dan Lahan di Kawasan Konservasi',
                    'B.11' => 'B.11 Tenaga Pengendalian Kebakaran Hutan',
                    'B.12' => 'B.12 Peralatan Tangan Pengendalian Kebakaran Hutan',
                    'B.13' => 'B.13 Peralatan Lainnya untuk Kebutuhan Pengendalian Kebakaran Hutan',
                    'B.14' => 'B.14 Rekapitulasi Kader Bina Cinta Alam',
                ]
            ],
            'konservasi_spesies' => [
                'nama' => 'Konservasi Spesies dan Genetik',
                'subs' => [
                    'C.01' => 'C.01 Perjumpaan Spesies di dalam dan luar Kawasan Konservasi',
                    'C.02' => 'C.02 Lembaga Konservasi Umum dan Khusus',
                    'C.03' => 'C.03 Koleksi TSL di Lembaga Konservasi',
                    'C.04' => 'C.04 Penangkaran Tumbuhan dan Satwa Liar',
                    'C.05' => 'C.05 Jenis TSL yang ditangkarkan di Penangkaran',
                    'C.06' => 'C.06 Pengedar Tumbuhan dan Satwa Liar (Dalam dan Luar Negeri)',
                    'C.07' => 'C.07 Kuota Pemanfaatan Tumbuhan dan Satwa Liar',
                    'C.08' => 'C.08 Realisasi Penangkapan/ Pengambilan Tumbuhan dan Satwa Liar',
                    'C.09' => 'C.09 Realisasi Ekspor Tumbuhan dan Satwa Liar Hasil Penangkaran',
                    'C.10' => 'C.10 Realisasi Ekspor Tumbuhan dan Satwa Liar Pengambilan dari Alam',
                    'C.11' => 'C.11 Rekapitulasi Sitaan/ Penyerahan/ Penyelamatkan Satwa',
                    'C.12' => 'C.12 PNBP dari kegiatan Pemanfaatan Tumbuhan dan Satwa Liar',
                    'C.14' => 'C.14 Interaksi Negatif Satwa Liar dan Manusia',
                    'C.15' => 'C.15 Rekapitulasi Pelepasliaran Kembali Satwa',
                    'C.16' => 'C.16 Rekapitulasi Kelahiran Satwa',
                    'C.17' => 'C.17 Rekapitulasi Kematian Satwa Liar',
                ]
            ],
            'pemanfaatan_jasa' => [
                'nama' => 'Pemanfaatan Jasa Lingkungan',
                'subs' => [
                    'D.01' => 'D.01 Pengunjung Kawasan Konservasi',
                    'D.02' => 'D.02 PNBP Wisata Alam di Kawasan Konservasi',
                    'D.03' => 'D.03 Desain Tapak Pemanfaatan Jasa Lingkungan Wisata Alam',
                    'D.04' => 'D.04 Potensi Wisata Alam di Kawasan Konservasi',
                    'D.05' => 'D.05 Pemanfaatan Jasa Lingkungan Penyediaan Jasa Wisata Alam (PBPJWA)',
                    'D.06' => 'D.06 Pengusahaan Sarana Jasa Lingkungan Wisata Alam (PBPSWA)',
                    'D.07' => 'D.07 Sarana dan Prasarana Wisata Alam di Kawasan Konservasi',
                    'D.08' => 'D.08 Dampak Aktivitas Wisata Alam',
                    'D.09' => 'D.09 Potensi Pemanfaatan Air dan Energi Air di Kawasan Konservasi',
                    'D.10' => 'D.10 Areal Pemanfaatan Air dan Energi Air di Kawasan Konservasi',
                    'D.11' => 'D.11 Pemanfaatan Air dan Energi Air di Kawasan Konservasi',
                    'D.12' => 'D.12 Potensi Pemanfaatan Karbon di Kawasan Konservasi',
                    'D.13' => 'D.13 Potensi Pemanfaatan Energi Panas Bumi di Kawasan Konservasi',
                    'D.14' => 'D.14 Pemanfaatan Jasa Lingkungan Panas Bumi di Kawasan Konservasi',
                    'D.15' => 'D.15 Kejadian Kecelakaan di dalam Kawasan Konservasi',
                    'D.16' => 'D.16 Promosi dan Publikasi Jasa Lingkungan Kawasan Konservasi',
                ]
            ],
            'pemulihan_ekosistem' => [
                'nama' => 'Pemulihan Ekosistem dan Bina Area Preservasi',
                'subs' => [
                    'E.01' => 'E.01 Perencanaan Pemulihan Ekosistem',
                    'E.02' => 'E.02 Realisasi Pemulihan Ekosistem',
                    'E.03' => 'E.03 Hasil Inventarisasi Area dengan Potensi Kehati Tinggi di Luar Kawasan Konservasi',
                    'E.04' => 'E.04 Kawasan Ekosistem Esensial',
                    'E.05' => 'E.05 Perencanaan Kawasan Ekosistem Esensial',
                    'E.06' => 'E.06 Penilaian Efektivitas Pengelolaan KEE',
                    'E.07' => 'E.07 Situs Ramsar',
                ]
            ],
            'kesekretariatan' => [
                'nama' => 'Kesekretariatan',
                'subs' => [
                    'F.01' => 'F.01 Sebaran PNS/CPNS Menurut Jabatan dan Jenis Kelamin',
                    'F.02' => 'F.02 Sebaran PNS/CPNS Menurut Tingkat Pendidikan dan Jenis Kelamin',
                    'F.03' => 'F.03 Sebaran PNS/CPNS Menurut Golongan dan Jenis Kelamin',
                    'F.04' => 'F.04 Sebaran Pejabat Fungsional Tertentu Menurut Fungsi dan Jenis Kelamin',
                    'F.05' => 'F.05 Sebaran Pejabat Fungsional Tertentu Menurut Fungsi, Tingkat Pendidikan dan Jenis Kelamin',
                    'F.06' => 'F.06 Sebaran ASN P3K menurut Tingkat Pendidikan dan Jenis Kelamin',
                    'F.07' => 'F.07 Kerja Sama Teknik Bidang KSDAE',
                    'F.08' => 'F.08 Perijinan Masuk Kawasan Konservasi',
                ]
            ]
        ];

        // 2. Tangkap Input Query Parameter dari URL
        $selectedBidang = $request->query('bidang');
        $selectedSub    = $request->query('sub_bidang');

        $data = collect();

        // 3. Ambil data HANYA JIKA sub_bidang sudah dipilih
        if ($selectedSub) {
            $data = Rekap::where('kode_sub_bidang', $selectedSub)->get();
        }

        // Return view rekapitulasi utama (pastikan nama folder/view disesuaikan, misal: 'rekap.index' atau 'rekaps.index')
        return view('rekaps.index', compact('masterBidang', 'selectedBidang', 'selectedSub', 'data'));
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