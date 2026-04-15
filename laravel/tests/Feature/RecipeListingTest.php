<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_recipe_listing_is_displayed(): void
    {
        $recipe = Recipe::factory()->create([
            'title' => 'Arros de muntanya',
            'chef_name' => 'Aina',
        ]);

        $response = $this->get(route('recipes.index'));

        $response->assertOk();
        $response->assertSee('El Receptari');
        $response->assertSee($recipe->title);
    }

    public function test_recipe_detail_section_is_displayed(): void
    {
        $recipe = Recipe::factory()->create([
            'title' => 'Coca salada',
            'ingredients' => ['farina', 'ceba'],
            'steps' => ['Pastar', 'Enfornar'],
        ]);

        $comment = Comment::factory()->for($recipe)->create([
            'content' => 'Molt bona!',
        ]);

        $response = $this->get(route('recipes.show', $recipe));

        $response->assertOk();
        $response->assertSee($recipe->title);
        $response->assertSee('Ingredients');
        $response->assertSee('Passos a seguir');
        $response->assertSee($comment->content);
    }

    public function test_recipe_listing_can_be_filtered_by_search(): void
    {
        Recipe::factory()->create([
            'title' => 'Sopa de peix',
            'ingredients' => ['peix fresc', 'patata'],
        ]);

        Recipe::factory()->create([
            'title' => 'Carn rostida',
            'ingredients' => ['vedella', 'pastanaga'],
        ]);

        $response = $this->get(route('recipes.index', ['search' => 'peix']));

        $response->assertOk();
        $response->assertSee('Sopa de peix');
        $response->assertDontSee('Carn rostida');
    }

    public function test_recipe_listing_can_be_filtered_by_difficulty(): void
    {
        Recipe::factory()->create([
            'title' => 'Truita',
            'difficulty' => 'fàcil',
        ]);

        Recipe::factory()->create([
            'title' => 'Souffle',
            'difficulty' => 'difícil',
        ]);

        $response = $this->get(route('recipes.index', ['difficulty' => 'fàcil']));

        $response->assertOk();
        $response->assertSee('Truita');
        $response->assertDontSee('Souffle');
    }

    public function test_recipe_listing_can_be_sorted(): void
    {
        Recipe::factory()->create([
            'title' => 'Recepta baixa',
            'average_rating' => 1.5,
        ]);

        Recipe::factory()->create([
            'title' => 'Recepta alta',
            'average_rating' => 4.8,
        ]);

        $response = $this->get(route('recipes.index', [
            'sort' => 'average_rating',
            'direction' => 'desc',
        ]));

        $response->assertOk();
        $response->assertSeeInOrder(['Recepta alta', 'Recepta baixa']);
    }
}
