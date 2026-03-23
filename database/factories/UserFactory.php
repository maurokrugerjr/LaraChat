<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'senha'             => 'senha123',
            'avatar'            => null,
            'bio'               => fake()->optional()->sentence(),
            'status_customizado' => fake()->optional()->words(3, true),
            'status'            => fake()->randomElement(['online', 'offline']),
            'ultimo_acesso'     => fake()->dateTimeBetween('-30 days', 'now'),
            'remember_token'    => \Illuminate\Support\Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function online(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'online',
        ]);
    }

    public function offline(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'        => 'offline',
            'ultimo_acesso' => fake()->dateTimeBetween('-7 days', '-1 hour'),
        ]);
    }
}
