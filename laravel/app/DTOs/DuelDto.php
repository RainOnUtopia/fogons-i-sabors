<?php

namespace App\DTOs;

use App\Models\Duel;
use Carbon\Carbon;

class DuelDto
{
    public function __construct(
        public int $id,
        public array $challenger,
        public array $challengerRecipe,
        public array $challenged,
        public array $challengedRecipe,
        public float $challengerAverageRating,
        public float $challengedAverageRating,
        public int $challengerVotesCount,
        public int $challengedVotesCount,
        public string $status,
        public ?string $duelResult,
        public ?int $winnerRecipeId,
        public ?int $winnerUserId,
        public string $startDate,
        public string $endDate,
        public int $daysRemaining,
        public int $totalVotes,
        public iterable $topLevelComments = [],
        public int $commentsCount = 0
    ) {}

    public static function fromModel(Duel $duel): self
    {
        $now = Carbon::now();
        $endDate = Carbon::parse($duel->end_date);
        $daysRemaining = $now->lessThan($endDate) ? $now->diffInDays($endDate) : 0;
        
        return new self(
            id: $duel->id,
            challenger: [
                'id' => $duel->challenger->id,
                'name' => $duel->challenger->name,
                'avatar' => $duel->challenger->avatar,
            ],
            challengerRecipe: [
                'id' => $duel->challengerRecipe->id,
                'title' => $duel->challengerRecipe->title,
                'image' => $duel->challengerRecipe->image,
            ],
            challenged: [
                'id' => $duel->challenged->id,
                'name' => $duel->challenged->name,
                'avatar' => $duel->challenged->avatar,
            ],
            challengedRecipe: [
                'id' => $duel->challengedRecipe->id,
                'title' => $duel->challengedRecipe->title,
                'image' => $duel->challengedRecipe->image,
            ],
            challengerAverageRating: $duel->challenger_average_rating,
            challengedAverageRating: $duel->challenged_average_rating,
            challengerVotesCount: $duel->challenger_votes_count,
            challengedVotesCount: $duel->challenged_votes_count,
            status: $duel->status,
            duelResult: $duel->duel_result,
            winnerRecipeId: $duel->winner_recipe_id,
            winnerUserId: $duel->winner_user_id,
            startDate: $duel->start_date->toIso8601String(),
            endDate: $duel->end_date->toIso8601String(),
            daysRemaining: (int) $daysRemaining,
            totalVotes: $duel->challenger_votes_count + $duel->challenged_votes_count,
            topLevelComments: $duel->topLevelComments ?? [],
            commentsCount: $duel->comments()->count()
        );
    }
}
