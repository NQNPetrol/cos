<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'contacto',
        'telefono',
        'email',
    ];

    public function rodados(): HasMany
    {
        return $this->hasMany(Rodado::class);
    }

    public function talleres(): HasMany
    {
        return $this->hasMany(Taller::class);
    }

    public function pagosServicios(): HasMany
    {
        return $this->hasMany(PagoServiciosRodado::class);
    }
}
