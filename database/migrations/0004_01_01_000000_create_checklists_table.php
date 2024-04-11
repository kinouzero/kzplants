<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('checklists', function (Blueprint $table) {
      $table->id()->primary();
      $table->string('name');
      $table->string('icon')->nullable();
      $table->timestamps();
    });

    Schema::create('checklist_parents', function (Blueprint $table) {
      $table->foreignId('checklist_id')->constrained('checklists')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('parent_id')->constrained('checklists')->onUpdate('cascade')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('checklists');
    Schema::dropIfExists('checklist_parents');
  }
};
