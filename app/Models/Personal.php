<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;

    protected $table = 'personal';
    
    protected $fillable = [
        'nombre',
        'apellido',
        'documento',
        'cliente_id',
        'cargo',
        'puesto',
        'convenio',
        'fecha_inicio',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
