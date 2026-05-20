<?php

namespace Tests\Feature;

use App\Models\Dashboard;
use App\Models\Statut;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PlantPhotoUploadValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_upload_photo_rejects_invalid_file(): void
    {
        $user = User::factory()->create();
        Statut::factory()->create(['name' => 'new']);
        $dashboard = Dashboard::factory()->create();
        $dashboard->users()->attach($user->id, ['creator' => true, 'default' => true]);

        $payload = [
            'name' => 'Plant Invalid Photo',
            'external_strain_id' => '777',
            'external_strain_name' => 'Fern',
            'photo_mode' => 'upload',
            'plant_photo' => UploadedFile::fake()->create('bad.txt', 10, 'text/plain'),
            'start_date' => now()->toDateString(),
            'dashboards' => [$dashboard->id],
        ];

        $this->actingAs($user)
            ->post(route('plant.store'), $payload)
            ->assertSessionHasErrors(['plant_photo']);
    }
}
