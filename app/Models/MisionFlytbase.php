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
        'dock_id',
        'site_id',
        'route_altitude',
        'route_speed',
        'route_waypoint_type',
        'waypoints',
        'kmz_file_path',
        'url',
        'activo',
        'observaciones',
        'est_total_distance',
        'est_total_duration',
        'waypoints_count',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'route_altitude' => 'decimal:2',
        'route_speed' => 'decimal:2',
        'waypoints' => 'array',
        'est_total_distance' => 'float',
        'est_total_duration' => 'integer',
        'waypoints_count' => 'integer',
    ];

    const TIPO_RUTA_LINEAL = 'linear_route';

    const TIPO_RUTA_TRANSITO = 'transits_waypoint';

    const TIPO_RUTA_CURVA_PARADA = 'curved_route_drone_stops';

    const TIPO_RUTA_CURVA_CONTINUA = 'curved_route_drone_continues';

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function drone(): BelongsTo
    {
        return $this->belongsTo(FlytbaseDrone::class, 'drone_id');
    }

    public function dock(): BelongsTo
    {
        return $this->belongsTo(FlytbaseDock::class, 'dock_id');
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(FlytbaseSite::class, 'site_id');
    }

    public function alertLogs(): HasMany
    {
        return $this->hasMany(AlertLog::class, 'mision_id');
    }

    public function peticionOrigen(): HasOne
    {
        return $this->hasOne(PeticionMisionFlytbase::class, 'mision_aprobada_id');
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
