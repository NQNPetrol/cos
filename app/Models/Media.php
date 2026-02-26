<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'model_id',
        'model_type',
    ];

    public function model()
    {
        return $this->morphTo();
    }
}
