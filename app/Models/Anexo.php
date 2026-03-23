<?php

namespace App\Models;

use App\Models\Traits\BelongsToMensagem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Anexo extends Model
{
    use HasFactory, BelongsToMensagem;

    protected $table = 'anexos';

    protected $fillable = [
        'mensagem_id',
        'disco',
        'caminho',
        'mime_type',
        'tamanho',
    ];

    protected $appends = ['url_assinada'];

    public function getUrlAssinadaAttribute(): string
    {
        return Storage::disk($this->disco)->temporaryUrl(
            $this->caminho,
            now()->addMinutes(5)
        );
    }
}
