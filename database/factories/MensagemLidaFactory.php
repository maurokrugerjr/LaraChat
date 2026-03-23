<?php

namespace Database\Factories;

use App\Models\Mensagem;
use App\Models\MensagemLida;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MensagemLida>
 */
class MensagemLidaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'mensagem_id' => Mensagem::factory(),
            'usuario_id'  => User::factory(),
            'lido_em'     => fake()->dateTimeBetween('-7 days', 'now'),
        ];
    }
}
