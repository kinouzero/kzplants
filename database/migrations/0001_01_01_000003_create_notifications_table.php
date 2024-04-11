<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('notifications', function (Blueprint $table) {
      $table->id()->primary();
      $table->string('name');
      $table->string('description')->nullable();
      $table->json('configuration')->nullable();
      $table->timestamps();
    });

    Schema::create('notification_users', function (Blueprint $table) {
      $table->foreignId('notification_id')->constrained('notifications')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
      $table->boolean('creator')->default(false);
      $table->boolean('active')->default(true);
      $table->primary(['user_id', 'notification_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('notifications');
    Schema::dropIfExists('notification_users');
  }
};
