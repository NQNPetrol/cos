<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        \Log::info('=== Iniciando ===');
        $user = $request->user();

        if (! $user) {
            \Log::warning('No hay usuario autenticado');

            return redirect()->route('login');
        }

        \Log::info('Usuario logueado:', [
            'id' => $user->id,
            'name' => $user->name,
            'roles' => $user->getRoleNames()->toArray(),
            'hasRole cliente' => $user->hasRole('cliente'),
            'isEmpty' => $user->roles->isEmpty(),
        ]);

        // Si el usuario no tiene roles asignados, redirigir a página de sin acceso
        if ($user->roles->isEmpty()) {
            \Log::info('Usuario sin rol asignado - Redirigiendo a NO ACCESS');

            return redirect()->route('no-access');
        }

        // Redirigir según el rol del usuario
        if ($user->hasAnyRole(['cliente', 'clientadmin', 'clientsupervisor'])) {
            \Log::info('Redirigiendo a CLIENT DASHBOARD');

            return redirect()->route('client.dashboard');
        }

        // Rol administrative → dashboard administrativo
        if ($user->hasRole('administrative')) {
            \Log::info('Redirigiendo a ADMINISTRATIVE DASHBOARD');

            return redirect()->route('rodados.admin-dashboard');
        }

        // Para admin, operador, otros
        \Log::info('Redirigiendo a DASHBOARD NORMAL');

        return redirect()->route('main.dashboard');
    }
}
