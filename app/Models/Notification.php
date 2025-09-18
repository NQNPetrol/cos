<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'user_id',
        'client_id',
        'priority',
        'is_active',
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'client_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'notification_user')
            ->withPivot(['is_read', 'is_dismissed', 'read_at', 'dismissed_at'])
            ->withTimestamps();
    }

    //notificaciones activas
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    //notif globales
    public function scopeGlobal($query)
    {
        return $query->where('type', 'global');
    }

    //notif especificas p un usuario
    public function scopeForUser($query, $userId)
    {
        return $query->where('type', 'user')->where('user_id', $userId);
    }

    // especificas p un cliente
    public function scopeForClient($query, $clientId)
    {
        return $query->where('type', 'client')->where('client_id', $clientId);
    }

    // vsibilidad de notif
    public function isVisibleForUser(User $user): bool
    {
        if (!$this->is_active) {
            return false;
        }

        switch ($this->type) {
            case 'global':
                return true;
            case 'user':
                return $this->user_id === $user->id;
            case 'client':
                return $user->clientes()->where('cliente_id', $this->client_id)->exists();
            default:
                return false;
        }
    }

    //marcar la notif como leida
    public function markAsReadForUser(User $user): void
    {
        $this->users()->syncWithoutDetaching([
            $user->id => [
                'is_read' => true,
                'read_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function dismissForUser(User $user): void
    {
        $this->users()->syncWithoutDetaching([
            $user->id => [
                'is_dismissed' => true,
                'dismissed_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function isReadByUser(User $user): bool
    {
        $pivot = $this->users()->where('user_id', $user->id)->first();
        return $pivot ? $pivot->pivot->is_read : false;
    }

    public function isDismissedByUser(User $user): bool
    {
        $pivot = $this->users()->where('user_id', $user->id)->first();
        return $pivot ? $pivot->pivot->is_dismissed : false;
    }
}
