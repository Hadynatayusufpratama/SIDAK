<?php

namespace Tests\Feature;

use App\Models\Rekap;
use App\Models\Bidang;
use App\Models\SubBidang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SekretariatTest extends TestCase
{
    use RefreshDatabase;

    public function test_secretariat_link_opens_the_f01_inline_form_from_the_existing_form(): void
    {
        $bidang = Bidang::create(['nama_bidang' => 'Kesekretariatan']);
        SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'F.01',
            'nama_sub_bidang' => 'Sebaran PNS/CPNS Menurut Jabatan dan Jenis Kelamin',
        ]);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/sekretariat');

        $response->assertRedirect(route('konservasi.create', ['sub_bidang' => 'F.01']));

        $formResponse = $this->actingAs($user)->get(route('konservasi.create', ['sub_bidang' => 'F.01']));
        $subBidang = SubBidang::where('kode_sub', 'F.01')->firstOrFail();

        $formResponse->assertOk()
            ->assertSee('value="' . $subBidang->id . '" data-kode="F.01"', false)
            ->assertDontSee('F.01 Sebaran PNS/CPNS')
            ->assertSee("x-show=\"selectedSubBidangKode === 'F.01'\"", false);
    }

    public function test_create_page_has_no_separate_secretariat_navigation_item(): void
    {
        $response = $this->actingAs(User::factory()->create())->get('/konservasi/create');

        $response->assertOk()
            ->assertDontSee('>Sekretariat</a>', false)
            ->assertSee('Bidang Utama')
            ->assertSee('id="sub_bidang_select"', false);
    }

    public function test_f01_input_fields_are_available_from_the_existing_create_page(): void
    {
        $response = $this->actingAs(User::factory()->create())->get('/konservasi/create');

        $response->assertOk()
            ->assertDontSee('F.01 Sebaran PNS/CPNS')
            ->assertSee('Jumlah')
            ->assertSee('Orang')
            ->assertSee('sekretariat_tahun', false)
            ->assertSee('struktural[eselon_i_a][laki_laki]', false)
            ->assertSeeInOrder(['id="sub_bidang_select"', 'id="create-sekretariat-tahun"'], false)
            ->assertSee(route('sekretariat.store'), false);
    }

    public function test_f02_education_fields_are_available_below_the_category_selection(): void
    {
        $bidang = Bidang::create(['nama_bidang' => 'Kesekretariatan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'F.02',
            'nama_sub_bidang' => 'Sebaran PNS/CPNS Menurut Tingkat Pendidikan dan Jenis Kelamin',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('konservasi.create', ['sub_bidang' => 'F.02']));

        $response->assertOk()
            ->assertSee('value="' . $subBidang->id . '" data-kode="F.02"', false)
            ->assertSeeInOrder(['id="sub_bidang_select"', 'id="create-pendidikan-tahun"'], false)
            ->assertSee('Doktor (S3)')
            ->assertSee('Master (S2)')
            ->assertSee('Sarjana (S1)')
            ->assertSee('Diploma (D3)')
            ->assertSee('SLTA/SMA')
            ->assertSee('SLTP/SMP')
            ->assertSee('Sekolah Dasar (SD)')
            ->assertSee('name="pendidikan[doktor_s3][laki_laki]"', false)
            ->assertSee('option value="2023"', false)
            ->assertSee('option value="2026"', false);
    }

    public function test_f03_grade_fields_are_available_below_the_category_selection(): void
    {
        $bidang = Bidang::create(['nama_bidang' => 'Kesekretariatan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'F.03',
            'nama_sub_bidang' => 'Sebaran PNS/CPNS Menurut Golongan dan Jenis Kelamin',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('konservasi.create', ['sub_bidang' => 'F.03']));

        $response->assertOk()
            ->assertSee('value="' . $subBidang->id . '" data-kode="F.03"', false)
            ->assertSeeInOrder(['id="sub_bidang_select"', 'id="create-golongan-tahun"'], false)
            ->assertSee('Golongan IV')
            ->assertSee('Golongan III')
            ->assertSee('Golongan II')
            ->assertSee('Golongan I')
            ->assertSee('name="golongan[golongan_iv][laki_laki]"', false)
            ->assertSee('name="golongan[golongan_iv][perempuan]"', false);
    }

    public function test_f04_functional_position_fields_are_available_below_the_category_selection(): void
    {
        $bidang = Bidang::create(['nama_bidang' => 'Kesekretariatan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'F.04',
            'nama_sub_bidang' => 'Sebaran Pejabat Fungsional Tertentu Menurut Fungsi dan Jenis Kelamin',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('konservasi.create', ['sub_bidang' => 'F.04']));

        $response->assertOk()
            ->assertSee('value="' . $subBidang->id . '" data-kode="F.04"', false)
            ->assertSeeInOrder(['id="sub_bidang_select"', 'id="create-jabatan-fungsional-tahun"'], false)
            ->assertSee('Jenis Jabatan Fungsional')
            ->assertSee('role="combobox"', false)
            ->assertSee('role="listbox"', false)
            ->assertSee('value="Analis Hukum"', false)
            ->assertSee('value="Polisi Kehutanan"', false)
            ->assertSeeInOrder([
                'data-value="Analis Hukum"',
                'data-value="Analis Kebijakan"',
                'data-value="Analis Kepegawaian"',
                'data-value="Analis Pengelolaan Keuangan APBN"',
                'data-value="Analis SDM Aparatur"',
                'data-value="Arsiparis"',
                'data-value="Manggala Agni"',
                'data-value="Medik Veteriner"',
                'data-value="Pengendali Ekosistem Hutan"',
                'data-value="Penggerak Swadaya Masyarakat"',
                'data-value="Penyuluh Kehutanan"',
                'data-value="Penyuluh Lingkungan Hidup"',
                'data-value="Perancang Peraturan Perundang-undangan"',
                'data-value="Perencana"',
                'data-value="Polisi Kehutanan"',
                'data-value="Pranata Humas"',
                'data-value="Pranata Keuangan APBN"',
                'data-value="Pranata Komputer"',
                'data-value="Pranata SDM Aparatur"',
                'data-value="Pengelola Pengadaan Barang/Jasa"',
            ], false)
            ->assertSee('hover:bg-blue-600', false)
            ->assertSee('name="jumlah_laki_laki"', false)
            ->assertSee('name="jumlah_perempuan"', false)
            ->assertSee('name="jumlah_laki_laki" type="number" min="0" max="100000" step="1" inputmode="numeric" value="0"', false)
            ->assertSee('name="jumlah_perempuan" type="number" min="0" max="100000" step="1" inputmode="numeric" value="0"', false)
            ->assertSee('placeholder="Masukan disini untuk informasi lainnya"', false);
    }

    public function test_f05_functional_education_fields_are_available_below_the_category_selection(): void
    {
        $bidang = Bidang::create(['nama_bidang' => 'Kesekretariatan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'F.05',
            'nama_sub_bidang' => 'Sebaran Pejabat Fungsional Tertentu Menurut Fungsi, Tingkat Pendidikan dan Jenis Kelamin',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('konservasi.create', ['sub_bidang' => 'F.05']));

        $response->assertOk()
            ->assertSee('value="' . $subBidang->id . '" data-kode="F.05"', false)
            ->assertSeeInOrder([
                'id="sub_bidang_select"',
                'id="create-f05-tahun"',
                'id="create-f05-unit-kerja"',
                'id="create-f05-jenis-jabatan-fungsional"',
                'id="create-f05-pendidikan-doktor_s3-laki_laki"',
            ], false)
            ->assertSee('Jenis Jabatan Fungsional Tertentu')
            ->assertSee('role="listbox"', false)
            ->assertSee('id="create-f05-job-picker"', false)
            ->assertSee('data-value="Analis Hukum"', false)
            ->assertSee('Doktor (S3)')
            ->assertSee('Sekolah Dasar (SD)')
            ->assertSee('name="pendidikan[doktor_s3][laki_laki]"', false)
            ->assertSee('name="pendidikan[doktor_s3][perempuan]"', false);
    }

    public function test_f08_permit_fields_are_available_below_the_category_selection(): void
    {
        $bidang = Bidang::create(['nama_bidang' => 'Kesekretariatan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'F.08',
            'nama_sub_bidang' => 'Perijinan Masuk Kawasan Konservasi',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('konservasi.create', ['sub_bidang' => 'F.08']));

        $response->assertOk()
            ->assertSee('value="' . $subBidang->id . '" data-kode="F.08"', false)
            ->assertSeeInOrder([
                'id="sub_bidang_select"',
                'id="create-f08-tahun"',
                'id="create-f08-bulan"',
                'id="create-f08-unit-kerja"',
                'id="create-f08-penelitian_pengembangan-dalam_negeri"',
            ], false)
            ->assertSee('Periode Bulan')
            ->assertSee('role="listbox"', false)
            ->assertSee('Cari bulan')
            ->assertSeeInOrder([
                'data-label="Januari"',
                'data-label="Februari"',
                'data-label="Maret"',
                'data-label="April"',
                'data-label="Mei"',
                'data-label="Juni"',
                'data-label="Juli"',
                'data-label="Agustus"',
                'data-label="September"',
                'data-label="Oktober"',
                'data-label="November"',
                'data-label="Desember"',
            ], false)
            ->assertSee('hover:bg-blue-600', false)
            ->assertSee('Penelitian dan Pengembangan')
            ->assertSee('Pendidikan dan Ilmu Pengetahuan')
            ->assertSee('Pengambilan Foto dan Video')
            ->assertSee('Lain-lain')
            ->assertSee('Dalam Negeri')
            ->assertSee('Luar Negeri')
            ->assertSee('name="perijinan[penelitian_pengembangan][dalam_negeri]"', false)
            ->assertSee('name="perijinan[penelitian_pengembangan][luar_negeri]"', false);
    }

    public function test_f07_work_agreement_fields_are_available_below_the_category_selection(): void
    {
        $bidang = Bidang::create(['nama_bidang' => 'Kesekretariatan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'F.07',
            'nama_sub_bidang' => 'Kerja Sama Teknik Bidang KSDAE',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('konservasi.create', ['sub_bidang' => 'F.07']));

        $response->assertOk()
            ->assertSee('value="' . $subBidang->id . '" data-kode="F.07"', false)
            ->assertSeeInOrder([
                'id="sub_bidang_select"',
                'id="create-f07-tahun"',
                'id="create-f07-semester"',
                'id="create-f07-eselon"',
                'name="perjanjian_baru"',
            ], false)
            ->assertDontSee('role="dialog"', false)
            ->assertSee('selectedSubBidangKode === \'F.07\'', false)
            ->assertSee('Ya, terdapat kerja sama baru')
            ->assertSee('Tidak ada perjanjian baru (Nihil)')
            ->assertSee('PKS (Perjanjian Kerja Sama)')
            ->assertSee('MOU (Memorandum of Understanding)')
            ->assertSee('Informasi Mitra')
            ->assertSee('Dukungan Kinerja KLHK')
            ->assertSee('<details class="group rounded border border-slate-300 bg-white">', false)
            ->assertSee('x-show="hasAgreement === \'ya\'"', false)
            ->assertSee('x-show="agreementType !== \'\'"', false)
            ->assertSee('create-f07-shapefile', false)
            ->assertSee('Lokasi Kerja Sama');

        $this->assertStringContainsString('hasAgreement: null, agreementType: null', $response->getContent());
        $this->assertDoesNotMatchRegularExpression('/name="perjanjian_baru" value="(?:ya|tidak)"[^>]*checked/', $response->getContent());
        $this->assertDoesNotMatchRegularExpression('/name="jenis_dokumen" value="(?:PKS|MOU|Potensi)"[^>]*checked/', $response->getContent());
        $this->assertStringContainsString('value="Aktif"', $response->getContent());
        $this->assertStringContainsString('value="Berakhir"', $response->getContent());
        $this->assertStringContainsString('value="Masa Perpanjangan"', $response->getContent());
        $this->assertStringContainsString('value="Potensi"', $response->getContent());
        $this->assertStringContainsString('value="Proses Perpanjangan"', $response->getContent());
    }

    public function test_f06_p3k_education_fields_are_available_below_the_category_selection(): void
    {
        $bidang = Bidang::create(['nama_bidang' => 'Kesekretariatan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'F.06',
            'nama_sub_bidang' => 'Sebaran ASN P3K Menurut Tingkat Pendidikan dan Jenis Kelamin',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('konservasi.create', ['sub_bidang' => 'F.06']));

        $response->assertOk()
            ->assertSee('value="' . $subBidang->id . '" data-kode="F.06"', false)
            ->assertSeeInOrder(['id="sub_bidang_select"', 'id="create-f06-pendidikan-tahun"'], false)
            ->assertDontSee('id="create-f06-pendidikan-doktor_s3-laki_laki"', false)
            ->assertSee('Master (S2)')
            ->assertSee('Master (S2)')
            ->assertSee('Sarjana (S1)')
            ->assertSee('Diploma (D3)')
            ->assertSee('SLTA/SMA')
            ->assertSee('SLTP/SMP')
            ->assertSee('Sekolah Dasar (SD)')
            ->assertDontSee('id="create-f06-pendidikan-doktor_s3-laki_laki"', false)
            ->assertSee('id="create-f06-pendidikan-master_s2-laki_laki"', false)
            ->assertSee('id="create-f06-pendidikan-master_s2-perempuan"', false);
    }

    public function test_secretariat_counts_are_saved_as_an_f01_recap(): void
    {
        $bidang = Bidang::create(['nama_bidang' => 'Kesekretariatan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'F.01',
            'nama_sub_bidang' => 'Sebaran PNS/CPNS Menurut Jabatan dan Jenis Kelamin',
        ]);

        $structural = [];
        foreach ([
            'eselon_i_a', 'eselon_i_b', 'eselon_ii_a', 'eselon_ii_b',
            'eselon_iii_a', 'eselon_iii_b', 'eselon_iv_a', 'eselon_iv_b',
        ] as $position) {
            $structural[$position] = ['laki_laki' => 0, 'perempuan' => 0];
        }
        $structural['eselon_i_a'] = ['laki_laki' => 2, 'perempuan' => 1];

        $functional = [
            'fungsional_khusus' => ['laki_laki' => 3, 'perempuan' => 4],
            'calon_fungsional_khusus' => ['laki_laki' => 0, 'perempuan' => 1],
            'fungsional_umum' => ['laki_laki' => 5, 'perempuan' => 6],
        ];

        $response = $this->actingAs(User::factory()->create())->post('/sekretariat', [
            'sub_bidang_id' => $subBidang->id,
            'sekretariat_tahun' => 2026,
            'unit_kerja' => 'Balai KSDA Sulawesi Tengah',
            'struktural' => $structural,
            'fungsional' => $functional,
            'keterangan' => 'Data uji F.01',
        ]);

        $response->assertRedirect(route('konservasi.create', ['sub_bidang' => 'F.01']))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('rekaps', [
            'kode_sub_bidang' => 'F.01',
            'tahun' => '2026',
            'kawasan' => 'Balai KSDA Sulawesi Tengah',
        ]);

        $record = Rekap::firstOrFail();
        $this->assertSame(2, $record->data_kustom['struktural']['eselon_i_a']['laki_laki']);
        $this->assertSame(6, $record->data_kustom['fungsional']['fungsional_umum']['perempuan']);
    }

    public function test_secretariat_education_counts_are_saved_as_an_f02_recap(): void
    {
        $bidang = Bidang::create(['nama_bidang' => 'Kesekretariatan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'F.02',
            'nama_sub_bidang' => 'Sebaran PNS/CPNS Menurut Tingkat Pendidikan dan Jenis Kelamin',
        ]);

        $education = [];
        foreach (array_keys(\App\Http\Controllers\RekapController::PENDIDIKAN) as $level) {
            $education[$level] = ['laki_laki' => 0, 'perempuan' => 0];
        }
        $education['doktor_s3'] = ['laki_laki' => 2, 'perempuan' => 1];

        $response = $this->actingAs(User::factory()->create())->post('/sekretariat', [
            'sub_bidang_id' => $subBidang->id,
            'sekretariat_tahun' => 2026,
            'unit_kerja' => 'Balai KSDA Sulawesi Tengah',
            'pendidikan' => $education,
            'keterangan' => 'Data uji F.02',
        ]);

        $response->assertRedirect(route('konservasi.create', ['sub_bidang' => 'F.02']))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('rekaps', [
            'kode_sub_bidang' => 'F.02',
            'tahun' => '2026',
            'kawasan' => 'Balai KSDA Sulawesi Tengah',
        ]);

        $record = Rekap::firstOrFail();
        $this->assertSame(2, $record->data_kustom['pendidikan']['doktor_s3']['laki_laki']);
        $this->assertSame(1, $record->data_kustom['pendidikan']['doktor_s3']['perempuan']);
    }

    public function test_secretariat_grade_counts_are_saved_as_an_f03_recap(): void
    {
        $bidang = Bidang::create(['nama_bidang' => 'Kesekretariatan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'F.03',
            'nama_sub_bidang' => 'Sebaran PNS/CPNS Menurut Golongan dan Jenis Kelamin',
        ]);

        $grades = [];
        foreach (array_keys(\App\Http\Controllers\RekapController::GOLONGAN) as $grade) {
            $grades[$grade] = ['laki_laki' => 0, 'perempuan' => 0];
        }
        $grades['golongan_iv'] = ['laki_laki' => 3, 'perempuan' => 2];

        $response = $this->actingAs(User::factory()->create())->post('/sekretariat', [
            'sub_bidang_id' => $subBidang->id,
            'sekretariat_tahun' => 2026,
            'unit_kerja' => 'Balai KSDA Sulawesi Tengah',
            'golongan' => $grades,
        ]);

        $response->assertRedirect(route('konservasi.create', ['sub_bidang' => 'F.03']))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('rekaps', [
            'kode_sub_bidang' => 'F.03',
            'tahun' => '2026',
            'kawasan' => 'Balai KSDA Sulawesi Tengah',
        ]);

        $record = Rekap::firstOrFail();
        $this->assertSame(3, $record->data_kustom['golongan']['golongan_iv']['laki_laki']);
        $this->assertSame(2, $record->data_kustom['golongan']['golongan_iv']['perempuan']);
    }

    public function test_secretariat_functional_position_counts_are_saved_as_an_f04_recap(): void
    {
        $bidang = Bidang::create(['nama_bidang' => 'Kesekretariatan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'F.04',
            'nama_sub_bidang' => 'Sebaran Pejabat Fungsional Tertentu Menurut Fungsi dan Jenis Kelamin',
        ]);

        $response = $this->actingAs(User::factory()->create())->post('/sekretariat', [
            'sub_bidang_id' => $subBidang->id,
            'sekretariat_tahun' => 2026,
            'unit_kerja' => 'Balai KSDA Sulawesi Tengah',
            'jabatan_fungsional' => 'Polisi Kehutanan',
            'jumlah_laki_laki' => 7,
            'jumlah_perempuan' => 3,
            'keterangan' => 'Data uji F.04',
        ]);

        $response->assertRedirect(route('konservasi.create', ['sub_bidang' => 'F.04']))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('rekaps', [
            'kode_sub_bidang' => 'F.04',
            'tahun' => '2026',
            'kawasan' => 'Balai KSDA Sulawesi Tengah',
        ]);

        $record = Rekap::firstOrFail();
        $this->assertSame('Polisi Kehutanan', $record->data_kustom['jabatan_fungsional']);
        $this->assertSame(7, $record->data_kustom['laki_laki']);
        $this->assertSame(3, $record->data_kustom['perempuan']);
    }

    public function test_f04_saves_zero_when_the_female_count_is_not_submitted(): void
    {
        $bidang = Bidang::create(['nama_bidang' => 'Kesekretariatan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'F.04',
            'nama_sub_bidang' => 'Sebaran Pejabat Fungsional Tertentu Menurut Fungsi dan Jenis Kelamin',
        ]);

        $response = $this->actingAs(User::factory()->create())->post('/sekretariat', [
            'sub_bidang_id' => $subBidang->id,
            'sekretariat_tahun' => 2026,
            'unit_kerja' => 'Balai KSDA Sulawesi Tengah',
            'jabatan_fungsional' => 'Polisi Kehutanan',
            'jumlah_laki_laki' => 5,
        ]);

        $response->assertRedirect(route('konservasi.create', ['sub_bidang' => 'F.04']))
            ->assertSessionHasNoErrors();

        $record = Rekap::firstOrFail();
        $this->assertSame(5, $record->data_kustom['laki_laki']);
        $this->assertSame(0, $record->data_kustom['perempuan']);
    }

    public function test_secretariat_functional_education_counts_are_saved_as_an_f05_recap(): void
    {
        $bidang = Bidang::create(['nama_bidang' => 'Kesekretariatan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'F.05',
            'nama_sub_bidang' => 'Sebaran Pejabat Fungsional Tertentu Menurut Fungsi, Tingkat Pendidikan dan Jenis Kelamin',
        ]);

        $education = [];
        foreach (array_keys(\App\Http\Controllers\RekapController::PENDIDIKAN_P3K) as $level) {
            $education[$level] = ['laki_laki' => 0, 'perempuan' => 0];
        }
        $education['doktor_s3'] = ['laki_laki' => 1, 'perempuan' => 2];

        $response = $this->actingAs(User::factory()->create())->post('/sekretariat', [
            'sub_bidang_id' => $subBidang->id,
            'sekretariat_tahun' => 2026,
            'unit_kerja' => 'Balai KSDA Sulawesi Tengah',
            'jabatan_fungsional' => 'Penyuluh Kehutanan',
            'pendidikan' => $education,
            'keterangan' => 'Data uji F.05',
        ]);

        $response->assertRedirect(route('konservasi.create', ['sub_bidang' => 'F.05']))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('rekaps', [
            'kode_sub_bidang' => 'F.05',
            'tahun' => '2026',
            'kawasan' => 'Balai KSDA Sulawesi Tengah',
        ]);

        $record = Rekap::firstOrFail();
        $this->assertSame('Penyuluh Kehutanan', $record->data_kustom['jabatan_fungsional']);
        $this->assertSame(1, $record->data_kustom['pendidikan']['doktor_s3']['laki_laki']);
        $this->assertSame(2, $record->data_kustom['pendidikan']['doktor_s3']['perempuan']);
    }

    public function test_p3k_education_counts_are_saved_as_an_f06_recap(): void
    {
        $bidang = Bidang::create(['nama_bidang' => 'Kesekretariatan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'F.06',
            'nama_sub_bidang' => 'Sebaran ASN P3K Menurut Tingkat Pendidikan dan Jenis Kelamin',
        ]);

        $education = [];
        foreach (array_keys(\App\Http\Controllers\RekapController::PENDIDIKAN) as $level) {
            $education[$level] = ['laki_laki' => 0, 'perempuan' => 0];
        }
        $education['sarjana_s1'] = ['laki_laki' => 4, 'perempuan' => 6];

        $response = $this->actingAs(User::factory()->create())->post('/sekretariat', [
            'sub_bidang_id' => $subBidang->id,
            'sekretariat_tahun' => 2026,
            'unit_kerja' => 'Balai KSDA Sulawesi Tengah',
            'pendidikan' => $education,
            'keterangan' => 'Data uji F.06',
        ]);

        $response->assertRedirect(route('konservasi.create', ['sub_bidang' => 'F.06']))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('rekaps', [
            'kode_sub_bidang' => 'F.06',
            'tahun' => '2026',
            'kawasan' => 'Balai KSDA Sulawesi Tengah',
        ]);

        $record = Rekap::firstOrFail();
        $this->assertSame(4, $record->data_kustom['pendidikan']['sarjana_s1']['laki_laki']);
        $this->assertSame(6, $record->data_kustom['pendidikan']['sarjana_s1']['perempuan']);
    }

    public function test_permit_counts_and_month_are_saved_as_an_f08_recap(): void
    {
        $bidang = Bidang::create(['nama_bidang' => 'Kesekretariatan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'F.08',
            'nama_sub_bidang' => 'Perijinan Masuk Kawasan Konservasi',
        ]);

        $permits = [];
        foreach (array_keys(\App\Http\Controllers\RekapController::PERIJINAN) as $category) {
            $permits[$category] = ['dalam_negeri' => 0, 'luar_negeri' => 0];
        }
        $permits['penelitian_pengembangan'] = ['dalam_negeri' => 12, 'luar_negeri' => 3];

        $response = $this->actingAs(User::factory()->create())->post('/sekretariat', [
            'sub_bidang_id' => $subBidang->id,
            'sekretariat_tahun' => 2026,
            'sekretariat_bulan' => 9,
            'unit_kerja' => 'Balai KSDA Sulawesi Tengah',
            'perijinan' => $permits,
            'keterangan' => 'Data uji F.08',
        ]);

        $response->assertRedirect(route('konservasi.create', ['sub_bidang' => 'F.08']))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('rekaps', [
            'kode_sub_bidang' => 'F.08',
            'tahun' => '2026',
            'kawasan' => 'Balai KSDA Sulawesi Tengah',
        ]);

        $record = Rekap::firstOrFail();
        $this->assertSame(9, $record->data_kustom['bulan']);
        $this->assertSame(12, $record->data_kustom['perijinan']['penelitian_pengembangan']['dalam_negeri']);
        $this->assertSame(3, $record->data_kustom['perijinan']['penelitian_pengembangan']['luar_negeri']);
    }

    public function test_new_work_agreement_is_saved_as_an_f07_recap(): void
    {
        $bidang = Bidang::create(['nama_bidang' => 'Kesekretariatan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'F.07',
            'nama_sub_bidang' => 'Kerja Sama Teknik Bidang KSDAE',
        ]);

        $response = $this->actingAs(User::factory()->create())->post('/sekretariat', [
            'sub_bidang_id' => $subBidang->id,
            'sekretariat_tahun' => 2026,
            'semester' => 'I',
            'unit_kerja' => 'Balai KSDA Sulawesi Tengah',
            'eselon_ii_upt' => 'Balai KSDA Sulawesi Tengah',
            'perjanjian_baru' => 'ya',
            'jenis_dokumen' => 'PKS',
            'mitra_kerja_sama' => 'Universitas Tadulako',
            'tipe_mitra' => 'Lembaga Nirlaba (Pendidikan dan Pelatihan)',
            'status_kerja_sama' => 'Aktif',
            'nomor_perjanjian' => 'PKS-01/2026',
            'tanggal_mulai' => '2026-01-15',
            'tanggal_berakhir' => '2026-12-31',
            'judul_kerja_sama' => 'Penguatan konservasi',
            'ruang_lingkup' => 'Penelitian dan pengembangan konservasi',
            'dukungan_kinerja' => ['IKSS-IKSS-PN2-SPESES'],
            'catatan_dukungan_kinerja' => 'Dukungan indikator spesies',
            'luas_area' => 12.5,
            'nilai_komitmen' => 2500000,
            'provinsi' => 'Sulawesi Tengah',
            'lokasi_detail' => 'Palu',
            'keterangan' => 'Data uji F.07',
        ]);

        $response->assertRedirect(route('konservasi.create', ['sub_bidang' => 'F.07']))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('rekaps', [
            'kode_sub_bidang' => 'F.07',
            'tahun' => '2026',
            'kawasan' => 'Balai KSDA Sulawesi Tengah',
        ]);

        $record = Rekap::firstOrFail();
        $this->assertSame('I', $record->data_kustom['semester']);
        $this->assertSame('Universitas Tadulako', $record->data_kustom['mitra_kerja_sama']);
        $this->assertSame(['IKSS-IKSS-PN2-SPESES'], $record->data_kustom['dukungan_kinerja']);
    }

    public function test_f07_nil_entry_does_not_require_agreement_details(): void
    {
        $bidang = Bidang::create(['nama_bidang' => 'Kesekretariatan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'F.07',
            'nama_sub_bidang' => 'Kerja Sama Teknik Bidang KSDAE',
        ]);

        $response = $this->actingAs(User::factory()->create())->post('/sekretariat', [
            'sub_bidang_id' => $subBidang->id,
            'sekretariat_tahun' => 2026,
            'semester' => 'II',
            'unit_kerja' => 'Balai KSDA Sulawesi Tengah',
            'eselon_ii_upt' => 'Balai KSDA Sulawesi Tengah',
            'perjanjian_baru' => 'tidak',
        ]);

        $response->assertRedirect(route('konservasi.create', ['sub_bidang' => 'F.07']))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('rekaps', [
            'kode_sub_bidang' => 'F.07',
            'tahun' => '2026',
            'kawasan' => 'Balai KSDA Sulawesi Tengah',
        ]);

        $record = Rekap::firstOrFail();
        $this->assertSame('tidak', $record->data_kustom['perjanjian_baru']);
        $this->assertArrayNotHasKey('mitra_kerja_sama', $record->data_kustom);
    }
}