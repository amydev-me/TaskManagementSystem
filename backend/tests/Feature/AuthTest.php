<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase; // Resets database after each test

    /** @test */
    public function user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['user', 'authorisation']);
    }

    /** @test */
    public function user_cannot_register_with_invalid_data()
    {
        User::factory()->create(['email' => 'john@example.com']);

        $response = $this->postJson('/api/register', [
            'name' => null,
            'email' => 'john@example.com',
            'password' => '123',
            'password_confirmation' => '321', // Does not match
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    /** @test */
    public function user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['user', 'authorisation']);
    }

    /** @test */
    public function user_cannot_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401) // Unauthorized
                 ->assertJson(['error' => 'Unauthorized']);
    }

    /** @test */
    public function authenticated_user_can_logout()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $response = $this->postJson('/api/logout', [], [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Successfully logged out']);
    }

    /** @test */
    public function authenticated_user_can_refresh_token()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $response = $this->postJson('/api/refresh', [], [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['user', 'authorisation']);
    }

    /** @test */
    public function authenticated_user_can_get_profile()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $response = $this->getJson('/api/me', [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'user' => [
                'email' => $user->email
            ]
        ]);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_protected_routes()
    {
        $response = $this->getJson('/api/me');

        $response->assertStatus(401); // Unauthorized
    }
}
