<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('duels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('challenger_id');
            $table->unsignedBigInteger('challenger_recipe_id');
            $table->unsignedBigInteger('challenged_id');
            $table->unsignedBigInteger('challenged_recipe_id');
            $table->decimal('challenger_average_rating', 3, 2)->default(0);
            $table->decimal('challenged_average_rating', 3, 2)->default(0);
            $table->unsignedInteger('challenger_votes_count')->default(0);
            $table->unsignedInteger('challenged_votes_count')->default(0);
            $table->enum('status', ['iniciat', 'finalitzat', 'peticio de cancelacio', 'cancelat'])->default('iniciat');
            $table->enum('duel_result', ['guanyador', 'empat'])->nullable();
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->unsignedBigInteger('winner_recipe_id')->nullable();
            $table->unsignedBigInteger('winner_user_id')->nullable();
            $table->timestamps();

            $table->foreign('challenger_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('challenger_recipe_id')->references('id')->on('recipes')->onDelete('cascade');
            $table->foreign('challenged_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('challenged_recipe_id')->references('id')->on('recipes')->onDelete('cascade');
            $table->foreign('winner_recipe_id')->references('id')->on('recipes')->onDelete('set null');
            $table->foreign('winner_user_id')->references('id')->on('users')->onDelete('set null');

            $table->unique(['challenger_recipe_id', 'challenged_recipe_id'], 'unique_duel_recipes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duels');
    }
};
