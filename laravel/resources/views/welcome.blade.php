<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fogons i Sabors</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,700&display=swap" rel="stylesheet">
</head>
<body class="bg-light body-reset d-flex flex-column min-vh-100 home-grid-bg">{{-- Navegació principal compartida --}}@include('layouts.navigation')

    {{-- SECCIÓ PRINCIPAL --}}
    <section class="principal-section">
        <div class="principal-content home-principal-content d-flex flex-column align-items-center justify-content-center text-center">
            <span class="mb-2 d-inline-flex align-items-center gap-2 principal-anim anim-d1 home-hero-tag">
                <i class="bi bi-stars home-hero-icon"></i>
                EXCEL·LÈNCIA CULINÀRIA
            </span>
            <h1 class="mb-2 home-principal-title principal-anim anim-d2 home-hero-title">
                On cada plat<br>
                <span class="home-hero-accent">explica una història</span>
            </h1>
            <p class="mb-4 principal-anim anim-d3 home-hero-desc">
                Uneix-te a la comunitat de xefs més prestigiosa del món.<br>
                Competeix en duels en viu, comparteix les teves receptes secretes i puja a l’estrellat culinari.
            </p>
            <div class="d-flex flex-wrap gap-3 justify-content-center principal-anim-scale anim-d4">
                <a href="#" class="home-btn-primary home-btn-reset d-inline-flex align-items-center">
                    Explorar Receptes
                    <i class="bi bi-arrow-right ms-2 home-hero-icon"></i>
                </a>
                @guest
                    <a href="{{ route('register') }}" class="home-btn-outline home-btn-reset">Uneix-te a la Comunitat</a>
                @endguest
            </div>
        </div>
    </section>

    {{-- RECEPTA DEL DIA (MOSTRA) --}}
    <section class="container my-5 reveal">
        <div class="text-center mb-4">
            <span class="home-badge home-badge-featured">SELECCIÓ DESTACADA</span>
            <h2 class="home-section-title">Recepta del Dia</h2>
        </div>
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center home-card home-recipe-card p-4 gap-4 mx-auto">
            <img src="{{ asset('img/risotto.jpg') }}" alt="Risotto de safrà" class="rounded-4 home-recipe-img">
            <div class="flex-grow-1 text-md-start text-center">
                <div class="mb-2">
                    <span class="home-badge">ITALIÀ</span>
                    <span class="home-badge">DIFÍCIL</span>
                </div>
                <h3 class="home-recipe-title">Risotto de safrà amb pa d’or i tòfona</h3>
                <p class="home-hero-desc">Un risotto cremós infusionat amb safrà, pa d’or cruixent i làmines de tòfona fresca. Un repte per als paladars més exigents.</p>
                <div class="d-flex align-items-center gap-2 justify-content-md-start justify-content-center mb-2">
                    <img src="{{ asset('img/chef-marco.jpg') }}" alt="Chef Marco Pierre" class="rounded-circle home-chef-avatar">
                    <span class="home-chef-name">Chef Marco Pierre</span>
                </div>
                <a href="#" class="home-btn-primary home-btn-primary-compact">Veure recepta completa &rarr;</a>
            </div>
        </div>
    </section>

    {{-- MÉTRICAS PLATAFORMA (MOSTRA) --}}
    <section class="container my-5 reveal">
        <div class="row home-metrics-row justify-content-center gap-4 gap-md-0">
            <div class="col-12 col-md-4 mb-4 mb-md-0 d-flex justify-content-center">
                <div class="home-metric-card w-100">
                    <div class="home-metric-icon"><i class="bi bi-egg-fried"></i></div>
                    <div class="home-metric-number">12.4k</div>
                    <div class="home-metric-text">XEFS ACTIUS</div>
                </div>
            </div>
            <div class="col-12 col-md-4 mb-4 mb-md-0 d-flex justify-content-center">
                <div class="home-metric-card w-100">
                    <div class="home-metric-icon"><i class="bi bi-trophy"></i></div>
                    <div class="home-metric-number">156</div>
                    <div class="home-metric-text">DUELS ACTIUS</div>
                </div>
            </div>
            <div class="col-12 col-md-4 d-flex justify-content-center">
                <div class="home-metric-card w-100">
                    <div class="home-metric-icon"><i class="bi bi-journal-text"></i></div>
                    <div class="home-metric-number">45.8k</div>
                    <div class="home-metric-text">RECEPTES TOTALS</div>
                </div>
            </div>
        </div>
    </section>

    {{-- LA PISSARRA DEL XEF (MOSTRA) --}}
    <section class="container my-5 reveal">
        <div class="home-quote-card home-quote-container mx-auto">
            <div class="home-quote-icon-bg"><i class="bi bi-egg-fried"></i></div>
            <blockquote class="mb-4 home-quote-text">
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
                <img src="{{ asset('img/logo.png') }}" alt="Logo FogonsiSabors" class="footer-logo">
                <span class="fw-bold footer-brand-text">Fogons <span class="navbar-brand-i">i</span> <span class="home-hero-accent">Sabors</span></span>
            </div>
            <div class="text-secondary footer-copy">&copy; {{ date('Y') }} Fogons i Sabors. Tots els drets reservats.</div>
            <div class="d-flex gap-3">
                <a href="#" class="text-secondary footer-link">Privacitat</a>
                <a href="#" class="text-secondary footer-link">Contacte</a>
            </div>
        </div>
    </footer>
</body>
</html>

