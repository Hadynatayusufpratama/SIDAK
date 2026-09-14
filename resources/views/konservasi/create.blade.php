<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-slate-800 leading-tight">
                    {{ __('Input Data Konservasi') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Sistem pencatatan terpadu capaian kinerja dan inventarisasi Balai KSDA Sulawesi Tengah
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('konservasi.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold py-2.5 px-4 rounded-xl flex items-center gap-2 transition">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Rekapitulasi</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Flash Alert Success -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <i class="fas fa-circle-check"></i>
                    </div>
                    <span class="text-xs font-bold">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-sm">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
        @endif

        <!-- Card Container Form -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 md:p-8 shadow-sm">
            <form action="{{ route('konservasi.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- SECTION 1: KLASIFIKASI DATA -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <span class="w-7 h-7 rounded-lg bg-emerald-700 text-amber-400 flex items-center justify-center text-xs font-black shadow-sm">1</span>
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Klasifikasi & Kategori Data</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Dropdown Bidang Utama -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Bidang Utama <span class="text-rose-500">*</span>
                            </label>
                            <select id="bidang_select" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:bg-white outline-none text-xs font-medium transition">
                                <option value="">-- Pilih Bidang Utama --</option>
                                @foreach($bidang as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama_bidang }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dropdown Sub-Bidang -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Sub-Bidang Kategori <span class="text-rose-500">*</span>
                            </label>
                            <select name="sub_bidang_id" id="sub_bidang_select" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:bg-white outline-none text-xs font-medium transition disabled:opacity-50 disabled:cursor-not-allowed" disabled required>
                                <option value="">-- Pilih Bidang Terlebih Dahulu --</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: WAKTU & SPASIAL -->
                <div class="space-y-4 pt-2">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <span class="w-7 h-7 rounded-lg bg-emerald-700 text-amber-400 flex items-center justify-center text-xs font-black shadow-sm">2</span>
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Waktu & Koordinat Spasial</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tahun <span class="text-rose-500">*</span></label>
                            <input type="number" name="tahun" value="{{ date('Y') }}" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:ring-2 focus:ring-emerald-600 focus:bg-white outline-none text-xs font-medium">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Bulan</label>
                            <select name="bulan" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:ring-2 focus:ring-emerald-600 focus:bg-white outline-none text-xs font-medium">
                                <option value="">-- Opsional --</option>
                                @for($i=1; $i<=12; $i++)
                                    <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Latitude (LS/LU)</label>
                            <input type="text" name="latitude" placeholder="-0.897123" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:ring-2 focus:ring-emerald-600 focus:bg-white outline-none text-xs font-medium placeholder:text-slate-400">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Longitude (BT)</label>
                            <input type="text" name="longitude" placeholder="119.87123" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:ring-2 focus:ring-emerald-600 focus:bg-white outline-none text-xs font-medium placeholder:text-slate-400">
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: DETAIL ISIAN KUANTITATIF -->
                <div id="dynamic_fields" class="p-5 bg-emerald-50/50 border border-emerald-100 rounded-2xl space-y-4 hidden transition-all">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-list-check text-emerald-700 text-xs"></i>
                        <h4 class="text-xs font-extrabold text-emerald-800 uppercase tracking-wider">Detail Isian Kuantitatif</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah / Volume</label>
                            <input type="number" name="jumlah" placeholder="Contoh: 12" class="w-full p-3 bg-white border border-slate-200 rounded-xl text-slate-800 focus:ring-2 focus:ring-emerald-600 outline-none text-xs font-medium">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan / Satuan / Catatan Lapangan</label>
                            <input type="text" name="keterangan" placeholder="Contoh: Peta terverifikasi / Eksemplar dokumen" class="w-full p-3 bg-white border border-slate-200 rounded-xl text-slate-800 focus:ring-2 focus:ring-emerald-600 outline-none text-xs font-medium">
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-3 px-8 rounded-xl shadow-sm transition duration-200 flex items-center justify-center gap-2 text-xs">
                        <i class="fas fa-floppy-disk text-amber-400"></i>
                        <span>Simpan Data Konservasi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

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
</x-app-layout>