<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patrulla extends Model
{
    protected $fillable = ['patente', 'modelo', 'color', 'estado', 'observaciones'];
    
    public function dispositivos()
    {
        return $this->belongsToMany(Dispositivo::class, 'dispositivo_patrulla')
                    ->withPivot('fecha_asignacion')
                    ->withTimestamps();
    }
}
