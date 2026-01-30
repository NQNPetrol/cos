<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactLead extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'empresa',
        'cargo',
        'mensaje',
        'ip_address',
        'user_agent',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Status labels
     *
     * @var array<string, string>
     */
    public static $statusLabels = [
        'nuevo' => 'Nuevo',
        'contactado' => 'Contactado',
        'demo_agendado' => 'Demo Agendado',
        'cerrado' => 'Cerrado',
    ];

    /**
     * Get the status label.
     *
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        return self::$statusLabels[$this->status] ?? $this->status;
    }

    /**
     * Scope for new leads.
     */
    public function scopeNuevos($query)
    {
        return $query->where('status', 'nuevo');
    }

    /**
     * Scope for contacted leads.
     */
    public function scopeContactados($query)
    {
        return $query->where('status', 'contactado');
    }

    /**
     * Mark as contacted.
     */
    public function markAsContactado(): bool
    {
        return $this->update(['status' => 'contactado']);
    }

    /**
     * Mark as demo scheduled.
     */
    public function markAsDemoAgendado(): bool
    {
        return $this->update(['status' => 'demo_agendado']);
    }

    /**
     * Mark as closed.
     */
    public function markAsCerrado(): bool
    {
        return $this->update(['status' => 'cerrado']);
    }
}
