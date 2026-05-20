<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dashboard_users', function (Blueprint $table) {
            $table->index(['user_id', 'dashboard_id']);
        });

        Schema::table('plant_checklists', function (Blueprint $table) {
            $table->index(['plant_id', 'checklist_id']);
        });

        Schema::table('plant_tags', function (Blueprint $table) {
            $table->index(['plant_id', 'tag_id']);
        });

        Schema::table('plant_properties', function (Blueprint $table) {
            $table->index(['plant_id', 'property_id']);
        });

        Schema::table('plant_pictures', function (Blueprint $table) {
            $table->index(['plant_id', 'picture_id']);
        });

        Schema::table('plant_items', function (Blueprint $table) {
            $table->index(['item_id']);
        });
    }

    public function down(): void
    {
        Schema::table('dashboard_users', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'dashboard_id']);
        });

        Schema::table('plant_checklists', function (Blueprint $table) {
            $table->dropIndex(['plant_id', 'checklist_id']);
        });

        Schema::table('plant_tags', function (Blueprint $table) {
            $table->dropIndex(['plant_id', 'tag_id']);
        });

        Schema::table('plant_properties', function (Blueprint $table) {
            $table->dropIndex(['plant_id', 'property_id']);
        });

        Schema::table('plant_pictures', function (Blueprint $table) {
            $table->dropIndex(['plant_id', 'picture_id']);
        });

        Schema::table('plant_items', function (Blueprint $table) {
            $table->dropIndex(['item_id']);
        });
    }
};
