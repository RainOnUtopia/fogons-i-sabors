<?php

namespace Database\Factories;

use App\Models\Duel;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Duel>
 */
class DuelFactory extends Factory
{
    protected $model = Duel::class;

    public function definition(): array
    {
        $startDate = now();
        $endDate = $startDate->copy()->addDays(14);

        return [
            'challenger_id' => User::factory(),
            'challenged_id' => User::factory(),
            'challenger_recipe_id' => function (array $attributes) {
                return Recipe::factory()->create(['user_id' => $attributes['challenger_id']])->id;
            },
            'challenged_recipe_id' => function (array $attributes) {
                return Recipe::factory()->create(['user_id' => $attributes['challenged_id']])->id;
            },
            'challenger_average_rating' => 0,
            'challenged_average_rating' => 0,
            'challenger_votes_count' => 0,
            'challenged_votes_count' => 0,
            'status' => 'iniciat',
            'duel_result' => null,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'winner_recipe_id' => null,
            'winner_user_id' => null,
        ];
    }

    public function finalitzat(): static
    {
        return $this->state(fn () => [
            'status' => 'finalitzat',
        ]);
    }
}

