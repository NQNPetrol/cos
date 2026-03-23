<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use HasRoles;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function alertLogs(): HasMany
    {
        return $this->hasMany(AlertLog::class);
    }

    /**
     * Tickets creados por este usuario
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Tickets asignados a este usuario
     */
    public function assignedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'asignado_a');
    }

    /**
     * Clientes asociados a este usuario
     */
    public function clientes(): BelongsToMany
    {
        return $this->belongsToMany(Cliente::class, 'users_has_cliente_id', 'user_id', 'cliente_id');
    }

    public function userClientes()
    {
        return $this->hasMany(UserCliente::class);
    }

    public function getClientePrincipalAttribute()
    {
        return $this->clientes()->first();
    }

    public function getLogoClienteAttribute()
    {
        $cliente = $this->cliente_principal;

        if ($cliente && $cliente->logo) {
            return $cliente->logo_url;
        }

        return asset('cyh.png');
    }

    public function getNombreClienteAttribute()
    {
        $cliente = $this->cliente_principal;

        return $cliente ? $cliente->nombre : 'Centro de Operaciones';
    }

    /**
     * URLs permitidas para redirect post-login (evita open redirects).
     */
    public function isIntendedUrlAllowed(string $url): bool
    {
        if ($url === '' || str_starts_with($url, '//')) {
            return false;
        }

        if (str_starts_with($url, '/')) {
            return true;
        }

        $appUrl = rtrim((string) config('app.url'), '/');

        return $url === $appUrl || str_starts_with($url, $appUrl.'/');
    }

    /**
     * Verifica si el usuario es del COS (staff interno)
     */
    public function esCOS(): bool
    {
        return $this->clientes()->where('cliente_id', 2)->exists();
    }

    /**
     * Verifica si el usuario pertenece a un cliente específico
     */
    public function perteneceACliente(int $clienteId): bool
    {
        return $this->clientes()->where('cliente_id', $clienteId)->exists();
    }

    /**
     * Get the user's initials.
     *
     * @return string
     */
    public function initials()
    {
        $nameParts = explode(' ', trim($this->name));
        $initials = '';

        foreach ($nameParts as $part) {
            if (! empty($part)) {
                $initials .= strtoupper(substr($part, 0, 1));
            }
        }

        return $initials ?: strtoupper(substr($this->email, 0, 1)); // Fallback to first letter of email
    }

    public function reportesGenerados()
    {
        return $this->hasMany(ReporteGenerado::class);
    }

    /**
     * Registro de personal asociado al usuario (para supervisores)
     */
    public function personal()
    {
        return $this->hasOne(Personal::class, 'user_id');
    }
}
