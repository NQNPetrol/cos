<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StreamUrl extends Model
{
    use HasFactory;

    protected $table = 'camera_stream_urls';

    protected $fillable = [
        'camera_index_code',
        'url',
        'authentication',
        'protocol',
        'stream_type',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_updated' => 'datetime'
    ];

    public function camera()
    {
        return $this->belongsTo(Camera::class, 'camera_index_code', 'camera_index_code');
    }
}
