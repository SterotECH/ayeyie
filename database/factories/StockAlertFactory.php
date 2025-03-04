<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockAlert>
 */
final class StockAlertFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'current_quantity' => fake()->numberBetween(1, 49),
            'threshold' => 50,
            'alert_message' => fake()->sentence(),
            'triggered_at' => fake()->dateTimeThisMonth(),
        ];
    }

    /**
     * State for a critical low stock alert.
     *
     * @return Factory
     */
    public function critical(): self
    {
        return $this->state(fn (array $attributes) => [
            'current_quantity' => fake()->numberBetween(1, 10),
            'alert_message' => 'Critically low stock!',
        ]);
    }
}
