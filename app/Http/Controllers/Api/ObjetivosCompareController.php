<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ObjetivoAipem;
use Illuminate\Support\Facades\Validator;

class ObjetivosCompareController extends Controller
{
    public function index()
    {
        $objetivos = ObjetivoAipem::all();
        return response()->json($objetivos, 200);
    }

    public function store(Request $request)
    {
        $datos = $request->all();

        \Log::info('Datos recibidos para objetivos_aipem:', $datos);
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
                    'codobj' => 'required|string|max:255',
                    'nombre' => 'required|string|max:255',
                    'fecha_alta' => 'nullable|date',
                    'fecha_baja' => 'nullable|date',
                    'codcli' => 'required|string|max:4',
                    'codsuc' => 'nullable|string|max:4',
                    'codsup' => 'required|string|max:4',
                    'calle' => 'required|string|max:255',
                    'nro' => 'required|string|max:10',
                    'piso' => 'nullable|string|max:10',
                    'dpto' => 'nullable|string|max:10',
                    'localidad' => 'required|string|max:255',
                    'pcia' => 'required|string|max:2',
                    'codpostal' => 'required|string|max:10',
                    'codzona' => 'nullable|string|max:4',
                    'pais' => 'nullable|string|max:3',
                    'coordmaps' => 'nullable|string|max:255',
                    'telefono' => 'nullable|string|max:20',
                    'email' => 'nullable|email|max:255',
                    'valid_ini' => 'required|date',
                    'valid_fin' => 'required|date',
                    'pto_descrip' => 'required|string'
                ]);

                if ($validator->fails()) {
                    $errores[] = [
                        'indice' => $index,
                        'errores' => $validator->errors()->toArray(),
                        'data' => $item
                    ];
                    continue;
                }

                $objetivo = ObjetivoAipem::create([
                    'codobj' => $item['codobj'],
                    'nombre' => $item['nombre'],
                    'fecha_alta' => $item['fecha_alta'] ?? null,
                    'fecha_baja' => $item['fecha_baja'] ?? null,
                    'codcli' => $item['codcli'],
                    'codsuc' => $item['codsuc'] ?? null,
                    'codsup' => $item['codsup'],
                    'calle' => $item['calle'],
                    'nro' => $item['nro'],
                    'piso' => $item['piso'] ?? null,
                    'dpto' => $item['dpto'] ?? null,
                    'localidad' => $item['localidad'],
                    'pcia' => $item['pcia'],
                    'codpostal' => $item['codpostal'],
                    'codzona' => $item['codzona'] ?? null,
                    'pais' => $item['pais'] ?? null,
                    'coordmaps' => $item['coordmaps'] ?? null,
                    'telefono' => $item['telefono'] ?? null,
                    'email' => $item['email'] ?? null,
                    'valid_ini' => $item['valid_ini'],
                    'valid_fin' => $item['valid_fin'],
                    'pto_descrip' => $item['pto_descrip']
                ]);
            
                \Log::info("Registro de objetivo creado exitosamente:", [
                    'indice' => $index,
                    'id' => $objetivo->id,
                    'codobj' => $objetivo->codobj,
                    'nombre' => $objetivo->nombre
                ]);
                
                $registros_nuevos[] = $objetivo;

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

        // Por codobj (código de objetivo)
        if (isset($item['codobj']) && $item['codobj']) {
            $registro = ObjetivoAipem::where('codobj', $item['codobj'])->first();
            if ($registro) {
                return [
                    'existe' => true,
                    'criterio' => 'codobj',
                    'registro' => $registro
                ];
            }
        }

        // Por nombre + dirección (como criterio secundario)
        if (isset($item['nombre']) && isset($item['calle']) && isset($item['nro'])) {
            $registro = ObjetivoAipem::where('nombre', $item['nombre'])
                                ->where('calle', $item['calle'])
                                ->where('nro', $item['nro'])
                                ->first();
            if ($registro) {
                return [
                    'existe' => true,
                    'criterio' => 'nombre_direccion',
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
