<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seguimiento extends Model
{
    protected $fillable = [
        'titulo',
        'observaciones',
        'fecha',
        'estado',
        'evento_id',
        'user_id'];

    protected $casts = [
        'fecha' => 'datetime'
    ];

    protected $with = ['evento']; 
    
    public function evento(): BelongsTo
    {
       return $this->belongsTo(Evento::class); 
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
