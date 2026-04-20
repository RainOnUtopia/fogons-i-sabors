<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Afegeix l'administrador per defecte si no existeix.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('12345678'), // Pots canviar aquesta contrasenya per defecte
                'role' => 'admin',
                'is_active' => true,
            ]
        );
    }
}
