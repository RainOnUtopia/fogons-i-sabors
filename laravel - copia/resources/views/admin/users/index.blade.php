{{-- Vista de la llista d'usuaris del sistema — Estén layouts/admin.blade.php --}}
@extends('layouts.admin')

@section('title', __('admin.users_list'))

@section('content')
    <div class="users-container">
        <!-- Header superior -->
        <div class="users-header">
            <div class="users-header-content">
                <h1 class="users-page-title">Membres de la Comunitat</h1>
            </div>
            <div class="users-header-actions">
                <a href="{{ route('admin.dashboard') }}" class="users-dashboard-btn">
                    <i class="bi bi-arrow-left"></i>
                    Panell Admin
                </a>
            </div>
        </div>

        <!-- Missatges d'estat-->
        @if (session('status') === 'user-updated')
            <div class="users-alert-container">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ __('admin.users.updated_successfully') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <!-- Targeta principal dels usuaris -->
        <div class="users-card">
            <!-- Header de la Targeta amb cerca i filtres -->
            <div class="users-card-header">
                <h2 class="users-card-title">Llista d'Usuaris</h2>

                <div class="users-card-controls">
                    <!-- Cerca -->
                    <form method="GET" action="{{ route('admin.users.index') }}" class="users-search-form"
                        id="usersSearchForm">
                        <input type="hidden" name="sort" value="{{ request('sort', 'name') }}">
                        <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">
                        <input type="hidden" name="role" value="{{ request('role') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">

                        <div class="users-search-wrapper">
                            <i class="bi bi-search"></i>
                            <input type="text" name="search" class="users-search-input" placeholder="Cercar usuari..."
                                value="{{ request('search') }}" id="searchInput">
                        </div>
                    </form>

                    <!-- Botó de filtre -->
                    <button class="users-filter-btn" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#filtersOffcanvas">
                        <i class="bi bi-funnel"></i>
                    </button>
                </div>
            </div>

            <!-- Taula d'usuaris -->
            <div class="users-table-wrapper">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th class="users-table-header">
                                <a href="{{ $sortData['name']['url'] }}" class="users-table-header-link">
                                    USUARI {{ $sortData['name']['indicator'] }}
                                </a>
                            </th>
                            <th class="users-table-header">
                                <a href="{{ $sortData['is_active']['url'] }}" class="users-table-header-link">
                                    ESTAT {{ $sortData['is_active']['indicator'] }}
                                </a>
                            </th>
                            <th class="users-table-header">
                                <a href="{{ $sortData['role']['url'] }}" class="users-table-header-link">
                                    ROL {{ $sortData['role']['indicator'] }}
                                </a>
                            </th>
                            <th class="users-table-header">RECEPTES</th>
                            <th class="users-table-header">ACCIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="users-table-row">
                                <!-- Columna Usuari -->
                                <td class="users-table-cell users-user-cell">
                                    <div class="users-user-info">
                                        <div class="users-avatar">
                                            @if($user->avatar)
                                                <img src="{{ asset('storage/' . $user->avatar) . '?v=' . $user->updated_at?->timestamp }}"
                                                    alt="Avatar de {{ $user->name }}">
                                            @else
                                                {{ substr($user->name, 0, 1) }}
                                            @endif
                                        </div>
                                        <div class="users-user-details">
                                            <div class="users-user-name">{{ $user->name }}</div>
                                            <div class="users-user-email">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <!-- Columna Estat -->
                                <td class="users-table-cell">
                                    @if($user->is_active)
                                        <span class="users-badge users-badge-active">ACTIU</span>
                                    @else
                                        <span class="users-badge users-badge-inactive">INACTIU</span>
                                    @endif
                                </td>
                                <!-- Columna Rol -->
                                <td class="users-table-cell">
                                    <span class="users-role-text">{{ ucfirst($user->role) }}</span>
                                </td>
                                <!-- Columna Receptes -->
                                <td class="users-table-cell">
                                    <span class="users-recipes-count">{{ $user->recipes_count ?? 0 }}</span>
                                </td>
                                <!-- Columna Accions -->
                                <td class="users-table-cell users-actions-cell">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="users-edit-btn">
                                        Editar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginació -->
            <div class="users-pagination-wrapper pagination-container">
                {{ $users->links("pagination::bootstrap-5") }}
            </div>
        </div>
    </div>

    <!-- Offcanvas de filtres -->
    <div class="offcanvas offcanvas-end users-filters-offcanvas" tabindex="-1" id="filtersOffcanvas">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Filtres</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="users-filters-form">
                <input type="hidden" name="sort" value="{{ request('sort', 'name') }}">
                <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">
                <input type="hidden" name="search" value="{{ request('search') }}">

                <!-- Filtre per Rol -->
                <div class="users-filter-group">
                    <label class="users-filter-label">Rol</label>
                    <select name="role" class="users-filter-select">
                        <option value="">Tots els rols</option>
                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Usuari</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <!-- Filtre per Estat -->
                <div class="users-filter-group">
                    <label class="users-filter-label">Estat</label>
                    <select name="status" class="users-filter-select">
                        <option value="">Tots els estats</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Actiu</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactiu</option>
                    </select>
                </div>

                <!-- Botons d'acció -->
                <div class="users-filter-actions">
                    <button type="submit" class="users-filter-apply-btn">Aplicar Filtres</button>
                    @if(request()->anyFilled(['search', 'role', 'status']))
                        <a href="{{ route('admin.users.index') }}" class="users-filter-reset-btn">Netejar</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-submit search form
        document.getElementById('searchInput')?.addEventListener('keyup', function (e) {
            if (e.key === 'Enter') {
                document.getElementById('usersSearchForm').submit();
            }
        });
    </script>
@endsection