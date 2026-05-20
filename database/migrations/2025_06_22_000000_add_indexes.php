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
        Schema::table('plant_items', function (Blueprint $table) {
            $table->index(['plant_id', 'checked']);
            $table->index(['plant_id', 'due']);
        });

        Schema::table('plant_waterings', function (Blueprint $table) {
            $table->index(['plant_id', 'created_at']);
        });

        Schema::table('plant_comments', function (Blueprint $table) {
            $table->index(['plant_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plant_items', function (Blueprint $table) {
            $table->dropIndex(['plant_id', 'checked']);
            $table->dropIndex(['plant_id', 'due']);
        });

        Schema::table('plant_waterings', function (Blueprint $table) {
            $table->dropIndex(['plant_id', 'created_at']);
        });

        Schema::table('plant_comments', function (Blueprint $table) {
            $table->dropIndex(['plant_id', 'created_at']);
        });
    }
};
