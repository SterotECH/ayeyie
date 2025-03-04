<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
final class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word() . ' Feed',
            'description' => fake()->optional()->sentence(),
            'price' => fake()->randomFloat(2, 5, 100),
            'stock_quantity' => fake()->numberBetween(50, 200),
            'threshold_quantity' => 50,
        ];
    }

    /**
     * State for a low stock product.
     *
     * @return Factory
     */
    public function lowStock(): self
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => fake()->numberBetween(1, 49),
        ]);
    }
}
