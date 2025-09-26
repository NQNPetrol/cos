<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Cliente;
use App\Models\UserCliente;
use App\Models\Personal;
use App\Models\Categoria;
use App\Models\Media;
use App\Models\EmpresaAsociada;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventoController extends Controller
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

    public function index(Request $request)
    {
        $redirect = $this->redirectIfNoClientes();
        if ($redirect) {
            return $redirect;
        }

        $clienteIds = $this->getClienteIds();

        $query = Evento::with(['creador', 'cliente', 'categoria'])->whereIn('cliente_id', $clienteIds)->latest('fecha_hora');

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

        return view('eventos.index-guest', compact(['eventos', 'clientes', 'empresas']));
    }

    public function create()
    {
        $redirect = $this->redirectIfNoClientes();
        if ($redirect) {
            return $redirect;
        }

        $clienteIds = $this->getClienteIds();

        $clientes = Cliente::whereIn('id', $clienteIds)->get();
        $supervisores = Personal::where('cargo', 'supervisor')->get();
        $categorias = Categoria::all();
        $empresas = collect();

        return view('eventos.nuevo-guest', compact(['clientes', 'supervisores','categorias', 'empresas']));
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
            'empresa_asociada_id'=> 'required|exists:empresas_asociadas,id',
            'elementos' => 'nullable|array',
            'elementos.*' => 'nullable|string|max:255',
            'cantidades' => 'nullable|array',
            'cantidades.*' => 'nullable|integer|min:1',
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


        return redirect()->route('eventos.index-guest')->with('success', 'Evento creado correctamente.');
    }

    public function edit(Evento $evento)
    {
        if (!$this->userHasAccessToCliente($evento->cliente_id)) {
            return redirect()->route('eventos.index-guest')->with('error', 'No tienes acceso a este evento.');
        }

        $clienteIds = $this->getClienteIds();

        $clientes = Cliente::whereIn('id', $clienteIds)->get();
        $supervisores = Personal::where('cargo', 'supervisor')->get();
        $categorias = Categoria::all();
        $empresas = $evento->cliente ? $evento->cliente->empresasAsociadas : collect();

        return view('eventos.edit-guest', [
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
            return redirect()->route('eventos.index-guest')->with('error', 'No tienes acceso a este evento.');
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
        'empresa_asociada_id'=> 'required|exists:empresas_asociadas,id',
        'elementos' => 'nullable|array',
        'elementos.*' => 'nullable|string|max:255',
        'cantidades' => 'nullable|array',
        'cantidades.*' => 'nullable|integer|min:1',
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

    return redirect()->route('eventos.index-guest')->with('success', 'Evento actualizado correctamente.');
    
    }


    public function destroy(Evento $evento)
    {
        if (!$this->userHasAccessToCliente($evento->cliente_id)) {
            return redirect()->route('eventos.index-guest')->with('error', 'No tienes acceso a este evento.');
        }

        // Eliminar reportes generados asociados al evento primero
        foreach($evento->reportesGenerados as $reporte) {
            $reporte->delete();
        }

        
        foreach($evento->media as $media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
        }

        $evento->delete();

        return redirect()->route('eventos.index-guest')->with('success', 'Evento eliminado correctamente');
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
    
}
