<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OidcLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_oidc_callback_creates_user_and_logs_in(): void
    {
        config()->set('oidc.enabled', true);
        config()->set('oidc.issuer', 'https://issuer.example');
        config()->set('oidc.client_id', 'client');
        config()->set('oidc.client_secret', 'secret');
        config()->set('oidc.redirect_uri', 'http://localhost/login/oidc/callback');
        config()->set('oidc.admin_group', 'admins');

        Cache::forget('oidc.discovery');

        Http::fake([
            'https://issuer.example/.well-known/openid-configuration' => Http::response([
                'authorization_endpoint' => 'https://issuer.example/auth',
                'token_endpoint' => 'https://issuer.example/token',
                'userinfo_endpoint' => 'https://issuer.example/userinfo',
            ], 200),
            'https://issuer.example/token' => Http::response([
                'access_token' => 'access',
            ], 200),
            'https://issuer.example/userinfo' => Http::response([
                'email' => 'user@example.com',
                'name' => 'User Example',
                'groups' => ['admins'],
            ], 200),
        ]);

        $this->withSession([
            'oidc_state' => 'state123',
            'oidc_nonce' => 'nonce123',
        ]);

        $response = $this->get(route('login.oidc.callback', ['state' => 'state123', 'code' => 'code123']));
        $response->assertStatus(302);

        $user = User::where('email', 'user@example.com')->first();
        $this->assertNotNull($user);

        $adminRole = Role::where('name', 'admin')->first();
        $this->assertNotNull($adminRole);
        $this->assertTrue($user->roles()->where('role_id', $adminRole->id)->exists());
    }
}
