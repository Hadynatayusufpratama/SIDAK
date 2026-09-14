<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-slate-800 leading-tight">
                    {{ __('Rekapitulasi Data Konservasi') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Daftar entri data kinerja dan kegiatan di lingkungan BKSDA Sulawesi Tengah
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('konservasi.create') }}" class="bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold py-2.5 px-4 rounded-xl flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-plus text-amber-400"></i>
                    <span>Tambah Data</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Flash Alert Success -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="text-xs font-bold">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-sm">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
        @endif

        <!-- Ringkasan Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Baris Data</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ method_exists($data, 'total') ? $data->total() : count($data) }}</h3>
                    <p class="text-[11px] text-emerald-600 font-semibold mt-1">
                        <i class="fas fa-layer-group text-[10px]"></i> Database Tersimpan
                    </p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl font-bold border border-emerald-100">
                    <i class="fas fa-database"></i>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Pencarian</p>
                    <h3 class="text-base font-extrabold text-slate-800 mt-1 truncate max-w-[180px]">
                        {{ request('search') ? request('search') : 'Semua Data' }}
                    </h3>
                    <p class="text-[11px] text-amber-600 font-semibold mt-1">
                        <i class="fas fa-filter text-[10px]"></i> Filter Aktif
                    </p>
                </div>
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl font-bold border border-amber-100">
                    <i class="fas fa-magnifying-glass"></i>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Peta GIS Terkait</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">Palu & Sulteng</h3>
                    <p class="text-[11px] text-sky-600 font-semibold mt-1">
                        <i class="fas fa-map-marked-alt text-[10px]"></i> Terhubung Koordinat
                    </p>
                </div>
                <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-xl flex items-center justify-center text-xl font-bold border border-sky-100">
                    <i class="fas fa-map-location-dot"></i>
                </div>
            </div>
        </div>

        <!-- Tabel Rekapitulasi Data -->
        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
            <!-- Header & Form Cari -->
            <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-extrabold text-slate-800">Riwayat Data Konservasi</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar entri data kinerja dan kegiatan BKSDA Sulawesi Tengah</p>
                </div>

                <form action="{{ route('konservasi.index') }}" method="GET" class="flex items-center gap-2">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari sub-bidang / keterangan..." class="pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-600 focus:bg-white outline-none w-64 transition-all">
                    </div>
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-xl text-xs font-bold transition">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('konservasi.index') }}" class="p-2 text-slate-400 hover:text-rose-600 text-xs transition" title="Reset">
                            <i class="fas fa-rotate-left"></i>
                        </a>
                    @endif
                </form>
            </div>

            <!-- Tabel Data -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-extrabold uppercase tracking-wider text-slate-400">
                            <th class="py-3.5 px-5">No</th>
                            <th class="py-3.5 px-5">Bidang Utama</th>
                            <th class="py-3.5 px-5">Sub-Bidang / Kategori</th>
                            <th class="py-3.5 px-5">Periode Waktu</th>
                            <th class="py-3.5 px-5">Jumlah / Vol</th>
                            <th class="py-3.5 px-5">Koordinat GIS</th>
                            <th class="py-3.5 px-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                        @forelse($data as $index => $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-5 font-bold text-slate-400">
                                    {{ method_exists($data, 'firstItem') ? $data->firstItem() + $index : $index + 1 }}
                                </td>
                                <td class="py-4 px-5">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-100">
                                        {{ $item->subBidang->bidang->nama_bidang ?? 'Konservasi' }}
                                    </span>
                                </td>
                                <td class="py-4 px-5">
                                    <p class="font-bold text-slate-800">
                                        {{ $item->subBidang->kode_sub ?? '' }}. {{ $item->subBidang->nama_sub_bidang ?? '-' }}
                                    </p>
                                    @if($item->keterangan)
                                        <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">{{ $item->keterangan }}</p>
                                    @endif
                                </td>
                                <td class="py-4 px-5 whitespace-nowrap text-slate-600 font-semibold">
                                    {{ $item->bulan ? date('F', mktime(0, 0, 0, $item->bulan, 1)) : '' }} {{ $item->tahun }}
                                </td>
                                <td class="py-4 px-5">
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-800 font-extrabold rounded-lg border border-slate-200">
                                        {{ $item->jumlah ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 text-[11px] font-mono text-slate-500 whitespace-nowrap">
                                    @if($item->latitude && $item->longitude)
                                        <span class="inline-flex items-center gap-1 text-emerald-700 font-semibold bg-emerald-50/50 px-2 py-0.5 rounded-md">
                                            <i class="fas fa-location-dot text-rose-500 text-[10px]"></i>
                                            {{ $item->latitude }}, {{ $item->longitude }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-5 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('konservasi.edit', $item->id) }}" 
                                           class="w-8 h-8 flex items-center justify-center bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-xl transition border border-amber-200/80 shadow-sm" 
                                           title="Edit Data">
                                            <i class="fas fa-pen-to-square text-xs"></i>
                                        </a>

                                        <form action="{{ route('konservasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-8 h-8 flex items-center justify-center bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition border border-rose-200/80 shadow-sm" 
                                                    title="Hapus Data">
                                                <i class="fas fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 px-5 text-center">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center mx-auto text-lg">
                                            <i class="fas fa-folder-open"></i>
                                        </div>
                                        <p class="text-xs font-bold text-slate-600">Belum Ada Data Tersimpan</p>
                                        <p class="text-[11px] text-slate-400">Silakan tambahkan entri data baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($data, 'links'))
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $data->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>