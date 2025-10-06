<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MisionFlytbase extends Model
{
    use HasFactory;

    protected $table = 'misiones_flytbase';

    protected $fillable = [
        'nombre',
        'descripcion',
        'cliente_id',
        'url',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function alertLogs(): HasMany
    {
        return $this->hasMany(AlertLog::class, 'mision_id');
    }

    // Scope para misiones activas
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    // Scope para misiones de un cliente específico
    public function scopePorCliente($query, $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }

    // Scope para misiones de múltiples clientes
    public function scopePorClientes($query, array $clienteIds)
    {
        return $query->whereIn('cliente_id', $clienteIds);
    }
}