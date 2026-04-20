<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('duel_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('duel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('duel_comments')->nullOnDelete();
            $table->text('content');
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            $table->index(['duel_id', 'parent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duel_comments');
    }
};
