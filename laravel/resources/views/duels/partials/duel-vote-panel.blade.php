@php
    $currentRating = $currentRating ?? 0;
    $isDisabled = $isDisabled ?? false;
    $disabledMessage = $disabledMessage ?? null;
@endphp

<div class="duel-vote-panel {{ $isDisabled ? 'duel-vote-panel--disabled' : '' }}">
    <div class="duel-vote-panel-head">
        <h3 class="duel-vote-title">Quina nota li dones?</h3>
        <p class="duel-vote-text mb-0">
            @if($currentRating > 0)
                Ara mateix li has donat {{ $currentRating }} estrelles.
            @else
                Encara no l'has puntuada.
            @endif
        </p>
    </div>

    @auth
        @if(!$isDisabled)
            <form action="{{ route('duels.vote', $duel->id) }}" method="POST" class="d-flex flex-column gap-3">
                @csrf
                <input type="hidden" name="recipe_id" value="{{ $recipe['id'] }}">

                <div class="duel-star-rating" role="radiogroup" aria-label="Valora {{ $recipe['title'] }}">
                    @for($rating = 5; $rating >= 1; $rating--)
                        @php
                            $inputId = 'duel-' . $duel->id . '-recipe-' . $recipe['id'] . '-rating-' . $rating;
                        @endphp
                        <input
                            class="duel-star-input"
                            type="radio"
                            name="rating"
                            id="{{ $inputId }}"
                            value="{{ $rating }}"
                            {{ $currentRating === $rating ? 'checked' : '' }}
                            required
                        >
                        <label class="duel-star-label" for="{{ $inputId }}" title="{{ $rating }} estrelles">
                            <i class="bi bi-star-fill"></i>
                        </label>
                    @endfor
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <button type="submit" class="btn-primary-ui">
                        <i class="bi bi-hand-thumbs-up-fill"></i>
                        {{ $currentRating > 0 ? 'Desar la nova nota' : 'Enviar la meva nota' }}
                    </button>
                    <span class="duel-vote-helper">Si canvies d'opinió, pots tornar a puntuar mentre el duel continuï obert.</span>
                </div>
            </form>
        @else
            <p class="duel-vote-helper mb-0">{{ $disabledMessage }}</p>
        @endif
    @else
        @if(!$isDisabled)
            <div class="d-flex flex-wrap align-items-center gap-2">
                <a href="{{ route('login') }}" class="btn-secondary-ui">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Inicia sessió per votar-la
                </a>
                <span class="duel-vote-helper">Quan entris, podràs donar la teva nota i ajudar a decidir el duel.</span>
            </div>
        @else
            <p class="duel-vote-helper mb-0">{{ $disabledMessage }}</p>
        @endif
    @endauth
</div>
