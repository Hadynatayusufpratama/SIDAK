@php
    $embedded = $embedded ?? false;
    $idPrefix = $embedded ? 'create-f08-' : '';
    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
@endphp

<div class="space-y-7">
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="{{ $idPrefix }}tahun" class="mb-1.5 block text-xs font-medium text-slate-700">Tahun <span class="text-rose-500">*</span></label>
            <select id="{{ $idPrefix }}tahun" name="sekretariat_tahun" @if ($embedded) :disabled="selectedSubBidangKode !== 'F.08'" @else required @endif class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                @for ($year = 2026; $year >= 2023; $year--)
                    <option value="{{ $year }}" @selected((string) old('sekretariat_tahun', now()->year) === (string) $year)>{{ $year }}</option>
                @endfor
            </select>
            @error('sekretariat_tahun') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="{{ $idPrefix }}bulan" class="mb-1.5 block text-xs font-medium text-slate-700">Periode Bulan <span class="text-rose-500">*</span></label>
            @php($selectedMonth = (string) old('sekretariat_bulan', ''))
            <div id="{{ $idPrefix }}month-picker" data-month-picker class="relative">
                <input id="{{ $idPrefix }}bulan" name="sekretariat_bulan" type="hidden" value="{{ $selectedMonth }}" @if ($embedded) :disabled="selectedSubBidangKode !== 'F.08'" @endif>
                <button type="button" data-month-toggle aria-haspopup="listbox" aria-expanded="false" aria-controls="{{ $idPrefix }}daftar-bulan" @if ($embedded) :disabled="selectedSubBidangKode !== 'F.08'" @endif class="flex w-full items-center justify-between rounded border border-slate-300 bg-white px-3 py-2.5 text-left text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 disabled:cursor-not-allowed disabled:bg-slate-100">
                    <span data-month-label>{{ $months[(int) $selectedMonth] ?? 'Pilih periode' }}</span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-500" aria-hidden="true"></i>
                </button>
                <div data-month-menu hidden class="absolute z-30 mt-1 w-full overflow-hidden rounded border border-slate-300 bg-white shadow-lg">
                    <div class="border-b border-slate-200 p-2">
                        <input type="search" data-month-search autocomplete="off" aria-label="Cari bulan" placeholder="Cari bulan" class="w-full rounded border border-slate-300 px-2.5 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>
                    <ul id="{{ $idPrefix }}daftar-bulan" role="listbox" class="max-h-56 overflow-y-auto py-1">
                        @foreach ($months as $month => $monthLabel)
                            <li role="presentation">
                                <button type="button" role="option" aria-selected="{{ $selectedMonth === (string) $month ? 'true' : 'false' }}" data-month-option data-value="{{ $month }}" data-label="{{ $monthLabel }}" class="block w-full px-3 py-2 text-left text-sm {{ $selectedMonth === (string) $month ? 'bg-blue-600 text-white' : 'text-slate-700 hover:bg-blue-600 hover:text-white' }}">{{ $monthLabel }}</button>
                            </li>
                        @endforeach
                        <li role="presentation" data-month-empty hidden><span class="block px-3 py-2 text-sm text-slate-500">Bulan tidak ditemukan</span></li>
                    </ul>
                </div>
                <p data-month-error hidden class="mt-1 text-xs text-rose-600">Pilih periode bulan.</p>
            </div>
            @error('sekretariat_bulan') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label for="{{ $idPrefix }}unit-kerja" class="mb-1.5 block text-xs font-medium text-slate-700">Satuan Kerja <span class="text-rose-500">*</span></label>
        <input id="{{ $idPrefix }}unit-kerja" name="unit_kerja" type="text" value="{{ old('unit_kerja', 'Balai KSDA Sulawesi Tengah') }}" maxlength="255" placeholder="Masukkan nama satuan kerja" @if ($embedded) :disabled="selectedSubBidangKode !== 'F.08'" @else required @endif class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
        @error('unit_kerja') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        @foreach ($perijinan as $key => $label)
            <fieldset class="rounded border border-slate-200 px-5 pb-5 pt-3 shadow-sm">
                <legend class="px-1 text-xs font-bold uppercase text-blue-600">{{ $label }}</legend>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach (['dalam_negeri' => 'Dalam Negeri', 'luar_negeri' => 'Luar Negeri'] as $cakupan => $cakupanLabel)
                        <div>
                            <label for="{{ $idPrefix }}{{ $key }}-{{ $cakupan }}" class="mb-1.5 block text-xs font-medium text-slate-700">{{ $cakupanLabel }} <span class="text-rose-500">*</span></label>
                            <div class="flex h-10 w-full overflow-hidden rounded border border-slate-300">
                                <span class="flex shrink-0 items-center border-r border-slate-300 bg-slate-50 px-2 text-[11px] text-slate-500">Jumlah</span>
                                <input id="{{ $idPrefix }}{{ $key }}-{{ $cakupan }}" name="perijinan[{{ $key }}][{{ $cakupan }}]" type="number" min="0" max="100000" step="1" inputmode="numeric" value="{{ old("perijinan.{$key}.{$cakupan}") }}" @if ($embedded) :disabled="selectedSubBidangKode !== 'F.08'" @else required @endif class="min-w-0 w-full px-2 text-sm tabular-nums focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                <span class="flex shrink-0 items-center border-l border-slate-300 bg-slate-50 px-2 text-[11px] text-slate-500">Orang</span>
                            </div>
                            @error("perijinan.{$key}.{$cakupan}") <p class="mt-1 text-[10px] text-rose-600">Jumlah tidak valid.</p> @enderror
                        </div>
                    @endforeach
                </div>
            </fieldset>
        @endforeach
    </div>
</div>

<script>
    (() => {
        const picker = document.getElementById(@json($idPrefix . 'month-picker'));
        if (!picker) return;

        const valueInput = picker.querySelector('input[name="sekretariat_bulan"]');
        const toggle = picker.querySelector('[data-month-toggle]');
        const label = picker.querySelector('[data-month-label]');
        const menu = picker.querySelector('[data-month-menu]');
        const search = picker.querySelector('[data-month-search]');
        const options = [...picker.querySelectorAll('[data-month-option]')];
        const emptyState = picker.querySelector('[data-month-empty]');
        const error = picker.querySelector('[data-month-error]');
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
            const query = search.value.trim().toLocaleLowerCase('id');
            options.forEach((option) => {
                option.closest('li').hidden = !option.dataset.label.toLocaleLowerCase('id').includes(query);
            });
            const visible = visibleOptions();
            emptyState.hidden = visible.length > 0;
            setActive(visible.findIndex((option) => option.dataset.value === valueInput.value));
            if (activeIndex < 0 && visible.length) setActive(0);
        };
        const openMenu = () => {
            menu.hidden = false;
            toggle.setAttribute('aria-expanded', 'true');
            search.value = '';
            filterOptions();
            search.focus();
        };
        const closeMenu = () => {
            menu.hidden = true;
            toggle.setAttribute('aria-expanded', 'false');
            activeIndex = -1;
            setActive(-1);
        };
        const chooseOption = (option) => {
            valueInput.value = option.dataset.value;
            label.textContent = option.dataset.label;
            error.hidden = true;
            toggle.removeAttribute('aria-invalid');
            options.forEach((item) => item.setAttribute('aria-selected', String(item === option)));
            closeMenu();
        };

        toggle.addEventListener('click', () => menu.hidden ? openMenu() : closeMenu());
        search.addEventListener('input', filterOptions);
        search.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowDown') {
                event.preventDefault();
                setActive(activeIndex + 1);
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                setActive(activeIndex - 1);
            } else if (event.key === 'Enter' && activeIndex >= 0) {
                event.preventDefault();
                chooseOption(visibleOptions()[activeIndex]);
            } else if (event.key === 'Escape') {
                closeMenu();
                toggle.focus();
            }
        });
        options.forEach((option) => option.addEventListener('click', () => chooseOption(option)));
        document.addEventListener('pointerdown', (event) => {
            if (!picker.contains(event.target)) closeMenu();
        });
        picker.closest('form')?.addEventListener('submit', (event) => {
            if (!valueInput.disabled && !valueInput.value) {
                event.preventDefault();
                error.hidden = false;
                toggle.setAttribute('aria-invalid', 'true');
                openMenu();
            }
        });
    })();
</script>