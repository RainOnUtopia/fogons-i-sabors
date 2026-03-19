<section>
    <header class="mb-4">
        <h2 class="h5 text-dark">
            {{ __('profile.update_profile_information') }}
        </h2>

        <p class="text-muted small">
            {{ __('profile.update_profile_description') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label">{{ __('auth.name') }}</label>
            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('auth.email') }}</label>
            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-dark mb-1">
                        {{ __('profile.unverified_email') }}
                        <button form="send-verification" class="btn btn-link p-0 text-decoration-none shadow-none">
                            {{ __('profile.resend_verification') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="text-success small fw-medium">
                            {{ __('profile.verification_sent') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3 mt-4">
            <button class="btn btn-primary">{{ __('common.save') }}</button>

            @if (session('status') === 'profile-updated')
                <p class="text-success small mb-0">{{ __('common.saved') }}</p>
            @endif
        </div>
    </form>
</section>
