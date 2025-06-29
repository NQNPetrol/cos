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
        'detalles',
        'observaciones',
        'fecha_inicio',
        'fecha_fin',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
