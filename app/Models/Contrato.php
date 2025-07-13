<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $fillable = [
        'cliente_id',
        'nombre_proyecto',
        'localidad',
        'provincia',
        'observaciones',
        'fecha_inicio',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
