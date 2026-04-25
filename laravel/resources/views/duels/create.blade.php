@extends('layouts.app')

@section('content')
    @php
        $canCreateDuel = $myRecipes->isNotEmpty() && $challengedUsers->isNotEmpty();
        $selectedChallengedId = old('challenged_id');
    @endphp

    <div class="section-ui">
        <div class="d-flex justify-content-between align-items-start gap-4 mx-auto mb-5" style="max-width: 840px; width: 100%;">
            <div class="text-center flex-grow-1">
                <h1 class="recipe-hero-title mb-2">Obre un nou duel</h1>
                <p class="recipe-hero-subtitle mb-0">Escull la teva recepta, tria a qui vols reptar i decideix fins quan es podrà votar.</p>
            </div>
        </div>

        <div class="recipe-form-container">
            <div class="recipe-form-header-box">
                <div class="flex-grow-1">
                    <h2 class="recipe-form-title">Configuració del duel</h2>
                    <p class="recipe-form-subtitle">Pots tenir fins a 3 duels actius com a retador. Si no canvies la data, el duel es tancarà d'aquí a 14 dies.</p>
                </div>
                <a href="{{ route('duels.index') }}" class="btn-secondary-ui">
                    <i class="bi bi-arrow-left"></i>
                    Tornar
                </a>
            </div>

            <div class="recipe-form-body">
                @if ($errors->any())
                    <div class="recipe-alert-error">
                        <div class="d-flex align-items-center gap-2 fw-semibold" style="font-size: 14px;">
                            <i class="bi bi-exclamation-circle"></i>
                            <span>Revisa les dades del duel</span>
                        </div>
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-1" style="font-size: 13px;">
                            @foreach ($errors->all() as $error)
                                <li class="position-relative lh-base ps-3">
                                    <span class="position-absolute start-0">•</span>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($myRecipes->isEmpty() || $challengedUsers->isEmpty())
                    <div class="duel-empty-state duel-empty-state--compact mb-4">
                        <div class="duel-empty-icon">
                            <i class="bi bi-exclamation-diamond"></i>
                        </div>
                        <h3 class="duel-empty-title">Ara mateix no pots crear aquest duel</h3>
                        <p class="duel-empty-text">
                            @if($myRecipes->isEmpty())
                                Abans de reptar algú, necessites tenir com a mínim una recepta teva publicada.
                            @else
                                Encara no hi ha cap altre usuari amb receptes disponibles per poder-lo reptar.
                            @endif
                        </p>
                        @if($myRecipes->isEmpty())
                            <a href="{{ route('recipes.create') }}" class="btn-primary-ui">
                                <i class="bi bi-plus-lg"></i>
                                Publicar una recepta
                            </a>
                        @endif
                    </div>
                @endif

                <form method="POST" action="{{ route('duels.store') }}" class="d-flex flex-column gap-4">
                    @csrf

                    <div class="row g-4">
                        <div class="col-md-6 recipe-form-group">
                            <label for="challenger_recipe_id" class="recipe-form-label">La teva recepta</label>
                            <select
                                id="challenger_recipe_id"
                                name="challenger_recipe_id"
                                class="recipe-form-select @error('challenger_recipe_id') recipe-form-error @enderror"
                                {{ $myRecipes->isEmpty() ? 'disabled' : '' }}
                                required
                            >
                                <option value="">-- Selecciona una recepta --</option>
                                @foreach($myRecipes as $recipe)
                                    <option value="{{ $recipe->id }}" {{ (string) old('challenger_recipe_id') === (string) $recipe->id ? 'selected' : '' }}>
                                        {{ $recipe->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('challenger_recipe_id')
                                <div class="recipe-form-error-msg">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 recipe-form-group">
                            <label for="challenged_id" class="recipe-form-label">Rival</label>
                            <select
                                id="challenged_id"
                                name="challenged_id"
                                class="recipe-form-select @error('challenged_id') recipe-form-error @enderror"
                                {{ $challengedUsers->isEmpty() ? 'disabled' : '' }}
                                required
                            >
                                <option value="">-- Selecciona un rival --</option>
                                @foreach($challengedUsers as $challengedUser)
                                    <option value="{{ $challengedUser->id }}" {{ (string) $selectedChallengedId === (string) $challengedUser->id ? 'selected' : '' }}>
                                        {{ $challengedUser->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('challenged_id')
                                <div class="recipe-form-error-msg">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6 recipe-form-group">
                            <label for="challenged_recipe_id" class="recipe-form-label">Recepta rival</label>
                            <select
                                id="challenged_recipe_id"
                                name="challenged_recipe_id"
                                class="recipe-form-select @error('challenged_recipe_id') recipe-form-error @enderror"
                                data-old-value="{{ old('challenged_recipe_id') }}"
                                required
                            >
                                <option value="">-- Tria primer el rival --</option>
                            </select>
                            <p class="small text-secondary mt-1 mb-0">Quan triïs un rival, aquí veuràs quines receptes seves pots reptar.</p>
                            @error('challenged_recipe_id')
                                <div class="recipe-form-error-msg">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 recipe-form-group">
                            <label for="end_date" class="recipe-form-label">Data final</label>
                            <input
                                type="date"
                                id="end_date"
                                name="end_date"
                                value="{{ old('end_date', $defaultEndDate) }}"
                                min="{{ now()->addDay()->toDateString() }}"
                                class="recipe-form-input @error('end_date') recipe-form-error @enderror"
                            >
                            <p class="small text-secondary mt-1 mb-0">Si no la canvies, farem servir una data de tancament a 14 dies. Si la canvies, ha de ser posterior a avui.</p>
                            @error('end_date')
                                <div class="recipe-form-error-msg">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="duel-form-helper-card">
                        <div>
                            <h3 class="duel-form-helper-title">Què passarà després?</h3>
                            <p class="duel-form-helper-text mb-0">Quan el duel estigui obert, la comunitat podrà votar les dues receptes. Quan arribi la data final, aquí mateix podràs veure qui s'ha endut la victòria.</p>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-3 pt-4 border-top">
                        <button type="submit" class="btn-primary-ui" {{ $canCreateDuel ? '' : 'disabled' }}>
                            <i class="bi bi-trophy"></i>
                            Crear duel
                        </button>
                        <a href="{{ route('duels.my-duels') }}" class="btn-secondary-ui">
                            <i class="bi bi-collection"></i>
                            Veure els meus duels
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rivalSelect = document.getElementById('challenged_id');
            const rivalRecipeSelect = document.getElementById('challenged_recipe_id');
            const challengedRecipesByUser = @json($challengedRecipesByUser);

            if (!rivalSelect || !rivalRecipeSelect) {
                return;
            }

            const populateRivalRecipes = () => {
                const selectedUserId = rivalSelect.value;
                const previousValue = rivalRecipeSelect.dataset.oldValue || '';
                const recipes = challengedRecipesByUser[selectedUserId] || [];

                rivalRecipeSelect.innerHTML = '';

                if (!selectedUserId || recipes.length === 0) {
                    rivalRecipeSelect.innerHTML = '<option value="">-- Tria primer el rival --</option>';
                    rivalRecipeSelect.disabled = true;
                    return;
                }

                rivalRecipeSelect.disabled = false;
                rivalRecipeSelect.insertAdjacentHTML('beforeend', '<option value="">-- Selecciona la recepta rival --</option>');

                recipes.forEach((recipe) => {
                    const option = document.createElement('option');
                    option.value = recipe.id;
                    option.textContent = recipe.title;

                    if (String(recipe.id) === previousValue) {
                        option.selected = true;
                    }

                    rivalRecipeSelect.appendChild(option);
                });
            };

            rivalSelect.addEventListener('change', function () {
                rivalRecipeSelect.dataset.oldValue = '';
                populateRivalRecipes();
            });

            populateRivalRecipes();
        });
    </script>
@endpush
