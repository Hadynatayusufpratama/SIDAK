<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daftar Akun - SIDAK BKSDA Sulawesi Tengah</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-slate-100 text-slate-800">

    <div class="min-h-screen w-full flex flex-col lg:flex-row">
        
        <!-- Sisi Kiri: Visual Branding BKSDA (Desktop) -->
        <div class="hidden lg:flex lg:w-7/12 bg-gradient-to-br from-emerald-950 via-emerald-900 to-slate-900 p-12 flex-col justify-between relative overflow-hidden">
            
            <!-- Ornamen Blur Accent -->
            <div class="absolute -right-20 -top-20 w-96 h-96 bg-emerald-600/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-96 h-96 bg-emerald-400/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Header Banner Logo -->
            <div class="relative z-10 flex items-center gap-3.5 bg-white/10 backdrop-blur-md px-4 py-3 rounded-2xl border border-white/10 w-fit">
                <div class="w-12 h-12 bg-white rounded-xl p-1 flex items-center justify-center shadow-md shrink-0">
                    <img src="{{ asset('images/logo-bksda.png') }}" alt="Logo BKSDA Sulteng" class="w-full h-full object-contain">
                </div>
                <div class="text-white">
                    <h1 class="text-sm font-extrabold leading-tight tracking-wide">SIDAK BKSDA</h1>
                    <p class="text-xs font-black text-amber-400 tracking-wider">SULAWESI TENGAH</p>
                </div>
            </div>

            <!-- Hero Text -->
            <div class="relative z-10 my-auto py-8 max-w-xl">
                <span class="px-3 py-1.5 bg-emerald-500/20 text-emerald-300 text-xs font-semibold rounded-full border border-emerald-400/30 uppercase tracking-widest">
                    Registrasi Petugas Baru
                </span>
                <h2 class="text-4xl lg:text-5xl font-extrabold text-white mt-5 leading-tight">
                    Bergabung dengan <br>
                    <span class="text-emerald-400">SIDAK BKSDA</span>
                </h2>
                <p class="text-slate-300 text-sm mt-4 leading-relaxed font-normal">
                    Buat akun petugas untuk mengakses sistem pencatatan capaian kinerja, input data konservasi, serta visualisasi pemetaan GIS wilayah Balai KSDA Sulawesi Tengah.
                </p>
            </div>

            <!-- Footer Kiri -->
            <div class="relative z-10 border-t border-white/10 pt-4 text-xs text-slate-400">
                &copy; {{ date('Y') }} Balai KSDA Sulawesi Tengah. All Rights Reserved.
            </div>
        </div>

        <!-- Sisi Kanan: Form Registrasi -->
        <div class="w-full lg:w-5/12 flex items-center justify-center p-6 sm:p-12 bg-white min-h-screen">
            <div class="w-full max-w-md space-y-5">
                
                <!-- Logo Mobile -->
                <div class="lg:hidden text-center mb-4">
                    <img src="{{ asset('images/logo-bksda.png') }}" alt="Logo BKSDA Sulteng" class="h-14 w-auto mx-auto mb-2 object-contain">
                    <h2 class="text-xl font-black text-slate-800">SIDAK BKSDA SULTENG</h2>
                    <p class="text-xs text-slate-500">Balai Konservasi Sumber Daya Alam Sulawesi Tengah</p>
                </div>

                <!-- Title Form -->
                <div>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">Pendaftaran Akun</h3>
                    <p class="text-xs text-slate-500 mt-1">Lengkapi data berikut untuk membuat akun petugas baru</p>
                </div>

                <!-- Form Registrasi -->
                <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Nama Lengkap
                        </label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                            placeholder="Nama Lengkap Petugas"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 text-slate-800 transition-all outline-none" />
                        @if ($errors->has('name'))
                            <p class="text-xs text-rose-600 mt-1">{{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Alamat Email
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                            placeholder="nama@bksda.go.id"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 text-slate-800 transition-all outline-none" />
                        @if ($errors->has('email'))
                            <p class="text-xs text-rose-600 mt-1">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <!-- Kata Sandi -->
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Kata Sandi
                        </label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                            placeholder="Minimal 8 karakter"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 text-slate-800 transition-all outline-none" />
                        @if ($errors->has('password'))
                            <p class="text-xs text-rose-600 mt-1">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <!-- Konfirmasi Kata Sandi -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Konfirmasi Kata Sandi
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                            placeholder="Ulangi kata sandi di atas"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 text-slate-800 transition-all outline-none" />
                        @if ($errors->has('password_confirmation'))
                            <p class="text-xs text-rose-600 mt-1">{{ $errors->first('password_confirmation') }}</p>
                        @endif
                    </div>

                    <!-- Tombol Register -->
                    <div class="pt-2">
                        <button type="submit" 
                            class="w-full py-3 px-4 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-900/20 active:scale-[0.99] transition-all duration-150 flex items-center justify-center gap-2">
                            <span>Daftar Akun Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Link Kembali ke Login -->
                    <div class="text-center pt-3 border-t border-slate-100 mt-4">
                        <p class="text-xs text-slate-500">
                            Sudah memiliki akun? 
                            <a href="{{ route('login') }}" class="font-bold text-emerald-700 hover:text-emerald-800 hover:underline">
                                Masuk di Sini
                            </a>
                        </p>
                    </div>
                </form>

            </div>
        </div>

    </div>

</body>
</html>