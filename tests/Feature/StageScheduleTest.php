<?php

namespace Tests\Feature;

use App\Models\Checklist;
use App\Models\Dashboard;
use App\Models\Item;
use App\Models\Stage;
use App\Models\Statut;
use App\Models\Strain;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StageScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sets_due_dates_from_stage_intervals(): void
    {
        $user = User::factory()->create();
        $strain = Strain::factory()->create();
        $statut = Statut::factory()->create();
        $dashboard = Dashboard::create(['name' => 'Board']);
        $dashboard->users()->sync([$user->id => ['creator' => true, 'default' => true]]);

        $checklist = Checklist::create(['name' => 'Stage 1', 'icon' => 'fas fa-leaf']);
        $stage = Stage::create([
            'name' => 'Stage 1',
            'checklist_id' => $checklist->id,
            'order' => 1,
            'interval_stage_days' => 0,
            'interval_item_days' => 7,
        ]);
        $item1 = Item::query()->forceCreate([
            'name' => 'Step 1',
            'checklist_id' => $checklist->id,
            'parent_id' => null,
            'statut_id' => $statut->id,
        ]);
        $item2 = Item::query()->forceCreate([
            'name' => 'Step 2',
            'checklist_id' => $checklist->id,
            'parent_id' => $item1->id,
            'statut_id' => $statut->id,
        ]);

        $payload = [
            'name' => 'Plant 1',
            'strain_id' => $strain->id,
            'start_date' => now()->toDateString(),
            'dashboards' => [$dashboard->id],
            'tags' => [],
            'properties' => [],
            'values' => [],
            'preferences' => [],
        ];

        $this->actingAs($user)->post(route('plant.store'), $payload)->assertStatus(302);

        $plant = $dashboard->plants()->first();
        $this->assertNotNull($plant);

        $this->actingAs($user)->post(route('plant.add', ['id' => $plant->id, 'objectType' => 'stage', 'objectId' => $stage->id]))->assertStatus(302);
        $this->actingAs($user)->post(route('plant.add', ['id' => $plant->id, 'objectType' => 'initial', 'objectId' => $stage->id]))->assertStatus(302);

        $plant = $plant->fresh();
        $plantItem1 = $plant->items()->where('item_id', $item1->id)->first();
        $plantItem2 = $plant->items()->where('item_id', $item2->id)->first();

        $this->assertNotNull($plantItem1);
        $this->assertNotNull($plantItem2);
        $base = $plant->created_at->startOfDay();
        $this->assertSame($base->format('Y-m-d H:i:s'), $plantItem1->pivot->due->format('Y-m-d H:i:s'));
        $this->assertSame($base->copy()->addDays(7)->format('Y-m-d H:i:s'), $plantItem2->pivot->due->format('Y-m-d H:i:s'));
    }
}
