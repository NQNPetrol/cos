<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Rodado extends Model
{
    use HasFactory;

    protected $fillable = [
        'marca',
        'tipo_vehiculo',
        'modelo',
        'año',
        'proveedor_id',
        'cliente_id',
        'es_propio',
        'patente',
    ];

    protected $casts = [
        'es_propio' => 'boolean',
        'año' => 'integer',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function turnosRodados(): HasMany
    {
        return $this->hasMany(TurnoRodado::class);
    }

    public function cambiosEquipos(): HasMany
    {
        return $this->hasMany(CambioEquipoRodado::class);
    }

    public function registrosKilometraje(): HasMany
    {
        return $this->hasMany(RegistroKilometraje::class);
    }

    public function kilometrajeActual(): HasOne
    {
        return $this->hasOne(RegistroKilometraje::class)->latestOfMany('fecha_registro');
    }

    public function pagosServicios(): HasMany
    {
        return $this->hasMany(PagoServiciosRodado::class);
    }
}
