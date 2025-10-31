<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeticionMisionFlytbase extends Model
{
    use HasFactory;

    protected $table = 'peticiones_misiones_flytbase';

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
        'observaciones',
        'user_id',
        'estado',
        'revisado_por',
        'comentarios_revisor',
        'revisado_en',
        'mision_aprobada_id'
    ];

    protected $casts = [
        'route_altitude' => 'decimal:2',
        'route_speed' => 'decimal:2',
        'waypoints' => 'array',
        'revisado_en' => 'datetime'
    ];

    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_APROBADA = 'aprobada';
    const ESTADO_RECHAZADA = 'rechazada';

    const TIPO_RUTA_LINEAL = 'linear_route';
    const TIPO_RUTA_TRANSITO = 'transits_waypoint';
    const TIPO_RUTA_CURVA_PARADA = 'curved_route_drone_stops';
    const TIPO_RUTA_CURVA_CONTINUA = 'curved_route_drone_continues';

    const ACCION_IMAGEN_TERMICA = 'take_thermal_image';
    const ACCION_INICIAR_GRABACION = 'start_recording';
    const ACCION_DETENER_GRABACION = 'stop_recording';
    const ACCION_ZOOM_IN = 'zoom_in';
    const ACCION_ZOOM_OUT = 'zoom_out';
    const ACCION_GIMBAL_90 = 'set_gimbal_90';
    const ACCION_GIMBAL_45 = 'set_gimbal_45';
    const ACCION_GIMBAL_0 = 'set_gimbal_0';

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

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function revisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    public function misionAprobada(): BelongsTo
    {
        return $this->belongsTo(MisionFlytbase::class, 'mision_aprobada_id');
    }

    public function scopeAprobadas($query)
    {
        return $query->where('estado', self::ESTADO_APROBADA);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE);
    }

    public function scopePorCliente($query, $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }

    public function scopePorUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function estaPendiente(): bool
    {
        return $this->estado === self::ESTADO_PENDIENTE;
    }

    public function aprobar(User $revisor, ?string $comentarios = null): MisionFlytbase
    {
        // Crear la misión en la tabla oficial
        $mision = MisionFlytbase::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'cliente_id' => $this->cliente_id,
            'drone_id' => $this->drone_id,
            'dock_id' => $this->dock_id,
            'site_id' => $this->site_id,
            'route_altitude' => $this->route_altitude,
            'route_speed' => $this->route_speed,
            'route_waypoint_type' => $this->route_waypoint_type,
            'waypoints' => $this->waypoints,
            'observaciones' => $this->observaciones,
            'url' => '', // Se generará después
            'activo' => true
        ]);

        return $mision;
    }

    public function rechazar(User $revisor, string $razon, ?string $comentarios = null): void
    {
        $this->update([
            'estado' => self::ESTADO_RECHAZADA,
            'revisado_por' => $revisor->id,
            'comentarios_revisor' => $comentarios ?: $razon,
            'revisado_en' => now()
        ]);
    }

    // Métodos para waypoints
    public function agregarWaypoint(float $latitud, float $longitud, float $altitud, array $acciones = []): void
    {
        $waypoints = $this->waypoints ?? [];
        
        $waypoints[] = [
            'latitud' => $latitud,
            'longitud' => $longitud,
            'altitud' => $altitud,
            'acciones' => $acciones,
            'orden' => count($waypoints) + 1
        ];

        $this->update(['waypoints' => $waypoints]);
    }
}
