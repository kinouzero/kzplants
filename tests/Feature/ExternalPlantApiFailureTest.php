<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExternalPlantApiFailureTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_503_when_external_api_fails(): void
    {
        config()->set('services.perenual.key', 'test-key');
        config()->set('services.perenual.base_url', 'https://perenual.test/api/v2');

        Http::fake([
            'perenual.test/api/v2/species-list*' => Http::response(null, 500),
        ]);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/api/external/plants?q=rose')
            ->assertStatus(503)
            ->assertJsonFragment(['error' => 'External API unavailable.']);
    }
}
