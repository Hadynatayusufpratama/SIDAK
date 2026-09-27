@php
    $embedded = $embedded ?? false;
    $activeCode = $activeCode ?? 'F.04';
    $idPrefix = $idPrefix ?? ($embedded ? 'create-' : '');
@endphp

<div>
    <label for="{{ $idPrefix }}jenis-jabatan-fungsional" class="mb-1.5 block text-xs font-medium text-slate-700">Jenis Jabatan Fungsional Tertentu <span class="text-rose-500">*</span></label>
    <div id="{{ $idPrefix }}job-picker" data-job-picker class="relative">
        <div class="flex">
            <input id="{{ $idPrefix }}jenis-jabatan-fungsional" name="jabatan_fungsional" type="text" value="{{ old('jabatan_fungsional') }}" autocomplete="off" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="{{ $idPrefix }}daftar-jabatan-fungsional" placeholder="Pilih atau cari jenis jabatan" @if ($embedded) :disabled="selectedSubBidangKode !== '{{ $activeCode }}'" @else required @endif class="w-full rounded-l border border-r-0 border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            <button type="button" data-job-toggle aria-label="Tampilkan jenis jabatan" class="rounded-r border border-slate-300 bg-slate-50 px-3 text-slate-500 hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50" @if ($embedded) :disabled="selectedSubBidangKode !== '{{ $activeCode }}'" @endif>
                <i class="fa-solid fa-chevron-down text-xs" aria-hidden="true"></i>
            </button>
        </div>
        <ul id="{{ $idPrefix }}daftar-jabatan-fungsional" role="listbox" hidden class="absolute z-30 mt-1 max-h-56 w-full overflow-y-auto rounded border border-slate-300 bg-white py-1 shadow-lg">
            @foreach ($jabatanFungsional as $jabatan)
                <li role="presentation">
                    <button type="button" role="option" aria-selected="false" data-job-option data-value="{{ $jabatan }}" class="block w-full px-3 py-2 text-left text-sm text-slate-700 hover:bg-blue-600 hover:text-white">{{ $jabatan }}</button>
                </li>
            @endforeach
            <li role="presentation" data-job-empty hidden><span class="block px-3 py-2 text-sm text-slate-500">Tidak ada jabatan yang cocok</span></li>
        </ul>
    </div>
    @error('jabatan_fungsional') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
</div>

<script>
    (() => {
        const picker = document.getElementById(@json($idPrefix . 'job-picker'));
        if (!picker || !picker.matches('[data-job-picker]')) return;

        const search = picker.querySelector('[role="combobox"]');
        const menu = picker.querySelector('[role="listbox"]');
        const toggle = picker.querySelector('[data-job-toggle]');
        const options = [...picker.querySelectorAll('[data-job-option]')];
        const emptyState = picker.querySelector('[data-job-empty]');
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
        const openMenu = () => {
            menu.hidden = false;
            search.setAttribute('aria-expanded', 'true');
            const query = search.value.trim().toLocaleLowerCase('id');
            options.forEach((option) => {
                option.closest('li').hidden = !option.dataset.value.toLocaleLowerCase('id').includes(query);
            });
            const visible = visibleOptions();
            emptyState.hidden = visible.length > 0;
            setActive(visible.findIndex((option) => option.dataset.value === search.value));
            if (activeIndex < 0 && visible.length) setActive(0);
        };
        const closeMenu = () => {
            menu.hidden = true;
            search.setAttribute('aria-expanded', 'false');
            activeIndex = -1;
            setActive(-1);
        };
        const chooseOption = (option) => {
            search.value = option.dataset.value;
            closeMenu();
            search.focus();
        };

        search.addEventListener('focus', openMenu);
        search.addEventListener('input', openMenu);
        search.addEventListener('keydown', (event) => {
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
                search.focus();
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