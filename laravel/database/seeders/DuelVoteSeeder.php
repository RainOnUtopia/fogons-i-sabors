<?php

namespace Database\Seeders;

use App\Models\Duel;
use App\Models\DuelVote;
use App\Models\User;
use Illuminate\Database\Seeder;

class DuelVoteSeeder extends Seeder
{
    /**
     * Afegeix vots de demostració als duels existents.
     */
    public function run(): void
    {
        $duels = Duel::query()->get();
        if ($duels->isEmpty()) {
            return;
        }

        $voters = User::query()->orderBy('id')->get();
        if ($voters->isEmpty()) {
            return;
        }

        foreach ($duels as $duel) {
            $sampleVoters = $voters
                ->whereNotIn('id', [$duel->challenger_id, $duel->challenged_id])
                ->take(5);

            foreach ($sampleVoters as $voter) {
                DuelVote::query()->updateOrCreate(
                    ['duel_id' => $duel->id, 'user_id' => $voter->id, 'recipe_id' => $duel->challenger_recipe_id],
                    ['rating' => random_int(2, 5)]
                );

                DuelVote::query()->updateOrCreate(
                    ['duel_id' => $duel->id, 'user_id' => $voter->id, 'recipe_id' => $duel->challenged_recipe_id],
                    ['rating' => random_int(1, 5)]
                );
            }
        }
    }
}

