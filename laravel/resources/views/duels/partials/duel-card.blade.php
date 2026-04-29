@php
    $context = $context ?? 'index';
    $showParticipantActions = $showParticipantActions ?? false;
    $end = \Illuminate\Support\Carbon::parse($duel->endDate);
    $now = now();
    $remaining = $end->isFuture() ? $now->diff($end) : null;
    $remainingLabel = $remaining
        ? trim(($remaining->days ? $remaining->days . 'd ' : '') . ($remaining->h ? $remaining->h . 'h ' : '') . ($remaining->i ? $remaining->i . 'm' : ''))
        : 'Tancat';
    $remainingLabel = $remainingLabel !== '' ? $remainingLabel : 'Ara';
    $challengerSupport = min(100, max(0, ($duel->challengerAverageRating / 5) * 100));
    $challengedSupport = min(100, max(0, ($duel->challengedAverageRating / 5) * 100));
@endphp

<article class="duel-card">
    <div class="duel-card-stage">
        <div class="duel-card-versus">
            <div class="duel-card-entrant duel-card-entrant--challenger">
                <div class="duel-card-recipe-box" @if($duel->challengerRecipeImage) style="background-image: url('{{ asset('storage/' . $duel->challengerRecipeImage) }}');" @endif>
                    @unless($duel->challengerRecipeImage)
                        <i class="bi bi-image"></i>
                    @endunless
                </div>

                <div class="duel-card-copy">
                    <h3 class="duel-card-recipe">{{ $duel->challengerRecipeTitle }}</h3>
                    <p class="duel-card-name">per {{ $duel->challengerName }}</p>
                </div>

                <div class="duel-card-support">
                    <div class="duel-card-support-head">
                        <span>Suport</span>
                        <span>{{ round($challengerSupport) }}%</span>
                    </div>
                    <div class="duel-card-support-track">
                        <span style="width: {{ $challengerSupport }}%;"></span>
                    </div>
                </div>
            </div>

            <div class="duel-card-vs-wrap" aria-hidden="true">
                <div class="duel-card-vs">VS</div>
                <div class="duel-card-time">
                    <i class="bi bi-clock"></i>
                    {{ $remainingLabel }}
                </div>
            </div>

            <div class="duel-card-entrant duel-card-entrant--challenged">
                <div class="duel-card-recipe-box" @if($duel->challengedRecipeImage) style="background-image: url('{{ asset('storage/' . $duel->challengedRecipeImage) }}');" @endif>
                    @unless($duel->challengedRecipeImage)
                        <i class="bi bi-image"></i>
                    @endunless
                </div>

                <div class="duel-card-copy">
                    <h3 class="duel-card-recipe">{{ $duel->challengedRecipeTitle }}</h3>
                    <p class="duel-card-name">per {{ $duel->challengedName }}</p>
                </div>

                <div class="duel-card-support">
                    <div class="duel-card-support-head">
                        <span>Suport</span>
                        <span>{{ round($challengedSupport) }}%</span>
                    </div>
                    <div class="duel-card-support-track">
                        <span style="width: {{ $challengedSupport }}%;"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($duel->duelResult === 'guanyador' && $duel->winner)
        <div class="duel-card-result">
            @include('duels.partials.duel-status-badge', ['value' => 'guanyador'])
            <p class="mb-0">
                {{ $duel->winner['user_name'] }} s'ha endut el duel amb
                <strong>{{ $duel->winner['recipe_title'] }}</strong>.
            </p>
        </div>
    @elseif($duel->duelResult === 'empat')
        <div class="duel-card-result">
            @include('duels.partials.duel-status-badge', ['value' => 'empat'])
            <p class="mb-0">Aquest duel ha acabat en empat.</p>
        </div>
    @endif

    <div class="duel-card-actions">
        <a href="{{ route('duels.show', $duel->id) }}" class="duel-card-main-link">
            Entrar a l'Arena
            <i class="bi bi-arrow-right"></i>
        </a>

        @if($showParticipantActions)
            @if($duel->status === 'iniciat')
                <form action="{{ route('duels.status.update', $duel->id) }}" method="POST" class="mb-0">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="peticio de cancelacio">
                    <button type="submit" class="btn-primary-ui duel-card-action-btn">
                        <i class="bi bi-slash-circle"></i>
                        Demanar cancelacio
                    </button>
                </form>
            @elseif($duel->status === 'peticio de cancelacio')
                <span class="duel-card-note">La peticio ja s'ha enviat i esta pendent de revisio.</span>
            @endif
        @endif
    </div>
</article>
