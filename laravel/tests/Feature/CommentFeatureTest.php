<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_comment(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson(route('recipes.comments.store', $recipe), [
                'content' => 'Comentari nou',
            ]);

        $response->assertOk()->assertJsonPath('success', true);
        $this->assertDatabaseHas('comments', [
            'recipe_id' => $recipe->id,
            'user_id' => $user->id,
            'content' => 'Comentari nou',
        ]);
    }

    public function test_comment_owner_can_edit_comment(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $comment = Comment::create([
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
            'content' => 'Text antic',
            'is_deleted' => false,
        ]);

        $response = $this
            ->actingAs($user)
            ->putJson(route('comments.update', $comment), [
                'content' => 'Text nou',
            ]);

        $response->assertOk()->assertJsonPath('success', true);
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Text nou',
        ]);
    }

    public function test_comment_owner_can_delete_comment(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $comment = Comment::create([
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
            'content' => 'A esborrar',
            'is_deleted' => false,
        ]);

        $response = $this
            ->actingAs($user)
            ->deleteJson(route('comments.destroy', $comment));

        $response->assertOk()->assertJsonPath('success', true);
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'is_deleted' => true,
        ]);
    }
}
