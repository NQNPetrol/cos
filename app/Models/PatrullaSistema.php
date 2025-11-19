<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PatrullaSistema extends Model
{
    protected $table = 'patrulla_sistemas';

    protected $fillable = [
        'patrulla_id',
        'sistema_id',
        'fecha_registro',
        'fecha_vto',
        'nro_interno',
        'registra_user'
    ];

    protected $casts = [
        'fecha_registro' => 'date',
        'fecha_vto' => 'date'
    ];

    public function patrulla()
    {
        return $this->belongsTo(Patrulla::class);
    }

    public function sistema()
    {
        return $this->belongsTo(Sistema::class);
    }

    public function usuarioRegistra()
    {
        return $this->belongsTo(User::class, 'registra_user');
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
