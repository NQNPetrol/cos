<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServicioUsuario extends Model
{
    use HasFactory;

    protected $table = 'servicios_usuario';

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo_calculo',
        'valor_unitario',
        'moneda',
        'activo',
    ];

    protected $casts = [
        'valor_unitario' => 'decimal:2',
        'activo' => 'boolean',
    ];

    const TIPO_FIJO = 'fijo';
    const TIPO_VARIABLE = 'variable';

    const MONEDA_ARS = 'ARS';
    const MONEDA_USD = 'USD';

    public function pagos(): HasMany
    {
        return $this->hasMany(PagoServiciosRodado::class, 'servicio_usuario_id');
    }

    public function cobranzas(): HasMany
    {
        return $this->hasMany(Cobranza::class, 'servicio_usuario_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Calcula el monto total basado en el tipo de calculo
     */
    public function calcularMonto(int $cantidad = 1): float
    {
        if ($this->tipo_calculo === self::TIPO_VARIABLE) {
            return (float) $this->valor_unitario * $cantidad;
        }

        return (float) $this->valor_unitario;
    }
}
