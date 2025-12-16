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
        'descripcion',
        'observaciones',
        'url_reporte',
        'user_id',
        'categoria_id',
        'tipo',
        'empresa_asociada_id',
        'elementos_sustraidos',
        'cantidad',
        'es_anulado',
        'anulado_por',
        'fecha_anulado',
        'notas_adicionales'
    ];

        protected $casts = [
        'fecha_hora' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'elementos_sustraidos' => 'array',
        'cantidad' => 'array',
        'fecha_anulado' => 'datetime',
        'es_anulado' => 'boolean',
    ];


    public function seguimientos()
    {
        return $this->hasMany(Seguimiento::class);
    }

    public function personas()
    {
        return $this->hasMany(PersonasEventos::class, 'evento_id');
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

    /**
     * Obtener el último seguimiento del evento
     */
    public function ultimoSeguimiento()
    {
        return $this->hasOne(Seguimiento::class, 'evento_id')
                    ->latestOfMany('fecha');
    }

    /**
     * Obtener el estado actual del evento basado en el último seguimiento
     */
    public function getEstadoActualAttribute()
    {
        $ultimo = $this->ultimoSeguimiento;
        return $ultimo ? $ultimo->estado : 'ABIERTO';
    }
}
