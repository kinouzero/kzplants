<?php

namespace Tests\Feature;

use App\Models\Dashboard;
use App\Models\Strain;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlantStepperViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_view_includes_stepper_fields(): void
    {
        $user = User::factory()->create();
        $dashboard = Dashboard::factory()->create();
        $dashboard->users()->attach($user->id, ['creator' => true, 'default' => true]);
        Strain::factory()->create();

        $this->actingAs($user)
            ->get(route('plant.create'))
            ->assertStatus(200)
            ->assertSee('data-stepper', false)
            ->assertSee('data-external-strain-search', false)
            ->assertSee('name="external_strain_id"', false)
            ->assertSee('name="start_date"', false);
    }
}
