<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Cliente;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::with(['roles', 'clientes'])->get();
        return view('usuarios.manage-users', compact('usuarios'));
    }
    public function roles()
    {
        $usuarios = User::with('roles')->get();
        $roles = Role::all();
        return view('usuarios.index', compact('usuarios', 'roles'));
    }

    public function asignarRol(Request $request, User $user)
    {
        $request->validate(['rol' => 'required|exists:roles,name']);
        $user->syncRoles([$request->rol]);

        return redirect()->route('usuarios.admin-roles')->with('success', 'Rol asignado correctamente.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')->with('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')->with('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            return redirect()->route('usuarios.index')->with('success', 'Contraseña reseteada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')->with('error', 'Error al resetear la contraseña: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            // Evitar que el admin se elimine a sí mismo
            if (auth()->id() === $user->id) {
                return redirect()->route('usuarios.index')->with('error', 'No puedes eliminar tu propio usuario.');
            }

            $user->delete();
            return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

}
