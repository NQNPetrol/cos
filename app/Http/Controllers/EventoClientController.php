<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Cliente;
use App\Models\UserCliente;
use App\Models\Personal;
use App\Models\PersonasEventos;
use App\Models\Categoria;
use App\Models\Media;
use App\Models\EmpresaAsociada;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventoClientController extends Controller
{
    const TIPOS_CON_ELEMENTOS = [
        'Robo o intento de robo',
        'Sabotaje o vandalismo',
        'Daños a instalaciones o equipos',
        'Hallazgo de objetos sospechosos'
    ];

    private function getClienteIds()
    {
        $user = Auth::user();
        if (!$user) {
            return collect();
        }

        //obetener el id de los clientes asignados a user
        return $user->clientes()->pluck('clientes.id');
    }

    private function userHasAccessToCliente($clienteId)
    {
        $clienteIds = $this->getClienteIds();
        return $clienteIds->contains($clienteId);
    }

    private function redirectIfNoClientes()
    {
        $clienteIds = $this->getClienteIds();
        
        if ($clienteIds->isEmpty()) {
            return redirect()->back()->with('error', 'No tienes clientes asignados. Contacta al administrador.');
        }
        
        return null;
    }

    public function anular(Request $request, Evento $evento)
    {
        if (!$this->userHasAccessToCliente($evento->cliente_id)) {
            return redirect()->route('client.eventos.index')->with('error', 'No tienes acceso a este evento.');
        }

        // Verificar que el evento no esté ya anulado
        if ($evento->es_anulado) {
            return redirect()->route('client.eventos.index')->with('error', 'Este evento ya ha sido anulado.');
        }

        // Actualizar los campos de anulación
        $evento->update([
            'es_anulado' => true,
            'anulado_por' => Auth::user()->name,
            'fecha_anulado' => now()
        ]);

        return redirect()->route('client.eventos.index')->with('success', 'Evento anulado correctamente.');
    }

    public function index(Request $request)
    {
        $redirect = $this->redirectIfNoClientes();
        if ($redirect) {
            return $redirect;
        }

        $clienteIds = $this->getClienteIds();

        $query = Evento::with(['creador', 'cliente', 'categoria'])
            ->whereIn('cliente_id', $clienteIds)
            ->where('es_anulado', false)
            ->latest('fecha_hora');

        if ($request->filled('cliente_id')) {
            if ($this->userHasAccessToCliente($request->cliente_id)) {
                $query->where('cliente_id', $request->cliente_id);
            } else {
                return redirect()->back()->with('error', 'No tienes acceso a este cliente.');
            }
        }
        if ($request->filled('fecha_desde')){
            $query->whereDate('fecha_hora', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')){
            $query->whereDate('fecha_hora', '<=', $request->fecha_hasta);
        }
        
        $eventos = $query->paginate(10)->appends($request->query());

        $clientes = Cliente::whereIn('id', $clienteIds)->orderBy('nombre')->get();

        $empresas = collect();

        return view('eventos.client.index-client', compact(['eventos', 'clientes', 'empresas']));
    }

    public function create()
    {
        $user = Auth::user();
        \Log::info('Usuario actual:', ['user_id' => $user->id, 'user_name' => $user->name]);

        $redirect = $this->redirectIfNoClientes();
        if ($redirect) {
            return $redirect;
        }

        $clienteIds = $this->getClienteIds();
        \Log::info('Clientes asignados al usuario:', $clienteIds->toArray());

        $clientes = Cliente::whereIn('id', $clienteIds)->with(['empresasAsociadas'])->get();
        \Log::info('Clientes encontrados:', $clientes->pluck('id', 'nombre')->toArray());

        $supervisores = Personal::whereIn('cliente_id', $clienteIds)
            ->where('cargo', 'supervisor')
            ->get();

        $categorias = Categoria::all();

        $empresas = EmpresaAsociada::whereHas('cliente', function($query) use ($clienteIds) {
            $query->whereIn('clientes.id', $clienteIds);
        })->get();

        return view('eventos.client.nuevo-client', compact(['clientes', 'supervisores','categorias', 'empresas']));
    }

    public function store(Request $request)
    {
        $redirect = $this->redirectIfNoClientes();
        if ($redirect) {
            return $redirect;
        }

        if (!$this->userHasAccessToCliente($request->cliente_id)) {
            return redirect()->back()->with('error', 'No tienes acceso a este cliente.');
        }

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
            'descripcion' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'url_reporte'   => 'nullable|url',
            'media.*'       => 'nullable|image|mimes:jpeg,png|max:2048', //2MB max
            'empresa_asociada_id'=> 'nullable|exists:empresas_asociadas,id',
            'elementos' => 'nullable|array',
            'elementos.*' => 'nullable|string|max:255',
            'cantidades' => 'nullable|array',
            'cantidades.*' => 'nullable|integer|min:1'
        ]);

        $personaValidated = $request->validate([
            'personas_tipo' => 'nullable|array',
            'personas_tipo.*' => 'nullable|string|in:afectado/victima,sospechoso',
            'personas_nombre' => 'nullable|array',
            'personas_nombre.*' => 'nullable|string|max:255',
            'personas_tipo_doc' => 'nullable|array',
            'personas_tipo_doc.*' => 'nullable|string|max:50',
            'personas_nro_doc' => 'nullable|array',
            'personas_nro_doc.*' => 'nullable|integer',
            'personas_nro_telefono' => 'nullable|array',
            'personas_nro_telefono.*' => 'nullable|string|max:20',
            'personas_relacion_evento' => 'nullable|array',
            'personas_relacion_evento.*' => 'nullable|string|max:500',
            'personas_descripcion_fisica' => 'nullable|array',
            'personas_descripcion_fisica.*' => 'nullable|string|max:500',
            'personas_comportamiento_observado' => 'nullable|array',
            'personas_comportamiento_observado.*' => 'nullable|string|max:500',
        ]);

        [$lat, $lng] = array_map('trim', explode(',', $validated['coordenadas']));
        $validated['latitud'] = $lat;
        $validated['longitud'] = $lng;

        $validated['user_id'] = auth()->id();

        // Procesar elementos sustraídos si el tipo es "Robo o intento de robo"
        if (in_array($request->tipo, self::TIPOS_CON_ELEMENTOS) && $request->has('elementos')) {
            $elementos = array_filter($request->elementos);
            $cantidades = array_filter($request->cantidades);
            
            // Asegurarse de que ambos arrays tengan la misma longitud
            $validated['elementos_sustraidos'] = array_values($elementos);
            $validated['cantidad'] = array_slice(array_values($cantidades), 0, count($elementos));
        } else {
            $validated['elementos_sustraidos'] = null;
            $validated['cantidad'] = null;
        }
        
        $evento = Evento::create($validated);

        // Procesar personas involucradas
        if ($request->has('personas_tipo')) {
            foreach ($request->personas_tipo as $index => $tipo) {
                $personaData = [
                    'tipo' => $tipo,
                    'evento_id' => $evento->id,
                ];

                if ($tipo === 'afectado/victima') {
                    $personaData['nombre'] = $request->personas_nombre[$index] ?? null;
                    $personaData['tipo_doc'] = $request->personas_tipo_doc[$index] ?? null;
                    $personaData['nro_doc'] = $request->personas_nro_doc[$index] ?? null;
                    $personaData['nro_telefono'] = $request->personas_nro_telefono[$index] ?? null;
                    $personaData['relacion_evento'] = $request->personas_relacion_evento[$index] ?? null;
                } elseif ($tipo === 'sospechoso') {
                    $personaData['descripcion_fisica'] = $request->personas_descripcion_fisica[$index] ?? null;
                    $personaData['comportamiento_observado'] = $request->personas_comportamiento_observado[$index] ?? null;
                }

                PersonasEventos::create($personaData);
            }
        }

        $empresas = EmpresaAsociada::all();

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('media/eventos', 'public');

                Media::create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'model_id' => $evento->id,
                    'model_type' => Evento::class,
                ]);
            }
        }


        return redirect()->route('client.eventos.index')->with('success', 'Evento creado correctamente.');
    }

    public function edit(Evento $evento)
    {
        if (!$this->userHasAccessToCliente($evento->cliente_id)) {
            return redirect()->route('client.eventos.index')->with('error', 'No tienes acceso a este evento.');
        }

        $clienteIds = $this->getClienteIds();

        $clientes = Cliente::whereIn('id', $clienteIds)->get();

        $supervisores = Personal::whereIn('cliente_id', $clienteIds)
            ->where('cargo', 'supervisor')
            ->get();

        $categorias = Categoria::all();

        $empresas = $evento->cliente ? $evento->cliente->empresasAsociadas : collect();

        return view('eventos.client.edit-client', [
            'evento' => $evento,
            'clientes' => $clientes,
            'supervisores' => $supervisores,
            'categorias' => $categorias,
            'empresas' => $empresas
        ]);
    }

    public function update(Request $request, Evento $evento)
    {
        if (!$this->userHasAccessToCliente($evento->cliente_id)) {
            return redirect()->route('client.eventos.index')->with('error', 'No tienes acceso a este evento.');
        }

        // Verificar que el usuario tenga acceso al nuevo cliente si se cambió
        if (!$this->userHasAccessToCliente($request->cliente_id)) {
            return redirect()->back()->with('error', 'No tienes acceso a este cliente.');
        }

        $validated = $request->validate([
        'fecha_hora' => 'required|date',
        'cliente_id' => 'required|exists:clientes,id',
        'supervisor_id' => 'required|exists:personal,id',
        'categoria_id' => 'required|exists:categorias,id',
        'tipo' => 'required|string|max:255',
        'coordenadas' => [
            'required',
            'regex:/^-?\d{1,3}\.\d+,\s*-?\d{1,3}\.\d+$/'
        ],
        'descripcion' => 'nullable|string',
        'observaciones' => 'nullable|string',
        'url_reporte' => 'nullable|url',
        'media.*' => 'nullable|image|mimes:jpeg,png|max:2048',
        'empresa_asociada_id'=> 'nullable|exists:empresas_asociadas,id',
        'elementos' => 'nullable|array',
        'elementos.*' => 'nullable|string|max:255',
        'cantidades' => 'nullable|array',
        'cantidades.*' => 'nullable|integer|min:1',
    ]);

    $personaValidated = $request->validate([
        'personas_tipo' => 'nullable|array',
        'personas_tipo.*' => 'nullable|string|in:afectado/victima,sospechoso',
        'personas_nombre' => 'nullable|array',
        'personas_nombre.*' => 'nullable|string|max:255',
        'personas_tipo_doc' => 'nullable|array',
        'personas_tipo_doc.*' => 'nullable|string|max:50',
        'personas_nro_doc' => 'nullable|array',
        'personas_nro_doc.*' => 'nullable|integer',
        'personas_nro_telefono' => 'nullable|array',
        'personas_nro_telefono.*' => 'nullable|string|max:20',
        'personas_relacion_evento' => 'nullable|array',
        'personas_relacion_evento.*' => 'nullable|string|max:500',
        'personas_descripcion_fisica' => 'nullable|array',
        'personas_descripcion_fisica.*' => 'nullable|string|max:500',
        'personas_comportamiento_observado' => 'nullable|array',
        'personas_comportamiento_observado.*' => 'nullable|string|max:500',
    ]);

    [$lat, $lng] = array_map('trim', explode(',', $validated['coordenadas']));
    $validated['latitud'] = $lat;
    $validated['longitud'] = $lng;

    $validated['user_id'] = auth()->id();

    // Procesar elementos sustraídos si el tipo es "Robo o intento de robo"
    if (in_array($request->tipo, self::TIPOS_CON_ELEMENTOS) && $request->has('elementos')) {
        $elementos = array_filter($request->elementos);
        $cantidades = array_filter($request->cantidades);
        
        $validated['elementos_sustraidos'] = array_values($elementos);
        $validated['cantidad'] = array_slice(array_values($cantidades), 0, count($elementos));
    } else {
        $validated['elementos_sustraidos'] = null;
        $validated['cantidad'] = null;
    }

    $evento->update($validated);

    if ($request->has('personas_tipo')) {
        foreach ($request->personas_tipo as $index => $tipo) {
            $personaData = [
                'tipo' => $tipo,
                'evento_id' => $evento->id,
            ];

            if ($tipo === 'afectado/victima') {
                $personaData['nombre'] = $request->personas_nombre[$index] ?? null;
                $personaData['tipo_doc'] = $request->personas_tipo_doc[$index] ?? null;
                $personaData['nro_doc'] = $request->personas_nro_doc[$index] ?? null;
                $personaData['nro_telefono'] = $request->personas_nro_telefono[$index] ?? null;
                $personaData['relacion_evento'] = $request->personas_relacion_evento[$index] ?? null;
            } elseif ($tipo === 'sospechoso') {
                $personaData['descripcion_fisica'] = $request->personas_descripcion_fisica[$index] ?? null;
                $personaData['comportamiento_observado'] = $request->personas_comportamiento_observado[$index] ?? null;
            }

            PersonasEventos::create($personaData);
        }
    }

    // Manejo de archivos
    if ($request->hasFile('media')) {
        foreach ($request->file('media') as $file) {
                $path = $file->store('media/eventos', 'public');

                Media::create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'model_id' => $evento->id,
                    'model_type' => Evento::class,
                ]);
        }
    }

    return redirect()->route('client.eventos.index')->with('success', 'Evento actualizado correctamente.');
    
    }


    public function destroy(Evento $evento)
    {
        if (!$this->userHasAccessToCliente($evento->cliente_id)) {
            return redirect()->route('client.eventos.index')->with('error', 'No tienes acceso a este evento.');
        }

        $evento->personas()->delete();

        // Eliminar reportes generados asociados al evento primero
        foreach($evento->reportesGenerados as $reporte) {
            $reporte->delete();
        }

        
        foreach($evento->media as $media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
        }

        $evento->delete();

        return redirect()->route('client.eventos.index')->with('success', 'Evento eliminado correctamente');
    }

    public function destroyMedia(Media $media)
    {
        $evento = $media->model;
        if (!$evento instanceof Evento || !$this->userHasAccessToCliente($evento->cliente_id)) {
            return redirect()->back()->with('error', 'No tienes acceso a este recurso.');
        }

        Storage::disk('public')->delete($media->file_path);
        $media->delete();

        return back()->with('success', 'Imagen eliminada correctamente');
    }

    public function eventosBarras(Request $request)
    {
        $redirect = $this->redirectIfNoClientes();
        if ($redirect) {
            return response()->json([]);
        }

        $clienteIds = $this->getClienteIds();

        $query = Evento::query();

        // Filtrar por fecha, ?start_date=2025-08-01&end_date=2025-08-05
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('fecha_hora', [
                $request->input('start_date') . ' 00:00:00',
                $request->input('end_date')   . ' 23:59:59',
            ]);
        }

        $data = $query
            ->select(
                DB::raw("DATE(`fecha_hora`) as date"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($data);

    }

    public function agregarNotasAdicionales(Request $request, Evento $evento)
    {
        if (!$this->userHasAccessToCliente($evento->cliente_id)) {
            return redirect()->route('client.eventos.index')->with('error', 'No tienes acceso a este evento.');
        }

        $validated = $request->validate([
            'notas_adicionales' => 'required|string|max:1000'
        ]);

        $evento->update([
            'notas_adicionales' => $validated['notas_adicionales']
        ]);

        return redirect()->route('client.eventos.index')->with('success', 'Notas adicionales agregadas correctamente.');
    }
    
}
