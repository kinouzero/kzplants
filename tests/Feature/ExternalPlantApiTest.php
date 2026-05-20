<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExternalPlantApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_proxies_external_plants(): void
    {
        config()->set('services.perenual.key', 'test-key');
        config()->set('services.perenual.base_url', 'https://perenual.test/api/v2');

        Http::fake([
            'perenual.test/api/v2/species-list*' => Http::response([
                'current_page' => 1,
                'data' => [
                    [
                        'id' => 123,
                        'common_name' => 'Monstera',
                        'scientific_name' => ['Monstera deliciosa'],
                        'default_image' => [
                            'thumbnail' => 'https://img.test/monstera-thumb.jpg',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/api/external/plants?q=monstera')
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => '123',
                'name' => 'Monstera',
                'scientific_name' => 'Monstera deliciosa',
                'image_url' => 'https://img.test/monstera-thumb.jpg',
            ]);
    }
}
