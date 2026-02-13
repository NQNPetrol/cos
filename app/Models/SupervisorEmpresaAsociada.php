<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupervisorEmpresaAsociada extends Model
{
    protected $table = 'supervisor_empresas_asociadas';

    protected $fillable = [
        'supervisor_id',
        'empresa_asociada_id',
        'user_id',
    ];

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'supervisor_id');
    }

    public function empresaAsociada(): BelongsTo
    {
        return $this->belongsTo(EmpresaAsociada::class, 'empresa_asociada_id');
    }

    public function asignadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeForSupervisor($query, $personalId)
    {
        return $query->where('supervisor_id', $personalId);
    }

    public function scopeForEmpresa($query, $empresaId)
    {
        return $query->where('empresa_asociada_id', $empresaId);
    }
}
