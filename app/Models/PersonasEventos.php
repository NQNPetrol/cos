<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonasEventos extends Model
{
    protected $table = 'personas_en_evento';

    protected $fillable = [
        'evento_id',
        'nombre',
        'tipo_doc',
        'nro_doc',
        'nro_telefono',
        'relacion_evento',
        'descripcion_fisica',
        'comportamiento_observado',
        'tipo'
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }
}
