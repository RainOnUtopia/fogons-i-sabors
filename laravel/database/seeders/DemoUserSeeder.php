<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    /**
     * Afegeix usuaris de demostració.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
                'avatar' => null,
                'about_me' => 'M\'agrada compartir receptes tradicionals i provar nous sabors.',
                'city' => 'Barcelona',
                'country' => 'Espanya',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Aina Serra',
                'email' => 'aina@fogons.local',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
                'avatar' => null,
                'about_me' => 'Cuina mediterrania, arrosos i plats per compartir.',
                'city' => 'Girona',
                'country' => 'Espanya',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Pau Ferrer',
                'email' => 'pau@fogons.local',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
                'avatar' => null,
                'about_me' => 'Apassionat pels sabors intensos i la cuina d\'autor.',
                'city' => 'Tarragona',
                'country' => 'Espanya',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Maria Costa',
                'email' => 'maria@fogons.local',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
                'avatar' => null,
                'about_me' => 'Especialista en postres i en receptes elegants per celebracions.',
                'city' => 'Lleida',
                'country' => 'Espanya',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
