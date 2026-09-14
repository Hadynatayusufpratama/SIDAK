<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>SIDAK — Sistem Informasi dan Data Konservasi | BKSDA Sulawesi Tengah</title>
<style>
  :root{
    --forest-950:#07160f;
    --forest-900:#0b2419;
    --forest-800:#0f3122;
    --forest-700:#154029;
    --forest-600:#1c5334;
    --forest-500:#256b41;
    --leaf-400:#3f9c63;
    --leaf-300:#6cbd85;
    --gold-400:#4ade80;
    --gold-300:#7fe3a4;
    --brand-gold:#e8b84b;
    --cream:#f6f4ec;
    --paper:#fbfaf5;
    --ink-900:#10201a;
    --ink-600:#4a5c52;
    --ink-400:#7c8c82;
    --line: rgba(16,32,26,0.1);
    --line-dark: rgba(246,244,236,0.14);
    --shadow: 0 20px 50px -25px rgba(7,22,15,0.45);
    --radius-lg: 22px;
    --radius-md: 14px;
  }
  *{ box-sizing:border-box; }
  html{ scroll-behavior:smooth; }
  body{
    margin:0;
    font-family:'Instrument Sans','Segoe UI',system-ui,-apple-system,sans-serif;
    color:var(--ink-900);
    background:var(--paper);
    -webkit-font-smoothing:antialiased;
  }
  img{ max-width:100%; display:block; }
  a{ color:inherit; text-decoration:none; }
  .wrap{ max-width:1180px; margin:0 auto; padding:0 32px; }
  h1,h2,h3{ margin:0; font-weight:600; letter-spacing:-0.01em; }
  p{ margin:0; }
  .eyebrow{
    display:inline-flex; align-items:center; gap:8px;
    font-size:12.5px; letter-spacing:.02em; font-weight:600;
    color:var(--leaf-300);
    padding:7px 16px; border-radius:999px;
    border:1px solid var(--line-dark);
    background:rgba(63,156,99,0.08);
  }
  .eyebrow.on-light{ color:var(--forest-600); border-color:rgba(28,83,52,0.18); background:rgba(28,83,52,0.06); }
  .eyebrow .dot{ width:6px; height:6px; border-radius:50%; background:var(--gold-400); }

  /* ---------- NAV ---------- */
  header{
    position:sticky; top:0; z-index:50;
    background:rgba(7,22,15,0.86);
    backdrop-filter:blur(10px);
    border-bottom:1px solid var(--line-dark);
  }
  nav{ display:flex; align-items:center; justify-content:space-between; padding:16px 32px; max-width:1180px; margin:0 auto; }
  .brand{ display:flex; align-items:center; gap:12px; color:var(--cream); }
  .brand-mark{
    width:42px; height:42px; border-radius:50%;
    background:var(--paper);
    display:flex; align-items:center; justify-content:center;
    overflow:hidden; flex-shrink:0;
    border:1px solid rgba(255,255,255,0.14);
  }
  .brand-mark img{ width:100%; height:100%; object-fit:contain; padding:3px; box-sizing:border-box; }
  .brand-name{ font-size:16px; font-weight:700; line-height:1.1; }
  .brand-sub{ font-size:11px; color:var(--leaf-300); font-weight:500; }
  .nav-links{ display:flex; gap:32px; font-size:14px; color:rgba(246,244,236,0.75); }
  .nav-links a{ transition:color .15s ease; }
  .nav-links a:hover{ color:var(--cream); }
  .nav-actions{ display:flex; align-items:center; gap:10px; }
  .btn{
    display:inline-flex; align-items:center; gap:8px; justify-content:center;
    font-size:14px; font-weight:600; padding:11px 20px; border-radius:11px;
    cursor:pointer; border:1px solid transparent; transition:transform .15s ease, filter .15s ease, background .15s ease;
    white-space:nowrap;
  }
  .btn:active{ transform:scale(0.97); }
  .btn-ghost-dark{ color:var(--cream); border-color:var(--line-dark); background:transparent; }
  .btn-ghost-dark:hover{ background:rgba(255,255,255,0.06); }
  .btn-solid{ background:var(--leaf-400); color:#07160f; }
  .btn-solid:hover{ filter:brightness(1.08); }
  .btn-outline-light{ border-color:rgba(16,32,26,0.18); color:var(--forest-700); background:transparent; }
  .btn-outline-light:hover{ background:rgba(16,32,26,0.05); }
  .menu-toggle{ display:none; background:none; border:none; color:var(--cream); font-size:22px; cursor:pointer; }

  /* ---------- HERO ---------- */
  .hero{
    position:relative;
    background:
      radial-gradient(1100px 520px at 82% -10%, rgba(63,156,99,0.28), transparent 60%),
      linear-gradient(160deg, rgba(11,36,25,0.5) 0%, rgba(7,22,15,0.5) 70%),
      url('{{ asset('images/wera-login.jpg') }}') center/cover no-repeat;
    color:var(--cream);
    overflow:hidden;
    padding:88px 0 0;
  }
  .hero::after{
    content:"";
    position:absolute; inset:auto 0 0 0; height:120px;
    background:linear-gradient(to bottom, transparent, var(--paper));
    pointer-events:none;
  }
  .hero-grid{
    display:grid; grid-template-columns:1.05fr 0.95fr; gap:56px; align-items:center;
    padding-bottom:70px; position:relative; z-index:2;
  }
  .instansi-badge{
    display:inline-flex; align-items:center; gap:12px;
    background:rgba(255,255,255,0.06); border:1px solid var(--line-dark);
    border-radius:14px; padding:10px 18px 10px 10px;
  }
  .instansi-badge img{ width:44px; height:44px; border-radius:50%; object-fit:contain; padding:3px; box-sizing:border-box; background:var(--paper); flex-shrink:0; }
  .instansi-text{ display:flex; flex-direction:column; line-height:1.35; }
  .instansi-text span{ font-size:10.5px; font-weight:600; letter-spacing:.03em; color:rgba(246,244,236,0.6); }
  .instansi-text strong{ font-size:12.5px; font-weight:700; color:var(--cream); }
  .instansi-text em{ font-style:normal; font-size:12.5px; font-weight:700; letter-spacing:.02em; color:var(--brand-gold); }
  .hero h1{
    font-size:47px; line-height:1.08; margin:22px 0 20px; letter-spacing:-0.02em;
  }
  .hero h1 em{ font-style:normal; color:var(--gold-300); }
  .hero p.lead{
    font-size:16.5px; line-height:1.7; color:rgba(246,244,236,0.72); max-width:480px; margin-bottom:32px;
  }
  .hero-cta{ display:flex; gap:12px; flex-wrap:wrap; margin-bottom:36px; }
  .hero-meta{ display:flex; gap:28px; flex-wrap:wrap; }
  .hero-meta div{ display:flex; flex-direction:column; gap:2px; }
  .hero-meta .num{ font-size:20px; font-weight:700; color:var(--cream); }
  .hero-meta .lbl{ font-size:12.5px; color:rgba(246,244,236,0.55); }

  /* hero visual panel */
  .panel{
    background:linear-gradient(165deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02));
    border:1px solid var(--line-dark);
    border-radius:var(--radius-lg);
    padding:22px;
    position:relative;
    box-shadow:var(--shadow);
  }
  .panel-top{ display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
  .panel-tag{
    display:flex; align-items:center; gap:8px; font-size:13px; color:var(--leaf-300); font-weight:600;
  }
  .pulse{ width:7px; height:7px; border-radius:50%; background:var(--leaf-300); animation:pulse 1.8s infinite; }
  @keyframes pulse{ 0%,100%{opacity:1;} 50%{opacity:.25;} }
  .panel-dots span{ display:inline-block; width:5px; height:5px; border-radius:50%; background:rgba(246,244,236,0.35); margin-left:3px; }
  .panel-card{
    background:rgba(7,22,15,0.55); border:1px solid var(--line-dark); border-radius:var(--radius-md);
    padding:14px 16px; margin-bottom:14px;
  }
  .panel-row{ display:flex; align-items:center; gap:12px; margin-bottom:12px; }
  .file-ico{
    width:34px; height:34px; border-radius:9px; background:var(--forest-700); display:flex; align-items:center; justify-content:center; flex-shrink:0;
  }
  .panel-row .meta p{ font-size:13.5px; font-weight:600; color:var(--cream); }
  .panel-row .meta span{ font-size:11.5px; color:rgba(246,244,236,0.5); }
  .bar-line{ margin-bottom:11px; }
  .bar-line .bar-label{ display:flex; justify-content:space-between; font-size:12px; color:rgba(246,244,236,0.65); margin-bottom:6px; }
  .bar-track{ height:5px; border-radius:99px; background:rgba(255,255,255,0.08); overflow:hidden; }
  .bar-fill{ height:100%; border-radius:99px; background:linear-gradient(90deg,var(--leaf-400),var(--gold-400)); width:0%; transition:width 1.1s ease; }
  .panel-stats{ display:grid; grid-template-columns:repeat(3,1fr); gap:8px; }
  .panel-stats div{ background:rgba(255,255,255,0.04); border-radius:10px; padding:10px 8px; text-align:center; }
  .panel-stats .n{ font-size:16px; font-weight:700; color:var(--cream); }
  .panel-stats .l{ font-size:10.5px; color:rgba(246,244,236,0.5); }
  .float-chip{
    position:absolute; background:var(--paper); color:var(--forest-800); font-size:12px; font-weight:600;
    padding:8px 13px; border-radius:11px; box-shadow:0 14px 30px -10px rgba(0,0,0,0.35);
    display:flex; align-items:center; gap:6px;
  }
  .float-chip.a{ top:-14px; right:18px; }
  .float-chip.b{ bottom:-14px; left:8px; background:var(--forest-700); color:var(--cream); }
  .float-chip svg{ width:13px; height:13px; }

  /* marquee */
  .marquee-wrap{
    background:var(--forest-950); border-top:1px solid var(--line-dark); border-bottom:1px solid var(--line-dark);
    overflow:hidden; padding:14px 0; position:relative; z-index:2;
  }
  .marquee-track{ display:flex; width:max-content; gap:14px; animation:scroll 32s linear infinite; }
  @keyframes scroll{ from{ transform:translateX(0);} to{ transform:translateX(-50%);} }
  .chip{
    display:flex; align-items:center; gap:8px; font-size:13px; color:rgba(246,244,236,0.75);
    background:rgba(255,255,255,0.05); border:1px solid var(--line-dark); padding:8px 16px; border-radius:999px; white-space:nowrap;
  }

  /* ---------- SECTION GENERIC ---------- */
  section{ padding:88px 0; }
  .section-head{ text-align:center; max-width:640px; margin:0 auto 52px; }
  .section-head h2{ font-size:32px; margin:16px 0 12px; letter-spacing:-0.015em; color:var(--ink-900); }
  .section-head p{ font-size:15.5px; color:var(--ink-600); line-height:1.7; }

  /* features */
  .feat-grid{ display:grid; grid-template-columns:repeat(4,1fr); gap:18px; }
  .feat-card{
    background:var(--paper); border:1px solid var(--line); border-radius:var(--radius-md); padding:26px 22px;
    transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
  }
  .feat-card:hover{ transform:translateY(-4px); box-shadow:0 24px 40px -28px rgba(16,32,26,0.35); border-color:rgba(28,83,52,0.25); }
  .feat-ico{
    width:42px; height:42px; border-radius:11px; display:flex; align-items:center; justify-content:center; margin-bottom:16px;
    background:rgba(28,83,52,0.08); color:var(--forest-600);
  }
  .feat-card h3{ font-size:16px; margin-bottom:8px; }
  .feat-card p{ font-size:13.5px; color:var(--ink-600); line-height:1.65; }

  /* how it works */
  .steps-band{ background:var(--forest-900); color:var(--cream); }
  .steps-band .section-head h2{ color:var(--cream); }
  .steps-band .section-head p{ color:rgba(246,244,236,0.65); }
  .steps-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:20px; position:relative; }
  .steps-grid::before{
    content:""; position:absolute; top:27px; left:16%; right:16%; height:1px;
    background:repeating-linear-gradient(90deg, var(--line-dark) 0 8px, transparent 8px 16px);
    z-index:0;
  }
  .step{ position:relative; z-index:1; text-align:left; }
  .step-num{
    width:54px; height:54px; border-radius:14px; background:var(--forest-700); border:1px solid var(--line-dark);
    display:flex; align-items:center; justify-content:center; font-weight:700; font-size:18px; margin-bottom:22px; color:var(--leaf-300);
  }
  .step h3{ font-size:17px; margin-bottom:8px; color:var(--cream); }
  .step p{ font-size:13.5px; color:rgba(246,244,236,0.6); line-height:1.65; max-width:280px; }

  /* gallery */
  .gallery-head{ display:flex; align-items:flex-end; justify-content:space-between; gap:24px; margin-bottom:36px; flex-wrap:wrap; }
  .gallery-head h2{ font-size:30px; letter-spacing:-0.015em; }
  .gallery-head p{ font-size:14.5px; color:var(--ink-600); max-width:420px; }
  .gallery-grid{ display:grid; grid-template-columns:repeat(4,1fr); grid-auto-rows:190px; gap:14px; }
  .g-item{
    position:relative; border-radius:16px; overflow:hidden; cursor:pointer;
  }
  .g-item.wide{ grid-column:span 2; grid-row:span 2; }
  .g-item img{ width:100%; height:100%; object-fit:cover; transition:transform .5s ease; }
  .g-item:hover img{ transform:scale(1.08); }
  .g-overlay{
    position:absolute; inset:0; background:linear-gradient(to top, rgba(7,22,15,0.88) 0%, rgba(7,22,15,0.05) 55%);
    display:flex; align-items:flex-end; padding:16px;
  }
  .g-overlay .tag{ font-size:10.5px; font-weight:700; letter-spacing:.04em; color:var(--gold-300); margin-bottom:4px; display:block; }
  .g-overlay h4{ color:var(--cream); font-size:15px; font-weight:600; margin:0; }
  .g-overlay p{ color:rgba(246,244,236,0.65); font-size:12px; margin-top:3px; }

  /* stats band */
  .stats-band{
    background:linear-gradient(160deg,var(--forest-800),var(--forest-950));
    color:var(--cream); border-radius:var(--radius-lg); padding:56px 46px;
    display:grid; grid-template-columns:repeat(3,1fr); gap:24px; position:relative; overflow:hidden;
  }
  .stats-band::before{
    content:""; position:absolute; inset:0;
    background:radial-gradient(600px 260px at 90% 0%, rgba(63,156,99,0.25), transparent 60%);
  }
  .stat{ position:relative; z-index:1; text-align:center; }
  .stat .n{ font-size:34px; font-weight:700; letter-spacing:-0.02em; color:var(--gold-300); }
  .stat .l{ font-size:12.5px; color:rgba(246,244,236,0.65); margin-top:6px; }

  /* faq */
  .faq-list{ max-width:760px; margin:0 auto; display:flex; flex-direction:column; gap:10px; }
  .faq-item{ border:1px solid var(--line); border-radius:var(--radius-md); background:var(--paper); overflow:hidden; }
  .faq-q{
    display:flex; align-items:center; justify-content:space-between; width:100%; background:none; border:none;
    padding:19px 22px; font-size:15px; font-weight:600; color:var(--ink-900); cursor:pointer; text-align:left; font-family:inherit;
  }
  .faq-q .chev{ transition:transform .2s ease; flex-shrink:0; color:var(--forest-600); }
  .faq-item.open .chev{ transform:rotate(180deg); }
  .faq-a{ max-height:0; overflow:hidden; transition:max-height .25s ease; }
  .faq-a-inner{ padding:0 22px 20px; font-size:13.5px; color:var(--ink-600); line-height:1.7; }

  /* CTA band */
  .cta-band{
    background:linear-gradient(160deg,var(--forest-900),var(--forest-950));
    color:var(--cream); border-radius:var(--radius-lg); padding:64px 40px; text-align:center;
    position:relative; overflow:hidden;
  }
  .cta-band::before{
    content:""; position:absolute; inset:0;
    background:radial-gradient(700px 300px at 50% 0%, rgba(63,156,99,0.3), transparent 65%);
  }
  .cta-band > *{ position:relative; z-index:1; }
  .cta-band h2{ font-size:30px; margin:16px 0 12px; letter-spacing:-0.015em; }
  .cta-band p{ color:rgba(246,244,236,0.65); font-size:15px; max-width:460px; margin:0 auto 28px; }
  .cta-btns{ display:flex; gap:12px; justify-content:center; flex-wrap:wrap; }

  /* footer */
  footer{ background:var(--forest-950); color:rgba(246,244,236,0.55); padding:56px 0 26px; }
  .foot-grid{ display:grid; grid-template-columns:1.4fr 1fr 1fr 1fr; gap:36px; padding-bottom:36px; border-bottom:1px solid var(--line-dark); }
  .foot-brand p{ font-size:13px; line-height:1.7; margin-top:14px; max-width:260px; color:rgba(246,244,236,0.5); }
  .foot-col h5{ font-size:12.5px; text-transform:uppercase; letter-spacing:.04em; color:rgba(246,244,236,0.75); margin-bottom:14px; }
  .foot-col a{ display:block; font-size:13.5px; color:rgba(246,244,236,0.55); margin-bottom:10px; }
  .foot-col a:hover{ color:var(--leaf-300); }
  .foot-bottom{ display:flex; justify-content:space-between; align-items:center; padding-top:22px; font-size:12.5px; flex-wrap:wrap; gap:10px; }

  @media (max-width: 920px){
    .nav-links, .hero-meta{ display:none; }
    .menu-toggle{ display:block; }
    .hero-grid{ grid-template-columns:1fr; }
    .hero h1{ font-size:34px; }
    .feat-grid, .steps-grid{ grid-template-columns:repeat(2,1fr); }
    .stats-band{ grid-template-columns:1fr; gap:28px; text-align:center; }
    .gallery-grid{ grid-template-columns:repeat(2,1fr); grid-auto-rows:160px; }
    .g-item.wide{ grid-column:span 2; grid-row:span 1; }
    .foot-grid{ grid-template-columns:1fr 1fr; }
  }
</style>
</head>
<body>

<header>
  <nav>
    <div class="brand">
      <div class="brand-mark">
        <img src="{{ asset('images/logo-icon.png') . '?v=2' }}" alt="Logo Balai KSDA Sulawesi Tengah">
      </div>
      <div>
        <div class="brand-name">SIDAK</div>
        <div class="brand-sub">BKSDA Sulawesi Tengah</div>
      </div>
    </div>
    <div class="nav-links">
      <a href="#fitur">Fitur</a>
      <a href="#cara-kerja">Cara kerja</a>
      <a href="#kawasan">Kawasan &amp; satwa</a>
    </div>
    <div class="nav-actions">
      @auth
        <a href="{{ route('konservasi.dashboard') }}" class="btn btn-solid">Ke dashboard</a>
      @else
        <a href="{{ route('login') }}" class="btn btn-solid">Masuk</a>
      @endauth
      <button class="menu-toggle" aria-label="Menu">☰</button>
    </div>
  </nav>
</header>

<section class="hero">
  <div class="wrap hero-grid">
    <div>
      <div class="instansi-badge">
        <img src="{{ asset('images/logo-icon.png') . '?v=2' }}" alt="Logo Balai KSDA Sulawesi Tengah">
        <div class="instansi-text">
          <span>KEMENTERIAN KEHUTANAN</span>
          <strong>BALAI KONSERVASI SUMBER DAYA ALAM</strong>
          <em>SULAWESI TENGAH</em>
        </div>
      </div>
      <span class="eyebrow" style="margin-top:18px;"><span class="dot"></span> Sistem informasi &amp; data konservasi</span>
      <h1>Satu sistem untuk<br>menjaga <em>kekayaan hayati</em><br>Sulawesi Tengah</h1>
      <p class="lead">SIDAK mencatat data kawasan konservasi, memantau satwa dan tumbuhan endemik, serta merapikan pelaporan kinerja — dari lapangan sampai ke meja kerja.</p>
      <div class="hero-cta">
        @auth
          <a href="{{ route('konservasi.dashboard') }}" class="btn btn-solid">Ke dashboard</a>
        @else
          <a href="{{ route('login') }}" class="btn btn-solid">Masuk ke sistem</a>
        @endauth
        <a href="#kawasan" class="btn btn-ghost-dark">Jelajahi kawasan &amp; satwa</a>
      </div>
      <div class="hero-meta">
        <div><span class="num">18</span><span class="lbl">Kawasan konservasi</span></div>
        <div><span class="num">120+</span><span class="lbl">Spesies terdata</span></div>
        <div><span class="num">24/7</span><span class="lbl">Akses lapangan</span></div>
      </div>
    </div>
  </div>

  <div class="marquee-wrap">
    <div class="marquee-track" id="marquee"></div>
  </div>
</section>

<section id="fitur">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow on-light">Fitur utama</span>
      <h2>Semua data konservasi, satu pintu masuk</h2>
      <p>SIDAK dirancang mengikuti alur kerja Balai KSDA — mulai dari pencatatan lapangan sampai laporan yang siap diajukan.</p>
    </div>
    <div class="feat-grid">
      <div class="feat-card">
        <div class="feat-ico"><svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 21s-7-5.3-7-11a7 7 0 0114 0c0 5.7-7 11-7 11z" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="10" r="2.4" stroke="currentColor" stroke-width="1.8"/></svg></div>
        <h3>Peta sebaran kawasan</h3>
        <p>Titik kawasan konservasi dan lokasi temuan satwa endemik ditampilkan pada peta interaktif, lengkap dengan riwayat kunjungan.</p>
      </div>
      <div class="feat-card">
        <div class="feat-ico"><svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M9 11l3 3L22 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></div>
        <h3>Pelaporan patroli lapangan</h3>
        <p>Petugas mencatat hasil patroli dan inventarisasi langsung dari lokasi, lengkap dengan foto dan koordinat.</p>
      </div>
      <div class="feat-card">
        <div class="feat-ico"><svg width="20" height="20" viewBox="0 0 24 24" fill="none"><ellipse cx="12" cy="6" rx="8" ry="3" stroke="currentColor" stroke-width="1.8"/><path d="M4 6v12c0 1.7 3.6 3 8 3s8-1.3 8-3V6" stroke="currentColor" stroke-width="1.8"/><path d="M4 12c0 1.7 3.6 3 8 3s8-1.3 8-3" stroke="currentColor" stroke-width="1.8"/></svg></div>
        <h3>Basis data hayati dan ekosistem</h3>
        <p>Arsip data flora, fauna, dan kondisi ekosistem tersimpan rapi, mudah dicari, dan siap diaudit sewaktu-waktu.</p>
      </div>
      <div class="feat-card">
        <div class="feat-ico"><svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M4 20V10M12 20V4M20 20v-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></div>
        <h3>Dashboard kinerja</h3>
        <p>Ringkasan capaian kinerja balai — jumlah patroli, temuan, dan status kawasan — terlihat dalam satu tampilan.</p>
      </div>
    </div>
  </div>
</section>

<section class="steps-band" id="cara-kerja">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">Alur kerja</span>
      <h2>Dari lapangan ke laporan resmi</h2>
      <p>Tidak perlu berpindah aplikasi — satu alur yang sama untuk petugas lapangan dan staf administrasi.</p>
    </div>
    <div class="steps-grid">
      <div class="step">
        <div class="step-num">01</div>
        <h3>Catat di lapangan</h3>
        <p>Petugas mengisi data patroli, titik kawasan, dan temuan satwa langsung dari perangkat di lokasi, meski jaringan terbatas.</p>
      </div>
      <div class="step">
        <div class="step-num">02</div>
        <h3>Sistem menyusun data</h3>
        <p>SIDAK mencocokkan lokasi, mengelompokkan spesies, dan menyusun data ke dalam basis data konservasi yang terstruktur.</p>
      </div>
      <div class="step">
        <div class="step-num">03</div>
        <h3>Laporan siap diajukan</h3>
        <p>Staf kantor tinggal meninjau dan mengekspor laporan capaian kinerja untuk kebutuhan administrasi bulanan.</p>
      </div>
    </div>
  </div>
</section>

<section id="kawasan">
  <div class="wrap">
    <div class="gallery-head">
      <div>
        <span class="eyebrow on-light">Kawasan &amp; satwa endemik</span>
        <h2 style="margin-top:14px;">Kekayaan hayati yang kami jaga</h2>
      </div>
      <p>Sebagian kawasan dan satwa endemik Sulawesi yang datanya tercatat dan dipantau melalui SIDAK.</p>
    </div>
    <div class="gallery-grid">
      <div class="g-item wide">
        <img src="{{ asset('images/wera.jpg') }}" alt="Air terjun di Taman Wisata Alam Wera, Sulawesi Tengah">
        <div class="g-overlay">
          <div>
            <span class="tag">KAWASAN KONSERVASI</span>
            <h4>Taman Wisata Alam Wera</h4>
            <p>Desa Balumpewa, Kabupaten Sigi</p>
          </div>
        </div>
      </div>
      <div class="g-item">
        <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Lowland%20anoa.jpg" alt="Anoa, hewan endemik Sulawesi">
        <div class="g-overlay">
          <div>
            <span class="tag">SATWA ENDEMIK</span>
            <h4>Anoa</h4>
            <p>Bubalus depressicornis</p>
          </div>
        </div>
      </div>
      <div class="g-item">
        <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Spectral%20Tarsier%20Tarsius%20tarsier%20(7911549768).jpg" alt="Tarsius, hewan endemik Sulawesi">
        <div class="g-overlay">
          <div>
            <span class="tag">SATWA ENDEMIK</span>
            <h4>Tarsius</h4>
            <p>Tarsius tarsier</p>
          </div>
        </div>
      </div>
      <div class="g-item">
        <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Macrocephalon%20maleo%20-%20Muara%20Pusian%20(2).JPG" alt="Burung maleo, hewan endemik Sulawesi">
        <div class="g-overlay">
          <div>
            <span class="tag">SATWA ENDEMIK</span>
            <h4>Maleo</h4>
            <p>Macrocephalon maleo</p>
          </div>
        </div>
      </div>
      <div class="g-item">
        <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Babirusa%20berkelahi%20-%20edited.jpg" alt="Babirusa, hewan endemik Sulawesi">
        <div class="g-overlay">
          <div>
            <span class="tag">SATWA ENDEMIK</span>
            <h4>Babirusa</h4>
            <p>Babyrousa celebensis</p>
          </div>
        </div>
      </div>
    </div>
    <p style="font-size:11.5px; color:var(--ink-400); margin-top:14px;">Foto TWA Wera: dokumentasi Balai KSDA Sulawesi Tengah. Foto satwa endemik: Wikimedia Commons (CC BY-SA)</p>
  </div>
</section>

<section>
  <div class="wrap">
    <div class="stats-band">
      <div class="stat"><div class="n" data-count="18">0</div><div class="l">Kawasan konservasi terpantau</div></div>
      <div class="stat"><div class="n" data-count="120">0</div><div class="l">Spesies flora &amp; fauna terdata</div></div>
      <div class="stat"><div class="n">24/7</div><div class="l">Akses lapangan</div></div>
    </div>
  </div>
</section>

<section>
  <div class="wrap">
    <div class="cta-band">
      <span class="eyebrow"><span class="dot"></span> Balai KSDA Sulawesi Tengah</span>
      <h2>Siap mengelola data konservasi dengan lebih rapi?</h2>
      <p>Masuk ke SIDAK untuk mulai mencatat, memantau, dan melaporkan data kawasan serta satwa endemik Sulawesi Tengah.</p>
      <div class="cta-btns">
        @auth
          <a href="{{ route('konservasi.dashboard') }}" class="btn btn-solid">Ke dashboard</a>
        @else
          <a href="{{ route('login') }}" class="btn btn-solid">Masuk ke sistem</a>
        @endauth
        <a href="#fitur" class="btn btn-ghost-dark">Pelajari fitur</a>
      </div>
    </div>
  </div>
</section>

<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div class="foot-brand">
        <div class="brand">
          <div class="brand-mark"><img src="{{ asset('images/logo-icon.png') . '?v=2' }}" alt="Logo Balai KSDA Sulawesi Tengah"></div>
          <div><div class="brand-name" style="color:var(--cream);">SIDAK</div></div>
        </div>
        <p>Sistem Informasi dan Data Konservasi Sumber Daya Alam Hayati dan Ekosistemnya — Balai KSDA Sulawesi Tengah.</p>
      </div>
      <div class="foot-col">
        <h5>Navigasi</h5>
        <a href="#fitur">Fitur</a>
        <a href="#cara-kerja">Cara kerja</a>
        <a href="#kawasan">Kawasan &amp; satwa</a>
      </div>
      <div class="foot-col">
        <h5>Instansi</h5>
        <a href="#">Kementerian Kehutanan</a>
        <a href="#">Balai KSDA Sulawesi Tengah</a>
        <a href="#">Hubungi kami</a>
      </div>
      <div class="foot-col">
        <h5>Bantuan</h5>
        <a href="#">Panduan pengguna</a>
      </div>
    </div>
    <div class="foot-bottom">
      <span>© 2026 Balai KSDA Sulawesi Tengah. Seluruh hak dilindungi.</span>
      <span>Menjaga kekayaan hayati dan ekosistem Sulawesi Tengah</span>
    </div>
  </div>
</footer>

<script>
  // marquee content
  const chips = [
    "Peta interaktif","Basis data hayati","Laporan real-time","Pelacakan satwa",
    "Kawasan konservasi","Inventarisasi ekosistem","Kamera jebak","Patroli lapangan"
  ];
  const track = document.getElementById('marquee');
  const html = chips.map(c => `<span class="chip">${c}</span>`).join('');
  track.innerHTML = html + html;

  // animate hero bars on load
  window.addEventListener('load', () => {
    document.querySelectorAll('.bar-fill').forEach(el => {
      const w = el.getAttribute('data-w');
      requestAnimationFrame(() => { el.style.width = w + '%'; });
    });
  });

  // faq accordion
  document.querySelectorAll('.faq-item').forEach(item => {
    const btn = item.querySelector('.faq-q');
    const answer = item.querySelector('.faq-a');
    btn.addEventListener('click', () => {
      const isOpen = item.classList.contains('open');
      document.querySelectorAll('.faq-item.open').forEach(o => {
        o.classList.remove('open');
        o.querySelector('.faq-a').style.maxHeight = null;
      });
      if(!isOpen){
        item.classList.add('open');
        answer.style.maxHeight = answer.scrollHeight + 'px';
      }
    });
  });

  // count-up stats on scroll into view
  const counters = document.querySelectorAll('.stat .n[data-count]');
  const io = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if(entry.isIntersecting){
        const el = entry.target;
        const target = parseInt(el.getAttribute('data-count'), 10);
        let cur = 0;
        const step = Math.max(1, Math.round(target / 40));
        const tick = () => {
          cur += step;
          if(cur >= target){ el.textContent = target; return; }
          el.textContent = cur;
          requestAnimationFrame(tick);
        };
        tick();
        io.unobserve(el);
      }
    });
  }, { threshold: 0.4 });
  counters.forEach(c => io.observe(c));
</script>
</body>
</html>