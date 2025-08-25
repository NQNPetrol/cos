<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Personal;
use Illuminate\Support\Facades\Validator;

class PersonalImportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personal = Personal::all();
        return response()->json($personal, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datos = $request->all();

        \Log::info('Datos recibidos:', $datos);
        \Log::info('Tipo de datos:', ['type' => gettype($datos)]);
        \Log::info('Es array?', ['is_array' => is_array($datos)]);

        if (is_array($datos) && isset($datos[0]) && is_array($datos[0])) {
            return $this->importarMasivo($datos);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'fecha_ing' => 'nullable|date',
            'puesto' => 'nullable|string',
            'convenio' => 'nullable|string',
            'cargo' => 'nullable|string',
            'cliente_id' => 'nullable|integer|exists:clientes,id',
            'tipo_doc' => 'nullable|string',
            'nro_doc' => 'nullable|string|unique:personal,nro_doc',
            'telefono' => 'nullable|string',
            'legajo' => 'nullable|integer|unique:personal,legajo'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $personal = Personal::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'fecha_ing' => $request->fecha_ing ?? now(),
            'puesto' => $request->puesto ?? 'Sin definir',
            'convenio' => $request->convenio ?? '',
            'cargo' => $request->cargo ?? 'Sin definir',
            'cliente_id' => $request->cliente_id ?? 1,
            'tipo_doc' => $request->tipo_doc ?? 'DU',
            'nro_doc' => $request->nro_doc ?? '',
            'telefono' => $request->telefono ?? '',
            'legajo' => $request->legajo ?? ''
        ]);

        if (!$personal) {
            $data = [
                'mensaje' => 'Error al crear el estudiante',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        return response()->json($personal, 201);
        
    }

    private function importarMasivo(array $datos)
    {
        \Log::info('=== INICIANDO IMPORTACIÓN MASIVA ===');
        \Log::info('Total de registros recibidos:', ['count' => count($datos)]);
        \Log::info('Primer registro:', $datos[0]);

        $resultados = [];
        $errores = [];
        
        foreach ($datos as $index => $item) {
            \Log::info("Procesando registro $index:", $item);
            try {

                if (isset($item['nro_doc']) && is_numeric($item['nro_doc'])) {
                    $item['nro_doc'] = (string) $item['nro_doc'];
                    \Log::info("nro_doc convertido a string:", ['nro_doc' => $item['nro_doc']]);
                }

                $validator = Validator::make($item, [
                    'nombre' => 'required|string|max:255',
                    'apellido' => 'required|string|max:255',
                    'nro_doc' => 'nullable|string',
                    'legajo' => 'nullable|integer'
                ]);
                
                if ($validator->fails()) {
                    $errores[] = [
                        'indice' => $index,
                        'errores' => $validator->errors()->toArray(),
                        'data' => $item
                    ];
                    continue;
                }
                \Log::info("Creando registro $index...");
                
                $personal = Personal::create([
                    'nombre' => $item['nombre'],
                    'apellido' => $item['apellido'],
                    'fecha_ing' => $item['fecha_ing'] ?? now(),
                    'puesto' => $item['puesto'] ?? 'Sin definir',
                    'convenio' => $item['convenio'] ?? '',
                    'cargo' => $item['cargo'] ?? 'Sin definir',
                    'cliente_id' => $item['cliente_id'] ?? 1,
                    'tipo_doc' => $item['tipo_doc'] ?? 'DU',
                    'nro_doc' => $item['nro_doc'] ?? '',
                    'telefono' => $item['telefono'] ?? '',
                    'legajo' => $item['legajo'] ?? null
                ]);
                
                \Log::info("Registro $index creado:", ['id' => $personal->id]);
                $resultados[] = $personal;
                
            } catch (\Exception $e) {
                \Log::error("Exception registro $index:", [
                    'error' => $e->getMessage(),
                    'data' => $item
                ]);
                $errores[] = [
                    'indice' => $index,
                    'error' => $e->getMessage(),
                    'data' => $item
                ];
            }
        }

        \Log::info('=== IMPORTACIÓN MASIVA COMPLETADA ===');
        \Log::info('Registros creados:', ['count' => count($resultados)]);
        \Log::info('Errores:', ['count' => count($errores)]);
        
        return response()->json([
            'success' => true,
            'message' => 'Importación completada',
            'registros_creados' => count($resultados),
            'errores' => count($errores),
            'detalle_errores' => $errores,
            'data' => $resultados
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
