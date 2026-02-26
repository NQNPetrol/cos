<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FlytbaseDrone extends Model
{
    use HasFactory;

    protected $table = 'drones_flytbase';

    protected $fillable = [
        'drone',
        'share_url',
        'dock_id',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación con las misiones
     */
    public function misiones(): HasMany
    {
        return $this->hasMany(MisionFlytbase::class, 'drone_id');
    }

    public function dock(): BelongsTo
    {
        return $this->belongsTo(FlytbaseDock::class, 'dock_id');
    }

    /**
     * Scope para drones activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Obtener la ruta de la vista del liveview
     */
    public function getLiveviewRouteAttribute(): string
    {
        return "streaming.{$this->drone}.liveview";
    }

    /**
     * Obtener la ruta de la vista blade
     */
    public function getLiveviewViewPathAttribute(): string
    {
        return "livestreaming.{$this->drone}.liveview";
    }

    /**
     * Verificar si el drone tiene una vista de liveview
     */
    public function hasLiveviewView(): bool
    {
        return view()->exists($this->liveview_view_path);
    }
}
