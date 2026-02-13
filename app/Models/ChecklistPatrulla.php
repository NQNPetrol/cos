<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistPatrulla extends Model
{
    use HasFactory;

    protected $table = 'checklist_patrullas';

    protected $fillable = [
        'patrulla_id',
        'user_id',
        'fecha',
        'ruedas_auxilio',
        'antena_starlink',
        'camaras_dvr',
        'parabrisas',
        'luces',
        'balizas',
        'antivuelco',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'ruedas_auxilio' => 'integer',
        'antena_starlink' => 'boolean',
        'camaras_dvr' => 'boolean',
        'parabrisas' => 'boolean',
        'luces' => 'boolean',
        'balizas' => 'boolean',
        'antivuelco' => 'boolean',
    ];

    public function patrulla(): BelongsTo
    {
        return $this->belongsTo(Patrulla::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
