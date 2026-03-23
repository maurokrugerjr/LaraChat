<?php

namespace App\Models\Traits;

use App\Models\Conversa;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToConversa
{
    public function conversa(): BelongsTo
    {
        return $this->belongsTo(Conversa::class, 'conversa_id');
    }
}
