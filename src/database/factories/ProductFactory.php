<?php

namespace Database\Factories;

use App\Modules\Product\Infrastructure\Models\Category;
use App\Modules\Product\Infrastructure\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'category_id' => Category::factory(),
            'in_stock' => $this->faker->boolean(),
            'rating' => $this->faker->randomFloat(2, 0, 5),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
