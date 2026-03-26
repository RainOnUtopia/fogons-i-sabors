@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="login-card">
        <!-- ICONA DEL HEADER -->
        <div class="login-icon-header">
            <div class="login-icon">
                <i class="bi bi-person-plus-fill"></i>
            </div>
        </div>

        <!-- CONTINGUT DE LA TARGETA -->
        <div class="login-card-content">
            {{-- ESTAT DE L'ALERTA --}}
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- TÍTOL -->
            <h1 class="login-title">Registrar-se</h1>
            
            <!-- DESCRIPCIÓ -->
            <p class="login-description">Crea el teu compte i entra a la cuina de Fogons i Sabors</p>

            <!-- FORMULARI REGISTRE -->
            <form method="POST" action="{{ route('register') }}" class="login-form">
                @csrf

                @if ($errors->has('password') || $errors->has('password_confirmation'))
                    <div class="alert alert-danger login-alert-danger-strong" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ $errors->first('password') ?: $errors->first('password_confirmation') }}
                    </div>
                @endif

                <!-- NOM -->
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

                <!-- EMAIL -->
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

                <!-- CONTRASENYA -->
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

                <!-- CONFIRMAR CONTRASENYA -->
                <div class="login-form-group">
                    <label for="password_confirmation" class="login-label">{{ __('auth.confirm_password') }}</label>
                    <div class="login-input-wrapper">
                        <span class="login-input-icon">
                            <i class="bi bi-key-fill"></i>
                        </span>
                        <input
                            id="password_confirmation"
                            class="login-input @error('password_confirmation') is-invalid @enderror"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••">
                    </div>
                    @error('password_confirmation')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- TEXT LEGAL -->
                <div class="login-form-group">
                    <p class="login-legal-text">
                        En registrar-te, acceptes les nostres 
                        <a href="#" class="login-legal-link">polítiques de privacitat</a> 
                        i 
                        <a href="#" class="login-legal-link">polítiques de cookies</a>
                    </p>
                </div>

                <!-- BOTÓ ENVIAR -->
                <div class="login-footer-actions login-center-actions">
                    <button type="submit" class="login-btn">
                        Crea un compte
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </form>

            <!-- LINK ACCEDIR -->
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
