<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-slate-800 leading-tight">
                    {{ __('Dashboard Analytics') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Sistem Informasi Data Konservasi (SIDAK) Balai KSDA Sulawesi Tengah
                </p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sistem Aktif
                </span>
                <a href="{{ route('konservasi.create') }}" class="bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold py-2.5 px-4 rounded-xl flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-plus text-amber-400"></i>
                    <span>Tambah Data</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Banner Welcome Header -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-900 via-emerald-800 to-teal-900 p-6 md:p-8 text-white shadow-xl">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-amber-400/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="space-y-2 max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-400/20 border border-amber-400/30 text-amber-300 text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-leaf"></i> Balai KSDA Sulawesi Tengah
                    </div>
                    <h1 class="text-2xl md:text-3xl font-black tracking-tight text-white">
                        Selamat Datang, {{ Auth::user()->name ?? 'Operator' }}!
                    </h1>
                    <p class="text-xs md:text-sm text-emerald-100/90 leading-relaxed">
                        Pantau seluruh ringkasan capaian kinerja, rekapitulasi data konservasi kawasan, dan persebaran koordinat GIS secara real-time di sini.
                    </p>
                </div>
                <div class="shrink-0 flex items-center gap-3 bg-white/10 backdrop-blur-md p-3 rounded-2xl border border-white/10">
                    <div class="w-12 h-12 rounded-xl bg-amber-400 text-emerald-950 flex items-center justify-center font-black text-lg shadow-md">
                        <i class="fas fa-shield-halved"></i>
                    </div>
                    <div class="text-xs">
                        <p class="text-slate-200">Hak Akses</p>
                        <p class="font-extrabold text-white">Petugas Operator</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- STAT CARDS ATAS (RAPI, BERWARNA & INFORMATIF) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Card 1: Total Data -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Total Data Konservasi</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base font-bold group-hover:bg-emerald-600 group-hover:text-white transition">
                        <i class="fas fa-folder-open"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">
                        {{ $totalData ?? '1' }}
                    </h3>
                    <div class="flex items-center gap-1.5 mt-2 text-xs font-bold text-emerald-600">
                        <i class="fas fa-circle-check text-[10px]"></i>
                        <span>Terdata di Sistem</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Kawasan Terdaftar -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Kawasan Terdaftar</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base font-bold group-hover:bg-amber-500 group-hover:text-white transition">
                        <i class="fas fa-tree"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">12</h3>
                    <div class="flex items-center gap-1.5 mt-2 text-xs font-bold text-amber-600">
                        <i class="fas fa-location-dot text-[10px]"></i>
                        <span>Wilayah BKSDA Sulteng</span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Titik Koordinat -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Titik Koordinat</span>
                    <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-base font-bold group-hover:bg-sky-500 group-hover:text-white transition">
                        <i class="fas fa-map-pin"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">1</h3>
                    <div class="flex items-center gap-1.5 mt-2 text-xs font-bold text-sky-600">
                        <i class="fas fa-satellite text-[10px]"></i>
                        <span>Terintegrasi Peta GIS</span>
                    </div>
                </div>
            </div>

            <!-- Card 4: Petugas Operator -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Petugas Operator</span>
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-base font-bold group-hover:bg-purple-600 group-hover:text-white transition">
                        <i class="fas fa-user-gear"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-sm font-black text-slate-800 truncate leading-snug" title="{{ Auth::user()->name ?? 'HADYNATA YUSUF PRATAMA' }}">
                        {{ Auth::user()->name ?? 'HADYNATA YUSUF PRATAMA' }}
                    </h3>
                    <div class="flex items-center gap-1.5 mt-2 text-xs font-bold text-purple-600">
                        <i class="fas fa-circle text-[8px] animate-pulse"></i>
                        <span>Logged In</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- SECTION UTAMA (GRAFIK + SIDE PANEL) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Area Grafik Visualisasi Data (2 Kolom) -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-base font-black text-slate-800">Visualisasi Sebaran Data</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Ringkasan akumulasi jumlah data per kategori/bidang</p>
                    </div>
                    <span class="px-3 py-1 bg-slate-100 border border-slate-200 text-slate-600 rounded-xl text-xs font-extrabold">
                        Tahun {{ date('Y') }}
                    </span>
                </div>

                <!-- Kontainer Grafik -->
                <div class="pt-2 min-h-[300px] flex items-center justify-center">
                    {{-- Kode Grafik / Chart Kamu ditaruh di sini --}}
                    {{-- Jika memakai Chart.js atau visualisasi khusus --}}
                    <div class="w-full">
                        {{-- Contoh Placeholder Canvas Chart.js --}}
                        <canvas id="konservasiChart" class="w-full max-h-[320px]"></canvas>
                    </div>
                </div>
            </div>

            <!-- Panel Samping Cepat (1 Kolom) -->
            <div class="space-y-6">
                
                <!-- Box Quick Access GIS -->
                <div class="bg-gradient-to-br from-slate-900 via-emerald-950 to-emerald-900 rounded-3xl p-6 text-white shadow-md relative overflow-hidden border border-emerald-800/50">
                    <div class="absolute right-0 bottom-0 translate-x-4 translate-y-4 text-emerald-800/20 text-9xl font-black pointer-events-none">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div class="relative z-10 space-y-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-400 text-emerald-950 flex items-center justify-center text-base font-extrabold shadow-sm">
                            <i class="fas fa-map-location-dot"></i>
                        </div>
                        <div>
                            <h4 class="text-base font-extrabold text-white">Akses Cepat GIS</h4>
                            <p class="text-xs text-slate-300 mt-1 leading-relaxed">
                                Lihat pemetaan kawasan hutan konservasi BKSDA Sulawesi Tengah secara terintegrasi.
                            </p>
                        </div>
                        <a href="{{ route('konservasi.peta') }}" class="inline-flex items-center justify-center gap-2 w-full py-3 px-4 bg-amber-400 hover:bg-amber-300 text-emerald-950 font-extrabold rounded-xl text-xs transition shadow-md">
                            <span>Buka Peta GIS Kawasan</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Ringkasan Informasi Sistem -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Informasi Sistem</h4>
                    
                    <div class="space-y-3 text-xs divide-y divide-slate-100">
                        <div class="flex justify-between items-center pt-1">
                            <span class="text-slate-500 font-medium">Instansi</span>
                            <span class="font-extrabold text-slate-800">BKSDA Sulteng</span>
                        </div>
                        <div class="flex justify-between items-center pt-3">
                            <span class="text-slate-500 font-medium">Sistem</span>
                            <span class="font-extrabold text-emerald-700">SIDAK v2.0</span>
                        </div>
                        <div class="flex justify-between items-center pt-3">
                            <span class="text-slate-500 font-medium">Wilayah Kerja</span>
                            <span class="font-extrabold text-slate-800">Sulawesi Tengah</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>