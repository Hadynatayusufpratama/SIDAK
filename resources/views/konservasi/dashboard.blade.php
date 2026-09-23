<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Analytics - SIDAK BKSDA Sulawesi Tengah</title>
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
    <!-- Chart.js untuk Grafik Dinamis -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <img src="{{ asset('images/logo-icon.png') }}" alt="Logo SIDAK" class="w-full h-full object-contain" onerror="this.onerror=null; this.src='https://via.placeholder.com/50?text=SIDAK';">
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-sm tracking-tight text-slate-900 leading-tight">SIDAK BKSDA SULTENG</span>
                    <span class="text-[11px] text-slate-500 font-medium">Sistem input data konservasi</span>
                </div>
            </div>

            <!-- Menu Navigasi -->
            <nav class="hidden md:flex items-center space-x-1 bg-slate-100/80 p-1.5 rounded-xl border border-slate-200">
                <a href="{{ route('konservasi.dashboard') }}" class="px-4 py-2 rounded-lg text-xs font-semibold bg-white text-forest-700 shadow-xs">Dashboard</a>
                <a href="{{ route('konservasi.index') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Rekapitulasi</a>
                <a href="{{ route('konservasi.create') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Tambah Data</a>
                <a href="{{ route('konservasi.peta') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Peta GIS</a>
            </nav>

            <!-- Status & User Profile (DINAMIS SESUAI USER LOGIN) -->
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

        <!-- HERO BANNER -->
        <div class="relative rounded-2xl overflow-hidden shadow-xl bg-slate-900 text-white min-h-[240px] flex flex-col justify-between p-8 border border-slate-800">
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/satwa-endemik-sulteng.jpg') }}" alt="Satwa Endemik & Kawasan Konservasi Sulawesi Tengah" class="w-full h-full object-cover opacity-50 transform hover:scale-105 transition duration-700" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?q=80&w=1600&auto=format&fit=crop'">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-900/80 to-slate-950/40"></div>
            </div>

            <!-- Konten Banner -->
            <div class="relative z-10 max-w-2xl">
                <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-medium mb-3 text-emerald-300 shadow-sm">
                    <i class="fa-solid fa-tree"></i> <span>Kawasan Konservasi & Satwa Endemik Sulawesi Tengah</span>
                </div>
                
                <h2 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">
                    Selamat Datang, {{ Auth::user()->name ?? 'Petugas Operator' }}!
                </h2>

                <p class="mt-2 text-xs sm:text-sm text-slate-200 leading-relaxed">
                    Pusat pemantauan data kawasan hutan, perlindungan satwa endemik, serta pemetaan koordinat GIS wilayah kerja BKSDA Sulawesi Tengah.
                </p>
            </div>

            <!-- Tombol Tambah Data & Badge Hak Akses -->
            <div class="relative z-10 flex flex-wrap items-center justify-between gap-4 mt-6 pt-4 border-t border-white/15">
                <div class="flex items-center space-x-2 text-xs text-slate-200">
                    <i class="fa-solid fa-shield-halved text-amber-400"></i>
                    <span>Hak Akses: <strong class="text-white font-semibold">Petugas {{ Auth::user()->role ?? 'Operator' }} Terverifikasi</strong></span>
                </div>
                <div>
                    <a href="{{ route('konservasi.create') }}" class="inline-flex items-center justify-center px-4.5 py-2.5 rounded-xl text-xs font-bold text-slate-950 bg-amber-400 hover:bg-amber-300 shadow-md transition">
                        <i class="fa-solid fa-plus mr-1.5"></i> Tambah Data Konservasi
                    </a>
                </div>
            </div>
        </div>

        <!-- GRID STATISTIK DINAMIS DARI CONTROLLER -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Card 1: Total Volume Konservasi -->
            <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition">
                <div class="flex items-center justify-between text-slate-500 mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Akumulasi Volume</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                        <i class="fa-solid fa-database text-lg"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-slate-900">{{ number_format($totalVolume ?? 0) }}</h3>
                <p class="text-xs text-slate-500 mt-2 flex items-center">
                    <span class="text-emerald-600 font-semibold mr-1.5"><i class="fa-solid fa-list-check"></i> {{ $totalData ?? 0 }}</span> Entri data berhasil diinput
                </p>
            </div>

            <!-- Card 2: Kawasan / Sub-Bidang Aktif Terisi -->
            <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition">
                <div class="flex items-center justify-between text-slate-500 mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Kawasan / Kategori Terisi</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                        <i class="fa-solid fa-tree text-lg"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-slate-900">{{ $totalKawasan ?? 0 }}</h3>
                <p class="text-xs text-slate-500 mt-2">Kategori kawasan aktif terdata di database</p>
            </div>

            <!-- Card 3: Titik Koordinat GIS -->
            <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition">
                <div class="flex items-center justify-between text-slate-500 mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Titik Koordinat GIS</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                        <i class="fa-solid fa-location-dot text-lg"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-slate-900">{{ $totalLokasi ?? 0 }}</h3>
                <p class="text-xs text-slate-500 mt-2">Pemantauan titik GIS lokasi real-time aktif</p>
            </div>

        </div>

        <!-- SECTION BAWAH: GRAFIK & AKSES PETA GIS -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Kolom Kiri: Visualisasi Grafik Dinamis Chart.js -->
            <div class="lg:col-span-2 bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-lg text-slate-900">Visualisasi Sebaran Data Konservasi</h3>
                            <p class="text-xs text-slate-500">Akumulasi real-time berdasarkan bidang data konservasi yang diinput</p>
                        </div>
                    </div>

                    <!-- Canvas Chart.js Dinamis -->
                    <div class="relative w-full h-72">
                        <canvas id="konservasiChart"></canvas>
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

                    <div class="mt-6 bg-slate-800/80 backdrop-blur rounded-xl p-4 border border-slate-700 h-36 flex items-center justify-center relative shadow-inner">
                        <div class="text-center">
                            <i class="fa-solid fa-map-location-dot text-3xl text-emerald-400 mb-2 animate-bounce"></i>
                            <p class="text-xs font-semibold text-slate-200">Peta Spasial Sulteng Aktif</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $totalLokasi ?? 0 }} Titik Koordinat Terpetakan</p>
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

    <!-- SCRIPT RENDER GRAFIK CHART.JS DINAMIS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const labels = <?php echo json_encode($chartLabels ?? []); ?>;
            const dataValues = <?php echo json_encode($chartData ?? []); ?>;

            const ctx = document.getElementById('konservasiChart').getContext('2d');

            if (labels.length === 0 || dataValues.length === 0) {
                // Tampilan fallback jika belum ada data di database
                ctx.font = '14px Plus Jakarta Sans, sans-serif';
                ctx.fillStyle = '#94a3b8';
                ctx.textAlign = 'center';
                ctx.fillText('Belum ada data konservasi yang diinputkan ke sistem.', ctx.canvas.width / 2, ctx.canvas.height / 2);
                return;
            }

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Volume / Rekap Data',
                        data: dataValues,
                        backgroundColor: [
                            '#16a34a', // Emerald Green
                            '#f43f5e', // Rose
                            '#f59e0b', // Amber
                            '#2563eb', // Blue
                            '#0d9488', // Teal
                            '#8b5cf6'  // Purple
                        ],
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 12,
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 12 },
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 11, family: 'Plus Jakarta Sans' },
                                color: '#64748b'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                precision: 0,
                                font: { size: 11, family: 'Plus Jakarta Sans' },
                                color: '#64748b'
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>