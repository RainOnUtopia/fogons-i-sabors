@php
    $badgeConfig = [
        'iniciat' => ['class' => 'duel-status-badge duel-status-badge--iniciat', 'icon' => 'bi-play-circle-fill', 'label' => 'Iniciat'],
        'finalitzat' => ['class' => 'duel-status-badge duel-status-badge--finalitzat', 'icon' => 'bi-check-circle-fill', 'label' => 'Finalitzat'],
        'peticio de cancelacio' => ['class' => 'duel-status-badge duel-status-badge--peticio', 'icon' => 'bi-exclamation-circle-fill', 'label' => 'Petició de cancel·lació'],
        'cancelat' => ['class' => 'duel-status-badge duel-status-badge--cancelat', 'icon' => 'bi-x-circle-fill', 'label' => 'Cancel·lat'],
        'empat' => ['class' => 'duel-status-badge duel-status-badge--empat', 'icon' => 'bi-slash-circle-fill', 'label' => 'Empat'],
        'guanyador' => ['class' => 'duel-status-badge duel-status-badge--guanyador', 'icon' => 'bi-trophy-fill', 'label' => 'Guanyador'],
    ];

    $badge = $badgeConfig[$value] ?? ['class' => 'duel-status-badge', 'icon' => 'bi-circle-fill', 'label' => ucfirst($value)];
@endphp

<span class="{{ $badge['class'] }}">
    <i class="bi {{ $badge['icon'] }}"></i>
    {{ $badge['label'] }}
</span>
