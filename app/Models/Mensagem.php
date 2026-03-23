<?php

namespace App\Models;

use App\Models\Traits\BelongsToConversa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mensagem extends Model
{
    use HasFactory, SoftDeletes, BelongsToConversa;

    protected $table = 'mensagens';

    protected $fillable = [
        'conversa_id',
        'usuario_id',
        'resposta_para_id',
        'tipo',
        'corpo',
    ];

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function respostaPara(): BelongsTo
    {
        return $this->belongsTo(Mensagem::class, 'resposta_para_id');
    }

    public function respostas(): HasMany
    {
        return $this->hasMany(Mensagem::class, 'resposta_para_id');
    }

    public function reacoes(): HasMany
    {
        return $this->hasMany(Reacao::class, 'mensagem_id');
    }

    public function leituras(): HasMany
    {
        return $this->hasMany(MensagemLida::class, 'mensagem_id');
    }

    public function anexos(): HasMany
    {
        return $this->hasMany(Anexo::class, 'mensagem_id');
    }
}
