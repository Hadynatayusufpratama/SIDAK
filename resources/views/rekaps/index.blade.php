<x-app-layout>
    <main class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Rekap Sekretariat · {{ $kode }}</p>
                    <h1 class="mt-1 text-2xl font-extrabold text-slate-900">{{ $subBidang?->nama_sub_bidang ?? 'Rekap Sub-Bidang' }}</h1>
                </div>
                <a href="{{ route('konservasi.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:border-emerald-300 hover:text-emerald-800">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Kembali ke Rekapitulasi
                </a>
            </div>

            @if ($data->isEmpty())
                <section class="rounded-xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center" data-theme-surface>
                    <i class="fa-regular fa-folder-open text-2xl text-slate-400" aria-hidden="true"></i>
                    <h2 class="mt-3 font-bold text-slate-800">Belum ada data rekap</h2>
                    <p class="mt-1 text-sm text-slate-500">Data yang disimpan untuk sub-bidang ini akan tampil di sini.</p>
                    <a href="{{ route('konservasi.create', ['sub_bidang' => $kode]) }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">
                        <i class="fa-solid fa-plus" aria-hidden="true"></i> Input data
                    </a>
                </section>
            @else
                <div class="space-y-4">
                    @foreach ($data as $item)
                        <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm" data-theme-surface>
                            <div class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-100 px-5 py-4">
                                <div>
                                    <h2 class="font-bold text-slate-900">{{ $item->kawasan }}</h2>
                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $kode }} · Tahun {{ $item->tahun }}
                                        @if (!empty($item->data_kustom['bulan']))
                                            · Bulan {{ $item->data_kustom['bulan'] }}
                                        @elseif (!empty($item->data_kustom['semester']))
                                            · Semester {{ $item->data_kustom['semester'] }}
                                        @endif
                                    </p>
                                </div>
                                <time class="text-xs text-slate-500" datetime="{{ $item->created_at?->toIso8601String() }}">Disimpan {{ $item->created_at?->translatedFormat('d M Y, H:i') }}</time>
                            </div>
                            <details class="px-5 py-4" open>
                                <summary class="cursor-pointer text-sm font-semibold text-emerald-800">Rincian data input</summary>
                                <div class="mt-4 overflow-x-auto rounded-lg border border-slate-200">
                                    <table class="w-full min-w-[560px] text-left text-xs">
                                        <thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                            <tr>
                                                <th class="px-4 py-3">Rincian</th>
                                                <th class="px-4 py-3">Data 1</th>
                                                <th class="px-4 py-3">Data 2</th>
                                                <th class="px-4 py-3 text-right">Total / Nilai</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 text-slate-700">
                                            @forelse ($item->detail_rows as $row)
                                                <tr class="odd:bg-slate-50/80">
                                                    <td class="px-4 py-2.5">
                                                        @if ($row['group'])
                                                            <span class="mr-1 text-slate-500">{{ $row['group'] }}:</span>
                                                        @endif
                                                        <span class="font-semibold">{{ $row['label'] }}</span>
                                                    </td>
                                                    <td class="px-4 py-2.5">
                                                        @if (array_key_exists('first', $row))
                                                            <span class="text-slate-500">{{ $row['first_label'] }}:</span> <strong>{{ $row['first'] }}</strong>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-2.5">
                                                        @if (array_key_exists('second', $row))
                                                            <span class="text-slate-500">{{ $row['second_label'] }}:</span> <strong>{{ $row['second'] }}</strong>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-2.5 text-right font-bold">
                                                        {{ $row['total'] ?? $row['value'] ?? '-' }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="px-4 py-6 text-center text-slate-500">Tidak ada rincian untuk ditampilkan.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </details>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </main>
</x-app-layout>
