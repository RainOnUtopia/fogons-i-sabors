<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contacte — Fogons i Sabors</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light body-reset d-flex flex-column min-vh-100 page-grid-bg">
    {{-- Navegació principal compartida --}}@include('layouts.navigation')

    {{-- HERO (SECCIÓ PRINCIPAL) --}}
    <section class="py-5 text-center">
        <div class="container">
            <span class="home-hero-tag d-inline-flex align-items-center gap-2 mb-2">
                <i class="bi bi-envelope-fill"></i>
                CONTACTE
            </span>
            <h1 class="home-section-title mb-3">Contacta amb nosaltres</h1>
            <p class="home-hero-desc" style="max-width: 600px; margin: 0 auto;">
                Tens alguna pregunta o suggeriment? Escriu-nos i et respondrem el més aviat possible.
            </p>
        </div>
    </section>

    {{-- FORMULARI DE CONTACTE --}}
    <section class="container mb-5">
        <div class="card-ui mx-auto" style="max-width: 560px;">

            {{-- ICONA DEL HEADER --}}
            <div class="login-icon-header">
                <div class="login-icon">
                    <i class="bi bi-chat-text-fill"></i>
                </div>
            </div>

            {{-- CONTINGUT DEL FORMULARI --}}
            <div class="login-card-content">
                <h2 class="login-title">Envia'ns un missatge</h2>
                <p class="login-description">Omple el formulari i ens posarem en contacte amb tu</p>

                {{-- MISSATGE D'ÈXIT --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    {{-- NOM --}}
                    <div class="login-form-group">
                        <label for="name" class="login-label">Nom</label>
                        <div class="login-input-wrapper @error('name') is-invalid @enderror">
                            <span class="login-input-icon">
                                <i class="bi bi-person"></i>
                            </span>
                            <input id="name" name="name" type="text" class="login-input @error('name') border-danger @enderror" 
                                placeholder="El teu nom complet" value="{{ old('name') }}" required>
                        </div>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div class="login-form-group">
                        <label for="email" class="login-label">Email</label>
                        <div class="login-input-wrapper @error('email') is-invalid @enderror">
                            <span class="login-input-icon">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input id="email" name="email" type="email" class="login-input @error('email') border-danger @enderror" 
                                placeholder="correu@exemple.com" value="{{ old('email') }}" required>
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- MISSATGE --}}
                    <div class="login-form-group">
                        <label for="message" class="login-label">Missatge</label>
                        <textarea id="message" name="message" class="input-ui @error('message') border-danger @enderror" rows="5"
                            placeholder="Escriu el teu missatge aquí..."
                            style="height: auto; min-height: 150px; resize: vertical;" required>{{ old('message') }}</textarea>
                        @error('message')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- BOTÓ ENVIAR --}}
                    <button type="submit" class="login-btn w-100">
                        <i class="bi bi-send"></i>
                        Enviar missatge
                    </button>
                </form>
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
