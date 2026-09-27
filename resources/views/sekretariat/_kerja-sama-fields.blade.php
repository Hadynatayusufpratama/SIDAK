@php
    $provinces = [
        'Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Kepulauan Riau', 'Jambi', 'Sumatera Selatan',
        'Kepulauan Bangka Belitung', 'Bengkulu', 'Lampung', 'DKI Jakarta', 'Banten', 'Jawa Barat',
        'Jawa Tengah', 'DI Yogyakarta', 'Jawa Timur', 'Bali', 'Nusa Tenggara Barat', 'Nusa Tenggara Timur',
        'Kalimantan Barat', 'Kalimantan Tengah', 'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara',
        'Sulawesi Utara', 'Gorontalo', 'Sulawesi Tengah', 'Sulawesi Barat', 'Sulawesi Selatan', 'Sulawesi Tenggara',
        'Maluku', 'Maluku Utara', 'Papua', 'Papua Barat', 'Papua Selatan', 'Papua Tengah', 'Papua Pegunungan', 'Papua Barat Daya',
    ];
    $documents = ['PKS' => 'PKS (Perjanjian Kerja Sama)', 'MOU' => 'MOU (Memorandum of Understanding)', 'Potensi' => 'Potensi'];
    $statuses = ['Aktif', 'Berakhir', 'Masa Perpanjangan', 'Potensi', 'Proses Perpanjangan'];
    $helperTextClass = 'mt-1 text-[11px] leading-relaxed text-slate-500';
@endphp

<div x-data="{ hasAgreement: @js(old('perjanjian_baru')), agreementType: @js(old('jenis_dokumen')) }" class="space-y-6">
    <fieldset :disabled="selectedSubBidangKode !== 'F.07'" class="space-y-6">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label for="create-f07-tahun" class="mb-1.5 block text-xs font-medium text-slate-700">Tahun <span class="text-rose-500">*</span></label>
                <select id="create-f07-tahun" name="sekretariat_tahun" required class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    @for ($year = 2026; $year >= 2023; $year--)
                        <option value="{{ $year }}" @selected((string) old('sekretariat_tahun', now()->year) === (string) $year)>{{ $year }}</option>
                    @endfor
                </select>
                @error('sekretariat_tahun') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="create-f07-semester" class="mb-1.5 block text-xs font-medium text-slate-700">Periode Semester <span class="text-rose-500">*</span></label>
                <select id="create-f07-semester" name="semester" required class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    <option value="">Pilih periode</option>
                    <option value="I" @selected(old('semester') === 'I')>Semester I</option>
                    <option value="II" @selected(old('semester') === 'II')>Semester II</option>
                </select>
                @error('semester') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>
        <div>
            <label for="create-f07-eselon" class="mb-1.5 block text-xs font-medium text-slate-700">Eselon II / UPT <span class="text-rose-500">*</span></label>
            <input id="create-f07-eselon" name="eselon_ii_upt" type="text" value="{{ old('eselon_ii_upt', 'Balai KSDA Sulawesi Tengah') }}" maxlength="255" required class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            @error('eselon_ii_upt') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <fieldset class="space-y-2">
            <legend class="text-xs font-semibold text-slate-800">Apakah ada perjanjian kerja sama yang baru dilakukan pada periode tersebut? <span class="text-rose-500">*</span></legend>
            <p class="text-[11px] text-slate-500">Pilih kondisi yang terjadi pada periode tersebut.</p>
            <div class="flex flex-col gap-3 pt-1 sm:flex-row sm:gap-7">
                <label class="inline-flex items-center gap-2 text-xs text-slate-700"><input type="radio" name="perjanjian_baru" value="ya" x-model="hasAgreement" required class="text-blue-600 focus:ring-blue-500"><span>Ya, terdapat kerja sama baru</span></label>
                <label class="inline-flex items-center gap-2 text-xs text-slate-700"><input type="radio" name="perjanjian_baru" value="tidak" x-model="hasAgreement" @change="agreementType = null" required class="text-blue-600 focus:ring-blue-500"><span>Tidak ada perjanjian baru (Nihil)</span></label>
            </div>
        </fieldset>

        <div x-show="hasAgreement === 'tidak'" x-transition class="rounded border border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-600">Tidak ada perjanjian kerja sama baru pada periode ini. Pilih Simpan untuk mencatat Nihil.</div>

        <fieldset x-show="hasAgreement === 'ya'" x-transition :disabled="hasAgreement !== 'ya'" class="space-y-6">
            <fieldset class="space-y-2">
                <legend class="text-xs font-semibold text-slate-800">Pilih Jenis Dokumen Perjanjian Kerja Sama? <span class="text-rose-500">*</span></legend>
                <p class="text-[11px] text-slate-500">Pilih Potensi jika kerja sama sedang dalam proses permohonan.</p>
                <div class="flex flex-col gap-3 pt-1 sm:flex-row sm:flex-wrap sm:gap-6">
                    @foreach ($documents as $value => $label)
                        <label class="inline-flex items-center gap-2 text-xs text-slate-700"><input type="radio" name="jenis_dokumen" value="{{ $value }}" x-model="agreementType" @checked(old('jenis_dokumen') === $value) :required="hasAgreement === 'ya'" class="text-blue-600 focus:ring-blue-500"><span>{{ $label }}</span></label>
                    @endforeach
                </div>
            </fieldset>

            <fieldset x-show="agreementType" x-transition :disabled="!agreementType" class="space-y-6">
            <div x-show="agreementType" x-transition class="rounded border border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-600">Entri ini membentuk catatan baru sesuai jenis dokumen yang dipilih. Perpanjangan, perubahan, atau tindak lanjut berikutnya dicatat melalui Detail Kerja Sama.</div>

            <fieldset class="space-y-4 rounded border border-slate-200 p-5">
                <legend class="px-1 text-xs font-bold uppercase text-slate-800">Informasi Mitra</legend>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="create-f07-mitra" class="mb-1.5 block text-xs font-medium text-slate-700">Mitra Kerja Sama <span class="text-rose-500">*</span></label>
                        <input id="create-f07-mitra" name="mitra_kerja_sama" type="text" value="{{ old('mitra_kerja_sama') }}" maxlength="255" placeholder="Nama mitra kerja sama" :required="hasAgreement === 'ya'" class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <p class="{{ $helperTextClass }}">Contoh: Dinas Lingkungan Hidup Provinsi Sulawesi Tengah.</p>
                        @error('mitra_kerja_sama') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="create-f07-tipe-mitra" class="mb-1.5 block text-xs font-medium text-slate-700">Tipe Mitra <span class="text-rose-500">*</span></label>
                        <select id="create-f07-tipe-mitra" name="tipe_mitra" :required="hasAgreement === 'ya'" class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="">Pilih tipe mitra</option>
                            @foreach ($tipeMitra as $type)
                                <option value="{{ $type }}" @selected(old('tipe_mitra') === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('tipe_mitra') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            <fieldset class="space-y-4 rounded border border-slate-200 p-5">
                <legend class="px-1 text-xs font-bold uppercase text-slate-800">Informasi Kerja Sama</legend>
                <div>
                    <span class="mb-2 block text-xs font-medium text-slate-700">Status Kerja Sama <span class="text-rose-500">*</span></span>
                    <div class="flex flex-col gap-3">
                        @foreach ($statuses as $status)
                            <label class="inline-flex items-center gap-2 text-xs text-slate-700"><input type="radio" name="status_kerja_sama" value="{{ $status }}" @checked(old('status_kerja_sama', 'Aktif') === $status) :required="hasAgreement === 'ya'" class="text-blue-600 focus:ring-blue-500"><span>{{ $status }}</span></label>
                        @endforeach
                    </div>
                    <p class="{{ $helperTextClass }}">Pilih status sesuai kondisi kerja sama pada periode pelaporan.</p>
                </div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    @foreach ([['nomor_perjanjian', 'Nomor Perjanjian', 'text'], ['tanggal_mulai', 'Tanggal Mulai', 'date'], ['tanggal_berakhir', 'Tanggal Berakhir', 'date']] as [$name, $label, $type])
                        <div>
                            <label for="create-f07-{{ $name }}" class="mb-1.5 block text-xs font-medium text-slate-700">{{ $label }} <span class="text-rose-500">*</span></label>
                            <input id="create-f07-{{ $name }}" type="{{ $type }}" name="{{ $name }}" value="{{ old($name) }}" @if ($name === 'nomor_perjanjian') :placeholder="agreementType === 'Potensi' ? 'Nomor referensi potensi' : agreementType + '.123/BKSDA/2024'" @endif :required="hasAgreement === 'ya'" class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            @if ($name === 'nomor_perjanjian')
                                <p class="{{ $helperTextClass }}">Contoh PKS: PKS.123/BKSDA/2024; contoh MOU: MOU.123/BKSDA/2024. Untuk Potensi, isi nomor referensi jika tersedia.</p>
                            @elseif ($name === 'tanggal_mulai')
                                <p class="{{ $helperTextClass }}">Isi tanggal mulai sesuai dokumen kerja sama.</p>
                            @else
                                <p class="{{ $helperTextClass }}">Isi tanggal berakhir sesuai dokumen kerja sama.</p>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div>
                    <label for="create-f07-judul" class="mb-1.5 block text-xs font-medium text-slate-700">Judul Kerja Sama <span class="text-rose-500">*</span></label>
                    <input id="create-f07-judul" name="judul_kerja_sama" value="{{ old('judul_kerja_sama') }}" placeholder="Judul kerja sama" :required="hasAgreement === 'ya'" class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label for="create-f07-ruang-lingkup" class="mb-1.5 block text-xs font-medium text-slate-700">Ruang Lingkup <span class="text-rose-500">*</span></label>
                    <textarea id="create-f07-ruang-lingkup" name="ruang_lingkup" rows="3" placeholder="Ruang lingkup kerja sama" :required="hasAgreement === 'ya'" class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">{{ old('ruang_lingkup') }}</textarea>
                </div>
            </fieldset>

            <fieldset class="space-y-4 rounded border border-slate-200 p-5">
                <legend class="px-1 text-xs font-bold uppercase text-slate-800">Dukungan Kinerja KLHK (referensi)</legend>
                <div>
                    <span class="mb-2 block text-xs text-slate-500">Pilih satu atau lebih indikator yang didukung.</span>
                    <details class="group rounded border border-slate-300 bg-white">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-3 py-3 text-xs font-medium text-slate-700 hover:bg-slate-50">
                            <span>Pilih dukungan kinerja KLHK</span>
                            <i class="fa-solid fa-chevron-down text-slate-400 transition group-open:rotate-180" aria-hidden="true"></i>
                        </summary>
                        <div class="border-t border-slate-200 p-3">
                            <label class="sr-only" for="create-f07-dukungan-search">Cari dukungan kinerja</label>
                            <input id="create-f07-dukungan-search" type="search" data-support-search autocomplete="off" placeholder="Cari dukungan kinerja..." class="mb-3 w-full rounded border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <div class="max-h-64 space-y-2 overflow-y-auto">
                        @foreach ($dukunganKinerja as $key => $indicator)
                            <label data-support-option data-search-text="{{ strtolower($key . ' ' . $indicator) }}" class="flex items-start gap-2 text-xs text-slate-700"><input type="checkbox" name="dukungan_kinerja[]" value="{{ $key }}" @checked(in_array($key, old('dukungan_kinerja', []))) class="mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500"><span><strong>{{ $key }}</strong> — {{ $indicator }}</span></label>
                        @endforeach
                            <p data-support-empty hidden class="text-xs text-slate-500">Dukungan kinerja tidak ditemukan.</p>
                            </div>
                        </div>
                    </details>
                    <p class="{{ $helperTextClass }}">Ketik kata kunci untuk mencari indikator; pilihan dapat lebih dari satu.</p>
                </div>
                <div>
                    <label for="create-f07-catatan-kinerja" class="mb-1.5 block text-xs font-medium text-slate-700">Catatan Dukungan Kinerja (teks bebas)</label>
                    <textarea id="create-f07-catatan-kinerja" name="catatan_dukungan_kinerja" rows="2" placeholder="Catatan IKU/IKP/IKK bila belum tersedia di referensi" class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">{{ old('catatan_dukungan_kinerja') }}</textarea>
                    <p class="{{ $helperTextClass }}">Isi keterangan tambahan jika indikator belum tersedia pada daftar referensi.</p>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="create-f07-luas" class="mb-1.5 block text-xs font-medium text-slate-700">Luas Area</label>
                        <div class="flex overflow-hidden rounded border border-slate-300"><input id="create-f07-luas" type="number" min="0" step="0.01" name="luas_area" value="{{ old('luas_area') }}" class="min-w-0 w-full px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20"><span class="flex items-center border-l border-slate-300 bg-slate-50 px-3 text-xs text-slate-500">Ha</span></div>
                        <p class="{{ $helperTextClass }}">Isi luas area dalam hektare sesuai dokumen; kosongkan jika tidak tersedia.</p>
                    </div>
                    <div>
                        <label for="create-f07-komitmen" class="mb-1.5 block text-xs font-medium text-slate-700">Nilai Komitmen Pendanaan</label>
                        <div class="flex overflow-hidden rounded border border-slate-300"><span class="flex items-center border-r border-slate-300 bg-slate-50 px-3 text-xs text-slate-500">Rp</span><input id="create-f07-komitmen" type="number" min="0" step="0.01" name="nilai_komitmen" value="{{ old('nilai_komitmen') }}" class="min-w-0 w-full px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20"></div>
                        <p class="{{ $helperTextClass }}">Isi nilai dalam rupiah tanpa mengetikkan simbol Rp; kosongkan jika tidak tersedia.</p>
                    </div>
                </div>
                <div>
                    <label for="create-f07-dokumen" class="mb-1.5 block text-xs font-medium text-slate-700">Dokumen Kerja Sama (PDF)</label>
                    <input id="create-f07-dokumen" type="file" name="dokumen_kerja_sama" accept=".pdf,application/pdf" class="w-full rounded border border-slate-300 bg-white text-sm file:mr-3 file:border-0 file:bg-slate-100 file:px-3 file:py-2">
                    <p class="{{ $helperTextClass }}">Unggah dokumen perjanjian yang relevan dalam format PDF, maksimal 20 MB.</p>
                </div>
            </fieldset>

            <fieldset class="space-y-4 rounded border border-slate-200 p-5">
                <legend class="px-1 text-xs font-bold uppercase text-slate-800">Lokasi Kerja Sama</legend>
                <div>
                    <label for="create-f07-provinsi" class="mb-1.5 block text-xs font-medium text-slate-700">Provinsi <span class="text-rose-500">*</span></label>
                    <div id="create-f07-provinsi-picker" data-province-picker class="relative">
                        <div class="flex">
                            <input id="create-f07-provinsi" name="provinsi" type="text" value="{{ old('provinsi') }}" autocomplete="off" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="create-f07-daftar-provinsi" placeholder="Ketik atau cari provinsi" :required="hasAgreement === 'ya'" class="w-full rounded-l border border-r-0 border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <button type="button" data-province-toggle aria-label="Tampilkan daftar provinsi" aria-haspopup="listbox" aria-expanded="false" class="rounded-r border border-slate-300 bg-slate-50 px-3 text-slate-500 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                <i class="fa-solid fa-chevron-down text-xs" aria-hidden="true"></i>
                            </button>
                        </div>
                        <ul id="create-f07-daftar-provinsi" role="listbox" hidden class="absolute z-30 mt-1 max-h-56 w-full overflow-y-auto rounded border border-slate-300 bg-white py-1 shadow-lg">
                            @foreach ($provinces as $province)
                                <li role="presentation"><button type="button" role="option" aria-selected="false" data-province-option data-value="{{ $province }}" class="block w-full px-3 py-2 text-left text-sm text-slate-700 hover:bg-blue-600 hover:text-white">{{ $province }}</button></li>
                            @endforeach
                            <li role="presentation" data-province-empty hidden><span class="block px-3 py-2 text-sm text-slate-500">Provinsi tidak ditemukan.</span></li>
                        </ul>
                    </div>
                    <p class="{{ $helperTextClass }}">Ketik nama provinsi atau pilih dari daftar. Contoh: Sulawesi Tengah.</p>
                </div>
                <div>
                    <label for="create-f07-lokasi-detail" class="mb-1.5 block text-xs font-medium text-slate-700">Lokasi Detail</label>
                    <textarea id="create-f07-lokasi-detail" name="lokasi_detail" rows="2" placeholder="Masukan detail lokasi kerja sama di sini" class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">{{ old('lokasi_detail') }}</textarea>
                    <p class="{{ $helperTextClass }}">Tuliskan kabupaten/kota, kecamatan, desa, atau nama lokasi area kerja sama.</p>
                </div>
                <div>
                    <label for="create-f07-shapefile" class="mb-1.5 block text-xs font-medium text-slate-700">Unggah Point/Polygon Area (Shapefile ZIP)</label>
                    <p class="{{ $helperTextClass }} mb-2">Sertakan file .shp, .dbf, .prj, .shx, dan .cpg dalam satu arsip ZIP yang menunjukkan area kerja sama.</p>
                    <input id="create-f07-shapefile" type="file" name="shapefile_kerja_sama" accept=".zip,application/zip" class="w-full rounded border border-slate-300 bg-white text-sm file:mr-3 file:border-0 file:bg-slate-100 file:px-3 file:py-2">
                    <p class="{{ $helperTextClass }}">Unggah satu file ZIP; maksimal 10 MB. Kosongkan jika shapefile belum tersedia.</p>
                </div>
            </fieldset>
            </fieldset>
        </fieldset>

        <div x-show="hasAgreement === 'ya'" x-transition class="rounded border border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-600">Data capaian dan evaluasi (output, outcome, dampak, estimasi manfaat) diisi melalui Evaluasi Tahunan dan Capaian Umum.</div>
        <div x-show="hasAgreement !== ''" x-transition>
            <label for="create-f07-keterangan" class="mb-1.5 block text-xs font-semibold text-slate-700">Keterangan</label>
            <textarea id="create-f07-keterangan" name="keterangan" rows="3" maxlength="2000" placeholder="Masukan disini untuk informasi lainnya" :disabled="selectedSubBidangKode !== 'F.07' || hasAgreement === ''" class="w-full rounded border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">{{ old('keterangan') }}</textarea>
        </div>
        <div x-show="hasAgreement !== ''" x-transition class="flex justify-end border-t border-slate-200 pt-4">
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-amber-400 px-6 py-3 text-xs font-bold text-slate-950 shadow-md transition hover:bg-amber-300"><i class="fa-solid fa-floppy-disk" aria-hidden="true"></i> Simpan Data Sekretariat</button>
        </div>
    </fieldset>
</div>

<script>
    (() => {
        document.querySelectorAll('[data-support-search]').forEach((search) => {
            const list = search.closest('details')?.querySelector('[data-support-option]')?.parentElement;
            if (!list) return;

            const options = [...list.querySelectorAll('[data-support-option]')];
            const emptyState = list.querySelector('[data-support-empty]');
            search.addEventListener('input', () => {
                const query = search.value.trim().toLocaleLowerCase('id');
                options.forEach((option) => {
                    option.hidden = !option.dataset.searchText.toLocaleLowerCase('id').includes(query);
                });
                emptyState.hidden = options.some((option) => !option.hidden);
            });
        });

        const picker = document.getElementById('create-f07-provinsi-picker');
        if (!picker) return;

        const input = picker.querySelector('[role="combobox"]');
        const toggle = picker.querySelector('[data-province-toggle]');
        const menu = picker.querySelector('[role="listbox"]');
        const options = [...picker.querySelectorAll('[data-province-option]')];
        const emptyState = picker.querySelector('[data-province-empty]');
        let activeIndex = -1;

        const visibleOptions = () => options.filter((option) => !option.closest('li').hidden);
        const setActive = (index) => {
            const visible = visibleOptions();
            activeIndex = visible.length ? (index + visible.length) % visible.length : -1;
            options.forEach((option) => {
                const active = option === visible[activeIndex];
                option.classList.toggle('bg-blue-600', active);
                option.classList.toggle('text-white', active);
                option.setAttribute('aria-selected', String(active));
            });
            visible[activeIndex]?.scrollIntoView({ block: 'nearest' });
        };
        const filterOptions = () => {
            const query = input.value.trim().toLocaleLowerCase('id');
            options.forEach((option) => {
                option.closest('li').hidden = !option.dataset.value.toLocaleLowerCase('id').includes(query);
            });
            const visible = visibleOptions();
            emptyState.hidden = visible.length > 0;
            setActive(visible.findIndex((option) => option.dataset.value === input.value));
            if (activeIndex < 0 && visible.length) setActive(0);
        };
        const openMenu = () => {
            menu.hidden = false;
            input.setAttribute('aria-expanded', 'true');
            toggle.setAttribute('aria-expanded', 'true');
            filterOptions();
        };
        const closeMenu = () => {
            menu.hidden = true;
            input.setAttribute('aria-expanded', 'false');
            toggle.setAttribute('aria-expanded', 'false');
            activeIndex = -1;
            options.forEach((option) => {
                option.classList.remove('bg-blue-600', 'text-white');
                option.setAttribute('aria-selected', 'false');
            });
        };
        const chooseOption = (option) => {
            input.value = option.dataset.value;
            closeMenu();
            input.focus();
        };

        input.addEventListener('focus', openMenu);
        input.addEventListener('input', openMenu);
        input.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowDown') {
                event.preventDefault();
                if (menu.hidden) openMenu();
                else setActive(activeIndex + 1);
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                setActive(activeIndex - 1);
            } else if (event.key === 'Enter' && !menu.hidden && activeIndex >= 0) {
                event.preventDefault();
                chooseOption(visibleOptions()[activeIndex]);
            } else if (event.key === 'Escape') {
                closeMenu();
            }
        });
        toggle.addEventListener('click', () => {
            if (menu.hidden) {
                input.focus();
                openMenu();
            } else {
                closeMenu();
            }
        });
        options.forEach((option) => option.addEventListener('click', () => chooseOption(option)));
        document.addEventListener('pointerdown', (event) => {
            if (!picker.contains(event.target)) closeMenu();
        });
    })();
</script>