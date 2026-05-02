<?php

namespace Database\Factories;

use App\Models\Duel;
use App\Models\DuelComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DuelComment>
 */
class DuelCommentFactory extends Factory
{
    protected $model = DuelComment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'duel_id' => Duel::factory(),
            'parent_id' => null,
            'content' => $this->faker->paragraph(),
            'is_deleted' => false,
        ];
    }
}

