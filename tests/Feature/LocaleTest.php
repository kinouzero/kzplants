<?php

namespace Tests\Feature;

use App\Models\Preference;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sets_locale_from_user_preference(): void
    {
        $pref = Preference::create([
            'key' => 'lang',
            'name' => 'Language',
            'type' => 'checklist',
            'options' => json_encode(['props' => ['en' => 'English', 'fr' => 'French']]),
        ]);

        $user = User::factory()->create();
        $user->preferences()->sync([
            ['user_id' => $user->id, 'preference_id' => $pref->id, 'value' => 'fr'],
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertStatus(200);
        $this->assertSame('fr', app()->getLocale());
    }
}
