<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sobre Nosaltres — Fogons i Sabors</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light body-reset d-flex flex-column min-vh-100 page-grid-bg">
    {{-- Navegació principal compartida --}}@include('layouts.navigation')

    {{-- HERO (SECCIÓ PRINCIPAL) --}}
    <section class="py-5 text-center">
        <div class="container">
            <span class="home-hero-tag d-inline-flex align-items-center gap-2 mb-2">
                <i class="bi bi-people-fill"></i>
                SOBRE NOSALTRES
            </span>
            <h1 class="home-section-title mb-3">Coneix Fogons i Sabors</h1>
            <p class="home-hero-desc" style="max-width: 700px; margin: 0 auto;">
                Una comunitat apassionada per la cuina, on cada recepta és una història i cada xef té una veu.
            </p>
        </div>
    </section>

    {{-- DESCRIPCIÓ DEL PROJECTE --}}
    <section class="container mb-5">
        <div class="card-ui p-4 p-md-5 mx-auto" style="max-width: 900px;">
            <h2 style="font-family: var(--font-serif); font-size: 1.8rem; font-weight: 700; color: var(--text-main); margin-bottom: 16px;">
                La nostra història
            </h2>
            <p style="color: var(--text-secondary); font-size: 1.05rem; line-height: 1.8;">
                Fogons i Sabors va néixer amb la idea de connectar amants de la cuina de tot el món.
                Creiem que cuinar és molt més que preparar aliments: és una forma d'expressió,
                de cultura i de comunitat. La nostra plataforma ofereix un espai perquè xefs
                professionals i aficionats comparteixin les seves creacions, participin en duels
                culinaris i descobreixin noves inspiracions gastronòmiques.
            </p>
            <p style="color: var(--text-secondary); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0;">
                Des dels nostres inicis, hem treballat per construir una comunitat inclusiva on
                la creativitat culinària no té límits. Cada recepta publicada, cada duel celebrat
                i cada valoració compartida contribueix a fer créixer aquest espai únic dedicat
                a l'art de la cuina.
            </p>
        </div>
    </section>

    {{-- MISSIÓ / VISIÓ / VALORS --}}
    <section class="container mb-5">
        <div class="text-center mb-4">
            <h2 class="home-section-title">El que ens defineix</h2>
        </div>
        <div class="row justify-content-center g-4" style="max-width: 1100px; margin: 0 auto;">
            <div class="col-12 col-md-4 d-flex">
                <div class="admin-card w-100">
                    <div class="admin-card-icon">
                        <i class="bi bi-bullseye"></i>
                    </div>
                    <h3 class="admin-card-title">Missió</h3>
                    <p class="admin-card-description mb-0">
                        Democratitzar l'art culinari, fent accessible el coneixement gastronòmic
                        a tothom, des de cuiners novells fins a xefs experts.
                    </p>
                </div>
            </div>
            <div class="col-12 col-md-4 d-flex">
                <div class="admin-card w-100">
                    <div class="admin-card-icon">
                        <i class="bi bi-eye-fill"></i>
                    </div>
                    <h3 class="admin-card-title">Visió</h3>
                    <p class="admin-card-description mb-0">
                        Ser la comunitat culinària de referència al món, on cada persona pugui
                        compartir, aprendre i competir amb passió.
                    </p>
                </div>
            </div>
            <div class="col-12 col-md-4 d-flex">
                <div class="admin-card w-100">
                    <div class="admin-card-icon">
                        <i class="bi bi-heart-fill"></i>
                    </div>
                    <h3 class="admin-card-title">Valors</h3>
                    <p class="admin-card-description mb-0">
                        Creativitat, inclusió, respecte per la tradició culinària i compromís
                        amb la qualitat en cada recepta compartida.
                    </p>
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
