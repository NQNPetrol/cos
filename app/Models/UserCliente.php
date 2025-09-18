<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCliente extends Model
{
    use hasFactory;

    protected $table = 'users_has_cliente_id';

    protected $fillable = [
        'user_id',
        'cliente_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForCliente($query, $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }
    public static function exists($userId, $clienteId)
    {
        return self::where('user_id', $userId)
                   ->where('cliente_id', $clienteId)
                   ->exists();
    }
}
