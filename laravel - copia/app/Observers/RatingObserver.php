<?php

namespace App\Observers;

use App\Models\Rating;

class RatingObserver
{
    /**
     * Gestiona el que es fa quan es crea una puntuacio
     */
    public function created(Rating $rating): void
    {
        $this->updateRecipeRating($rating->recipe_id);
    }

    /**
     *  Gestiona el que es fa quan es actualitza una puntuacio
     */
    public function updated(Rating $rating): void
    {
        $this->updateRecipeRating($rating->recipe_id);
    }

    /**
     *  Gestiona el que es fa quan s'esborra una puntuacio
     */
    public function deleted(Rating $rating): void
    {
        $this->updateRecipeRating($rating->recipe_id);
    }

    /**
     * Recalcula la mitjana i el comptador d'una recepta i la guarda.
     */
    private function updateRecipeRating($recipeId): void
    {
        $recipe = \App\Models\Recipe::find($recipeId);

        if ($recipe) {
            $recipe->average_rating = $recipe->ratings()->avg('rating') ?: 0;
            $recipe->ratings_count = $recipe->ratings()->count();
            $recipe->save();
        }
    }
}
