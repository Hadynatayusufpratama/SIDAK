<x-guest-layout :title="'Verifikasi Email - SIDAK BKSDA Sulawesi Tengah'">

    <span class="auth-badge"><span class="dot"></span> SATU LANGKAH LAGI</span>
    <h1>Verifikasi <em>email</em></h1>
    <p class="sub">Terima kasih sudah mendaftar. Sebelum mulai, mohon verifikasi email kamu lewat tautan yang sudah kami kirim.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="status-msg">Tautan verifikasi baru sudah dikirim ke email kamu.</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn-submit">
            Kirim ulang email verifikasi
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" style="margin-top:10px;">
        @csrf
        <button type="submit" class="btn-submit" style="background:transparent; color:#4a5c52; border:1px solid #e6ece7;">
            Keluar
        </button>
    </form>

</x-guest-layout>