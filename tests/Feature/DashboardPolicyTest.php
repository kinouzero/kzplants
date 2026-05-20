<?php

use App\Models\Dashboard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows creator to update dashboard', function () {
    $user = User::factory()->create();
    $dashboard = Dashboard::factory()->create();
    $dashboard->users()->attach($user->id, ['creator' => true]);

    expect($user->can('update', $dashboard))->toBeTrue();
});

it('allows member to view dashboard', function () {
    $user = User::factory()->create();
    $dashboard = Dashboard::factory()->create();
    $dashboard->users()->attach($user->id);

    expect($user->can('view', $dashboard))->toBeTrue();
});
