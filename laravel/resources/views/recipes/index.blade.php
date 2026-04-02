@extends('layouts.app')

@section('content')
<div style="width: 100vw; min-height: 100vh; background-color: #f4efea; background-image: linear-gradient(0deg, transparent 24%, rgba(200, 200, 200, 0.05) 25%, rgba(200, 200, 200, 0.05) 26%, transparent 27%, transparent 74%, rgba(200, 200, 200, 0.05) 75%, rgba(200, 200, 200, 0.05) 76%, transparent 77%, transparent), linear-gradient(90deg, transparent 24%, rgba(200, 200, 200, 0.05) 25%, rgba(200, 200, 200, 0.05) 26%, transparent 27%, transparent 74%, rgba(200, 200, 200, 0.05) 75%, rgba(200, 200, 200, 0.05) 76%, transparent 77%, transparent); background-size: 50px 50px; padding: 40px 20px; margin: 0; margin-left: calc(-50vw + 50%); display: flex; flex-direction: column;">

    <!-- HEADER -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; max-width: 1100px; width: 100%; margin: 0 auto 40px; gap: 20px;">
        <div style="flex: 1;">
            <h1 style="font-family: Georgia, Garamond, serif; font-style: italic; font-size: 2.5rem; font-weight: 700; color: #2d2d2d; margin: 0 0 12px; letter-spacing: -0.5px;">El Receptari</h1>
            <p style="font-size: 16px; color: #7a7a7a; margin: 0; font-weight: 400;">Explora la nostra col·lecció seleccionada de receptes de classe mundial.</p>
        </div>
        @auth
        <a href="{{ route('recipes.create') }}" style="height: 44px; padding: 0 24px; background-color: #be3144; color: white; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px; white-space: nowrap; text-decoration: none;">
            <i class="bi bi-plus-lg"></i>
            <span style="font-weight: 600;">Compartir Recepta</span>
        </a>
        @endauth
    </div>

    <!-- CARTA PRINCIPAL CON BÚSQUEDA Y FILTROS -->
    <div style="max-width: 1100px; width: 100%; margin: 0 auto 40px; background: white; border-radius: 24px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); overflow: hidden;">

        <!-- HEADER CON BÚSQUEDA -->
        <div style="padding: 32px 36px; border-bottom: 1px solid #e8e8e8; display: flex; justify-content: space-between; align-items: center; gap: 24px; flex-wrap: wrap;">

            <!-- BUSCADOR -->
            <form method="GET" style="flex: 1; min-width: 300px;">
                <input type="hidden" name="difficulty" value="{{ $currentDifficulty }}">
                <div style="position: relative; display: flex; align-items: center;">
                    <i class="bi bi-search" style="position: absolute; left: 16px; font-size: 16px; color: #9a9a9a; pointer-events: none; z-index: 1;"></i>
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Cerca per nom, xef o ingredient..."
                        value="{{ request('search') }}"
                        style="width: 100%; height: 48px; padding: 12px 16px 12px 48px; background-color: #f3f3f3; border: none; border-radius: 12px; font-size: 14px; color: #2d2d2d; transition: all 0.3s ease; font-family: inherit;">
                </div>
            </form>

            <!-- SELECTOR GRID/LISTA (SOLO UI) -->
            <div style="display: flex; align-items: center; gap: 8px;">
                <button title="Vista grid" style="width: 40px; height: 40px; padding: 0; background-color: #be3144; color: white; border: none; border-radius: 8px; font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-grid-3x3-gap"></i>
                </button>
                <button title="Vista lista" style="width: 40px; height: 40px; padding: 0; background-color: transparent; color: #7a7a7a; border: 1px solid #e8e8e8; border-radius: 8px; font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease;">
                    <i class="bi bi-list-ul"></i>
                </button>
            </div>
        </div>

        <!-- FILTROS -->
        <div style="padding: 20px 36px; border-bottom: 1px solid #e8e8e8; display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
            <a href="{{ route('recipes.index') }}" style="display: inline-flex; align-items: center; padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.2s ease; background-color: {{ $currentDifficulty === 'tots' ? '#be3144' : '#f3f3f3' }}; color: {{ $currentDifficulty === 'tots' ? 'white' : '#7a7a7a' }}; text-transform: uppercase; letter-spacing: 0.4px;">
                TOTS
            </a>
            <a href="{{ route('recipes.index', ['difficulty' => 'fàcil']) }}" style="display: inline-flex; align-items: center; padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.2s ease; background-color: {{ $currentDifficulty === 'fàcil' ? '#be3144' : '#f3f3f3' }}; color: {{ $currentDifficulty === 'fàcil' ? 'white' : '#7a7a7a' }}; text-transform: uppercase; letter-spacing: 0.4px;">
                FÀCIL
            </a>
            <a href="{{ route('recipes.index', ['difficulty' => 'mitjà']) }}" style="display: inline-flex; align-items: center; padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.2s ease; background-color: {{ $currentDifficulty === 'mitjà' ? '#be3144' : '#f3f3f3' }}; color: {{ $currentDifficulty === 'mitjà' ? 'white' : '#7a7a7a' }}; text-transform: uppercase; letter-spacing: 0.4px;">
                MITJÀ
            </a>
            <a href="{{ route('recipes.index', ['difficulty' => 'difícil']) }}" style="display: inline-flex; align-items: center; padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.2s ease; background-color: {{ $currentDifficulty === 'difícil' ? '#be3144' : '#f3f3f3' }}; color: {{ $currentDifficulty === 'difícil' ? 'white' : '#7a7a7a' }}; text-transform: uppercase; letter-spacing: 0.4px;">
                DIFÍCIL
            </a>
        </div>

        <!-- GRILL DE RECEPTES -->
        <div style="padding: 40px 36px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 32px; width: 100%;">

                @forelse($recipes as $recipe)
                    @php
                        $isFavorite = in_array($recipe->id, $favoriteRecipeIds, true);
                    @endphp
                    <!-- TARJETA RECEPTA -->
                    <a href="{{ route('recipes.show', $recipe) }}" style="text-decoration: none; color: inherit; transition: all 0.3s ease; display: flex; flex-direction: column;">
                        <div style="background: white; border-radius: 24px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); overflow: hidden; display: flex; flex-direction: column; height: 100%; transition: all 0.3s ease; position: relative;">

                            <!-- IMAGEN CON BADGES Y FAVORITO -->
                            <div style="position: relative; width: 100%; height: 220px; overflow: hidden; background: #f3f3f3;">
                                @if($recipe->image)
                                    <img src="{{ asset('storage/' . $recipe->image) }}" alt="{{ $recipe->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #e0e0e0 0%, #f3f3f3 100%); font-size: 48px; color: #999;">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif

                                <!-- BADGES -->
                                @if($recipe->tags && count($recipe->tags) > 0)
                                    <div style="position: absolute; top: 12px; left: 12px; display: flex; gap: 6px; flex-wrap: wrap;">
                                        @foreach($recipe->tags as $tag)
                                            <span style="display: inline-block; background: rgba(255, 255, 255, 0.95); color: #be3144; border-radius: 6px; font-size: 11px; font-weight: 600; padding: 4px 8px; text-transform: uppercase; letter-spacing: 0.3px;">
                                                {{ $tag }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Botó de favorits sense alterar l'estructura visual de la targeta -->
                                @auth
                                    <form method="POST"
                                        action="{{ $isFavorite ? route('recipes.favorite.destroy', $recipe) : route('recipes.favorite.store', $recipe) }}"
                                        onsubmit="event.stopPropagation();"
                                        onclick="event.stopPropagation();"
                                        style="position: absolute; bottom: 12px; right: 12px; margin: 0;">
                                        @csrf
                                        @if($isFavorite)
                                            @method('DELETE')
                                        @endif
                                        <button type="submit"
                                            aria-label="{{ $isFavorite ? 'Treure de favorits' : 'Afegir a favorits' }}"
                                            style="width: 40px; height: 40px; border-radius: 50%; background: white; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); transition: all 0.2s ease; font-size: 18px; color: {{ $isFavorite ? '#BE3144' : '#ccc' }};">
                                            <i class="bi {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                        </button>
                                    </form>
                                @else
                                    <!-- Placeholder invisible per mantenir la mateixa estructura visual en mode convidat -->
                                    <span aria-hidden="true"
                                        style="position: absolute; bottom: 12px; right: 12px; width: 40px; height: 40px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); visibility: hidden;">
                                    </span>
                                @endauth
                            </div>

                            <!-- CONTENIDO -->
                            <div style="padding: 20px; flex: 1; display: flex; flex-direction: column;">
                                <!-- TITULO -->
                                <h3 style="font-size: 18px; font-weight: 600; color: #2d2d2d; margin: 0 0 12px; line-height: 1.3;">
                                    {{ $recipe->title }}
                                </h3>

                                <!-- CHEF -->
                                <p style="font-size: 14px; color: #7a7a7a; margin: 0 0 16px; display: flex; align-items: center; gap: 6px;">
                                    @if($recipe->chef_avatar)
                                        <img src="{{ asset('storage/' . $recipe->chef_avatar) }}" alt="{{ $recipe->chef_name }}" style="width: 20px; height: 20px; border-radius: 50%; object-fit: cover;">
                                    @else
                                        <span style="width: 20px; height: 20px; border-radius: 50%; background: #be3144; color: white; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600;">
                                            {{ substr($recipe->chef_name, 0, 1) }}
                                        </span>
                                    @endif
                                    Creat per {{ $recipe->chef_name }}
                                </p>

                                <!-- INFO TIEMPO Y DIFICULTAD -->
                                <div style="margin-top: auto; display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                    <span style="display: flex; align-items: center; gap: 4px; font-size: 13px; color: #7a7a7a;">
                                        <i class="bi bi-clock" style="font-size: 14px;"></i>
                                        {{ $recipe->cooking_time }} min
                                    </span>
                                    @if($recipe->rating > 0)
                                        <span style="display: flex; align-items: center; gap: 4px; font-size: 13px; color: #f59e0b;">
                                            <i class="bi bi-star-fill" style="font-size: 13px;"></i>
                                            {{ number_format($recipe->rating, 1) }}
                                        </span>
                                    @endif
                                </div>

                                <!-- BOTÓN VEURE DETALLS -->
                                <button onclick="event.preventDefault(); window.location.href = '{{ route('recipes.show', $recipe) }}';" style="width: 100%; padding: 12px; background: transparent; color: #be3144; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; text-align: center;">
                                    Veure detalls →
                                </button>
                            </div>
                        </div>
                    </a>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #7a7a7a;">
                        <p style="font-size: 16px; margin: 0;">No s'han trobat receptes.</p>
                    </div>
                @endforelse

            </div>
        </div>

        <!-- PAGINACIÓN -->
        @if($recipes->hasPages())
            <div style="padding: 24px 36px; border-top: 1px solid #e8e8e8; display: flex; justify-content: center;">
                {{ $recipes->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
