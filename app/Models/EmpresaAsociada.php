<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;
use App\Models\ClienteEmpresaAsociada;

class EmpresaAsociada extends Model
{

    protected $table = 'empresas_asociadas';

    protected $fillable = [
        'nombre'
    ];
    
    public function cliente()
    {
        return $this->belongsToMany(Cliente::class, 'cliente_empresa_asociada')
                    ->using(ClienteEmpresaAsociada::class);
    }
}
