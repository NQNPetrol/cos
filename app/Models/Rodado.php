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

    // Constantes para marcas
    const MARCA_TOYOTA = 'Toyota';
    const MARCA_NISSAN = 'Nissan';
    const MARCA_FORD = 'Ford';
    const MARCA_VOLKSWAGEN = 'Volkswagen';
    const MARCA_CHEVROLET = 'Chevrolet';
    const MARCA_RENAULT = 'Renault';
    const MARCA_MERCEDES_BENZ = 'Mercedes Benz';
    const MARCA_SCANIA = 'Scania';
    const MARCA_JMC = 'JMC';
    const MARCA_IVECO = 'Iveco';
    const MARCA_VOLVO = 'Volvo';
    const MARCA_OTRO = 'Otro';

    public static function getMarcas(): array
    {
        return [
            self::MARCA_TOYOTA,
            self::MARCA_NISSAN,
            self::MARCA_FORD,
            self::MARCA_VOLKSWAGEN,
            self::MARCA_CHEVROLET,
            self::MARCA_RENAULT,
            self::MARCA_MERCEDES_BENZ,
            self::MARCA_SCANIA,
            self::MARCA_JMC,
            self::MARCA_IVECO,
            self::MARCA_VOLVO,
            self::MARCA_OTRO,
        ];
    }

    // Constantes para tipos de vehículo
    const TIPO_CAMIONETA = 'Camioneta';
    const TIPO_CAMIONETA_TODOTERRENO = 'Camioneta todoterreno';
    const TIPO_CAMION_REMOLQUE = 'Camion remolque';
    const TIPO_AUTO = 'Auto';
    const TIPO_MOTO = 'Moto';
    const TIPO_CAMION_SEMI_REMOLQUE = 'Camion semi remolque';
    const TIPO_CAMION_CARGA = 'Camion carga';
    const TIPO_CAMIONETA_TRANSPORTE_PASAJEROS = 'Camioneta de transporte de pasajeros';
    const TIPO_CAMIONETA_CARGA_COMERCIAL = 'Camioneta de carga comercial';
    const TIPO_CAMIONETA_PLATAFORMA = 'Camioneta de plataforma';
    const TIPO_OTRO = 'Otro';

    public static function getTiposVehiculo(): array
    {
        return [
            self::TIPO_CAMIONETA,
            self::TIPO_CAMIONETA_TODOTERRENO,
            self::TIPO_CAMION_REMOLQUE,
            self::TIPO_AUTO,
            self::TIPO_MOTO,
            self::TIPO_CAMION_SEMI_REMOLQUE,
            self::TIPO_CAMION_CARGA,
            self::TIPO_CAMIONETA_TRANSPORTE_PASAJEROS,
            self::TIPO_CAMIONETA_CARGA_COMERCIAL,
            self::TIPO_CAMIONETA_PLATAFORMA,
            self::TIPO_OTRO,
        ];
    }

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

    protected $appends = ['display_name'];

    public function getDisplayNameAttribute(): string
    {
        $parts = [];
        
        if ($this->patente) {
            $parts[] = $this->patente;
        }
        
        if ($this->cliente) {
            $parts[] = 'Cliente: ' . $this->cliente->nombre;
        }
        
        if ($this->proveedor) {
            $parts[] = 'Proveedor: ' . $this->proveedor->nombre;
        } else {
            $parts[] = 'Proveedor: -';
        }
        
        return implode(' - ', $parts);
    }

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
