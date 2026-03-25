@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="login-card">
        <!-- Icona del Header -->
        <div class="login-icon-header">
            <div class="login-icon">
                <i class="bi bi-person-plus-fill"></i>
            </div>
        </div>

        <!-- Contigut de la Tarjeta -->
        <div class="login-card-content">
            {{-- Estat de l'alerta --}}
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Títol -->
            <h1 class="login-title">Registrar-se</h1>
            
            <!-- Descripció -->
            <p class="login-description">Crea el teu compte i entra a la cuina de Fogons i Sabors</p>

            <!-- Formulari Registre -->
            <form method="POST" action="{{ route('register') }}" class="login-form">
                @csrf

                <!-- Nom -->
                <div class="login-form-group">
                    <label for="name" class="login-label">{{ __('auth.name') }}</label>
                    <div class="login-input-wrapper">
                        <span class="login-input-icon">
                            <i class="bi bi-person"></i>
                        </span>
                        <input 
                            id="name" 
                            class="login-input @error('name') is-invalid @enderror" 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus 
                            autocomplete="name"
                            placeholder="Nom complet">
                    </div>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="login-form-group">
                    <label for="email" class="login-label">{{ __('auth.email') }}</label>
                    <div class="login-input-wrapper">
                        <span class="login-input-icon">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input 
                            id="email" 
                            class="login-input @error('email') is-invalid @enderror" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="username"
                            placeholder="correu@exemple.com">
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Contrasenya -->
                <div class="login-form-group">
                    <label for="password" class="login-label">{{ __('auth.password') }}</label>
                    <div class="login-input-wrapper">
                        <span class="login-input-icon">
                            <i class="bi bi-key"></i>
                        </span>
                        <input 
                            id="password" 
                            class="login-input @error('password') is-invalid @enderror" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Text Legal -->
                <div class="login-form-group" style="margin-bottom: 24px;">
                    <p style="font-size: 12px; color: #7A7A7A; text-align: center; margin: 0;">
                        En registrar-te, acceptes les nostres 
                        <a href="#" style="color: #BE3144; text-decoration: none;">polítiques de privacitat</a> 
                        i 
                        <a href="#" style="color: #BE3144; text-decoration: none;">polítiques de cookies</a>
                    </p>
                </div>

                <!-- Botó Enviar -->
                <div class="login-footer-actions" style="justify-content: center;">
                    <button type="submit" class="login-btn">
                        Crea un compte
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </form>

            <!-- Link Accedir -->
            <div class="login-register-footer">
                <span class="login-register-text">Ja tens compte?</span>
                <a class="login-register-link" href="{{ route('login') }}">
                    Accedeix
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
