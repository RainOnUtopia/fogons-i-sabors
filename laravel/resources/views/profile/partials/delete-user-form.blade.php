<section>

    {{-- Danger Zone banner --}}
    <div class="d-flex align-items-center gap-2 mb-4 p-3 danger-zone-banner">
        <i class="bi bi-exclamation-triangle-fill text-danger danger-zone-icon"></i>
        <span class="fw-bold text-danger danger-zone-label">Danger Zone</span>
    </div>

    <button type="button"
        class="btn btn-danger danger-btn-rounded"
        data-bs-toggle="modal"
        data-bs-target="#confirmUserDeletionModal">
        <i class="bi bi-trash3 me-1"></i>
        {{ __('profile.delete_account') }}
    </button>

    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content danger-modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header danger-modal-header">
                        <h5 class="modal-title text-danger d-flex align-items-center gap-2" id="confirmUserDeletionModalLabel">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            {{ __('profile.are_you_sure') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-muted small">
                            {{ __('profile.delete_account_confirmation') }}
                        </p>

                        <div class="edit-form-group mt-3">
                            <label for="password" class="edit-form-label">{{ __('auth.password') }}</label>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="input-ui @if($errors->userDeletion->has('password')) is-invalid @endif"
                                placeholder="{{ __('auth.password') }}"
                            />
                            @if($errors->userDeletion->has('password'))
                                <div class="edit-form-error">{{ $errors->userDeletion->first('password') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer danger-modal-footer">
                        <button type="button"
                            class="btn btn-secondary btn-rounded"
                            data-bs-dismiss="modal">
                            {{ __('profile.cancel') }}
                        </button>
                        <button type="submit"
                            class="btn btn-danger danger-btn-rounded">
                            <i class="bi bi-trash3 me-1"></i>
                            {{ __('profile.delete_account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($errors->userDeletion->isNotEmpty())
        <script type="module">
            document.addEventListener("DOMContentLoaded", function() {
                var myModal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
                myModal.show();
            });
        </script>
    @endif
</section>
