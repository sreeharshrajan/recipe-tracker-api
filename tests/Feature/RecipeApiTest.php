<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Recipe;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecipeApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        Sanctum::actingAs(User::factory()->create());
    }

    public function test_can_create_recipe()
    {
        $this->authenticate();

        $payload = [
            'name' => 'Spicy Curry',
            'ingredients' => 'chili, chicken, salt',
            'prep_time' => 15,
            'cook_time' => 20,
            'difficulty' => 'medium',
            'description' => 'Hot and spicy chicken curry.'
        ];

        $response = $this->postJson('/api/recipes', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Spicy Curry']);
    }

    public function test_validation_fails_on_invalid_data()
    {
        $this->authenticate();

        $payload = [
            'name' => '',
            'ingredients' => '',
            'prep_time' => 'not-an-int',
            'cook_time' => null,
            'difficulty' => 'extreme',
            'description' => ''
        ];

        $response = $this->postJson('/api/recipes', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'ingredients',
                'prep_time',
                'cook_time',
                'difficulty',
            ]);
    }


    public function test_can_list_recipes()
    {
        $this->authenticate();
        Recipe::factory()->count(2)->create();

        $response = $this->getJson('/api/recipes');

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'data', 'message']);
    }

    public function test_show_returns_recipe()
    {
        $this->authenticate();
        $recipe = Recipe::factory()->create();

        $response = $this->getJson("/api/recipes/{$recipe->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => $recipe->name]);
    }

    public function test_show_returns_404_for_missing_recipe()
    {
        $this->authenticate();

        $response = $this->getJson('/api/recipes/999');

        $response->assertStatus(404);
    }

    public function test_searches_by_time_and_ingredients()
    {
        $this->authenticate();

        Recipe::factory()->create([
            'name' => 'Saag Aloo',
            'ingredients' => 'potatoes,onion,cumin',
            'prep_time' => 20,
            'cook_time' => 30,
            'difficulty' => 'easy',
            'description' => 'Classic Indian dish'
        ]);

        $query = http_build_query([
            'ingredients' => ['potatoes', 'onion', 'cumin'],
            'min_time' => 20,
            'max_time' => 50,
        ]);

        $response = $this->getJson('/api/recipes/search?' . $query);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Saag Aloo']);
    }

    public function test_can_update_recipe()
    {
        $this->authenticate();

        $recipe = Recipe::factory()->create();

        $response = $this->putJson("/api/recipes/{$recipe->id}", [
            'name' => 'Updated Name',
            'ingredients' => 'salt,pepper',
            'prep_time' => 5,
            'cook_time' => 10,
            'difficulty' => 'easy',
            'description' => 'Updated desc'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Updated Name']);
    }

    public function test_deletes_a_recipe()
    {
        $this->authenticate();

        $recipe = Recipe::factory()->create();

        $response = $this->deleteJson("/api/recipes/{$recipe->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    }

    public function test_can_filter_by_difficulty()
    {
        $this->authenticate();

        Recipe::factory()->create(['difficulty' => 'easy']);
        Recipe::factory()->create(['difficulty' => 'hard']);

        $response = $this->getJson('/api/recipes/difficulty/easy');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }
}
