<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SistemaController extends Controller
{
    public function index()
    {
        return view('admin.sistema');
    }

    public function crear_permiso()
    {
        return view('sistema.permisos');
    }

    public function asignar_permisos(Request $request)
    {
        $roleId = $request->query('role');

        return view('sistema.permisos', ['selectedRole' => $roleId]);
    }
}
