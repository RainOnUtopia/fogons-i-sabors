<?php

namespace Database\Seeders;

use App\Models\Rating;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->get()->keyBy('email');
        $recipes = Recipe::query()->get()->keyBy('title');

        $ratings = [
            ['user' => 'test@example.com', 'recipe' => 'Risotto de safran amb pa d\'or', 'rating' => 5],
            ['user' => 'aina@fogons.local', 'recipe' => 'Risotto de safran amb pa d\'or', 'rating' => 4],
            ['user' => 'pau@fogons.local', 'recipe' => 'Risotto de safran amb pa d\'or', 'rating' => 5],
            ['user' => 'maria@fogons.local', 'recipe' => 'Bacalla negre amb glacejat de miso', 'rating' => 4],
            ['user' => 'test@example.com', 'recipe' => 'Bacalla negre amb glacejat de miso', 'rating' => 5],
            ['user' => 'aina@fogons.local', 'recipe' => 'Tagine de xai especiat', 'rating' => 4],
            ['user' => 'pau@fogons.local', 'recipe' => 'Pastis de llimona deconstruit', 'rating' => 5],
        ];

        foreach ($ratings as $ratingData) {
            if (! isset($users[$ratingData['user']], $recipes[$ratingData['recipe']])) {
                continue;
            }

            Rating::updateOrCreate(
                [
                    'user_id' => $users[$ratingData['user']]->id,
                    'recipe_id' => $recipes[$ratingData['recipe']]->id,
                ],
                [
                    'rating' => $ratingData['rating'],
                ]
            );
        }
    }
}
