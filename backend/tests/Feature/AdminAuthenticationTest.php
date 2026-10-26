<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_access_the_session_and_logout(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'password' => 'correct-password',
        ]);

        $login = $this->postJson('/api/admin/login', [
            'email' => $admin->email,
            'password' => 'correct-password',
            'device_name' => 'phpunit',
        ]);

        $login
            ->assertOk()
            ->assertJsonPath('data.user.email', $admin->email)
            ->assertJsonPath('data.user.is_admin', true)
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'token_type',
                    'user' => ['id', 'name', 'email', 'is_admin'],
                ],
            ]);

        $token = $login->json('data.token');
        $this->assertNotEmpty($token);

        $this->withToken($token)
            ->getJson('/api/admin/me')
            ->assertOk()
            ->assertJsonPath('data.email', $admin->email);

        $tokenId = (int) explode('|', $token)[0];

        $this->withToken($token)->postJson('/api/admin/logout')->assertOk();
        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $tokenId]);

        $this->app['auth']->forgetGuards();
        $this->withToken($token)->getJson('/api/admin/me')->assertUnauthorized();
    }

    public function test_login_rejects_invalid_credentials_and_non_admin_users(): void
    {
        User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'password' => 'correct-password',
        ]);

        $this->postJson('/api/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');

        User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'user-password',
        ]);

        $this->postJson('/api/admin/login', [
            'email' => 'user@example.com',
            'password' => 'user-password',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    }

    public function test_admin_api_requires_authentication(): void
    {
        $this->getJson('/api/admin/me')->assertUnauthorized();
        $this->getJson('/api/admin/dashboard')->assertUnauthorized();
    }

    public function test_non_admin_users_cannot_access_the_admin_api(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/admin/dashboard')->assertForbidden();
    }

    public function test_admin_can_access_the_dashboard(): void
    {
        Sanctum::actingAs(User::factory()->admin()->create());

        $this->getJson('/api/admin/dashboard')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'stats' => [
                        'projects',
                        'services',
                        'skill_groups',
                        'experiences',
                        'contact_details',
                        'contact_opportunities',
                    ],
                    'recent_projects',
                    'last_updated_at',
                ],
            ]);
    }
}
