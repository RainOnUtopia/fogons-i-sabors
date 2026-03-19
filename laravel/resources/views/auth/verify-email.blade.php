<x-guest-layout>
    <div class="mb-4 text-secondary">
        {{ __('auth.verify_email_intro') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success" role="alert">
            {{ __('auth.verify_email_sent') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mt-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit" class="btn btn-primary">
                {{ __('auth.verify_email_resend_button') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="btn btn-outline-secondary">
                {{ __('auth.logout_button') }}
            </button>
        </form>
    </div>
</x-guest-layout>
