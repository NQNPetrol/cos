<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Taller extends Model
{
    use HasFactory;

    protected $table = 'talleres';

    protected $fillable = [
        'nombre',
        'contacto',
        'telefono',
        'email',
        'direccion',
    ];

    public function turnosRodados(): HasMany
    {
        return $this->hasMany(TurnoRodado::class);
    }

    public function cambiosEquipos(): HasMany
    {
        return $this->hasMany(CambioEquipoRodado::class);
    }
}
