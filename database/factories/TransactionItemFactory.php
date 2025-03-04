<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionItem>
 */
final class TransactionItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 10);
        $unitPrice = fake()->randomFloat(2, 5, 50);

        return [
            'transaction_id' => Transaction::factory(),
            'product_id' => Product::factory(),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'subtotal' => $quantity * $unitPrice,
        ];
    }

    /**
     * State for a high-quantity item.
     *
     * @return Factory
     */
    public function highQuantity(): self
    {
        $quantity = fake()->numberBetween(20, 50);
        $unitPrice = fake()->randomFloat(2, 5, 50);

        return $this->state(fn (array $attributes) => [
            'quantity' => $quantity,
            'subtotal' => $quantity * $unitPrice,
        ]);
    }
}
