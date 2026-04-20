<?php

namespace App\Observers;

use App\Models\DuelVote;

class DuelVoteObserver
{
    /**
     * S'executa quan un vot es crea; recalcula les estadístiques del duel.
     */
    public function created(DuelVote $duelVote): void
    {
        $this->updateDuelStats($duelVote);
    }

    /**
     * S'executa quan un vot s'actualitza; recalcula les estadístiques del duel.
     */
    public function updated(DuelVote $duelVote): void
    {
        $this->updateDuelStats($duelVote);
    }

    /**
     * S'executa quan un vot s'elimina; recalcula les estadístiques del duel.
     */
    public function deleted(DuelVote $duelVote): void
    {
        $this->updateDuelStats($duelVote);
    }

    /**
     * Recalcula i guarda el recompte de vots i la mitjana per a la recepta del vot rebut.
     */
    private function updateDuelStats(DuelVote $duelVote): void
    {
        $duel = $duelVote->duel;
        
        // Obtenim tots els vots per a la recepta específica en aquest duel
        $votes = DuelVote::where('duel_id', $duel->id)
            ->where('recipe_id', $duelVote->recipe_id)
            ->get();
            
        $count = $votes->count();
        $average = $count > 0 ? $votes->avg('rating') : 0;
        
        // Actualitzam les columnes de resum segons si és la recepta del reptador o del reptat
        if ($duel->challenger_recipe_id == $duelVote->recipe_id) {
            $duel->challenger_votes_count = $count;
            $duel->challenger_average_rating = $average;
        } elseif ($duel->challenged_recipe_id == $duelVote->recipe_id) {
            $duel->challenged_votes_count = $count;
            $duel->challenged_average_rating = $average;
        }
        
        $duel->save();
    }
}
