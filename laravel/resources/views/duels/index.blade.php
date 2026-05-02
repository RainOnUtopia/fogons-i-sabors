@extends('layouts.app')

@section('content')
    <div class="section-ui duels-arena-page">
        <div class="duels-arena-hero recipe-page-container-mb">
            <div>
                <p class="duels-arena-kicker">
                    <i class="bi bi-crosshair"></i>
                    Arena en viu
                </p>
                <h1 class="duels-arena-title">Els Duels</h1>
                <p class="duels-arena-subtitle">Mira els mestres competir. El teu vot decideix el guanyador.</p>
            </div>

            <div class="duels-arena-actions">
                @auth
                    <a href="{{ route('duels.my-duels') }}" class="duels-arena-utility-link">
                        <i class="bi bi-collection"></i>
                        Els meus duels
                    </a>
                    <a href="{{ route('duels.create') }}" class="duels-arena-utility-link duels-arena-utility-link--primary">
                        <i class="bi bi-plus-lg"></i>
                        Crear duel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="duels-arena-utility-link duels-arena-utility-link--primary">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Inicia sessio per competir
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

        <div class="recipe-page-container-mb">
            <div class="duels-arena-tabs">
                <div class="duels-arena-tab-group">
                    @foreach([
                        'tots' => 'Tots',
                        'iniciat' => 'Actius',
                        'finalitzat' => 'Historial',
                        'peticio de cancelacio' => 'Peticions',
                        'cancelat' => 'Cancelats',
                    ] as $statusValue => $statusLabel)
                        <a
                            href="{{ route('duels.index', $statusValue !== 'tots' ? ['status' => $statusValue] : []) }}"
                            class="duels-arena-tab {{ $currentStatus === $statusValue ? 'is-active' : '' }}"
                        >
                            {{ $statusLabel }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="duel-cards-grid">
                @forelse($duels as $duel)
                    @include('duels.partials.duel-card', ['duel' => $duel, 'context' => 'index'])
                @empty
                    <div class="duel-empty-state">
                        <div class="duel-empty-icon">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <h3 class="duel-empty-title">Encara no hi ha cap duel en marxa</h3>
                        <p class="duel-empty-text">Quan algu llanci el primer repte, el veuras aqui i podras seguir-lo des del primer vot.</p>
                        @auth
                            <a href="{{ route('duels.create') }}" class="btn-primary-ui">
                                <i class="bi bi-plus-lg"></i>
                                Crear el primer duel
                            </a>
                        @endauth
                    </div>
                @endforelse
            </div>

            @if($duels->hasPages())
                <div class="d-flex justify-content-center px-4 py-4 pagination-container">
                    {{ $duels->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection
