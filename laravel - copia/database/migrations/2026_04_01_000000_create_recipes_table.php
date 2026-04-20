<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Executa la migració.
     */
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();

            // Información básica de la receta
            $table->string('title');                                    // Titol de la recepta
            $table->text('description')->nullable();                    // Descripció
            $table->integer('cooking_time');                            // Temps de cocció (minuts)
            $table->enum('difficulty', ['fàcil', 'mitjà', 'difícil']); // Dificultat
            $table->string('image')->nullable();                        // Imatge (path storage)

            // Metadata JSON
            $table->json('tags')->nullable();                           // Etiquetes: ['ITALIÀ', 'LUXE', etc]
            $table->json('ingredients')->nullable();                    // Ingredients: [item1, item2, ...]
            $table->text('chef_notes')->nullable();                     // Notes del Xef (quote o paràgraf)

            // Informació del Chef
            $table->string('chef_name');                                // Nom del Chef
            $table->string('chef_avatar')->nullable();                  // Avatar del Chef (path storage)

            // Relació amb usuari
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');                                // FK al creador

            // Valoració
            $table->decimal('rating', 3, 1)->default(0);              // Punishment: 4.9

            // Timestamps automàtics
            $table->timestamps();

            // Índexes per rendiment
            $table->index('difficulty');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverteix la migració.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
