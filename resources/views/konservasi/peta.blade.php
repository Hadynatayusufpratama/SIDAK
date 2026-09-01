<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta GIS Kawasan - SIDAK BKSDA Sulteng</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Leaflet GIS Map CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        #map { height: calc(100vh - 140px); width: 100%; border-radius: 1rem; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex antialiased">

    <!-- SIDEBAR NAVIGASI UTAMA (Presisi & Seragam) -->
    <aside class="w-72 bg-gradient-to-b from-emerald-950 via-emerald-900 to-slate-900 border-r border-emerald-800/40 flex flex-col justify-between shrink-0 hidden md:flex min-h-screen sticky top-0 shadow-2xl z-40">
        <div>
            <!-- Header Brand BKSDA Sulteng -->
            <div class="p-5 border-b border-emerald-800/40 bg-emerald-950/60 flex items-center gap-3.5 backdrop-blur-md">
                <div class="w-12 h-12 bg-white rounded-xl shadow-md flex items-center justify-center shrink-0 p-1.5 border border-white/20">
                    <img src="{{ asset('images/logo-bksda.jpeg') }}" alt="Logo BKSDA" class="w-full h-full object-contain scale-110" onerror="this.src='https://via.placeholder.com/50?text=BKSDA'">
                </div>
                <div>
                    <h1 class="font-black text-sm tracking-wide text-white leading-tight">SIDAK BKSDA</h1>
                    <p class="text-[11px] font-black text-amber-400 tracking-wider">SULAWESI TENGAH</p>
                </div>
            </div>

            <!-- Menu Navigasi -->
            <nav class="p-4 space-y-1.5 text-sm">
                <div class="px-3 py-2 text-[10px] font-extrabold uppercase tracking-widest text-emerald-400/80">Main Menu</div>

                <!-- Dashboard Analytics -->
                <a href="{{ route('konservasi.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium {{ request()->routeIs('konservasi.dashboard') ? 'bg-emerald-800 text-white font-bold shadow-lg shadow-emerald-950/40 border border-emerald-600/30' : 'text-slate-300 hover:text-white hover:bg-emerald-800/40' }}">
                    <i class="fas fa-chart-pie w-5 {{ request()->routeIs('konservasi.dashboard') ? 'text-amber-400' : 'text-emerald-400' }}"></i>
                    <span>Dashboard Analytics</span>
                </a>

                <!-- Input Data Konservasi -->
                <a href="{{ route('konservasi.create') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium {{ request()->routeIs('konservasi.create') ? 'bg-emerald-800 text-white font-bold shadow-lg shadow-emerald-950/40 border border-emerald-600/30' : 'text-slate-300 hover:text-white hover:bg-emerald-800/40' }}">
                    <i class="fas fa-file-pen w-5 {{ request()->routeIs('konservasi.create') ? 'text-amber-400' : 'text-emerald-400' }}"></i>
                    <span>Input Data Konservasi</span>
                </a>

                <!-- Rekapitulasi Data -->
                <a href="{{ route('konservasi.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium {{ request()->routeIs('konservasi.index') ? 'bg-emerald-800 text-white font-bold shadow-lg shadow-emerald-950/40 border border-emerald-600/30' : 'text-slate-300 hover:text-white hover:bg-emerald-800/40' }}">
                    <i class="fas fa-database w-5 {{ request()->routeIs('konservasi.index') ? 'text-amber-400' : 'text-emerald-400' }}"></i>
                    <span>Rekapitulasi Data</span>
                </a>

                <!-- Peta GIS Kawasan (Aktif) -->
                <a href="{{ route('konservasi.peta') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium {{ request()->routeIs('konservasi.peta') ? 'bg-emerald-800 text-white font-bold shadow-lg shadow-emerald-950/40 border border-emerald-600/30' : 'text-slate-300 hover:text-white hover:bg-emerald-800/40' }}">
                    <i class="fas fa-map-location-dot w-5 {{ request()->routeIs('konservasi.peta') ? 'text-amber-400' : 'text-emerald-400' }}"></i>
                    <span>Peta GIS Kawasan</span>
                </a>
            </nav>
        </div>

        <!-- Tombol Logout & Footer Sidebar -->
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
    <div class="flex-1 flex flex-col min-w-0 bg-slate-100">

        <!-- TOPBAR NAVBAR -->
        <header class="h-16 border-b border-emerald-900/10 bg-white/90 backdrop-blur-md px-6 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-3">
                <button class="md:hidden text-emerald-900 hover:text-emerald-700 text-lg">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="hidden sm:flex items-center gap-2 text-xs text-slate-500">
                    <span class="font-medium text-slate-600">Sistem Informasi</span>
                    <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
                    <span class="text-[#005826] font-bold">Peta GIS Sebaran Kawasan</span>
                </div>
            </div>

            <!-- Profile & Badge Topbar -->
            <div class="flex items-center gap-4">
                <div class="hidden lg:flex items-center gap-2 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-xl text-xs">
                    <i class="fas fa-building-columns text-[#005826]"></i>
                    <span class="font-bold text-[#005826]">BALAI KSDA SULAWESI TENGAH</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-emerald-800 text-white font-bold rounded-xl flex items-center justify-center text-xs shadow-md uppercase">
                        {{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}
                    </div>
                    <div class="text-left hidden sm:block">
                        <p class="text-xs font-bold text-slate-800 leading-tight">
                            {{ Auth::user()->name ?? 'Administrator' }}
                        </p>
                        <p class="text-[10px] text-slate-500">
                            {{ Auth::user()->email ?? 'Petugas Operator' }}
                        </p>
                    </div>
                </div>
            </div>
        </header>

        <!-- KONTEN PETA GIS -->
        <main class="p-6 md:p-8 max-w-7xl mx-auto w-full space-y-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-lg">
                <div id="map" class="shadow-inner"></div>
            </div>
        </main>
    </div>

    <!-- Leaflet Map Script -->
    <script>
        // Inisialisasi Peta Berpusat di Sulawesi Tengah
        var map = L.map('map').setView([-0.8971, 119.8712], 7);

        // Tile Layer OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; BKSDA Sulawesi Tengah | OpenStreetMap'
        }).addTo(map);

        // Parsing Data Konservasi ke Marker Peta
        var locations = @json($locations);

        locations.forEach(function(item) {
            if(item.latitude && item.longitude) {
                var marker = L.marker([item.latitude, item.longitude]).addTo(map);
                
                var popupContent = `
                    <div class="p-1">
                        <strong class="text-xs text-[#005826] block">${item.sub_bidang ? item.sub_bidang.bidang.nama_bidang : ''}</strong>
                        <h4 class="font-bold text-sm text-slate-800">${item.sub_bidang ? item.sub_bidang.nama_sub_bidang : ''}</h4>
                        <p class="text-xs text-slate-600 mt-1">Volume: <strong>${item.jumlah ?? '-'}</strong></p>
                        <p class="text-xs text-slate-500 mt-1">${item.keterangan ?? ''}</p>
                    </div>
                `;
                marker.bindPopup(popupContent);
            }
        });
    </script>
</body>
</html>