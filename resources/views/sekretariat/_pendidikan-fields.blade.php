@php
    $embedded = $embedded ?? false;
    $activeCode = $activeCode ?? 'F.02';
    $includePeriodAndUnit = $includePeriodAndUnit ?? true;
    $idPrefix = $idPrefix ?? ($embedded ? 'create-' : '');
@endphp

<div class="space-y-7">
    @if ($includePeriodAndUnit)
    <div class="space-y-5">
        <div>
            <label for="{{ $idPrefix }}pendidikan-tahun" class="mb-1.5 block text-xs font-medium text-slate-700">Tahun <span class="text-rose-500">*</span></label>
            <select id="{{ $idPrefix }}pendidikan-tahun" name="sekretariat_tahun" @if ($embedded) :disabled="selectedSubBidangKode !== '{{ $activeCode }}'" @else required @endif class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                @for ($year = 2026; $year >= 2023; $year--)
                    <option value="{{ $year }}" @selected((string) old('sekretariat_tahun', now()->year) === (string) $year)>{{ $year }}</option>
                @endfor
            </select>
            @error('sekretariat_tahun') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="{{ $idPrefix }}pendidikan-unit-kerja" class="mb-1.5 block text-xs font-medium text-slate-700">Satuan Kerja <span class="text-rose-500">*</span></label>
            <input id="{{ $idPrefix }}pendidikan-unit-kerja" name="unit_kerja" type="text" value="{{ old('unit_kerja', 'Balai KSDA Sulawesi Tengah') }}" maxlength="255" placeholder="Masukkan nama satuan kerja" @if ($embedded) :disabled="selectedSubBidangKode !== '{{ $activeCode }}'" @else required @endif class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            @error('unit_kerja') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        @foreach ($pendidikan as $key => $label)
            <fieldset class="rounded border border-slate-200 px-5 pb-5 pt-3 shadow-sm">
                <legend class="px-1 text-xs font-bold uppercase text-blue-600">{{ $label }}</legend>
                <div class="space-y-4">
                    @foreach (['laki_laki' => 'Laki-laki', 'perempuan' => 'Perempuan'] as $gender => $genderLabel)
                        <div>
                            <label for="{{ $idPrefix }}pendidikan-{{ $key }}-{{ $gender }}" class="mb-1.5 block text-xs font-medium text-slate-700">{{ $genderLabel }} <span class="text-rose-500">*</span></label>
                            <div class="flex h-10 w-full overflow-hidden rounded border border-slate-300">
                                <span class="flex shrink-0 items-center border-r border-slate-300 bg-slate-50 px-2 text-[11px] text-slate-500">Jumlah</span>
                                <input id="{{ $idPrefix }}pendidikan-{{ $key }}-{{ $gender }}" name="pendidikan[{{ $key }}][{{ $gender }}]" type="number" min="0" max="100000" step="1" inputmode="numeric" value="{{ old("pendidikan.{$key}.{$gender}") }}" @if ($embedded) :disabled="selectedSubBidangKode !== '{{ $activeCode }}'" @else required @endif class="min-w-0 w-full px-2 text-sm tabular-nums focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                <span class="flex shrink-0 items-center border-l border-slate-300 bg-slate-50 px-2 text-[11px] text-slate-500">Orang</span>
                            </div>
                            @error("pendidikan.{$key}.{$gender}") <p class="mt-1 text-[10px] text-rose-600">Jumlah tidak valid.</p> @enderror
                        </div>
                    @endforeach
                </div>
            </fieldset>
        @endforeach
    </div>
</div>