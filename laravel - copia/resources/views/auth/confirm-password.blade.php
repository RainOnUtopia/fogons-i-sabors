<x-guest-layout>
    <div class="mb-4 text-secondary">
        {{ __('auth.confirm_password_intro') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- CONTRASENYA -->
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('auth.password') }}</label>
            <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary">
                {{ __('auth.confirm_password_button') }}
            </button>
        </div>
    </form>
</x-guest-layout>
