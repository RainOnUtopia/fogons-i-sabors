<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Executa els seeders de la base de dades.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            DemoUserSeeder::class,
            RecipeSeeder::class,
            FavoriteSeeder::class,
            CommentSeeder::class,
            RatingSeeder::class,
            DuelSeeder::class,
            DuelVoteSeeder::class,
            DuelCommentSeeder::class,
        ]);
    }
}
