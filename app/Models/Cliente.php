<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EmpresaAsociada;
use App\Models\ClienteEmpresaAsociada;
use App\Models\Contrato;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $fillable = [
        'nombre',
        'cuit',
        'domicilio',
        'ciudad',
        'provincia',
        'categoria',
        'convenio'
    ];

    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }

    public function personal()
    {
        return $this->hasMany(Personal::class);
    }

    public function empresasAsociadas()
    {
        return $this->belongsToMany(EmpresaAsociada::class, 'cliente_empresa_asociada')
                    ->using(ClienteEmpresaAsociada::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Usuarios asociados a este cliente
     */
    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_has_cliente_id', 'cliente_id', 'user_id');
    }

    /**
     * Verifica si este es el cliente COS 
     */
    public function esCOS(): bool
    {
        return $this->id === 2;
    }

}
