<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fogons i Sabors</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,700&display=swap"
        rel="stylesheet">
</head>

<body class="bg-light body-reset d-flex flex-column min-vh-100 home-grid-bg">
    {{-- Navegació principal compartida --}}@include('layouts.navigation')

    {{-- SECCIÓ PRINCIPAL --}}
    <section class="principal-section">
        <div
            class="principal-content home-principal-content d-flex flex-column align-items-center justify-content-center text-center">
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
                <a href="{{ route('recipes.index') }}"
                    class="home-btn-primary home-btn-reset d-inline-flex align-items-center">
                    Explorar Receptes
                    <i class="bi bi-arrow-right ms-2 home-hero-icon"></i>
                </a>
                @guest
                    <a href="{{ route('register') }}" class="home-btn-outline home-btn-reset">Uneix-te a la Comunitat</a>
                @endguest
            </div>
        </div>
    </section>

    {{-- RECEPTA DEL DIA --}}
    @if($featuredRecipe)
        <section class="container my-5 reveal">
            <div class="text-center mb-4">
                <span class="home-badge home-badge-featured">SELECCIÓ DESTACADA</span>
                <h2 class="home-section-title">Recepta del Dia</h2>
            </div>
            <div
                class="d-flex flex-column flex-md-row align-items-center justify-content-center home-card home-recipe-card p-4 gap-4 mx-auto">
                @if($featuredRecipe->image)
                    <img src="{{ asset('storage/' . $featuredRecipe->image) }}" alt="{{ $featuredRecipe->title }}"
                        class="rounded-4 home-recipe-img">
                @else
                    <div class="rounded-4 home-recipe-img d-flex align-items-center justify-content-center"
                        style="background: linear-gradient(135deg, #e0e0e0 0%, #f3f3f3 100%); font-size: 48px; color: #999;">
                        <i class="bi bi-image"></i>
                    </div>
                @endif
                <div class="flex-grow-1 text-md-start text-center">
                    <div class="mb-2">
                        @if($featuredRecipe->tags && count($featuredRecipe->tags) > 0)
                            @foreach(array_slice($featuredRecipe->tags, 0, 2) as $tag)
                                <span class="home-badge">{{ $tag }}</span>
                            @endforeach
                        @endif
                        <span class="home-badge">{{ strtoupper($featuredRecipe->difficulty) }}</span>
                    </div>
                    <h3 class="home-recipe-title">{{ $featuredRecipe->title }}</h3>
                    @if($featuredRecipe->description)
                        <p class="home-hero-desc">{{ $featuredRecipe->description }}</p>
                    @endif
                    <div class="d-flex align-items-center gap-2 justify-content-md-start justify-content-center mb-4">
                        @if($featuredRecipe->chef_avatar)
                            <img src="{{ asset('storage/' . $featuredRecipe->chef_avatar) }}"
                                alt="{{ $featuredRecipe->chef_name }}" class="rounded-circle home-chef-avatar">
                        @else
                            <div class="rounded-circle home-chef-avatar d-flex align-items-center justify-content-center"
                                style="background: #be3144; color: white; font-weight: 600; font-size: 14px;">
                                {{ substr($featuredRecipe->chef_name, 0, 1) }}
                            </div>
                        @endif
                        <span class="home-chef-name">Chef {{ $featuredRecipe->chef_name }}</span>
                        @if($featuredRecipe->rating > 0)
                            <span style="color: #f59e0b; font-size: 13px; font-weight: 600;">⭐
                                {{ number_format($featuredRecipe->rating, 1) }}</span>
                        @endif
                    </div>
                    <a href="/recipes/{{ $featuredRecipe->id }}" class="home-btn-primary home-btn-primary-compact">Veure
                        recepta completa &rarr;</a>
                </div>
            </div>
        </section>
    @endif

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
                “Sempre deixa reposar la carn almenys la meitat del temps que ha trigat a cuinar-se. Això permet que els
                sucs es redistribueixin, assegurant que cada mos sigui perfectament tendre.”
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
                <span class="fw-bold footer-brand-text">Fogons <span class="navbar-brand-i">i</span> <span
                        class="home-hero-accent">Sabors</span></span>
            </div>
            <div class="text-secondary footer-copy">&copy; {{ date('Y') }} Fogons i Sabors. Tots els drets reservats.
            </div>
            <div class="d-flex gap-3">
                <a href="{{ route('about') }}" class="footer-link">Sobre nosaltres</a>
                <a href="{{ route('contact') }}" class="footer-link">Contacte</a>
                <a href="{{ route('privacy') }}" class="footer-link">Privacitat</a>
            </div>
        </div>
    </footer>
</body>

</html>