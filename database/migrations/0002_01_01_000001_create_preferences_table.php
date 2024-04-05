<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('preferences', function (Blueprint $table) {
      $table->id()->primary();
      $table->string('key');
      $table->string('name');
      $table->string('type');
      $table->string('description')->nullable();
      $table->string('options')->nullable();
      $table->timestamps();
    });

    Schema::create('user_preferences', function (Blueprint $table) {
      $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('preference_id')->constrained('preferences')->onUpdate('cascade')->onDelete('cascade');
      $table->string('value');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('preferences');
    Schema::dropIfExists('user_preferences');
  }
};
