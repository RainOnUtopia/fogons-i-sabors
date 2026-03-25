<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fogons i Sabors</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,700&display=swap" rel="stylesheet">
    <style>
        body.bg-light {
            background-color: #F4EFEA !important;
        }
        .home-grid-bg {
            background-color: #F4EFEA;
            background-image:
                linear-gradient(0deg, transparent 24%, rgba(200,200,200,0.05) 25%, rgba(200,200,200,0.05) 26%, transparent 27%, transparent 74%, rgba(200,200,200,0.05) 75%, rgba(200,200,200,0.05) 76%, transparent 77%, transparent),
                linear-gradient(90deg, transparent 24%, rgba(200,200,200,0.05) 25%, rgba(200,200,200,0.05) 26%, transparent 27%, transparent 74%, rgba(200,200,200,0.05) 75%, rgba(200,200,200,0.05) 76%, transparent 77%, transparent);
            background-size: 50px 50px;
        }
        .home-principal-title {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-weight: 700;
            letter-spacing: -1px;
        }
        .home-btn-primary {
            background: #BE3144;
            color: #fff;
            border-radius: 12px;
            border: none;
            padding: 12px 32px;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: background 0.2s;
        }
        .home-btn-primary:hover {
            background: #A82838;
            color: #fff;
        }
        .home-btn-outline {
            background: #fff;
            color: #2D2D2D;
            border: 2px solid #F3F3F3;
            border-radius: 12px;
            padding: 12px 32px;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: border 0.2s, color 0.2s;
        }
        .home-btn-outline:hover {
            border-color: #BE3144;
            color: #BE3144;
        }
        .home-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .home-badge {
            display: inline-block;
            background: #F3F3F3;
            color: #7A7A7A;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 4px 12px;
            margin-right: 8px;
        }
        .home-metric-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            padding: 32px 16px;
            text-align: center;
        }
        .home-metric-icon {
            font-size: 2.5rem;
            color: #BE3144;
            margin-bottom: 12px;
        }
        .home-quote-card {
            background: #4F646F;
            border-radius: 24px;
            color: #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            padding: 48px 32px 32px 32px;
            position: relative;
            overflow: hidden;
        }
        .home-quote-icon-bg {
            position: absolute;
            right: 32px;
            top: 32px;
            font-size: 6rem;
            color: rgba(255,255,255,0.12);
            pointer-events: none;
        }
        .home-quote-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
        }
        .home-quote-author {
            font-weight: 600;
            margin-bottom: 0;
        }
        .home-quote-role {
            color: #F3F3F3;
            font-size: 0.95rem;
        }
        @media (max-width: 991.98px) {
            .home-metrics-row { flex-direction: column !important; }
            .home-metric-card { margin-bottom: 24px; }
        }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100 home-grid-bg">
    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom" style="height: 65px;">
            <div class="container-fluid h-100 d-flex align-items-center">
                {{-- Logo + nom --}}
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo FogonsiSabors"
                        style="width: 34px; height: 34px; object-fit: cover; border-radius: 8px; border: 2px solid #dc3544;">
                    <span class="fw-bold">
                        <span style="color: #000000;">Fogons</span><span style="color: #ffbb55;">i</span><span style="color: #be3144;">Sabors</span>
                    </span>
                </a>

                {{-- Responsive toggler --}}
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
                        aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    {{-- Menu horitzontal --}}
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center gap-5 justify-content-start">
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="#">Receptes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="#">Duels</a>
                        </li>
                        @auth
                            @if(Auth::user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link text-dark" href="{{ route('admin.dashboard') }}">Admin</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    {{-- Camp de cerca --}}
                    <form class="d-flex me-3" role="search">
                        <input class="form-control form-control-sm rounded-pill" type="search" placeholder="Cercar…" aria-label="Cercar">
                    </form>

                    {{-- Notificacions, avatar, nom, sortir --}}
                    <div class="d-flex align-items-center gap-3">
                        @auth
                            <button class="btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center" type="button" aria-label="Notificacions" style="width: 32px; height: 32px;">
                                <i class="bi bi-bell" style="font-size: 16px;"></i>
                            </button>

                            <div class="rounded-circle overflow-hidden" style="width: 32px; height: 32px; flex-shrink: 0;">
                                <img src="{{ asset('img/user-avatar.svg') }}" alt="Avatar User" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>

                            <span class="d-none d-md-inline text-dark fw-medium">{{ Auth::user()->name }}</span>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Logout">
                                    <i class="bi bi-box-arrow-right"></i>
                                </button>
                            </form>
                        @endauth

                        @guest
                            <a class="btn btn-outline-dark btn-sm" href="{{ route('login') }}">Accedir</a>
                            @if (Route::has('register'))
                                <a class="btn btn-danger btn-sm" href="{{ route('register') }}">Registrar-se</a>
                            @endif
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

    {{-- SECCIÓ PRINCIPAL --}}
    <section class="principal-section">
        <div class="principal-content d-flex flex-column align-items-center justify-content-center text-center" style="max-width:700px; margin:auto;">
            <span class="mb-2 d-inline-flex align-items-center gap-2" style="font-size:1rem; color:#BE3144; letter-spacing:2px; font-weight:600;">
                <i class="bi bi-stars" style="font-size:1.1em; vertical-align:middle;"></i>
                EXCEL·LÈNCIA CULINÀRIA
            </span>
            <h1 class="mb-2 home-principal-title" style="font-size:3.2rem; font-weight:800; color:#2D2D2D; line-height:1.1;">
                On cada plat<br>
                <span style="color:#BE3144;">explica una història</span>
            </h1>
            <p class="mb-4" style="color:#7A7A7A; font-size:1.15rem;">
                Uneix-te a la comunitat de xefs més prestigiosa del món.<br>
                Competeix en duels en viu, comparteix les teves receptes secretes i puja a l’estrellat culinari.
            </p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="#" class="home-btn-primary d-inline-flex align-items-center" style="text-decoration:none;">
                    Explorar Receptes
                    <i class="bi bi-arrow-right ms-2" style="font-size:1.1em;"></i>
                </a>
                <a href="#" class="home-btn-outline" style="text-decoration:none;">Uneix-te a la Comunitat</a>
            </div>
        </div>
    </section>

    {{-- RECEPTA DEL DIA (MOSTRA) --}}
    <section class="container my-5">
        <div class="text-center mb-4">
            <span class="home-badge" style="background:#F3F3F3; color:#BE3144; font-weight:600;">SELECCIÓ DESTACADA</span>
            <h2 style="font-size:2.1rem; font-weight:800; color:#2D2D2D;">Recepta del Dia</h2>
        </div>
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center home-card p-4 gap-4 mx-auto" style="max-width:900px;">
            <img src="{{ asset('img/risotto.jpg') }}" alt="Risotto de safrà" class="rounded-4" style="width:220px; height:180px; object-fit:cover;">
            <div class="flex-grow-1 text-md-start text-center">
                <div class="mb-2">
                    <span class="home-badge">ITALIÀ</span>
                    <span class="home-badge">DIFÍCIL</span>
                </div>
                <h3 style="font-size:1.5rem; font-weight:700; color:#2D2D2D;">Risotto de safrà amb pa d’or i tòfona</h3>
                <p style="color:#7A7A7A;">Un risotto cremós infusionat amb safrà, pa d’or cruixent i làmines de tòfona fresca. Un repte per als paladars més exigents.</p>
                <div class="d-flex align-items-center gap-2 justify-content-md-start justify-content-center mb-2">
                    <img src="{{ asset('img/chef-marco.jpg') }}" alt="Chef Marco Pierre" class="rounded-circle" style="width:36px; height:36px; object-fit:cover;">
                    <span style="color:#7A7A7A; font-size:0.98rem;">Chef Marco Pierre</span>
                </div>
                <a href="#" class="home-btn-primary" style="padding:8px 20px; font-size:1rem;">Veure recepta completa &rarr;</a>
            </div>
        </div>
    </section>

    {{-- MÉTRICAS PLATAFORMA (MOSTRA) --}}
    <section class="container my-5">
        <div class="row home-metrics-row justify-content-center gap-4 gap-md-0">
            <div class="col-12 col-md-4 mb-4 mb-md-0 d-flex justify-content-center">
                <div class="home-metric-card w-100">
                    <div class="home-metric-icon"><i class="bi bi-egg-fried"></i></div>
                    <div style="font-size:2.1rem; font-weight:800; color:#2D2D2D;">12.4k</div>
                    <div style="color:#7A7A7A; font-size:1.1rem; font-weight:600;">XEFS ACTIUS</div>
                </div>
            </div>
            <div class="col-12 col-md-4 mb-4 mb-md-0 d-flex justify-content-center">
                <div class="home-metric-card w-100">
                    <div class="home-metric-icon"><i class="bi bi-trophy"></i></div>
                    <div style="font-size:2.1rem; font-weight:800; color:#2D2D2D;">156</div>
                    <div style="color:#7A7A7A; font-size:1.1rem; font-weight:600;">DUELS ACTIUS</div>
                </div>
            </div>
            <div class="col-12 col-md-4 d-flex justify-content-center">
                <div class="home-metric-card w-100">
                    <div class="home-metric-icon"><i class="bi bi-journal-text"></i></div>
                    <div style="font-size:2.1rem; font-weight:800; color:#2D2D2D;">45.8k</div>
                    <div style="color:#7A7A7A; font-size:1.1rem; font-weight:600;">RECEPTES TOTALS</div>
                </div>
            </div>
        </div>
    </section>

    {{-- LA PISSARRA DEL XEF (MOSTRA) --}}
    <section class="container my-5">
        <div class="home-quote-card mx-auto" style="max-width:800px;">
            <div class="home-quote-icon-bg"><i class="bi bi-egg-fried"></i></div>
            <blockquote class="mb-4" style="font-size:1.35rem; font-weight:500; line-height:1.5;">
                “Sempre deixa reposar la carn almenys la meitat del temps que ha trigat a cuinar-se. Això permet que els sucs es redistribueixin, assegurant que cada mos sigui perfectament tendre.”
            </blockquote>
            <div class="d-flex align-items-center gap-3">
                <img src="{{ asset('img/chef-marco.jpg') }}" alt="Chef Marco Pierre" class="home-quote-avatar">
                <div>
                    <div class="home-quote-author">Chef Marco Pierre</div>
                    <div class="home-quote-role">Mentor 3 estrelles Michelin</div>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-white border-top mt-auto py-4">
        <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('img/logo.png') }}" alt="Logo FogonsiSabors" style="width: 32px; height: 32px; object-fit: cover; border-radius: 8px; border: 2px solid #dc3544;">
                <span class="fw-bold" style="color:#2D2D2D;">Fogons <span style="color:#ffbb55;">i</span> <span style="color:#BE3144;">Sabors</span></span>
            </div>
            <div class="text-secondary" style="color:#7A7A7A;">&copy; {{ date('Y') }} Fogons i Sabors. Tots els drets reservats.</div>
            <div class="d-flex gap-3">
                <a href="#" class="text-secondary" style="color:#BE3144; text-decoration:none; font-weight:600;">Privacitat</a>
                <a href="#" class="text-secondary" style="color:#BE3144; text-decoration:none; font-weight:600;">Contacte</a>
            </div>
        </div>
    </footer>
</body>
</html>
