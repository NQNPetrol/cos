<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
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
        return $query->where('estado_hikconnect', 'conectado');
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

    // Métodos de utilidad
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
}
