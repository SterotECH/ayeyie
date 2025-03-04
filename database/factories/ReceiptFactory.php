<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Receipt>
 */
final class ReceiptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transaction_id' => Transaction::factory(),
            'receipt_code' => 'AYE-' . fake()->unique()->numerify('####'),
            'qr_code' => 'qr' . fake()->unique()->numerify('#####'),
            'issued_at' => fake()->dateTimeThisMonth(),
            'is_synced' => true,
        ];
    }

    /**
     * State for an unsynced receipt.
     *
     * @return Factory
     */
    public function unsynced(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_synced' => false,
        ]);
    }
}
