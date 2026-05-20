<?php

namespace Tests\Feature;

use App\Models\Dashboard;
use App\Models\Plant;
use App\Models\Statut;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PlantPhotoUploadOverrideTest extends TestCase
{
    use RefreshDatabase;

    public function test_upload_photo_sets_plant_default_picture(): void
    {
        Storage::fake('public');
        Http::fake([
            'img.test/*' => Http::response('fake-image-bytes', 200, ['Content-Type' => 'image/jpeg']),
        ]);

        $user = User::factory()->create();
        Statut::factory()->create(['name' => 'new']);
        $dashboard = Dashboard::factory()->create();
        $dashboard->users()->attach($user->id, ['creator' => true, 'default' => true]);

        $payload = [
            'name' => 'Plant Photo',
            'external_strain_id' => '555',
            'external_strain_name' => 'Aloe',
            'external_strain_image_url' => 'https://img.test/aloe.jpg',
            'photo_mode' => 'upload',
            'plant_photo' => UploadedFile::fake()->create('plant.jpg', 100, 'image/jpeg'),
            'start_date' => now()->toDateString(),
            'dashboards' => [$dashboard->id],
        ];

        $this->actingAs($user)->post(route('plant.store'), $payload)->assertRedirect();

        $plant = Plant::first();
        $this->assertNotNull($plant);
        $this->assertTrue($plant->pictures()->wherePivot('default', true)->exists());
    }
}
