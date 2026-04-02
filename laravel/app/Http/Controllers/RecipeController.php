<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RecipeController extends Controller
{
    /**
     * Mostrar listado de recetas (Receptes)
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $currentDifficulty = $request->input('difficulty', 'tots');
        $favoriteRecipeIds = [];

        // Aplicam els filtres només quan existeixen per mantenir la consulta clara i incremental.
        $query = Recipe::query()
            ->when($search !== '', function ($recipeQuery) use ($search) {
                $recipeQuery->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('chef_name', 'like', "%{$search}%")
                        ->orWhereJsonContains('tags', $search)
                        ->orWhereJsonContains('ingredients', $search);
                });
            })
            ->when($currentDifficulty !== 'tots', function ($recipeQuery) use ($currentDifficulty) {
                $recipeQuery->where('difficulty', $currentDifficulty);
            });

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

        // Carregam els IDs favorits una sola vegada i només si la taula pivot ja existeix.
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
     * Mostrar el formulari d'edició només al propietari de la recepta.
     */
    public function edit(Request $request, Recipe $recipe): View
    {
        $this->ensureRecipeOwner($request, $recipe);

        return view('recipes.create', [
            'recipe' => $recipe,
        ]);
    }

    /**
     * Guardar receta nueva (POST)
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->prepareRecipePayload($request);

        // Nom del xef: usuari autenticat o anònim
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

        // Crear la recepta
        $recipe = Recipe::create($validated);

        return Redirect::route('recipes.show', $recipe)->with('status', 'recipe-created');
    }

    /**
     * Actualitzar una recepta existent del mateix autor.
     */
    public function update(Request $request, Recipe $recipe): RedirectResponse
    {
        $this->ensureRecipeOwner($request, $recipe);

        $validated = $this->prepareRecipePayload($request, $recipe);
        $validated['chef_name'] = $request->user()->name;
        $validated['chef_avatar'] = $request->user()->avatar ?? null;

        $recipe->update($validated);

        return Redirect::route('recipes.show', $recipe)->with('status', 'recipe-updated');
    }

    /**
     * Eliminar una recepta pròpia i netejar la seva imatge del storage.
     */
    public function destroy(Request $request, Recipe $recipe): RedirectResponse
    {
        $this->ensureRecipeOwner($request, $recipe);

        if ($recipe->image && Storage::disk('public')->exists($recipe->image)) {
            Storage::disk('public')->delete($recipe->image);
        }

        $recipe->delete();

        return Redirect::route('profile.show')->with('status', 'recipe-deleted');
    }

    /**
     * Mostrar detalle de receta
     */
    public function show(Request $request, Recipe $recipe): View
    {
        // Consultam l'estat actual del favorit només si la infraestructura ja està migrada.
        $isFavorite = false;

        if ($request->user() && Schema::hasTable('favorites')) {
            $isFavorite = $request->user()
                ->favoriteRecipes()
                ->whereKey($recipe->id)
                ->exists();
        }

        return view('recipes.show', compact('recipe', 'isFavorite'));
    }

    /**
     * Validam i preparam les dades comunes del formulari de receptes.
     */
    private function prepareRecipePayload(Request $request, ?Recipe $recipe = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'difficulty' => ['required', 'string', 'in:fàcil,mitjà,difícil'],
            'cooking_time' => ['required', 'integer', 'min:1', 'max:1000'],
            'tags' => ['nullable', 'string'],
            'description' => ['nullable', 'string', 'max:1000'],
            'ingredients' => ['nullable', 'string'],
            'chef_notes' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // Convertim les etiquetes de text a array JSON.
        $validated['tags'] = ($validated['tags'] ?? null)
            ? array_values(array_map('strtoupper', array_filter(array_map('trim', explode(',', $validated['tags'])))))
            : [];

        // Convertim els ingredients de text a array JSON.
        $validated['ingredients'] = ($validated['ingredients'] ?? null)
            ? array_values(array_filter(array_map('trim', explode("\n", $validated['ingredients']))))
            : [];

        // Substituïm la imatge antiga només si l'usuari n'ha pujat una de nova.
        if ($request->hasFile('image')) {
            if ($recipe?->image && Storage::disk('public')->exists($recipe->image)) {
                Storage::disk('public')->delete($recipe->image);
            }

            $validated['image'] = $request->file('image')->store('recipes', 'public');
        } else {
            unset($validated['image']);
        }

        return $validated;
    }

    /**
     * Restringim l'edició a l'autor original de la recepta.
     */
    private function ensureRecipeOwner(Request $request, Recipe $recipe): void
    {
        abort_unless(
            $request->user() && (int) $recipe->user_id === (int) $request->user()->id,
            403
        );
    }
}
