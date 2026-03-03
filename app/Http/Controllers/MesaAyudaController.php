<?php

namespace App\Http\Controllers;

class MesaAyudaController extends Controller
{
    /**
     * Muestra la mesa de ayuda / chat asistente para el usuario cliente.
     */
    public function index()
    {
        return view('client.mesa-ayuda.index');
    }
}
