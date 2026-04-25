<?php

namespace App\DTOs;

use App\Models\Duel;

class DuelListDto
{
    public function __construct(
        public int $id,
        public string $challengerName,
        public string $challengedName,
        public ?string $challengerAvatar,
        public ?string $challengedAvatar,
        public string $challengerRecipeTitle,
        public string $challengedRecipeTitle,
        public ?string $challengerRecipeImage,
        public ?string $challengedRecipeImage,
        public float $challengerAverageRating,
        public float $challengedAverageRating,
        public string $status,
        public ?string $duelResult,
        public ?array $winner,
        public string $startDate,
        public string $endDate
    ) {}

    public static function fromModel(Duel $duel): self
    {
        $winnerData = null;
        if ($duel->duel_result === 'guanyador' && $duel->winnerUser && $duel->winnerRecipe) {
            $winnerData = [
                'user_id' => $duel->winnerUser->id,
                'user_name' => $duel->winnerUser->name,
                'recipe_id' => $duel->winnerRecipe->id,
                'recipe_title' => $duel->winnerRecipe->title,
            ];
        }

        return new self(
            id: $duel->id,
            challengerName: $duel->challenger->name,
            challengedName: $duel->challenged->name,
            challengerAvatar: $duel->challenger->avatar,
            challengedAvatar: $duel->challenged->avatar,
            challengerRecipeTitle: $duel->challengerRecipe->title,
            challengedRecipeTitle: $duel->challengedRecipe->title,
            challengerRecipeImage: $duel->challengerRecipe->image,
            challengedRecipeImage: $duel->challengedRecipe->image,
            challengerAverageRating: $duel->challenger_average_rating,
            challengedAverageRating: $duel->challenged_average_rating,
            status: $duel->status,
            duelResult: $duel->duel_result,
            winner: $winnerData,
            startDate: $duel->start_date->toIso8601String(),
            endDate: $duel->end_date->toIso8601String()
        );
    }
}
