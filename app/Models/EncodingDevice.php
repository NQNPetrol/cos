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
}
