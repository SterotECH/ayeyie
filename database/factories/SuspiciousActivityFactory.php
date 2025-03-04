<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SuspiciousActivity>
 */
final class SuspiciousActivityFactory extends Factory
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
            'entity_type' => User::class,
            'entity_id' => fn (array $attributes) => $attributes['user_id'],
            'description' => $this->faker->sentence(),
            'severity' => 'medium',
            'detected_at' => $this->faker->dateTimeThisMonth(),
        ];
    }

    /**
     * State for a low severity activity.
     *
     * @return Factory
     */
    public function low(): self
    {
        return $this->state(fn (array $attributes) => [
            'severity' => 'low',
        ]);
    }

    /**
     * State for a high severity activity.
     *
     * @return Factory
     */
    public function high(): self
    {
        return $this->state(fn (array $attributes) => [
            'severity' => 'high',
        ]);
    }
}
