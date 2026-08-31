<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - SIDAK BKSDA Sulawesi Tengah</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS via CDN -->
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-slate-100 text-slate-800">

    <div class="min-h-screen w-full flex flex-col lg:flex-row">
        
        <!-- Sisi Kiri: Visual Branding BKSDA (Tampil di Layar Komputer) -->
        <div class="hidden lg:flex lg:w-7/12 bg-gradient-to-br from-emerald-950 via-emerald-900 to-slate-900 p-12 flex-col justify-between relative overflow-hidden">
            
            <!-- Ornamen Blur Accent -->
            <div class="absolute -right-20 -top-20 w-96 h-96 bg-emerald-600/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-96 h-96 bg-emerald-400/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Header Banner Logo -->
            <div class="relative z-10 flex items-center gap-4 bg-white/10 backdrop-blur-md px-5 py-3.5 rounded-2xl border border-white/10 w-fit">
                <img src="{{ asset('images/logo-bksda.png') }}" alt="Logo BKSDA Sulteng" class="h-12 w-auto object-contain bg-white p-1 rounded-lg shadow-sm">
                <div class="text-white">
                    <p class="text-[10px] uppercase tracking-widest font-bold text-emerald-400">Kementerian Kehutanan</p>
                    <h1 class="text-xs font-bold leading-tight tracking-wide">BALAI KONSERVASI SUMBER DAYA ALAM</h1>
                    <p class="text-xs font-black text-amber-400 tracking-wider">SULAWESI TENGAH</p>
                </div>
            </div>

            <!-- Hero Text -->
            <div class="relative z-10 my-auto py-12 max-w-xl">
                <span class="px-3 py-1.5 bg-emerald-500/20 text-emerald-300 text-xs font-semibold rounded-full border border-emerald-400/30 uppercase tracking-widest">
                    Sistem Informasi & Data Konservasi
                </span>
                <h2 class="text-4xl lg:text-5xl font-extrabold text-white mt-5 leading-tight">
                    SIDAK BKSDA <br>
                    <span class="text-emerald-400">SULAWESI TENGAH</span>
                </h2>
                <p class="text-slate-300 text-sm mt-4 leading-relaxed font-normal">
                    Platform terpadu untuk pencatatan capaian kinerja, pelaporan inventarisasi kawasan, serta manajemen data konservasi alam di wilayah Balai KSDA Sulawesi Tengah.
                </p>
            </div>

            <!-- Footer Kiri -->
            <div class="relative z-10 border-t border-white/10 pt-4 text-xs text-slate-400">
                &copy; {{ date('Y') }} Balai KSDA Sulawesi Tengah. All Rights Reserved.
            </div>
        </div>

        <!-- Sisi Kanan: Form Login -->
        <div class="w-full lg:w-5/12 flex items-center justify-center p-6 sm:p-12 bg-white min-h-screen">
            <div class="w-full max-w-md space-y-6">
                
                <!-- Logo Tampil di Mobile / Tablet -->
                <div class="lg:hidden text-center mb-6">
                    <img src="{{ asset('images/logo-bksda.png') }}" alt="Logo BKSDA Sulteng" class="h-16 w-auto mx-auto mb-3 object-contain">
                    <h2 class="text-xl font-black text-slate-800">SIDAK BKSDA SULTENG</h2>
                    <p class="text-xs text-slate-500">Balai Konservasi Sumber Daya Alam Sulawesi Tengah</p>
                </div>

                <!-- Title Form -->
                <div>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">Selamat Datang</h3>
                    <p class="text-xs text-slate-500 mt-1">Silakan masukkan akun Anda untuk masuk ke dalam sistem</p>
                </div>

                <!-- Alert Error / Status -->
                @if (session('status'))
                    <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-xl">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Form Login -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Alamat Email
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            placeholder="nama@bksda.go.id"
                            class="w-full px-4 py-3 text-sm rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 text-slate-800 transition-all outline-none" />
                        @if ($errors->has('email'))
                            <p class="text-xs text-rose-600 mt-1">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Kata Sandi
                        </label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full px-4 py-3 text-sm rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 text-slate-800 transition-all outline-none" />
                        @if ($errors->has('password'))
                            <p class="text-xs text-rose-600 mt-1">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <!-- Remember Me & Reset Password -->
                    <div class="flex items-center justify-between text-xs pt-1">
                        <label for="remember_me" class="inline-flex items-center text-slate-600 cursor-pointer">
                            <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-600">
                            <span class="ml-2 font-medium">Ingat Saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="font-bold text-emerald-700 hover:text-emerald-800 hover:underline">
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    <!-- Tombol Login -->
                    <div class="pt-3">
                        <button type="submit" 
                            class="w-full py-3.5 px-4 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-900/20 active:scale-[0.99] transition-all duration-150 flex items-center justify-center gap-2">
                            <span>Masuk ke Sistem</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Register Link -->
                    @if (Route::has('register'))
                        <div class="text-center pt-4 border-t border-slate-100 mt-5">
                            <p class="text-xs text-slate-500">
                                Belum memiliki akun? 
                                <a href="{{ route('register') }}" class="font-bold text-emerald-700 hover:text-emerald-800 hover:underline">
                                    Daftar Akun Baru
                                </a>
                            </p>
                        </div>
                    @endif
                </form>

            </div>
        </div>

    </div>

</body>
</html>