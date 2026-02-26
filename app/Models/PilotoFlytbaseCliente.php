<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PilotoFlytbaseCliente extends Model
{
    use HasFactory;

    protected $table = 'piloto_flytbase_cliente';

    protected $fillable = [
        'piloto_flytbase_id',
        'cliente_id',
    ];

    /**
     * Relación con el piloto
     */
    public function piloto()
    {
        return $this->belongsTo(PilotoFlytbase::class, 'piloto_flytbase_id');
    }

    /**
     * Relación con el cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
