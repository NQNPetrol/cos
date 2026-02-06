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
    ];

    const TIPO_VENCIMIENTO_PAGO = 'vencimiento_pago';
    const TIPO_RECORDATORIO_TURNO = 'recordatorio_turno';
    const TIPO_AGENDAR_TURNO_KM = 'agendar_turno_km';
    const TIPO_PERSONALIZADA = 'personalizada';

    const FRECUENCIA_DIARIA = 'diaria';
    const FRECUENCIA_SEMANAL = 'semanal';
    const FRECUENCIA_MENSUAL = 'mensual';

    const ACCION_DASHBOARD = 'dashboard';
    const ACCION_NOTIFICACION = 'notificacion';
    const ACCION_CORREO = 'correo';

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
}
