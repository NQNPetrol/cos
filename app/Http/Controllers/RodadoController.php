<?php

namespace App\Http\Controllers;

use App\Models\CambioEquipoRodado;
use App\Models\Cliente;
use App\Models\PagoServiciosRodado;
use App\Models\Patrulla;
use App\Models\Proveedor;
use App\Models\Rodado;
use App\Models\Taller;
use App\Models\TurnoRodado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RodadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Rodado::with(['cliente', 'proveedor', 'kilometrajeActual']);

        // Filtro por propiedad (proveedor o propio)
        if ($request->filled('propiedad')) {
            if ($request->propiedad === 'propio') {
                $query->where('es_propio', true)
                    ->whereNull('proveedor_id');
            } elseif ($request->propiedad !== 'todos') {
                // Filtrar por proveedor específico
                $query->where('proveedor_id', $request->propiedad);
            }
        }

        $rodados = $query->latest()->get();

        $clientes = Cliente::orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('nombre')->get();
        $talleres = Taller::orderBy('nombre')->get();

        // Dispositivos para el modal de cambio de equipo (camara_ip, nvr_dvr, antena_starlink)
        $dispositivos = \App\Models\Dispositivo::whereIn('tipo', ['camara_ip', 'nvr_dvr', 'antena_starlink'])
            ->where('estado_inventario', 'En stock')
            ->orderBy('nombre')
            ->get();

        // Obtener datos para las pestañas
        $turnos = TurnoRodado::with(['rodado', 'taller'])
            ->latest('fecha_hora')
            ->get();

        $cambiosEquipos = CambioEquipoRodado::with(['rodado', 'taller'])
            ->latest('fecha_hora_estimada')
            ->get();

        // Combinar turnos y cambios de equipos para la vista de servicios
        $todosLosServicios = collect();

        // Agregar turnos
        foreach ($turnos as $turno) {
            $todosLosServicios->push([
                'id' => $turno->id,
                'tipo' => $turno->tipo,
                'tipo_servicio' => 'turno',
                'rodado' => $turno->rodado,
                'taller' => $turno->taller,
                'fecha_hora' => $turno->fecha_hora,
                'estado' => $turno->estado,
                'cubre_servicio' => $turno->cubre_servicio ?? false,
                'fecha_vencimiento_pago' => $turno->fecha_vencimiento_pago,
                'model' => $turno,
            ]);
        }

        // Agregar cambios de equipos como si fueran turnos de tipo cambio_equipo
        foreach ($cambiosEquipos as $cambio) {
            $todosLosServicios->push([
                'id' => $cambio->id,
                'tipo' => 'cambio_equipo',
                'tipo_servicio' => 'cambio_equipo',
                'rodado' => $cambio->rodado,
                'taller' => $cambio->taller,
                'fecha_hora' => $cambio->fecha_hora_estimada,
                'estado' => 'completado', // Los cambios de equipos se consideran completados
                'cubre_servicio' => false,
                'fecha_vencimiento_pago' => null,
                'model' => $cambio,
            ]);
        }

        // Ordenar por fecha (más reciente primero)
        $todosLosServicios = $todosLosServicios->sortByDesc(function ($item) {
            return $item['fecha_hora'];
        })->values();

        $pagos = PagoServiciosRodado::with(['rodado', 'proveedor'])
            ->latest('fecha_pago')
            ->get();

        // Cross-reference patrullas with rodados to get auxilios data
        $auxiliosMap = [];
        $rodadoPatentes = $rodados->pluck('patente')->filter()->toArray();
        if (! empty($rodadoPatentes)) {
            $patrullasConChecklist = Patrulla::whereIn('patente', $rodadoPatentes)
                ->with('ultimoChecklist')
                ->get();

            foreach ($patrullasConChecklist as $patrulla) {
                if ($patrulla->ultimoChecklist) {
                    $auxiliosMap[$patrulla->patente] = $patrulla->ultimoChecklist->ruedas_auxilio;
                }
            }
        }

        return view('rodados.index', compact(
            'rodados',
            'clientes',
            'proveedores',
            'talleres',
            'turnos',
            'cambiosEquipos',
            'pagos',
            'todosLosServicios',
            'dispositivos',
            'auxiliosMap'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'marca' => 'required|string|max:255',
            'tipo_vehiculo' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'año' => 'required|integer|min:1900|max:'.(date('Y') + 1),
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'cliente_id' => 'required|exists:clientes,id',
            'es_propio' => 'nullable|boolean',
            'patente' => 'nullable|string|max:20',
            'imagen_frente' => 'nullable|image|max:5120',
            'imagen_costado_izq' => 'nullable|image|max:5120',
            'imagen_costado_der' => 'nullable|image|max:5120',
            'imagen_dorso' => 'nullable|image|max:5120',
        ]);

        // Asegurar que es_propio sea boolean
        $validated['es_propio'] = $request->has('es_propio') ? (bool) $request->es_propio : true;

        // Remove image files from validated (handled separately)
        unset($validated['imagen_frente'], $validated['imagen_costado_izq'], $validated['imagen_costado_der'], $validated['imagen_dorso']);

        $rodado = Rodado::create($validated);

        // Handle image uploads
        $this->handleImageUploads($request, $rodado);

        return redirect()->route('rodados.index')
            ->with('success', 'Vehículo creado exitosamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rodado $rodado)
    {
        $validated = $request->validate([
            'marca' => ['required', 'string', 'in:'.implode(',', Rodado::getMarcas())],
            'tipo_vehiculo' => ['required', 'string', 'in:'.implode(',', Rodado::getTiposVehiculo())],
            'modelo' => 'required|string|max:255',
            'año' => 'required|integer|min:1900|max:'.(date('Y') + 1),
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'cliente_id' => 'required|exists:clientes,id',
            'es_propio' => 'nullable|boolean',
            'patente' => 'nullable|string|max:20',
            'imagen_frente' => 'nullable|image|max:5120',
            'imagen_costado_izq' => 'nullable|image|max:5120',
            'imagen_costado_der' => 'nullable|image|max:5120',
            'imagen_dorso' => 'nullable|image|max:5120',
        ]);

        // Asegurar que es_propio sea boolean
        $validated['es_propio'] = $request->has('es_propio') ? (bool) $request->es_propio : false;

        // Remove image files from validated
        unset($validated['imagen_frente'], $validated['imagen_costado_izq'], $validated['imagen_costado_der'], $validated['imagen_dorso']);

        // Handle image deletions
        foreach (['frente', 'costado_izq', 'costado_der', 'dorso'] as $tipo) {
            if ($request->has("eliminar_imagen_{$tipo}")) {
                $field = "imagen_{$tipo}_path";
                if ($rodado->$field) {
                    Storage::disk('public')->delete($rodado->$field);
                    $validated[$field] = null;
                }
            }
        }

        $rodado->update($validated);

        // Handle image uploads
        $this->handleImageUploads($request, $rodado);

        return redirect()->route('rodados.index')
            ->with('success', 'Vehículo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rodado $rodado)
    {
        // Eliminar archivos asociados si existen
        $turnos = $rodado->turnosRodados;
        foreach ($turnos as $turno) {
            if ($turno->factura_path) {
                Storage::disk('public')->delete($turno->factura_path);
            }
            if ($turno->comprobante_pago_path) {
                Storage::disk('public')->delete($turno->comprobante_pago_path);
            }
        }

        $cambiosEquipos = $rodado->cambiosEquipos;
        foreach ($cambiosEquipos as $cambio) {
            if ($cambio->factura_path) {
                Storage::disk('public')->delete($cambio->factura_path);
            }
            if ($cambio->comprobante_pago_path) {
                Storage::disk('public')->delete($cambio->comprobante_pago_path);
            }
        }

        $pagos = $rodado->pagosServicios;
        foreach ($pagos as $pago) {
            if ($pago->factura_path) {
                Storage::disk('public')->delete($pago->factura_path);
            }
            if ($pago->comprobante_pago_path) {
                Storage::disk('public')->delete($pago->comprobante_pago_path);
            }
        }

        $rodado->delete();

        // Delete vehicle images
        foreach (['imagen_frente_path', 'imagen_costado_izq_path', 'imagen_costado_der_path', 'imagen_dorso_path'] as $field) {
            if ($rodado->$field) {
                Storage::disk('public')->delete($rodado->$field);
            }
        }

        $rodado->delete();

        return redirect()->route('rodados.index')
            ->with('success', 'Vehículo eliminado exitosamente.');
    }

    /**
     * Handle image uploads for a rodado
     */
    private function handleImageUploads(Request $request, Rodado $rodado): void
    {
        $imageFields = [
            'imagen_frente' => 'imagen_frente_path',
            'imagen_costado_izq' => 'imagen_costado_izq_path',
            'imagen_costado_der' => 'imagen_costado_der_path',
            'imagen_dorso' => 'imagen_dorso_path',
        ];

        foreach ($imageFields as $inputName => $dbField) {
            if ($request->hasFile($inputName)) {
                // Delete old image if exists
                if ($rodado->$dbField) {
                    Storage::disk('public')->delete($rodado->$dbField);
                }
                $path = $request->file($inputName)->store("rodados/{$rodado->id}", 'public');
                $rodado->$dbField = $path;
            }
        }

        $rodado->save();
    }
}
