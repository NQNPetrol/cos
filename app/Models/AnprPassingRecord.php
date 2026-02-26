<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnprPassingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'cross_record_syscode',
        'camera_index_code',
        'plate_no',
        'owner_name',
        'contact',
        'vehicle_pic_uri',
        'cross_time',
        'vehicle_color',
        'vehicle_type',
        'country',
        'vehicle_direction_type',
        'vehicle_brand',
        'vehicle_speed',
    ];

    protected $casts = [
        'cross_time' => 'datetime',
        'vehicle_color' => 'integer',
        'vehicle_type' => 'integer',
        'country' => 'integer',
        'vehicle_direction_type' => 'integer',
        'vehicle_brand' => 'integer',
        'vehicle_speed' => 'integer',
    ];

    public function eventImage()
    {
        return $this->hasOne(AnprEventImage::class, 'anpr_record_id');
    }

    public static function boot()
    {
        parent::boot();

        // Opcionalmente agregar índice único en la migración
    }
}
