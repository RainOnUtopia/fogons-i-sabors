{{-- Vista del panell de control d'administrador — Estén layouts/admin.blade.php --}}
@extends('layouts.admin')

@section('title', __('admin.dashboard_title'))

@section('content')
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
                </div>
            </div>

            <!-- Graella de Targetes -->
            <div class="admin-cards-grid">
                <!-- Targeta 1: Gestió d'usuaris -->
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

                <!-- Targeta 2: Moderació de duels -->
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

                <!-- Targeta 3: Panell d'activitat -->
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
@endsection
