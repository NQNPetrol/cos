<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PilotoFlytbase extends Model
{
    use HasFactory;

    protected $table = 'pilotos_flytbase';

    protected $fillable = [
        'nombre',
        'token',
        'user_id',
    ];

    /**
     * Usuario asociado al piloto
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Clientes asignados al piloto
     */
    public function clientes(): BelongsToMany
    {
        return $this->belongsToMany(Cliente::class, 'piloto_flytbase_cliente', 'piloto_flytbase_id', 'cliente_id')
            ->withTimestamps();
    }
}
