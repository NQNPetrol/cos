<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistroKilometraje extends Model
{
    use HasFactory;

    protected $table = 'registros_kilometraje';

    protected $fillable = [
        'rodado_id',
        'kilometraje',
        'fecha_registro',
        'observaciones',
    ];

    protected $casts = [
        'kilometraje' => 'integer',
        'fecha_registro' => 'date',
    ];

    public function rodado(): BelongsTo
    {
        return $this->belongsTo(Rodado::class);
    }
}
