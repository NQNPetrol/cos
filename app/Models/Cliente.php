<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EmpresaAsociada;
use App\Models\ClienteEmpresaAsociada;

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

    public function personal()
    {
        return $this->hasMany(Personal::class);
    }

    public function empresasAsociadas()
    {
        return $this->belongsToMany(EmpresaAsociada::class, 'cliente_empresa_asociada')
                    ->using(ClienteEmpresaAsociada::class);
    }

}
