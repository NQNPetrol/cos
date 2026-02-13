<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FlytbaseDock extends Model
{
    use HasFactory;

    protected $table = 'flytbase_docks';

    protected $fillable = [
        'nombre',
        'descripcion',
        'flytbase_site_id',
        'latitud',
        'longitud',
        'altitude',
        'active',
    ];

    protected $casts = [
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
        'altitude' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(FlytbaseSite::class, 'flytbase_site_id');
    }

    public function misiones(): HasMany
    {
        return $this->hasMany(MisionFlytbase::class, 'dock_id');
    }

    public function peticionesMisiones(): HasMany
    {
        return $this->hasMany(PeticionMisionFlytbase::class, 'dock_id');
    }

    public function drones(): HasMany
    {
        return $this->hasMany(FlytbaseDrone::class, 'dock_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('active', true);
    }

    public function getCoordenadasAttribute(): array
    {
        return [
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'altitude' => $this->altitude,
        ];
    }
}
