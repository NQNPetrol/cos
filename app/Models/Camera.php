<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Camera extends Model
{
    use HasFactory;

    protected $fillable = [
        'camera_index_code',
        'camera_name',
        'capability_set',
        'dev_resource_type',
        'encode_dev_index_code',
        'record_type',
        'record_location',
        'region_index_code',
        'site_index_code',
        'status',
        'is_support_wake_up',
        'wake_up_status'
    ];

    protected $casts = [
        'status' => 'integer',
        'is_support_wake_up' => 'boolean',
        'wake_up_status' => 'integer'
    ];

    /**
     * Relación con EncodingDevice
     */
    public function encodingDevice()
    {
        return $this->belongsTo(EncodingDevice::class, 'encode_dev_index_code', 'encode_dev_index_code');
    }

    /**
     * Scope para cámaras activas
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope para cámaras por tipo de dispositivo
     */
    public function scopeByDeviceType($query, $type = 'encodeDevice')
    {
        return $query->where('dev_resource_type', $type);
    }

    public function stream()
    {
        return $this->hasOne(StreamUrl::class, 'camera_index_code', 'camera_index_code');
    }
}
