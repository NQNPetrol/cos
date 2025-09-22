<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MobileVehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'mobile_vehicle_index_code',
        'mobile_vehicle_name',
        'status',
        'dev_index_code',
        'region_index_code',
        'plate_no',
        'person_family_name',
        'person_given_name',
        'person_name',
        'phone_no',
        'vehicle_type',
        'vehicle_brand',
        'vehicle_color',
        'patrulla_id'
    ];

    protected $casts = [
        'status' => 'integer',
        'vehicle_type' => 'integer',
        'vehicle_brand' => 'integer',
        'vehicle_color' => 'integer',
    ];

    /**
     * Relación con la tabla patrullas
     */
    public function patrulla()
    {
        return $this->belongsTo(Patrulla::class);
    }

    /**
     * Scope para vehículos activos
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope para vehículos con patrulla asignada
     */
    public function scopeWithPatrulla($query)
    {
        return $query->whereNotNull('patrulla_id');
    }

    /**
     * Accessor para obtener el nombre del estado
     */
    public function getStatusNameAttribute()
    {
        return match($this->status) {
            1 => 'Activo',
            2 => 'Inactivo',
            default => 'Desconocido'
        };
    }

    /**
     * Método para buscar patrulla por patente
     */
    public static function findPatrullaByPlate(string $plateNo)
    {
        return Patrulla::where('patente', $plateNo)->first();
    }
}
