@php
    $embedded = $embedded ?? false;
    $idPrefix = $embedded ? 'create-' : '';
@endphp

<div data-secretariat-fields class="space-y-7">
    <div class="space-y-5">
        <div>
            <label for="{{ $idPrefix }}sekretariat-tahun" class="mb-1.5 block text-xs font-medium text-slate-700">Tahun <span class="text-rose-500">*</span></label>
            <select id="{{ $idPrefix }}sekretariat-tahun" name="sekretariat_tahun" @if ($embedded) :disabled="selectedSubBidangKode !== 'F.01'" @else required @endif class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                @for ($year = 2026; $year >= 2023; $year--)
                    <option value="{{ $year }}" @selected((string) old('sekretariat_tahun', now()->year) === (string) $year)>{{ $year }}</option>
                @endfor
            </select>
            @error('sekretariat_tahun') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="{{ $idPrefix }}unit-kerja" class="mb-1.5 block text-xs font-medium text-slate-700">Satuan Kerja <span class="text-rose-500">*</span></label>
            <input id="{{ $idPrefix }}unit-kerja" name="unit_kerja" type="text" list="{{ $idPrefix }}daftar-satuan-kerja" value="{{ old('unit_kerja', 'Balai KSDA Sulawesi Tengah') }}" maxlength="255" placeholder="Masukkan nama satuan kerja" @if ($embedded) :disabled="selectedSubBidangKode !== 'F.01'" @else required @endif class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            <datalist id="{{ $idPrefix }}daftar-satuan-kerja"><option value="Balai KSDA Sulawesi Tengah"></datalist>
            @error('unit_kerja') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <section class="space-y-3">
        <h3 class="text-xs font-bold uppercase text-slate-800">Jabatan Struktural</h3>
        <div class="space-y-5">
            @foreach ($struktural as $key => $label)
                <fieldset class="rounded border border-slate-200 px-5 pb-5 pt-3 shadow-sm">
                    <legend class="px-1 text-xs font-bold uppercase text-blue-600">{{ $label }}</legend>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @foreach (['laki_laki' => 'Laki-laki', 'perempuan' => 'Perempuan'] as $gender => $genderLabel)
                            <div>
                                <label for="{{ $idPrefix }}struktural-{{ $key }}-{{ $gender }}" class="mb-1.5 block text-xs font-medium text-slate-700">{{ $genderLabel }} <span class="text-rose-500">*</span></label>
                                <div class="flex h-10 w-full overflow-hidden rounded border border-slate-300">
                                    <span class="flex shrink-0 items-center border-r border-slate-300 bg-slate-50 px-2 text-[11px] text-slate-500">Jumlah</span>
                                    <input id="{{ $idPrefix }}struktural-{{ $key }}-{{ $gender }}" name="struktural[{{ $key }}][{{ $gender }}]" type="number" min="0" max="100000" step="1" inputmode="numeric" data-count="{{ $gender }}" value="{{ old("struktural.{$key}.{$gender}", 0) }}" @if ($embedded) :disabled="selectedSubBidangKode !== 'F.01'" @else required @endif class="min-w-0 w-full px-2 text-sm tabular-nums focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                    <span class="flex shrink-0 items-center border-l border-slate-300 bg-slate-50 px-2 text-[11px] text-slate-500">Orang</span>
                                </div>
                                @error("struktural.{$key}.{$gender}") <p class="mt-1 text-[10px] text-rose-600">Jumlah tidak valid.</p> @enderror
                            </div>
                        @endforeach
                    </div>
                </fieldset>
            @endforeach
        </div>
    </section>

    <section class="space-y-3">
        <h3 class="text-xs font-bold uppercase text-slate-800">Jabatan Fungsional Umum &amp; Tertentu</h3>
        <div class="space-y-5">
            @foreach ($fungsional as $key => $label)
                <fieldset class="rounded border border-slate-200 px-5 pb-5 pt-3 shadow-sm">
                    <legend class="px-1 text-xs font-bold uppercase text-blue-600">{{ $label }}</legend>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @foreach (['laki_laki' => 'Laki-laki', 'perempuan' => 'Perempuan'] as $gender => $genderLabel)
                            <div>
                                <label for="{{ $idPrefix }}fungsional-{{ $key }}-{{ $gender }}" class="mb-1.5 block text-xs font-medium text-slate-700">{{ $genderLabel }} <span class="text-rose-500">*</span></label>
                                <div class="flex h-10 w-full overflow-hidden rounded border border-slate-300">
                                    <span class="flex shrink-0 items-center border-r border-slate-300 bg-slate-50 px-2 text-[11px] text-slate-500">Jumlah</span>
                                    <input id="{{ $idPrefix }}fungsional-{{ $key }}-{{ $gender }}" name="fungsional[{{ $key }}][{{ $gender }}]" type="number" min="0" max="100000" step="1" inputmode="numeric" data-count="{{ $gender }}" value="{{ old("fungsional.{$key}.{$gender}", 0) }}" @if ($embedded) :disabled="selectedSubBidangKode !== 'F.01'" @else required @endif class="min-w-0 w-full px-2 text-sm tabular-nums focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                    <span class="flex shrink-0 items-center border-l border-slate-300 bg-slate-50 px-2 text-[11px] text-slate-500">Orang</span>
                                </div>
                                @error("fungsional.{$key}.{$gender}") <p class="mt-1 text-[10px] text-rose-600">Jumlah tidak valid.</p> @enderror
                            </div>
                        @endforeach
                    </div>
                </fieldset>
            @endforeach
        </div>
    </section>

    <div class="flex flex-wrap gap-4 rounded-xl border border-slate-200 bg-white px-4 py-3 text-xs text-slate-600">
        <span>Laki-laki: <strong data-total-laki class="tabular-nums text-slate-900">0</strong></span>
        <span>Perempuan: <strong data-total-perempuan class="tabular-nums text-slate-900">0</strong></span>
        <span>Total: <strong data-total-pegawai class="tabular-nums text-emerald-700">0</strong></span>
    </div>
</div>

<script>
    document.querySelectorAll('[data-secretariat-fields]').forEach((fields) => {
        const countInputs = fields.querySelectorAll('[data-count]');
        const totals = {
            laki_laki: fields.querySelector('[data-total-laki]'),
            perempuan: fields.querySelector('[data-total-perempuan]'),
            pegawai: fields.querySelector('[data-total-pegawai]')
        };

        if (!totals.laki_laki || !totals.perempuan || !totals.pegawai) return;

        const updateTotals = () => {
            const counts = { laki_laki: 0, perempuan: 0 };
            countInputs.forEach((input) => {
                counts[input.dataset.count] += Number(input.value) || 0;
            });
            totals.laki_laki.textContent = counts.laki_laki.toLocaleString('id-ID');
            totals.perempuan.textContent = counts.perempuan.toLocaleString('id-ID');
            totals.pegawai.textContent = (counts.laki_laki + counts.perempuan).toLocaleString('id-ID');
        };

        countInputs.forEach((input) => input.addEventListener('input', updateTotals));
        updateTotals();
    });
</script>