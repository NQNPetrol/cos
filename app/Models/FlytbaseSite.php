<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FlytbaseSite extends Model
{
    use HasFactory;

    protected $table = 'flytbase_sites';

    protected $fillable = [
        'nombre',
        'descripcion',
        'cliente_id',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function docks(): HasMany
    {
        return $this->hasMany(FlytbaseDock::class, 'flytbase_site_id');
    }

    public function misiones(): HasMany
    {
        return $this->hasMany(MisionFlytbase::class, 'site_id');
    }

    public function peticionesMisiones(): HasMany
    {
        return $this->hasMany(PeticionMisionFlytbase::class, 'site_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorCliente($query, $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }
}
