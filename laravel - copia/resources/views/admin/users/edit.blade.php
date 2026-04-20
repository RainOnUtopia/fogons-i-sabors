{{-- Vista d'edició d'usuari — Estén layouts/admin.blade.php --}}
@extends('layouts.admin')

@section('title', __('admin.users.edit_title'))

@section('content')
        <div class="edit-container">
            <!-- Header superior -->
            <div class="edit-header">
                <div class="edit-header-content">
                    <h1 class="edit-page-title">Editar Usuari</h1>
                </div>
            </div>

            <!-- Targeta principal d''edició -->
            <div class="edit-card">
                <!-- Header de la Targeta -->
                <div class="edit-card-header">
                    <div class="edit-card-header-info">
                        <h2 class="edit-card-title">{{ $user->name }}</h2>
                        <p class="edit-card-subtitle">{{ $user->email }}</p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="edit-back-btn">
                        <i class="bi bi-arrow-left"></i>
                        Tornar
                    </a>
                </div>

                <!-- Contingut del formulari -->
                <div class="edit-card-body">
                    @if ($errors->any())
                        <div class="edit-alert edit-alert-error">
                            <div class="edit-alert-header">
                                <i class="bi bi-exclamation-circle"></i>
                                <span>Error a la validació</span>
                            </div>
                            <ul class="edit-alert-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="edit-form">
                        @csrf
                        @method('PATCH')

                        <!-- Camp Rol -->
                        <div class="edit-form-group">
                            <label for="role" class="edit-form-label">Rol</label>
                            <select class="edit-form-select @error('role') edit-form-select-error @enderror" id="role" name="role" required>
                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Usuari</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrador</option>
                            </select>
                            @error('role')
                                <div class="edit-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Camp Estat -->
                        <div class="edit-form-group">
                            <label for="is_active" class="edit-form-label">Estat</label>
                            <select class="edit-form-select @error('is_active') edit-form-select-error @enderror" id="is_active" name="is_active" required>
                                <option value="1" {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}>Actiu</option>
                                <option value="0" {{ old('is_active', $user->is_active) == 0 ? 'selected' : '' }}>Inactiu</option>
                            </select>
                            @error('is_active')
                                <div class="edit-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botons d'acció -->
                        <div class="edit-form-actions">
                            <button type="submit" class="edit-submit-btn">
                                <i class="bi bi-check-lg"></i>
                                Desar canvis
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
