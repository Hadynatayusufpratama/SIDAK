<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $title ?? 'SIDAK — BKSDA Sulawesi Tengah' }}</title>
<style>
  :root{
    --forest-900:#0b2419;
    --forest-800:#0f3122;
    --forest-700:#154029;
    --forest-600:#1c5334;
    --leaf-400:#3f9c63;
    --leaf-300:#6cbd85;
    --ink-900:#10201a;
    --ink-600:#4a5c52;
    --ink-400:#7c8c82;
    --line:#e6ece7;
  }
  *{ box-sizing:border-box; }
  html,body{ height:100%; }
  body{
    margin:0;
    font-family:'Instrument Sans','Segoe UI',system-ui,-apple-system,sans-serif;
    color:var(--ink-900);
    display:flex;
    align-items:center;
    justify-content:center;
    padding:24px;
    position:relative;
    background:
      linear-gradient(180deg, rgba(7,22,15,0.55) 0%, rgba(7,22,15,0.72) 100%),
      url('{{ asset('images/wera-login.jpg') }}') center/cover no-repeat fixed;
  }

  /* small floating popup card */
  .auth-popup{
    position:relative;
    width:100%;
    max-width:380px;
    background:#fff;
    border-radius:20px;
    padding:30px 28px 26px;
    box-shadow:0 40px 80px -30px rgba(0,0,0,0.55);
    animation:pop .28s ease;
  }
  @keyframes pop{ from{ opacity:0; transform:translateY(10px) scale(0.98);} to{ opacity:1; transform:translateY(0) scale(1);} }

  .popup-close{
    position:absolute; top:14px; right:14px; width:30px; height:30px; border-radius:50%;
    display:flex; align-items:center; justify-content:center; color:var(--ink-400);
    text-decoration:none; transition:background .15s ease, color .15s ease;
  }
  .popup-close:hover{ background:#f3f5f3; color:var(--ink-900); }

  .auth-brand-row{ display:flex; align-items:center; gap:10px; margin-bottom:20px; }
  .auth-brand-row img{ width:36px; height:36px; border-radius:50%; object-fit:contain; background:#fff; border:1px solid var(--line); flex-shrink:0; }
  .auth-brand-row strong{ display:block; font-size:14.5px; font-weight:700; line-height:1.2; }
  .auth-brand-row span{ font-size:11.5px; color:var(--ink-600); }

  .auth-badge{
    display:inline-flex; align-items:center; gap:7px; font-size:11px; font-weight:700; letter-spacing:.03em;
    color:var(--forest-600); background:rgba(28,83,52,0.08); border:1px solid rgba(28,83,52,0.15);
    padding:5px 12px; border-radius:999px; margin-bottom:14px;
  }
  .auth-badge .dot{ width:6px; height:6px; border-radius:50%; background:var(--leaf-400); }
  .auth-popup h1{ font-size:20px; margin:0 0 5px; letter-spacing:-0.01em; }
  .auth-popup h1 em{ font-style:normal; color:var(--leaf-400); }
  .auth-popup p.sub{ font-size:12.5px; color:var(--ink-600); margin:0 0 20px; line-height:1.5; }

  .status-msg{
    background:rgba(63,156,99,0.1); border:1px solid rgba(63,156,99,0.3); color:var(--forest-700);
    font-size:12.5px; padding:9px 12px; border-radius:9px; margin-bottom:16px;
  }
  .field{ margin-bottom:14px; }
  .field label{ display:block; font-size:11.5px; font-weight:600; color:var(--ink-900); margin-bottom:6px; letter-spacing:.01em; }
  .field input{
    width:100%; padding:10px 12px; font-size:13.5px; border-radius:9px; border:1px solid var(--line);
    background:#fbfaf5; color:var(--ink-900); font-family:inherit; transition:border-color .15s ease, box-shadow .15s ease;
  }
  .field input:focus{ outline:none; border-color:var(--leaf-400); box-shadow:0 0 0 3px rgba(63,156,99,0.15); background:#fff; }
  .field .err{ color:#c0392b; font-size:11.5px; margin-top:5px; }
  .row-between{ display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; font-size:12px; }
  .remember{ display:flex; align-items:center; gap:7px; color:var(--ink-600); }
  .remember input{ width:14px; height:14px; accent-color:var(--forest-600); }
  .row-between a{ color:var(--forest-600); font-weight:600; text-decoration:none; }
  .row-between a:hover{ text-decoration:underline; }
  .btn-submit{
    width:100%; padding:11px; border:none; border-radius:10px; background:var(--forest-700); color:#fff;
    font-size:13.5px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:7px;
    transition:filter .15s ease, transform .15s ease; font-family:inherit;
  }
  .btn-submit:hover{ filter:brightness(1.1); }
  .btn-submit:active{ transform:scale(0.98); }
  .divider{ display:flex; align-items:center; gap:10px; margin:16px 0; color:var(--ink-400); font-size:10.5px; letter-spacing:.05em; }
  .divider::before, .divider::after{ content:""; flex:1; height:1px; background:var(--line); }
  .switch-link{ text-align:center; font-size:12.5px; color:var(--ink-600); margin:0; }
  .switch-link a{ color:var(--forest-600); font-weight:700; text-decoration:none; }
  .switch-link a:hover{ text-decoration:underline; }
</style>
</head>
<body>

  <div class="auth-popup">
    <a href="{{ route('landing') }}" class="popup-close" aria-label="Tutup dan kembali ke beranda">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
    </a>

    <div class="auth-brand-row">
      <img src="{{ asset('images/logo-icon.png') }}" alt="Logo Balai KSDA Sulawesi Tengah">
      <div>
        <strong>SIDAK</strong>
        <span>BKSDA Sulawesi Tengah</span>
      </div>
    </div>

    {{ $slot }}
  </div>

</body>
</html>