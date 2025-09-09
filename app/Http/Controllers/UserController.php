<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Cliente;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::with('roles')->get();
        $roles = Role::all();
        return view('usuarios.index', compact('usuarios', 'roles'));
    }

    public function asignarRol(Request $request, User $user)
    {
        $request->validate(['rol' => 'required|exists:roles,name']);
        $user->syncRoles([$request->rol]);

        return redirect()->route('usuarios.index')->with('success', 'Rol asignado correctamente.');
    }

    /**
     * Vista para asignar clientes a usuarios
     */
    public function asignarClientes()
    {
        $usuarios = User::with(['clientes'])->get();
        $clientes = Cliente::all();
        return view('usuarios.asignar-clientes', compact('usuarios', 'clientes'));
    }

    /**
     * Procesar la asignación de clientes a un usuario
     */
    public function storeAsignacionClientes(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'clientes' => 'array',
            'clientes.*' => 'exists:clientes,id'
        ]);

        $user = User::findOrFail($request->user_id);
        $user->clientes()->sync($request->clientes ?? []);

        return redirect()
            ->route('usuarios.asignar-clientes')
            ->with('success', 'Clientes asignados correctamente al usuario.');
    }
}
