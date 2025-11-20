<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sistema extends Model
{
    protected $fillable = [
        'nombre',
        'link',
        'correo_electronico',
        'telefono'
    ];

    public function patrullas()
    {
        return $this->belongsToMany(Patrulla::class, 'patrulla_sistemas')
                    ->withPivot('fecha_registro', 'nro_interno', 'registra_user')
                    ->withTimestamps();
    }
}
