<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Analytics - SIDAK BKSDA Sulteng</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex antialiased">

    <!-- SIDEBAR NAVIGASI -->
    <aside class="w-72 bg-gradient-to-b from-emerald-950 via-emerald-900 to-slate-900 border-r border-emerald-800/40 flex flex-col justify-between shrink-0 hidden md:flex min-h-screen sticky top-0 shadow-2xl z-40">
        <div>
            <!-- Header Sidebar -->
            <div class="p-5 border-b border-emerald-800/40 bg-emerald-950/60 flex items-center gap-3.5 backdrop-blur-md">
                <div class="w-11 h-11 bg-white p-1 rounded-xl shadow-md flex items-center justify-center shrink-0">
                    <img src="{{ asset('images/logo-bksda.png') }}" alt="Logo BKSDA" class="h-full w-auto object-contain" onerror="this.src='https://via.placeholder.com/50?text=BKSDA'">
                </div>
                <div>
                    <h1 class="font-black text-sm tracking-wide text-white leading-tight">SIDAK BKSDA</h1>
                    <p class="text-[11px] font-black text-amber-400 tracking-wider">SULAWESI TENGAH</p>
                </div>
            </div>

            <nav class="p-4 space-y-1.5 text-sm">
                <div class="px-3 py-2 text-[10px] font-extrabold uppercase tracking-widest text-emerald-400/80">Main Menu</div>

                <a href="{{ route('konservasi.dashboard') }}" class="flex items-center justify-between px-4 py-3 rounded-xl bg-emerald-800 text-white font-bold shadow-lg shadow-emerald-950/40 border border-emerald-600/30 transition">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-chart-pie w-5 text-amber-400"></i>
                        <span>Dashboard Analytics</span>
                    </div>
                </a>

                <a href="{{ route('konservasi.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-emerald-800/40 transition font-medium">
                    <i class="fas fa-file-pen w-5 text-emerald-400"></i>
                    <span>Input Data Konservasi</span>
                </a>

                <a href="{{ route('konservasi.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-emerald-800/40 transition font-medium">
                    <i class="fas fa-database w-5 text-emerald-400"></i>
                    <span>Rekapitulasi Data</span>
                </a>

                <a href="{{ route('konservasi.peta') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-emerald-800/40 transition font-medium">
                    <i class="fas fa-map-location-dot w-5 text-emerald-400"></i>
                    <span>Peta GIS Kawasan</span>
                </a>
            </nav>
        </div>

        <!-- Tombol Logout -->
        <div class="p-4 border-t border-emerald-800/40 mt-auto bg-emerald-950/40">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-rose-300 hover:text-white hover:bg-rose-600/20 transition font-bold text-sm">
                    <i class="fas fa-right-from-bracket w-5 text-rose-400"></i>
                    <span>Keluar / Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <div class="flex-1 flex flex-col min-w-0 bg-slate-100/70">
        
        <!-- Header Topbar -->
        <header class="h-16 border-b border-slate-200/80 bg-white/90 backdrop-blur-md px-6 md:px-8 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-2.5">
                <span class="font-semibold text-slate-400 text-xs">Sistem Informasi</span>
                <i class="fas fa-chevron-right text-[9px] text-slate-300"></i>
                <span class="text-emerald-800 font-bold text-xs">Dashboard Analytics</span>
            </div>
        </header>

        <main class="p-6 md:p-8 max-w-7xl mx-auto w-full space-y-6">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">Ringkasan Kinerja & Data</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Balai Konservasi Sumber Daya Alam Sulawesi Tengah</p>
                </div>
            </div>

            <!-- WIDGET STATISTIK -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition-all">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Entri Data</p>
                        <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $totalData }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-xl flex items-center justify-center text-xl shadow-sm">
                        <i class="fas fa-folder-open"></i>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition-all">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Akumulasi Volume</p>
                        <h3 class="text-3xl font-black text-emerald-800 mt-1">{{ number_format($totalVolume) }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 border border-amber-100 rounded-xl flex items-center justify-center text-xl shadow-sm">
                        <i class="fas fa-cubes"></i>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition-all">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Titik Terpetakan (GIS)</p>
                        <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $totalLokasi }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-sky-50 text-sky-600 border border-sky-100 rounded-xl flex items-center justify-center text-xl shadow-sm">
                        <i class="fas fa-location-dot"></i>
                    </div>
                </div>
            </div>

            <!-- GRAFIK & DATA TERBARU -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- BAR CHART -->
                <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                    <div class="mb-5 pb-3 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h4 class="font-bold text-slate-800 text-base">Ringkasan Data Aktivitas</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Jumlah laporan kegiatan yang terdaftar per bidang</p>
                        </div>
                    </div>
                    <div class="relative w-full h-80">
                        <canvas id="analyticsChart"></canvas>
                    </div>
                </div>

                <!-- AKTIVITAS TERBARU -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="mb-5 pb-3 border-b border-slate-100">
                            <h4 class="font-bold text-slate-800 text-base">Entri Terbaru</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Daftar laporan masuk terkini</p>
                        </div>
                        <div class="space-y-3">
                            @forelse($recentData as $item)
                            <div class="p-3.5 bg-slate-50 hover:bg-emerald-50/40 rounded-xl border border-slate-100 transition flex justify-between items-center gap-3">
                                <div class="min-w-0 flex-1">
                                    <p class="font-bold text-slate-700 text-xs truncate leading-snug">{{ $item->subBidang->nama_sub_bidang ?? 'N/A' }}</p>
                                    <p class="text-slate-400 text-[10px] mt-0.5">{{ $item->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="font-black text-xs text-emerald-800 bg-emerald-100/80 px-2.5 py-1 rounded-lg shrink-0 border border-emerald-200/50">
                                    {{ $item->jumlah ?? 0 }}
                                </span>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <i class="fas fa-folder-open text-slate-300 text-3xl mb-2"></i>
                                <p class="text-slate-400 text-xs font-medium">Belum ada data terbaru.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>

    <!-- SCRIPT CHART.JS -->
    <script>
    const ctx = document.getElementById('analyticsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Jumlah Entri Kegiatan',
                data: {!! json_encode($chartData) !!},
                backgroundColor: '#065f46',
                hoverBackgroundColor: '#047857',
                borderRadius: 8,
                barThickness: 32
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        stepSize: 1,
                        precision: 0,
                        font: { family: 'Plus Jakarta Sans', size: 11 }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { family: 'Plus Jakarta Sans', size: 11, weight: '500' }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' }
                    }
                }
            }
        }
    });
    </script>
</body>
</html>