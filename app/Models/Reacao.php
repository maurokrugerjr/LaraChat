<?php

namespace App\Models;

use App\Models\Traits\BelongsToMensagem;
use App\Models\Traits\BelongsToUsuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reacao extends Model
{
    use HasFactory, BelongsToMensagem, BelongsToUsuario;

    protected $table = 'reacoes';

    protected $fillable = [
        'mensagem_id',
        'usuario_id',
        'emoji',
    ];
}
