<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;

class EmpresaAsociada extends Model
{

    protected $table = 'empresas_asociadas';
    protected $fillable = [
        'cliente_id',
        'nombre'
    ];
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
