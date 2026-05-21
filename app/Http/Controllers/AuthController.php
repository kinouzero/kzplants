<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Login
     */
    public function login(Request $request)
    {
        $login = trim((string) $request->input('email'));

        Log::info('Auth login attempt', [
            'login' => $login,
            'ip' => $request->ip(),
            'host' => $request->getHost(),
            'scheme' => $request->getScheme(),
            'secure' => $request->isSecure(),
            'has_session' => $request->hasSession(),
            'session_id' => $request->session()->getId(),
            'session_cookie' => $request->cookies->has(config('session.cookie')),
            'user_agent' => (string) $request->userAgent(),
        ]);

        $password = (string) $request->input('password');
        $attempted = auth()->attempt(['email' => $login, 'password' => $password]);
        if (! $attempted) {
            $attempted = auth()->attempt(['name' => $login, 'password' => $password]);
        }

        if ($attempted) {
            Log::info('Auth login success', [
                'user_id' => auth()->id(),
                'session_id' => $request->session()->getId(),
            ]);

            return redirect()->intended('/');
        }

        Log::warning('Auth login failed', [
            'login' => $login,
            'session_id' => $request->session()->getId(),
        ]);

        return redirect()->back()->withInput()->withErrors(['email' => 'Email ou mot de passe incorrect.']);
    }

    /**
     * Logout
     */
    public function logout()
    {
        Log::info('Auth logout', [
            'user_id' => auth()->id(),
        ]);
        auth()->logout();

        return redirect('/');
    }

    /**
     * Forgot password form
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send reset link
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Reset password form
     */
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => (string) $request->query('email'),
        ]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * OIDC redirect
     */
    public function oidcRedirect(Request $request)
    {
        if (! config('oidc.enabled')) {
            abort(404);
        }

        $config = $this->oidcConfig();

        $state = Str::random(32);
        $nonce = Str::random(32);

        $request->session()->put('oidc_state', $state);
        $request->session()->put('oidc_nonce', $nonce);

        $params = [
            'response_type' => 'code',
            'client_id' => config('oidc.client_id'),
            'redirect_uri' => config('oidc.redirect_uri'),
            'scope' => config('oidc.scopes'),
            'state' => $state,
            'nonce' => $nonce,
        ];

        return redirect()->away($config['authorization_endpoint'].'?'.http_build_query($params));
    }

    /**
     * OIDC callback
     */
    public function oidcCallback(Request $request)
    {
        if (! config('oidc.enabled')) {
            abort(404);
        }

        $state = $request->session()->pull('oidc_state');
        $nonce = $request->session()->pull('oidc_nonce');

        if (! $state || $state !== $request->get('state')) {
            return redirect()->route('login')->withErrors(['email' => 'OIDC state mismatch.']);
        }

        $code = $request->get('code');
        if (! $code) {
            return redirect()->route('login')->withErrors(['email' => 'OIDC code missing.']);
        }

        $config = $this->oidcConfig();

        $tokenResponse = Http::asForm()->post($config['token_endpoint'], [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => config('oidc.redirect_uri'),
            'client_id' => config('oidc.client_id'),
            'client_secret' => config('oidc.client_secret'),
        ]);

        if (! $tokenResponse->ok()) {
            return redirect()->route('login')->withErrors(['email' => 'OIDC token exchange failed.']);
        }

        $token = $tokenResponse->json();
        $accessToken = $token['access_token'] ?? null;

        if (! $accessToken || empty($config['userinfo_endpoint'])) {
            return redirect()->route('login')->withErrors(['email' => 'OIDC userinfo unavailable.']);
        }

        $userInfoResponse = Http::withToken($accessToken)->get($config['userinfo_endpoint']);
        if (! $userInfoResponse->ok()) {
            return redirect()->route('login')->withErrors(['email' => 'OIDC userinfo request failed.']);
        }

        $claims = $userInfoResponse->json();
        if (! empty($token['id_token'])) {
            $claims = array_merge($this->decodeIdToken($token['id_token']), $claims);
        }

        if ($nonce && isset($claims['nonce']) && $claims['nonce'] !== $nonce) {
            return redirect()->route('login')->withErrors(['email' => 'OIDC nonce mismatch.']);
        }

        $emailClaim = config('oidc.email_claim', 'email');
        $nameClaim = config('oidc.name_claim', 'name');
        $groupsClaim = config('oidc.groups_claim', 'groups');

        $email = data_get($claims, $emailClaim);
        if (! $email) {
            return redirect()->route('login')->withErrors(['email' => 'OIDC email claim missing.']);
        }

        $name = data_get($claims, $nameClaim) ?: ($claims['preferred_username'] ?? $email);

        $isFirstUser = User::count() === 0;

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Str::random(40),
                'email_verified_at' => now(),
            ]
        );

        if ($isFirstUser) {
            $adminRole = Role::firstOrCreate(['name' => 'admin'], ['description' => 'Administrator']);
            if (! $user->roles()->where('role_id', $adminRole->id)->exists()) {
                $user->roles()->attach($adminRole->id);
            }
        }

        $adminGroup = config('oidc.admin_group');
        if ($adminGroup) {
            $groups = data_get($claims, $groupsClaim, []);
            if (is_string($groups)) {
                $groups = array_map('trim', explode(',', $groups));
            }
            if (is_array($groups) && in_array($adminGroup, $groups, true)) {
                $adminRole = Role::firstOrCreate(['name' => 'admin']);
                if (! $user->roles()->where('role_id', $adminRole->id)->exists()) {
                    $user->roles()->attach($adminRole->id);
                }
            }
        }

        auth()->login($user);

        return redirect()->intended('/');
    }

    private function oidcConfig(): array
    {
        $issuer = rtrim((string) config('oidc.issuer'), '/');
        if (! $issuer) {
            abort(500, 'OIDC issuer not configured.');
        }

        return Cache::remember('oidc.discovery', 3600, function () use ($issuer) {
            $response = Http::get($issuer.'/.well-known/openid-configuration');
            if (! $response->ok()) {
                abort(500, 'OIDC discovery failed.');
            }

            return $response->json();
        });
    }

    private function decodeIdToken(string $idToken): array
    {
        $parts = explode('.', $idToken);
        if (count($parts) < 2) {
            return [];
        }

        $payload = $parts[1];
        $payload .= str_repeat('=', (4 - strlen($payload) % 4) % 4);
        $decoded = base64_decode(strtr($payload, '-_', '+/'), true);

        return $decoded ? (json_decode($decoded, true) ?: []) : [];
    }
}
