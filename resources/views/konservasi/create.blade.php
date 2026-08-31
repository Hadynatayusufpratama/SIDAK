<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIDAK BKSDA - Balai KSDA Sulawesi Tengah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex antialiased">

    <!-- SIDEBAR NAVIGASI UTAMA (Identitas Warna BKSDA - Dark Forest Green) -->
    <aside class="w-72 bg-[#003818] border-r border-emerald-900 flex flex-col justify-between shrink-0 hidden md:flex min-h-screen sticky top-0 shadow-2xl">
        <div>
            <!-- Header Brand BKSDA Sulteng -->
            <div class="p-5 border-b border-emerald-900/80 bg-[#002e13] flex items-center gap-3.5">
                <div class="w-11 h-11 bg-white p-1.5 rounded-xl shadow-md flex items-center justify-center shrink-0">
                    <img src="{{ asset('images/logo-bksda.jpeg') }}" alt="Logo BKSDA" class="h-full w-auto object-contain" onerror="this.src='https://via.placeholder.com/50?text=BKSDA'">
                </div>
                <div>
                    <h1 class="font-extrabold text-sm tracking-wide text-white leading-tight">SIDAK BKSDA</h1>
                    <p class="text-[11px] text-yellow-400 font-semibold tracking-wider">SULAWESI TENGAH</p>
                </div>
            </div>
            

            <!-- Menu Navigasi -->
            <nav class="p-4 space-y-1.5 text-sm">
                <div class="px-3 py-2 text-[10px] font-extrabold uppercase tracking-widest text-emerald-400/70">Main Menu</div>

             <a href="{{ route('konservasi.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-emerald-100/70 hover:text-white hover:bg-emerald-900/50 transition">
                <i class="fas fa-chart-pie w-5 text-emerald-400"></i>
                <span>Dashboard Analytics</span>
            </a>
    
                <a href="{{ route('konservasi.create') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-800 to-emerald-700 text-white font-bold shadow-md border border-emerald-500/30">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-pen w-5 text-yellow-400"></i>
                        <span>Input Data Konservasi</span>
                    </div>
                </a>

                
<a href="{{ route('konservasi.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-emerald-100 hover:bg-emerald-800/50 transition font-medium text-sm">
    <i class="fas fa-database w-5 text-emerald-400"></i>
    <span>Rekapitulasi Data</span>
</a>

                <a href="{{ route('konservasi.peta') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-emerald-100/70 hover:text-white hover:bg-emerald-900/50 transition">
                    <i class="fas fa-map-location-dot w-5 text-emerald-400"></i>
                    <span>Peta GIS Kawasan</span>
                </a>

                

            </nav>
        </div>

        <!-- Footer Sidebar Status System -->
        <div class="p-4 m-3 bg-[#002b12] border border-emerald-800/80 rounded-2xl">
            <div class="flex items-center gap-3">
                <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
                <div class="text-xs">
                    <p class="font-bold text-white">BKSDA SULTENG</p>
                    <p class="text-[10px] text-emerald-300">Kementerian Kehutanan RI</p>
                </div>
            </div>
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
                    <span class="text-[#005826] font-bold">Entry Data Konservasi</span>
                </div>
            </div>
            

            <!-- Profile & Quick Action Topbar -->
            <div class="flex items-center gap-4">
                <div class="bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-xl flex items-center gap-2 text-xs">
                    <i class="fas fa-building-columns text-[#005826]"></i>
                    <span class="font-bold text-[#005826]">BALAI KSDA SULAWESI TENGAH</span>
                </div>
                <div class="flex items-center gap-3">
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
            </div>
        </header>

        <!-- KONTEN FORMULAR -->
        <main class="p-6 md:p-8 max-w-6xl mx-auto w-full space-y-6">

            <!-- Banner Header Page (Identitas Khas BKSDA Green & Gold) -->
            <div class="bg-gradient-to-r from-[#00421c] via-[#005826] to-[#007031] text-white p-6 rounded-2xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shadow-xl relative overflow-hidden border-b-4 border-yellow-500">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-yellow-400/10 rounded-full blur-2xl pointer-events-none"></div>
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-yellow-400 text-emerald-950 font-black mb-2 shadow-sm">
                        <i class="fas fa-database"></i> Database SIDAK
                    </span>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight">Formulir Entry Data Konservasi</h1>
                    <p class="text-xs text-emerald-100 mt-1">Sistem pencatatan terpadu capaian kinerja dan inventarisasi Balai KSDA Sulawesi Tengah.</p>
                </div>
            </div>

            <!-- Container Form Card -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 shadow-lg relative">
                <form action="{{ route('konservasi.store') }}" method="POST" class="space-y-8">
                    @csrf

                    @if(session('success'))
                        <div class="p-4 bg-emerald-50 border-l-4 border-[#005826] text-emerald-900 rounded-r-xl flex items-center gap-3">
                            <i class="fas fa-circle-check text-[#005826] text-xl"></i>
                            <span class="text-xs font-bold">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- SECTION 1: KLASIFIKASI DATA -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 pb-3 border-b border-slate-200">
                            <span class="w-7 h-7 rounded-lg bg-[#005826] text-yellow-400 flex items-center justify-center text-xs font-black shadow-sm">1</span>
                            <h2 class="text-sm font-bold text-[#005826] uppercase tracking-wider">Klasifikasi & Kategori Data</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Dropdown Bidang Utama -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Bidang Utama <span class="text-red-500">*</span>
                                </label>
                                <select id="bidang_select" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:ring-2 focus:ring-[#005826] focus:border-[#005826] focus:bg-white outline-none text-sm transition font-medium">
                                    <option value="">-- Pilih Bidang Utama --</option>
                                    @foreach($bidang as $b)
                                        <option value="{{ $b->id }}">{{ $b->nama_bidang }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Dropdown Sub-Bidang -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Sub-Bidang Kategori <span class="text-red-500">*</span>
                                </label>
                                <select name="sub_bidang_id" id="sub_bidang_select" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:ring-2 focus:ring-[#005826] focus:border-[#005826] focus:bg-white outline-none text-sm transition font-medium disabled:opacity-50 disabled:cursor-not-allowed" disabled required>
                                    <option value="">-- Pilih Bidang Terlebih Dahulu --</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: WAKTU & SPASIAL -->
                    <div class="space-y-4 pt-2">
                        <div class="flex items-center gap-2 pb-3 border-b border-slate-200">
                            <span class="w-7 h-7 rounded-lg bg-[#005826] text-yellow-400 flex items-center justify-center text-xs font-black shadow-sm">2</span>
                            <h2 class="text-sm font-bold text-[#005826] uppercase tracking-wider">Waktu & Koordinat Spasial</h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tahun <span class="text-red-500">*</span></label>
                                <input type="number" name="tahun" value="{{ date('Y') }}" required class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:ring-2 focus:ring-[#005826] focus:bg-white outline-none text-sm font-medium">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Bulan</label>
                                <select name="bulan" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:ring-2 focus:ring-[#005826] focus:bg-white outline-none text-sm font-medium">
                                    <option value="">-- Opsional --</option>
                                    @for($i=1; $i<=12; $i++)
                                        <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Latitude (LS/LU)</label>
                                <input type="text" name="latitude" placeholder="-0.897123" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:ring-2 focus:ring-[#005826] focus:bg-white outline-none text-sm font-medium placeholder:text-slate-400">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Longitude (BT)</label>
                                <input type="text" name="longitude" placeholder="119.87123" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:ring-2 focus:ring-[#005826] focus:bg-white outline-none text-sm font-medium placeholder:text-slate-400">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: DETAIL ISIAN KUANTITATIF -->
                    <div id="dynamic_fields" class="p-5 bg-emerald-50/60 border border-emerald-300/80 rounded-2xl space-y-4 hidden transition-all">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-list-check text-[#005826]"></i>
                            <h3 class="text-xs font-bold text-[#005826] uppercase tracking-wider">Detail Isian Kuantitatif</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah / Volume</label>
                                <input type="number" name="jumlah" placeholder="Contoh: 12" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:ring-2 focus:ring-[#005826] outline-none text-sm font-medium">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan / Satuan / Catatan Lapangan</label>
                                <input type="text" name="keterangan" placeholder="Contoh: Peta terverifikasi / Eksemplar dokumen" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:ring-2 focus:ring-[#005826] outline-none text-sm font-medium">
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-4 border-t border-slate-200 flex justify-end">
                        <button type="submit" class="w-full sm:w-auto bg-[#005826] hover:bg-[#00421c] text-white font-extrabold py-3.5 px-8 rounded-xl shadow-lg hover:shadow-xl transition duration-200 flex items-center justify-center gap-2 text-sm border-b-2 border-yellow-500">
                            <i class="fas fa-floppy-disk text-yellow-400"></i> Simpan Data Konservasi
                        </button>
                    </div>
                </form>
                <!-- Form Logout -->
<form method="POST" action="{{ route('logout') }}" class="mt-auto pt-4">
    @csrf
    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 rounded-xl transition-all duration-200 group">
        <svg class="w-5 h-5 text-rose-400 group-hover:text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
        </svg>
        <span>Keluar / Logout</span>
    </button>
</form>
            </div>
        </main>
    </div>

    <!-- AJAX Script -->
    <script>
        document.getElementById('bidang_select').addEventListener('change', function() {
            let bidangId = this.value;
            let subSelect = document.getElementById('sub_bidang_select');
            let dynamicArea = document.getElementById('dynamic_fields');
            
            subSelect.innerHTML = '<option value="">Memuat sub-bidang...</option>';
            subSelect.disabled = true;

            if (bidangId) {
                fetch('/get-sub-bidang/' + bidangId)
                    .then(response => response.json())
                    .then(data => {
                        subSelect.innerHTML = '<option value="">-- Pilih Sub-Bidang Kategori --</option>';
                        data.forEach(item => {
                            subSelect.innerHTML += `<option value="${item.id}">${item.kode_sub}. ${item.nama_sub_bidang}</option>`;
                        });
                        subSelect.disabled = false;
                        dynamicArea.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error fetching sub-bidang:', error);
                        subSelect.innerHTML = '<option value="">-- Gagal memuat sub-bidang --</option>';
                    });
            } else {
                subSelect.innerHTML = '<option value="">-- Pilih Bidang Terlebih Dahulu --</option>';
                subSelect.disabled = true;
                dynamicArea.classList.add('hidden');
            }
        });
    </script>
    
</body>
</html>