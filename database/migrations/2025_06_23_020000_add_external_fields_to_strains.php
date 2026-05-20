<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('strains', function (Blueprint $table) {
            $table->string('external_source')->nullable()->after('name');
            $table->string('external_id')->nullable()->after('external_source');
            $table->unique(['external_source', 'external_id'], 'strains_external_unique');
        });
    }

    public function down(): void
    {
        Schema::table('strains', function (Blueprint $table) {
            $table->dropUnique('strains_external_unique');
            $table->dropColumn(['external_source', 'external_id']);
        });
    }
};
