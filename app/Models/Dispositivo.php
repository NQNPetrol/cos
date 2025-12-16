<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Dispositivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'categoria',
        'tipo',
        'modelo',
        'direccion_ip',
        'puerto',
        'numero_serie',
        'version_software',
        'direccion_ipv6',
        'estado_hikconnect',
        'cliente_id',
        'ubicacion',
        'observaciones',
        'necesita_mantenimiento',
        'necesita_actualizacion',
        'fecha_instalacion',
        'ultimo_mantenimiento',
        'proximo_mantenimiento',
        'estado_inventario',
    ];
    
    protected $casts = [
        'necesita_actualizacion' => 'boolean',
        'necesita_mantenimiento' => 'boolean',
        'fecha_instalacion' => 'date',
        'ultimo_mantenimiento' => 'date',
        'proximo_mantenimiento' => 'date',
    ];

    protected $attributes = [
        'estado_hikconnect' => 'Conectado',
        'estado_inventario' => 'En stock',
        'necesita_mantenimiento' => false,
        'necesita_actualizacion' => false,
    ];

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function patrullas()
    {
        return $this->belongsToMany(Patrulla::class)
                    ->using(DispositivoPatrulla::class)
                    ->withPivot('fecha_asignacion')
                    ->withTimestamps();
    }

    public function cambiosEquipos()
    {
        return $this->hasMany(CambioEquipoRodado::class);
    }

    public function camera()
    {
        return $this->hasOne(Camera::class);
    }

    // Scopes
    
    public function scopeInstalados($query)
    {
        return $query->where('estado_inventario', 'Instalado');
    }

    public function scopeEnStock($query)
    {
        return $query->where('estado_inventario', 'En stock');
    }

    public function scopeNecesitaMantenimiento($query)
    {
        return $query->where('necesita_mantenimiento', true);
    }

    public function scopeNecesitaActualizacion($query)
    {
        return $query->where('necesita_actualizacion', true);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    public function scopePorCliente($query, $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }

    public function scopeConectadosHik($query)
    {
        return $query->where('estado_hikconnect', 'Conectado');
    }

    // Accessors

    public function getEstadoInventarioBadgeAttribute()
    {
        $badges = [
            'En stock' => 'bg-blue-100 text-blue-800',
            'Instalado' => 'bg-green-100 text-green-800',
            'En mantenimiento' => 'bg-yellow-100 text-yellow-800',
            'Dado de baja' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->estado_inventario] ?? 'bg-gray-100 text-gray-800';
    }

    public function getDiasProximoMantenimientoAttribute()
    {
        if (!$this->proximo_mantenimiento) {
            return null;
        }

        return Carbon::now()->diffInDays($this->proximo_mantenimiento, false);
    }

    public function estaVencidoMantenimiento()
    {
        return $this->proximo_mantenimiento && Carbon::now()->gt($this->proximo_mantenimiento);
    }

    public function estaProximoMantenimiento($dias = 30)
    {
        return $this->proximo_mantenimiento && 
               Carbon::now()->addDays($dias)->gte($this->proximo_mantenimiento) &&
               Carbon::now()->lte($this->proximo_mantenimiento);
    }

    /**
     * Obtener coordenadas desde el campo ubicacion
     */
    public function getCoordenadasAttribute()
    {
        if (!$this->ubicacion) {
            return null;
        }
        
        // Intentar parsear como JSON primero
        $json = json_decode($this->ubicacion, true);
        if (json_last_error() === JSON_ERROR_NONE && isset($json['lat']) && isset($json['lng'])) {
            $lat = (float) $json['lat'];
            $lng = (float) $json['lng'];
            
            // Validar rango de coordenadas
            if ($lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180) {
                return [
                    'lat' => $lat,
                    'lng' => $lng
                ];
            }
        }
        
        // Buscar patrón de coordenadas: -XX.XXXX, -XX.XXXX
        $pattern = '/(-?\d+\.?\d*)\s*,\s*(-?\d+\.?\d*)/';
        if (preg_match($pattern, $this->ubicacion, $matches)) {
            $lat = (float) trim($matches[1]);
            $lng = (float) trim($matches[2]);
            
            // Validar rango de coordenadas
            if ($lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180) {
                return [
                    'lat' => $lat,
                    'lng' => $lng
                ];
            }
        }
        
        return null;
    }

    /**
     * Verificar si el dispositivo tiene coordenadas válidas
     */
    public function tieneCoordenadas()
    {
        return $this->coordenadas !== null;
    }
}
