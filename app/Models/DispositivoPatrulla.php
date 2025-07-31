<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DispositivoPatrulla extends Pivot
{
    protected $table = 'dispositivo_patrulla';

    protected $fillable = [
        'patrulla_id',
        'dispositivo_id',
        'fecha_asignacion'
    ];

    protected $casts = [
        'fecha_asignacion' => 'date'
    ];

    //Relaciones
    public function patrulla()
    {
        return $this->belongsTo(Patrulla::class);
    }

    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class);
    }

}
