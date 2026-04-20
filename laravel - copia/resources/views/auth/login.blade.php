@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="login-card">
        <!-- ICONA DEL HEADER -->
        <div class="login-icon-header">
            <div class="login-icon">
                <i class="bi bi-lock-fill"></i>
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
            <h1 class="login-title">Benvingut</h1>
            
            <!-- DESCRIPCIÓ -->
            <p class="login-description">Inicia sessió amb el teu compte</p>

            <!-- FORMULARI ACCEDIR -->
            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf

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
                            autofocus 
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
                            autocomplete="current-password"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- RECORDA'M -->
                <div class="login-remember">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label class="form-check-label" for="remember_me">
                        {{ __('auth.remember_password') }}
                    </label>
                </div>

                <!-- CONTRASENYA OBLIDADA I ENVIAR -->
                <div class="login-footer-actions">
                    <button type="submit" class="login-btn">
                        {{ __('auth.login_button') }}
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
                @if (Route::has('password.request'))
                    <div class="login-forgot-wrapper">
                        <a class="login-forgot-link" href="{{ route('password.request') }}">
                            Has oblidat la teva contrasenya?
                        </a>
                    </div>
                @endif
            </form>

            <!-- LINK REGISTRAR-SE -->
            @if (Route::has('register'))
                <div class="login-register-footer">
                    <span class="login-register-text">No tens compte?</span>
                    <a class="login-register-link" href="{{ route('register') }}">
                        Registra't
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
