<?php

use App\Models\Dashboard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->withoutMiddleware()->post(route('dashboard.store'), [
        'name' => 'My Dashboard',
        'description' => 'Test dashboard',
        'color' => '#000000',
    ])->assertRedirect();

    $this->assertDatabaseHas('dashboards', [
        'name' => 'My Dashboard',
    ]);
});

it('updates a dashboard', function () {
    $user = User::factory()->create();
    $dashboard = Dashboard::factory()->create(['name' => 'Old']);
    $dashboard->users()->attach($user->id, ['creator' => true]);

    $this->actingAs($user)->withoutMiddleware()->post(route('dashboard.update', ['id' => $dashboard->id]), [
        'name' => 'New Name',
        'description' => 'Updated',
        'color' => '#111111',
    ])->assertRedirect();

    $this->assertDatabaseHas('dashboards', [
        'id' => $dashboard->id,
        'name' => 'New Name',
    ]);
});
