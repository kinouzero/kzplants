<?php

namespace Tests\Feature;

use App\Models\Plant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadPhotoTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_rejects_invalid_upload_format(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $plant = Plant::factory()->create([
            'created_by' => $user->id,
        ]);

        $file = UploadedFile::fake()->create('not-an-image.txt', 10, 'text/plain');

        $response = $this->actingAs($user)->post(route('upload.pictures'), [
            'plant_id' => $plant->id,
            'pictures' => [$file],
        ]);

        $response->assertSessionHasErrors(['pictures.0']);
    }
}
