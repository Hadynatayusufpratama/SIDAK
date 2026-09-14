<x-guest-layout :title="'Konfirmasi Password - SIDAK BKSDA Sulawesi Tengah'">

    <span class="auth-badge"><span class="dot"></span> AREA AMAN</span>
    <h1>Konfirmasi <em>password</em></h1>
    <p class="sub">Ini adalah area sensitif, mohon masukkan password kamu lagi untuk melanjutkan.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="field">
            <label for="password">Password</label>
            <input id="password" type="password" name="password"
                   placeholder="Masukkan password" required autofocus autocomplete="current-password">
            @error('password')
                <div class="err">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-submit">
            Konfirmasi
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
    </form>

</x-guest-layout>