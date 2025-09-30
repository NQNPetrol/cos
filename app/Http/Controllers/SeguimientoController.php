<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Seguimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha' => 'required|date',
            'estado' => 'required|in:ABIERTO,EN REVISION,CERRADO',
            'evento_id' => 'required|exists:eventos,id'
        ]);


        return redirect()->route('seguimientos.index');
    }

    /**
     * Obtener los IDs de clientes asociados al usuario autenticado
     */
    private function getClienteIds()
    {
        $user = Auth::user();
        
        if (!$user) {
            return collect();
        }

        return $user->clientes()->pluck('clientes.id');
    }

    /**
     * Verificar si el usuario tiene acceso a un cliente específico
     */
    private function userHasAccessToCliente($clienteId)
    {
        $clienteIds = $this->getClienteIds();
        return $clienteIds->contains($clienteId);
    }

    /**
     * Verificar si el usuario tiene acceso a un evento específico
     */
    private function userHasAccessToEvento($eventoId)
    {
        $evento = Evento::find($eventoId);
        if (!$evento) {
            return false;
        }
        
        return $this->userHasAccessToCliente($evento->cliente_id);
    }

    /**
     * Redirigir con error si el usuario no tiene clientes asignados
     */
    private function redirectIfNoClientes()
    {
        $clienteIds = $this->getClienteIds();
        
        if ($clienteIds->isEmpty()) {
            return redirect()->back()->with('error', 'No tienes clientes asignados. Contacta al administrador.');
        }
        
        return null;
    }

    public function createClientLayout()
    {
        $redirect = $this->redirectIfNoClientes();
        if ($redirect) {
            return $redirect;
        }

        $clienteIds = $this->getClienteIds();

        $eventos = Evento::whereIn('cliente_id', $clienteIds)
            ->whereDoesntHave('seguimientos', function($query){
                $query->where('estado', 'CERRADO');
            })->get();

        return view('seguimientos.nuevo-client', [
            'eventos' => $eventos,
            'header' => 'Nuevo Seguimiento'
        ]);
    }

    public function indexClientLayout(Request $request)
    {
        $redirect = $this->redirectIfNoClientes();
        if ($redirect) {
            return $redirect;
        }

        $clienteIds = $this->getClienteIds();

        $query = Seguimiento::with(['evento', 'user'])
            ->whereHas('evento', function($q) use ($clienteIds) {
                $q->whereIn('cliente_id', $clienteIds);
            })
            ->latest();
        
        if ($request->has('estado') && in_array($request->estado, ['ABIERTO', 'EN REVISION', 'CERRADO'])){
            $query->where('estado', $request->estado);
        }

        if ($request->has('evento_id')) {
            if ($this->userHasAccessToEvento($request->evento_id)) {
                $query->where('evento_id', $request->evento_id);
            } else {
                return redirect()->back()->with('error', 'No tienes acceso a este evento.');
            }
        }

        if ($request->has('busqueda')) {
            $query->where(function($q) use ($request){
                $q->where('titulo', 'like', '%'.$request->busqueda.'%')
                ->orWhere('descripcion', 'like', '%'.$request->busqueda.'%');
            });
        }

        $seguimientos = $query->paginate(10);
        $eventos = Evento::whereIn('cliente_id', $clienteIds)->get();

        return view('seguimientos.client.index', [
            'seguimientos' => $seguimientos,
            'eventos' => $eventos,
            'filtros' => $request->only(['estado', 'evento_id', 'busqueda'])
        ]);
    }
    public function storeClientLayout(Request $request)
    {
        $redirect = $this->redirectIfNoClientes();
        if ($redirect) {
            return $redirect;
        }

        // Verificar que el usuario tenga acceso al evento seleccionado
        if (!$this->userHasAccessToEvento($request->evento_id)) {
            return redirect()->back()->with('error', 'No tienes acceso a este evento.');
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha' => 'required|date',
            'estado' => 'required|in:ABIERTO,EN REVISION,CERRADO',
            'evento_id' => 'required|exists:eventos,id'
        ]);


        return redirect()->route('seguimientos.index-client');
    }

}
