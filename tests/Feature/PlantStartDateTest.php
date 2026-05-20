<?php

namespace Tests\Feature;

use App\Models\Dashboard;
use App\Models\Statut;
use App\Models\Strain;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlantStartDateTest extends TestCase
{
    use RefreshDatabase;

    public function test_start_date_sets_created_at(): void
    {
        $user = User::factory()->create();
        Statut::factory()->create(['name' => 'new']);
        $strain = Strain::factory()->create();
        $dashboard = Dashboard::factory()->create();
        $dashboard->users()->attach($user->id, ['creator' => true, 'default' => true]);

        $startDate = '2020-01-02';

        $this->actingAs($user)->post(route('plant.store'), [
            'name' => 'Plant Date',
            'strain_id' => $strain->id,
            'start_date' => $startDate,
            'dashboards' => [$dashboard->id],
        ])->assertRedirect();

        $plant = $dashboard->plants()->first();
        $this->assertNotNull($plant);
        $this->assertSame($startDate, Carbon::parse($plant->created_at)->toDateString());
    }
}
