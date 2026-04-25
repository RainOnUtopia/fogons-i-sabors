@php
    $combinedAverage = $challengerAverage + $challengedAverage;
    $challengerWidth = $combinedAverage > 0 ? ($challengerAverage / $combinedAverage) * 100 : 50;
    $challengedWidth = 100 - $challengerWidth;
@endphp

<div class="duel-progress-card">
    <div class="duel-progress-head">
        <div>
            <p class="duel-progress-caption mb-1">Així va ara mateix</p>
            <h3 class="duel-progress-title mb-0">Com va la votació</h3>
        </div>
        <div class="duel-progress-meta">
            <span>{{ number_format($challengerAverage, 1) }}/5</span>
            <span>{{ number_format($challengedAverage, 1) }}/5</span>
        </div>
    </div>

    <div class="duel-progress-track-shell" aria-hidden="true">
        <div class="duel-progress-track">
            <div class="duel-progress-fill duel-progress-fill--challenger" style="width: {{ $challengerWidth }}%;"></div>
            <div class="duel-progress-fill duel-progress-fill--challenged" style="width: {{ $challengedWidth }}%;"></div>
        </div>
        <div class="duel-progress-vs">
            <span>VS</span>
        </div>
    </div>

    <div class="duel-progress-labels">
        <span class="duel-progress-label duel-progress-label--challenger">{{ $challengerName }}</span>
        <span class="duel-progress-label duel-progress-label--challenged">{{ $challengedName }}</span>
    </div>
</div>
