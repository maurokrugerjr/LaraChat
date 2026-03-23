<?php

namespace App\Models;

use App\Models\Traits\BelongsToConversa;
use App\Models\Traits\BelongsToUsuario;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ConversaParticipante extends Pivot
{
    use BelongsToConversa, BelongsToUsuario;

    protected $table = 'conversa_participantes';

    public $incrementing = true;

    protected $fillable = [
        'conversa_id',
        'usuario_id',
        'funcao',
        'ultimo_lido_em',
        'silenciado_ate',
    ];

    protected function casts(): array
    {
        return [
            'ultimo_lido_em' => 'datetime',
            'silenciado_ate' => 'datetime',
        ];
    }
}
