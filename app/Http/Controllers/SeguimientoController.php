<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Seguimiento;
use Illuminate\Http\Request;

class SeguimientoController extends Controller
{
    public function create()
    {
        $eventos = Evento::whereDoesntHave('seguimientos', function($query){
            $query->where('estado', 'CERRADO');
        })->get();

        return view('seguimientos.nuevo', [
            'eventos' => $eventos,
            'header' => 'Nuevo Seguimiento'
        ]);
    }

    public function index(Request $request)
    {
        $query = Seguimiento::with(['evento', 'user'])
            ->latest();
        
        if ($request->has('estado') && in_array($request->estado, ['ABIERTO', 'EN REVISION', 'CERRADO'])){
            $query->where('estado', $request->estado);
        }

        if ($request->has('evento_id')) {
            $query->where('evento_id', $request->evento_id);
        }

        if ($request->has('busqueda')) {
            $query->where(function($q) use ($request){
                $q->where('titulo', 'like', '%'.$request->busqueda.'%')
                ->orWhere('descripcion', 'like', '%'.$request->busqueda.'%');
            });
        }

        $seguimientos = $query->paginate(10);
        $eventos = Evento::all();

        return view('seguimientos.index', [
            'seguimientos' => $seguimientos,
            'eventos' => $eventos,
            'filtros' => $request->only(['estado', 'evento_id', 'busqueda'])
        ]);
    }
}
