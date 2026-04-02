@extends('layouts.app')

@section('content')
<div style="width: 100vw; min-height: 100vh; background-color: #f4efea; background-image: linear-gradient(0deg, transparent 24%, rgba(200, 200, 200, 0.05) 25%, rgba(200, 200, 200, 0.05) 26%, transparent 27%, transparent 74%, rgba(200, 200, 200, 0.05) 75%, rgba(200, 200, 200, 0.05) 76%, transparent 77%, transparent), linear-gradient(90deg, transparent 24%, rgba(200, 200, 200, 0.05) 25%, rgba(200, 200, 200, 0.05) 26%, transparent 27%, transparent 74%, rgba(200, 200, 200, 0.05) 75%, rgba(200, 200, 200, 0.05) 76%, transparent 77%, transparent); background-size: 50px 50px; padding: 40px 20px; margin: 0; margin-left: calc(-50vw + 50%); display: flex; flex-direction: column;">

    <!-- BREADCRUMB -->
    <div style="max-width: 1100px; width: 100%; margin: 0 auto 40px;">
        <nav style="display: flex; align-items: center; gap: 12px; font-size: 13px; color: #7a7a7a; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
            <a href="/" style="color: #be3144; text-decoration: none;">INICI</a>
            <span style="color: #d0d0d0;">›</span>
            <a href="{{ route('recipes.index') }}" style="color: #be3144; text-decoration: none;">RECEPTES</a>
            <span style="color: #d0d0d0;">›</span>
            <span style="color: #2d2d2d;">{{ strtoupper($recipe->title) }}</span>
        </nav>
    </div>

    <!-- CONTENEDOR PRINCIPAL - LAYOUT DOS COLUMNAS -->
    <div style="max-width: 1100px; width: 100%; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: start;">

            <!-- COLUMNA IZQUIERDA - IMAGEN Y BOTONES -->
            <div>
                <!-- IMAGEN GRANDE -->
                <div style="position: relative; margin-bottom: 24px; border-radius: 24px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);">
                    @if($recipe->image)
                        <img src="{{ asset('storage/' . $recipe->image) }}" alt="{{ $recipe->title }}" style="width: 100%; height: auto; display: block; aspect-ratio: 16/10; object-fit: cover;">
                    @else
                        <div style="width: 100%; aspect-ratio: 16/10; background: linear-gradient(135deg, #e0e0e0 0%, #f3f3f3 100%); display: flex; align-items: center; justify-content: center; font-size: 64px; color: #999;">
                            <i class="bi bi-image"></i>
                        </div>
                    @endif
                </div>

                @auth
                    <!-- Botons exclusius per a usuaris autenticats -->
                    <div style="display: flex; gap: 12px;">
                        <!-- DESAR A PREFERITS -->
                        <form method="POST"
                            action="{{ $isFavorite ? route('recipes.favorite.destroy', $recipe) : route('recipes.favorite.store', $recipe) }}"
                            style="flex: 1;">
                            @csrf
                            @if($isFavorite)
                                @method('DELETE')
                            @endif
                            <button type="submit"
                                style="width: 100%; height: 44px; background-color: #be3144; color: white; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                                <i class="bi {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                <span>{{ $isFavorite ? 'Treure de Favorits' : 'Desar a Preferits' }}</span>
                            </button>
                        </form>

                        <!-- MODE CUINA -->
                        <button style="flex: 1; height: 44px; background-color: #2d2d2d; color: white; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                            <i class="bi bi-suit-heart-fill"></i>
                            <span>Mode Cuina</span>
                        </button>
                    </div>
                @endauth

                <!-- ICONES COMPARTIR I IMPRIMIR -->
                <div style="display: flex; gap: 12px; margin-top: 12px;">
                    <button style="width: 44px; height: 44px; background: white; border: 1px solid #e8e8e8; color: #7a7a7a; border-radius: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; font-size: 18px;">
                        <i class="bi bi-share"></i>
                    </button>
                    <button style="width: 44px; height: 44px; background: white; border: 1px solid #e8e8e8; color: #7a7a7a; border-radius: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; font-size: 18px;">
                        <i class="bi bi-printer"></i>
                    </button>
                </div>
            </div>

            <!-- COLUMNA DERECHA - INFORMACIÓN -->
            <div>
                <!-- BADGES ETIQUETAS -->
                @if($recipe->tags && count($recipe->tags) > 0)
                    <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 16px;">
                        @foreach($recipe->tags as $tag)
                            <span style="display: inline-block; background: #f3f3f3; color: #be3144; border-radius: 6px; font-size: 11px; font-weight: 600; padding: 6px 12px; text-transform: uppercase; letter-spacing: 0.4px;">
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <!-- TÍTULO GRANDE -->
                <h1 style="font-family: Georgia, Garamond, serif; font-style: italic; font-size: 2.4rem; font-weight: 700; color: #2d2d2d; margin: 0 0 20px; letter-spacing: -0.5px; line-height: 1.2;">
                    {{ $recipe->title }}
                </h1>

                <!-- INFORMACIÓN: TIEMPO, DIFICULTAD, RATING -->
                <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 24px; flex-wrap: wrap;">
                    <!-- TIEMPO -->
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-clock" style="font-size: 18px; color: #be3144;"></i>
                        <span style="font-size: 14px; color: #7a7a7a; font-weight: 500;">{{ $recipe->cooking_time }} min</span>
                    </div>

                    <!-- DIFICULTAD -->
                    <span style="display: inline-block; background: #f3f3f3; color: #2d2d2d; border-radius: 6px; font-size: 12px; font-weight: 600; padding: 6px 12px; text-transform: capitalize;">
                        {{ ucfirst($recipe->difficulty) }}
                    </span>

                    <!-- RATING -->
                    @if($recipe->rating > 0)
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <span style="font-size: 18px;">⭐</span>
                            <span style="font-size: 14px; color: #2d2d2d; font-weight: 600;">{{ number_format($recipe->rating, 1) }}</span>
                        </div>
                    @endif
                </div>

                <!-- DESCRIPCIÓN -->
                @if($recipe->description)
                    <p style="font-size: 15px; color: #7a7a7a; line-height: 1.6; margin: 0 0 28px;">
                        {{ $recipe->description }}
                    </p>
                @endif

                <!-- SECCIÓN INGREDIENTES -->
                @if($recipe->ingredients && count($recipe->ingredients) > 0)
                    <div style="margin-bottom: 32px;">
                        <h3 style="font-family: Georgia, Garamond, serif; font-size: 1.3rem; font-weight: 700; color: #be3144; margin: 0 0 16px; display: flex; align-items: center; gap: 6px;">
                            <i class="bi bi-check2-circle " style="font-size: 20px;"></i>
                            Ingredients
                        </h3>
                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px;">
                            @foreach($recipe->ingredients as $ingredient)
                                <li style="font-size: 14px; color: #2d2d2d; padding-left: 20px; position: relative;">
                                    <span style="position: absolute; left: 0; color: #be3144; font-weight: bold;">•</span>
                                    {{ $ingredient }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- SECCIÓN NOTES DEL XEF -->
                @if($recipe->chef_notes || $recipe->chef_name)
                    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);">
                        <h3 style="font-size: 14px; font-weight: 600; color: #7a7a7a; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 16px;">Notes del Xef</h3>

                        @if($recipe->chef_notes)
                            <blockquote style="font-style: italic; font-size: 15px; color: #2d2d2d; margin: 0 0 20px; border-left: 3px solid #be3144; padding-left: 16px; line-height: 1.6;">
                                "{{ $recipe->chef_notes }}"
                            </blockquote>
                        @endif

                        <!-- CHEF INFO -->
                        <div style="display: flex; align-items: center; gap: 12px;">
                            @if($recipe->chef_avatar)
                                <img src="{{ asset('storage/' . $recipe->chef_avatar) }}" alt="{{ $recipe->chef_name }}" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid #be3144;">
                            @else
                                <div style="width: 48px; height: 48px; border-radius: 50%; background: #be3144; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 18px;">
                                    {{ substr($recipe->chef_name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <div style="font-weight: 600; color: #2d2d2d; font-size: 14px;">{{ $recipe->chef_name }}</div>
                                <div style="font-size: 12px; color: #7a7a7a; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600;">Chef Executiu</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if(session('status') === 'recipe-created')
        <div style="margin-top: 20px; max-width: 1100px; width: 100%; margin-left: auto; margin-right: auto; padding: 12px 16px; background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; border-radius: 12px; font-size: 14px;">
            <i class="bi bi-check-circle"></i> Recepta creada exitosament!
        </div>
    @endif

</div>

<style>
    @media (max-width: 768px) {
        div[style*="grid-template-columns: 1fr 1fr"] {
            grid-template-columns: 1fr !important;
            gap: 24px !important;
        }
    }
</style>
@endsection
