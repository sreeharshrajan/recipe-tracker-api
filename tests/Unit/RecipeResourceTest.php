<?php

namespace Tests\Unit;

use App\Models\Recipe;
use App\Http\Resources\RecipeResource;
use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecipeResourceTest extends TestCase
{
  use RefreshDatabase;

  #[Test]
  public function it_calculates_total_time_correctly()
  {
    $recipe = Recipe::factory()->create([
      'prep_time' => 15,
      'cook_time' => 20,
    ]);

    $resource = (new RecipeResource($recipe))->resolve();

    $this->assertEquals(
      35,
      $resource['total_time'],
      'Total time should be the sum of prep_time (15) and cook_time (20)'
    );
  }
}
