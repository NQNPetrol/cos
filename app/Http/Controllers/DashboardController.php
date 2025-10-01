<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        \Log::info('=== Iniciando ===');
        $user = $request->user();

        if (!$user) {
            \Log::warning('No hay usuario autenticado');
            return redirect()->route('login');
        }

        \Log::info('Usuario logueado:', [
        'id' => $user->id,
        'name' => $user->name,
        'roles' => $user->getRoleNames()->toArray(),
        'hasRole cliente' => $user->hasRole('cliente'),
        'isEmpty' => $user->roles->isEmpty()
    ]);
        
        // Redirigir según el rol del usuario
        if ($user->hasRole('cliente') || $user->roles->isEmpty()) {
            \Log::info('Redirigiendo a CLIENT DASHBOARD');
            return redirect()->route('client.dashboard');
        }
        
        // Para admin, operador, otros
         \Log::info('Redirigiendo a DASHBOARD NORMAL');
        return redirect()->route('main.dashboard');
    }
}
