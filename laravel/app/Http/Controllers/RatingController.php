<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

/**
 * Controlador per gestionar les puntuacions (valoracions) de les receptes.
 * 
 * Permet als usuaris puntuar les receptes amb un valor d'1 a 5.
 * Suporta peticions tradicionals i AJAX.
 * 
 * @package App\Http\Controllers
 */
class RatingController extends Controller
{
    /**
     * Guarda o actualitza la puntuació d'una recepta.
     * 
     * @param Request $request Petició amb la puntuació seleccionada.
     * @param Recipe $recipe La recepta que es puntua.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse Resposta JSON amb la nova mitjana o redirecció enrere.
     */
    public function store(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $recipe->ratings()->updateOrCreate(
            ['user_id' => $request->user()->id],
            ['rating' => $validated['rating']]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('recipe.rating_success'),
                'average_rating' => $recipe->fresh()->average_rating,
                'user_rating' => $validated['rating']
            ]);
        }

        return back()->with('status', __('recipe.rating_success'));
    }
}
