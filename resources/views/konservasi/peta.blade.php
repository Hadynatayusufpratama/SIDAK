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
        #map { height: calc(100vh - 120px); width: 100%; border-radius: 1rem; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex antialiased">

    <!-- SIDEBAR NAVIGASI -->
    <aside class="w-72 bg-[#003818] border-r border-emerald-900 flex flex-col justify-between shrink-0 hidden md:flex min-h-screen sticky top-0 shadow-2xl">
        <div>
            <div class="p-5 border-b border-emerald-900/80 bg-[#002e13] flex items-center gap-3.5">
                <div class="w-11 h-11 bg-white p-1.5 rounded-xl shadow-md flex items-center justify-center shrink-0">
                    <img src="{{ asset('images/logo-bksda.jpeg') }}" alt="Logo BKSDA" class="h-full w-auto object-contain" onerror="this.src='https://via.placeholder.com/50?text=BKSDA'">
                </div>
                <div>
                    <h1 class="font-extrabold text-sm tracking-wide text-white leading-tight">SIDAK BKSDA</h1>
                    <p class="text-[11px] text-yellow-400 font-semibold tracking-wider">SULAWESI TENGAH</p>
                </div>
            </div>

            <nav class="p-4 space-y-1.5 text-sm">
                <div class="px-3 py-2 text-[10px] font-extrabold uppercase tracking-widest text-emerald-400/70">Main Menu</div>

                <a href="{{ route('konservasi.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-emerald-100/70 hover:text-white hover:bg-emerald-900/50 transition">
    <i class="fas fa-chart-pie w-5 text-emerald-400"></i>
    <span>Dashboard Analytics</span>
</a>

                <a href="{{ route('konservasi.create') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-emerald-100/70 hover:text-white hover:bg-emerald-900/50 transition">
                    <i class="fas fa-file-pen w-5 text-emerald-400"></i>
                    <span>Input Data Konservasi</span>
                </a>

                <a href="{{ route('konservasi.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-emerald-100/70 hover:text-white hover:bg-emerald-900/50 transition">
                    <i class="fas fa-database w-5 text-emerald-400"></i>
                    <span>Rekapitulasi Data</span>
                </a>

                <a href="{{ route('konservasi.peta') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-800 to-emerald-700 text-white font-bold shadow-md border border-emerald-500/30">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-map-location-dot w-5 text-yellow-400"></i>
                        <span>Peta GIS Kawasan</span>
                    </div>
                </a>
            </nav>
        </div>
    </aside>
    

    <!-- AREA KONTEN UTAMA -->
    <div class="flex-1 flex flex-col min-w-0 bg-slate-100">
        <header class="h-16 border-b border-emerald-900/10 bg-white/90 backdrop-blur-md px-6 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="font-medium text-slate-600 text-xs">Sistem Informasi</span>
                <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-[#005826] font-bold text-xs">Peta GIS Sebaran Kawasan</span>
            </div>
        </header>

        <main class="p-6 md:p-8 max-w-7xl mx-auto w-full space-y-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-lg">
                <div id="map" class="shadow-inner"></div>
            </div>
        </main>
    </div>

    <!-- Leaflet Map Script -->
    <script>
        // Inisialisasi Peta Berpusat di Sulawesi Tengah (Palu / Morowali area)
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