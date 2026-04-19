<?php

namespace App\Observers;

use App\Models\DuelVote;

class DuelVoteObserver
{
    /**
     * Handle the DuelVote "created" event.
     */
    public function created(DuelVote $duelVote): void
    {
        $this->updateDuelStats($duelVote);
    }

    /**
     * Handle the DuelVote "updated" event.
     */
    public function updated(DuelVote $duelVote): void
    {
        $this->updateDuelStats($duelVote);
    }

    /**
     * Handle the DuelVote "deleted" event.
     */
    public function deleted(DuelVote $duelVote): void
    {
        $this->updateDuelStats($duelVote);
    }

    private function updateDuelStats(DuelVote $duelVote): void
    {
        $duel = $duelVote->duel;
        
        $votes = DuelVote::where('duel_id', $duel->id)
            ->where('recipe_id', $duelVote->recipe_id)
            ->get();
            
        $count = $votes->count();
        $average = $count > 0 ? $votes->avg('rating') : 0;
        
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
