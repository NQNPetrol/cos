<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatrullaRegistroFlota extends Model
{
    protected $table = 'patrulla_registros_flota';

    protected $fillable = [
        'fecha_registro',
        'patrulla_id',
        'objetivo_servicio',
        'observacion',
        'user_id'
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
    ];

    public function patrulla()
    {
        return $this->belongsTo(Patrulla::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
