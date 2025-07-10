<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evento extends Model
{
    public function seguimientos(): HasMany
    {
        return $this->hasMany(Seguimiento::class);
    }
}
