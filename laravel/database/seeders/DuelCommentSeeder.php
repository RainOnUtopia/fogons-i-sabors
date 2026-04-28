<?php

namespace Database\Seeders;

use App\Models\Duel;
use App\Models\DuelComment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DuelCommentSeeder extends Seeder
{
    /**
     * Afegeix comentaris de demostració als duels.
     */
    public function run(): void
    {
        $duels = Duel::query()->get();
        $users = User::query()->orderBy('id')->get();

        if ($duels->isEmpty() || $users->isEmpty()) {
            return;
        }

        foreach ($duels as $duel) {
            $author = $users->first();
            if (!$author) {
                continue;
            }

            $comment = DuelComment::query()->create([
                'user_id' => $author->id,
                'duel_id' => $duel->id,
                'parent_id' => null,
                'content' => 'Quin duel més interessant! Bona sort a tots dos.',
                'is_deleted' => false,
            ]);

            $replyAuthor = $users->skip(1)->first() ?? $author;
            DuelComment::query()->create([
                'user_id' => $replyAuthor->id,
                'duel_id' => $duel->id,
                'parent_id' => $comment->id,
                'content' => 'Totalment d\'acord — jo ja he votat.',
                'is_deleted' => false,
            ]);
        }
    }
}

