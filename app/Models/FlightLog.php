<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlightLog extends Model
{
    use HasFactory;

    protected $table = 'flytbase_flight_logs';

    protected $fillable = [
        'piloto_flytbase_id',
        'mision_flytbase_id',
        'alert_log_id',
        'flight_starttime',
        'flight_endtime',
        'flight_time',
        'total_distance',
        'estado',
        'event_id',
        'message',
        'severity',
        'drone_name',
        'dock_name',
        'organization',
        'event_timestamp',
        'site',
        'event_coordinates',
        'automation',
        'drone_battery',
        'flight_details'
    ];

    protected $casts = [
        'flight_starttime' => 'datetime',
        'flight_endtime' => 'datetime',
        'flight_time' => 'integer',
        'total_distance' => 'float',
        'event_coordinates' => 'array'
    ];

    const ESTADO_EN_PROCESO = 'en_proceso';
    const ESTADO_COMPLETADO = 'completado';
    const ESTADO_INTERRUMPIDO = 'interrumpido';

    public function piloto(): BelongsTo
    {
        return $this->belongsTo(PilotoFlytbase::class, 'piloto_flytbase_id');
    }

    public function mision(): BelongsTo
    {
        return $this->belongsTo(MisionFlytbase::class, 'mision_flytbase_id');
    }

    public function alertLog(): BelongsTo
    {
        return $this->belongsTo(AlertLog::class, 'alert_log_id');
    }


    public function scopeEnProceso($query)
    {
        return $query->where('estado', self::ESTADO_EN_PROCESO);
    }

    public function scopeCompletados($query)
    {
        return $query->where('estado', self::ESTADO_COMPLETADO);
    }

    public function scopeInterrumpidos($query)
    {
        return $query->where('estado', self::ESTADO_INTERRUMPIDO);
    }

     public function scopeActivos($query)
    {
        return $query->whereNotNull('flight_starttime')
                    ->whereNull('flight_endtime');
    }

    

    /**
     * Scope para vuelos por rango de fechas
     */
    public function scopePorRangoFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('flight_starttime', [$fechaInicio, $fechaFin]);
    }

    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para buscar flight logs cercanos a un timestamp
     */
    public function scopeCercanosATimestamp($query, $timestamp, $minutosTolerancia = 30)
    {
        $fechaInicio = \Carbon\Carbon::parse($timestamp)->subMinutes($minutosTolerancia);
        $fechaFin = \Carbon\Carbon::parse($timestamp);
        
        return $query->whereBetween('flight_starttime', [$fechaInicio, $fechaFin])
                    ->where('flight_starttime', '<=', $fechaFin);
    }

    /**
     * Calcular automáticamente el tiempo de vuelo si starttime y endtime están presentes
     */
    public function calcularTiempoVuelo(): void
    {
        if ($this->flight_starttime && $this->flight_endtime) {
            $diferencia = $this->flight_starttime->diffInSeconds($this->flight_endtime);
            $this->flight_time = $diferencia;
        }
    }

    public function iniciarVuelo(): bool
    {
        $this->flight_starttime = now();
        $this->estado = self::ESTADO_EN_PROCESO;
        return $this->save();
    }

    public function completarVuelo(): bool
    {
        $this->flight_endtime = now();
        $this->calcularTiempoVuelo();
        $this->estado = self::ESTADO_COMPLETADO;
        return $this->save();
    }

    public function interrumpirVuelo(): bool
    {
        $this->flight_endtime = now();
        $this->calcularTiempoVuelo();
        $this->estado = self::ESTADO_INTERRUMPIDO;
        return $this->save();
    }

    public function estaEnProceso(): bool
    {
        return $this->estado === self::ESTADO_EN_PROCESO;
    }

    /**
     * Verificar si el vuelo está completado
     */
    public function estaCompletado(): bool
    {
        return $this->estado === self::ESTADO_COMPLETADO;
    }

    /**
     * Verificar si el vuelo está interrumpido
     */
    public function estaInterrumpido(): bool
    {
        return $this->estado === self::ESTADO_INTERRUMPIDO;
    }


    /**
     * Verificar si el vuelo está activo
     */
    public function estaActivo(): bool
    {
        return $this->flight_starttime && !$this->flight_endtime;
    }

    /**
     * Verificar si el vuelo está completado
     */
    public function tieneFlightEndTime(): bool
    {
        return $this->flight_starttime && $this->flight_endtime;
    }

    /**
     * Obtener la duración del vuelo en formato legible
     */
    public function getDuracionLegibleAttribute(): string
    {
        if (!$this->flight_time) {
            return 'No disponible';
        }

        $horas = floor($this->flight_time / 3600);
        $minutos = floor(($this->flight_time % 3600) / 60);
        $segundos = $this->flight_time % 60;

        if ($horas > 0) {
            return sprintf("%dh %dm %ds", $horas, $minutos, $segundos);
        }

        if ($minutos > 0) {
            return sprintf("%dm %ds", $minutos, $segundos);
        }

        return sprintf("%ds", $segundos);
    }

    /**
     * Obtener la distancia en formato legible
     */
    public function getDistanciaLegibleAttribute(): string
    {
        if (!$this->total_distance) {
            return 'No disponible';
        }

        if ($this->total_distance >= 1000) {
            return number_format($this->total_distance / 1000, 2) . ' km';
        }

        return number_format($this->total_distance, 2) . ' m';
    }
}
