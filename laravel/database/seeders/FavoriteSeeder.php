<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $favoritesMap = [
            'test@example.com' => [
                'Risotto de safran amb pa d\'or',
                'Bacalla negre amb glacejat de miso',
            ],
            'aina@fogons.local' => [
                'Pastis de llimona deconstruit',
                'Wagyu infusionat amb tofona',
            ],
            'pau@fogons.local' => [
                'Consomme de bolets salvatges',
                'Tagine de xai especiat',
            ],
            'maria@fogons.local' => [
                'Risotto de safran amb pa d\'or',
                'Bacalla negre amb glacejat de miso',
                'Tagine de xai especiat',
            ],
        ];

        foreach ($favoritesMap as $userEmail => $recipeTitles) {
            $user = User::where('email', $userEmail)->first();

            if (! $user) {
                continue;
            }

            $recipeIds = Recipe::whereIn('title', $recipeTitles)->pluck('id')->all();

            if ($recipeIds !== []) {
                $user->favoriteRecipes()->syncWithoutDetaching($recipeIds);
            }
        }
    }
}
