<?php

namespace App\Policies;

use App\Models\Recorrido;
use App\Models\User;

class RecorridoPolicy
{
    /**
     * Determine if the user can view the recorrido.
     */
    public function view(User $user, Recorrido $recorrido): bool
    {
        return $user->clientes->contains('id', $recorrido->cliente_id);
    }

    /**
     * Determine if the user can update the recorrido.
     */
    public function update(User $user, Recorrido $recorrido): bool
    {
        return $user->hasPermissionTo('editar.recorridos-cliente') &&
               $user->clientes->contains('id', $recorrido->cliente_id);
    }

    /**
     * Determine if the user can delete the recorrido.
     */
    public function delete(User $user, Recorrido $recorrido): bool
    {
        return $user->hasPermissionTo('eliminar.recorridos-cliente') &&
               $user->clientes->contains('id', $recorrido->cliente_id);
    }
}
