<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\Cliente;
use App\Models\EmpresaAsociada;

class ClienteEmpresaAsociada extends Pivot
{
    protected $table = 'cliente_empresa_asociada';

    protected $fillable = [
        'cliente_id',
        'empresa_asociada_id'
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
