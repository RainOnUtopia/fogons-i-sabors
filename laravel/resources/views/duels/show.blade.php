@extends('layouts.app')

@section('content')
    @php
        $startDate = \Illuminate\Support\Carbon::parse($duelDto->startDate)->format('d/m/Y');
        $endDate = \Illuminate\Support\Carbon::parse($duelDto->endDate)->format('d/m/Y');
        $isVotingOpen = in_array($duelDto->status, ['iniciat', 'peticio de cancelacio'], true);
        $isParticipant = auth()->check() && in_array(auth()->id(), [$duelDto->challenger['id'], $duelDto->challenged['id']], true);
        $challengerIsWinner = $duelDto->winnerRecipeId === $duelDto->challengerRecipe['id'];
        $challengedIsWinner = $duelDto->winnerRecipeId === $duelDto->challengedRecipe['id'];
        $resultMessage = match (true) {
            $duelDto->status === 'cancelat' => 'Aquest duel s\'ha cancel·lat. Ja no es pot votar ni fer cap altra acció.',
            $duelDto->duelResult === 'guanyador' && $challengerIsWinner => $duelDto->challenger['name'] . ' s\'ha endut la victòria amb la recepta "' . $duelDto->challengerRecipe['title'] . '".',
            $duelDto->duelResult === 'guanyador' && $challengedIsWinner => $duelDto->challenged['name'] . ' s\'ha endut la victòria amb la recepta "' . $duelDto->challengedRecipe['title'] . '".',
            $duelDto->duelResult === 'empat' => 'El duel ha acabat en empat. Cap de les dues receptes ha aconseguit desmarcar-se.',
            $duelDto->status === 'peticio de cancelacio' => 'Hi ha una petició de cancel·lació en curs. Mentrestant, encara pots consultar com va el duel.',
            default => 'La votació és oberta. Si t\'agrada més una de les dues receptes, encara hi ets a temps de donar-li suport.',
        };
    @endphp

    <div class="section-ui">
        <div class="recipe-page-container-mb">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-start gap-4">
                <div class="flex-grow-1">
                    <div class="recipe-breadcrumb mb-3">
                        <a href="{{ route('duels.index') }}">Duels</a>
                        <span class="separator">/</span>
                        <span class="active">Detall</span>
                    </div>
                    <h1 class="recipe-show-title mb-2">{{ $duelDto->challenger['name'] }} vs {{ $duelDto->challenged['name'] }}</h1>
                    <p class="recipe-show-desc mb-0">Compara les dues receptes, mira com va la votació i decideix quina et convenç més.</p>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    @include('duels.partials.duel-status-badge', ['value' => $duelDto->status])
                    @if($duelDto->duelResult)
                        @include('duels.partials.duel-status-badge', ['value' => $duelDto->duelResult])
                    @endif
                </div>
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
                        <span>No hem pogut completar l'acció</span>
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

        <div class="recipe-page-container-mb card-ui duel-summary-card">
            <div class="duel-summary-banner">
                <div>
                    <p class="duel-battle-kicker mb-2">
                        <i class="bi bi-lightning-charge-fill"></i>
                        Duel en joc
                    </p>
                    <p class="duel-summary-label">Així està el duel ara mateix</p>
                    <h2 class="recipe-form-title mb-1">Com va el duel</h2>
                    <p class="recipe-form-subtitle mb-0">{{ $resultMessage }}</p>
                </div>
            </div>

            <div class="duel-summary-metrics">
                <div class="duel-summary-metric">
                    <div class="duel-summary-metric-icon">
                        <i class="bi bi-calendar2-week"></i>
                    </div>
                    <div class="duel-summary-metric-copy">
                        <span class="duel-summary-metric-label">Va començar el</span>
                        <strong>{{ $startDate }}</strong>
                        <span class="duel-summary-metric-note">Aquest és el dia que es va obrir el duel.</span>
                    </div>
                </div>
                <div class="duel-summary-metric">
                    <div class="duel-summary-metric-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="duel-summary-metric-copy">
                        <span class="duel-summary-metric-label">Es tanca el</span>
                        <strong>{{ $endDate }}</strong>
                        <span class="duel-summary-metric-note">A partir d'aquí ja no es podran enviar més vots.</span>
                    </div>
                </div>
                <div class="duel-summary-metric">
                    <div class="duel-summary-metric-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="duel-summary-metric-copy">
                        <span class="duel-summary-metric-label">Encara queden</span>
                        <strong>{{ $duelDto->daysRemaining }} {{ $duelDto->daysRemaining === 1 ? 'dia' : 'dies' }}</strong>
                        <span class="duel-summary-metric-note">Temps disponible perquè la comunitat continuï votant.</span>
                    </div>
                </div>
                <div class="duel-summary-metric">
                    <div class="duel-summary-metric-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="duel-summary-metric-copy">
                        <span class="duel-summary-metric-label">Ja s'han fet</span>
                        <strong>{{ $duelDto->totalVotes }} vots</strong>
                        <span class="duel-summary-metric-note">La suma de les valoracions rebudes per les dues receptes.</span>
                    </div>
                </div>
            </div>

            @include('duels.partials.duel-progress-bar', [
                'challengerName' => $duelDto->challenger['name'],
                'challengedName' => $duelDto->challenged['name'],
                'challengerAverage' => $duelDto->challengerAverageRating,
                'challengedAverage' => $duelDto->challengedAverageRating,
            ])

            @if($isParticipant)
                <div class="duel-summary-actions">
                    <a href="{{ route('duels.my-duels') }}" class="btn-secondary-ui">
                        <i class="bi bi-arrow-left"></i>
                        Tornar a la meva activitat
                    </a>

                    @if($duelDto->status === 'iniciat')
                        <form action="{{ route('duels.status.update', $duelDto->id) }}" method="POST" class="mb-0">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="peticio de cancelacio">
                            <button type="submit" class="btn-primary-ui">
                                <i class="bi bi-slash-circle"></i>
                                Demanar cancel·lació
                            </button>
                        </form>
                    @elseif($duelDto->status === 'peticio de cancelacio')
                        <span class="duel-card-note">Ja hi ha una petició de cancel·lació en curs.</span>
                    @endif
                </div>
            @endif
        </div>

        <div class="recipe-page-container card-ui p-4 p-md-5">
            <div class="duel-battle-stage">
                <div class="duel-stage-vs" aria-hidden="true">
                    <span>VS</span>
                </div>

                <div class="duel-show-grid">
                <div class="duel-entry-card duel-entry-card--challenger {{ $challengerIsWinner ? 'duel-entry-card--winner' : '' }}">
                    <div class="duel-entry-head">
                        <div class="d-flex align-items-center gap-3">
                            <div class="duel-entry-avatar">
                                @if($duelDto->challenger['avatar'])
                                    <img src="{{ asset('storage/' . $duelDto->challenger['avatar']) }}" alt="{{ $duelDto->challenger['name'] }}" class="avatar-ui w-100 h-100">
                                @else
                                    <span>{{ mb_strtoupper(mb_substr($duelDto->challenger['name'], 0, 1)) }}</span>
                                @endif
                            </div>
                            <div>
                                <p class="duel-card-role mb-1">Retador</p>
                                <h2 class="duel-entry-name mb-0">{{ $duelDto->challenger['name'] }}</h2>
                            </div>
                        </div>

                        @if($challengerIsWinner)
                            @include('duels.partials.duel-status-badge', ['value' => 'guanyador'])
                        @endif
                    </div>

                    <div class="duel-entry-image-wrap">
                        @if($duelDto->challengerRecipe['image'])
                            <img src="{{ asset('storage/' . $duelDto->challengerRecipe['image']) }}" alt="{{ $duelDto->challengerRecipe['title'] }}" class="duel-entry-image">
                        @else
                            <div class="duel-entry-image-placeholder">
                                <i class="bi bi-image"></i>
                            </div>
                        @endif
                    </div>

                    <div class="duel-entry-body">
                        <div class="duel-entry-title-wrap">
                            <h3 class="duel-entry-title">{{ $duelDto->challengerRecipe['title'] }}</h3>
                        </div>
                        <div class="duel-entry-stats">
                            <div class="duel-entry-stat">
                                <i class="bi bi-star-fill"></i>
                                <span>{{ number_format($duelDto->challengerAverageRating, 1) }}/5 de mitjana</span>
                            </div>
                            <div class="duel-entry-stat">
                                <i class="bi bi-bar-chart-fill"></i>
                                <span>{{ $duelDto->challengerVotesCount }} vots rebuts</span>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <a href="{{ route('recipes.show', $duelDto->challengerRecipe['id']) }}" class="btn-secondary-ui">
                                <i class="bi bi-book"></i>
                                Veure recepta completa
                            </a>
                        </div>

                        @include('duels.partials.duel-vote-panel', [
                            'duel' => $duelDto,
                            'recipe' => $duelDto->challengerRecipe,
                            'currentRating' => $userVotes[$duelDto->challengerRecipe['id']] ?? 0,
                            'isDisabled' => !$isVotingOpen,
                            'disabledMessage' => 'La votació d\'aquesta recepta està tancada perquè el duel ja no és actiu.',
                        ])
                    </div>
                </div>

                <div class="duel-entry-card duel-entry-card--challenged {{ $challengedIsWinner ? 'duel-entry-card--winner' : '' }}">
                    <div class="duel-entry-head">
                        <div class="d-flex align-items-center gap-3">
                            <div class="duel-entry-avatar duel-entry-avatar--challenged">
                                @if($duelDto->challenged['avatar'])
                                    <img src="{{ asset('storage/' . $duelDto->challenged['avatar']) }}" alt="{{ $duelDto->challenged['name'] }}" class="avatar-ui w-100 h-100">
                                @else
                                    <span>{{ mb_strtoupper(mb_substr($duelDto->challenged['name'], 0, 1)) }}</span>
                                @endif
                            </div>
                            <div>
                                <p class="duel-card-role mb-1">Reptat</p>
                                <h2 class="duel-entry-name mb-0">{{ $duelDto->challenged['name'] }}</h2>
                            </div>
                        </div>

                        @if($challengedIsWinner)
                            @include('duels.partials.duel-status-badge', ['value' => 'guanyador'])
                        @endif
                    </div>

                    <div class="duel-entry-image-wrap">
                        @if($duelDto->challengedRecipe['image'])
                            <img src="{{ asset('storage/' . $duelDto->challengedRecipe['image']) }}" alt="{{ $duelDto->challengedRecipe['title'] }}" class="duel-entry-image">
                        @else
                            <div class="duel-entry-image-placeholder">
                                <i class="bi bi-image"></i>
                            </div>
                        @endif
                    </div>

                    <div class="duel-entry-body">
                        <div class="duel-entry-title-wrap">
                            <h3 class="duel-entry-title">{{ $duelDto->challengedRecipe['title'] }}</h3>
                        </div>
                        <div class="duel-entry-stats">
                            <div class="duel-entry-stat">
                                <i class="bi bi-star-fill"></i>
                                <span>{{ number_format($duelDto->challengedAverageRating, 1) }}/5 de mitjana</span>
                            </div>
                            <div class="duel-entry-stat">
                                <i class="bi bi-bar-chart-fill"></i>
                                <span>{{ $duelDto->challengedVotesCount }} vots rebuts</span>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <a href="{{ route('recipes.show', $duelDto->challengedRecipe['id']) }}" class="btn-secondary-ui">
                                <i class="bi bi-book"></i>
                                Veure recepta completa
                            </a>
                        </div>

                        @include('duels.partials.duel-vote-panel', [
                            'duel' => $duelDto,
                            'recipe' => $duelDto->challengedRecipe,
                            'currentRating' => $userVotes[$duelDto->challengedRecipe['id']] ?? 0,
                            'isDisabled' => !$isVotingOpen,
                            'disabledMessage' => 'La votació d\'aquesta recepta està tancada perquè el duel ja no és actiu.',
                        ])
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection
