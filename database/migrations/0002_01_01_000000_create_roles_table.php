<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('roles', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('description')->nullable();
      $table->timestamps();
    });

    Schema::create('user_roles', function (Blueprint $table) {
      $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('role_id')->constrained('roles')->onUpdate('cascade')->onDelete('cascade');
      $table->primary(['user_id', 'role_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('roles');
    Schema::dropIfExists('user_roles');
  }
};
