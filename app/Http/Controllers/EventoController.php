<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Cliente;
use App\Models\Personal;
use App\Models\Categoria;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

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
            'media.*'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048' //2MB max
        ]);

        [$lat, $lng] = array_map('trim', explode(',', $validated['coordenadas']));
        
        $evento = Evento::create([
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

        if ($request->hasFile('media')){

            //crear si no existe
            Storage::makeDirectory('public/eventos/media');

            foreach ($request->file('media') as $file) {
                $path = $file->store('public/eventos/media');

                Media::create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => Storage::url($path),
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

        return view('eventos.edit', [
            'evento' => $evento,
            'clientes' => $clientes,
            'supervisores' => $supervisores,
            'categorias' => $categorias
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
            'regex:/^-?\d{1,3}\.\d+,\s*-?\d{1,3}\.\d+$/'
        ],
        'observaciones' => 'nullable|string',
        'url_reporte' => 'nullable|url',
        'media.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    [$lat, $lng] = array_map('trim', explode(',', $validated['coordenadas']));

    $evento->update([
        'fecha_hora' => $validated['fecha_hora'],
        'cliente_id' => $validated['cliente_id'],
        'supervisor_id' => $validated['supervisor_id'],
        'categoria_id' => $validated['categoria_id'],
        'tipo' => $validated['tipo'],
        'longitud' => $lng,
        'latitud' => $lat,
        'observaciones' => $validated['observaciones'],
        'url_reporte' => $validated['url_reporte']
    ]);

    // Manejo de archivos
    if ($request->hasFile('media')) {
        Storage::makeDirectory('public/eventos/media');
        
        foreach ($request->file('media') as $file) {
            $path = $file->store('public/eventos/media');

            Media::create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => Storage::url($path),
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize() ?? 0,
                'model_id' => $evento->id,
                'model_type' => Evento::class,
            ]);
        }
    }

    return redirect()->route('eventos.index')->with('success', 'Evento actualizado correctamente.');
    
    }


    public function destroy(Evento $evento)
    {
        foreach($evento->media as $media) {
            $filePath = str_replace('/storage', 'public', $media->file_path);
            Storage::delete($filePath);

            $media->delete();
        }

        $evento->delete();

        return redirect()->route('eventos.index')->with('success', 'Evento eliminado correctamente');
    }
}
