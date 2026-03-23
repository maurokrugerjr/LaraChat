<?php

namespace Database\Factories;

use App\Models\Conversa;
use App\Models\Mensagem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Mensagem>
 */
class MensagemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'conversa_id'     => Conversa::factory(),
            'usuario_id'      => User::factory(),
            'resposta_para_id' => null,
            'tipo'            => 'texto',
            'corpo'           => fake()->sentence(),
        ];
    }

    public function imagem(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo'  => 'imagem',
            'corpo' => null,
        ]);
    }

    public function arquivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo'  => 'arquivo',
            'corpo' => null,
        ]);
    }

    public function resposta(Mensagem $mensagem): static
    {
        return $this->state(fn (array $attributes) => [
            'conversa_id'      => $mensagem->conversa_id,
            'resposta_para_id' => $mensagem->id,
        ]);
    }
}
