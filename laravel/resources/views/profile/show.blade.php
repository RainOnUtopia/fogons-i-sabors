@extends('layouts.app')

@section('content')
    <div class="page-grid-bg profile-show-bg">
        <div class="container profile-show-container">

            <!-- SECCIÓ 1: HEADER PERFIL -->
            <div class="profile-header profile-header-custom mb-4 p-4 position-relative d-flex flex-column align-items-center justify-content-center">
                <div class="position-absolute top-0 end-0 m-3">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary p-2 fw-bold profile-edit-btn"
                        title="Editar perfil">
                        <i class="bi bi-wrench profile-edit-icon"></i>
                    </a>
                </div>
                @if (session('status') === 'avatar-updated')
                    <div class="alert alert-success py-2 px-3 mb-3 profile-avatar-alert">
                        Imatge de perfil actualitzada correctament.
                    </div>
                @endif

                <form id="avatarUploadForm" method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" class="mb-3">
                    @csrf
                    @method('PATCH')

                    <label for="avatarInput" class="profile-avatar-label" title="Fes clic per canviar la imatge de perfil">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) . '?v=' . $user->updated_at?->timestamp : asset('img/user-avatar.svg') }}" alt="Avatar" class="mb-0 profile-avatar-image">
                    </label>

                    <input id="avatarInput" type="file" name="avatar" accept=".png,.jpg,.jpeg,image/png,image/jpeg" class="d-none"
                        onchange="if (this.files.length) { document.getElementById('avatarUploadForm').submit(); }">
                </form>
                @if ($errors->avatarUpload->has('avatar'))
                    <div class="text-danger small mb-2">
                        {{ $errors->avatarUpload->first('avatar') }}
                    </div>
                @endif

                <h2 class="fw-bold mb-1 profile-user-name">{{ $user->name }}</h2>
                <div class="d-flex flex-wrap gap-3 justify-content-center align-items-center mt-2 profile-user-meta">
                    <span><i class="bi bi-geo-alt"></i> Barcelona, España</span>
                    <span><i class="bi bi-calendar"></i> Miembro desde {{ $user->created_at->format('d/m/Y') }}</span>
                </div>
            </div>

            <div class="row g-4">

                <!-- SECCIÓ 2: TARGETA SOBRE MI -->
                <div class="col-lg-4">
                    <div class="card mb-4 profile-sidebar-card">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3 profile-card-title">Sobre mí</h5>
                            <p class="mb-4 profile-about-text">
                                {{ $user->about_me ?? 'Sin descripción.' }}</p>
                            <div class="d-flex justify-content-between text-center mt-4">
                                <div>
                                    <div class="fw-bold profile-stat-value">12</div>
                                    <div class="text-muted profile-stat-label">Recetas</div>
                                </div>
                                <div>
                                    <div class="fw-bold profile-stat-value">340</div>
                                    <div class="text-muted profile-stat-label">Seguidores</div>
                                </div>
                                <div>
                                    <div class="fw-bold profile-stat-value">4.8</div>
                                    <div class="text-muted profile-stat-label">Estrellas</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓ 5: TARGETA ASSOLIMENTS -->
                    <div class="card mt-4 profile-achievements-card-custom">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Assoliments</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i class="bi bi-award me-2"></i>Master of Saffron</li>
                                <li class="mb-2"><i class="bi bi-trophy me-2"></i>Duel Champion</li>
                                <li><i class="bi bi-star me-2"></i>Community Star</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- SECCIÓ CENTRAL: TABS I GRID -->
                <div class="col-lg-8">

                    <!-- SECCIÓ 3: TABS CENTRALS -->
                    <ul class="nav nav-tabs mb-4 profile-tabs">
                        <li class="nav-item">
                            <a class="nav-link active fw-bold profile-tab-active-custom"
                                href="#">Mi despensa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold profile-tab-inactive-custom"
                                href="#">Platos favoritos</a>
                        </li>
                    </ul>

                    <!-- SECCIÓ 4: GRAELLA RECEPTES -->
                    <div class="row g-4">
                        <!-- Targeta crear recepta -->
                        <div class="col-md-4">
                            <div class="card h-100 d-flex align-items-center justify-content-center profile-create-card-custom">
                                <a href="#"
                                    class="d-flex flex-column align-items-center justify-content-center text-decoration-none profile-create-link">
                                    <span class="display-4 mb-2">+</span>
                                    <span class="fw-bold">Crear receta</span>
                                </a>
                            </div>
                        </div>

                        <!-- Exemple de recepta -->
                        <div class="col-md-4">
                            <div class="card h-100 profile-recipe-card">
                                <img src="{{ asset('img/risotto.jpg') }}" class="card-img-top profile-recipe-image">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-2 profile-recipe-title">Risotto de safrà</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted profile-recipe-meta"><i class="bi bi-clock"></i> 45
                                            min</span>
                                        <span>
                                            <a href="#" class="text-primary me-2"><i class="bi bi-pencil"></i></a>
                                            <a href="#" class="text-danger"><i class="bi bi-trash"></i></a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Més recetes... -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection