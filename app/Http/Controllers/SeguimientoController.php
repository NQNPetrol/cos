<?php

namespace App\Http\Controllers;

use App\Models\Evento;
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
}
