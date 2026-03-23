<?php

namespace App\Models;

use App\Models\Traits\BelongsToMensagem;
use App\Models\Traits\BelongsToUsuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MensagemLida extends Model
{
    use HasFactory, BelongsToMensagem, BelongsToUsuario;

    protected $table = 'mensagens_lidas';

    public $timestamps = false;

    protected $fillable = [
        'mensagem_id',
        'usuario_id',
        'lido_em',
    ];

    protected function casts(): array
    {
        return [
            'lido_em' => 'datetime',
        ];
    }
}
