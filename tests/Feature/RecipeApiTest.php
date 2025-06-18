<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class RecipeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_recipes()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/recipes');

        \Log::info('Recipe API Response', [
            'status' => $response->status(),
            'content' => $response->content(),
            'headers' => $response->headers->all()
        ]);

        $response->assertStatus(200);
    }
}
