@extends('layouts.app')

@section('content')
    @php
        // Conservam la resta de paràmetres GET quan l'usuari cerca o canvia la dificultat.
        $searchFormParams = request()->except('search', 'page');
        $difficultyFilterParams = request()->except('difficulty', 'page');
    @endphp
    <div class="section-ui">

        <!-- HEADER -->
        <div class="recipe-page-container-mb d-flex justify-content-between align-items-start gap-4">
            <div class="flex-grow-1">
                <h1 class="recipe-hero-title">El Receptari</h1>
                <p class="recipe-hero-subtitle">Explora la nostra col·lecció seleccionada de receptes de classe mundial.</p>
            </div>
            @auth
                <a href="{{ route('recipes.create') }}" class="btn-primary-ui">
                    <i class="bi bi-plus-lg"></i>
                    <span class="fw-semibold">Compartir Recepta</span>
                </a>
            @endauth
        </div>

        <!-- CARTA PRINCIPAL CON BÚSQUEDA Y FILTROS -->
        <div class="recipe-page-container-mb card-ui overflow-hidden">

            <!-- HEADER CON BÚSQUEDA -->
            <div class="recipe-filter-bar">

                <!-- BUSCADOR -->
                <form method="GET" class="flex-grow-1" style="min-width: 300px;">
                    @foreach($searchFormParams as $paramName => $paramValue)
                        @if(!is_array($paramValue))
                            <input type="hidden" name="{{ $paramName }}" value="{{ $paramValue }}">
                        @endif
                    @endforeach
                    <div class="recipe-filter-input-wrap">
                        <button   type="submit" class="btn btn-primary btn-sm bi bi-search button-search-recipies">
                         
                        </button>
                        <input type="text" name="search" class="form-control recipe-form-input has-icon"
                            placeholder="Cerca per nom, xef o ingredient..." value="{{ request('search') }}">
                    </div>
                </form>

                <!-- Controls visuals: vista actual i accés als filtres -->
                <div class="d-flex align-items-center gap-2">
                    <button type="button" title="Vista grid" aria-label="Vista grid activa" class="recipe-btn-icon-primary d-none">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </button>
                    <button type="button" title="Filtres" aria-label="Mostrar o amagar filtres" data-bs-toggle="collapse"
                        data-bs-target="#recipesFiltersCollapse" aria-expanded="true" aria-controls="recipesFiltersCollapse"
                        class="recipe-btn-icon-outline">
                        <i class="bi bi-funnel"></i>
                    </button>
                </div>
            </div>

            <!-- FILTROS -->
            <div id="recipesFiltersCollapse" class="collapse show">
                <div class="px-4 px-md-5 py-3 border-bottom d-flex flex-wrap align-items-center gap-2"
                    style="border-bottom-color: var(--border) !important;">
                    <a href="{{ route('recipes.index', $difficultyFilterParams) }}"
                        class="btn btn-sm rounded-pill fw-semibold text-uppercase px-3 py-1 lh-base text-decoration-none {{ $currentDifficulty === 'tots' ? 'btn-danger text-white' : 'btn-light text-secondary border' }}">
                        TOTS
                    </a>
                    <a href="{{ route('recipes.index', array_merge($difficultyFilterParams, ['difficulty' => 'fàcil'])) }}"
                        class="btn btn-sm rounded-pill fw-semibold text-uppercase px-3 py-1 lh-base text-decoration-none {{ $currentDifficulty === 'fàcil' ? 'btn-danger text-white' : 'btn-light text-secondary border' }}">
                        FÀCIL
                    </a>
                    <a href="{{ route('recipes.index', array_merge($difficultyFilterParams, ['difficulty' => 'mitjà'])) }}"
                        class="btn btn-sm rounded-pill fw-semibold text-uppercase px-3 py-1 lh-base text-decoration-none {{ $currentDifficulty === 'mitjà' ? 'btn-danger text-white' : 'btn-light text-secondary border' }}">
                        MITJÀ
                    </a>
                    <a href="{{ route('recipes.index', array_merge($difficultyFilterParams, ['difficulty' => 'difícil'])) }}"
                        class="btn btn-sm rounded-pill fw-semibold text-uppercase px-3 py-1 lh-base text-decoration-none {{ $currentDifficulty === 'difícil' ? 'btn-danger text-white' : 'btn-light text-secondary border' }}">
                        DIFÍCIL
                    </a>
                </div>
            </div>

            <!-- GRILL DE RECEPTES -->
            <div class="p-4 p-md-5">
                <div class="recipe-cards-grid">

                    @forelse($recipes as $recipe)
                        @php
                            $isFavorite = in_array($recipe->id, $favoriteRecipeIds, true);
                        @endphp
                        <!-- TARJETA RECEPTA -->
                        <a href="{{ route('recipes.show', $recipe) }}" class="recipe-card-link">
                            <div class="recipe-card-inner">

                                <!-- IMAGEN CON BADGES Y FAVORITO -->
                                <div class="recipe-card-img-wrap">
                                    @if($recipe->image)
                                        <img src="{{ asset('storage/' . $recipe->image) }}" alt="{{ $recipe->title }}"
                                            class="recipe-card-img">
                                    @else
                                        <div class="recipe-card-img-placeholder">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif

                                    <!-- BADGES -->
                                    @if($recipe->tags && count($recipe->tags) > 0)
                                        <div class="recipe-card-tags">
                                            @foreach($recipe->tags as $tag)
                                                <span class="recipe-card-tag">
                                                    {{ $tag }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Botó de favorits sense alterar l'estructura visual de la targeta -->
                                    @auth
                                        <form method="POST"
                                            action="{{ $isFavorite ? route('recipes.favorite.destroy', $recipe) : route('recipes.favorite.store', $recipe) }}"
                                            onsubmit="event.stopPropagation();" onclick="event.stopPropagation();"
                                            class="position-absolute bottom-0 end-0 mb-3 me-3">
                                            @csrf
                                            @if($isFavorite)
                                                @method('DELETE')
                                            @endif
                                            <button type="submit"
                                                class="favorite-toggle-btn rounded-circle shadow-sm bg-white border-0 d-flex align-items-center justify-content-center {{ $isFavorite ? 'text-primary-ui' : 'text-muted' }}"
                                                aria-label="{{ $isFavorite ? 'Treure de favorits' : 'Afegir a favorits' }}"
                                                style="width: 40px; height: 40px; transition: all 0.2s ease; font-size: 18px;">
                                                <i class="bi {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Placeholder invisible per mantenir la mateixa estructura visual en mode convidat -->
                                        <span aria-hidden="true"
                                            class="position-absolute bottom-0 end-0 mb-3 me-3 rounded-circle bg-white shadow-sm invisible"
                                            style="width: 40px; height: 40px;">
                                        </span>
                                    @endauth
                                </div>

                                <!-- CONTENIDO -->
                                <div class="recipe-card-content">
                                    <!-- TITULO -->
                                    <h3 class="recipe-card-title">
                                        {{ $recipe->title }}
                                    </h3>

                                    <!-- CHEF -->
                                    <p class="recipe-card-chef">
                                        @if($recipe->user->avatar)
                                            <img src="{{ asset('storage/' . $recipe->user->avatar) }}" alt="{{ $recipe->user->name ?? $recipe->chef_name }}"
                                                class="rounded-circle object-fit-cover" style="width: 20px; height: 20px;">
                                        @else
                                            <span
                                                class="rounded-circle d-flex align-items-center justify-content-center text-white bg-primary fw-bold"
                                                style="width: 20px; height: 20px; font-size: 11px;">
                                                {{ substr($recipe->chef_name, 0, 1) }}
                                            </span>
                                        @endif
                                        Creat per {{ $recipe->chef_name }}
                                    </p>

                                    <!-- INFO TIEMPO Y DIFICULTAD -->
                                    <div class="recipe-card-meta">
                                        <span class="recipe-card-meta-item">
                                            <i class="bi bi-clock"></i>
                                            {{ $recipe->cooking_time }} min
                                        </span>
                                        @if($recipe->average_rating > 0)
                                            <span class="recipe-card-meta-item rating">
                                                <i class="bi bi-star-fill"></i>
                                                {{ number_format($recipe->average_rating, 1) }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- BOTÓN VEURE DETALLS -->
                                    <button
                                        onclick="event.preventDefault(); window.location.href = '{{ route('recipes.show', $recipe) }}';"
                                        class="recipe-card-btn">
                                        Veure detalls &rarr;
                                    </button>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-12 text-center py-5 text-secondary">
                            <p class="fs-6 mb-0">No s'han trobat receptes.</p>
                        </div>
                    @endforelse

                </div>
            </div>

            <!-- PAGINACIÓN -->
            @if($recipes->hasPages())
                <div class="d-flex justify-content-center border-top px-4 py-4 mt-auto pagination-container">
                    {{ $recipes->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>

    </div>
@endsection