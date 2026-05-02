<?php

namespace Database\Seeders;

use App\Models\Duel;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;

class DuelSeeder extends Seeder
{
    /**
     * Crea duels de demostració entre usuaris amb receptes.
     */
    public function run(): void
    {
        $users = User::query()
            ->whereHas('recipes')
            ->with(['recipes:id,user_id,title'])
            ->orderBy('id')
            ->get();

        if ($users->count() < 2) {
            return;
        }

        // Crea fins a 4 duels entre usuaris consecutius amb receptes
        $maxDuels = min(4, $users->count() - 1);

        for ($i = 0; $i < $maxDuels; $i++) {
            $challenger = $users[$i];
            $challenged = $users[$i + 1];

            $challengerRecipe = $challenger->recipes->first();
            $challengedRecipe = $challenged->recipes->first();

            if (!$challengerRecipe || !$challengedRecipe) {
                continue;
            }

            Duel::query()->firstOrCreate(
                [
                    'challenger_recipe_id' => $challengerRecipe->id,
                    'challenged_recipe_id' => $challengedRecipe->id,
                ],
                [
                    'challenger_id' => $challenger->id,
                    'challenged_id' => $challenged->id,
                    'start_date' => now()->subDays(2),
                    'end_date' => now()->addDays(12),
                    'status' => 'iniciat',
                ]
            );
        }

        // Un duel finalitzat d'exemple (si hi ha receptes suficients)
        $recipes = Recipe::query()->with('user')->orderBy('id')->get();
        $first = $recipes->first();
        $second = $recipes->skip(1)->first();

        if ($first && $second && $first->user_id !== $second->user_id) {
            Duel::query()->firstOrCreate(
                [
                    'challenger_recipe_id' => $first->id,
                    'challenged_recipe_id' => $second->id,
                ],
                [
                    'challenger_id' => $first->user_id,
                    'challenged_id' => $second->user_id,
                    'start_date' => now()->subDays(20),
                    'end_date' => now()->subDays(6),
                    'status' => 'finalitzat',
                    'duel_result' => 'empat',
                    'winner_recipe_id' => null,
                    'winner_user_id' => null,
                ]
            );
        }
    }
}

