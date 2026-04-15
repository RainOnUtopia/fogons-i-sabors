<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_favorites(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();

        $response = $this->actingAs($user)->post(route('recipes.favorite.store', $recipe));

        $response->assertRedirect();
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
        ]);
    }

    public function test_user_can_remove_favorites(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $user->favoriteRecipes()->attach($recipe->id);

        $response = $this->actingAs($user)->delete(route('recipes.favorite.destroy', $recipe));

        $response->assertRedirect();
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
        ]);
    }

    public function test_favorites_listing_is_displayed(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create(['title' => 'Escudella']);
        $user->favoriteRecipes()->attach($recipe->id);

        $response = $this->actingAs($user)->get(route('profile.show', ['tab' => 'favorites']));

        $response->assertOk();
        $response->assertSee('Plats favorits');
        $response->assertSee('Escudella');
    }

    public function test_favorites_can_be_filtered(): void
    {
        $user = User::factory()->create();
        $matching = Recipe::factory()->create([
            'title' => 'Peix al forn',
            'difficulty' => 'fàcil',
            'ingredients' => ['peix blanc'],
        ]);
        $other = Recipe::factory()->create([
            'title' => 'Xai a baixa temperatura',
            'difficulty' => 'difícil',
            'ingredients' => ['xai'],
        ]);

        $user->favoriteRecipes()->attach([$matching->id, $other->id]);

        $response = $this->actingAs($user)->get(route('profile.show', [
            'tab' => 'favorites',
            'f_search' => 'peix',
            'f_difficulty' => 'fàcil',
        ]));

        $response->assertOk();
        $response->assertSee('Peix al forn');
        $response->assertDontSee('Xai a baixa temperatura');
    }

    public function test_personal_recipe_listing_is_displayed(): void
    {
        $user = User::factory()->create();
        Recipe::factory()->for($user)->create(['title' => 'Mandonguilles']);

        $response = $this->actingAs($user)->get(route('profile.show'));

        $response->assertOk();
        $response->assertSee('El meu rebost');
        $response->assertSee('Mandonguilles');
    }

    public function test_personal_recipes_can_be_filtered(): void
    {
        $user = User::factory()->create();
        Recipe::factory()->for($user)->create([
            'title' => 'Arros negre',
            'difficulty' => 'mitjà',
            'ingredients' => ['sipia'],
        ]);
        Recipe::factory()->for($user)->create([
            'title' => 'Crema dolça',
            'difficulty' => 'fàcil',
            'ingredients' => ['nata'],
        ]);

        $response = $this->actingAs($user)->get(route('profile.show', [
            'r_search' => 'sipia',
            'r_difficulty' => 'mitjà',
        ]));

        $response->assertOk();
        $response->assertSee('Arros negre');
        $response->assertDontSee('Crema dolça');
    }
}
