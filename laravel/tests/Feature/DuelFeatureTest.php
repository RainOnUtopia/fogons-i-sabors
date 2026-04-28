<?php

namespace Tests\Feature;

use App\Models\Duel;
use App\Models\DuelVote;
use App\Models\Recipe;
use App\Models\User;
use App\Services\DuelService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DuelFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_a_duel(): void
    {
        $challenger = User::factory()->create();
        $challenged = User::factory()->create();

        $challengerRecipe = Recipe::factory()->create(['user_id' => $challenger->id]);
        $challengedRecipe = Recipe::factory()->create(['user_id' => $challenged->id]);

        $response = $this->actingAs($challenger)->post(route('duels.store'), [
            'challenger_recipe_id' => $challengerRecipe->id,
            'challenged_id' => $challenged->id,
            'challenged_recipe_id' => $challengedRecipe->id,
            'end_date' => now()->addDays(10)->toDateString(),
        ]);

        $duel = Duel::query()->firstOrFail();

        $response->assertRedirect(route('duels.show', $duel));
        $this->assertDatabaseHas('duels', [
            'id' => $duel->id,
            'challenger_id' => $challenger->id,
            'challenged_id' => $challenged->id,
            'challenger_recipe_id' => $challengerRecipe->id,
            'challenged_recipe_id' => $challengedRecipe->id,
            'status' => 'iniciat',
        ]);
    }

    public function test_duel_show_page_renders_and_includes_view_data(): void
    {
        $duel = Duel::factory()->create();

        $response = $this->get(route('duels.show', $duel));

        $response->assertOk();
        $response->assertViewHas('duelDto');
        $response->assertViewHas('userVotes');
    }

    public function test_authenticated_user_can_vote_for_a_recipe_in_a_duel_and_stats_are_updated(): void
    {
        $duel = Duel::factory()->create();
        $voter = User::factory()->create();

        $response = $this->actingAs($voter)->post(route('duels.vote', $duel), [
            'recipe_id' => $duel->challenger_recipe_id,
            'rating' => 5,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('duel_votes', [
            'duel_id' => $duel->id,
            'user_id' => $voter->id,
            'recipe_id' => $duel->challenger_recipe_id,
            'rating' => 5,
        ]);

        $duel->refresh();
        $this->assertSame(1, $duel->challenger_votes_count);
        $this->assertSame(5.0, $duel->challenger_average_rating);
    }

    public function test_duel_service_resolves_overdue_duels_and_results_are_visible_in_show_page(): void
    {
        $duel = Duel::factory()->create([
            'status' => 'iniciat',
            'start_date' => now()->subDays(20),
            'end_date' => now()->subDays(1),
        ]);

        $voter1 = User::factory()->create();
        $voter2 = User::factory()->create();

        DuelVote::query()->create([
            'duel_id' => $duel->id,
            'user_id' => $voter1->id,
            'recipe_id' => $duel->challenger_recipe_id,
            'rating' => 5,
        ]);

        DuelVote::query()->create([
            'duel_id' => $duel->id,
            'user_id' => $voter2->id,
            'recipe_id' => $duel->challenged_recipe_id,
            'rating' => 2,
        ]);

        app(DuelService::class)->resolveDuels();

        $duel->refresh();
        $this->assertSame('finalitzat', $duel->status);
        $this->assertSame('guanyador', $duel->duel_result);
        $this->assertSame($duel->challenger_id, $duel->winner_user_id);
        $this->assertSame($duel->challenger_recipe_id, $duel->winner_recipe_id);

        $response = $this->get(route('duels.show', $duel));
        $response->assertOk();

        $duelDto = $response->viewData('duelDto');
        $this->assertSame('finalitzat', $duelDto->status);
        $this->assertSame('guanyador', $duelDto->duelResult);
        $this->assertSame($duel->winner_user_id, $duelDto->winnerUserId);
        $this->assertSame($duel->winner_recipe_id, $duelDto->winnerRecipeId);
    }

    public function test_user_duels_listing_includes_created_and_received_duels(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();

        $myRecipe = Recipe::factory()->create(['user_id' => $me->id]);
        $otherRecipe = Recipe::factory()->create(['user_id' => $other->id]);

        $created = Duel::query()->create([
            'challenger_id' => $me->id,
            'challenger_recipe_id' => $myRecipe->id,
            'challenged_id' => $other->id,
            'challenged_recipe_id' => $otherRecipe->id,
            'start_date' => now(),
            'end_date' => now()->addDays(7),
            'status' => 'iniciat',
        ]);

        $received = Duel::factory()->create([
            'challenger_id' => $other->id,
            'challenger_recipe_id' => $otherRecipe->id,
            'challenged_id' => $me->id,
            'challenged_recipe_id' => $myRecipe->id,
        ]);

        $response = $this->actingAs($me)->get(route('duels.my-duels'));

        $response->assertOk();
        $response->assertViewHasAll(['createdDuels', 'receivedDuels', 'activeTab']);

        $createdIds = $response->viewData('createdDuels')->getCollection()->pluck('id')->all();
        $receivedIds = $response->viewData('receivedDuels')->getCollection()->pluck('id')->all();

        $this->assertContains($created->id, $createdIds);
        $this->assertContains($received->id, $receivedIds);
    }
}

