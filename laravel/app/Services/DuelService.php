<?php

namespace App\Services;

use App\Models\Duel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class DuelService
{
    /**
     * Crea un nou duel validant les regles de negoci.
     */
    public function createDuel(array $data, User $challenger): Duel
    {
        $activeDuelsCount = Duel::where('challenger_id', $challenger->id)
            ->where('status', 'iniciat')
            ->count();

        if ($activeDuelsCount >= 3) {
            throw new Exception("L'usuari ja té 3 duels iniciats on figura com a retador.");
        }

        $startDate = Carbon::now();
        $endDate = empty($data['end_date']) ? $startDate->copy()->addDays(14) : Carbon::parse($data['end_date']);

        $duel = Duel::create([
            'challenger_id' => $challenger->id,
            'challenger_recipe_id' => $data['challenger_recipe_id'],
            'challenged_id' => $data['challenged_id'],
            'challenged_recipe_id' => $data['challenged_recipe_id'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'iniciat',
        ]);

        return $duel;
    }

    /**
     * Tanca els duels vençuts i gestiona l'empat/victòria actualitzant el comptador.
     */
    public function resolveDuels(): void
    {
        $overdueDuels = Duel::where('status', 'iniciat')
            ->where('end_date', '<=', Carbon::now())
            ->get();

        foreach ($overdueDuels as $duel) {
            DB::transaction(function () use ($duel) {
                if ($duel->challenger_average_rating > $duel->challenged_average_rating) {
                    $duel->duel_result = 'guanyador';
                    $duel->winner_user_id = $duel->challenger_id;
                    $duel->winner_recipe_id = $duel->challenger_recipe_id;
                } elseif ($duel->challenged_average_rating > $duel->challenger_average_rating) {
                    $duel->duel_result = 'guanyador';
                    $duel->winner_user_id = $duel->challenged_id;
                    $duel->winner_recipe_id = $duel->challenged_recipe_id;
                } else {
                    $duel->duel_result = 'empat';
                    $duel->winner_user_id = null;
                    $duel->winner_recipe_id = null;
                }

                $duel->status = 'finalitzat';
                $duel->save();

                if ($duel->duel_result === 'guanyador' && $duel->winner_user_id) {
                    $winner = User::find($duel->winner_user_id);
                    if ($winner) {
                        $winner->increment('victories_count');
                    }
                }
            });
        }
    }
}
