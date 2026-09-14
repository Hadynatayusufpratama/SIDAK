<x-guest-layout :title="'Masuk - SIDAK BKSDA Sulawesi Tengah'">

    <span class="auth-badge"><span class="dot"></span> SELAMAT DATANG KEMBALI</span>
    <h1>Masuk ke <em>SIDAK</em></h1>
    <p class="sub">Silakan masukkan akun kamu untuk mengakses sistem.</p>

    @if (session('status'))
        <div class="status-msg">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="field">
            <label for="email">Alamat email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   placeholder="nama@bksda.go.id" required autofocus autocomplete="username">
            @error('email')
                <div class="err">{{ $message }}</div>
            @enderror
        </div>

        <div class="field">
            <label for="password">Kata sandi</label>
            <input id="password" type="password" name="password"
                   placeholder="Masukkan kata sandi" required autocomplete="current-password">
            @error('password')
                <div class="err">{{ $message }}</div>
            @enderror
        </div>

        <div class="row-between">
            <label class="remember">
                <input type="checkbox" name="remember">
                Ingat saya
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Lupa password?</a>
            @endif
        </div>

        <button type="submit" class="btn-submit">
            Masuk ke sistem
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
    </form>

    @if (Route::has('register'))
        <div class="divider">ATAU</div>
        <p class="switch-link">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
    @endif

</x-guest-layout>