<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

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
}
