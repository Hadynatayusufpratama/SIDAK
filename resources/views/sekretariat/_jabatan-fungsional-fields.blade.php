@php
    $embedded = $embedded ?? false;
    $idPrefix = $embedded ? 'create-' : '';
@endphp

<div class="space-y-6">
    <div class="space-y-5">
        <div>
            <label for="{{ $idPrefix }}jabatan-fungsional-tahun" class="mb-1.5 block text-xs font-medium text-slate-700">Tahun <span class="text-rose-500">*</span></label>
            <select id="{{ $idPrefix }}jabatan-fungsional-tahun" name="sekretariat_tahun" @if ($embedded) :disabled="selectedSubBidangKode !== 'F.04'" @else required @endif class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                @for ($year = 2026; $year >= 2023; $year--)
                    <option value="{{ $year }}" @selected((string) old('sekretariat_tahun', now()->year) === (string) $year)>{{ $year }}</option>
                @endfor
            </select>
            @error('sekretariat_tahun') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="{{ $idPrefix }}jabatan-fungsional-unit-kerja" class="mb-1.5 block text-xs font-medium text-slate-700">Satuan Kerja <span class="text-rose-500">*</span></label>
            <input id="{{ $idPrefix }}jabatan-fungsional-unit-kerja" name="unit_kerja" type="text" value="{{ old('unit_kerja', 'Balai KSDA Sulawesi Tengah') }}" maxlength="255" placeholder="Masukkan nama satuan kerja" @if ($embedded) :disabled="selectedSubBidangKode !== 'F.04'" @else required @endif class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            @error('unit_kerja') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
    </div>

    @include('sekretariat._jabatan-fungsional-picker', ['embedded' => $embedded, 'activeCode' => 'F.04', 'idPrefix' => $idPrefix])

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        @foreach (['laki_laki' => ['Laki-laki', 'jumlah_laki_laki'], 'perempuan' => ['Perempuan', 'jumlah_perempuan']] as $gender => [$label, $inputName])
            <div>
                <label for="{{ $idPrefix }}{{ $inputName }}" class="mb-1.5 block text-xs font-medium text-slate-700">{{ $label }}</label>
                <div class="flex h-10 w-full overflow-hidden rounded border border-slate-300">
                    <span class="flex shrink-0 items-center border-r border-slate-300 bg-slate-50 px-3 text-xs text-slate-500">Jumlah</span>
                    <input id="{{ $idPrefix }}{{ $inputName }}" name="{{ $inputName }}" type="number" min="0" max="100000" step="1" inputmode="numeric" value="{{ old($inputName) === '' ? 0 : old($inputName, 0) }}" @if ($embedded) :disabled="selectedSubBidangKode !== 'F.04'" @else required @endif class="min-w-0 w-full px-3 text-sm tabular-nums focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    <span class="flex shrink-0 items-center border-l border-slate-300 bg-slate-50 px-3 text-xs text-slate-500">Orang</span>
                </div>
                @error($inputName) <p class="mt-1 text-xs text-rose-600">Jumlah tidak valid.</p> @enderror
            </div>
        @endforeach
    </div>
</div>