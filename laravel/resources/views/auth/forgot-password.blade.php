<x-guest-layout>
    <!-- ICONA DEL HEADER -->
    <div class="login-icon-header">
        <div class="login-icon">
            <i class="bi bi-envelope-fill"></i>
        </div>
    </div>

    {{-- ESTAT DE L'ALERTA --}}
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- TÍTOL -->
    <h1 class="login-title">Has oblidat la teva contrasenya?</h1>

    <!-- DESCRIPCIÓ -->
    <p class="login-description">{{ __('auth.forgot_password_intro') }}</p>

    <!-- FORMULARI RECUPERAR CONTRASENYA -->
    <form method="POST" action="{{ route('password.email') }}" class="login-form">
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

        <!-- BOTÓ ENVIAR -->
        <div class="login-footer-actions login-center-actions">
            <button type="submit" class="login-btn">
                {{ __('auth.password_reset_link_button') }}
                <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </form>

    <!-- LINK ACCEDIR -->
    <div class="login-register-footer">
        <span class="login-register-text">Recordes la contrasenya?</span>
        <a class="login-register-link" href="{{ route('login') }}">
            Inicia sessió
        </a>
    </div>
</x-guest-layout>
