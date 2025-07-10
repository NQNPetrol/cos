<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seguimiento extends Model
{

    public function evento(): BelongsTo
    {
       return $this->belongsTo(Evento::class); 
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
