@extends('layouts.admin')

@section('title', 'Moderaci&oacute; de Duels')

@section('content')
<div class="admin-duels-page">
    <div class="admin-duels-shell">
        <div class="admin-duels-topbar">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="admin-duels-back">
                    <i class="bi bi-arrow-left"></i>
                    Panell Admin
                </a>
                <h1 class="admin-duels-title">Moderaci&oacute; de Duels</h1>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success shadow-sm border-0 rounded-4 mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger shadow-sm border-0 rounded-4 mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger shadow-sm border-0 rounded-4 mb-4" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="admin-duels-stats">
            <article class="admin-duels-stat">
                <i class="bi bi-crosshair admin-duels-stat-icon admin-duels-stat-icon--danger"></i>
                <strong>{{ $duelStats['active'] ?? 0 }}</strong>
                <span>Duels actius</span>
            </article>
            <article class="admin-duels-stat">
                <i class="bi bi-exclamation-triangle admin-duels-stat-icon admin-duels-stat-icon--warning"></i>
                <strong>{{ $duelStats['pending'] ?? $duels->total() }}</strong>
                <span>Pendent d'aprovaci&oacute;</span>
            </article>
            <article class="admin-duels-stat">
                <i class="bi bi-check-circle admin-duels-stat-icon admin-duels-stat-icon--success"></i>
                <strong>{{ $duelStats['completedToday'] ?? 0 }}</strong>
                <span>Completats avui</span>
            </article>
        </div>

        <section class="admin-duels-panel">
            <div class="admin-duels-panel-head">
                <h2>Sol&middot;licituds de Duel Pendents</h2>
            </div>

            <div class="admin-duels-requests">
                @forelse($duels as $duel)
                    <article class="admin-duels-request">
                        <div class="admin-duels-avatars" aria-hidden="true">
                            <div class="admin-duels-avatar">
                                @if($duel->challengerAvatar)
                                    <img src="{{ asset('storage/' . $duel->challengerAvatar) }}" alt="">
                                @else
                                    <span>{{ mb_strtoupper(mb_substr($duel->challengerName, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="admin-duels-avatar admin-duels-avatar--second">
                                @if($duel->challengedAvatar)
                                    <img src="{{ asset('storage/' . $duel->challengedAvatar) }}" alt="">
                                @else
                                    <span>{{ mb_strtoupper(mb_substr($duel->challengedName, 0, 1)) }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="admin-duels-request-copy">
                            <h3>{{ $duel->challengerRecipeTitle }} vs {{ $duel->challengedRecipeTitle }}</h3>
                            <p>
                                Sol&middot;licitat per {{ $duel->challengerName }}
                                <span>&middot;</span>
                                Duel #{{ $duel->id }}
                            </p>
                        </div>

                        <div class="admin-duels-request-actions">
                            <a href="{{ route('duels.show', $duel->id) }}" target="_blank" rel="noopener" class="admin-duels-action admin-duels-action--ghost">
                                Veure
                            </a>

                            <form action="{{ route('duels.status.update', $duel->id) }}" method="POST" class="m-0">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="iniciat">
                                <button type="submit" class="admin-duels-action admin-duels-action--ghost" onclick="return confirm('Segur que vols rebutjar la petici&oacute;? El duel tornar&agrave; a estar iniciat.')">
                                    Declinar
                                </button>
                            </form>

                            <form action="{{ route('duels.status.update', $duel->id) }}" method="POST" class="m-0">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="cancelat">
                                <button type="submit" class="admin-duels-action admin-duels-action--primary" onclick="return confirm('Segur que vols aprovar la cancel&middot;laci&oacute;? El duel quedar&agrave; cancel&middot;lat definitivament.')">
                                    Aprovar
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="admin-duels-empty">
                        <i class="bi bi-check-circle"></i>
                        <h3>No hi ha cap petici&oacute; pendent</h3>
                        <p>Quan arribi una nova sol&middot;licitud de cancel&middot;laci&oacute;, apareixer&agrave; aqu&iacute;.</p>
                    </div>
                @endforelse
            </div>

            @if($duels->hasPages())
                <div class="admin-duels-pagination pagination-container">
                    {{ $duels->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
