<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversa extends Model
{
    use HasFactory;

    protected $table = 'conversas';

    protected $fillable = [
        'tipo',
        'nome',
        'avatar',
        'criado_por',
    ];

    public function criador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'criado_por');
    }

    public function mensagens(): HasMany
    {
        return $this->hasMany(Mensagem::class, 'conversa_id');
    }

    public function participantes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversa_participantes', 'conversa_id', 'usuario_id')
            ->using(ConversaParticipante::class)
            ->withPivot(['funcao', 'ultimo_lido_em', 'silenciado_ate'])
            ->withTimestamps();
    }

    public function scopePrivada(Builder $query): Builder
    {
        return $query->where('tipo', 'privada');
    }

    public function scopeGrupo(Builder $query): Builder
    {
        return $query->where('tipo', 'grupo');
    }
}
