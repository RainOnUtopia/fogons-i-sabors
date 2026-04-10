<?php

namespace App\Observers;

use App\Models\Favorite;
use App\Models\Recipe;

class FavoriteObserver
{
    /**
     * Handle the Favorite "created" event.
     */
    public function created(Favorite $favorite): void
    {
        $this->updateRecipeFavoriteCount($favorite->recipe_id);
    }

    /**
     * Handle the Favorite "deleted" event.
     */
    public function deleted(Favorite $favorite): void
    {
        $this->updateRecipeFavoriteCount($favorite->recipe_id);
    }

    /**
     * Recalcula el total de favorits d'una recepta i ho actualitza a la taula.
     */
    private function updateRecipeFavoriteCount($recipeId): void
    {
        $recipe = Recipe::find($recipeId);

        if ($recipe) {
            $recipe->favorites_count = $recipe->favoritedByUsers()->count();
            $recipe->save();
        }
    }
}
