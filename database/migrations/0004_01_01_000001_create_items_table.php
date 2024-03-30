<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('items', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->foreignId('checklist_id')->constrained('checklists')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('parent_id')->nullable()->constrained('items')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('statut_id')->constrained('status')->onUpdate('cascade')->onDelete('cascade');
      $table->timestamps();

      $table->unique(['checklist_id', 'id', 'parent_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('items');
  }
};
