<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\EmpresaAsociada;
use App\Models\Evento;
use App\Models\Media;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EventoController extends Controller
{
    const TIPOS_CON_ELEMENTOS = [
        'Robo o intento de robo',
        'Sabotaje o vandalismo',
        'Daños a instalaciones o equipos',
        'Hallazgo de objetos sospechosos',
    ];

    public function index(Request $request)
    {
        $query = Evento::with(['creador', 'cliente', 'categoria'])->latest('fecha_hora');

        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_hora', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_hora', '<=', $request->fecha_hasta);
        }

        // Filtro por evento anulado o vigente
        if ($request->filled('estado')) {
            if ($request->estado === 'ANULADO') {
                $query->where('es_anulado', true);
            } elseif ($request->estado === 'VIGENTE') {
                $query->where('es_anulado', false);
            }
        }

        // Paginación dinámica: si paginate=1 se activa, si no se muestran todos
        $isPaginated = $request->boolean('paginate', false);

        if ($isPaginated) {
            $eventos = $query->paginate(15)->appends($request->query());
        } else {
            // Sin paginación: obtener todos los registros pero usar LengthAwarePaginator para mantener compatibilidad
            $allEventos = $query->get();
            $eventos = new \Illuminate\Pagination\LengthAwarePaginator(
                $allEventos,
                $allEventos->count(),
                $allEventos->count() ?: 1,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        $clientes = Cliente::orderBy('nombre')->get();

        $empresas = collect();

        return view('eventos.index', compact(['eventos', 'clientes', 'empresas', 'isPaginated']));
    }

    public function create()
    {
        $clientes = \App\Models\Cliente::all();
        $supervisores = Personal::where('cargo', 'supervisor')->get();
        $categorias = Categoria::all();
        $empresas = collect();

        return view('eventos.nuevo', compact(['clientes', 'supervisores', 'categorias', 'empresas']));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'fecha_hora' => 'required|date',
            'cliente_id' => 'required|exists:clientes,id',
            'supervisor_id' => 'required|exists:personal,id',
            'categoria_id' => 'required|exists:categorias,id',
            'tipo' => 'required|string|max:255',
            'coordenadas' => [
                'required',
                'regex:/^-?\d{1,3}\.\d+,\s*-?\d{1,3}\.\d+$/',
            ],
            'descripcion' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'url_reporte' => 'nullable|url',
            'media.*' => 'nullable|image|mimes:jpeg,png|max:2048', // 2MB max
            'empresa_asociada_id' => 'nullable|exists:empresas_asociadas,id',
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

        return redirect()->route('eventos.index')->with('success', 'Evento creado correctamente.');
    }

    public function edit(Evento $evento)
    {
        $clientes = Cliente::all();
        $supervisores = Personal::where('cargo', 'supervisor')->get();
        $categorias = Categoria::all();
        $empresas = $evento->cliente ? $evento->cliente->empresasAsociadas : collect();
        $personas = $evento->personas;

        return view('eventos.edit', [
            'evento' => $evento,
            'clientes' => $clientes,
            'supervisores' => $supervisores,
            'categorias' => $categorias,
            'empresas' => $empresas,
            'personas' => $personas,
        ]);
    }

    public function update(Request $request, Evento $evento)
    {
        $validated = $request->validate([
            'fecha_hora' => 'required|date',
            'cliente_id' => 'required|exists:clientes,id',
            'supervisor_id' => 'required|exists:personal,id',
            'categoria_id' => 'required|exists:categorias,id',
            'tipo' => 'required|string|max:255',
            'coordenadas' => [
                'required',
                'regex:/^-?\d{1,3}\.\d+,\s*-?\d{1,3}\.\d+$/',
            ],
            'descripcion' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'url_reporte' => 'nullable|url',
            'media.*' => 'nullable|image|mimes:jpeg,png|max:2048',
            'empresa_asociada_id' => 'nullable|exists:empresas_asociadas,id',
            'elementos' => 'nullable|array',
            'elementos.*' => 'nullable|string|max:255',
            'cantidades' => 'nullable|array',
            'cantidades.*' => 'nullable|integer|min:1',
        ]);

        $personasValidated = $request->validate([
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

        $evento->personas()->delete();

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

                \App\Models\PersonasEventos::create($personaData);
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

        return redirect()->route('eventos.index')->with('success', 'Evento actualizado correctamente.');

    }

    public function destroy(Evento $evento)
    {
        $evento->personas()->delete();

        // Eliminar reportes generados asociados al evento primero
        foreach ($evento->reportesGenerados as $reporte) {
            $reporte->delete();
        }

        foreach ($evento->media as $media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
        }

        $evento->delete();

        return redirect()->route('eventos.index')->with('success', 'Evento eliminado correctamente');
    }

    public function destroyMedia(Media $media)
    {
        Storage::disk('public')->delete($media->file_path);
        $media->delete();

        return back()->with('success', 'Imagen eliminada correctamente');
    }

    public function eventosBarras(Request $request)
    {

        $query = Evento::query();

        // Filtrar por fecha, ?start_date=2025-08-01&end_date=2025-08-05
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('fecha_hora', [
                $request->input('start_date').' 00:00:00',
                $request->input('end_date').' 23:59:59',
            ]);
        }

        $data = $query
            ->select(
                DB::raw('DATE(`fecha_hora`) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($data);

    }
}
