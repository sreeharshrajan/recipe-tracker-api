<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;  // Use RefreshDatabase for a clean slate for each test

    /**
     * Test that an user login with correct credentials.
     *
     * @return void
     */
    public function test_user_can_login_with_correct_credentials(): void
    {
        // 1. Create a user
        $user = User::factory()->create([
            'password' => bcrypt('password123') // Hash the password
        ]);

        // 2. Make a POST request to the login endpoint
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123'
        ]);

        // 3. Assert the response status and JSON structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'email_verified_at',
                        'created_at',
                        'updated_at',
                    ],
                    'token',
                ],
            ])
            // Assert specific values if needed
            ->assertJsonFragment([
                'status' => true,
                'message' => 'Login successful.',
                'email' => $user->email
            ])
            // Assert the token is a non-empty string
            ->assertJsonPath('data.token', fn($token) => is_string($token) && !empty($token));
    }

    /**
     * Test that login fails with invalid credentials.
     *
     * @return void
     */
    public function test_login_fails_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpass'
        ]);

        \Log::error('Fail' . json_encode($response));

        $response->assertStatus(401)
            ->assertJson([
                'status' => false,
                'message' => 'Invalid credentials.',
                'data' => null
            ]);
    }

    /**
     * Test that an authenticated user can fetch their details.
     *
     * @return void
     */
    public function test_authenticated_user_can_fetch_user_details(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['api']); // Authenticate the user for the 'api' guard/scope
        
        $response = $this->getJson('/api/user');

        // Create a token for the user.
        // $token = $user->createToken('test-token')->plainTextToken;

        // $response = $this->withHeader('Authorization', "Bearer $token")
        //     ->getJson('/api/user');

        // \Log::info('User Fetch Response: ' . json_encode($response->json()));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJsonFragment([
                'status' => true,
                'message' => 'User details retrieved successfully.',
                'email' => $user->email,
            ]);
    }
}
