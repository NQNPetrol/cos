<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class ReporteGenerado extends Model
{
    use HasFactory;

    protected $table = 'reportes_generados';

    protected $fillable = [
        'evento_id',
        'user_id',
        'nombre_archivo',
        'ruta_archivo'
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePorUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopePorEvento($query, $eventoId)
    {
        return $query->where('evento_id', $eventoId);
    }

    public function getUrlAttribute()
    {
        return Storage::disk('public')->url($this->ruta_archivo);
    }

    public function archivoExiste()
    {
        return Storage::disk('public')->exists($this->ruta_archivo);
    }

}
