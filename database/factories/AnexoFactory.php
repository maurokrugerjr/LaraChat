<?php

namespace Database\Factories;

use App\Models\Anexo;
use App\Models\Mensagem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Anexo>
 */
class AnexoFactory extends Factory
{
    public function definition(): array
    {
        $mime = fake()->randomElement([
            'image/jpeg', 'image/png', 'application/pdf',
            'audio/mpeg', 'video/mp4',
        ]);

        return [
            'mensagem_id' => Mensagem::factory(),
            'disco'       => 's3',
            'caminho'     => 'uploads/' . fake()->uuid() . '/' . fake()->word() . '.' . $this->extensao($mime),
            'mime_type'   => $mime,
            'tamanho'     => fake()->numberBetween(10_000, 10_000_000),
        ];
    }

    public function imagem(): static
    {
        return $this->state(fn (array $attributes) => [
            'mime_type' => 'image/jpeg',
            'caminho'   => 'uploads/' . fake()->uuid() . '/foto.jpg',
        ]);
    }

    public function pdf(): static
    {
        return $this->state(fn (array $attributes) => [
            'mime_type' => 'application/pdf',
            'caminho'   => 'uploads/' . fake()->uuid() . '/documento.pdf',
        ]);
    }

    private function extensao(string $mime): string
    {
        return match ($mime) {
            'image/jpeg'       => 'jpg',
            'image/png'        => 'png',
            'application/pdf'  => 'pdf',
            'audio/mpeg'       => 'mp3',
            'video/mp4'        => 'mp4',
            default            => 'bin',
        };
    }
}
