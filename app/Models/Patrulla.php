<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patrulla extends Model
{
    protected $fillable = ['patente', 'marca', 'modelo', 'color', 'estado', 'observaciones', 'cliente_id', 'año'];

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

    public function registrosFlota()
    {
        return $this->hasMany(PatrullaRegistroFlota::class);
    }

    public function ultimoRegistroFlota()
    {
        return $this->hasOne(PatrullaRegistroFlota::class)->latestOfMany();
    }

    public function getUltimoObjetivoServicioAttribute()
    {
        $ultimoRegistro = $this->registrosFlota()->latest('fecha_registro')->first();

        return $ultimoRegistro->objetivo_servicio ?? 'N/A';
    }

    public function getUltimaObservacionAttribute()
    {
        $ultimoRegistro = $this->registrosFlota()->latest('fecha_registro')->first();

        return $ultimoRegistro->observacion ?? 'N/A';
    }

    public function sistemas()
    {
        return $this->belongsToMany(Sistema::class, 'patrulla_sistemas')
            ->withPivot('fecha_registro', 'nro_interno', 'registra_user')
            ->withTimestamps();
    }

    public function documentacion()
    {
        return $this->hasMany(PatrullaDocumental::class);
    }

    public function checklists()
    {
        return $this->hasMany(ChecklistPatrulla::class);
    }

    public function ultimoChecklist()
    {
        return $this->hasOne(ChecklistPatrulla::class)->latestOfMany();
    }

    public function supervisorPatrulla()
    {
        return $this->hasOne(SupervisorPatrulla::class, 'patrulla_id');
    }

    public function scopeDisponibles($query)
    {
        return $query->whereDoesntHave('supervisorPatrulla');
    }

    public function scopeDelCliente($query, $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }
}
