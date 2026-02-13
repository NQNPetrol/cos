<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Taller extends Model
{
    use HasFactory;

    protected $table = 'talleres';

    protected $fillable = [
        'nombre',
        'contacto',
        'telefono',
        'email',
        'direccion',
        'proveedor_id',
        'whatsapp',
    ];

    public function proveedor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function turnosRodados(): HasMany
    {
        return $this->hasMany(TurnoRodado::class);
    }

    public function cambiosEquipos(): HasMany
    {
        return $this->hasMany(CambioEquipoRodado::class);
    }

    /**
     * Generate WhatsApp link for this taller
     */
    public function getWhatsappLinkAttribute(): ?string
    {
        $number = $this->whatsapp ?? $this->telefono;
        if (! $number) {
            return null;
        }
        $cleaned = preg_replace('/[^0-9]/', '', $number);

        return 'https://wa.me/'.$cleaned;
    }

    /**
     * Generate mailto link for this taller
     */
    public function getMailtoLinkAttribute(): ?string
    {
        return $this->email ? 'mailto:'.$this->email : null;
    }
}
