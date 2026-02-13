<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObjetivoAipem extends Model
{
    protected $table = 'objetivos_aipem';

    protected $fillable = [
        'codobj',
        'nombre',
        'fecha_alta',
        'fecha_baja',
        'codcli',
        'codsup',
        'calle',
        'nro',
        'piso',
        'dpto',
        'localidad',
        'pcia',
        'codpostal',
        'codzona',
        'pais',
        'coordmaps',
        'telefono',
        'email',
        'valid_ini',
        'valid_fin',
        'pto_descrip',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }

    public function empresaAsociada()
    {
        return $this->belongsTo(EmpresaAsociada::class, 'empresa_asociada_id');
    }
}
