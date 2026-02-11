<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlertaAdmin extends Model
{
    use HasFactory;

    protected $table = 'alertas_admin';

    protected $fillable = [
        'user_id',
        'titulo',
        'descripcion',
        'tipo',
        'trigger_config',
        'accion_config',
        'rodado_id',
        'cliente_id',
        'servicio_usuario_id',
        'taller_id',
        'destinatario_tipo',
        'destinatario_user_id',
        'dia_mes',
        'dias_anticipacion',
        'km_intervalo',
        'fecha_alerta',
        'recurrente',
        'frecuencia_recurrencia',
        'activa',
        'ultima_ejecucion',
    ];

    protected $casts = [
        'trigger_config' => 'array',
        'accion_config' => 'array',
        'fecha_alerta' => 'date',
        'recurrente' => 'boolean',
        'activa' => 'boolean',
        'ultima_ejecucion' => 'datetime',
        'dia_mes' => 'integer',
        'dias_anticipacion' => 'integer',
        'km_intervalo' => 'integer',
    ];

    // Tipos de alerta
    const TIPO_PAGO_SERVICIO = 'pago_servicio';
    const TIPO_COBRO_CLIENTE = 'cobro_cliente';
    const TIPO_KM_VEHICULO = 'km_vehiculo';
    const TIPO_VENCIMIENTO = 'vencimiento';
    const TIPO_PERSONALIZADA = 'personalizada';

    // Backward compat
    const TIPO_VENCIMIENTO_PAGO = 'vencimiento_pago';
    const TIPO_RECORDATORIO_TURNO = 'recordatorio_turno';
    const TIPO_AGENDAR_TURNO_KM = 'agendar_turno_km';

    const FRECUENCIA_DIARIA = 'diaria';
    const FRECUENCIA_SEMANAL = 'semanal';
    const FRECUENCIA_MENSUAL = 'mensual';

    const ACCION_DASHBOARD = 'dashboard';
    const ACCION_NOTIFICACION = 'notificacion';
    const ACCION_CORREO = 'correo';

    const DESTINATARIO_ADMIN = 'admin';
    const DESTINATARIO_CLIENTE = 'cliente';
    const DESTINATARIO_AMBOS = 'ambos';

    /**
     * Get all available tipos for the modal
     */
    public static function getTipos(): array
    {
        return [
            self::TIPO_VENCIMIENTO => 'Recordatorio / Vencimiento',
            self::TIPO_COBRO_CLIENTE => 'Cobro a Cliente',
            self::TIPO_KM_VEHICULO => 'Control por Kilometraje',
            self::TIPO_PERSONALIZADA => 'Personalizada',
        ];
    }

    /**
     * Get human-readable tipo label
     */
    public function getTipoLabelAttribute(): string
    {
        // If tipo is vencimiento and has a service linked, show as "Pago de Servicio"
        if ($this->tipo === self::TIPO_VENCIMIENTO && $this->servicio_usuario_id) {
            return 'Pago de Servicio';
        }

        $tipos = self::getTipos();
        // Also handle legacy types
        $legacyMap = [
            'pago_servicio' => 'Pago de Servicio',
            'vencimiento_pago' => 'Vencimiento de Pago',
            'recordatorio_turno' => 'Recordatorio de Turno',
            'agendar_turno_km' => 'Agendar Turno por KM',
        ];

        return $tipos[$this->tipo] ?? $legacyMap[$this->tipo] ?? ucfirst(str_replace('_', ' ', $this->tipo));
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rodado(): BelongsTo
    {
        return $this->belongsTo(Rodado::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function servicioUsuario(): BelongsTo
    {
        return $this->belongsTo(ServicioUsuario::class);
    }

    public function taller(): BelongsTo
    {
        return $this->belongsTo(Taller::class);
    }

    public function destinatarioUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'destinatario_user_id');
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Check if a specific action is configured
     */
    public function tieneAccion(string $accion): bool
    {
        $acciones = $this->accion_config ?? [];
        return in_array($accion, $acciones);
    }

    /**
     * Get all configured actions
     */
    public function getAcciones(): array
    {
        return $this->accion_config ?? [];
    }

    /**
     * Get the color theme for the tipo
     */
    public function getTipoColorAttribute(): string
    {
        // Vencimiento with service linked = red (payment), otherwise blue (generic reminder)
        if ($this->tipo === 'vencimiento' && $this->servicio_usuario_id) {
            return 'red';
        }

        return match ($this->tipo) {
            'pago_servicio', 'vencimiento_pago' => 'red',
            'cobro_cliente' => 'emerald',
            'km_vehiculo', 'agendar_turno_km' => 'orange',
            'vencimiento', 'recordatorio_turno' => 'blue',
            default => 'purple',
        };
    }
}
