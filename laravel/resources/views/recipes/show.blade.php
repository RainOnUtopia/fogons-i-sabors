@extends('layouts.app')

@section('content')
    <div class="section-ui">

 

        <!-- CONTENEDOR PRINCIPAL - LAYOUT DOS COLUMNAS -->
        <div class="recipe-page-container">
            <div class="recipe-show-grid">

                <!-- COLUMNA IZQUIERDA - IMAGEN Y BOTONES -->
                <div>
                    <!-- IMAGEN GRANDE -->
                    <div class="recipe-show-img-wrap">
                        @if($recipe->image)
                            <img src="{{ asset('storage/' . $recipe->image) }}" alt="{{ $recipe->title }}"
                                class="recipe-show-img">
                        @else
                            <div class="recipe-show-img-placeholder">
                                <i class="bi bi-image"></i>
                            </div>
                        @endif
                    </div>

                    @auth
                        <!-- Botons exclusius per a usuaris autenticats -->
                        <div class="d-flex gap-2">
                            <!-- DESAR A PREFERITS -->
                            <form method="POST"
                                action="{{ $isFavorite ? route('recipes.favorite.destroy', $recipe) : route('recipes.favorite.store', $recipe) }}"
                                class="flex-grow-1">
                                @csrf
                                @if($isFavorite)
                                    @method('DELETE')
                                @endif
                                <button type="submit"
                                    class="favorite-toggle-btn favorite-toggle-btn-primary btn-primary-ui w-100 justify-content-center">
                                    <i class="bi {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                    <span>{{ $isFavorite ? 'Treure de Favorits' : 'Desar a Preferits' }}</span>
                                </button>
                            </form>

                   
                        </div>
                    @endauth

                    <!-- ICONES COMPARTIR I IMPRIMIR -->
                    <div class="d-none gap-2 mt-3">
                        <button class="recipe-icon-btn">
                            <i class="bi bi-share"></i>
                        </button>
                        <button class="recipe-icon-btn">
                            <i class="bi bi-printer"></i>
                        </button>
                    </div>
                                 <!-- SECCIÓN NOTES DEL XEF -->
                    @if($recipe->chef_notes || $recipe->chef_name)
                        <div class="recipe-chef-notes-card">
                            <h3 class="recipe-chef-notes-title">Notes del Xef</h3>

                            @if($recipe->chef_notes)
                                <blockquote class="recipe-chef-notes-blockquote">
                                    "{{ $recipe->chef_notes }}"
                                </blockquote>
                            @endif

                            <!-- CHEF INFO -->
                            <div class="d-flex align-items-center gap-3">
                                @if($recipe->user?->avatar)
                                    <img src="{{ asset('storage/' . $recipe->user->avatar) }}" alt="{{ $recipe->user->name ?? $recipe->chef_name }}"
                                        class="rounded-circle object-fit-cover border border-2 border-primary"
                                        style="width: 48px; height: 48px;">
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold fs-5 bg-primary"
                                        style="width: 48px; height: 48px;">
                                        {{ substr($recipe->chef_name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold text-dark">{{ $recipe->chef_name }}</div>
                                
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- COLUMNA DERECHA - INFORMACIÓN -->
                <div>
                    <!-- BADGES ETIQUETAS -->
                    @if($recipe->tags && count($recipe->tags) > 0)
                        <div class="recipe-show-tags">
                            @foreach($recipe->tags as $tag)
                                <span class="recipe-show-tag">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <!-- TÍTULO GRANDE -->
                    <h1 class="recipe-show-title">
                        {{ $recipe->title }}
                    </h1>

                    <!-- INFORMACIÓN: TIEMPO, DIFICULTAD, RATING -->
                    <div class="recipe-show-meta">
                        <!-- TIEMPO -->
                        <div class="recipe-card-meta-item">
                            <i class="bi bi-clock text-primary-ui fs-5"></i>
                            <span class="fw-medium text-secondary">{{ $recipe->cooking_time }} min</span>
                        </div>

                        <!-- DIFICULTAD -->
                        <span class="recipe-show-meta-difficulty">
                            {{ ucfirst($recipe->difficulty) }}
                        </span>

                        <!-- RATING -->
                        @if($recipe->average_rating > 0)
                            <div class="recipe-card-meta-item">
                                <i class="bi bi-star-fill text-warning fs-5"></i>
                                <span class="fw-bold text-dark">{{ number_format($recipe->average_rating, 1) }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- DESCRIPCIÓN -->
                    @if($recipe->description)
                        <p class="recipe-show-desc">
                            {{ $recipe->description }}
                        </p>
                    @endif

                    <!-- SECCIÓN INGREDIENTES -->
                    @if($recipe->ingredients && count($recipe->ingredients) > 0)
                        <div class="mb-4">
                            <h3 class="recipe-show-ingredients-title">
                                <i class="bi bi-check2-circle fs-5"></i>
                                Ingredients
                            </h3>
                            <ul class="recipe-show-ingredients-list">
                                @foreach($recipe->ingredients as $ingredient)
                                    <li>
                                        <span>•</span>
                                        {{ $ingredient }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- SECCIÓ PASSOS A SEGUIR -->
                    @if($recipe->steps && count($recipe->steps) > 0)
                        <div class="mb-4 steps-card">
                            <h3 class="recipe-show-ingredients-title">
                                <i class="bi bi-list-ol fs-5"></i>
                                Passos a seguir
                            </h3>
                            <div class="d-flex flex-column gap-3 mt-3">
                                @foreach($recipe->steps as $index => $step)
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; font-weight: bold;">
                                            {{ $index + 1 }}
                                        </div>
                                        <div class="pt-1 lh-base text-dark step-text">
                                            {{ $step }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

       
                </div>
            </div>
            <!-- SECCIÓ COMENTARIS -->
        <div class="recipe-comments-section mt-5 pt-4">
            <h3 class="mb-4"><i class="bi bi-chat-text"></i> Comentaris (<span id="comments-count">{{ $recipe->comments()->count() }}</span>)</h3>

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
                @foreach($recipe->topLevelComments as $comment)
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
        

        @if(session('status') === 'recipe-created')
            <div class="recipe-alert-success mt-4">
                <i class="bi bi-check-circle"></i> Recepta creada exitosament!
            </div>
        @endif

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
    const storeUrl = `{{ route('recipes.comments.store', $recipe) }}`;
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
    document.getElementById('comments-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-delete')) {
            handleDelete(e.target.dataset.id, e.target);
        } else if (e.target.classList.contains('btn-edit')) {
            handleEdit(e.target.dataset.id, e.target);
        } else if (e.target.classList.contains('btn-reply')) {
            handleReply(e.target.dataset.id, e.target);
        }
    });

    async function handleDelete(commentId, btn) {
        if (!confirm('Segur que vols eliminar aquest comentari?')) return;
        
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        btn.disabled = true;
        
        try {
            const response = await fetch(`/comments/${commentId}`, {
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
                const response = await fetch(`/comments/${commentId}`, {
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