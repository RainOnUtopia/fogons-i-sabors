<section>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="edit-form">
        @csrf
        @method('patch')

        <div class="edit-form-group">
            <label for="name" class="edit-form-label">{{ __('auth.name') }}</label>
            <input id="name" name="name" type="text" class="input-ui @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="edit-form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="edit-form-group">
            <label for="email" class="edit-form-label">{{ __('auth.email') }}</label>
            <input id="email" name="email" type="email" class="input-ui @error('email') is-invalid @enderror"
                value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <div class="edit-form-error">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="small text-dark mb-1">
                        {{ __('profile.unverified_email') }}
                        <button form="send-verification"
                            class="btn btn-link p-0 text-decoration-none shadow-none edit-link-primary">
                            {{ __('profile.resend_verification') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="text-success small fw-medium">{{ __('profile.verification_sent') }}</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="edit-form-group">
            <label for="city" class="edit-form-label">Ciutat</label>
            <input id="city" name="city" type="text" class="input-ui @error('city') is-invalid @enderror"
                value="{{ old('city', $user->city) }}" autocomplete="address-level2">
            @error('city')
                <div class="edit-form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="edit-form-group">
            <label for="country" class="edit-form-label">Pa�s</label>
            <input id="country" name="country" type="text" class="input-ui @error('country') is-invalid @enderror"
                value="{{ old('country', $user->country) }}" autocomplete="country-name">
            @error('country')
                <div class="edit-form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="edit-form-group">
            <label for="about_me" class="edit-form-label">Descripci�</label>
            <textarea id="about_me" name="about_me" class="input-ui @error('about_me') is-invalid @enderror"
                rows="3">{{ old('about_me', $user->about_me) }}</textarea>
            @error('about_me')
                <div class="edit-form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="edit-form-actions">
            @if (session('status') === 'profile-updated')
                <p class="text-success small mb-0 me-auto"><i class="bi bi-check-circle me-1"></i>{{ __('common.saved') }}
                </p>
            @endif
            <button type="submit" class="edit-submit-btn">
                <i class="bi bi-check-lg"></i>
                {{ __('common.save') }}
            </button>
        </div>
    </form>
</section>