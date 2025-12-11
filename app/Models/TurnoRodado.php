<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TurnoRodado extends Model
{
    use HasFactory;

    protected $table = 'turnos_rodados';

    protected $fillable = [
        'rodado_id',
        'taller_id',
        'tipo',
        'fecha_hora',
        'encargado_dejar',
        'encargado_retirar',
        'tipo_reparacion',
        'descripcion',
        'cubre_servicio',
        'tipo_equipo',
        'tipo_cubierta',
        'pago_mano_obra',
        'factura_path',
        'comprobante_pago_path',
        'fecha_factura',
        'dias_vencimiento',
        'fecha_vencimiento_pago',
        'estado',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'fecha_factura' => 'date',
        'fecha_vencimiento_pago' => 'date',
        'cubre_servicio' => 'boolean',
        'pago_mano_obra' => 'decimal:2',
        'dias_vencimiento' => 'integer',
    ];

    const TIPO_TURNO_SERVICE = 'turno_service';
    const TIPO_TURNO_MECANICO = 'turno_mecanico';
    const TIPO_CAMBIO_EQUIPO = 'cambio_equipo';
    const TIPO_TURNO_TALLER = 'turno_taller';

    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_COMPLETADO = 'completado';

    public function rodado(): BelongsTo
    {
        return $this->belongsTo(Rodado::class);
    }

    public function taller(): BelongsTo
    {
        return $this->belongsTo(Taller::class);
    }
}
