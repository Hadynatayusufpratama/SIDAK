<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Data - SIDAK BKSDA Sulawesi Tengah</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        forest: {
                            600: '#15803d',
                            700: '#166534',
                            800: '#14532d',
                        }
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100/90 font-sans text-slate-800 antialiased min-h-screen relative">

    <!-- BACKGROUND GLOBAL KAWASAN KONSERVASI DENGAN OPASITAS TIPIS -->
    <div class="fixed inset-0 pointer-events-none z-[-1] overflow-hidden">
        <img src="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?q=80&w=1920&auto=format&fit=crop" alt="Background Konservasi" class="w-full h-full object-cover opacity-15">
        <div class="absolute inset-0 bg-slate-100/75 backdrop-blur-[2px]"></div>
    </div>

    <!-- NAVBAR UTAMA -->
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Logo & Title Instansi -->
            <div class="flex items-center space-x-3">
                <div class="w-11 h-11 rounded-xl bg-forest-700/10 border border-forest-700/20 flex items-center justify-center p-1.5 shadow-xs overflow-hidden">
                    <img src="{{ asset('images/logo-icon.png') }}" alt="Logo SIDAK" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-sm tracking-tight text-slate-900 leading-tight">SIDAK BKSDA SULTENG</span>
                    <span class="text-[11px] text-slate-500 font-medium">Sistem Input data konservasi</span>
                </div>
            </div>

            <!-- Menu Navigasi -->
            <nav class="hidden md:flex items-center space-x-1 bg-slate-100/80 p-1.5 rounded-xl border border-slate-200">
                <a href="{{ route('konservasi.dashboard') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Dashboard</a>
                <a href="{{ route('konservasi.index') }}" class="px-4 py-2 rounded-lg text-xs font-semibold bg-white text-forest-700 shadow-xs">Rekapitulasi</a>
                <a href="{{ route('konservasi.create') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Tambah Data</a>
                <a href="{{ route('konservasi.peta') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Peta GIS</a>
            </nav>

            <!-- Status & User Profile -->
            <div class="flex items-center space-x-4">
                <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 mr-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Sistem Aktif
                </span>
                <div class="flex items-center space-x-2.5 border-l pl-4 border-slate-200">
                    <div class="w-9 h-9 rounded-full bg-forest-700 text-white flex items-center justify-center font-bold text-xs shadow-sm uppercase">
                        {{ strtoupper(substr(Auth::user()->name ?? 'User', 0, 2)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-xs font-bold text-slate-800 leading-tight">{{ Auth::user()->name ?? 'Pengguna' }}</p>
                        <p class="text-[10px] text-slate-500">{{ Auth::user()->role ?? 'Operator' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </header>

    <!-- MAIN CONTENT CONTAINER -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- HEADER BANNER & TOMBOL TAMBAH -->
        <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">
                    Rekapitulasi Data Konservasi
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Daftar entri data kinerja, pemantauan satwa, dan kegiatan kawasan BKSDA Sulawesi Tengah
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('konservasi.create') }}" class="inline-flex items-center justify-center px-4.5 py-2.5 rounded-xl text-xs font-bold text-slate-950 bg-amber-400 hover:bg-amber-300 shadow-md transition">
                    <i class="fa-solid fa-plus mr-1.5"></i> Tambah Data
                </a>
            </div>
        </div>

        <!-- Flash Alert Success -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span class="text-xs font-bold">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <!-- RINGKASAN STAT CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Baris Data</p>
                    <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ method_exists($data, 'total') ? $data->total() : count($data) }}</h3>
                    <p class="text-[11px] text-emerald-600 font-semibold mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-layer-group text-[10px]"></i> Database Tersimpan
                    </p>
                </div>
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl font-bold border border-amber-100 shadow-xs">
                    <i class="fa-solid fa-database"></i>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Pencarian</p>
                    <h3 class="text-lg font-extrabold text-slate-900 mt-1 truncate max-w-[180px]">
                        {{ request('search') ? request('search') : 'Semua Data' }}
                    </h3>
                    <p class="text-[11px] text-amber-600 font-semibold mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-filter text-[10px]"></i> Filter Aktif
                    </p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl font-bold border border-emerald-100 shadow-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Peta GIS Terkait</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 mt-1">Palu & Sulteng</h3>
                    <p class="text-[11px] text-blue-600 font-semibold mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-map-location-dot text-[10px]"></i> Terhubung Koordinat
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl font-bold border border-blue-100 shadow-xs">
                    <i class="fa-solid fa-satellite"></i>
                </div>
            </div>
        </div>

        <!-- TABEL REKAPITULASI DATA -->
        <div class="bg-white/90 backdrop-blur-md border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
            
            <!-- Header Judul, Form Cari, & Tombol Unduh -->
            <div class="p-6 border-b border-slate-200/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Riwayat Data Konservasi</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar entri data kinerja dan kegiatan BKSDA Sulawesi Tengah</p>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <!-- Form Pencarian Global -->
                    <form action="{{ route('konservasi.index') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
                        <div class="relative w-full sm:w-72">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Cari bidang, satwa, bulan, tahun..." 
                                   class="w-full pl-9 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white transition shadow-xs">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-forest-700 hover:bg-forest-800 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('konservasi.index') }}" class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-medium rounded-xl transition">
                                Reset
                            </a>
                        @endif
                    </form>

                    <!-- Tombol Unduh PDF & Excel -->
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                        <a href="{{ route('konservasi.export.pdf', request()->query()) }}" 
                           class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition shadow-xs whitespace-nowrap">
                            <i class="fa-solid fa-file-pdf mr-1.5 text-sm"></i> Unduh PDF
                        </a>

                        <a href="{{ route('konservasi.export.excel', request()->query()) }}" 
                           class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition shadow-xs whitespace-nowrap">
                            <i class="fa-solid fa-file-excel mr-1.5 text-sm"></i> Unduh Excel
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-400">
                            <th class="py-4 px-5">No</th>
                            <th class="py-4 px-5">Bidang Utama</th>
                            <th class="py-4 px-5">Sub-Bidang / Kategori</th>
                            <th class="py-4 px-5">Periode Waktu</th>
                            <th class="py-4 px-5">Jumlah / Vol</th>
                            <th class="py-4 px-5">Koordinat GIS</th>
                            <th class="py-4 px-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                        @forelse($data as $index => $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-5 font-bold text-slate-400">
                                    {{ method_exists($data, 'firstItem') ? $data->firstItem() + $index : $index + 1 }}
                                </td>
                                <td class="py-4 px-5">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold bg-emerald-50 text-forest-700 border border-emerald-200/80">
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
                                    @php
                                        $months = [
                                            '01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April',
                                            '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus',
                                            '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember',
                                            '1'=>'Januari', '2'=>'Februari', '3'=>'Maret', '4'=>'April',
                                            '5'=>'Mei', '6'=>'Juni', '7'=>'Juli', '8'=>'Agustus', '9'=>'September'
                                        ];
                                        $namaBulan = $months[$item->bulan] ?? $item->bulan;
                                    @endphp
                                    {{ $namaBulan }} {{ $item->tahun }}
                                </td>
                                <td class="py-4 px-5">
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-800 font-extrabold rounded-lg border border-slate-200">
                                        {{ $item->jumlah ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 text-[11px] font-mono text-slate-500 whitespace-nowrap">
                                    @if($item->latitude && $item->longitude)
                                        <span class="inline-flex items-center gap-1 text-emerald-700 font-semibold bg-emerald-50/80 border border-emerald-200 px-2 py-0.5 rounded-md">
                                            <i class="fa-solid fa-location-dot text-rose-500 text-[10px]"></i>
                                            {{ $item->latitude }}, {{ $item->longitude }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-5 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('konservasi.edit', $item->id) }}" 
                                           class="w-8 h-8 flex items-center justify-center bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-xl transition border border-amber-200/80 shadow-xs" 
                                           title="Edit Data">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>

                                        <form action="{{ route('konservasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-8 h-8 flex items-center justify-center bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition border border-rose-200/80 shadow-xs" 
                                                    title="Hapus Data">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
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
                                            <i class="fa-solid fa-folder-open"></i>
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
            <div class="p-4 border-t border-slate-200/80 bg-slate-50/50">
                {{ $data->links() }}
            </div>
            @endif
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="mt-12 border-t border-slate-200 bg-white/80 backdrop-blur-md py-6 text-center text-xs text-slate-500">
        <p>&copy; 2026 <strong>SIDAK BKSDA Sulawesi Tengah</strong>. All rights reserved.</p>
    </footer>

</body>
</html>