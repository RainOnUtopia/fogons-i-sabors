<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear la taula pivot de favorits entre usuaris i receptes.
     */
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recipe_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'recipe_id']);
        });
    }

    /**
     * Eliminar la taula pivot de favorits.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
