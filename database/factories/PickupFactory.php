<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Receipt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pickup>
 */
final class PickupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'receipt_id' => Receipt::factory(),
            'user_id' => User::factory()->staff(),
            'pickup_status' => 'completed',
            'pickup_date' => fake()->dateTimeThisMonth(),
            'is_synced' => true,
        ];
    }

    /**
     * State for a pending pickup.
     *
     * @return Factory
     */
    public function pending(): self
    {
        return $this->state(fn (array $attributes) => [
            'pickup_status' => 'pending',
            'pickup_date' => null,
            'is_synced' => false,
        ]);
    }
}
