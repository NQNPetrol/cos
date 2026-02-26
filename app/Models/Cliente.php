<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'cuit',
        'domicilio',
        'ciudad',
        'provincia',
        'categoria',
        'convenio',
        'logo',
    ];

    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/'.$this->logo);
        }

        return asset('public/cyh.png');
    }

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

    public function patrullas()
    {
        return $this->hasMany(Patrulla::class);
    }

    public function pilotosFlytbase(): BelongsToMany
    {
        return $this->belongsToMany(PilotoFlytbase::class, 'piloto_flytbase_cliente', 'cliente_id', 'piloto_flytbase_id')
            ->withTimestamps();
    }

    /**
     * Verifica si este es el cliente COS
     */
    public function esCOS(): bool
    {
        return $this->id === 2;
    }
}
