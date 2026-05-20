<?php

use App\Models\Dashboard;
use App\Models\Plant;
use App\Models\Statut;
use App\Models\Strain;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a plant', function () {
    $user = User::factory()->create();
    Statut::factory()->create(['name' => 'new']);
    $strain = Strain::factory()->create();
    $dashboard = Dashboard::factory()->create();
    $dashboard->users()->attach($user->id, ['creator' => true, 'default' => true]);

    $this->actingAs($user)->withoutMiddleware()->post(route('plant.store'), [
        'name' => 'Test Plant',
        'strain_id' => $strain->id,
        'start_date' => now()->toDateString(),
        'dashboards' => [$dashboard->id],
    ])->assertRedirect();

    $this->assertDatabaseHas('plants', [
        'name' => 'Test Plant',
        'strain_id' => $strain->id,
    ]);
});

it('updates a plant', function () {
    $user = User::factory()->create();
    $statut = Statut::factory()->create(['name' => 'new']);
    $strain = Strain::factory()->create();
    $dashboard = Dashboard::factory()->create();
    $dashboard->users()->attach($user->id, ['creator' => true, 'default' => true]);

    $plant = Plant::factory()->create([
        'name' => 'Old Plant',
        'created_by' => $user->id,
        'statut_id' => $statut->id,
        'strain_id' => $strain->id,
    ]);
    $plant->dashboards()->attach($dashboard->id);

    $this->actingAs($user)->withoutMiddleware()->post(route('plant.update', ['id' => $plant->id]), [
        'name' => 'Updated Plant',
        'strain_id' => $strain->id,
        'dashboards' => [$dashboard->id],
    ])->assertRedirect();

    $this->assertDatabaseHas('plants', [
        'id' => $plant->id,
        'name' => 'Updated Plant',
    ]);
});
