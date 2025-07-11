<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evento extends Model
{
    protected $fillable = ['fecha_hora', 'id_cliente', 'id_supervisor', 'longitud', 'latitud', 'observaciones', 'url_reporte', 'user_id'];
    public function seguimientos(): HasMany
    {
        return $this->hasMany(Seguimiento::class);
    }
}
