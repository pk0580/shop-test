<?php

namespace Tests\Feature;

use App\Modules\Product\Infrastructure\Models\Category;
use App\Modules\Product\Infrastructure\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_search_products_by_name(): void
    {
        Product::factory()->create(['name' => 'Apple iPhone']);
        Product::factory()->create(['name' => 'Samsung Galaxy']);

        $response = $this->getJson('/api/products?q=iPhone');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Apple iPhone');
    }

    public function test_can_filter_products_by_price_range(): void
    {
        Product::factory()->create(['price' => 100]);
        Product::factory()->create(['price' => 200]);
        Product::factory()->create(['price' => 300]);

        $response = $this->getJson('/api/products?price_from=150&price_to=250');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.price', '200.00');
    }

    public function test_can_filter_products_by_category(): void
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        Product::factory()->create(['category_id' => $category1->id]);
        Product::factory()->create(['category_id' => $category2->id]);

        $response = $this->getJson('/api/products?category_id=' . $category1->id);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.category_id', $category1->id);
    }

    public function test_can_filter_products_by_stock_status(): void
    {
        Product::factory()->create(['in_stock' => true]);
        Product::factory()->create(['in_stock' => false]);

        $response = $this->getJson('/api/products?in_stock=1');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.in_stock', true);
    }

    public function test_can_filter_products_by_rating(): void
    {
        Product::factory()->create(['rating' => 3.5]);
        Product::factory()->create(['rating' => 4.5]);

        $response = $this->getJson('/api/products?rating_from=4.0');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.rating', 4.5);
    }

    public function test_can_sort_products_by_price_desc(): void
    {
        Product::factory()->create(['price' => 100]);
        Product::factory()->create(['price' => 300]);
        Product::factory()->create(['price' => 200]);

        $response = $this->getJson('/api/products?sort=price_desc');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.price', '300.00')
            ->assertJsonPath('data.1.price', '200.00')
            ->assertJsonPath('data.2.price', '100.00');
    }

    public function test_pagination_works(): void
    {
        Product::factory()->count(20)->create();

        $response = $this->getJson('/api/products?per_page=5&page=2');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('current_page', 2)
            ->assertJsonPath('per_page', 5)
            ->assertJsonPath('total', 20);
    }

    public function test_can_combine_multiple_filters(): void
    {
        $category = Category::factory()->create();

        // Target product
        Product::factory()->create([
            'name' => 'Specific Phone',
            'price' => 500,
            'category_id' => $category->id,
            'in_stock' => true,
            'rating' => 4.8
        ]);

        // Noise
        Product::factory()->create(['name' => 'Specific Phone', 'price' => 100]); // Low price
        Product::factory()->create(['name' => 'Other Phone', 'price' => 500]); // Wrong name
        Product::factory()->create(['name' => 'Specific Phone', 'price' => 500, 'in_stock' => false]); // Out of stock

        $response = $this->getJson('/api/products?' . http_build_query([
            'q' => 'Specific',
            'price_from' => 400,
            'price_to' => 600,
            'category_id' => $category->id,
            'in_stock' => 1,
            'rating_from' => 4.5
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Specific Phone');
    }

    public function test_can_sort_by_newest(): void
    {
        $old = Product::factory()->create(['created_at' => now()->subDay()]);
        $new = Product::factory()->create(['created_at' => now()]);

        $response = $this->getJson('/api/products?sort=newest');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.id', $new->id)
            ->assertJsonPath('data.1.id', $old->id);
    }

    public function test_can_sort_by_rating_desc(): void
    {
        Product::factory()->create(['rating' => 1.5]);
        Product::factory()->create(['rating' => 4.8]);
        Product::factory()->create(['rating' => 3.2]);

        $response = $this->getJson('/api/products?sort=rating_desc');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.rating', 4.8)
            ->assertJsonPath('data.1.rating', 3.2)
            ->assertJsonPath('data.2.rating', 1.5);
    }
}
