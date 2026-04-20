<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_recipe(): void
    {
        $user = User::factory()->create(['name' => 'Chef Test']);

        $response = $this->actingAs($user)->post(route('recipes.store'), $this->recipePayload());

        $response->assertRedirect();
        $this->assertDatabaseHas('recipes', [
            'title' => 'Fideua marinera',
            'user_id' => $user->id,
            'chef_name' => 'Chef Test',
        ]);
    }

    public function test_owner_can_edit_recipe(): void
    {
        $user = User::factory()->create(['name' => 'Chef Edit']);
        $recipe = Recipe::factory()->for($user)->create([
            'title' => 'Recepta original',
            'chef_name' => $user->name,
        ]);

        $response = $this->actingAs($user)->patch(route('recipes.update', $recipe), [
            ...$this->recipePayload(),
            'title' => 'Recepta actualitzada',
        ]);

        $response->assertRedirect(route('recipes.show', $recipe));
        $this->assertDatabaseHas('recipes', [
            'id' => $recipe->id,
            'title' => 'Recepta actualitzada',
        ]);
    }

    public function test_owner_can_delete_recipe(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->for($user)->create();

        $response = $this->actingAs($user)->delete(route('recipes.destroy', $recipe));

        $response->assertRedirect(route('profile.show'));
        $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    }

    private function recipePayload(): array
    {
        return [
            'title' => 'Fideua marinera',
            'difficulty' => 'mitjà',
            'cooking_time' => 35,
            'tags' => 'mar, pasta',
            'description' => 'Un plat mariner complet.',
            'ingredients' => "fideus\ncalamar\nbrou",
            'steps' => "sofregir\nafegir brou\nreposar",
            'chef_notes' => 'Millor amb brou casola.',
        ];
    }
}
