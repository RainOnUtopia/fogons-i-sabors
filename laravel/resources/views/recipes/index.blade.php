@extends('layouts.app')

@section('content')
    @php
        $searchFormParams = request()->except('search', 'page');
        $difficultyFilterParams = request()->except('difficulty', 'page');
        $difficultyOptions = [
            'tots' => 'TOTS',
            'fàcil' => 'FÀCIL',
            'mitjà' => 'MITJÀ',
            'difícil' => 'DIFÍCIL',
        ];
    @endphp

    <div class="section-ui recipes-library-page">
        <div class="recipe-page-container-mb recipes-library-hero">
            <div>
                <h1 class="recipes-library-title">El Receptari</h1>
                <p class="recipes-library-subtitle">Explora la nostra col·lecció seleccionada de receptes de classe mundial.</p>
            </div>

            @auth
                @if(auth()->user()->role !== 'admin')
                    <a href="{{ route('recipes.create') }}" class="recipes-library-create">
                        <i class="bi bi-plus-lg"></i>
                        Compartir Recepta
                    </a>
                @endif
            @endauth
        </div>

        <div class="recipe-page-container-mb recipes-library-toolbar">
            <form method="GET" class="recipes-library-search">
                @foreach($searchFormParams as $paramName => $paramValue)
                    @if(!is_array($paramValue))
                        <input type="hidden" name="{{ $paramName }}" value="{{ $paramValue }}">
                    @endif
                @endforeach

                <button type="submit" aria-label="Cercar">
                    <i class="bi bi-search"></i>
                </button>
                <input
                    type="text"
                    name="search"
                    placeholder="Cerca per nom, xef o ingredient..."
                    value="{{ request('search') }}"
                >
            </form>

            <nav class="recipes-library-filters" aria-label="Filtrar per dificultat">
                @foreach($difficultyOptions as $difficultyValue => $difficultyLabel)
                    <a
                        href="{{ route('recipes.index', $difficultyValue === 'tots' ? $difficultyFilterParams : array_merge($difficultyFilterParams, ['difficulty' => $difficultyValue])) }}"
                        class="{{ $currentDifficulty === $difficultyValue ? 'active' : '' }}"
                    >
                        {{ $difficultyLabel }}
                    </a>
                @endforeach
            </nav>

            <div class="recipes-library-viewtools" aria-hidden="true">
                <span class="active"><i class="bi bi-grid-3x3-gap-fill"></i></span>
                <span><i class="bi bi-list-ul"></i></span>
            </div>
        </div>

        <div class="recipe-page-container-mb">
            <div class="recipes-library-grid">
                @forelse($recipes as $recipe)
                    @php
                        $isFavorite = in_array($recipe->id, $favoriteRecipeIds, true);
                        $recipeTags = collect($recipe->tags ?? [])->filter()->take(2);
                    @endphp

                    <article class="recipes-library-card">
                        <div class="recipes-library-media">
                            <a href="{{ route('recipes.show', $recipe) }}" class="recipes-library-image">
                                @if($recipe->image)
                                    <img src="{{ asset('storage/' . $recipe->image) }}" alt="{{ $recipe->title }}">
                                @else
                                    <span><i class="bi bi-image"></i></span>
                                @endif

                                @if($recipeTags->isNotEmpty())
                                    <div class="recipes-library-tags">
                                        @foreach($recipeTags as $tag)
                                            <span>{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </a>

                            @auth
                                <form
                                    method="POST"
                                    action="{{ $isFavorite ? route('recipes.favorite.destroy', $recipe) : route('recipes.favorite.store', $recipe) }}"
                                    class="recipes-library-favorite"
                                >
                                    @csrf
                                    @if($isFavorite)
                                        @method('DELETE')
                                    @endif
                                    <button type="submit" aria-label="{{ $isFavorite ? 'Treure de favorits' : 'Afegir a favorits' }}">
                                        <i class="bi {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="recipes-library-favorite" aria-label="Inicia sessió per afegir a favorits">
                                    <span><i class="bi bi-heart"></i></span>
                                </a>
                            @endauth
                        </div>

                        <div class="recipes-library-card-body">
                            <div class="recipes-library-title-row">
                                <h2>
                                    <a href="{{ route('recipes.show', $recipe) }}">{{ $recipe->title }}</a>
                                </h2>
                                @if($recipe->average_rating > 0)
                                    <span class="recipes-library-rating">
                                        <i class="bi bi-star-fill"></i>
                                        {{ number_format($recipe->average_rating, $recipe->average_rating == floor($recipe->average_rating) ? 0 : 1) }}
                                    </span>
                                @endif
                            </div>

                            <p class="recipes-library-chef">Creat per {{ $recipe->chef_name }}</p>

                            <div class="recipes-library-card-footer">
                                <span>
                                    <i class="bi bi-clock"></i>
                                    {{ $recipe->cooking_time }} min
                                </span>
                                <a href="{{ route('recipes.show', $recipe) }}">
                                    Veure detalls
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="recipes-library-empty">
                        <i class="bi bi-journal-x"></i>
                        <h2>No s'han trobat receptes</h2>
                        <p>Prova amb una altra cerca o canvia el filtre de dificultat.</p>
                    </div>
                @endforelse
            </div>

            @if($recipes->hasPages())
                <div class="d-flex justify-content-center px-4 py-4 mt-4 pagination-container">
                    {{ $recipes->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection
