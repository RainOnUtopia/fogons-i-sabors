<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;

/**
 * Controlador per gestionar les receptes favorites dels usuaris.
 * 
 * Permet marcar receptes com a favorites i desmarcar-les, així com
 * gestionar la redirecció segons l'origen de la petició.
 * 
 * @package App\Http\Controllers
 */
class FavoriteController extends Controller
{
    /**
     * Afegeix una recepta als preferits de l'usuari autenticat.
     * 
     * @param Request $request Petició de l'usuari.
     * @param Recipe $recipe La recepta a afegir com a favorita.
     * @return RedirectResponse Redirecció enrere o al perfil segons els paràmetres.
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
     * 
     * @param Request $request Petició de l'usuari.
     * @param Recipe $recipe La recepta a eliminar de favorites.
     * @return RedirectResponse Redirecció enrere o al perfil segons els paràmetres.
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
