<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'product_number' => strtoupper(Str::random(8)),
            'price' => $this->faker->randomFloat(2, 1, 500),
            'description' => $this->faker->paragraph(3),
            'is_active' => $this->faker->boolean(90),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Product $product) {
            $product->addMedia(public_path('images/thumbnail.png'))
                ->preservingOriginal()
                ->toMediaCollection('product-images');
        });
    }
}
