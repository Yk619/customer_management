<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }

    public function test_get_customers()
    {
        $user = User::factory()->create();
        Customer::factory()->count(3)->create();

        Passport::actingAs($user);
        $response = $this->getJson('/api/customers');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_create_customer()
    {
        $user = User::factory()->create();

        Passport::actingAs($user);
        $response = $this->postJson('/api/customers', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'age' => 30,
            'dob' => '1992-01-01',
        ]);

        $response->assertStatus(201)
            ->assertJson(['data' => ['email' => 'john@example.com']]);
    }

    public function test_update_customer()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['email' => 'old@example.com']);

        Passport::actingAs($user);
        $response = $this->putJson("/api/customers/{$customer->id}", [
            'email' => 'new@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson(['data' => ['email' => 'new@example.com']]);
    }

    public function test_delete_customer()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();

        Passport::actingAs($user);
        $response = $this->deleteJson("/api/customers/{$customer->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Customer deleted successfully']);
    }

    public function test_validation_errors()
    {
        $user = User::factory()->create();

        Passport::actingAs($user);
        $response = $this->postJson('/api/customers', [
            'first_name' => '',
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(400)
            ->assertJsonStructure(['error']);
    }
}