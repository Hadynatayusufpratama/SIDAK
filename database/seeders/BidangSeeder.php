<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bidang;
use App\Models\SubBidang;
use Illuminate\Support\Facades\DB;

class BidangSeeder extends Seeder
{
    public function run(): void
    {
        // Matikan foreign key check sementara agar pembersihan data aman
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Bidang::truncate();
        SubBidang::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'bidang' => 'Perencanaan Konservasi',
                'subs' => [
                    ['a', 'Kawasan Konservasi: Pasal 6'],
                    ['b', 'Hasil Evaluasi Kesesuaian Fungsi Kawasan Konservasi'],
                    ['c', 'Perubahan Fungsi dan Perubahan Peruntukan Kawasan Konservasi'],
                    ['d', 'Ekosistem Kawasan Konservasi'],
                    ['e', 'Penataan Kawasan Konservasi'],
                    ['f', 'Penetapan Kesesuaian Pengelolaan Hutan Konservasi (KPHK) Taman Nasional: Pasal 6'],
                    ['g', 'Penetapan Kesatuan Pengelolaan Hutan Konservasi (KPHK) Non Taman Nasional'],
                    ['h', 'Kerjasama Penyelenggaraan Kawasan Suaka Alam dan Kawasan Pelestarian Alam'],
                ]
            ],
            [
                'bidang' => 'Konservasi Kawasan',
                'subs' => [
                    ['a', 'Perencanaan Pengelolaan Kawasan Konservasi Pasal 5'],
                    ['b', 'Kawasan Konservasi yang Mendapat Penetapan Status Internasional sebagai Cagar Biosfer'],
                    ['c', 'Kawasan Konservasi yang Mendapat Penetapan Status Internasional sebagai Situs Warisan Dunia'],
                    ['d', 'Kawasan Konservasi yang Mendapat Penetapan Status Internasional sebagai Situs Ramsar'],
                    ['e', 'Kawasan Konservasi yang Mendapat Penetapan Status Internasional sebagai ASEAN Heritage Park'],
                    ['f', 'Kawasan Konservasi yang Mendapat Penetapan Status Internasional sebagai UNESCO Global Geopark'],
                    ['g', 'Penataan Batas Kawasan Konservasi'],
                    ['h', 'Rekonstruksi Batas Kawasan Konservasi'],
                    ['i', 'Pemeliharaan Batas Kawasan Konservasi'],
                    ['j', 'Perencanaan Pemulihan Ekosistem Kawasan Konservasi Pasal 9'],
                    ['k', 'Rencana dan Realisasi Pemulihan Ekosistem Kawasan Konservasi Pasal 9'],
                    ['l', 'Daerah Penyangga Kawasan Konservasi'],
                    ['m', 'Desa Binaan di Daerah Penyangga Kawasan Konservasi'],
                    ['n', 'Pembinaan Usaha Ekonomi Produktif pada Daerah Penyangga Kawasan Konservasi'],
                    ['o', 'Zona dan Blok Tradisional Kawasan Konservasi'],
                    ['p', 'Pemanfaatan Zona dan Blok Tradisional Kawasan Konservasi'],
                    ['q', 'Kemitraan Konservasi'],
                    ['r', 'Permasalahan Kawasan Konservasi'],
                    ['s', 'Gangguan Penerbangan Liar di Kawasan Konservasi'],
                    ['t', 'Gangguan Pemburuan Liar di Kawasan Konservasi'],
                    ['u', 'Gangguan Pengambilan Hasil Hutan Lainnya di Kawasan Konservasi'],
                    ['v', 'Penggunaan Kawasan Konservasi Tanpa Izin untuk Kegiatan Perkebunan'],
                    ['w', 'Penggunaan Kawasan Konservasi Tanpa Izin untuk Keperluan Pemukiman, Persawahan, dan Kebun Campur'],
                    ['x', 'Penggunaan Kawasan Konservasi Tanpa Izin untuk Pembangunan Infrastruktur'],
                    ['y', 'Penggunaan Kawasan Konservasi Tanpa Izin untuk Kegiatan Pertambangan'],
                    ['z', 'Hasil Operasi Pengamanan Kawasan Konservasi'],
                    ['aa', 'Hasil Operasi Pengamanan Peredaran Tumbuhan dan Satwa Liar'],
                    ['bb', 'Penanganan Perkara Tindak Pidana'],
                    ['cc', 'Tenaga Pengamanan Hutan Satuan Kerja'],
                    ['dd', 'Tenaga Pengamanan Hutan pada Kawasan Konservasi'],
                    ['ee', 'Sarana Pengamanan Hutan'],
                    ['ff', 'Sebaran Titik Panas (Hot Spot) di Kawasan Konservasi'],
                    ['gg', 'Kebakaran Hutan di Kawasan Konservasi'],
                    ['hh', 'Tenaga Pengendalian Kebakaran Hutan'],
                    ['ii', 'Peralatan Tangan Pengendalian Kebakaran Hutan'],
                    ['jj', 'Peralatan Transportasi Pengendalian Kebakaran Hutan'],
                    ['kk', 'Peralatan Mesin Pompa dan Kelengkapannya untuk Kebutuhan Pengendalian Kebakaran Hutan'],
                ]
            ],
            [
                'bidang' => 'Konservasi Spesies dan Genetik',
                'subs' => [
                    ['a', 'Perjumpaan Satwa Liar pada Kawasan Konservasi'],
                    ['b', 'Perjumpaan Tumbuhan Alam pada Kawasan Konservasi'],
                    ['c', 'Lembaga Konservasi Umum'],
                    ['d', 'Lembaga Konservasi Khusus'],
                    ['e', 'Penangkaran Tumbuhan dan Satwa Liar'],
                    ['f', 'Pengedar Tumbuhan dan Satwa Liar Dalam Negeri'],
                    ['g', 'Pengedar Tumbuhan dan Satwa Liar Luar Negeri'],
                    ['h', 'Kuota Pemanfaatan Tumbuhan dan Satwa Liar (Appendiks dan Non Appendiks CITES)'],
                    ['i', 'Realisasi Pemanfaatan Tumbuhan dan Satwa Liar (Appendiks dan Non Appendiks CITES)'],
                    ['j', 'Realisasi Ekspor Tumbuhan dan Satwa Liar Hasil Penangkaran'],
                    ['k', 'Realisasi Ekspor Tumbuhan dan Satwa Liar Hasil Pengambilan dari Alam'],
                    ['l', 'PNBP dari Kegiatan Pemanfaatan Tumbuhan dan Satwa Liar'],
                    ['m', 'Penerima Devisa dari Ekspor Tumbuhan dan Satwa Liar'],
                    ['n', 'Hasil Assesmen Aman Lingkungan terhadap Produk Rekayasa Genetik'],
                    ['o', 'Konflik Satwa dan Manusia'],
                    ['p', 'Realisasi Penggunaan SATS-DN'],
                    ['q', 'Rekapitulasi Kelahiran Satwa Liar'],
                    ['r', 'Rekapitulasi Kematian Satwa Liar'],
                    ['s', 'Rekapitulasi Pelepasliaran Kembali Satwa'],
                    ['t', 'Rekapitulasi Sitaan Satwa Liar'],
                ]
            ],
            [
                'bidang' => 'Pemanfaatan Jasa Lingkungan',
                'subs' => [
                    ['a', 'Pengunjung Kawasan Konservasi'],
                    ['b', 'Kunjungan Wisata ke Kawasan Konservasi'],
                    ['c', 'Promosi dan Publikasi Jasa Lingkungan Kawasan Konservasi'],
                    ['d', 'Potensi Wisata Alam di Kawasan Konservasi'],
                    ['e', 'Desain Tapak Pemanfaatan Jasa Lingkungan Wisata Alam'],
                    ['f', 'Pengusahaan Pemanfaatan Jasa Lingkungan Wisata Alam'],
                    ['g', 'Potensi Pemanfaatan Air di Kawasan Konservasi'],
                    ['h', 'Pemanfaatan Masa Air di Kawasan Konservasi'],
                    ['i', 'Pemanfaatan Energi Air di Kawasan Konservasi'],
                    ['j', 'Potensi Pemanfaatan Karbon di Kawasan Konservasi'],
                    ['k', 'Potensi Pemanfaatan Energi Panas Bumi di Kawasan Konservasi'],
                    ['l', 'Pemanfaatan Jasa Lingkungan Panas Bumi di Kawasan Konservasi'],
                    ['m', 'Perizinan Pemanfaatan Jasa Lingkungan pada Kawasan Konservasi'],
                    ['n', 'PNBP dari Kunjungan Wisata ke Kawasan Konservasi'],
                    ['o', 'Kejadian Kecelakaan di Dalam Kawasan Konservasi'],
                ]
            ],
            [
                'bidang' => 'Pemulihan Ekosistem dan Bina Area Preservasi',
                'subs' => [
                    ['a', 'Fasilitasi Area Preservasi'],
                    ['b', 'Koleksi Spesies pada Area Preservasi'],
                    ['c', 'Ekosistem Esensial Karst'],
                    ['d', 'Area Preservasi Mangrove'],
                    ['e', 'Area Preservasi Lahan Basah'],
                    ['f', 'Area Preservasi Areal Bernilai Konservasi Tinggi (ABKT/HCVA)'],
                    ['g', 'Area Preservasi Koridor Kehidupan Liar'],
                    ['h', 'Rencana Aksi Pengelolaan Area Preservasi'],
                    ['i', 'Calon Lokasi Area Preservasi'],
                ]
            ],
            [
                'bidang' => 'Kesekretariatan',
                'subs' => [
                    ['a', 'Sebaran PNS/CPNS Menurut Jabatan dan Jenis Kelamin'],
                    ['b', 'Sebaran PNS/CPNS Menurut Tingkat Pendidikan dan Jenis Kelamin'],
                    ['c', 'Sebaran PNS/CPNS Menurut Golongan dan Jenis Kelamin'],
                    ['d', 'Sebaran Pejabat Fungsional Tertentu Menurut Fungsi dan Jenis Kelamin'],
                    ['e', 'Sebaran Pejabat Fungsional Tertentu Menurut Fungsi, Tingkat Pendidikan, dan Jenis Kelamin'],
                    ['f', 'Sebaran Pejabat Fungsional Tertentu Menurut Fungsi dan Jenjang Jabatan'],
                    ['g', 'Sebaran Pegawai Tidak Tetap Menurut Tingkat Pendidikan dan Jenis Kelamin'],
                    ['h', 'Pagu dan Realisasi Anggaran'],
                    ['i', 'Target dan Realisasi Penerimaan Negara Bukan Pajak'],
                    ['j', 'Rincian Barang Milik Negara (Gabungan Intrakomptabel dan Ekstrakomptabel)'],
                    ['k', 'Perizinan Masuk Kawasan Konservasi'],
                    ['l', 'Publikasi Bidang KSDAE'],
                ]
            ]
        ];

        foreach ($data as $item) {
            $bidang = Bidang::create(['nama_bidang' => $item['bidang']]);
            foreach ($item['subs'] as $sub) {
                SubBidang::create([
                    'bidang_id' => $bidang->id,
                    'kode_sub' => $sub[0],
                    'nama_sub_bidang' => $sub[1],
                ]);
            }
        }
    }
}