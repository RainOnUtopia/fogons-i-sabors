<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            if (! Schema::hasColumn('recipes', 'steps')) {
                $table->json('steps')->nullable()->after('ingredients');
            }

            if (! Schema::hasColumn('recipes', 'average_rating')) {
                $table->decimal('average_rating', 3, 2)->default(0)->after('user_id');
            }

            if (! Schema::hasColumn('recipes', 'ratings_count')) {
                $table->unsignedInteger('ratings_count')->default(0)->after('average_rating');
            }

            if (! Schema::hasColumn('recipes', 'favorites_count')) {
                $table->unsignedInteger('favorites_count')->default(0)->after('ratings_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $dropColumns = [];

            foreach (['steps', 'average_rating', 'ratings_count', 'favorites_count'] as $column) {
                if (Schema::hasColumn('recipes', $column)) {
                    $dropColumns[] = $column;
                }
            }

            if ($dropColumns !== []) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
