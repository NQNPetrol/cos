<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $fillable = [
        'cliente_id',
        'empresa_asociada_id',
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

    public function empresaAsociada()
    {
        return $this->belongsTo(EmpresaAsociada::class, 'empresa_asociada_id');
    }
}
