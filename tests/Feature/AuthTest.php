<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_with_mfa()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'MFA token sent to your email']);
    }

    public function test_mfa_verification()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'mfa_token' => '123456',
        ]);

        $response = $this->postJson('/api/mfa-verify', [
            'user_id' => $user->id,
            'mfa_token' => '123456',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
    }

    public function test_invalid_mfa_verification()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'mfa_token' => '123456',
        ]);

        $response = $this->postJson('/api/mfa-verify', [
            'user_id' => $user->id,
            'mfa_token' => 'wrongcode',
        ]);

        $response->assertStatus(401)
            ->assertJson(['error' => 'Invalid MFA token']);
    }
}