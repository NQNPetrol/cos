<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patrulla extends Model
{
    protected $fillable = ['patente', 'marca', 'modelo', 'color', 'estado', 'observaciones'];
    
    public function dispositivos()
    {
        return $this->belongsToMany(Dispositivo::class)
                    ->using(DispositivoPatrulla::class)
                    ->withPivot('fecha_asignacion')
                    ->withTimestamps();
    }
}
