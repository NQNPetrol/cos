<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Personal extends Model
{
    use HasFactory;

    protected $table = 'personal';

    protected $fillable = [
        'nombre',
        'apellido',
        'cliente_id',
        'cargo',
        'puesto',
        'convenio',
        'fecha_ing',
        'tipo_doc',
        'nro_doc',
        'telefono',
        'legajo',
        'user_id',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function supervisorPatrulla(): HasOne
    {
        return $this->hasOne(SupervisorPatrulla::class, 'supervisor_id');
    }

    public function empresasAsociadas(): BelongsToMany
    {
        return $this->belongsToMany(
            EmpresaAsociada::class,
            'supervisor_empresas_asociadas',
            'supervisor_id',
            'empresa_asociada_id'
        )->withPivot('user_id')->withTimestamps();
    }

    public function scopeSinUsuarioAsignado($query)
    {
        return $query->whereNull('user_id');
    }

    public function scopeDelCliente($query, $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }
}
