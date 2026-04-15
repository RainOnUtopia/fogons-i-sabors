<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->get()->keyBy('email');
        $recipes = Recipe::query()->get()->keyBy('title');

        if (! isset($users['test@example.com'], $users['aina@fogons.local'], $users['maria@fogons.local'])) {
            return;
        }

        if (! isset($recipes['Risotto de safran amb pa d\'or'], $recipes['Bacalla negre amb glacejat de miso'])) {
            return;
        }

        $comment = Comment::updateOrCreate(
            [
                'recipe_id' => $recipes['Risotto de safran amb pa d\'or']->id,
                'user_id' => $users['test@example.com']->id,
                'parent_id' => null,
            ],
            [
                'content' => 'Molt bona recepta, el safran hi queda espectacular.',
                'is_deleted' => false,
            ]
        );

        Comment::updateOrCreate(
            [
                'recipe_id' => $recipes['Risotto de safran amb pa d\'or']->id,
                'user_id' => $users['aina@fogons.local']->id,
                'parent_id' => $comment->id,
            ],
            [
                'content' => 'Gracies! Si hi afegeixes brou a poc a poc queda encara millor.',
                'is_deleted' => false,
            ]
        );

        Comment::updateOrCreate(
            [
                'recipe_id' => $recipes['Bacalla negre amb glacejat de miso']->id,
                'user_id' => $users['maria@fogons.local']->id,
                'parent_id' => null,
            ],
            [
                'content' => 'El glacejat de miso m ha encantat.',
                'is_deleted' => false,
            ]
        );
    }
}
