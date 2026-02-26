<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecorridoTimetable extends Model
{
    protected $table = 'recorridos_timetable';

    protected $fillable = [
        'recorrido_id',
        'fecha_hora_inicio',
        'velocidad',
        'fechahora_fin_est',
        'duracion_est',
        'patrulla_id',
        'supervisor_id',
        'user_id',
        'velocidad_excedida',
    ];

    protected $casts = [
        'fecha_hora_inicio' => 'datetime',
        'fechahora_fin_est' => 'datetime',
        'velocidad' => 'integer',
        'duracion_est' => 'integer',
        'velocidad_excedida' => 'boolean',
    ];

    public function recorrido(): BelongsTo
    {
        return $this->belongsTo(Recorrido::class, 'recorrido_id');
    }

    public function patrulla(): BelongsTo
    {
        return $this->belongsTo(Patrulla::class, 'patrulla_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'supervisor_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Calculate estimated duration (in minutes) based on speed and route length.
     */
    public function calcularDuracionEstimada(): ?int
    {
        if (! $this->velocidad || ! $this->recorrido || ! $this->recorrido->longitud_mts) {
            return null;
        }

        $distanciaKm = $this->recorrido->longitud_mts / 1000;
        $duracionHoras = $distanciaKm / $this->velocidad;

        return (int) round($duracionHoras * 60);
    }
}
