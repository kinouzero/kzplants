<?php

use App\Models\Dashboard;
use App\Models\Plant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows creator to update plant', function () {
    $user = User::factory()->create();
    $plant = Plant::factory()->create(['created_by' => $user->id]);

    expect($user->can('update', $plant))->toBeTrue();
});

it('allows dashboard member to view plant', function () {
    $user = User::factory()->create();
    $dashboard = Dashboard::factory()->create();
    $dashboard->users()->attach($user->id);

    $plant = Plant::factory()->create();
    $plant->dashboards()->attach($dashboard->id);

    expect($user->can('view', $plant))->toBeTrue();
});
