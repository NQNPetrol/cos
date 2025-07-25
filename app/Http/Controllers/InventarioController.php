<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dispositivo;
use App\Models\Cliente;

class InventarioController extends Controller
{
    
    public function index()
    {
        return view('inventario.index');
    }

    public function create()
    {
        return view('inventario.create');
    }

    /**
     * Exportar dispositivos a CSV
     */
    public function exportar()
    {
        $dispositivos = Dispositivo::with('cliente')->get();
        
        $filename = 'dispositivos_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($dispositivos) {
            $file = fopen('php://output', 'w');
            
            // Encabezados
            fputcsv($file, [
                'ID', 'Tipo', 'Estado', 'IP', 'Puerto', 'Serial', 'MAC', 'Cliente', 
                'Ubicación', 'Estado Inventario', 'Versión SW', 'Fecha Instalación',
                'Necesita Mantenimiento', 'Necesita Actualización', 'Observaciones'
            ]);

            // Datos
            foreach ($dispositivos as $dispositivo) {
                fputcsv($file, [
                    $dispositivo->id,
                    $dispositivo->device_type,
                    $dispositivo->status,
                    $dispositivo->ipv4_address,
                    $dispositivo->port,
                    $dispositivo->device_serial_number,
                    $dispositivo->mac_address,
                    $dispositivo->cliente->nombre ?? 'Sin asignar',
                    $dispositivo->ubicacion,
                    $dispositivo->estado_inventario,
                    $dispositivo->software_version,
                    $dispositivo->fecha_instalacion ? $dispositivo->fecha_instalacion->format('Y-m-d') : '',
                    $dispositivo->necesita_mantenimiento ? 'Sí' : 'No',
                    $dispositivo->necesita_actualizacion ? 'Sí' : 'No',
                    $dispositivo->observaciones,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generar reporte de mantenimientos
     */
    public function reporteMantenimientos()
    {
        $vencidos = Dispositivo::where('proximo_mantenimiento', '<', now())
            ->with('cliente')
            ->get();

        $proximos = Dispositivo::where('proximo_mantenimiento', '>=', now())
            ->where('proximo_mantenimiento', '<=', now()->addDays(30))
            ->with('cliente')
            ->orderBy('proximo_mantenimiento')
            ->get();

        $pendientes = Dispositivo::where('necesita_mantenimiento', true)
            ->with('cliente')
            ->get();

        return view('dispositivos.reporte-mantenimientos', compact('vencidos', 'proximos', 'pendientes'));
    }

    /**
     * Generar reporte de actualizaciones
     */
    public function reporteActualizaciones()
    {
        $necesitan_actualizacion = Dispositivo::where('necesita_actualizacion', true)
            ->with('cliente')
            ->get();

        $versiones = Dispositivo::select('version_software')
            ->selectRaw('count(*) as total')
            ->whereNotNull('software_version')
            ->groupBy('version_software')
            ->orderBy('total', 'desc')
            ->get();

        return view('dispositivos.reporte-actualizaciones', compact('necesitan_actualizacion', 'versiones'));
    }

    /**
     * Marcar mantenimiento como realizado
     */
    public function completarMantenimiento(Request $request, Dispositivo $dispositivo)
    {
        $request->validate([
            'fecha_mantenimiento' => 'required|date',
            'proximo_mantenimiento' => 'nullable|date|after:fecha_mantenimiento',
            'observaciones' => 'nullable|string',
        ]);

        $dispositivo->update([
            'ultimo_mantenimiento' => $request->fecha_mantenimiento,
            'proximo_mantenimiento' => $request->proximo_mantenimiento,
            'necesita_mantenimiento' => false,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->back()->with('success', 'Mantenimiento registrado correctamente.');
    }

    /**
     * Marcar actualización como realizada
     */
    public function completarActualizacion(Request $request, Dispositivo $dispositivo)
    {
        $request->validate([
            'nueva_version' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        $dispositivo->update([
            'version_software' => $request->nueva_version,
            'necesita_actualizacion' => false,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->back()->with('success', 'Actualización registrada correctamente.');
    }

    public function store()
    {
        $validated = $request->validate([
            'nombre' => 'nullable|string|max:255',
            'categoria' => 'required|string|max:255',
            'tipo' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'direccion_ip' => 'nullable|string|max:255',
            'puerto' => 'nullable|string|max:255',
            'version_software' => 'nullable|string',
            'direccion_ipv6' => 'nullable|string|max:255',
            'estado_hikconnect' => 'nullable|string|max:255',
            'cliente_id' =>'required|exists:clientes,id',
            'ubicacion' => 'nullable|string|max:255',
            'observaciones'=> 'nullable|string|max:255',
            'necesita_mantenimiento',
            'necesita_actualizacion',
            'fecha_instalacion',
            'ultimo_mantenimiento',
            'proximo_mantenimiento',
            'estado_inventario'
        ]);

        Personal::create($validated);

        return redirect()->route('inventario.store')
            ->with('success', 'Personal creado correctamente.');
    }
}