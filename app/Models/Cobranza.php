<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cobranza extends Model
{
    use HasFactory;

    protected $table = 'cobranzas';

    protected $fillable = [
        'cliente_id',
        'servicio_usuario_id',
        'concepto',
        'valor_unitario',
        'cantidad',
        'monto_total',
        'moneda',
        'estado',
        'fecha_emision',
        'fecha_vencimiento',
        'fecha_pago',
        'factura_path',
        'comprobante_path',
        'observaciones',
    ];

    protected $casts = [
        'valor_unitario' => 'decimal:2',
        'monto_total' => 'decimal:2',
        'fecha_emision' => 'date',
        'fecha_vencimiento' => 'date',
        'fecha_pago' => 'date',
    ];

    const ESTADO_PENDIENTE = 'pendiente';

    const ESTADO_COBRADO = 'cobrado';

    const ESTADO_VENCIDO = 'vencido';

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function servicioUsuario(): BelongsTo
    {
        return $this->belongsTo(ServicioUsuario::class, 'servicio_usuario_id');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE);
    }

    public function scopeCobradas($query)
    {
        return $query->where('estado', self::ESTADO_COBRADO);
    }

    public function scopeVencidas($query)
    {
        return $query->where('estado', self::ESTADO_VENCIDO);
    }

    public function scopeDelMes($query, $mes = null, $anio = null)
    {
        $mes = $mes ?? now()->month;
        $anio = $anio ?? now()->year;

        return $query->whereMonth('fecha_emision', $mes)->whereYear('fecha_emision', $anio);
    }
}
