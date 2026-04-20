{{-- Vista d'edició del perfil d'usuari — Estén layouts/app.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="edit-container">

    {{-- HEADER --}}
    <div class="edit-header">
        <div class="edit-header-content">
            <h1 class="edit-page-title">{{ __('profile.profile') }}</h1>
        </div>
        <div class="edit-header-actions">
            <a href="{{ route('profile.show') }}" class="edit-back-btn">
                <i class="bi bi-arrow-left"></i>
                {{ __('profile.back') }}
            </a>
        </div>
    </div>

    {{-- INFORMACIÓ DEL PERFIL --}}
    <div class="edit-card mb-4">
        <div class="edit-card-header">
            <div class="edit-card-header-info">
                <h2 class="edit-card-title">{{ __('profile.update_profile_information') }}</h2>
                <p class="edit-card-subtitle">{{ __('profile.update_profile_description') }}</p>
            </div>
        </div>
        <div class="edit-card-body">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    {{-- CANVI DE CONTRASENYA --}}
    <div class="edit-card mb-4">
        <div class="edit-card-header">
            <div class="edit-card-header-info">
                <h2 class="edit-card-title">{{ __('profile.update_password') }}</h2>
                <p class="edit-card-subtitle">{{ __('profile.update_password_description') }}</p>
            </div>
        </div>
        <div class="edit-card-body">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    {{-- ELIMINAR COMPTE --}}
    <div class="edit-card edit-danger-card mb-4">
        <div class="edit-card-header edit-danger-header">
            <div class="edit-card-header-info">
                <h2 class="edit-card-title edit-danger-title">{{ __('profile.delete_account') }}</h2>
                <p class="edit-card-subtitle">{{ __('profile.delete_account_description') }}</p>
            </div>
        </div>
        <div class="edit-card-body">
            @include('profile.partials.delete-user-form')
        </div>
    </div>

</div>
@endsection
