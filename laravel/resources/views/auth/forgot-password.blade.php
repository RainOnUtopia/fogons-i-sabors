<x-guest-layout>
    <!-- Icona del Header -->
    <div class="login-icon-header">
        <div class="login-icon">
            <i class="bi bi-envelope-fill"></i>
        </div>
    </div>

    {{-- Estat de l'alerta --}}
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Títol -->
    <h1 class="login-title">Has oblidat la teva contrasenya?</h1>

    <!-- Descripció -->
    <p class="login-description">{{ __('auth.forgot_password_intro') }}</p>

    <!-- Formulari Recuperar Contrasenya -->
    <form method="POST" action="{{ route('password.email') }}" class="login-form">
        @csrf

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
                    autofocus
                    autocomplete="username"
                    placeholder="correu@exemple.com">
            </div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Botó Enviar -->
        <div class="login-footer-actions" style="justify-content: center;">
            <button type="submit" class="login-btn">
                {{ __('auth.password_reset_link_button') }}
                <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </form>

    <!-- Link Accedir -->
    <div class="login-register-footer">
        <span class="login-register-text">Recordes la contrasenya?</span>
        <a class="login-register-link" href="{{ route('login') }}">
            Inicia sessió
        </a>
    </div>
</x-guest-layout>
