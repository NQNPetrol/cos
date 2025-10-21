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
        'drone_id',
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

    public function drone(): BelongsTo
    {
        return $this->belongsTo(FlytbaseDrone::class, 'drone_id');
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

    public function scopePorClienteUsuario($query, $user)
    {
        if ($user->hasRole('admin') || $user->hasRole('operador')) {
            return $query;
        }

        if ($user->hasRole('cliente')) {
            $clienteIds = UserCliente::where('user_id', $user->id)->pluck('cliente_id');
            return $query->whereIn('cliente_id', $clienteIds->toArray());
        }

        // Si no tiene rol válido, no retorna ninguna misión
        return $query->where('cliente_id', 0);
    }

    public function scopeConDrone($query)
    {
        return $query->whereNotNull('drone_id');
    }

    public function hasLiveview(): bool
    {
        return $this->drone && $this->drone->hasLiveviewView();
    }

    public function getLiveviewRoute(): string
    {
        return $this->drone ? $this->drone->liveview_route : '';
    }

    public function getLiveviewViewPath(): string
    {
        return $this->drone ? $this->drone->liveview_view_path : '';
    }
}