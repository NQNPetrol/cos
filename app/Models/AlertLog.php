<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlertLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_alerta',
        'descripcion',
        'user_id',
        'exito',
        'codigo_respuesta',
        'mensaje_error',
        'payload',
        'respuesta',
        'mision_id',
    ];

    protected $casts = [
        'payload' => 'array',
        'respuesta' => 'array',
        'exito' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mision(): BelongsTo
    {
        return $this->belongsTo(MisionFlytbase::class, 'mision_id');
    }

    // Scopes para filtros
    public function scopeTipo($query, $tipo)
    {
        return $tipo ? $query->where('tipo_alerta', $tipo) : $query;
    }

    public function scopeUsuario($query, $usuarioId)
    {
        return $usuarioId ? $query->where('user_id', $usuarioId) : $query;
    }

    public function scopeExitoso($query, $exitoso)
    {
        if ($exitoso !== null) {
            return $query->where('exito', $exitoso);
        }

        return $query;
    }

    public function scopeFechaDesde($query, $fecha)
    {
        return $fecha ? $query->where('created_at', '>=', $fecha) : $query;
    }

    public function scopeFechaHasta($query, $fecha)
    {
        return $fecha ? $query->where('created_at', '<=', $fecha.' 23:59:59') : $query;
    }

    public function scopePorMisionesUsuario($query, $user)
    {
        if ($user->hasRole('admin') || $user->hasRole('operador')) {
            return $query;
        }

        if ($user->hasRole('cliente')) {
            $userClientes = UserCliente::where('user_id', $user->id)->pluck('cliente_id');

            return $query->whereHas('mision', function ($q) use ($userClientes) {
                $q->whereIn('cliente_id', $userClientes);
            });
        }

        return $query;
    }
}
