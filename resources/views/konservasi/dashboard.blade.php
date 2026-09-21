<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIDAK BKSDA Sulawesi Tengah</title>
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

    <!-- NAVBAR UTAMA (ROUTE AKTIF & BISA DIKLIK) -->
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Logo & Title Instansi Menggunakan logo-icon.png -->
            <div class="flex items-center space-x-3">
                <div class="w-11 h-11 rounded-xl bg-forest-700/10 border border-forest-700/20 flex items-center justify-center p-1.5 shadow-xs overflow-hidden">
                    <img src="{{ asset('images/logo-icon.png') }}" alt="Logo SIDAK" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-sm tracking-tight text-slate-900 leading-tight">SIDAK BKSDA SULTENG</span>
                    <span class="text-[11px] text-slate-500 font-medium">Sistem Informasi Data Konservasi</span>
                </div>
            </div>

            <!-- Menu Navigasi (Aktif & Bisa Diklik) -->
            <nav class="hidden md:flex items-center space-x-1 bg-slate-100/80 p-1.5 rounded-xl border border-slate-200">
                <a href="{{ route('konservasi.dashboard') }}" class="px-4 py-2 rounded-lg text-xs font-semibold bg-white text-forest-700 shadow-xs">Dashboard</a>
                <a href="{{ route('konservasi.index') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Rekapitulasi</a>
                <a href="{{ route('konservasi.create') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Tambah Data</a>
                <a href="{{ route('konservasi.peta') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Peta GIS</a>
            </nav>

            <!-- Status & User Profile -->
            <div class="flex items-center space-x-4">
                <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 mr-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Sistem Aktif
                </span>
                <div class="flex items-center space-x-2.5 border-l pl-4 border-slate-200">
                    <div class="w-9 h-9 rounded-full bg-forest-700 text-white flex items-center justify-center font-bold text-xs shadow-sm">FA</div>
                    <div class="hidden sm:block text-left">
                        <p class="text-xs font-bold text-slate-800 leading-tight">fadil aja</p>
                        <p class="text-[10px] text-slate-500">Operator</p>
                    </div>
                </div>
            </div>

        </div>
    </header>

    <!-- MAIN CONTENT CONTAINER -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- HERO BANNER DENGAN BACKGROUND SATWA ENDEMIK & KAWASAN SULTENG -->
        <div class="relative rounded-2xl overflow-hidden shadow-xl bg-slate-900 text-white min-h-[280px] flex flex-col justify-between p-8 border border-slate-800">
            <!-- Background Image Menggunakan File Lokal Satwa Endemik (anoa, babirusa, maleo, tarsius, dll) -->
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/satwa-endemik-sulteng.jpg') }}" alt="Satwa Endemik & Kawasan Konservasi Sulawesi Tengah" class="w-full h-full object-cover opacity-50 transform hover:scale-105 transition duration-700" onerror="this.src='https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?q=80&w=1600&auto=format&fit=crop'">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-900/80 to-slate-950/40"></div>
            </div>

            <!-- Konten Banner -->
            <div class="relative z-10 max-w-2xl">
                <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-medium mb-3 text-emerald-300 shadow-sm">
                    <i class="fa-solid fa-tree"></i> <span>Kawasan Konservasi & Satwa Endemik Sulawesi Tengah</span>
                </div>
                <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Selamat Datang, fadil aja!</h2>
                <p class="mt-2 text-sm text-slate-200 leading-relaxed">
                    Pusat pemantauan data kawasan hutan, perlindungan satwa endemik (Anoa, Babirusa, Maleo, Tarsius, Rangkong, Buaya), serta pemetaan koordinat GIS wilayah kerja BKSDA Sulteng.
                </p>
            </div>

            <!-- Tombol Tambah Data & Badge Hak Akses di Banner -->
            <div class="relative z-10 flex flex-wrap items-center justify-between gap-4 mt-6 pt-4 border-t border-white/15">
                <div class="flex items-center space-x-2 text-xs text-slate-200">
                    <i class="fa-solid fa-shield-halved text-amber-400"></i>
                    <span>Hak Akses: <strong class="text-white font-semibold">Petugas Operator Terverifikasi</strong></span>
                </div>
                <div>
                    <a href="{{ route('konservasi.create') }}" class="inline-flex items-center justify-center px-4.5 py-2.5 rounded-xl text-xs font-bold text-slate-950 bg-amber-400 hover:bg-amber-300 shadow-md transition">
                        <i class="fa-solid fa-plus mr-1.5"></i> Tambah Data Konservasi
                    </a>
                </div>
            </div>
        </div>

        <!-- GRID STATISTIK & LAPORAN -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Card 1 -->
            <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition">
                <div class="flex items-center justify-between text-slate-500 mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Konservasi</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                        <i class="fa-solid fa-database text-lg"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-900">4,512</h3>
                <p class="text-xs text-slate-500 mt-1 flex items-center">
                    <span class="text-emerald-600 font-medium mr-1"><i class="fa-solid fa-arrow-trend-up"></i> +12%</span> Rekaman Data Satwa & Ekosistem
                </p>
            </div>

            <!-- Card 2 -->
            <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition">
                <div class="flex items-center justify-between text-slate-500 mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Kawasan Terdaftar</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                        <i class="fa-solid fa-tree text-lg"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-900">12</h3>
                <p class="text-xs text-slate-500 mt-1">Total Area Terlindung: 1,250 Ha</p>
            </div>

            <!-- Card 3 -->
            <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition">
                <div class="flex items-center justify-between text-slate-500 mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Titik Koordinat GIS</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                        <i class="fa-solid fa-satellite text-lg"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-900">25</h3>
                <p class="text-xs text-slate-500 mt-1">Pemantauan GIS Real-time Aktif</p>
            </div>

            <!-- Card 4 (Aktivitas Lapangan) -->
            <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between text-slate-500 mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Kegiatan Lapangan</span>
                        <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100">
                            <i class="fa-solid fa-calendar-days text-sm"></i>
                        </div>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">Patroli Hutan & Maleo</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Status: <span class="text-emerald-600 font-semibold">Berjalan / Siaga</span></p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>3 Monitoring Aktif</span>
                    <span class="font-medium text-forest-700">Hari Ini</span>
                </div>
            </div>

        </div>

        <!-- SECTION BAWAH: VISUALISASI GRAFIK MODERN & PETA GIS INTERAKTIF -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Kolom Kiri: Visualisasi Grafik Sebaran Data (Modern & Interaktif) -->
            <div class="lg:col-span-2 bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col justify-between">
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-6">
                        <div>
                            <h3 class="font-bold text-lg text-slate-900">Visualisasi Sebaran Data Konservasi</h3>
                            <p class="text-xs text-slate-500">Akumulasi statistik populasi satwa endemik & luas kawasan hutan</p>
                        </div>
                        <div>
                            <select class="text-xs border border-slate-300 rounded-xl px-3.5 py-2 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-forest-600 font-medium text-slate-700 shadow-xs">
                                <option>Periode 2026 | Kategori Satwa & Hutan</option>
                                <option>Periode 2025</option>
                            </select>
                        </div>
                    </div>

                    <!-- Modern Bar Chart dengan Efek Gradasi & Animasi -->
                    <div class="h-64 flex items-end justify-between gap-3 pt-8 px-4 border-b border-slate-200 relative">
                        <!-- Garis Grid Latar Belakang Grafik -->
                        <div class="absolute inset-0 flex flex-col justify-between pointer-events-none opacity-25">
                            <div class="border-b border-dashed border-slate-300 w-full"></div>
                            <div class="border-b border-dashed border-slate-300 w-full"></div>
                            <div class="border-b border-dashed border-slate-300 w-full"></div>
                            <div class="border-b border-dashed border-slate-300 w-full"></div>
                        </div>

                        <!-- Batang 1 -->
                        <div class="w-full bg-gradient-to-t from-emerald-800 to-emerald-600 rounded-t-xl h-[65%] hover:scale-y-[1.03] transition-all duration-300 cursor-pointer relative group shadow-sm">
                            <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition shadow-md whitespace-nowrap z-20">1,420 Data</span>
                        </div>
                        <!-- Batang 2 -->
                        <div class="w-full bg-gradient-to-t from-rose-500 to-rose-400 rounded-t-xl h-[80%] hover:scale-y-[1.03] transition-all duration-300 cursor-pointer relative group shadow-sm">
                            <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition shadow-md whitespace-nowrap z-20">1,850 Data</span>
                        </div>
                        <!-- Batang 3 -->
                        <div class="w-full bg-gradient-to-t from-amber-500 to-amber-400 rounded-t-xl h-[45%] hover:scale-y-[1.03] transition-all duration-300 cursor-pointer relative group shadow-sm">
                            <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition shadow-md whitespace-nowrap z-20">920 Data</span>
                        </div>
                        <!-- Batang 4 -->
                        <div class="w-full bg-gradient-to-t from-blue-600 to-blue-400 rounded-t-xl h-[30%] hover:scale-y-[1.03] transition-all duration-300 cursor-pointer relative group shadow-sm">
                            <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition shadow-md whitespace-nowrap z-20">610 Data</span>
                        </div>
                        <!-- Batang 5 -->
                        <div class="w-full bg-gradient-to-t from-emerald-700 to-teal-500 rounded-t-xl h-[90%] hover:scale-y-[1.03] transition-all duration-300 cursor-pointer relative group shadow-sm">
                            <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition shadow-md whitespace-nowrap z-20">2,100 Data</span>
                        </div>
                        <!-- Batang 6 -->
                        <div class="w-full bg-gradient-to-t from-amber-700 to-amber-600 rounded-t-xl h-[40%] hover:scale-y-[1.03] transition-all duration-300 cursor-pointer relative group shadow-sm">
                            <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition shadow-md whitespace-nowrap z-20">850 Data</span>
                        </div>
                    </div>
                </div>

                <!-- Legenda Grafik -->
                <div class="flex flex-wrap items-center justify-center gap-6 mt-6 text-xs text-slate-700 font-medium">
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-600 shadow-xs"></span>
                        <span>Satwa Endemik (Anoa/Maleo)</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 rounded-full bg-rose-400 shadow-xs"></span>
                        <span>Ekosistem Pesisir & Rawa</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 rounded-full bg-amber-500 shadow-xs"></span>
                        <span>Hutan Lindung & Suaka</span>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Akses Cepat Peta GIS -->
            <div class="bg-slate-900 text-white p-6 rounded-2xl shadow-sm border border-slate-800 flex flex-col justify-between relative overflow-hidden">
                <div class="absolute inset-0 opacity-20 pointer-events-none">
                    <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=800&auto=format&fit=crop" alt="GIS Map" class="w-full h-full object-cover">
                </div>

                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-lg text-white">Akses Cepat GIS</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] bg-emerald-500/20 text-emerald-400 font-semibold border border-emerald-500/30">Live Map</span>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Eksplorasi spasial wilayah konservasi, titik koordinat patroli, dan batas kawasan hutan di Sulawesi Tengah secara terintegrasi.
                    </p>

                    <div class="mt-4 bg-slate-800/80 backdrop-blur rounded-xl p-3 border border-slate-700 h-40 flex items-center justify-center relative shadow-inner">
                        <div class="absolute text-center">
                            <i class="fa-solid fa-map-location-dot text-3xl text-emerald-400 mb-2 animate-bounce"></i>
                            <p class="text-xs font-semibold text-slate-200">Peta Spasial Sulteng Aktif</p>
                            <p class="text-[10px] text-slate-400">Lore Lindu • Morowali • Tinombo</p>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 mt-6">
                    <a href="{{ route('konservasi.peta') }}" class="w-full py-2.5 px-4 bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold rounded-xl text-xs text-center block transition shadow-md">
                        Jelajahi Peta GIS Interaktif <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

        </div>

    </main>

    <!-- FOOTER -->
    <footer class="mt-12 border-t border-slate-200 bg-white/80 backdrop-blur-md py-6 text-center text-xs text-slate-500">
        <p>&copy; 2026 <strong>SIDAK BKSDA Sulawesi Tengah</strong>. All rights reserved.</p>
    </footer>

</body>
</html>