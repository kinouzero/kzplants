<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationConfigTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_saves_notification_config_and_users(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $adminRole = \App\Models\Role::firstOrCreate(['name' => 'admin']);
        $user->roles()->attach($adminRole->id);

        $payload = [
            'name' => 'Test Notif',
            'description' => 'desc',
            'channels' => ['mail', 'ntfy'],
            'mail_subject' => 'Subject',
            'ntfy_url' => 'https://ntfy.example.com',
            'ntfy_topic' => 'topic',
            'ntfy_priority' => 3,
            'ntfy_tags' => 'a,b',
            'users' => [$other->id],
        ];

        $response = $this->actingAs($user)->post(route('notification.store'), $payload);
        $response->assertStatus(302);

        $notification = Notification::first();
        $this->assertNotNull($notification);
        $config = $notification->config();
        $this->assertSame(['mail', 'ntfy'], $config['channels']);
        $this->assertSame('Subject', $config['mail_subject']);
        $this->assertSame('https://ntfy.example.com', $config['ntfy_url']);
        $this->assertSame('topic', $config['ntfy_topic']);
        $this->assertSame(3, $config['ntfy_priority']);
        $this->assertSame('a,b', $config['ntfy_tags']);

        $this->assertTrue($notification->users()->where('user_id', $user->id)->wherePivot('creator', true)->exists());
        $this->assertTrue($notification->users()->where('user_id', $other->id)->exists());
    }
}
