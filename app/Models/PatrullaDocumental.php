<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatrullaDocumental extends Model
{
    protected $table = 'patrulla_documental';

    protected $fillable = [
        'patrulla_id',
        'nombre',
        'fecha_inicio',
        'fecha_vto',
        'detalles'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_vto' => 'date',
    ];

    public function patrulla()
    {
        return $this->belongsTo(Patrulla::class);
    }
}
