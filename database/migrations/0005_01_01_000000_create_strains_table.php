<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('strains', function (Blueprint $table) {
      $table->id()->primary();
      $table->string('name');
      $table->timestamps();
    });

    Schema::create('strain_pictures', function (Blueprint $table) {
      $table->foreignId('strain_id')->constrained('strains')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('picture_id')->constrained('pictures')->onUpdate('cascade')->onDelete('cascade');
      $table->boolean('default')->defaut(false);
      $table->primary(['strain_id', 'picture_id']);
    });

    Schema::create('strain_properties', function (Blueprint $table) {
      $table->id()->primary();
      $table->foreignId('strain_id')->constrained('strains')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('property_id')->constrained('properties')->onUpdate('cascade')->onDelete('cascade');
      $table->string('value');
    });

    Schema::create('strain_tags', function (Blueprint $table) {
      $table->foreignId('strain_id')->constrained('strains')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('tag_id')->constrained('tags')->onUpdate('cascade')->onDelete('cascade');
      $table->primary(['strain_id', 'tag_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('strains');
    Schema::dropIfExists('strain_pictures');
    Schema::dropIfExists('strain_properties');
    Schema::dropIfExists('strain_tags');
  }
};
