<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seguimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'estado',
        'detalles',
        'registra',
    ];

    public function evento(): BelongsTo
    {
       return $this->belongsTo(Evento::class, 'id_evento'); 
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registra');
    }
}
