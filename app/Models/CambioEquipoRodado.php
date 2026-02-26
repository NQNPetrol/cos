<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CambioEquipoRodado extends Model
{
    use HasFactory;

    protected $table = 'cambio_equipo_rodado';

    protected $fillable = [
        'rodado_id',
        'taller_id',
        'tipo',
        'fecha_hora_estimada',
        'tipo_cubierta',
        'pago_mano_obra',
        'factura_path',
        'comprobante_pago_path',
        'kilometraje_en_cambio',
        'dispositivo_id',
        'detalle_equipo_nuevo',
        'detalle_equipo_viejo',
        'motivo',
    ];

    protected $casts = [
        'fecha_hora_estimada' => 'datetime',
        'pago_mano_obra' => 'decimal:2',
        'kilometraje_en_cambio' => 'integer',
    ];

    const TIPO_CUBIERTAS = 'cubiertas';

    const TIPO_ANTENA_STARLINK = 'antena_starlink';

    const TIPO_CAMARA_MOBIL = 'camara_mobil';

    const TIPO_DVR = 'dvr';

    public function rodado(): BelongsTo
    {
        return $this->belongsTo(Rodado::class);
    }

    public function taller(): BelongsTo
    {
        return $this->belongsTo(Taller::class);
    }

    public function dispositivo(): BelongsTo
    {
        return $this->belongsTo(Dispositivo::class);
    }

    public function scopeRequiereDispositivo($query)
    {
        return $query->whereIn('tipo', [
            self::TIPO_ANTENA_STARLINK,
            self::TIPO_CAMARA_MOBIL,
            self::TIPO_DVR,
        ]);
    }
}
