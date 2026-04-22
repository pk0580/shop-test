<?php

namespace Tests\Feature;

use App\Modules\Product\Infrastructure\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_validates_rating_range(): void
    {
        $response = $this->getJson('/api/products?rating_from=6');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['rating_from']);
    }

    public function test_it_validates_negative_price(): void
    {
        $response = $this->getJson('/api/products?price_from=-10');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['price_from']);
    }

    public function test_it_validates_invalid_category_id(): void
    {
        $response = $this->getJson('/api/products?category_id=999');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['category_id']);
    }

    public function test_it_validates_invalid_sort_parameter(): void
    {
        $response = $this->getJson('/api/products?sort=invalid_sort');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['sort']);
    }

    public function test_it_validates_pagination_parameters(): void
    {
        $response = $this->getJson('/api/products?page=0&per_page=101');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['page', 'per_page']);
    }
}
