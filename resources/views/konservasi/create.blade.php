<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data - SIDAK BKSDA Sulawesi Tengah</title>
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

    <!-- BACKGROUND GLOBAL KAWASAN KONSERVASI -->
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
                    <span class="text-[11px] text-slate-500 font-medium">Sistem Informasi Data Konservasi</span>
                </div>
            </div>

            <!-- Menu Navigasi -->
            <nav class="hidden md:flex items-center space-x-1 bg-slate-100/80 p-1.5 rounded-xl border border-slate-200">
                <a href="{{ route('konservasi.dashboard') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Dashboard</a>
                <a href="{{ route('konservasi.index') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Rekapitulasi</a>
                <a href="{{ route('konservasi.create') }}" class="px-4 py-2 rounded-lg text-xs font-semibold bg-white text-forest-700 shadow-xs">Tambah Data</a>
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
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- HEADER BANNER & TOMBOL KEMBALI -->
        <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">
                    Input Data Konservasi
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Sistem pencatatan terpadu capaian kinerja dan inventarisasi Balai KSDA Sulawesi Tengah
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('konservasi.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-200/80 hover:bg-slate-300 transition shadow-xs">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Rekapitulasi
                </a>
            </div>
        </div>

        <!-- Flash Alert Success -->
        <?php if (session('success')): ?>
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <span class="text-xs font-bold">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        <?php endif; ?>

        <!-- CARD CONTAINER FORM -->
        <div class="bg-white/90 backdrop-blur-md border border-slate-200/80 rounded-2xl p-6 md:p-8 shadow-sm">
            <form action="{{ route('konservasi.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- SECTION 1: KLASIFIKASI DATA -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-200/80">
                        <span class="w-7 h-7 rounded-lg bg-forest-700 text-amber-400 flex items-center justify-center text-xs font-black shadow-xs">1</span>
                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Klasifikasi & Kategori Data</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Dropdown Bidang Utama -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Bidang Utama <span class="text-rose-500">*</span>
                            </label>
                            <select id="bidang_select" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white text-xs font-medium transition shadow-xs">
                                <option value="">-- Pilih Bidang Utama --</option>
                                <?php foreach ($bidang as$b): ?>
                                    <option value="{{ $b->id }}">{{ $b->nama_bidang }}</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Dropdown Sub-Bidang -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Sub-Bidang Kategori <span class="text-rose-500">*</span>
                            </label>
                            <select name="sub_bidang_id" id="sub_bidang_select" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white text-xs font-medium transition shadow-xs disabled:opacity-50 disabled:cursor-not-allowed" disabled required>
                                <option value="">-- Pilih Bidang Terlebih Dahulu --</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: WAKTU & SPASIAL -->
                <div class="space-y-4 pt-2">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-200/80">
                        <span class="w-7 h-7 rounded-lg bg-forest-700 text-amber-400 flex items-center justify-center text-xs font-black shadow-xs">2</span>
                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Waktu & Koordinat Spasial</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tahun <span class="text-rose-500">*</span></label>
                            <input type="number" name="tahun" value="{{ date('Y') }}" required class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white text-xs font-medium shadow-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Bulan</label>
                            <select name="bulan" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white text-xs font-medium shadow-xs">
                                <option value="">-- Opsional --</option>
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Latitude (LS/LU)</label>
                            <input type="text" name="latitude" placeholder="-0.897123" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white text-xs font-medium placeholder:text-slate-400 shadow-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Longitude (BT)</label>
                            <input type="text" name="longitude" placeholder="119.87123" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white text-xs font-medium placeholder:text-slate-400 shadow-xs">
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: DETAIL ISIAN KUANTITATIF -->
                <div id="dynamic_fields" class="p-5 bg-emerald-50/70 border border-emerald-200/80 rounded-2xl space-y-4 hidden transition-all shadow-xs">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-forest-700 text-xs"></i>
                        <h4 class="text-xs font-extrabold text-forest-800 uppercase tracking-wider">Detail Isian Kuantitatif</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah / Volume</label>
                            <input type="number" name="jumlah" placeholder="Contoh: 12" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 text-xs font-medium shadow-xs">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan / Satuan / Catatan Lapangan</label>
                            <input type="text" name="keterangan" placeholder="Contoh: Peta terverifikasi / Eksemplar dokumen" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 text-xs font-medium shadow-xs">
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-4 border-t border-slate-200/80 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl text-xs font-bold text-slate-950 bg-amber-400 hover:bg-amber-300 shadow-md transition duration-200 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Data Konservasi</span>
                    </button>
                </div>
            </form>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="mt-12 border-t border-slate-200 bg-white/80 backdrop-blur-md py-6 text-center text-xs text-slate-500">
        <p>&copy; 2026 <strong>SIDAK BKSDA Sulawesi Tengah</strong>. All rights reserved.</p>
    </footer>

    <!-- Script AJAX Sub-Bidang -->
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