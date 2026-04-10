@extends('layouts.app')

@section('content')
    @php
        // El tab actiu es detecta per PHP: paràmetre 'tab' explícit, o presència
        // de qualsevol paràmetre de filtre/paginació de favorits (f_*) o del rebost (r_*).
        $activeProfileTab = (
            request('tab') === 'favorites'
            || request()->hasAny(['f_search', 'f_difficulty', 'f_sort', 'f_direction', 'f_page'])
        ) ? 'favorites' : 'pantry';
    @endphp
    <div class="page-grid-bg profile-show-bg">
        <div class="container profile-show-container">

            <!-- SECCIÓ 1: HEADER PERFIL -->
            <div class="profile-header profile-header-custom mb-4 p-4 position-relative d-flex flex-column align-items-center justify-content-center">
                <div class="position-absolute top-0 end-0 m-3">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary p-2 fw-bold profile-edit-btn"
                        title="Edita el perfil">
                        <i class="bi bi-wrench profile-edit-icon"></i>
                    </a>
                </div>
                @if (session('status') === 'avatar-updated')
                    <div class="alert alert-success py-2 px-3 mb-3 profile-avatar-alert">
                        Imatge de perfil actualitzada correctament.
                    </div>
                @endif

                <form id="avatarUploadForm" method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" class="mb-3">
                    @csrf
                    @method('PATCH')

                    <label for="avatarInput" class="profile-avatar-label" title="Fes clic per canviar la imatge de perfil">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) . '?v=' . $user->updated_at?->timestamp : asset('img/user-avatar.svg') }}" alt="Avatar" class="mb-0 profile-avatar-image">
                    </label>

                    <input id="avatarInput" type="file" name="avatar" accept=".png,.jpg,.jpeg,image/png,image/jpeg" class="d-none"
                        onchange="if (this.files.length) { document.getElementById('avatarUploadForm').submit(); }">
                </form>
                @if ($errors->avatarUpload->has('avatar'))
                    <div class="text-danger small mb-2">
                        {{ $errors->avatarUpload->first('avatar') }}
                    </div>
                @endif

                <h2 class="fw-bold mb-1 profile-user-name">{{ $user->name }}</h2>
                <div class="d-flex flex-wrap gap-3 justify-content-center align-items-center mt-2 profile-user-meta">
                       @if($user->city || $user->country)
                        <span><i class="bi bi-geo-alt"></i> {{ collect([$user->city, $user->country])->filter()->join(', ') }}</span>
                    @endif    
                    <span><i class="bi bi-calendar"></i> Membre des de {{ $user->created_at->format('d/m/Y') }}</span>
                </div>
            </div>

            @if(auth()->check() && auth()->user()->role === 'admin')
            <div class="d-flex justify-content-center mb-4">
                <a href="{{ url('admin/dashboard') }}" class="btn-primary-ui shadow-sm">Panell d'Administració</a>
            </div>
            @endif
            @if($user->role !== 'admin')
            <div class="row g-4" >
            
                <!-- SECCIÓ 2: TARGETA SOBRE MI -->
                <div class="col-lg-4">
                    <div class="card mb-4 profile-sidebar-card">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3 profile-card-title">Sobre mí</h5>
                            <p class="mb-4 profile-about-text">
                                {{ $user->about_me ?? 'Sense descripció.' }}</p>
                            <div class="d-flex justify-content-between text-center mt-4">
                                <div>
                                    <div class="fw-bold profile-stat-value">{{ $userRecipes->total() }}</div>
                                    <div class="text-muted profile-stat-label">Receptes</div>
                                </div>
                    			 <div>
                                    <div class="fw-bold profile-stat-value">0</div>
                                    <div class="text-muted profile-stat-label">Victòries</div>
                                </div>
                                <div>
                                    <div class="fw-bold profile-stat-value">4.8</div>
                                    <div class="text-muted profile-stat-label">Mitjana</div>
                                </div>
                            </div>
                        </div>
                    </div>

           
                </div>

                <!-- SECCIÓ CENTRAL: TABS I GRID -->
                <div class="col-lg-8">

                    <!-- SECCIÓ 3: TABS CENTRALS -->
                    <ul class="nav nav-tabs mb-4 profile-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeProfileTab === 'pantry' ? 'active' : '' }} fw-bold"
                                id="profile-pantry-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#profile-pantry-pane"
                                type="button"
                                role="tab"
                                aria-controls="profile-pantry-pane"
                                aria-selected="{{ $activeProfileTab === 'pantry' ? 'true' : 'false' }}">El meu rebost</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeProfileTab === 'favorites' ? 'active' : '' }} fw-bold"
                                id="profile-favorites-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#profile-favorites-pane"
                                type="button"
                                role="tab"
                                aria-controls="profile-favorites-pane"
                                aria-selected="{{ $activeProfileTab === 'favorites' ? 'true' : 'false' }}">Plats favorits</button>
                        </li>
                    </ul>

                    <!-- Contingut separat per pestanyes mantenint la graella Bootstrap existent -->
                    <div class="tab-content">
                        <div class="tab-pane fade {{ $activeProfileTab === 'pantry' ? 'show active' : '' }}"
                            id="profile-pantry-pane"
                            role="tabpanel"
                            aria-labelledby="profile-pantry-tab"
                            tabindex="0">

                            {{-- ── BARRA DE FILTRES DEL REBOST ────────────────────────────── --}}
                            @php
                                // Preservam tots els paràmetres actuals excepte el que acabem de canviar.
                                $rExceptSearch     = request()->except(['r_search', 'r_page']);
                                $rExceptDifficulty = request()->except(['r_difficulty', 'r_page']);
                                $rExceptSort       = request()->except(['r_sort', 'r_direction', 'r_page']);
                            @endphp

                            <div class="mb-3">
                                {{-- Cerca --}}
                                <form method="GET" class="mb-2">
                                    @foreach($rExceptSearch as $k => $v)
                                        @if(!is_array($v))
                                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                        @endif
                                    @endforeach
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input type="text" name="r_search" class="form-control"
                                               placeholder="Cerca per nom o ingredient..."
                                               value="{{ $rSearch }}">
                                        @if($rSearch)
                                            <a href="{{ route('profile.show', array_merge($rExceptSearch, ['tab' => 'pantry'])) }}"
                                               class="btn btn-outline-secondary btn-sm" title="Esborra la cerca">
                                                <i class="bi bi-x-lg"></i>
                                            </a>
                                        @endif
                                        <button type="submit" class="btn btn-primary btn-sm">Cercar</button>
                                    </div>
                                </form>

                                {{-- Dificultat + Ordenació en una mateixa fila --}}
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    {{-- Pills de dificultat --}}
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach(['tots' => 'Tots', 'fàcil' => 'Fàcil', 'mitjà' => 'Mitjà', 'difícil' => 'Difícil'] as $val => $label)
                                            <a href="{{ route('profile.show', array_merge($rExceptDifficulty, $val !== 'tots' ? ['r_difficulty' => $val] : [], ['tab' => 'pantry'])) }}"
                                               class="btn btn-sm rounded-pill fw-semibold px-3 py-1 text-decoration-none dificulty-tag
                                                      {{ $rDifficulty === $val ? 'btn-danger text-white' : 'btn-light text-secondary border' }}">
                                                {{ $label }}
                                            </a>
                                        @endforeach
                                    </div>

                                    {{-- Selector d'ordre --}}
                                    <form method="GET" class="ms-auto">
                                        @foreach($rExceptSort as $k => $v)
                                            @if(!is_array($v))
                                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                            @endif
                                        @endforeach
                                        <input type="hidden" name="tab" value="pantry">
                                        <select name="r_sort" class="form-select form-select-sm d-inline-block w-auto"
                                                onchange="this.form.submit()">
                                            <option value="created_at" data-dir="desc" {{ $rSort === 'created_at' && $rDirection === 'desc' ? 'selected' : '' }}>Més recents</option>
                                            <option value="created_at" data-dir="asc"  {{ $rSort === 'created_at' && $rDirection === 'asc'  ? 'selected' : '' }}>Més antics</option>
                                            <option value="average_rating"     data-dir="desc" {{ $rSort === 'average_rating'     ? 'selected' : '' }}>Millor valorats</option>
                                            <option value="title"      data-dir="asc"  {{ $rSort === 'title'      ? 'selected' : '' }}>Ordre alfabètic</option>
                                        </select>
                                        {{-- Camp ocult actualitzat per JS quan canvia el select --}}
                                        <input type="hidden" name="r_direction" id="r_direction_input" value="{{ $rDirection }}">
                                    </form>
                                </div>
                            </div>
                            {{-- ── FI BARRA DE FILTRES ────────────────────────────────────── --}}

                            <div class="row g-4">
                                @if(auth()->check() && auth()->id() === $user->id)
                                    <!-- Targeta crear recepta visible només al rebost propi -->
                                    <div class="col-md-4">
                                        <div class="card h-100 d-flex align-items-center justify-content-center profile-create-card-custom">
                                            <a href="{{ route('recipes.create') }}"
                                                class="d-flex flex-column align-items-center justify-content-center text-decoration-none profile-create-link">
                                                <span class="display-4 mb-2">+</span>
                                                <span class="fw-bold">Crear recepta</span>
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @forelse ($userRecipes as $recipe)
                                    <!-- Recepta pròpia mostrada al rebost mantenint la targeta existent del perfil -->
                                    <div class="col-md-4">
                                        <div style="position: relative;">
                                            <a href="{{ route('recipes.show', $recipe) }}" class="text-decoration-none d-block">
                                                <div class="card h-100 profile-recipe-card">
                                                    @if($recipe->image)
                                                        <img src="{{ asset('storage/' . $recipe->image) }}" alt="{{ $recipe->title }}" class="card-img-top profile-recipe-image">
                                                    @else
                                                        <div class="card-img-top profile-recipe-image d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #e0e0e0 0%, #f3f3f3 100%); color: #999; font-size: 2rem;">
                                                            <i class="bi bi-image"></i>
                                                        </div>
                                                    @endif
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2 recipe-title-container">
                                                            <h6 class="fw-bold mb-0 profile-recipe-title ">{{ $recipe->title }}</h6>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="text-muted profile-recipe-meta"><i class="bi bi-clock"></i> {{ $recipe->cooking_time }} min</span>
                                                            <span class="text-muted profile-recipe-meta">{{ ucfirst($recipe->difficulty) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>

                                            @if(auth()->check() && auth()->id() === $user->id)
                                                <!-- Accions ràpides del rebost: editar i eliminar la recepta pròpia -->
                                                <div style="position: absolute; top: 12px; right: 12px; display: flex; gap: 8px; z-index: 2;">
                                                    <a href="{{ route('recipes.edit', $recipe) }}"
                                                        title="Editar recepta"
                                                        aria-label="Editar recepta"
                                                        style="width: 34px; height: 34px; border-radius: 50%; background: white; color: #BE3144; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </a>
                                                    <button type="button"
                                                        title="Eliminar recepta"
                                                        aria-label="Eliminar recepta"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteRecipeModal{{ $recipe->id }}"
                                                        style="width: 34px; height: 34px; border-radius: 50%; background: white; color: #BE3144; border: none; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); display: flex; align-items: center; justify-content: center;">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @if(auth()->check() && auth()->id() === $user->id)
                                        <!-- Confirmació abans d'eliminar una recepta del rebost -->
                                        <div class="modal fade" id="deleteRecipeModal{{ $recipe->id }}" tabindex="-1" aria-labelledby="deleteRecipeModalLabel{{ $recipe->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content danger-modal-content">
                                                    <form method="POST" action="{{ route('recipes.destroy', $recipe) }}">
                                                        @csrf
                                                        @method('DELETE')

                                                        <div class="modal-header danger-modal-header">
                                                            <h5 class="modal-title text-danger d-flex align-items-center gap-2" id="deleteRecipeModalLabel{{ $recipe->id }}">
                                                                <i class="bi bi-exclamation-triangle-fill"></i>
                                                                Confirmar eliminació
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <p class="text-muted small mb-0">
                                                                Vols eliminar definitivament la recepta <strong>{{ $recipe->title }}</strong>? També desapareixerà del teu rebost i del llistat general de receptes.
                                                            </p>
                                                        </div>

                                                        <div class="modal-footer danger-modal-footer">
                                                            <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                                                                Cancel·lar
                                                            </button>
                                                            <button type="submit" class="btn btn-danger danger-btn-rounded">
                                                                <i class="bi bi-trash3 me-1"></i>
                                                                Eliminar recepta
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <div class="col-12">
                                        <div class="card profile-recipe-card">
                                            <div class="card-body text-center py-5">
                                                <h6 class="fw-bold mb-2 profile-recipe-title">Encara no has creat cap recepta</h6>
                                                <p class="text-muted mb-0 profile-recipe-meta">Quan publiquis una recepta, apareixerà aquí dins del teu rebost.</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            {{-- Paginació del rebost --}}
                            @if($userRecipes->hasPages())
                                <div class="d-flex justify-content-center mt-3 pagination-container">
                                    {{ $userRecipes->links('pagination::bootstrap-5') }}
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade {{ $activeProfileTab === 'favorites' ? 'show active' : '' }}"
                            id="profile-favorites-pane"
                            role="tabpanel"
                            aria-labelledby="profile-favorites-tab"
                            tabindex="0">

                            {{-- ── BARRA DE FILTRES DELS FAVORITS ─────────────────────────── --}}
                            @php
                                $fExceptSearch     = request()->except(['f_search', 'f_page']);
                                $fExceptDifficulty = request()->except(['f_difficulty', 'f_page']);
                                $fExceptSort       = request()->except(['f_sort', 'f_direction', 'f_page']);
                            @endphp

                            <div class="mb-3">
                                {{-- Cerca --}}
                                <form method="GET" class="mb-2">
                                    @foreach($fExceptSearch as $k => $v)
                                        @if(!is_array($v))
                                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                        @endif
                                    @endforeach
                                    <input type="hidden" name="tab" value="favorites">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input type="text" name="f_search" class="form-control"
                                               placeholder="Cerca per nom, xef o ingredient..."
                                               value="{{ $fSearch }}">
                                        @if($fSearch)
                                            <a href="{{ route('profile.show', array_merge($fExceptSearch, ['tab' => 'favorites'])) }}"
                                               class="btn btn-outline-secondary btn-sm" title="Esborra la cerca">
                                                <i class="bi bi-x-lg"></i>
                                            </a>
                                        @endif
                                        <button type="submit" class="btn btn-primary btn-sm">Cercar</button>
                                    </div>
                                </form>

                                {{-- Dificultat + Ordenació --}}
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    {{-- Pills de dificultat --}}
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach(['tots' => 'Tots', 'fàcil' => 'Fàcil', 'mitjà' => 'Mitjà', 'difícil' => 'Difícil'] as $val => $label)
                                            <a href="{{ route('profile.show', array_merge($fExceptDifficulty, $val !== 'tots' ? ['f_difficulty' => $val] : [], ['tab' => 'favorites'])) }}"
                                               class="btn btn-sm rounded-pill fw-semibold px-3 py-1 text-decoration-none dificulty-tag
                                                      {{ $fDifficulty === $val ? 'btn-danger text-white' : 'btn-light text-secondary border' }}">
                                                {{ $label }}
                                            </a>
                                        @endforeach
                                    </div>

                                    {{-- Selector d'ordre (inclou data d'afegit a favorits) --}}
                                    <form method="GET" class="ms-auto">
                                        @foreach($fExceptSort as $k => $v)
                                            @if(!is_array($v))
                                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                            @endif
                                        @endforeach
                                        <input type="hidden" name="tab" value="favorites">
                                        <select name="f_sort" class="form-select form-select-sm d-inline-block w-auto"
                                                onchange="this.form.submit()">
                                            <option value="favorites.created_at" data-dir="desc" {{ $fSort === 'favorites.created_at' && $fDirection === 'desc' ? 'selected' : '' }}>Afegits més recentment</option>
                                            <option value="favorites.created_at" data-dir="asc"  {{ $fSort === 'favorites.created_at' && $fDirection === 'asc'  ? 'selected' : '' }}>Afegits més antigament</option>
                                            <option value="recipes.average_rating"       data-dir="desc" {{ $fSort === 'recipes.average_rating'       ? 'selected' : '' }}>Millor valorats</option>
                                            <option value="recipes.title"        data-dir="asc"  {{ $fSort === 'recipes.title'        ? 'selected' : '' }}>Ordre alfabètic</option>
                                        </select>
                                        <input type="hidden" name="f_direction" id="f_direction_input" value="{{ $fDirection }}">
                                    </form>
                                </div>
                            </div>
                            {{-- ── FI BARRA DE FILTRES FAVORITS ──────────────────────────── --}}

                            <div class="row g-4">
                                @forelse ($favoriteRecipes as $recipe)
                                    <!-- Recepta favorita real mostrada a la graella existent -->
                                    <div class="col-md-4">
                                        <div style="position: relative;">
                                            <a href="{{ route('recipes.show', $recipe) }}" class="text-decoration-none d-block">
                                                <div class="card h-100 profile-recipe-card">
                                                    @if($recipe->image)
                                                        <img src="{{ asset('storage/' . $recipe->image) }}" alt="{{ $recipe->title }}" class="card-img-top profile-recipe-image">
                                                    @else
                                                        <div class="card-img-top profile-recipe-image d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #e0e0e0 0%, #f3f3f3 100%); color: #999; font-size: 2rem;">
                                                            <i class="bi bi-image"></i>
                                                        </div>
                                                    @endif
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                                            <h6 class="fw-bold mb-0 profile-recipe-title">{{ $recipe->title }}</h6>
                                                            <i class="bi bi-heart-fill" style="color: #BE3144;"></i>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="text-muted profile-recipe-meta"><i class="bi bi-clock"></i> {{ $recipe->cooking_time }} min</span>
                                                            <span class="text-muted profile-recipe-meta">{{ ucfirst($recipe->difficulty) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>

                                            <!-- Acció ràpida per treure la recepta dels favorits sense eliminar-la -->
                                            <form method="POST"
                                                action="{{ route('recipes.favorite.destroy', $recipe) }}"
                                                style="position: absolute; top: 12px; right: 12px; z-index: 2; margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="redirect_tab" value="favorites">
                                                <button type="submit"
                                                    title="Treure de favorits"
                                                    aria-label="Treure de favorits"
                                                    style="width: 34px; height: 34px; border-radius: 50%; background: white; color: #BE3144; border: none; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); display: flex; align-items: center; justify-content: center;">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="card profile-recipe-card">
                                            <div class="card-body text-center py-5">
                                                <h6 class="fw-bold mb-2 profile-recipe-title">Encara no tens plats favorits</h6>
                                                <p class="text-muted mb-0 profile-recipe-meta">Quan marquis receptes amb el cor, apareixeran aquí.</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            {{-- Paginació dels favorits --}}
                            @if($favoriteRecipes instanceof \Illuminate\Pagination\LengthAwarePaginator && $favoriteRecipes->hasPages())
                                <div class="d-flex justify-content-center mt-3 pagination-container">
                                    {{ $favoriteRecipes->links('pagination::bootstrap-5') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    /**
     * Sincronitza la direcció d'ordenació (data-dir) de cada select d'ordre
     * amb el camp hidden corresponent abans de l'enviament del formulari.
     * Necessari perquè un sol select combina el camp (r_sort / f_sort) i
     * la direcció (asc/desc) en una sola selecció d'usuari.
     */
    document.addEventListener('DOMContentLoaded', function () {
        // Mapeja cada select de sort amb el seu input hidden de direction.
        const sortPairs = [
            { selectName: 'r_sort', hiddenId: 'r_direction_input' },
            { selectName: 'f_sort', hiddenId: 'f_direction_input' },
        ];

        sortPairs.forEach(function ({ selectName, hiddenId }) {
            const select = document.querySelector('select[name="' + selectName + '"]');
            const hidden = document.getElementById(hiddenId);
            if (!select || !hidden) return;

            select.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                hidden.value = selectedOption.dataset.dir ?? 'desc';
            });
        });
    });
</script>
@endpush
