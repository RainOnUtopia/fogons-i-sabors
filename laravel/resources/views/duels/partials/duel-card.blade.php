@php
    $context = $context ?? 'index';
    $showParticipantActions = $showParticipantActions ?? false;
    $endDate = \Illuminate\Support\Carbon::parse($duel->endDate)->format('d/m/Y');
    $challengerInitial = mb_strtoupper(mb_substr($duel->challengerName, 0, 1));
    $challengedInitial = mb_strtoupper(mb_substr($duel->challengedName, 0, 1));
@endphp

<article class="duel-card">
    <div class="duel-card-head">
        @include('duels.partials.duel-status-badge', ['value' => $duel->status])
        <span class="duel-card-date">
            <i class="bi bi-calendar-event"></i>
            Es tanca el {{ $endDate }}
        </span>
    </div>

    <div class="duel-card-stage">
        <div class="duel-card-stage-glow duel-card-stage-glow--challenger" aria-hidden="true"></div>
        <div class="duel-card-stage-glow duel-card-stage-glow--challenged" aria-hidden="true"></div>

        <div class="duel-card-versus">
            <div class="duel-card-entrant duel-card-entrant--challenger">
                <div class="duel-card-entrant-top">
                    <div class="duel-card-avatar">
                        @if($duel->challengerAvatar)
                            <img src="{{ asset('storage/' . $duel->challengerAvatar) }}" alt="{{ $duel->challengerName }}"
                                class="avatar-ui w-100 h-100">
                        @else
                            <span>{{ $challengerInitial }}</span>
                        @endif
                    </div>
                    <div class="duel-card-identity">
                        <p class="duel-card-role">Retador</p>
                        <h3 class="duel-card-name">{{ $duel->challengerName }}</h3>
                    </div>
                </div>

                <div class="duel-card-recipe-box" @if($duel->challengerRecipeImage)
                style="background-image: url('{{ asset('storage/' . $duel->challengerRecipeImage) }}'); " @endif>
                    <p class="duel-card-recipe-label" @if($duel->challengerRecipeImage) style="color: #e2e8f0;" @endif>
                        Recepta</p>
                    <p class="duel-card-recipe" @if($duel->challengerRecipeImage)
                    style="color: #ffffff; text-shadow: 1px 1px 3px rgba(0,0,0,0.9);" @endif>
                        {{ $duel->challengerRecipeTitle }}
                    </p>
                </div>

                <div class="duel-card-score-chip">
                    <i class="bi bi-star-fill"></i>
                    <span>{{ number_format($duel->challengerAverageRating, 1) }}/5</span>
                </div>
            </div>

            <div class="duel-card-vs-wrap" aria-hidden="true">
                <div class="duel-card-vs">VS</div>
            </div>

            <div class="duel-card-entrant duel-card-entrant--challenged">
                <div class="duel-card-entrant-top">
                    <div class="duel-card-avatar duel-card-avatar--challenged">
                        @if($duel->challengedAvatar)
                            <img src="{{ asset('storage/' . $duel->challengedAvatar) }}" alt="{{ $duel->challengedName }}"
                                class="avatar-ui w-100 h-100">
                        @else.
                            <span>{{ $challengedInitial }}</span>
                        @endif
                    </div>
                    <div class="duel-card-identity">
                        <p class="duel-card-role">Reptat</p>
                        <h3 class="duel-card-name">{{ $duel->challengedName }}</h3>
                    </div>
                </div>

                <div class="duel-card-recipe-box duel-card-recipe-box--challenged" @if($duel->challengedRecipeImage)
                style="background-image: url('{{ asset('storage/' . $duel->challengedRecipeImage) }}');" @endif>
                    <p class="duel-card-recipe-label" @if($duel->challengedRecipeImage) style="color: #e2e8f0;" @endif>
                        Recepta</p>
                    <p class="duel-card-recipe" @if($duel->challengedRecipeImage)
                    style="color: #ffffff; text-shadow: 1px 1px 3px rgba(0,0,0,0.9);" @endif>
                        {{ $duel->challengedRecipeTitle }}
                    </p>
                </div>

                <div class="duel-card-score-chip duel-card-score-chip--challenged">
                    <i class="bi bi-star-fill"></i>
                    <span>{{ number_format($duel->challengedAverageRating, 1) }}/5</span>
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
        <a href="{{ route('duels.show', $duel->id) }}" class="btn-secondary-ui duel-card-main-link">
            <i class="bi bi-eye"></i>
            Veure duel
        </a>

        @if($showParticipantActions)
            @if($duel->status === 'iniciat')
                <form action="{{ route('duels.status.update', $duel->id) }}" method="POST" class="mb-0">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="peticio de cancelacio">
                    <button type="submit" class="btn-primary-ui duel-card-action-btn">
                        <i class="bi bi-slash-circle"></i>
                        Demanar cancel·lació
                    </button>
                </form>
            @elseif($duel->status === 'peticio de cancelacio')
                <span class="duel-card-note">La petició ja s'ha enviat i està pendent de revisió.</span>
            @endif
        @elseif($context === 'index' && auth()->check())
            <a href="{{ route('duels.my-duels') }}" class="duel-card-link-inline d-none">
                <i class="bi bi-arrow-right-circle"></i>
                Veure els meus duels
            </a>
        @endif
    </div>
</article>