<x-guest-layout :title="'Lupa Password - SIDAK BKSDA Sulawesi Tengah'">

    <span class="auth-badge"><span class="dot"></span> RESET PASSWORD</span>
    <h1>Lupa <em>password?</em></h1>
    <p class="sub">Masukkan email akun kamu, kami akan kirim tautan untuk membuat password baru.</p>

    @if (session('status'))
        <div class="status-msg">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="field">
            <label for="email">Alamat email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   placeholder="nama@bksda.go.id" required autofocus>
            @error('email')
                <div class="err">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-submit">
            Kirim tautan reset
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
    </form>

    <div class="divider">ATAU</div>
    <p class="switch-link">Sudah ingat password? <a href="{{ route('login') }}">Masuk di sini</a></p>

</x-guest-layout>