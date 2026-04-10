<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    <title>SIJAR — Sistem Inventaris Peminjaman Barang Jurusan</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* ─── Design Tokens ─────────────────────────────────── */
        :root {
            --blue-primary:   #3B9EE8;   /* bright soft blue – main brand color   */
            --blue-light:     #74BFEF;   /* lighter blue – accents / badges        */
            --blue-lighter:   #C3E2FA;   /* very light blue – backgrounds / chips  */
            --blue-dark:      #1A6FAF;   /* deep blue – hover, headings            */
            --blue-darker:    #0E4A7A;   /* darkest blue – footer / text           */

            --sky:            #E8F5FF;   /* near-white sky – page background       */
            --white:          #FFFFFF;
            --text-main:      #1E3A5F;   /* navy text                              */
            --text-muted:     #5A85AD;   /* muted body copy                        */

            --green-soft:     #4ECBA0;   /* success / tujuan accent                */
            --yellow-soft:    #FFD166;   /* highlight accent                       */
            --coral-soft:     #FF8C6B;   /* warning / hot accent                   */

            --radius-card:    1.25rem;
            --shadow-card:    0 4px 24px rgba(59,158,232,.12);
            --shadow-hover:   0 8px 36px rgba(59,158,232,.22);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--sky);
            color: var(--text-main);
        }

        h1, h2, h3, h4, h5 { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ─── Navbar ─────────────────────────────────────────── */
        .navbar {
            background: rgba(255,255,255,0.82);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(59,158,232,0.12);
        }

        .nav-link {
            position: relative;
            color: var(--text-muted);
            font-weight: 500;
            transition: color .25s;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px; left: 0; right: 0;
            height: 2px;
            background: var(--blue-primary);
            border-radius: 2px;
            transform: scaleX(0);
            transition: transform .25s;
        }
        .nav-link:hover { color: var(--blue-primary); }
        .nav-link:hover::after { transform: scaleX(1); }

        /* ─── Hero dashed ring background ─────────────────────── */
        .hero-bg {
            position: relative;
            overflow: hidden;
        }
        .ring {
            position: absolute;
            border-radius: 50%;
            border: 2px dashed;
            opacity: .18;
            pointer-events: none;
        }
        .ring-1 { width: 520px; height: 520px; top: -120px; left: -140px; border-color: var(--blue-primary); }
        .ring-2 { width: 380px; height: 380px; top: 40px;  left: -60px;  border-color: var(--blue-light); }
        .ring-3 { width: 700px; height: 700px; top: -200px; right:-220px; border-color: var(--blue-primary); }
        .ring-4 { width: 480px; height: 480px; top:  0px;   right:-80px;  border-color: var(--blue-light);  animation: spin-slow 40s linear infinite; }
        @keyframes spin-slow { to { transform: rotate(360deg); } }

        /* ─── Cards ──────────────────────────────────────────── */
        .card {
            background: var(--white);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
            transition: box-shadow .3s, transform .3s;
        }
        .card:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-4px);
        }

        /* ─── Jurusan badge ──────────────────────────────────── */
        .jurusan-card {
            background: var(--white);
            border-radius: var(--radius-card);
            border: 1.5px solid var(--blue-lighter);
            box-shadow: var(--shadow-card);
            transition: all .3s;
        }
        .jurusan-card:hover {
            background: var(--blue-primary);
            color: #fff;
            box-shadow: var(--shadow-hover);
            transform: translateY(-4px);
        }
        .jurusan-card:hover .jurusan-icon { background: rgba(255,255,255,.2); color: #fff; }
        .jurusan-card:hover p { color: rgba(255,255,255,.85); }

        /* ─── Section label chip ─────────────────────────────── */
        .section-chip {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: var(--blue-lighter);
            color: var(--blue-dark);
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: .3rem .85rem;
            border-radius: 9999px;
            margin-bottom: .85rem;
        }

        /* ─── Btn primary ────────────────────────────────────── */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: var(--sky);
            color: var(--blue-primary);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: .95rem;
            padding: .75rem 2rem;
            border-radius:12px;
            box-shadow: 0 4px 18px rgba(59,158,232,.35);
            transition: all .25s;
        }
        .btn-primary:hover {
            background: var(--blue-primary);
            color: #fff;
            text-decoration: underline;
            box-shadow: 0 6px 24px rgba(59,158,232,.45);
            transform: translateY(-2px);
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: transparent;
            color: var(--blue-primary);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: .9rem;
            padding: .6rem 1.5rem;
            border-radius: 9999px;
            border: 2px solid var(--blue-light);
            transition: all .25s;
        }
        .btn-outline:hover {
            background: var(--blue-lighter);
            border-color: var(--blue-primary);
        }

        /* ─── Divider wave ───────────────────────────────────── */
        .wave-divider { line-height: 0; }
        .wave-divider svg { display: block; width: 100%; }

        /* ─── Scroll reveal (no JS needed – CSS fade on load) ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp .7s ease both; }
        .delay-1 { animation-delay: .1s; }
        .delay-2 { animation-delay: .2s; }
        .delay-3 { animation-delay: .3s; }
        .delay-4 { animation-delay: .45s; }

        /* footer link */
        .footer-link { color: var(--blue-light); transition: color .2s; }
        .footer-link:hover { color: #fff; }

        /* Mobile nav toggle */
        #mobile-menu { transition: max-height .35s ease, opacity .35s ease; }
    </style>
</head>

<body>

<!-- ══════════════════════════════════════════
     NAVBAR
══════════════════════════════════════════ -->
<nav class="navbar fixed top-0 left-0 right-0 z-50 px-6 py-3">
    <div class="mx-auto flex items-center justify-between gap-4">

        <!-- Logo -->
        <a href="#home" class="flex items-center gap-2">
            <img src="{{ asset('/images/logo.png') }}" alt="SIJAR Logo" class="h-12 w-12 object-contain">
            <span class="text-xl font-extrabold" style="color:var(--blue-primary)">SIJAR</span>
        </a>

        <!-- Desktop Links -->
        <div class="hidden md:flex items-center gap-8">
            <a href="#home"    class="nav-link text-sm">Beranda</a>
            <a href="#tentang" class="nav-link text-sm">Tentang</a>
            <a href="#jurusan" class="nav-link text-sm">Jurusan</a>
            <a href="#barang"  class="nav-link text-sm">Barang</a>
            <a href="#kontak"  class="nav-link text-sm">Kontak</a>
        </div>

        <!-- CTA + hamburger -->
        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" class="btn-primary text-sm hidden md:inline-flex">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4m-5-4 5-5-5-5m5 5H3"/></svg>
                Login
            </a>
            <!-- Hamburger -->
            <button id="hamburger" class="md:hidden p-2 rounded-xl" style="color:var(--blue-primary)">
                
            </button>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="md:hidden max-h-0 opacity-0 overflow-hidden px-2 pb-0">
        <div class="bg-white rounded-2xl mt-2 p-4 flex flex-col gap-3 shadow-lg border border-blue-50">
                <a href="#home"    class="nav-link text-sm py-1">Beranda</a>
                <a href="#tentang" class="nav-link text-sm py-1">Tentang</a>
                <a href="#jurusan" class="nav-link text-sm py-1">Jurusan</a>
                <a href="#barang"  class="nav-link text-sm py-1">Barang</a>
                <a href="#kontak"  class="nav-link text-sm py-1">Kontak</a>
            <a href="{{ route('login') }}" class="btn-primary text-sm justify-center mt-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"></svg>
                Login
            </a>
        </div>
    </div>
</nav>


<!-- ══════════════════════════════════════════
     SECTION 1 — HERO
══════════════════════════════════════════ -->
<section id="home" class="hero-bg min-h-screen flex items-center pt-24 pb-16 px-6">
    <!-- dashed rings -->
    <div class="ring ring-1"></div>
    <div class="ring ring-2"></div>
    <div class="ring ring-3"></div>
    <div class="ring ring-4"></div>

    <div class="max-w-6xl mx-auto w-full grid md:grid-cols-2 gap-12 items-center relative z-10">

        <!-- Text side -->
        <div class="fade-up">
            <div class="section-chip">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Digitalisasi Peminjaman Barang
            </div>

            <div class="flex items-center gap-3 mb-4">
                <!-- Icon beside H1 -->
                <div class="flex-shrink-0 w-12 h-12 rounded-2xl flex items-center justify-center" style="background:var(--blue-lighter)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:var(--blue-primary)">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zm-9 5H7m4 0h6"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 3H8a1 1 0 0 0-1 1v3h10V4a1 1 0 0 0-1-1zm0 14H8v2a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-2z"/>
                    </svg>
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight" style="color:var(--blue-darker)">
                    SIJAR
                </h1>
            </div>

            <h2 class="text-xl md:text-2xl font-semibold mb-4 leading-snug" style="color:var(--blue-dark)">
                Sistem Inventaris Peminjaman<br>Barang Jurusan
            </h2>
            <p class="text-base mb-8 leading-relaxed max-w-md" style="color:var(--text-muted)">
                Minjam barang jurusan masih pakai kertas? Sudah saatnya beralih ke SIJAR — solusi digital cepat, rapi, dan transparan untuk seluruh jurusan di SMKN 8 Semarang.
            </p>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('login') }}" class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4m-5-4 5-5-5-5m5 5H3"/></svg>
                    Mulai Sekarang
                </a>
                <a href="#tentang" class="btn-outline">
                    Pelajari Lebih
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </a>
            </div>

            <!-- Stats row -->
            <div class="flex gap-8 mt-10">
                <div>
                    <p class="text-2xl font-extrabold" style="color:var(--blue-primary)">5+</p>
                    <p class="text-xs font-medium" style="color:var(--text-muted)">Jurusan</p>
                </div>
                <div class="w-px" style="background:var(--blue-lighter)"></div>
                <div>
                    <p class="text-2xl font-extrabold" style="color:var(--blue-primary)">100%</p>
                    <p class="text-xs font-medium" style="color:var(--text-muted)">Digital</p>
                </div>
                <div class="w-px" style="background:var(--blue-lighter)"></div>
                <div>
                    <p class="text-2xl font-extrabold" style="color:var(--blue-primary)">Real-time</p>
                    <p class="text-xs font-medium" style="color:var(--text-muted)">Tracking</p>
                </div>
            </div>
        </div>

        <!-- Image side -->
        <div class="fade-up delay-2 flex justify-center inset-y-1/2">
            <div class="relative">
                <!-- Decorative blob behind image -->
                <div class="absolute inset-0 -m-6 rounded-full blur-3xl opacity-30" style="background:var(--blue-light)"></div>
                <div class="rounded-full bg-white  px-4 py-4 m-2 border-dashed border-2 ring-2">
                    <img src="{{ asset('/images/hero_peminjaman.png') }}"
                     alt="SIJAR Ilustrasi"
                     class="relative z-10 w-full max-w-sm drop-shadow-xl">
                </div>
                
            </div>
        </div>
    </div>
</section>


<!-- ══════════════════════════════════════════
     SECTION 2 — TENTANG / TUJUAN & MANFAAT
══════════════════════════════════════════ -->
<section id="tentang" class="py-20 px-6" style="background:var(--white)">
    <div class="max-w-6xl mx-auto">

        <div class="text-center mb-14">
            <div class="section-chip mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4m0-4h.01"/></svg>
                Tentang SIJAR
            </div>
            <h2 class="text-3xl md:text-4xl font-extrabold mb-3" style="color:var(--blue-darker)">
                Kenapa Harus SIJAR?
            </h2>
            <p class="max-w-xl mx-auto text-base" style="color:var(--text-muted)">
                SIJAR hadir untuk menggantikan sistem peminjaman manual dengan solusi digital yang mudah, cepat, dan dapat dipantau kapan saja.
            </p>
        </div>

        <!-- Tujuan & Manfaat cards -->
        <div class="grid md:grid-cols-2 gap-8 mb-14">

            <!-- Tujuan -->
            <div class="card p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center" style="background:var(--blue-lighter)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:var(--blue-primary)">
                            <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold" style="color:var(--blue-darker)">Tujuan Sistem</h3>
                </div>
                <ul class="space-y-3">
                    @foreach([
                        'Mempermudah proses peminjaman barang antar jurusan',
                        'Mengurangi kehilangan & kerusakan barang inventaris',
                        'Menciptakan laporan peminjaman yang akurat dan real-time',
                        'Meningkatkan akuntabilitas penggunaan aset jurusan'
                    ] as $tujuan)
                    <li class="flex items-start gap-3">
                        <div class="mt-1 w-5 h-5 rounded-full flex-shrink-0 flex items-center justify-center" style="background:var(--blue-lighter)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" style="color:var(--blue-primary)"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 0 1 0 1.414l-8 8a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L8 12.586l7.293-7.293a1 1 0 0 1 1.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <p class="text-sm leading-relaxed" style="color:var(--text-muted)">{{ $tujuan }}</p>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Manfaat -->
            <div class="card p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center" style="background:#E6FAF4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:var(--green-soft)">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold" style="color:var(--blue-darker)">Manfaat untuk Kamu</h3>
                </div>
                <ul class="space-y-3">
                    @foreach([
                        'Proses pengajuan peminjaman hanya butuh beberapa menit',
                        'Notifikasi otomatis saat peminjaman disetujui atau ditolak',
                        'Riwayat peminjaman tersimpan dengan rapi dan mudah dicari',
                        'Guru dan admin dapat memantau stok barang secara langsung'
                    ] as $item)
                    <li class="flex items-start gap-3">
                        <div class="mt-1 w-5 h-5 rounded-full flex-shrink-0 flex items-center justify-center" style="background:#E6FAF4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" style="color:var(--green-soft)"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 0 1 0 1.414l-8 8a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L8 12.586l7.293-7.293a1 1 0 0 1 1.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <p class="text-sm leading-relaxed" style="color:var(--text-muted)">{{ $item }}</p>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- 3 feature highlight -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @foreach([
                ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/>', 'title' => 'Pencatatan Digital', 'desc' => 'Semua transaksi peminjaman tercatat otomatis tanpa kertas.', 'color' => 'var(--blue-lighter)', 'icolor' => 'var(--blue-primary)'],
                ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>', 'title' => 'Pantau Real-time', 'desc' => 'Status barang dan peminjaman bisa dipantau kapan saja.', 'color' => '#FFF5E0', 'icolor' => 'var(--yellow-soft)'],
                ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2zm0 0V9a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v10m-6 0a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2m0 0V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2z"/>', 'title' => 'Laporan Lengkap', 'desc' => 'Ekspor laporan inventaris bulanan dengan mudah.', 'color' => '#F0FFF8', 'icolor' => 'var(--green-soft)'],
            ] as $feat)
            <div class="card p-6 text-center">
                <div class="w-12 h-12 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background:{{ $feat['color'] }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="color:{{ $feat['icolor'] }}">{!! $feat['icon'] !!}</svg>
                </div>
                <h4 class="font-bold mb-1 text-sm" style="color:var(--blue-darker)">{{ $feat['title'] }}</h4>
                <p class="text-xs leading-relaxed" style="color:var(--text-muted)">{{ $feat['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- ══════════════════════════════════════════
     SECTION 3 — JURUSAN
══════════════════════════════════════════ -->
<section id="jurusan" class="py-20 px-6" style="background:var(--sky)">
    <div class="max-w-6xl mx-auto">

        <div class="text-center mb-14">
            <div class="section-chip mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5m-4 0h4"/></svg>
                Jurusan
            </div>
            <h2 class="text-3xl md:text-4xl font-extrabold mb-3" style="color:var(--blue-darker)">
                Jurusan yang Tersedia
            </h2>
            <p class="max-w-lg mx-auto text-base" style="color:var(--text-muted)">
                SIJAR melayani seluruh jurusan di SMKN 8 Semarang. Pilih jurusanmu dan mulai kelola peminjaman barang dengan mudah.
            </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-5">
            @foreach([
                ['name' => 'PPLG',  'full' => 'Pengembangan Perangkat Lunak & GIM', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>'],
                ['name' => 'TJKT',  'full' => 'Teknik Jaringan Komputer & Telekomun.', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>'],
                ['name' => 'LK',    'full' => 'Layanan Kesehatan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 0 0 0 6.364L12 20.364l7.682-7.682a4.5 4.5 0 0 0-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 0 0-6.364 0z"/>'],
                ['name' => 'PS',    'full' => 'Perhotelan & Spa', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 0 0 1 1h3m10-11l2 2m-2-2v10a1 1 0 0 1-1 1h-3m-6 0a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1m-6 0h6"/>'],
                ['name' => 'DKV',   'full' => 'Desain Komunikasi Visual', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 0 1 2.828 0L16 16m-2-2 1.586-1.586a2 2 0 0 1 2.828 0L20 14m-6-6h.01M6 20h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"/>'],
            ] as $jurusan)
            <div class="jurusan-card p-5 flex flex-col items-center text-center cursor-default">
                <div class="jurusan-icon w-12 h-12 rounded-2xl flex items-center justify-center mb-3 transition-colors duration-300" style="background:var(--blue-lighter); color:var(--blue-primary)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $jurusan['icon'] !!}</svg>
                </div>
                <h3 class="font-extrabold text-lg mb-1" style="color:var(--blue-darker)">{{ $jurusan['name'] }}</h3>
                <p class="text-xs leading-snug" style="color:var(--text-muted)">{{ $jurusan['full'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- ══════════════════════════════════════════
     SECTION 4 — BARANG / INVENTARIS
══════════════════════════════════════════ -->
<section id="barang" class="py-20 px-6" style="background:var(--white)">
    <div class="max-w-6xl mx-auto">

        <div class="text-center mb-14">
            <div class="section-chip mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zm-9 5H7"/></svg>
                Inventaris Barang
            </div>
            <h2 class="text-3xl md:text-4xl font-extrabold mb-3" style="color:var(--blue-darker)">
                Barang yang Bisa Dipinjam
            </h2>
            <p class="max-w-lg mx-auto text-base" style="color:var(--text-muted)">
                Berbagai barang inventaris jurusan tersedia dan siap dipinjam melalui sistem SIJAR.
            </p>
        </div>

        <!-- Sample item cards — replace src with real images -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @forelse($brg as $i)
           <div class="card overflow-hidden">
                <div class="h-44 w-full overflow-hidden" style="background:var(--blue-lighter)">
                    <img src="{{ asset('encrypted/barang/' . $i->foto_barang) }}" alt="{{ $i->nama_item }}"
                         class="w-full h-full object-cover">
                </div>
                <div class="p-5">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h4 class="font-bold text-base" style="color:var(--blue-darker)">{{ $i->nama_item }}</h4>
                            <p class="text-xs" style="color:var(--text-muted)">{{ $i->kategori_jurusan->nama_kategori }}</p>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full" style="background:#E6FAF4; color:var(--green-soft)">
                            {{ $i->status_item}}
                        </span>
                    </div>
                    <a href="{{ route('login') }}" class="mt-3 btn-outline w-full justify-center text-xs py-2">
                        Pinjam Sekarang
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
            @empty
            <p class="text-center col-span-full text-sm italic" style="color:var(--text-muted)">Belum ada barang yang tersedia untuk dipinjam.</p>
            @endforelse
        </div>

        <!-- More button -->
        <div class="text-center">
            <a href="{{ route('login') }}" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h7"/></svg>
                Lihat Semua Barang
            </a>
        </div>
    </div>
</section>


<!-- ══════════════════════════════════════════
     SECTION 5 — KONTAK / SOSMED
══════════════════════════════════════════ -->
<section id="kontak" class="py-20 px-6" style="background:var(--sky)">
    <div class="max-w-4xl mx-auto text-center">

        <div class="section-chip mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z"/></svg>
            Hubungi Kami
        </div>
        <h2 class="text-3xl md:text-4xl font-extrabold mb-3" style="color:var(--blue-darker)">
            Ada Pertanyaan?
        </h2>
        <p class="mb-10 max-w-md mx-auto" style="color:var(--text-muted)">
            Jangan ragu untuk menghubungi tim kami melalui media sosial di bawah ini. Kami siap membantu!
        </p>

        <div class="flex flex-wrap justify-center gap-4">

            <!-- Instagram -->
            <a href="#" class="card flex items-center gap-3 px-5 py-4 hover:scale-105 transition-transform">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#fce4ec">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="#E91E63" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                    </svg>
                </div>
                <span class="font-semibold text-sm" style="color:var(--text-main)">@sijar_smkn8</span>
            </a>

            <!-- WhatsApp -->
            <a href="https://wa.me/6281234567890" class="card flex items-center gap-3 px-5 py-4 hover:scale-105 transition-transform">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#E6F9EE">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="#25D366">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a13 13 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
                    </svg>
                </div>
                <span class="font-semibold text-sm" style="color:var(--text-main)">WhatsApp</span>
            </a>

            <!-- Email -->
            <a href="mailto:sijar@smkn8semarang.sch.id" class="card flex items-center gap-3 px-5 py-4 hover:scale-105 transition-transform">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:var(--blue-lighter)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:var(--blue-primary)">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z"/>
                    </svg>
                </div>
                <span class="font-semibold text-sm" style="color:var(--text-main)">Email Kami</span>
            </a>

            <!-- YouTube -->
            <a href="#" class="card flex items-center gap-3 px-5 py-4 hover:scale-105 transition-transform">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#ffebee">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="#FF0000">
                        <path d="M23.495 6.205a3.007 3.007 0 0 0-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 0 0 .527 6.205a31.247 31.247 0 0 0-.522 5.805 31.247 31.247 0 0 0 .522 5.783 3.007 3.007 0 0 0 2.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 0 0 2.088-2.088 31.247 31.247 0 0 0 .5-5.783 31.247 31.247 0 0 0-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/>
                    </svg>
                </div>
                <span class="font-semibold text-sm" style="color:var(--text-main)">YouTube</span>
            </a>
        </div>
    </div>
</section>



<footer style="background:var(--blue-darker)">
    <div class="max-w-6xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

            <!-- Brand -->
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <img src="{{ asset('/images/logo_sijar.png') }}" alt="SIJAR Logo" class="h-8 w-8 object-contain brightness-200">
                    <span class="text-xl font-extrabold text-white">SIJAR</span>
                </div>
                <p class="text-sm leading-relaxed" style="color:var(--blue-light)">
                    Sistem Inventaris Peminjaman Barang Jurusan — solusi digital untuk SMKN 8 Semarang.
                </p>
            </div>

            <!-- Quick links -->
            <div>
                <h5 class="text-white font-bold mb-4 text-sm uppercase tracking-widest">Navigasi</h5>
                <ul class="space-y-2">
                    @foreach(['Beranda' => '#home', 'Tentang' => '#tentang', 'Jurusan' => '#jurusan', 'Barang' => '#barang', 'Kontak' => '#kontak'] as $label => $href)
                    <li><a href="{{ $href }}" class="footer-link text-sm">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Jurusan -->
            <div>
                <h5 class="text-white font-bold mb-4 text-sm uppercase tracking-widest">Jurusan</h5>
                <ul class="space-y-2">
                    @foreach(['PPLG', 'TJKT', 'LK', 'PS', 'DKV'] as $j)
                    <li><span class="footer-link text-sm">{{ $j }}</span></li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-2" style="border-top:1px solid rgba(255,255,255,.1)">
            <p class="text-xs" style="color:var(--blue-light)">© {{ date('Y') }} SIJAR — SMKN 8 Semarang</p>
            <p class="text-xs" style="color:var(--blue-light)">Production by XI PPLG 3</p>
        </div>
    </div>
</footer>



<script>
    // Mobile hamburger
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobile-menu');
    let open = false;
    hamburger.addEventListener('click', () => {
        open = !open;
        if (open) {
            mobileMenu.style.maxHeight = mobileMenu.scrollHeight + 'px';
            mobileMenu.style.opacity  = '1';
        } else {
            mobileMenu.style.maxHeight = '0';
            mobileMenu.style.opacity   = '0';
        }
    });

    // Close mobile menu on link click
    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            open = false;
            mobileMenu.style.maxHeight = '0';
            mobileMenu.style.opacity   = '0';
        });
    });

    // Intersection observer for fade-up sections
    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.style.opacity = '1';
                e.target.style.transform = 'translateY(0)';
                io.unobserve(e.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('section').forEach(sec => {
        sec.style.opacity = '0';
        sec.style.transform = 'translateY(32px)';
        sec.style.transition = 'opacity .65s ease, transform .65s ease';
        io.observe(sec);
    });
</script>

</body>
</html>