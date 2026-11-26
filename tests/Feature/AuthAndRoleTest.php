<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_donor_can_register_via_api_and_cannot_escalate_role_to_admin(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Donatur Baru',
            'email' => 'donatur.baru@example.com',
            'phone' => '08123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.role', User::ROLE_DONOR);

        $this->assertDatabaseHas('users', [
            'email' => 'donatur.baru@example.com',
            'role' => User::ROLE_DONOR,
        ]);
    }

    public function test_admin_and_donor_can_login_and_logout_via_api(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@kitapeduli.test',
            'password' => 'password123',
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'admin@kitapeduli.test',
            'password' => 'password123',
        ]);

        $loginResponse->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.role', 'admin');

        $token = $loginResponse->json('data.token');

        $meResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/me');

        $meResponse->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.email', $admin->email);

        $logoutResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/logout');

        $logoutResponse->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_regular_donor_and_guest_cannot_access_admin_endpoints_or_pages(): void
    {
        $donor = User::factory()->create(['role' => User::ROLE_DONOR]);

        $this->getJson('/api/admin/dashboard')->assertUnauthorized();

        $this->actingAs($donor, 'sanctum')
            ->getJson('/api/admin/dashboard')
            ->assertForbidden();

        $this->actingAs($donor)
            ->get('/admin/dashboard')
            ->assertForbidden();
    }

    public function test_inactive_user_cannot_login_and_api_returns_json_without_accept_header(): void
    {
        User::factory()->create([
            'email' => 'nonaktif@example.com',
            'password' => 'password123',
            'status' => User::STATUS_INACTIVE,
        ]);

        $this->postJson('/api/login', [
            'email' => 'nonaktif@example.com',
            'password' => 'password123',
        ])->assertForbidden()->assertJsonPath('success', false);

        // Endpoint API harus selalu mengembalikan JSON 401 meskipun tanpa header Accept.
        $this->get('/api/me')
            ->assertUnauthorized()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonPath('success', false);
    }
}
