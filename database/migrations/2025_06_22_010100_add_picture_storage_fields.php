<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pictures', function (Blueprint $table) {
            $table->string('path')->nullable();
            $table->string('disk')->nullable();
            $table->string('mime')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pictures', function (Blueprint $table) {
            $table->dropColumn(['path', 'disk', 'mime']);
        });
    }
};
