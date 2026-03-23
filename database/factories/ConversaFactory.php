<?php

namespace Database\Factories;

use App\Models\Conversa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Conversa>
 */
class ConversaFactory extends Factory
{
    public function definition(): array
    {
        $tipo = fake()->randomElement(['privada', 'grupo']);

        return [
            'tipo'      => $tipo,
            'nome'      => $tipo === 'grupo' ? fake()->words(3, true) : null,
            'avatar'    => null,
            'criado_por' => User::factory(),
        ];
    }

    public function privada(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'privada',
            'nome' => null,
        ]);
    }

    public function grupo(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'grupo',
            'nome' => fake()->words(3, true),
        ]);
    }
}
