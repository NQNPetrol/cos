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
        'cargo',
        'puesto',
        'convenio',
        'fecha_inicio',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
