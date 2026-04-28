<?php

namespace Database\Factories;

use App\Models\Duel;
use App\Models\DuelVote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DuelVote>
 */
class DuelVoteFactory extends Factory
{
    protected $model = DuelVote::class;

    public function definition(): array
    {
        return [
            'duel_id' => Duel::factory(),
            'user_id' => User::factory(),
            'recipe_id' => function (array $attributes) {
                $duel = Duel::query()->findOrFail($attributes['duel_id']);
                return $duel->challenger_recipe_id;
            },
            'rating' => $this->faker->numberBetween(1, 5),
        ];
    }
}

