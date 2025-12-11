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
        'mes',
        'año',
        'monto',
        'monto_service',
        'factura_path',
        'comprobante_pago_path',
        'fecha_pago',
    ];

    protected $casts = [
        'mes' => 'integer',
        'año' => 'integer',
        'monto' => 'decimal:2',
        'monto_service' => 'decimal:2',
        'fecha_pago' => 'date',
    ];

    const TIPO_PAGO_PATENTE = 'pago_patente';
    const TIPO_PAGO_ALQUILER = 'pago_alquiler';
    const TIPO_PAGO_PROVEEDOR = 'pago_proveedor';

    public function rodado(): BelongsTo
    {
        return $this->belongsTo(Rodado::class);
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }
}
