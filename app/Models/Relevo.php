<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relevo extends Model
{
    public function creador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
