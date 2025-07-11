<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Cliente;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        $query = Evento::with('usuario')->latest();

        if ($request->has('cliente')){
            $query->where('cliente', 'like', '%'.$request->cliente.'%');
        }
        if ($request->has('fecha_desde')){
            $query->where('fecha', '>=', $request->fecha_desde);
        }
        if ($request->has('fecha_hasta')){
            $query->where('fecha', '<=', $request->fecha_hasta);
        }
        
        $eventos = Evento::with('user')->latest()->paginate(10);
        return view('eventos.index', compact('eventos'));
    }

    public function create()
    {
        $clientes = \App\Models\Cliente::all();
        $supervisores = \App\Models\User::whereHas('roles', function($query) {
        $query->where('name', 'supervisor');
    })->get();
        return view('eventos.nuevo', compact('clientes', 'supervisores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'observaciones' => 'required|string',
            'fecha_hora' => 'required|datetime',
            'longitud' => 'required|float',
            'latitud' => 'required|float'
        ]);

        

        return redirect()->route('eventos.index');
    }
}
