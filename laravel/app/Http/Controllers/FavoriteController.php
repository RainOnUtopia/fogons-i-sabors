<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;

class FavoriteController extends Controller
{
    /**
     * Controlador responsable de gestionar els favorits de l'usuari autenticat.
     * Afegeix una recepta als preferits evitant duplicats.
     */
    public function store(Request $request, Recipe $recipe): RedirectResponse
    {
        // Evitam errors si encara no s'ha executat la migració de favorits.
        if (! Schema::hasTable('favorites')) {
            return Redirect::back()->with('status', 'favorites-unavailable');
        }

        $request->user()->favoriteRecipes()->syncWithoutDetaching([$recipe->id]);

        if ($request->input('redirect_tab') === 'favorites') {
            return Redirect::route('profile.show', ['tab' => 'favorites']);
        }

        return Redirect::back();
    }

    /**
     * Elimina una recepta dels preferits de l'usuari autenticat.
     */
    public function destroy(Request $request, Recipe $recipe): RedirectResponse
    {
        // Evitam errors si encara no s'ha executat la migració de favorits.
        if (! Schema::hasTable('favorites')) {
            return Redirect::back()->with('status', 'favorites-unavailable');
        }

        $request->user()->favoriteRecipes()->detach($recipe->id);

        if ($request->input('redirect_tab') === 'favorites') {
            return Redirect::route('profile.show', ['tab' => 'favorites']);
        }

        return Redirect::back();
    }
}
