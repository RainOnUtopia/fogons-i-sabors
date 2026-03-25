<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ __('admin.dashboard_title') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-light">
        <div class="admin-container">
            <!-- Header Superior -->
            <div class="admin-header">
                <div class="admin-header-content">
                    <div class="admin-page-header">
                        <p class="admin-page-subtitle">
                            <i class="bi bi-shield-fill"></i>
                            CENTRE DE CONTROL
                        </p>
                        <h1 class="admin-page-title">{{ __('admin.dashboard_title') }}</h1>
                    </div>
                    <p class="admin-welcome-message">
                        {{ __('admin.welcome') }}, <strong>{{ Auth::user()->name }}</strong>
                        <span class="admin-role-badge">{{ Auth::user()->role }}</span>
                    </p>
                </div>
                <div class="admin-header-actions">
                    <a href="/" class="admin-home-btn">
                        <i class="bi bi-house-fill"></i>
                        Tornar a l'Inici
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="admin-logout-btn">
                            <i class="bi bi-box-arrow-right"></i>
                            {{ __('auth.logout') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Graella de tarjetas -->
            <div class="admin-cards-grid">
                <!-- Tarjeta 1: Gestió d'usuaris -->
                <div class="admin-card">
                    <div class="admin-card-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h2 class="admin-card-title">Gestió d'usuaris</h2>
                    <p class="admin-card-description">Accedir a la gestió completa d'usuaris del sistema</p>
                    <a href="{{ route('admin.users.index') }}" class="admin-card-btn">
                        Anar a usuaris
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <!-- Tarjeta 2: Moderació de duels -->
                <div class="admin-card">
                    <div class="admin-card-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h2 class="admin-card-title">Moderació de duels</h2>
                    <p class="admin-card-description">Gestionar duels publicats, reportats o pendents d'aprovació</p>
                    <a href="#" class="admin-card-btn">
                        Accedir a moderació
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <!-- Tarjeta 3: Panell d'activitat -->
                <div class="admin-card">
                    <div class="admin-card-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h2 class="admin-card-title">Panell d'activitat</h2>
                    <p class="admin-card-description">Visualitzar activitat recent del sistema</p>
                    <a href="#" class="admin-card-btn">
                        Veure activitat
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
