<?php

namespace App\Models\Traits;

use App\Models\Mensagem;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToMensagem
{
    public function mensagem(): BelongsTo
    {
        return $this->belongsTo(Mensagem::class, 'mensagem_id');
    }
}
