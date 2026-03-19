<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0 text-dark">{{ __('admin.users.edit_title') }}</h2>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">{{ __('admin.users.back') }}</a>
        </div>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-md-8">

                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 mt-2">{{ $user->name }} ({{ $user->email }})</h5>
                            <p class="text-muted small mb-0">{{ __('admin.users.edit_description') }}</p>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                                @csrf
                                @method('PATCH')

                                <div class="mb-3">
                                    <label for="role" class="form-label">{{ __('admin.users.role') }}</label>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="is_active" class="form-label">{{ __('admin.users.is_active') }}</label>
                                    <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active" required>
                                        <option value="1" {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}>{{ __('admin.users.active') }}</option>
                                        <option value="0" {{ old('is_active', $user->is_active) == 0 ? 'selected' : '' }}>{{ __('admin.users.inactive') }}</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">{{ __('common.save') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
