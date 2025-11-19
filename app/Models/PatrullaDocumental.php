<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function getInfoDiasAttribute()
    {
        if (!$this->fecha_vto) {
            return null;
        }

        $hoy = Carbon::today();
        $fechaVto = Carbon::parse($this->fecha_vto);
        
        if ($fechaVto->isPast()) {
            $dias = $fechaVto->diffInDays($hoy);
            return "(vencido hace {$dias} días)";
        } else {
            $dias = $hoy->diffInDays($fechaVto);
            return "({$dias} días restantes)";
        }
    }

    public function getEstaVencidoAttribute()
    {
        if (!$this->fecha_vto) {
            return false;
        }
        
        return Carbon::parse($this->fecha_vto)->isPast();
    }
}
