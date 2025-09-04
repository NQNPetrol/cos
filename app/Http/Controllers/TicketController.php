<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        return view('tickets.nuevo');
    }

    /**
     * Obtener tickets asignados al usuario actual
     */
    public function assignedToMe()
    {
        $tickets = Ticket::where('asignado_a', auth()->id())
            ->where('estado', '!=', 'cerrado')
            ->with(['user', 'cliente'])
            ->orderBy('prioridad', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($tickets);
    }

    /**
     * Buscar tickets (para admin)
     */
    public function search(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $query = Ticket::with(['user', 'assignedTo', 'cliente']);

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if ($request->has('estado') && $request->estado) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('categoria') && $request->categoria) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->has('prioridad') && $request->prioridad) {
            $query->where('prioridad', $request->prioridad);
        }

        if ($request->has('cliente_id') && $request->cliente_id) {
            $query->where('cliente_id', $request->cliente_id);
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($tickets);
    }
}
