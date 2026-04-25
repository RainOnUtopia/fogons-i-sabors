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

        <div class="recipe-page-container-mb card-ui p-4 p-md-5">
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
        <div class="recipe-page-container card-ui comment-card p-4 p-md-5">
            <h3 class="mb-4"><i class="bi bi-chat-text"></i> Comentaris (<span id="comments-count">{{ $duelDto->commentsCount }}</span>)</h3>

            <!-- Comment form for Authenticated Users -->
            @auth
            <div class="mb-4 p-3 bg-light rounded shadow-sm ">
                <form id="main-comment-form">
                    @csrf
                    <div class="mb-2">
                        <textarea class="form-control focus-ring" name="content" id="main-comment-content" rows="3" placeholder="Escriu el teu comentari..." required></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary" id="main-comment-submit">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Publicar
                        </button>
                    </div>
                </form>
            </div>
            @else
            <div class="alert alert-info shadow-sm steps-card">
                Si us plau, <a href="{{ route('login') }}" class="fw-bold text-primary">inicia sessió</a> per deixar un comentari.
            </div>
            @endauth

            <div id="comments-container" class="d-flex flex-column gap-3">
                @foreach($duelDto->topLevelComments as $comment)
                    <div class="comment-item {{ $comment->is_deleted ? 'opacity-75' : '' }}" id="comment-{{ $comment->id }}" data-id="{{ $comment->id }}">
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                @if($comment->user?->avatar)
                                    <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="{{ $comment->user->name }}" class="rounded-circle object-fit-cover shadow-sm" style="width: 45px; height: 45px;">
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold bg-primary shadow-sm" style="width: 45px; height: 45px;">
                                        {{ substr($comment->user?->name ?? '?', 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-grow-1">
                                <div class="bg-white border p-3 rounded shadow-sm position-relative">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="fw-bold text-dark">{{ $comment->user?->name }}</div>
                                        <div class="text-muted small">{{ $comment->created_at->diffForHumans() }}</div>
                                    </div>
                                    <div class="comment-content text-break text-secondary" id="comment-content-{{ $comment->id }}" style="white-space: pre-wrap;">{{ $comment->content }}</div>
                                </div>
                                
                                <div class="d-flex gap-3 mt-1 ms-2 small fw-medium">
                                    @auth
                                        @if(!$comment->is_deleted)
                                            <button type="button" class="btn btn-link p-0 text-decoration-none text-primary btn-reply" data-id="{{ $comment->id }}">Respon</button>
                                            @if(auth()->id() === $comment->user_id)
                                                <button type="button" class="btn btn-link p-0 text-decoration-none text-primary btn-edit" data-id="{{ $comment->id }}">Edita</button>
                                            @endif
                                            @if(auth()->id() === $comment->user_id || auth()->user()->role === 'admin')
                                                <button type="button" class="btn btn-link p-0 text-decoration-none text-danger btn-delete" data-id="{{ $comment->id }}">Elimina</button>
                                            @endif
                                        @endif
                                    @endauth
                                </div>

                                <div class="replies-container mt-3 d-flex flex-column gap-3 ms-4" id="replies-{{ $comment->id }}">
                                    @foreach($comment->replies as $reply)
                                        <div class="reply-item {{ $reply->is_deleted ? 'opacity-75' : '' }}" id="comment-{{ $reply->id }}" data-id="{{ $reply->id }}">
                                            <div class="d-flex gap-3">
                                                <div class="flex-shrink-0">
                                                    @if($reply->user?->avatar)
                                                        <img src="{{ asset('storage/' . $reply->user->avatar) }}" alt="{{ $reply->user->name }}" class="rounded-circle object-fit-cover shadow-sm" style="width: 35px; height: 35px;">
                                                    @else
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold bg-secondary shadow-sm" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                                            {{ substr($reply->user?->name ?? '?', 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="bg-light border p-2 rounded shadow-sm position-relative">
                                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                                            <div class="fw-bold small text-dark">{{ $reply->user?->name }}</div>
                                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $reply->created_at->diffForHumans() }}</div>
                                                        </div>
                                                        <div class="comment-content text-break text-secondary small" id="comment-content-{{ $reply->id }}" style="white-space: pre-wrap;">{{ $reply->content }}</div>
                                                    </div>
                                                    
                                                    <div class="d-flex gap-3 mt-1 ms-2" style="font-size: 0.8rem;">
                                                        @auth
                                                            @if(!$reply->is_deleted)
                                                                @if(auth()->id() === $reply->user_id)
                                                                    <button type="button" class="btn btn-link p-0 text-decoration-none text-primary btn-edit" data-id="{{ $reply->id }}">Edita</button>
                                                                @endif
                                                                @if(auth()->id() === $reply->user_id || auth()->user()->role === 'admin')
                                                                    <button type="button" class="btn btn-link p-0 text-decoration-none text-danger btn-delete" data-id="{{ $reply->id }}">Elimina</button>
                                                                @endif
                                                            @endif
                                                        @endauth
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function escapeHtml(unsafe) {
    return (unsafe || "").toString()
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
}

document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const storeUrl = `{{ route('duels.comments.store', $duelDto->id) }}`;
    const mainForm = document.getElementById('main-comment-form');
    
    // Formulario principal
    if (mainForm) {
        mainForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const contentInput = document.getElementById('main-comment-content');
            const submitBtn = document.getElementById('main-comment-submit');
            const spinner = submitBtn.querySelector('.spinner-border');
            
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            
            try {
                const response = await fetch(storeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ content: contentInput.value })
                });
                
                const data = await response.json();
                if (data.success) {
                    contentInput.value = '';
                    appendComment(data.comment, false);
                    updateCommentCount(1);
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (err) {
                console.error(err);
                alert('Error de connexió');
            } finally {
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
            }
        });
    }

    // Event Delegation para Botones Reaccionar
    const commentsContainer = document.getElementById('comments-container');
    if (commentsContainer) {
        commentsContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-delete')) {
                handleDelete(e.target.dataset.id, e.target);
            } else if (e.target.classList.contains('btn-edit')) {
                handleEdit(e.target.dataset.id, e.target);
            } else if (e.target.classList.contains('btn-reply')) {
                handleReply(e.target.dataset.id, e.target);
            }
        });
    }

    async function handleDelete(commentId, btn) {
        if (!confirm('Segur que vols eliminar aquest comentari?')) return;
        
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        btn.disabled = true;
        
        try {
            const response = await fetch(`/duel-comments/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            if (data.success) {
                const contentDiv = document.getElementById(`comment-content-${commentId}`);
                contentDiv.innerText = data.comment.content;
                
                // Hide actions
                const actionsDiv = btn.closest('.d-flex.gap-3');
                if (actionsDiv) actionsDiv.innerHTML = '';
            } else {
                alert('Error: ' + data.message);
                btn.innerText = 'Elimina';
                btn.disabled = false;
            }
        } catch (err) {
            console.error(err);
            alert('Error de connexió');
            btn.innerText = 'Elimina';
            btn.disabled = false;
        }
    }

    function handleEdit(commentId, btn) {
        const contentDiv = document.getElementById(`comment-content-${commentId}`);
        const currentText = contentDiv.innerText.trim();
        
        // Reemplazar div por form edición
        contentDiv.innerHTML = `
            <textarea class="form-control mb-2 mt-2 border-primary" id="edit-input-${commentId}" rows="2" required>${currentText}</textarea>
            <div class="text-end">
                <button type="button" class="btn btn-sm btn-outline-secondary me-2 btn-cancel-edit" data-id="${commentId}" data-original="${currentText}">Cancel·la</button>
                <button type="button" class="btn btn-sm btn-primary btn-save-edit" data-id="${commentId}">Desa</button>
            </div>
        `;
        
        const actionsDiv = document.querySelector(`#comment-${commentId} .btn-edit`).closest('.d-flex.gap-3');
        if (actionsDiv) actionsDiv.classList.add('d-none');

        // Manejadores Cancel/Save
        const textEditBox = contentDiv.querySelector('.btn-cancel-edit');
        textEditBox.addEventListener('click', (e) => {
            contentDiv.innerText = e.target.dataset.original;
            if (actionsDiv) actionsDiv.classList.remove('d-none');
        });

        const saveBtn = contentDiv.querySelector('.btn-save-edit');
        saveBtn.addEventListener('click', async (e) => {
            const newText = document.getElementById(`edit-input-${commentId}`).value;
            if (!newText.trim()) return;
            
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            saveBtn.disabled = true;

            try {
                const response = await fetch(`/duel-comments/${commentId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ content: newText })
                });
                const data = await response.json();
                if (data.success) {
                    contentDiv.innerText = data.comment.content;
                    if (actionsDiv) actionsDiv.classList.remove('d-none');
                } else {
                    alert('Error: ' + data.message);
                    saveBtn.innerText = 'Desa';
                    saveBtn.disabled = false;
                }
            } catch (err) {
                console.error(err);
                alert('Error de connexió');
                saveBtn.innerText = 'Desa';
                saveBtn.disabled = false;
            }
        });
    }

    function handleReply(parentId, btn) {
        document.querySelectorAll('.reply-form-container').forEach(el => el.remove());

        const actionsDiv = document.querySelector(`#comment-${parentId} > .d-flex > .flex-grow-1 > .d-flex.gap-3`);

        const formHtml = `
            <div class="reply-form-container mt-2 mb-3 bg-light p-2 rounded border">
                <form id="reply-form-${parentId}">
                    <textarea class="form-control mb-2" id="reply-input-${parentId}" rows="2" placeholder="Escriu la teva resposta..." required></textarea>
                    <div class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-secondary me-2 btn-cancel-reply">Cancel·la</button>
                        <button type="submit" class="btn btn-sm btn-primary btn-submit-reply">Respon</button>
                    </div>
                </form>
            </div>
        `;
        
        actionsDiv.insertAdjacentHTML('afterend', formHtml);
        const formContainer = actionsDiv.nextElementSibling;
        
        formContainer.querySelector('.btn-cancel-reply').addEventListener('click', () => {
            formContainer.remove();
        });

        formContainer.querySelector('form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const replyInput = document.getElementById(`reply-input-${parentId}`);
            const submitBtn = formContainer.querySelector('.btn-submit-reply');
            if (!replyInput.value.trim()) return;

            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            submitBtn.disabled = true;

            try {
                const response = await fetch(storeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ content: replyInput.value, parent_id: parentId })
                });
                const data = await response.json();
                if (data.success) {
                    formContainer.remove();
                    appendComment(data.comment, true, parentId);
                    updateCommentCount(1);
                } else {
                    alert('Error: ' + data.message);
                    submitBtn.innerText = 'Respon';
                    submitBtn.disabled = false;
                }
            } catch (err) {
                console.error(err);
                alert('Error de connexió');
                submitBtn.innerText = 'Respon';
                submitBtn.disabled = false;
            }
        });
    }

    function appendComment(comment, isReply, parentId = null) {
        const currentUserId = {{ auth()->id() ?? 'null' }};
        const isAdmin = {{ auth()->check() && auth()->user()->role === 'admin' ? 'true' : 'false' }};
        
        const avatarUrl = comment.user && comment.user.avatar 
            ? `{{ asset('storage') }}/${comment.user.avatar}` 
            : null;
            
        let avatarHtml = '';
        if (avatarUrl) {
            avatarHtml = `<img src="${avatarUrl}" alt="${comment.user.name}" class="rounded-circle object-fit-cover shadow-sm" style="width: ${isReply ? '35px' : '45px'}; height: ${isReply ? '35px' : '45px'};">`;
        } else {
            const initial = comment.user && comment.user.name ? comment.user.name.charAt(0).toUpperCase() : '?';
            avatarHtml = `<div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold bg-primary shadow-sm" style="width: ${isReply ? '35px' : '45px'}; height: ${isReply ? '35px' : '45px'}; font-size: ${isReply ? '0.8rem' : '1rem'};">${initial}</div>`;
        }

        let buttonsHtml = '';
        if (currentUserId) {
            if (!isReply) buttonsHtml += `<button type="button" class="btn btn-link p-0 text-decoration-none text-primary btn-reply" data-id="${comment.id}">Respon</button>`;
            if (currentUserId === comment.user_id) buttonsHtml += `<button type="button" class="btn btn-link p-0 text-decoration-none text-primary btn-edit ms-3" data-id="${comment.id}">Edita</button>`;
            if (currentUserId === comment.user_id || isAdmin) buttonsHtml += `<button type="button" class="btn btn-link p-0 text-decoration-none text-danger btn-delete ${!isReply ? 'ms-3' : ''} ${isReply && currentUserId !== comment.user_id ? '' : 'ms-3'}" data-id="${comment.id}">Elimina</button>`;
        }

        const html = `
            <div class="${isReply ? 'reply-item' : 'comment-item'}" id="comment-${comment.id}" data-id="${comment.id}">
                <div class="d-flex gap-3">
                    <div class="flex-shrink-0">${avatarHtml}</div>
                    <div class="flex-grow-1">
                        <div class="bg-${isReply ? 'light' : 'white'} border p-${isReply ? '2' : '3'} rounded shadow-sm position-relative">
                            <div class="d-flex justify-content-between align-items-center mb-${isReply ? '1' : '2'}">
                                <div class="fw-bold ${isReply ? 'small' : ''} text-dark">${comment.user ? comment.user.name : 'Anònim'}</div>
                                <div class="text-muted ${isReply ? 'style="font-size: 0.75rem;"' : 'small'}">Just ara</div>
                            </div>
                            <div class="comment-content text-break text-secondary ${isReply ? 'small' : ''}" id="comment-content-${comment.id}" style="white-space: pre-wrap;">${escapeHtml(comment.content)}</div>
                        </div>
                        <div class="d-flex gap-3 mt-1 ms-2 ${isReply ? '' : 'small fw-medium'}" ${isReply ? 'style="font-size: 0.8rem;"' : ''}>
                            ${buttonsHtml}
                        </div>
                        ${!isReply ? `<div class="replies-container mt-3 d-flex flex-column gap-3 ms-4" id="replies-${comment.id}"></div>` : ''}
                    </div>
                </div>
            </div>
        `;

        if (isReply && parentId) {
            document.getElementById(`replies-${parentId}`).insertAdjacentHTML('beforeend', html);
        } else {
            document.getElementById('comments-container').insertAdjacentHTML('afterbegin', html);
        }
    }

    function updateCommentCount(increment) {
        const countSpan = document.getElementById('comments-count');
        if (countSpan) {
            countSpan.innerText = parseInt(countSpan.innerText) + increment;
        }
    }
});
</script>
@endpush
