<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class RecipeController extends Controller
{
    /**
     * Mostrar listado de recetas (Receptes)
     */
    public function index(Request $request): View
    {
        $query = Recipe::query();
        $favoriteRecipeIds = [];

        // BÚSQUEDA por título, chef o ingredientes
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('chef_name', 'like', "%{$search}%")
                  ->orWhereJsonContains('tags', $search);
            });
        }

        // FILTRO por dificultad
        if ($request->filled('difficulty') && $request->difficulty !== 'tots') {
            $query->where('difficulty', $request->difficulty);
        }

        // ORDENAMIENTO
        $sortBy = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $allowedSorts = ['created_at', 'rating', 'title'];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // PAGINACIÓN (12 items para grid 3 columnas)
        $recipes = $query->paginate(12)->withQueryString();

        // Parámetros actuales para filtros
        $currentDifficulty = $request->input('difficulty', 'tots');

        // Carregam els IDs favorits una sola vegada i nomÃ©s si la taula pivot ja existeix.
        if ($request->user() && Schema::hasTable('favorites')) {
            $favoriteRecipeIds = $request->user()
                ->favoriteRecipes()
                ->pluck('recipes.id')
                ->all();
        }

        return view('recipes.index', compact('recipes', 'currentDifficulty', 'favoriteRecipeIds'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create(): View
    {
        return view('recipes.create');
    }

    /**
     * Guardar receta nueva (POST)
     */
    public function store(Request $request): RedirectResponse
    {
        // VALIDACIÓN
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'difficulty' => ['required', 'string', 'in:fàcil,mitjà,difícil'],
            'cooking_time' => ['required', 'integer', 'min:1', 'max:1000'],
            'tags' => ['nullable', 'string'],
            'description' => ['nullable', 'string', 'max:1000'],
            'ingredients' => ['nullable', 'string'],
            'chef_notes' => ['nullable', 'string', 'max:500'],
        ]);

        // Procesar tags (convertir string a array)
        $tags = [];
        if ($validated['tags'] ?? null) {
            $tags = array_map('trim', explode(',', $validated['tags']));
            $tags = array_filter($tags);
            $tags = array_map('strtoupper', $tags);
        }
        $validated['tags'] = $tags;

        // Procesar ingredientes (convertir string a array)
        $ingredients = [];
        if ($validated['ingredients'] ?? null) {
            $ingredients = array_map('trim', explode("\n", $validated['ingredients']));
            $ingredients = array_filter($ingredients);
        }
        $validated['ingredients'] = $ingredients;

        // Nom del chef: usuari autenticat o anònim
        if ($request->user()) {
            $validated['chef_name'] = $request->user()->name;
            $validated['chef_avatar'] = $request->user()->avatar ?? null;
            $validated['user_id'] = $request->user()->id;
        } else {
            $validated['chef_name'] = 'Chef Anònim';
            $validated['chef_avatar'] = null;
        }

        // Default rating
        $validated['rating'] = 0;

        // Crear la receta
        $recipe = Recipe::create($validated);

        return Redirect::route('recipes.show', $recipe)->with('status', 'recipe-created');
    }

    /**
     * Mostrar detalle de receta
     */
    public function show(Request $request, Recipe $recipe): View
    {
        // Consultam l'estat actual del favorit nomÃ©s si la infraestructura ja estÃ  migrada.
        $isFavorite = false;

        if ($request->user() && Schema::hasTable('favorites')) {
            $isFavorite = $request->user()
                ->favoriteRecipes()
                ->whereKey($recipe->id)
                ->exists();
        }

        return view('recipes.show', compact('recipe', 'isFavorite'));
    }
}
