@extends('layouts.app')

@section('content')
    <div class="section-ui">
        <div class="recipe-page-container-mb d-flex flex-column flex-lg-row justify-content-between align-items-lg-start gap-4">
            <div class="flex-grow-1">
                <h1 class="recipe-hero-title">Els meus duels</h1>
                <p class="recipe-hero-subtitle">Des d'aquí pots seguir els duels que has obert i els reptes que t'han llançat.</p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('duels.index') }}" class="btn-secondary-ui">
                    <i class="bi bi-arrow-left"></i>
                    Tornar al llistat
                </a>
                <a href="{{ route('duels.create') }}" class="btn-primary-ui">
                    <i class="bi bi-plus-lg"></i>
                    Crear duel
                </a>
            </div>
        </div>

        <div class="recipe-page-container-mb">
            @if(session('success'))
                <div class="alert alert-success shadow-sm border-0 rounded-4 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="recipe-alert-error mb-4">
                    <div class="d-flex align-items-center gap-2 fw-semibold" style="font-size: 14px;">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>Revisa les dades del duel</span>
                    </div>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-1" style="font-size: 13px;">
                        @foreach($errors->all() as $error)
                            <li class="position-relative lh-base ps-3">
                                <span class="position-absolute start-0">•</span>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="recipe-page-container-mb card-ui p-4 p-md-5">
            <ul class="nav nav-tabs mb-4 profile-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link {{ $activeTab === 'created' ? 'active' : '' }} fw-bold"
                        id="duels-created-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#duels-created-pane"
                        type="button"
                        role="tab"
                        aria-controls="duels-created-pane"
                        aria-selected="{{ $activeTab === 'created' ? 'true' : 'false' }}"
                    >
                        Creats ({{ $createdDuels->total() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link {{ $activeTab === 'received' ? 'active' : '' }} fw-bold"
                        id="duels-received-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#duels-received-pane"
                        type="button"
                        role="tab"
                        aria-controls="duels-received-pane"
                        aria-selected="{{ $activeTab === 'received' ? 'true' : 'false' }}"
                    >
                        Rebuts ({{ $receivedDuels->total() }})
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade {{ $activeTab === 'created' ? 'show active' : '' }}" id="duels-created-pane" role="tabpanel" aria-labelledby="duels-created-tab" tabindex="0">
                    <div class="duel-cards-grid">
                        @forelse($createdDuels as $duel)
                            @include('duels.partials.duel-card', [
                                'duel' => $duel,
                                'context' => 'my-duels',
                                'showParticipantActions' => true,
                            ])
                        @empty
                            <div class="duel-empty-state duel-empty-state--compact">
                                <div class="duel-empty-icon">
                                    <i class="bi bi-lightning-charge"></i>
                                </div>
                                <h3 class="duel-empty-title">Encara no has obert cap duel</h3>
                                <p class="duel-empty-text">Quan en creïs un, el podràs seguir aquí i veure com avança la votació.</p>
                                <a href="{{ route('duels.create') }}" class="btn-primary-ui">
                                    <i class="bi bi-plus-lg"></i>
                                    Crear duel
                                </a>
                            </div>
                        @endforelse
                    </div>

                    @if($createdDuels->hasPages())
                        <div class="d-flex justify-content-center mt-4 pagination-container">
                            {{ $createdDuels->appends(['tab' => 'created'])->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>

                <div class="tab-pane fade {{ $activeTab === 'received' ? 'show active' : '' }}" id="duels-received-pane" role="tabpanel" aria-labelledby="duels-received-tab" tabindex="0">
                    <div class="duel-cards-grid">
                        @forelse($receivedDuels as $duel)
                            @include('duels.partials.duel-card', [
                                'duel' => $duel,
                                'context' => 'my-duels',
                                'showParticipantActions' => true,
                            ])
                        @empty
                            <div class="duel-empty-state duel-empty-state--compact">
                                <div class="duel-empty-icon">
                                    <i class="bi bi-inbox"></i>
                                </div>
                                <h3 class="duel-empty-title">Encara no has rebut cap repte</h3>
                                <p class="duel-empty-text">Quan algú et repti, el trobaràs aquí per seguir el duel i entrar directament al detall.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($receivedDuels->hasPages())
                        <div class="d-flex justify-content-center mt-4 pagination-container">
                            {{ $receivedDuels->appends(['tab' => 'received'])->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
