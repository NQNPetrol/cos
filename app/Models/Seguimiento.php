<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seguimiento extends Model
{

    protected $fillable = [
        'titulo',
        'descripcion',
        'estado',
        'fecha',
        'user_id',
        'evento_id',
    ];

    public function evento(): BelongsTo
    {
       return $this->belongsTo(Evento::class); 
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
