<?php

namespace Tests\Feature;

use App\Models\Rating;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_rate_recipe(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson(route('recipes.rate', $recipe), [
                'rating' => 4,
            ]);

        $response->assertOk()->assertJsonPath('success', true);
        $this->assertDatabaseHas('ratings', [
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
            'rating' => 4,
        ]);
    }

    public function test_recipe_rating_average_is_calculated(): void
    {
        $recipe = Recipe::factory()->create();
        $users = User::factory()->count(2)->create();

        Rating::create([
            'user_id' => $users[0]->id,
            'recipe_id' => $recipe->id,
            'rating' => 5,
        ]);

        Rating::create([
            'user_id' => $users[1]->id,
            'recipe_id' => $recipe->id,
            'rating' => 3,
        ]);

        $recipe->refresh();

        $this->assertSame(4.0, (float) $recipe->average_rating);
        $this->assertSame(2, $recipe->ratings_count);
    }
}
