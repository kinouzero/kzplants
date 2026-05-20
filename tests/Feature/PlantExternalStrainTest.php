<?php

namespace Tests\Feature;

use App\Models\Dashboard;
use App\Models\Plant;
use App\Models\Statut;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PlantExternalStrainTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_plant_from_external_strain(): void
    {
        Storage::fake('public');
        Http::fake([
            'img.test/*' => Http::response('fake-image-bytes', 200, ['Content-Type' => 'image/jpeg']),
        ]);

        $user = User::factory()->create();
        $statut = Statut::factory()->create(['name' => 'new']);
        $dashboard = Dashboard::factory()->create();
        $dashboard->users()->attach($user->id, ['creator' => true, 'default' => true]);

        $payload = [
            'name' => 'My Plant',
            'external_strain_id' => '999',
            'external_strain_name' => 'Ficus',
            'external_strain_image_url' => 'https://img.test/ficus.jpg',
            'photo_mode' => 'api',
            'start_date' => now()->toDateString(),
            'dashboards' => [$dashboard->id],
        ];

        $this->actingAs($user)->post(route('plant.store'), $payload)->assertRedirect();

        $plant = Plant::first();
        $this->assertNotNull($plant);
        $this->assertSame('Ficus', $plant->strain->name);
        $this->assertNotNull($plant->strain->defaultPicture());
        $this->assertTrue($plant->strain->pictures()->wherePivot('default', true)->exists());
    }
}
