<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Guarda o actualitza la puntuació d'una recepta.
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

        return back()->with('status', __('recipe.rating_success'));
    }
}
