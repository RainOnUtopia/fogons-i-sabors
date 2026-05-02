@extends('layouts.app')

@section('content')
    <div class="section-ui duels-arena-page my-duels-arena-page">
        <div class="duels-arena-hero recipe-page-container-mb">
            <div>
                <p class="duels-arena-kicker">
                    <i class="bi bi-collection"></i>
                    La meva arena
                </p>
                <h1 class="duels-arena-title">Els meus duels</h1>
                <p class="duels-arena-subtitle">Segueix els duels que has obert i els reptes que t'han llan&ccedil;at.</p>
            </div>

            <div class="duels-arena-actions">
                <a href="{{ route('duels.index') }}" class="duels-arena-utility-link">
                    <i class="bi bi-arrow-left"></i>
                    Tornar al llistat
                </a>
                <a href="{{ route('duels.create') }}" class="duels-arena-utility-link duels-arena-utility-link--primary">
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
                                <span class="position-absolute start-0">&bull;</span>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="recipe-page-container-mb my-duels-panel">
            <ul class="my-duels-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button
                        class="my-duels-tab {{ $activeTab === 'created' ? 'active' : '' }}"
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
                        class="my-duels-tab {{ $activeTab === 'received' ? 'active' : '' }}"
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
                                <p class="duel-empty-text">Quan en creis un, el podras seguir aqui i veure com avan&ccedil;a la votacio.</p>
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
                                <p class="duel-empty-text">Quan algu et repti, el trobaras aqui per seguir el duel i entrar directament al detall.</p>
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
