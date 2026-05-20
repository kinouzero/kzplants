<?php

namespace Tests\Feature;

use App\Models\Dashboard;
use App\Models\Preference;
use App\Models\Statut;
use App\Models\Strain;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlantPreferencesTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_saves_plant_preferences(): void
    {
        $user = User::factory()->create();
        $strain = Strain::factory()->create();
        $statut = Statut::factory()->create();
        $dashboard = Dashboard::create(['name' => 'Board']);
        $dashboard->users()->sync([$user->id => ['creator' => true, 'default' => true]]);

        $preference = Preference::create([
            'key' => 'plant-color',
            'name' => 'Plant color',
            'type' => 'text',
        ]);

        $payload = [
            'name' => 'Plant 1',
            'strain_id' => $strain->id,
            'start_date' => now()->toDateString(),
            'dashboards' => [$dashboard->id],
            'tags' => [],
            'properties' => [],
            'values' => [],
            'preferences' => [
                $preference->id => 'green',
            ],
        ];

        $response = $this->actingAs($user)->post(route('plant.store'), $payload);
        $response->assertStatus(302);

        $plant = $user->fresh()->dashboards()->first()->plants()->first();
        $this->assertNotNull($plant);
        $this->assertTrue($plant->preferences()->where('preference_id', $preference->id)->wherePivot('value', 'green')->exists());
    }
}
