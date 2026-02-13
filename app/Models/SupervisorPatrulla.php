<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupervisorPatrulla extends Model
{
    protected $table = 'supervisor_patrulla';

    protected $fillable = [
        'supervisor_id',
        'patrulla_id',
        'user_id',
    ];

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'supervisor_id');
    }

    public function patrulla(): BelongsTo
    {
        return $this->belongsTo(Patrulla::class, 'patrulla_id');
    }

    public function asignadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeForSupervisor($query, $personalId)
    {
        return $query->where('supervisor_id', $personalId);
    }
}
