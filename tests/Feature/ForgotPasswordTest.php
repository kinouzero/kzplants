<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_page_loads(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
    }

    public function test_forgot_password_creates_reset_token(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }

    public function test_reset_password_updates_user_password(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $token = Password::createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));

        $user->refresh();
        $this->assertTrue(Hash::check('new-password-123', $user->password));
    }

    public function test_forgot_password_throttled(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        [$max] = array_pad(explode(',', (string) config('auth.password_reset_throttle', '5,1')), 2, 1);
        $max = (int) $max;

        for ($i = 0; $i < $max; $i++) {
            $this->post(route('password.email'), [
                'email' => $user->email,
            ]);
        }

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertStatus(429);
    }

    public function test_reset_password_throttled(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $token = Password::createToken($user);

        [$max] = array_pad(explode(',', (string) config('auth.password_reset_throttle', '5,1')), 2, 1);
        $max = (int) $max;

        for ($i = 0; $i < $max; $i++) {
            $this->post(route('password.update'), [
                'token' => $token,
                'email' => $user->email,
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ]);
        }

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertStatus(429);
    }
}
