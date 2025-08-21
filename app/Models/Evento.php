<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Evento extends Model
{
        protected $fillable = [
        'fecha_hora',
        'cliente_id',
        'supervisor_id',
        'longitud',
        'latitud',
        'observaciones',
        'url_reporte',
        'user_id',
        'categoria_id',
        'tipo',
        'empresa_asociada_id',
    ];

        protected $casts = [
        'fecha_hora' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];


    public function seguimientos()
    {
        return $this->hasMany(Seguimiento::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'id_supervisor');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function getUbicacionAttribute()
    {
        if ($this->latitud && $this->longitud) {
            return "https://www.google.com/maps/search/?api=1&query={$this->latitud},{$this->longitud}";
        }

        return null;
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function empresaAsociada()
    {
        return $this->belongsTo(EmpresaAsociada::class, 'empresa_asociada_id');
    }

    public function reportesGenerados()
    {
        return $this->hasMany(ReporteGenerado::class);
    }
}
