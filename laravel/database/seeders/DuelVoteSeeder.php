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
                if(random_int(0, 1) === 0) {
                    continue; // Simula que no tots els votants voten en tots els duels
                }
                if(random_int(0, 1) === 0) {
                    // Vota només per la recepta del challenger
                    DuelVote::query()->updateOrCreate(
                        ['duel_id' => $duel->id, 'user_id' => $voter->id, 'recipe_id' => $duel->challenger_recipe_id],
                        ['rating' => 5]
                    );
                } else {
                    // Vota només per la recepta del challenged
                    DuelVote::query()->updateOrCreate(
                        ['duel_id' => $duel->id, 'user_id' => $voter->id, 'recipe_id' => $duel->challenged_recipe_id],
                        ['rating' => 5]
                    );
                }
            }
        }
    }
}

