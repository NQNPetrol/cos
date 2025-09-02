<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Personal;
use Illuminate\Support\Facades\Validator;

class PersonalCompareController extends Controller
{
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

        if (!is_array($datos) || !isset($datos[0]) || !is_array($datos[0])) {
            return response()->json([
                'success' => false,
                'message' => 'Se esperaba un array de registros'
            ], 422);
        }

        $registros_nuevos = [];
        $registros_duplicados = [];
        $errores = [];

        foreach ($datos as $index => $item) {
            try {
                // Verificar si ya existe
                $existe = $this->verificarDuplicado($item);

                if ($existe['existe']) {
                    $registros_duplicados[] = [
                        'indice' => $index,
                        'criterio' => $existe['criterio'],
                        'registro_existente' => $existe['registro'],
                        'datos_nuevos' => $item
                    ];
                    continue;
                }

                $validator = Validator::make($item, [
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
                    $errores[] = [
                        'indice' => $index,
                        'errores' => $validator->errors()->toArray(),
                        'data' => $item
                    ];
                    continue;
                }

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
            
                \Log::info("Registro creado exitosamente:", [
                    'indice' => $index,
                    'id' => $personal->id,
                    'nombre' => $personal->nombre,
                    'apellido' => $personal->apellido,
                    'legajo' => $personal->legajo
                ]);
                
                $registros_nuevos[] = $personal;

            } catch (\Exception $e) {
                $errores[] = [
                    'indice' => $index,
                    'error' => $e->getMessage(),
                    'data' => $item
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Procesamiento completado',
            'estadisticas' => [
                'total_registros' => count($datos),
                'nuevos_registros' => count($registros_nuevos),
                'registros_duplicados' => count($registros_duplicados),
                'errores' => count($errores)
            ],
            'registros_nuevos' => $registros_nuevos,
            'registros_duplicados' => $registros_duplicados,
            'errores' => $errores
        ], 201);
    }

    private function verificarDuplicado(array $item)
    {
        $existe = false;
        $criterio = '';
        $registro = null;

        // Por legajo
        if (isset($item['legajo']) && $item['legajo']) {
            $registro = Personal::where('legajo', $item['legajo'])->first();
            if ($registro) {
                return [
                    'existe' => true,
                    'criterio' => 'legajo',
                    'registro' => $registro
                ];
            }
        }

        // Por nro_doc
        if (isset($item['nro_doc']) && $item['nro_doc']) {
            $registro = Personal::where('nro_doc', $item['nro_doc'])->first();
            if ($registro) {
                return [
                    'existe' => true,
                    'criterio' => 'nro_doc',
                    'registro' => $registro
                ];
            }
        }

        // Por nombre + apellido
        if (isset($item['nombre']) && isset($item['apellido'])) {
            $registro = Personal::where('nombre', $item['nombre'])
                                ->where('apellido', $item['apellido'])
                                ->first();
            if ($registro) {
                return [
                    'existe' => true,
                    'criterio' => 'nombre_apellido',
                    'registro' => $registro
                ];
            }
        }

        return [
            'existe' => false,
            'criterio' => '',
            'registro' => null
        ];
    }

    public function verificarExistencia(Request $request)
    {
        $datos = $request->all();

        if (!is_array($datos) || !isset($datos[0]) || !is_array($datos[0])) {
            return response()->json([
                'success' => false,
                'message' => 'Se esperaba un array de registros para verificación masiva'
            ], 422);
        }

        $resultados = [];

        foreach ($datos as $index => $item) {
            try {
                $resultado = $this->verificarDuplicado($item);
                
                $resultados[] = [
                    'indice' => $index,
                    'existe' => $resultado['existe'],
                    'criterio' => $resultado['criterio'],
                    'registro_existente' => $resultado['registro'],
                    'datos_consultados' => $item
                ];

            } catch (\Exception $e) {
                $resultados[] = [
                    'indice' => $index,
                    'error' => $e->getMessage(),
                    'existe' => false,
                    'datos_consultados' => $item
                ];
            }
        }

        return response()->json([
            'success' => true,
            'total_registros' => count($datos),
            'registros_existentes' => count(array_filter($resultados, fn($r) => $r['existe'])),
            'registros_nuevos' => count(array_filter($resultados, fn($r) => !$r['existe'] && !isset($r['error']))),
            'errores' => count(array_filter($resultados, fn($r) => isset($r['error']))),
            'resultados' => $resultados
        ], 200);
    }
}
