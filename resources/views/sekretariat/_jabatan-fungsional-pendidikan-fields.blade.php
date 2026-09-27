@php
    $embedded = $embedded ?? false;
    $idPrefix = $embedded ? 'create-f05-' : '';
@endphp

<div class="space-y-7">
    <div class="space-y-5">
        <div>
            <label for="{{ $idPrefix }}tahun" class="mb-1.5 block text-xs font-medium text-slate-700">Tahun <span class="text-rose-500">*</span></label>
            <select id="{{ $idPrefix }}tahun" name="sekretariat_tahun" @if ($embedded) :disabled="selectedSubBidangKode !== 'F.05'" @else required @endif class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                @for ($year = 2026; $year >= 2023; $year--)
                    <option value="{{ $year }}" @selected((string) old('sekretariat_tahun', now()->year) === (string) $year)>{{ $year }}</option>
                @endfor
            </select>
            @error('sekretariat_tahun') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="{{ $idPrefix }}unit-kerja" class="mb-1.5 block text-xs font-medium text-slate-700">Satuan Kerja <span class="text-rose-500">*</span></label>
            <input id="{{ $idPrefix }}unit-kerja" name="unit_kerja" type="text" value="{{ old('unit_kerja', 'Balai KSDA Sulawesi Tengah') }}" maxlength="255" placeholder="Masukkan nama satuan kerja" @if ($embedded) :disabled="selectedSubBidangKode !== 'F.05'" @else required @endif class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            @error('unit_kerja') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
    </div>

    @include('sekretariat._jabatan-fungsional-picker', ['embedded' => $embedded, 'activeCode' => 'F.05', 'idPrefix' => $idPrefix])
    @include('sekretariat._pendidikan-fields', ['embedded' => $embedded, 'activeCode' => 'F.05', 'includePeriodAndUnit' => false, 'idPrefix' => $idPrefix])
</div>