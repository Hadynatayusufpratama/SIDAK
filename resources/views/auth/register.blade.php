<x-guest-layout :title="'Daftar Akun - SIDAK BKSDA Sulawesi Tengah'">

    <span class="auth-badge"><span class="dot"></span> BUAT AKUN BARU</span>
    <h1>Daftar ke <em>SIDAK</em></h1>
    <p class="sub">Lengkapi data berikut untuk membuat akun petugas baru.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="field">
            <label for="name">Nama lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   placeholder="Nama lengkap petugas" required autofocus autocomplete="name">
            @error('name')
                <div class="err">{{ $message }}</div>
            @enderror
        </div>

        <div class="field">
            <label for="email">Alamat email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   placeholder="nama@bksda.go.id" required autocomplete="username">
            @error('email')
                <div class="err">{{ $message }}</div>
            @enderror
        </div>

        <div class="field">
            <label for="password">Kata sandi</label>
            <input id="password" type="password" name="password"
                   placeholder="Minimal 8 karakter" required autocomplete="new-password">
            @error('password')
                <div class="err">{{ $message }}</div>
            @enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Konfirmasi kata sandi</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   placeholder="Ulangi kata sandi di atas" required autocomplete="new-password">
            @error('password_confirmation')
                <div class="err">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-submit" style="margin-top:4px;">
            Daftar akun sekarang
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
    </form>

    <div class="divider">ATAU</div>
    <p class="switch-link">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>

</x-guest-layout>