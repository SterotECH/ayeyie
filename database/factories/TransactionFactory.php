<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
final class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->staff(),
            'customer_user_id' => User::factory(),
            'total_amount' => fake()->randomFloat(2, 10, 1000),
            'payment_status' => 'completed',
            'payment_method' => fake()->randomElement(['cash', 'card', 'mobile']),
            'transaction_date' => fake()->dateTimeThisMonth(),
            'is_synced' => true,
        ];
    }

    /**
     * State for a pending transaction.
     *
     * @return Factory
     */
    public function pending(): self
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'pending',
            'is_synced' => false,
        ]);
    }

    /**
     * State for a failed transaction.
     *
     * @return Factory
     */
    public function failed(): self
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'failed',
            'is_synced' => true,
        ]);
    }

    /**
     * State for a walk-in transaction (no customer).
     *
     * @return Factory
     */
    public function walkIn(): self
    {
        return $this->state(fn (array $attributes) => [
            'customer_user_id' => null,
        ]);
    }
}
