<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'avatar',
        'bio',
        'status_customizado',
        'status',
        'ultimo_acesso',
    ];

    protected $hidden = [
        'senha',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'ultimo_acesso'     => 'datetime',
            'senha'             => 'hashed',
        ];
    }

    public function getAuthPassword(): string
    {
        return $this->senha;
    }

    public function getAuthPasswordName(): string
    {
        return 'senha';
    }

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function mensagens(): HasMany
    {
        return $this->hasMany(Mensagem::class, 'usuario_id');
    }

    public function conversas(): BelongsToMany
    {
        return $this->belongsToMany(Conversa::class, 'conversa_participantes', 'usuario_id', 'conversa_id')
            ->using(ConversaParticipante::class)
            ->withPivot(['funcao', 'ultimo_lido_em', 'silenciado_ate'])
            ->withTimestamps();
    }

    public function scopeOnline(Builder $query): Builder
    {
        return $query->where('status', 'online');
    }
}
