<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Cliente;
use App\Models\Personal;
use App\Models\Categoria;

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
        
        $eventos = Evento::with('creador','cliente')->latest()->paginate(10);
        return view('eventos.index', compact('eventos'));
    }

    public function create()
    {
        $clientes = \App\Models\Cliente::all();
        $supervisores = Personal::where('cargo', 'supervisor')->get();
        $categorias = Categoria::all();
        return view('eventos.nuevo', compact('clientes', 'supervisores','categorias'));
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'fecha_hora'    => 'required|date',
            'cliente_id'    => 'required|exists:clientes,id',
            'supervisor_id' => 'required|exists:personal,id',
            'categoria_id'  => 'required|exists:categorias,id',
            'tipo'          => 'required|string|max:255',
            'coordenadas'   => [
                'required',
                'regex:/^-?\d{1,3}\.\d+,\s*-?\d{1,3}\.\d+$/'
            ],
            'observaciones' => 'nullable|string',
            'url_reporte'   => 'nullable|url',
        ]);
        [$lat, $lng] = array_map('trim', explode(',', $validated['coordenadas']));
        
         Evento::create([
            'fecha_hora'    => $validated['fecha_hora'],
            'cliente_id'    => $validated['cliente_id'] ?? null,
            'supervisor_id' => $validated['supervisor_id'] ?? null,
            'categoria_id'  => $validated['categoria_id'] ?? null,
            'tipo'          => $validated['tipo'],
            'longitud'      => $lng,
            'latitud'       => $lat,
            'observaciones' => $validated['observaciones'] ?? null,
            'url_reporte'   => $validated['url_reporte'] ?? null,
            'user_id'       => auth()->id(),
        ]);


        return redirect()->route('eventos.index')->with('success', 'Evento creado correctamente.');
    }
}
