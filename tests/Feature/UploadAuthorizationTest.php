<?php

namespace Tests\Feature;

use App\Models\Plant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_blocks_upload_for_non_owner(): void
    {
        Storage::fake('public');

        $owner = User::factory()->create();
        $other = User::factory()->create();

        $plant = Plant::factory()->create([
            'created_by' => $owner->id,
        ]);

        $file = UploadedFile::fake()->create('pic.jpg', 10, 'image/jpeg');

        $response = $this->actingAs($other)->post(route('upload.pictures'), [
            'plant_id' => $plant->id,
            'pictures' => [$file],
        ]);

        $response->assertStatus(403);
    }
}
