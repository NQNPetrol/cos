<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClienteEmpresaAsociada extends Pivot
{
    protected $table = 'cliente_empresa_asociada';

    protected $fillable = [
        'cliente_id',
        'empresa_asociada_id',
        'created_at',
        'updated_at',
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
