<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmpresaAsociada extends Model
{
    protected $table = 'empresas_asociadas';

    protected $fillable = [
        'nombre',
    ];

    public function cliente()
    {
        return $this->belongsToMany(Cliente::class, 'cliente_empresa_asociada')
            ->withPivot(['created_at', 'updated_at'])
            ->using(ClienteEmpresaAsociada::class);
    }

    public function recorridos(): HasMany
    {
        return $this->hasMany(Recorrido::class, 'empresa_asociada_id');
    }
}
