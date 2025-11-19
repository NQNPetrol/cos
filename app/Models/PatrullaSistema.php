<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatrullaSistema extends Model
{
    protected $table = 'patrulla_sistemas';

    protected $fillable = [
        'patrulla_id',
        'sistema_id',
        'fecha_registro',
        'nro_interno',
        'registra_user'
    ];

    protected $casts = [
        'fecha_registro' => 'date',
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
}
