<?php

namespace Database\Seeders;

use App\Models\Anexo;
use App\Models\Conversa;
use App\Models\Mensagem;
use App\Models\MensagemLida;
use App\Models\Reacao;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ── Usuário fixo para desenvolvimento ──────────────────────────
        $dev = User::factory()->create([
            'nome'  => 'Dev User',
            'email' => 'dev@larachat.com',
            'senha' => 'senha123',
        ]);

        // ── Outros usuários ────────────────────────────────────────────
        $usuarios = User::factory()->count(9)->create();
        $todos    = $usuarios->prepend($dev);

        // ── Conversas privadas (dev com cada outro usuário) ────────────
        $usuarios->each(function (User $outro) use ($dev) {
            $conversa = Conversa::factory()->privada()->create([
                'criado_por' => $dev->id,
            ]);

            $conversa->participantes()->attach([
                $dev->id   => ['funcao' => 'admin'],
                $outro->id => ['funcao' => 'membro'],
            ]);

            // 5 mensagens alternadas
            collect(range(1, 5))->each(function (int $i) use ($conversa, $dev, $outro) {
                $autor = $i % 2 === 0 ? $dev : $outro;

                $mensagem = Mensagem::factory()->create([
                    'conversa_id' => $conversa->id,
                    'usuario_id'  => $autor->id,
                ]);

                // última mensagem tem reação
                if ($i === 5) {
                    Reacao::factory()->create([
                        'mensagem_id' => $mensagem->id,
                        'usuario_id'  => $dev->id,
                    ]);
                }

                // marcar como lida pelo dev
                MensagemLida::factory()->create([
                    'mensagem_id' => $mensagem->id,
                    'usuario_id'  => $dev->id,
                ]);
            });
        });

        // ── 3 grupos ────────────────────────────────────────────────────
        collect(range(1, 3))->each(function () use ($dev, $todos) {
            $conversa = Conversa::factory()->grupo()->create([
                'criado_por' => $dev->id,
            ]);

            // dev como admin + 4 membros aleatórios
            $membros = $todos->except([$dev->id])->random(4);

            $conversa->participantes()->attach(
                $membros->mapWithKeys(fn (User $u) => [$u->id => ['funcao' => 'membro']])->all()
                + [$dev->id => ['funcao' => 'admin']]
            );

            // 10 mensagens no grupo
            collect(range(1, 10))->each(function (int $i) use ($conversa, $todos) {
                $mensagem = Mensagem::factory()->create([
                    'conversa_id' => $conversa->id,
                    'usuario_id'  => $todos->random()->id,
                ]);

                // mensagem 3 tem anexo de imagem
                if ($i === 3) {
                    Anexo::factory()->imagem()->create([
                        'mensagem_id' => $mensagem->id,
                    ]);
                }

                // mensagem 7 é uma resposta à mensagem 3
                if ($i === 7) {
                    $mensagem->update([
                        'resposta_para_id' => $conversa->mensagens()->skip(2)->first()?->id,
                    ]);
                }
            });
        });
    }
}
