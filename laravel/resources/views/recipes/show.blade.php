@extends('layouts.app')

@section('content')
    <div class="section-ui">

        <!-- BREADCRUMB -->
        <div class="recipe-page-container-mb">
            <nav class="recipe-breadcrumb">
                <a href="/">INICI</a>
                <span class="separator">›</span>
                <a href="{{ route('recipes.index') }}">RECEPTES</a>
                <span class="separator">›</span>
                <span class="active">{{ strtoupper($recipe->title) }}</span>
            </nav>
        </div>

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

                            <!-- MODE CUINA -->
                            <button class="btn-dark-ui flex-grow-1 justify-content-center">
                                <i class="bi bi-suit-heart-fill"></i>
                                <span>Mode Cuina</span>
                            </button>
                        </div>
                    @endauth

                    <!-- ICONES COMPARTIR I IMPRIMIR -->
                    <div class="d-flex gap-2 mt-3">
                        <button class="recipe-icon-btn">
                            <i class="bi bi-share"></i>
                        </button>
                        <button class="recipe-icon-btn">
                            <i class="bi bi-printer"></i>
                        </button>
                    </div>
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
                                @if($recipe->chef_avatar)
                                    <img src="{{ asset('storage/' . $recipe->chef_avatar) }}" alt="{{ $recipe->chef_name }}"
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
                                    <div class="small fw-semibold text-secondary text-uppercase opacity-75">Chef Executiu</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if(session('status') === 'recipe-created')
            <div class="recipe-alert-success">
                <i class="bi bi-check-circle"></i> Recepta creada exitosament!
            </div>
        @endif

    </div>

@endsection