<?php

namespace Tests\Feature;

use App\Models\Dashboard;
use App\Models\Statut;
use App\Models\Strain;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlantStartDateValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_start_date_is_required(): void
    {
        $user = User::factory()->create();
        Statut::factory()->create(['name' => 'new']);
        $strain = Strain::factory()->create();
        $dashboard = Dashboard::factory()->create();
        $dashboard->users()->attach($user->id, ['creator' => true, 'default' => true]);

        $this->actingAs($user)->post(route('plant.store'), [
            'name' => 'Plant Missing Date',
            'strain_id' => $strain->id,
            'dashboards' => [$dashboard->id],
        ])->assertSessionHasErrors(['start_date']);
    }
}
