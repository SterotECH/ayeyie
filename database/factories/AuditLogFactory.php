<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuditLog>
 */
final class AuditLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'action' => fake()->randomElement(['login', 'payment_processed', 'pickup_verified']),
            'entity_type' => User::class,
            'entity_id' => fn (array $attributes) => $attributes['user_id'],
            'details' => fake()->optional()->sentence(),
            'logged_at' => fake()->dateTimeThisMonth(),
        ];
    }

    /**
     * State for a payment-related audit log.
     *
     * @return Factory
     */
    public function payment(): self
    {
        return $this->state(fn (array $attributes) => [
            'action' => 'payment_processed',
            'entity_type' => Transaction::class,
            'entity_id' => Transaction::factory(),
            'details' => 'Processed a payment of $' . fake()->randomFloat(2, 10, 100),
        ]);
    }
}
