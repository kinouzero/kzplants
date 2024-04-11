<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('dashboards', function (Blueprint $table) {
      $table->id()->primary();
      $table->string('name');
      $table->string('description')->nullable();
      $table->string('color')->nullable();
      $table->timestamps();
    });

    Schema::create('dashboard_users', function (Blueprint $table) {
      $table->foreignId('dashboard_id')->constrained('dashboards')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
      $table->boolean('creator')->default(false);
      $table->boolean('default')->default(false);
      $table->primary(['user_id', 'dashboard_id']);
    });

    Schema::create('dashboard_plants', function (Blueprint $table) {
      $table->foreignId('dashboard_id')->constrained('dashboards')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('plant_id')->constrained('plants')->onUpdate('cascade')->onDelete('cascade');
      $table->primary(['plant_id', 'dashboard_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('dashboards');
    Schema::dropIfExists('dashboard_users');
    Schema::dropIfExists('dashboard_plants');
  }
};
