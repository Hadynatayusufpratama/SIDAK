<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Konservasi - SIDAK BKSDA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 font-sans antialiased text-slate-800 min-h-screen flex">

    <!-- SIDEBAR -->
    <aside class="w-72 bg-[#003818] border-r border-emerald-900 flex flex-col justify-between shrink-0 hidden md:flex min-h-screen">
        <div>
            <!-- Logo Brand -->
            <div class="p-6 flex items-center gap-3 border-b border-emerald-900/50">
                <div class="w-10 h-10 bg-white rounded-xl shadow-md flex items-center justify-center font-bold text-emerald-900 text-lg">
                    S
                </div>
                <div>
                    <h1 class="font-bold text-white leading-tight">SIDAK BKSDA</h1>
                    <p class="text-[10px] text-emerald-400 font-semibold tracking-wider uppercase">SULAWESI TENGAH</p>
                </div>
            </div>

            <!-- Menu Navigasi -->
            <nav class="p-4 space-y-1.5 text-sm">
                <div class="px-3 py-2 text-[10px] font-extrabold uppercase tracking-widest text-emerald-400/70">Main Menu</div>
                
                <a href="{{ route('konservasi.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-emerald-100/70 hover:text-white hover:bg-emerald-900/50 transition font-medium">
                    <i class="fas fa-chart-pie w-5 text-emerald-400"></i>
                    <span>Dashboard Analytics</span>
                </a>

                <a href="{{ route('konservasi.create') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-emerald-100/70 hover:text-white hover:bg-emerald-900/50 transition font-medium">
                    <i class="fas fa-file-pen w-5 text-emerald-400"></i>
                    <span>Input Data Konservasi</span>
                </a>

                <a href="{{ route('konservasi.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-900/30">
                    <i class="fas fa-database w-5 text-yellow-400"></i>
                    <span>Rekapitulasi Data</span>
                </a>

                <a href="{{ route('konservasi.peta') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-emerald-100/70 hover:text-white hover:bg-emerald-900/50 transition font-medium">
                    <i class="fas fa-map-location-dot w-5 text-emerald-400"></i>
                    <span>Peta GIS Kawasan</span>
                </a>
            </nav>
        </div>
    </aside>

    <!-- CONTENT AREA -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen">
        
        <!-- HEADER TOPBAR -->
        <header class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30 px-8 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <span>Sistem Informasi</span>
                <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
                <a href="{{ route('konservasi.index') }}" class="hover:text-emerald-700">Rekapitulasi Data</a>
                <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-emerald-800 font-semibold">Edit Data Konservasi</span>
            </div>

            <<div class="flex items-center gap-3">
    <!-- Inisial 2 Huruf Pertama dari Nama User -->
    <div class="w-9 h-9 bg-emerald-800 text-white font-bold rounded-xl flex items-center justify-center text-xs shadow-md uppercase">
        {{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}
    </div>
    <div class="text-left hidden sm:block">
        <!-- Nama User yang Login -->
        <p class="text-xs font-bold text-slate-800 leading-tight">
            {{ Auth::user()->name ?? 'Administrator' }}
        </p>
        <!-- Email atau Role User -->
        <p class="text-[10px] text-slate-500">
            {{ Auth::user()->email ?? 'Petugas Operator' }}
        </p>
    </div>
</div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="p-8 flex-1">
            <div class="max-w-5xl mx-auto">
                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
                    
                    <!-- Form Header Banner -->
                    <div class="bg-gradient-to-r from-[#003818] via-emerald-800 to-teal-900 p-8 text-white flex justify-between items-center relative overflow-hidden">
                        <div class="relative z-10">
                            <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 text-[10px] font-bold tracking-widest uppercase rounded-full border border-emerald-400/30">Mode Perubahan Data</span>
                            <h2 class="text-2xl font-black mt-2 tracking-tight">Edit Data Konservasi</h2>
                            <p class="text-xs text-emerald-100/80 mt-1">Perbarui informasi data capaian kinerja dan inventarisasi kawasan.</p>
                        </div>
                        <a href="{{ route('konservasi.index') }}" class="relative z-10 px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold rounded-xl border border-white/20 transition flex items-center gap-2 backdrop-blur-md shadow-lg">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <!-- Form Input -->
                    <form action="{{ route('konservasi.update', $item->id) }}" method="POST" class="p-8 space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- Section 1: Klasifikasi -->
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-800 mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span> Klasifikasi & Kategori Data
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Bidang Utama -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Bidang Utama *</label>
                                    <div class="relative">
                                        <select id="bidang_id" name="bidang_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-600 focus:bg-white transition appearance-none font-medium text-slate-800 pr-10" required>
                                            <option value="">-- Pilih Bidang Utama --</option>
                                            @foreach($bidangs as $b)
                                                <option value="{{ $b->id }}" {{ (isset($currentBidangId) && $currentBidangId == $b->id) ? 'selected' : '' }}>
                                                    {{ $b->nama_bidang }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                                    </div>
                                </div>

                                <!-- Sub Bidang -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Sub-Bidang Kategori *</label>
                                    <div class="relative">
                                        <select id="sub_bidang_id" name="sub_bidang_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-600 focus:bg-white transition appearance-none font-medium text-slate-800 pr-10" required>
                                            <option value="">-- Pilih Sub-Bidang --</option>
                                            @foreach($subBidangs as $sb)
                                                <option value="{{ $sb->id }}" {{ $item->sub_bidang_id == $sb->id ? 'selected' : '' }}>
                                                    {{ $sb->nama_sub_bidang }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="border-slate-100">

                        <!-- Section 2: Spasial & Waktu -->
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-800 mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span> Waktu & Koordinat Spasial
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                                <!-- Tahun -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tahun *</label>
                                    <input type="number" name="tahun" value="{{ $item->tahun }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-600 focus:bg-white transition font-medium text-slate-800" required>
                                </div>

                                <!-- Bulan -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Bulan</label>
                                    <input type="text" name="bulan" value="{{ $item->bulan }}" placeholder="-- Opsional --" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-600 focus:bg-white transition font-medium text-slate-800">
                                </div>

                                <!-- Latitude -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Latitude (LS/LU)</label>
                                    <div class="relative">
                                        <input type="text" name="latitude" value="{{ $item->latitude }}" placeholder="-0.897123" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-600 focus:bg-white transition font-medium text-slate-800 pr-9">
                                        <i class="fas fa-location-dot absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    </div>
                                </div>

                                <!-- Longitude -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Longitude (BT)</label>
                                    <div class="relative">
                                        <input type="text" name="longitude" value="{{ $item->longitude }}" placeholder="119.87123" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-600 focus:bg-white transition font-medium text-slate-800 pr-9">
                                        <i class="fas fa-location-dot absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                            <a href="{{ route('konservasi.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-2">
                                <i class="fas fa-xmark"></i> Batal
                            </a>
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-emerald-800 to-teal-800 hover:from-emerald-900 hover:to-teal-900 text-white rounded-xl text-xs font-bold shadow-lg shadow-emerald-900/20 transition flex items-center gap-2">
                                <i class="fas fa-floppy-disk"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- AJAX Script untuk Sub-Bidang -->
    <script>
    document.getElementById('bidang_id').addEventListener('change', function () {
        let bidangId = this.value;
        let subBidangSelect = document.getElementById('sub_bidang_id');

        subBidangSelect.innerHTML = '<option value="">-- Memuat Sub-Bidang... --</option>';

        if (bidangId) {
            fetch(`/get-sub-bidang/${bidangId}`)
                .then(response => response.json())
                .then(data => {
                    subBidangSelect.innerHTML = '<option value="">-- Pilih Sub-Bidang --</option>';
                    data.forEach(item => {
                        subBidangSelect.innerHTML += `<option value="${item.id}">${item.nama_sub_bidang}</option>`;
                    });
                });
        }
    });
    </script>
</body>
</html>