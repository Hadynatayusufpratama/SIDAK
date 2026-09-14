<x-guest-layout :title="'Reset Password - SIDAK BKSDA Sulawesi Tengah'">

    <span class="auth-badge"><span class="dot"></span> BUAT PASSWORD BARU</span>
    <h1>Reset <em>password</em></h1>
    <p class="sub">Masukkan password baru untuk akun kamu.</p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="field">
            <label for="email">Alamat email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                   placeholder="nama@bksda.go.id" required autofocus autocomplete="username">
            @error('email')
                <div class="err">{{ $message }}</div>
            @enderror
        </div>

        <div class="field">
            <label for="password">Password baru</label>
            <input id="password" type="password" name="password"
                   placeholder="Minimal 8 karakter" required autocomplete="new-password">
            @error('password')
                <div class="err">{{ $message }}</div>
            @enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Konfirmasi password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   placeholder="Ulangi password di atas" required autocomplete="new-password">
            @error('password_confirmation')
                <div class="err">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-submit">
            Simpan password baru
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
    </form>

</x-guest-layout>