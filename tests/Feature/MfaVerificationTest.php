<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class MfaVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_redirects_to_mfa_form_after_successful_login()
    {
        Notification::fake();
        
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $response->assertRedirect(route('verify-mfa'));
        $this->assertTrue(session()->has('mfa_token'));
    }

    /** @test */
    public function it_shows_404_when_accessing_mfa_form_without_session()
    {
        $response = $this->get(route('verify-mfa'));
        $response->assertRedirect('/login');
        $response->assertSessionHas('error', 'Session expired');
    }

    /** @test */
    public function it_successfully_verifies_valid_mfa_token()
    {
        $user = User::factory()->create();
        
        $this->withSession([
            'mfa_token' => 1234,
            'mfa_user_id' => $user->id
        ]);

        $response = $this->post(route('verify-mfa'), [
            'mfa_token' => 1234
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function it_rejects_invalid_mfa_token()
    {
        $user = User::factory()->create();
        
        $this->withSession([
            'mfa_token' => 1234,
            'mfa_user_id' => $user->id
        ]);

        $response = $this->post(route('verify-mfa'), [
            'mfa_token' => 9999 // Invalid token
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('mfa_token');
        $this->assertGuest();
    }

    /** @test */
    public function it_handles_expired_mfa_session()
    {
        $response = $this->post(route('verify-mfa'), [
            'mfa_token' => 1234
        ]);

        $response->assertRedirect('/login');
    }

    /** @test */
    public function it_validates_mfa_token_format()
    {
        $invalidTokens = [
            'abcd',  // Non-numeric
            '12',    // Too short
            '12345', // Too long
            ''       // Empty
        ];

        foreach ($invalidTokens as $token) {
            $response = $this->post(route('verify-mfa'), [
                'mfa_token' => $token
            ]);
            
            $response->assertSessionHasErrors('mfa_token');
        }
    }

    /** @test */
    public function it_maintains_intended_redirect_after_mfa()
    {
        $user = User::factory()->create();
        
        $this->withSession([
            'mfa_token' => 1234,
            'mfa_user_id' => $user->id,
            'url.intended' => route('dashboard')
        ]);

        $response = $this->post(route('verify-mfa'), [
            'mfa_token' => 1234
        ]);

        $response->assertRedirect(route('dashboard'));
    }
}