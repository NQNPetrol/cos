<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PagoServiciosRodado extends Model
{
    use HasFactory;

    protected $table = 'pago_servicios_rodados';

    protected $fillable = [
        'rodado_id',
        'proveedor_id',
        'tipo',
        'monto',
        'monto_service',
        'factura_path',
        'comprobante_pago_path',
        'fecha_pago',
        'moneda',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'monto_service' => 'decimal:2',
        'fecha_pago' => 'date',
    ];

    const TIPO_PAGO_PATENTE = 'pago_patente';
    const TIPO_PAGO_ALQUILER = 'pago_alquiler';
    const TIPO_PAGO_PROVEEDOR = 'pago_proveedor';
    const TIPO_PAGO_A_PROVEEDOR = 'pago_a_proveedor';
    const TIPO_PAGO_SEGURO = 'pago_seguro';
    const TIPO_PAGO_SERVICIO_STARLINK = 'pago_servicio_starlink';
    const TIPO_PAGO_VTV = 'pago_vtv';
    const TIPO_PAGOS_ADICIONALES = 'pagos_adicionales';

    const MONEDA_ARS = 'ARS';
    const MONEDA_USD = 'USD';

    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_PAGADO = 'pagado';
    const ESTADO_VENCIDO = 'vencido';

    public function rodado(): BelongsTo
    {
        return $this->belongsTo(Rodado::class);
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }
}
