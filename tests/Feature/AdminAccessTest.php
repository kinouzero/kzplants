<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('blocks non-admin from admin routes', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get(route('tag.index'))->assertStatus(403);
});
