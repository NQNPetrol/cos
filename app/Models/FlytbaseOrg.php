<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FlytbaseOrg extends Model
{
    use HasFactory;

    protected $table = 'flytbase_organizations';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function sites(): HasMany
    {
        return $this->hasMany(FlytbaseSite::class, 'organization_id');
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorNombre($query, $nombre)
    {
        return $query->where('nombre', 'like', '%'.$nombre.'%');
    }

    public function getCantidadSitiosActivosAttribute(): int
    {
        return $this->sites()->activos()->count();
    }

    public function getCantidadTotalSitiosAttribute(): int
    {
        return $this->sites()->count();
    }

    public function getCantidadDispositivosAttribute(): int
    {
        return $this->sites->sum('cantidad_dispositivos');
    }

    public function getCantidadMiembrosAttribute(): int
    {
        return $this->sites->sum('cantidad_miembros');
    }

    public function activar(): bool
    {
        return $this->update(['activo' => true]);
    }

    public function desactivar(): bool
    {
        return $this->update(['activo' => false]);
    }

    public function tieneSitios(): bool
    {
        return $this->sites()->exists();
    }
}
