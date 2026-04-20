<section>

    <form method="post" action="{{ route('password.update') }}" class="edit-form">
        @csrf
        @method('put')

        <div class="edit-form-group">
            <label for="update_password_current_password" class="edit-form-label">{{ __('profile.current_password') }}</label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="input-ui @if($errors->updatePassword->has('current_password')) is-invalid @endif"
                autocomplete="current-password">
            @if($errors->updatePassword->has('current_password'))
                <div class="edit-form-error">{{ $errors->updatePassword->first('current_password') }}</div>
            @endif
        </div>

        <div class="edit-form-group">
            <label for="update_password_password" class="edit-form-label">{{ __('profile.new_password') }}</label>
            <input id="update_password_password" name="password" type="password"
                class="input-ui @if($errors->updatePassword->has('password')) is-invalid @endif"
                autocomplete="new-password" minlength="8" pattern="(?=.*\d).{8,}" title="La contrasenya ha de tenir mínim 8 caràcters i incloure almenys un número">
            @if($errors->updatePassword->has('password'))
                <div class="edit-form-error">{{ $errors->updatePassword->first('password') }}</div>
            @endif
        </div>

        <div class="edit-form-group">
            <label for="update_password_password_confirmation" class="edit-form-label">{{ __('auth.confirm_password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="input-ui @if($errors->updatePassword->has('password_confirmation')) is-invalid @endif"
                autocomplete="new-password">
            @if($errors->updatePassword->has('password_confirmation'))
                <div class="edit-form-error">{{ $errors->updatePassword->first('password_confirmation') }}</div>
            @endif
        </div>

        <div class="edit-form-actions">
            @if (session('status') === 'password-updated')
                <p class="text-success small mb-0 me-auto"><i class="bi bi-check-circle me-1"></i>{{ __('common.saved') }}</p>
            @endif
            <button type="submit" class="edit-submit-btn">
                <i class="bi bi-check-lg"></i>
                {{ __('common.save') }}
            </button>
        </div>
    </form>
</section>
