<?php

namespace Database\Factories;

use App\Models\Mensagem;
use App\Models\Reacao;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reacao>
 */
class ReacaoFactory extends Factory
{
    private const EMOJIS = ['👍', '❤️', '😂', '😮', '😢', '🔥', '👏', '🎉'];

    public function definition(): array
    {
        return [
            'mensagem_id' => Mensagem::factory(),
            'usuario_id'  => User::factory(),
            'emoji'       => fake()->randomElement(self::EMOJIS),
        ];
    }
}
