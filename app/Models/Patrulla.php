<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patrulla extends Model
{
    protected $fillable = ['patente', 'marca', 'modelo', 'color', 'estado', 'observaciones', 'cliente_id'];
    
    public function dispositivos()
    {
        return $this->belongsToMany(Dispositivo::class)
                    ->using(DispositivoPatrulla::class)
                    ->withPivot('fecha_asignacion')
                    ->withTimestamps();
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function mobileVehicle()
    {
        return $this->hasOne(MobileVehicle::class);
    }

    public function scopeWithMobileVehicle($query)
    {
        return $query->whereHas('mobileVehicle');
    }

    public function hasMobileVehicle()
    {
        return $this->mobileVehicle()->exists();
    }

    public function getMobileVehicleIndexCode()
    {
        return $this->mobileVehicle?->mobile_vehicle_index_code;
    }
}
