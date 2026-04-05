<x-guest-layout>
    <!-- ICONA DEL HEADER -->
    <div class="login-icon-header">
        <div class="login-icon">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
    </div>

    <!-- TÍTOL -->
    <h1 class="login-title">{{ __('auth.reset_password_title') }}</h1>

    <form method="POST" action="{{ route('password.store') }}" class="login-form">
        @csrf

        <!-- TOKEN DE RESTABLIMENT -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                    value="{{ old('email', $request->email) }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="correu@exemple.com">
            </div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- PASSWORD -->
        <div class="login-form-group mt-3">
            <label for="password" class="login-label">{{ __('auth.password') }}</label>
            <div class="login-input-wrapper">
                <span class="login-input-icon">
                    <i class="bi bi-lock"></i>
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

        <!-- CONFIRM PASSWORD -->
        <div class="login-form-group mt-3">
            <label for="password_confirmation" class="login-label">{{ __('auth.confirm_password') }}</label>
            <div class="login-input-wrapper">
                <span class="login-input-icon">
                    <i class="bi bi-lock-fill"></i>
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

        <!-- BOTÓ ENVIAR -->
        <div class="login-footer-actions login-center-actions mt-4">
            <button type="submit" class="login-btn">
                {{ __('auth.reset_password_button') }}
                <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </form>
</x-guest-layout>
