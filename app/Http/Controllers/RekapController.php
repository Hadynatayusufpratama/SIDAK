<?php

namespace App\Http\Controllers;

use App\Models\Rekap;
use App\Models\SubBidang;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RekapController extends Controller
{

    private const KODE_SEKRETARIAT_F01 = 'F.01';
    private const KODE_SEKRETARIAT_F02 = 'F.02';
    private const KODE_SEKRETARIAT_F03 = 'F.03';
    private const KODE_SEKRETARIAT_F04 = 'F.04';
    private const KODE_SEKRETARIAT_F05 = 'F.05';
    private const KODE_SEKRETARIAT_F06 = 'F.06';
    private const KODE_SEKRETARIAT_F07 = 'F.07';
    private const KODE_SEKRETARIAT_F08 = 'F.08';

    public const STRUKTURAL = [
        'eselon_i_a' => 'Struktural I-A',
        'eselon_i_b' => 'Struktural I-B',
        'eselon_ii_a' => 'Struktural II-A',
        'eselon_ii_b' => 'Struktural II-B',
        'eselon_iii_a' => 'Struktural III-A',
        'eselon_iii_b' => 'Struktural III-B',
        'eselon_iv_a' => 'Struktural IV-A',
        'eselon_iv_b' => 'Struktural IV-B',
    ];

    public const FUNGSIONAL = [
        'fungsional_khusus' => 'Fungsional Khusus',
        'calon_fungsional_khusus' => 'Calon Fungsional Khusus',
        'fungsional_umum' => 'Fungsional Umum',
    ];

    public const PENDIDIKAN = [
        'doktor_s3' => 'Doktor (S3)',
        'master_s2' => 'Master (S2)',
        'sarjana_s1' => 'Sarjana (S1)',
        'diploma_d3' => 'Diploma (D3)',
        'slta_sma' => 'SLTA/SMA',
        'sltp_smp' => 'SLTP/SMP',
        'sekolah_dasar_sd' => 'Sekolah Dasar (SD)',
    ];

    public const PENDIDIKAN_P3K = [
        'master_s2' => 'Master (S2)',
        'sarjana_s1' => 'Sarjana (S1)',
        'diploma_d3' => 'Diploma (D3)',
        'slta_sma' => 'SLTA/SMA',
        'sltp_smp' => 'SLTP/SMP',
        'sekolah_dasar_sd' => 'Sekolah Dasar (SD)',
    ];

    public const PERIJINAN = [
        'penelitian_pengembangan' => 'Penelitian dan Pengembangan',
        'pendidikan_ilmu_pengetahuan' => 'Pendidikan dan Ilmu Pengetahuan',
        'pengambilan_foto_video' => 'Pengambilan Foto dan Video',
        'lain_lain' => 'Lain-lain',
    ];

    public const TIPE_MITRA = [
        'Badan Usaha Milik Daerah',
        'Badan Usaha Milik Negara',
        'Badan Usaha Milik Swasta',
        'Koperasi',
        'Lembaga Nirlaba (Organisasi Masyarakat)',
        'Lembaga Nirlaba (Pendidikan dan Pelatihan)',
        'Lembaga Nirlaba (Penelitian/Pengkajian)',
        'Pemerintah Daerah Kab/Kota',
        'Pemerintah Daerah Provinsi',
        'Pemerintah Pusat (Kementerian)',
        'Pemerintah Pusat (Lembaga Pemerintah Non-Kementerian)',
        'Pemerintah Pusat (Sekretariat Lembaga Negara)',
        'Pemerintah Pusat (Sekretariat Lembaga Non-Struktural)',
    ];

    public const DUKUNGAN_KINERJA = [
        'IKSS-IKSS-PN2-SPESES' => 'Indeks Daftar Merah Nasional Status Keterancaman Spesies',
        'IKSS-IKSS-PN2-EKSPOR' => 'Nilai ekspor produk kehutanan',
        'IKSS-IKSS-PN2-PNBP' => 'Nilai PNBP Fungsional Kehutanan',
        'IKSS-IKSS-PN6-NTE' => 'Nilai transaksi ekonomi kelompok masyarakat kehutanan',
        'IKP-IKP-1.1.1' => 'Jumlah spesies yang diasesmen secara nasional untuk mendukung IUCN Red List',
        'IKP-IKP-1.1.2' => 'Penurunan status keterancaman spesies pada IUCN Red List',
        'IKP-IKP-1.2.1' => 'Nilai transaksi ekonomi kelompok masyarakat di sekitar KSA/KPA/TB',
        'IKP-IKP-1.3.1' => 'Nilai ekspor TSL dan bioprospecting',
        'IKP-IKP-1.3.2' => 'Nilai PNBP dari pemanfaatan jasa lingkungan dan TSL berkelanjutan',
        'IKK-IKK-2.1.1' => 'Indeks Efektivitas Perencanaan KSA/KPA/TB',
        'IKK-IKK-2.1.2' => 'Persentase entitas kerja sama yang efektif, efisien, transparan, dan akuntabel',
        'IKK-IKK-3.1.1' => 'Jumlah unit KSA, KPA, dan TB dengan kategori pengelolaan efektif',
        'IKK-IKK-3.2.1' => 'Jumlah kelompok masyarakat yang dibina dalam upaya konservasi kawasan dan keanekaragaman hayati',
        'IKK-IKK-PROV-SPECIES-TERDATA' => 'Jumlah spesies yang terdata sebaran dan/atau populasinya',
        'IKK-IKK-PROV-AREAL-PRESERVASI' => 'Luas Areal Preservasi yang dikembangkan',
        'IKK-IKK-PROV-PEMULIHAN-EKOSISTEM' => 'Luas pemulihan ekosistem di KSA, KPA, dan TB',
        'IKK-IKK-PROV-DESA-JASLING' => 'Jumlah desa di sekitar KPA yang mendapat manfaat dari jasa lingkungan',
        'IKK-IKK-PROV-BIOPROSPEKSI' => 'Jumlah produk yang dikembangkan melalui bioprospeksi',
        'IKK-IKK-PROV-PENANGKARAN' => 'Peningkatan jumlah individu dari hasil penangkaran yang dimanfaatkan',
        'IKK-IKK-PROV-WISATA-ETICKET' => 'Persentase pintu masuk obyek wisata yang menerapkan e-ticketing dan cashless payment',
        'IKK-IKK-PROV-KARBON' => 'Luas kawasan hutan konservasi yang dipersiapkan untuk implementasi nilai ekonomi karbon',
        'IKK-IKK-PROV-PNBP-JASLING' => 'Nilai PNBP dari pemanfaatan jasa lingkungan, KSA, KPA, dan TB',
        'IKK-IKK-PROV-PNBP-TSL' => 'Nilai PNBP dari hasil pemanfaatan tumbuhan dan satwa liar berkelanjutan',
        'IKK-IKK-PROV-SAKIP' => 'Nilai SAKIP Ditjen KSDAE',
        'IKK-IKK-PROV-SPIP' => 'Nilai maturitas SPIP Ditjen KSDAE',
    ];

    public const GOLONGAN = [
        'golongan_iv' => 'Golongan IV',
        'golongan_iii' => 'Golongan III',
        'golongan_ii' => 'Golongan II',
        'golongan_i' => 'Golongan I',
    ];

    public const JABATAN_FUNGSIONAL = [
        'Analis Hukum',
        'Analis Kebijakan',
        'Analis Kepegawaian',
        'Analis Pengelolaan Keuangan APBN',
        'Analis SDM Aparatur',
        'Arsiparis',
        'Manggala Agni',
        'Medik Veteriner',
        'Pengendali Ekosistem Hutan',
        'Penggerak Swadaya Masyarakat',
        'Penyuluh Kehutanan',
        'Penyuluh Lingkungan Hidup',
        'Perancang Peraturan Perundang-undangan',
        'Perencana',
        'Polisi Kehutanan',
        'Pranata Humas',
        'Pranata Keuangan APBN',
        'Pranata Komputer',
        'Pranata SDM Aparatur',
        'Pengelola Pengadaan Barang/Jasa',
    ];

    public function sekretariat()
    {
        return redirect()->route('konservasi.create', ['sub_bidang' => self::KODE_SEKRETARIAT_F01]);
    }

    public function storeSekretariat(Request $request)
    {
        $baseRules = [
            'sub_bidang_id' => 'required|exists:ref_sub_bidang,id',
            'sekretariat_tahun' => 'required|integer|min:2000|max:2100',
            'unit_kerja' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:2000',
        ];

        $validated = $request->validate($baseRules);
        $subBidang = SubBidang::findOrFail($validated['sub_bidang_id']);
        $kodeSubBidang = $subBidang->kode_sub;

        abort_unless(in_array($kodeSubBidang, [
            self::KODE_SEKRETARIAT_F01,
            self::KODE_SEKRETARIAT_F02,
            self::KODE_SEKRETARIAT_F03,
            self::KODE_SEKRETARIAT_F04,
            self::KODE_SEKRETARIAT_F05,
            self::KODE_SEKRETARIAT_F06,
            self::KODE_SEKRETARIAT_F07,
            self::KODE_SEKRETARIAT_F08,
        ], true), 422);

        if ($kodeSubBidang === self::KODE_SEKRETARIAT_F07) {
            $agreementIsNew = $request->input('perjanjian_baru') === 'ya';
            $rules = [
                ...$baseRules,
                'semester' => ['required', Rule::in(['I', 'II'])],
                'eselon_ii_upt' => 'required|string|max:255',
                'perjanjian_baru' => ['required', Rule::in(['ya', 'tidak'])],
            ];

            if ($agreementIsNew) {
                $rules += [
                    'jenis_dokumen' => ['required', Rule::in(['PKS', 'MOU', 'Potensi'])],
                    'mitra_kerja_sama' => 'required|string|max:255',
                    'tipe_mitra' => ['required', Rule::in(self::TIPE_MITRA)],
                    'status_kerja_sama' => ['required', Rule::in(['Aktif', 'Berakhir', 'Masa Perpanjangan', 'Potensi', 'Proses Perpanjangan'])],
                    'nomor_perjanjian' => 'required|string|max:255',
                    'tanggal_mulai' => 'required|date',
                    'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
                    'judul_kerja_sama' => 'required|string|max:255',
                    'ruang_lingkup' => 'required|string|max:5000',
                    'dukungan_kinerja' => 'nullable|array',
                    'dukungan_kinerja.*' => ['string', Rule::in(array_keys(self::DUKUNGAN_KINERJA))],
                    'catatan_dukungan_kinerja' => 'nullable|string|max:3000',
                    'luas_area' => 'nullable|numeric|min:0',
                    'nilai_komitmen' => 'nullable|numeric|min:0',
                    'dokumen_kerja_sama' => 'nullable|mimes:pdf|max:20480',
                    'provinsi' => 'required|string|max:100',
                    'lokasi_detail' => 'nullable|string|max:2000',
                    'shapefile_kerja_sama' => 'nullable|mimes:zip|max:10240',
                ];
            }

            $validated = $request->validate($rules);
            $dataKustom = [
                'unit_kerja' => $validated['unit_kerja'],
                'eselon_ii_upt' => $validated['eselon_ii_upt'],
                'semester' => $validated['semester'],
                'perjanjian_baru' => $validated['perjanjian_baru'],
                'keterangan' => $validated['keterangan'] ?? null,
            ];

            if ($agreementIsNew) {
                foreach ([
                    'jenis_dokumen', 'mitra_kerja_sama', 'tipe_mitra', 'status_kerja_sama',
                    'nomor_perjanjian', 'tanggal_mulai', 'tanggal_berakhir', 'judul_kerja_sama',
                    'ruang_lingkup', 'dukungan_kinerja', 'catatan_dukungan_kinerja', 'luas_area',
                    'nilai_komitmen', 'provinsi', 'lokasi_detail',
                ] as $field) {
                    $dataKustom[$field] = $validated[$field] ?? null;
                }

                foreach (['dokumen_kerja_sama', 'shapefile_kerja_sama'] as $fileField) {
                    if ($request->hasFile($fileField)) {
                        $dataKustom[$fileField] = $request->file($fileField)->store('dokumen_kerja_sama', 'public');
                    }
                }
            }

            $this->saveSecretariatRecord($request, self::KODE_SEKRETARIAT_F07, $validated, $dataKustom);

            return $this->secretariatSaveRedirect($request, $kodeSubBidang);
        }

        if ($kodeSubBidang === self::KODE_SEKRETARIAT_F08) {
            $rules = [
                ...$baseRules,
                'sekretariat_bulan' => 'required|integer|min:1|max:12',
            ];

            foreach (array_keys(self::PERIJINAN) as $kategori) {
                foreach (['dalam_negeri', 'luar_negeri'] as $cakupan) {
                    $rules["perijinan.{$kategori}.{$cakupan}"] = 'required|integer|min:0|max:100000';
                }
            }

            $validated = $request->validate($rules);

            $this->saveSecretariatRecord($request, self::KODE_SEKRETARIAT_F08, $validated, [
                    'unit_kerja' => $validated['unit_kerja'],
                    'bulan' => (int) $validated['sekretariat_bulan'],
                    'perijinan' => $validated['perijinan'],
                    'keterangan' => $validated['keterangan'] ?? null,
                ]);

            return $this->secretariatSaveRedirect($request, $kodeSubBidang);
        }

        if (in_array($kodeSubBidang, [self::KODE_SEKRETARIAT_F04, self::KODE_SEKRETARIAT_F05], true)) {
            $isF04 = $kodeSubBidang === self::KODE_SEKRETARIAT_F04;
            $rules = [
                ...$baseRules,
                'jabatan_fungsional' => ['required', 'string', Rule::in(self::JABATAN_FUNGSIONAL)],
            ];

            if ($isF04) {
                foreach (['jumlah_laki_laki', 'jumlah_perempuan'] as $countField) {
                    if (!$request->filled($countField)) {
                        $request->merge([$countField => 0]);
                    }
                }
                $rules['jumlah_laki_laki'] = 'required|integer|min:0|max:100000';
                $rules['jumlah_perempuan'] = 'required|integer|min:0|max:100000';
            } else {
                foreach (array_keys(self::PENDIDIKAN) as $level) {
                    foreach (['laki_laki', 'perempuan'] as $gender) {
                        $rules["pendidikan.{$level}.{$gender}"] = 'required|integer|min:0|max:100000';
                    }
                }
            }

            $validated = $request->validate($rules);
            $dataKustom = [
                'unit_kerja' => $validated['unit_kerja'],
                'jabatan_fungsional' => $validated['jabatan_fungsional'],
                'keterangan' => $validated['keterangan'] ?? null,
            ];

            if ($isF04) {
                $dataKustom['laki_laki'] = $validated['jumlah_laki_laki'];
                $dataKustom['perempuan'] = $validated['jumlah_perempuan'];
            } else {
                $dataKustom['pendidikan'] = $validated['pendidikan'];
            }

            $this->saveSecretariatRecord($request, $kodeSubBidang, $validated, $dataKustom);

            return $this->secretariatSaveRedirect($request, $kodeSubBidang);
        }

        $sections = match ($kodeSubBidang) {
            self::KODE_SEKRETARIAT_F01 => ['struktural' => self::STRUKTURAL, 'fungsional' => self::FUNGSIONAL],
            self::KODE_SEKRETARIAT_F02 => ['pendidikan' => self::PENDIDIKAN],
            self::KODE_SEKRETARIAT_F06 => ['pendidikan' => self::PENDIDIKAN_P3K],
            self::KODE_SEKRETARIAT_F03 => ['golongan' => self::GOLONGAN],
        };
        $rules = $baseRules;

        foreach ($sections as $section => $positions) {
            foreach (array_keys($positions) as $position) {
                foreach (['laki_laki', 'perempuan'] as $gender) {
                    $rules["{$section}.{$position}.{$gender}"] = 'required|integer|min:0|max:100000';
                }
            }
        }

        $validated = $request->validate($rules);

        $dataKustom = [
            'unit_kerja' => $validated['unit_kerja'],
            'keterangan' => $validated['keterangan'] ?? null,
        ];
        foreach ($sections as $section => $positions) {
            $dataKustom[$section] = $validated[$section];
        }

        $this->saveSecretariatRecord($request, $kodeSubBidang, $validated, $dataKustom);

        return $this->secretariatSaveRedirect($request, $kodeSubBidang);
    }

    public function editSekretariat(Rekap $rekap)
    {
        abort_unless(str_starts_with($rekap->kode_sub_bidang, 'F.'), 404);
        $subBidang = SubBidang::where('kode_sub', $rekap->kode_sub_bidang)->firstOrFail();
        $formData = $rekap->data_kustom ?? [];
        $formData['sub_bidang_id'] = $subBidang->id;
        $formData['sekretariat_tahun'] = $rekap->tahun;
        $formData['unit_kerja'] = $formData['unit_kerja'] ?? $rekap->kawasan;

        if ($rekap->kode_sub_bidang === self::KODE_SEKRETARIAT_F04) {
            $formData['jumlah_laki_laki'] = $formData['laki_laki'] ?? 0;
            $formData['jumlah_perempuan'] = $formData['perempuan'] ?? 0;
        }

        if ($rekap->kode_sub_bidang === self::KODE_SEKRETARIAT_F08) {
            $formData['sekretariat_bulan'] = $formData['bulan'] ?? '';
        }

        return redirect()
            ->route('konservasi.create', [
                'sub_bidang' => $rekap->kode_sub_bidang,
                'edit_record' => $rekap->id,
            ])
            ->withInput($formData);
    }

    public function updateSekretariat(Request $request, Rekap $rekap)
    {
        abort_unless(str_starts_with($rekap->kode_sub_bidang, 'F.'), 404);
        $request->attributes->set('secretariat_record', $rekap);

        return $this->storeSekretariat($request);
    }

    public function destroySekretariat(Rekap $rekap)
    {
        abort_unless(str_starts_with($rekap->kode_sub_bidang, 'F.'), 404);
        $rekap->delete();

        return redirect()->route('konservasi.index')->with('success', 'Data Sekretariat berhasil dihapus.');
    }

    private function saveSecretariatRecord(Request $request, string $kodeSubBidang, array $validated, array $dataKustom): Rekap
    {
        $attributes = [
            'kode_sub_bidang' => $kodeSubBidang,
            'tahun' => (string) $validated['sekretariat_tahun'],
            'kawasan' => $validated['unit_kerja'],
            'data_kustom' => $dataKustom,
        ];
        $record = $request->attributes->get('secretariat_record');

        if ($record) {
            abort_unless($record->kode_sub_bidang === $kodeSubBidang, 404);
            if ($kodeSubBidang === self::KODE_SEKRETARIAT_F07 && ($dataKustom['perjanjian_baru'] ?? null) === 'ya') {
                foreach (['dokumen_kerja_sama', 'shapefile_kerja_sama'] as $fileField) {
                    $attributes['data_kustom'][$fileField] = $dataKustom[$fileField]
                        ?? $record->data_kustom[$fileField]
                        ?? null;
                }
            }
            $record->update($attributes);

            return $record;
        }

        return Rekap::create($attributes);
    }

    private function secretariatSaveRedirect(Request $request, string $kodeSubBidang)
    {
        if ($request->attributes->get('secretariat_record')) {
            return redirect()->route('konservasi.index')->with('success', 'Data Sekretariat berhasil diperbarui.');
        }

        return redirect()
            ->route('konservasi.create', ['sub_bidang' => $kodeSubBidang])
            ->with('success', 'Data Sekretariat berhasil disimpan.')
            ->with('recap_url', route('rekap.index', $kodeSubBidang));
    }
    // Tampilkan halaman Rekapitulasi
    public function index(Request $request)
    {
        $data = Rekap::latest()->get();

        $data->each(function ($record) {
            $record->detail_rows = $this->formatDetailRows($record->data_kustom ?? []);
        });

        return view('rekaps.index', [
            'data' => $data,
            'kode' => null,
            'subBidang' => null,
        ]);
    }
    // Tampilkan Halaman Rekap berdasarkan Kode Sub-Bidang
    public function indexByKode($kode, Request $request)
{
    $query = Rekap::where('kode_sub_bidang', $kode);

    if ($request->filled('record')) {
        $query->whereKey($request->integer('record'));
    }

    $data = $query->latest()->get();

    $subBidang = SubBidang::where('kode_sub', $kode)->first();

    $data->each(function ($record) {
        $record->detail_rows = $this->formatDetailRows($record->data_kustom ?? []);
    });

    return view('rekaps.index', compact('data', 'kode', 'subBidang'));
}

    private function formatDetailRows(array $details): array
    {
        $labels = [
            'struktural' => 'Jabatan Struktural',
            'fungsional' => 'Jabatan Fungsional',
            'pendidikan' => 'Pendidikan',
            'golongan' => 'Golongan',
            'perijinan' => 'Perizinan',
            'unit_kerja' => 'Satuan Kerja',
            'jabatan_fungsional' => 'Jabatan Fungsional',
            'laki_laki' => 'Laki-laki',
            'perempuan' => 'Perempuan',
            'dalam_negeri' => 'Dalam Negeri',
            'luar_negeri' => 'Luar Negeri',
            'keterangan' => 'Keterangan',
            'bulan' => 'Bulan',
            'semester' => 'Semester',
            'eselon_ii_upt' => 'Eselon II UPT',
            'perjanjian_baru' => 'Perjanjian Baru',
            'jenis_dokumen' => 'Jenis Dokumen',
            'mitra_kerja_sama' => 'Mitra Kerja Sama',
            'tipe_mitra' => 'Tipe Mitra',
            'status_kerja_sama' => 'Status Kerja Sama',
            'nomor_perjanjian' => 'Nomor Perjanjian',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_berakhir' => 'Tanggal Berakhir',
            'judul_kerja_sama' => 'Judul Kerja Sama',
            'ruang_lingkup' => 'Ruang Lingkup',
            'dukungan_kinerja' => 'Dukungan Kinerja',
            'catatan_dukungan_kinerja' => 'Catatan Dukungan Kinerja',
            'luas_area' => 'Luas Area',
            'nilai_komitmen' => 'Nilai Komitmen',
            'provinsi' => 'Provinsi',
            'lokasi_detail' => 'Lokasi Detail',
        ];
        $rows = [];
        $walk = function (array $values, array $parents = []) use (&$walk, &$rows, $labels): void {
            $label = fn (string $key): string => $labels[$key] ?? Str::headline($key);
            $group = implode(' / ', array_map($label, $parents));

            if (array_key_exists('laki_laki', $values) || array_key_exists('perempuan', $values)) {
                $rowLabel = $values['jabatan_fungsional'] ?? ($parents ? $label((string) end($parents)) : 'Jumlah');
                $rows[] = [
                    'group' => $group,
                    'label' => $rowLabel,
                    'first_label' => 'Laki-laki',
                    'first' => $values['laki_laki'] ?? '-',
                    'second_label' => 'Perempuan',
                    'second' => $values['perempuan'] ?? '-',
                    'total' => ((int) ($values['laki_laki'] ?? 0)) + ((int) ($values['perempuan'] ?? 0)),
                ];
            }

            if (array_key_exists('dalam_negeri', $values) || array_key_exists('luar_negeri', $values)) {
                $rows[] = [
                    'group' => $group,
                    'label' => $parents ? $label((string) end($parents)) : 'Perizinan',
                    'first_label' => 'Dalam Negeri',
                    'first' => $values['dalam_negeri'] ?? '-',
                    'second_label' => 'Luar Negeri',
                    'second' => $values['luar_negeri'] ?? '-',
                    'total' => ((int) ($values['dalam_negeri'] ?? 0)) + ((int) ($values['luar_negeri'] ?? 0)),
                ];
            }

            foreach ($values as $key => $value) {
                if (in_array($key, ['laki_laki', 'perempuan', 'dalam_negeri', 'luar_negeri'], true)) {
                    continue;
                }

                $key = (string) $key;
                if (is_array($value)) {
                    if (array_is_list($value)) {
                        $rows[] = [
                            'group' => $group,
                            'label' => $label($key),
                            'value' => implode(', ', array_map('strval', $value)),
                        ];
                    } else {
                        $walk($value, [...$parents, $key]);
                    }
                } else {
                    $rows[] = [
                        'group' => $group,
                        'label' => $label($key),
                        'value' => $value === null || $value === '' ? '-' : $value,
                    ];
                }
            }
        };

        $walk($details);

        return $rows;

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