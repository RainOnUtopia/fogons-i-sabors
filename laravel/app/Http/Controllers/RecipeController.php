<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Controlador de receptes culinàries.
 * 
 * Gestiona el cicle de vida de les receptes: llistat públic, visualització de detalls,
 * creació, edició i eliminació per part dels autors.
 * 
 * @package App\Http\Controllers
 */
class RecipeController extends Controller
{
    /**
     * Mostra el llistat de receptes amb cerca i filtrat.
     * 
     * @param Request $request Petició amb paràmetres de cerca, dificultat i ordenació.
     * @return View Vista amb la col·lecció paginada de receptes.
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
                        ->orWhere('tags', 'like', "%{$search}%")
                         ->orWhere(function ($q) use ($search) {
                             if ($q->getConnection()->getDriverName() === 'sqlite') {
                                 $q->where('ingredients', 'like', "%{$search}%");
                             } else {
                                 $q->whereRaw("JSON_SEARCH(ingredients, 'one', ?) IS NOT NULL", ["%{$search}%"]);
                             }
                         });
                });
            })
            ->when($currentDifficulty !== 'tots', function ($recipeQuery) use ($currentDifficulty) {
                $recipeQuery->where('difficulty', $currentDifficulty);
            });

        // ORDENACIÓ
        $sortBy = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $allowedSorts = ['created_at', 'average_rating', 'title'];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // PAGINACIÓ (12 items per a grid de 3 columnes)
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
     * Mostra el formulari de creació d'una recepta.
     * 
     * @return View Vista del formulari de creació.
     */
    public function create(): View
    {
        return view('recipes.create');
    }

    /**
     * Mostra el formulari d'edició només al propietari de la recepta.
     * 
     * @param Request $request Petició de l'usuari.
     * @param Recipe $recipe La recepta a editar.
     * @return View Vista del formulari d'edició (reutilitza la de creació).
     */
    public function edit(Request $request, Recipe $recipe): View
    {
        $this->ensureRecipeOwner($request, $recipe);

        return view('recipes.create', [
            'recipe' => $recipe,
        ]);
    }

    /**
     * Guarda una recepta nova (POST).
     * 
     * @param Request $request Petició amb les dades de la recepta i la imatge.
     * @return RedirectResponse Redirecció al detall de la recepta creada.
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

        // Crear la recepta
        $recipe = Recipe::create($validated);

        return Redirect::route('recipes.show', $recipe)->with('status', 'recipe-created');
    }

    /**
     * Actualitza una recepta existent del mateix autor.
     * 
     * @param Request $request Petició amb les dades actualitzades.
     * @param Recipe $recipe La recepta a actualitzar.
     * @return RedirectResponse Redirecció al detall de la recepta actualitzada.
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
     * Elimina una recepta pròpia i neteja la seva imatge del storage.
     * 
     * @param Request $request Petició de l'usuari.
     * @param Recipe $recipe La recepta a eliminar.
     * @return RedirectResponse Redirecció al perfil de l'usuari.
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
     * Mostra el detall d'una recepta específica.
     * 
     * @param Request $request Petició de l'usuari.
     * @param Recipe $recipe La recepta a mostrar.
     * @return View Vista amb la informació detallada de la recepta.
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

        // Càrrega ansiosa (Eager Loading) de comentaris i respostes amb usuaris per evitar N+1
        $recipe->load(['topLevelComments.user', 'topLevelComments.replies.user']);

        // Carregam el vot particular de l'usuari per evitar consultes innecessàries
        if ($request->user()) {
            $recipe->load('userRating');
        }

        return view('recipes.show', compact('recipe', 'isFavorite'));
    }

    /**
     * Valida i prepara les dades comunes del formulari de receptes.
     * 
     * Converteix cadenes de text (etiquetes, ingredients, passos) en arrays JSON
     * i gestiona la pujada d'imatges reemplaçant l'anterior si escau.
     * 
     * @param Request $request Petició original.
     * @param Recipe|null $recipe Instància de la recepta en cas d'actualització.
     * @return array Dades validades i processades.
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
            'steps' => ['nullable', 'string'],
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

        // Convertim els passos de text a array JSON.
        $validated['steps'] = ($validated['steps'] ?? null)
            ? array_values(array_filter(array_map('trim', explode("\n", $validated['steps']))))
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
     * Restringeix l'accés només a l'autor original de la recepta.
     * 
     * @param Request $request Petició de l'usuari.
     * @param Recipe $recipe La recepta a comprovar.
     * @return void
     * @throws \Illuminate\Http\Exceptions\HttpResponseException Si l'usuari no és el propietari (403).
     */
    private function ensureRecipeOwner(Request $request, Recipe $recipe): void
    {
        abort_unless(
            $request->user() && (int) $recipe->user_id === (int) $request->user()->id,
            403
        );
    }
}
