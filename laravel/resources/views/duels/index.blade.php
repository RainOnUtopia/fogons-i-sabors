@extends('layouts.app')

@section('content')
    <div class="section-ui">
        <div class="recipe-page-container-mb d-flex flex-column flex-lg-row justify-content-between align-items-lg-start gap-4">
            <div class="flex-grow-1">
                <h1 class="recipe-hero-title">Duels Culinaris</h1>
                <p class="recipe-hero-subtitle">Tria un duel, mira com va la votació i descobreix quina recepta s'acaba imposant.</p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                @auth
                    <a href="{{ route('duels.my-duels') }}" class="btn-secondary-ui">
                        <i class="bi bi-collection"></i>
                        Els meus duels
                    </a>
                    <a href="{{ route('duels.create') }}" class="btn-primary-ui">
                        <i class="bi bi-plus-lg"></i>
                        Crear duel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary-ui">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Inicia sessió per competir
                    </a>
                @endauth
            </div>
        </div>

        <div class="recipe-page-container-mb">
            @if(session('success'))
                <div class="alert alert-success shadow-sm border-0 rounded-4 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->has('error'))
                <div class="alert alert-danger shadow-sm border-0 rounded-4 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ $errors->first('error') }}
                </div>
            @endif
        </div>

        <div class="recipe-page-container-mb card-ui overflow-hidden">
            <div class="recipe-filter-bar">
                <div>
                    <h2 class="recipe-form-title mb-1">Llistat de duels</h2>
                    <p class="recipe-form-subtitle mb-0">Filtra pels que més t'interessen i entra-hi per votar o per veure com van.</p>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    @foreach([
                        'tots' => 'Tots',
                        'iniciat' => 'Iniciats',
                        'finalitzat' => 'Finalitzats',
                        'peticio de cancelacio' => 'Peticions',
                        'cancelat' => 'Cancel·lats',
                    ] as $statusValue => $statusLabel)
                        <a
                            href="{{ route('duels.index', $statusValue !== 'tots' ? ['status' => $statusValue] : []) }}"
                            class="btn btn-sm rounded-pill fw-semibold px-3 py-2 text-decoration-none {{ $currentStatus === $statusValue ? 'btn-danger text-white' : 'btn-light text-secondary border' }}"
                        >
                            {{ $statusLabel }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="p-4 p-md-5">
                <div class="duel-cards-grid">
                    @forelse($duels as $duel)
                        @include('duels.partials.duel-card', ['duel' => $duel, 'context' => 'index'])
                    @empty
                        <div class="duel-empty-state">
                            <div class="duel-empty-icon">
                                <i class="bi bi-trophy"></i>
                            </div>
                            <h3 class="duel-empty-title">Encara no hi ha cap duel en marxa</h3>
                            <p class="duel-empty-text">Quan algú llanci el primer repte, el veuràs aquí i podràs seguir-lo des del primer vot.</p>
                            @auth
                                <a href="{{ route('duels.create') }}" class="btn-primary-ui">
                                    <i class="bi bi-plus-lg"></i>
                                    Crear el primer duel
                                </a>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>

            @if($duels->hasPages())
                <div class="d-flex justify-content-center border-top px-4 py-4 pagination-container">
                    {{ $duels->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection
