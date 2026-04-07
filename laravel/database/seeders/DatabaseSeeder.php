<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

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
        ]);
    }
}
