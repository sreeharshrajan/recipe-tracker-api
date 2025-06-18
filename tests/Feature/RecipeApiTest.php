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

    public function test_search_by_time_and_ingredients()
    {
        $this->authenticate();

        Recipe::factory()->create([
            'name' => 'Test Soup',
            'ingredients' => 'water, salt, chicken',
            'prep_time' => 5,
            'cook_time' => 10,
        ]);

        $response = $this->getJson('/api/recipes/search?ingredients[]=salt&min_time=5&max_time=20');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Test Soup']);
    }
}
