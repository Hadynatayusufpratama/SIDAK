<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Data - SIDAK BKSDA Sulteng</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
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

                <a href="{{ route('konservasi.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-800 to-emerald-700 text-white font-bold shadow-md border border-emerald-500/30">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-database w-5 text-yellow-400"></i>
                        <span>Rekapitulasi Data</span>
                    </div>
                </a>

                <a href="{{ route('konservasi.peta') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-emerald-100/70 hover:text-white hover:bg-emerald-900/50 transition">
                    <i class="fas fa-map-location-dot w-5 text-emerald-400"></i>
                    <span>Peta GIS Kawasan</span>
                </a>
            </nav>
        </div>
    </aside>
    

    <!-- AREA KONTEN UTAMA -->
    <div class="flex-1 flex flex-col min-w-0 bg-slate-100">

        <!-- TOPBAR NAVBAR -->
        <header class="h-16 border-b border-emerald-900/10 bg-white/90 backdrop-blur-md px-6 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="font-medium text-slate-600 text-xs">Sistem Informasi</span>
                <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-[#005826] font-bold text-xs">Rekapitulasi Data Konservasi</span>
            </div>
            <a href="{{ route('konservasi.create') }}" class="bg-[#005826] hover:bg-[#00421c] text-white text-xs font-bold py-2 px-4 rounded-xl flex items-center gap-2 border-b-2 border-yellow-500 shadow-md">
                <i class="fas fa-plus text-yellow-400"></i> Tambah Data Baru
            </a>
        </header>

        <main class="p-6 md:p-8 max-w-7xl mx-auto w-full space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-[#005826] text-emerald-900 rounded-r-xl flex items-center gap-3">
                    <i class="fas fa-circle-check text-[#005826] text-xl"></i>
                    <span class="text-xs font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Table Card Container -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-extrabold text-[#005826]">Riwayat Data Konservasi</h2>
                        <p class="text-xs text-slate-500 mt-1">Daftar entri data kinerja dan kegiatan di lingkungan BKSDA Sulteng</p>
                    </div>

                    <!-- Search Form -->
                    <form action="{{ route('konservasi.index') }}" method="GET" class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari sub-bidang/keterangan..." class="p-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#005826] outline-none w-64">
                        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-600">
                                <th class="p-4">No</th>
                                <th class="p-4">Bidang Utama</th>
                                <th class="p-4">Sub-Bidang Kategori</th>
                                <th class="p-4">Waktu</th>
                                <th class="p-4">Jumlah/Vol</th>
                                <th class="p-4">Koordinat</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                            @forelse($data as $index => $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-4 font-bold text-slate-400">{{ $data->firstItem() + $index }}</td>
                                    <td class="p-4 font-bold text-[#005826]">{{ $item->subBidang->bidang->nama_bidang ?? '-' }}</td>
                                    <td class="p-4">
                                        <span class="font-bold text-slate-800">{{ $item->subBidang->kode_sub }}. {{ $item->subBidang->nama_sub_bidang }}</span>
                                        @if($item->keterangan)
                                            <p class="text-[11px] text-slate-500 mt-0.5">{{ $item->keterangan }}</p>
                                        @endif
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        {{ $item->bulan ? date('F', mktime(0, 0, 0, $item->bulan, 1)) : '' }} {{ $item->tahun }}
                                    </td>
                                    <td class="p-4 font-bold text-slate-900">{{ $item->jumlah ?? '-' }}</td>
                                    <td class="p-4 text-[11px] font-mono text-slate-500">
                                        @if($item->latitude && $item->longitude)
                                            <i class="fas fa-location-dot text-red-500 mr-1"></i>{{ $item->latitude }}, {{ $item->longitude }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
    <div class="flex items-center justify-center gap-2">
        <!-- Tombol Edit (Kuning/Amber) -->
        <a href="{{ route('konservasi.edit', $item->id) }}" 
           class="p-2 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-lg transition border border-amber-200" 
           title="Edit Data">
            <i class="fas fa-pen-to-square"></i>
        </a>

        <!-- Tombol Hapus (Merah) -->
        <form action="{{ route('konservasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition border border-red-200" 
                    title="Hapus Data">
                <i class="fas fa-trash-can"></i>
            </button>
        </form>
    </div>
</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-slate-400 font-medium">
                                        Belum ada data konservasi tersimpan. Silakan tambahkan data baru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="p-4 border-t border-slate-100 bg-slate-50">
                    {{ $data->links() }}
                </div>
            </div>
        </main>
    </div>

</body>
</html>