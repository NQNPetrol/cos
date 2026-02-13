<?php

namespace App\Policies;

use App\Models\RecorridoTimetable;
use App\Models\User;

class RecorridoTimetablePolicy
{
    /**
     * Determine if the user can update the registro.
     */
    public function update(User $user, RecorridoTimetable $registro): bool
    {
        return $user->hasPermissionTo('editar.recorridos-cliente') &&
               $user->clientes->contains('id', $registro->recorrido->cliente_id);
    }

    /**
     * Determine if the user can delete the registro.
     * Only clientadmin of the same client can delete.
     */
    public function delete(User $user, RecorridoTimetable $registro): bool
    {
        return $user->hasPermissionTo('eliminar.historial-recorridos-cliente') &&
               $user->hasRole('clientadmin') &&
               $user->clientes->contains('id', $registro->recorrido->cliente_id);
    }
}
