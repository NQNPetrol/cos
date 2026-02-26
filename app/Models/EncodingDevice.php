<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EncodingDevice extends Model
{
    protected $fillable = [
        'encode_dev_index_code',
        'name',
        'ip',
        'port',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
        'port' => 'integer',
    ];

    public function cameras()
    {
        return $this->hasMany(Camera::class, 'encode_dev_index_code', 'encode_dev_index_code');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            1 => 'Activo',
            2 => 'Inactivo',
            default => 'Desconocido'
        };
    }

    public function getFullAddressAttribute()
    {
        return $this->ip.($this->port ? ':'.$this->port : '');
    }
}
