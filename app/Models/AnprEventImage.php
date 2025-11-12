<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnprEventImage extends Model
{
    use HasFactory;

    protected $table = 'anpr_event_images';

    protected $fillable = [
        'anpr_record_id',
        'veh_pic_uri',
        'image_path',
        'image_base64',
        'mime_type',
        'file_size',
        'status',
        'error_message',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    /**
     * Relación con el registro ANPR
     */
    public function anprRecord(): BelongsTo
    {
        return $this->belongsTo(AnprRecord::class);
    }

    /**
     * Scope para imágenes pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para imágenes completadas
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Verificar si la imagen está disponible
     */
    public function getIsAvailableAttribute(): bool
    {
        return $this->status === 'completed' && !empty($this->image_base64);
    }

    /**
     * Obtener la URL de la imagen para el frontend
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->is_available) {
            return "data:{$this->mime_type};base64,{$this->image_base64}";
        }
        return null;
    }
}
