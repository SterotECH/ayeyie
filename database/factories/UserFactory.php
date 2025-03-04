<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
final class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->unique()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'role' => 'customer',
            'language' => 'en',
        ];
    }

    /**
     * State for a staff user.
     *
     * @return Factory
     */
    public function staff(): self
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'staff',
        ]);
    }

    /**
     * State for an admin user.
     *
     * @return Factory
     */
    public function admin(): self
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * State for a walk-in customer (no email/password).
     *
     * @return Factory
     */
    public function walkIn(): self
    {
        return $this->state(fn (array $attributes) => [
            'email' => null,
            'password' => null,
            'role' => 'customer',
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
