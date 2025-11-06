<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnprPassingRecord extends Model
{
    use HasFactory;

    protected $primaryKey = 'cross_record_syscode';
    public $incrementing = false;
    protected $keyType = 'string';

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
}
