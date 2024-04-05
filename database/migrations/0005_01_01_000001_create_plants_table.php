<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('plants', function (Blueprint $table) {
      $table->id()->primary();
      $table->string('name');
      $table->foreignId('created_by')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('statut_id')->constrained('status')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('strain_id')->constrained('strains')->onUpdate('cascade')->onDelete('cascade');
      $table->timestamps();
    });

    Schema::create('plant_pictures', function (Blueprint $table) {
      $table->foreignId('plant_id')->constrained('plants')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('picture_id')->constrained('pictures')->onUpdate('cascade')->onDelete('cascade');
      $table->boolean('default')->defaut(false);
      $table->primary(['plant_id', 'picture_id']);
    });

    Schema::create('plant_properties', function (Blueprint $table) {
      $table->id()->primary();
      $table->foreignId('plant_id')->constrained('plants')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('property_id')->constrained('properties')->onUpdate('cascade')->onDelete('cascade');
      $table->string('value');
    });

    Schema::create('plant_tags', function (Blueprint $table) {
      $table->foreignId('plant_id')->constrained('plants')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('tag_id')->constrained('tags')->onUpdate('cascade')->onDelete('cascade');
      $table->primary(['plant_id', 'tag_id']);
    });

    Schema::create('plant_checklists', function (Blueprint $table) {
      $table->foreignId('plant_id')->constrained('plants')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('checklist_id')->constrained('checklists')->onUpdate('cascade')->onDelete('cascade');
      $table->boolean('initial')->defaut(false);
      $table->primary(['plant_id', 'checklist_id']);
    });

    Schema::create('plant_history', function (Blueprint $table) {
      $table->id()->primary();
      $table->foreignId('plant_id')->constrained('plants')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('checklist_id')->constrained('checklists')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('by')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
      $table->string('data');
      $table->timestamps();
    });

    Schema::create('plant_items', function (Blueprint $table) {
      $table->foreignId('plant_id')->constrained('plants')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('item_id')->constrained('items')->onUpdate('cascade')->onDelete('cascade');
      $table->dateTime('due', 3)->nullable();
      $table->dateTime('checked', 3)->nullable();
      $table->boolean('flush')->default(false);
      $table->primary(['plant_id', 'item_id']);
    });

    Schema::create('plant_waterings', function (Blueprint $table) {
      $table->id()->primary();
      $table->foreignId('plant_id')->constrained('plants')->onUpdate('cascade')->onDelete('cascade');
      $table->boolean('chemical')->defaut(false);
      $table->timestamps();
    });

    Schema::create('plant_comments', function (Blueprint $table) {
      $table->id()->primary();
      $table->foreignId('plant_id')->constrained('plants')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('author_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
      $table->string('value')->nullable();
      $table->timestamps();
    });

    Schema::create('plant_preferences', function (Blueprint $table) {
      $table->foreignId('plant_id')->constrained('plants')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('preference_id')->constrained('preferences')->onUpdate('cascade')->onDelete('cascade');
      $table->string('value');
      $table->primary(['plant_id', 'preference_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('plants');
    Schema::dropIfExists('plant_pictures');
    Schema::dropIfExists('plant_properties');
    Schema::dropIfExists('plant_tags');
    Schema::dropIfExists('plant_checklists');
    Schema::dropIfExists('plant_history');
    Schema::dropIfExists('plant_items');
    Schema::dropIfExists('plant_waterings');
    Schema::dropIfExists('plant_comments');
    Schema::dropIfExists('plant_preferences');
  }
};
