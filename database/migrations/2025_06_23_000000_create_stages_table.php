<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('checklist_id')->unique()->constrained('checklists')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->integer('interval_stage_days')->default(0);
            $table->integer('interval_item_days')->default(7);
            $table->timestamps();
        });

        Schema::create('plant_stages', function (Blueprint $table) {
            $table->foreignId('plant_id')->constrained('plants')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('stage_id')->constrained('stages')->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('initial')->default(false);
            $table->primary(['plant_id', 'stage_id']);
        });

        if (Schema::hasTable('checklists')) {
            $checklists = DB::table('checklists')->orderBy('id')->get();
            $order = 1;
            foreach ($checklists as $checklist) {
                DB::table('stages')->insert([
                    'name' => $checklist->name,
                    'checklist_id' => $checklist->id,
                    'order' => $order++,
                    'interval_stage_days' => 0,
                    'interval_item_days' => 7,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        if (Schema::hasTable('plant_checklists')) {
            $mapping = DB::table('stages')->pluck('id', 'checklist_id');
            $rows = DB::table('plant_checklists')->get();
            foreach ($rows as $row) {
                if (! isset($mapping[$row->checklist_id])) {
                    continue;
                }
                DB::table('plant_stages')->insert([
                    'plant_id' => $row->plant_id,
                    'stage_id' => $mapping[$row->checklist_id],
                    'initial' => (bool) $row->initial,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('plant_stages');
        Schema::dropIfExists('stages');
    }
};
