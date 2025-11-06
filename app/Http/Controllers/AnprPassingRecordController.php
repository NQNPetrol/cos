<?php

namespace App\Http\Controllers;

use App\Services\HikCentralService;
use App\Models\AnprPassingRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnprPassingRecordController extends Controller
{
    protected $hikCentralService;

    public function __construct(HikCentralService $hikCentralService)
    {
        $this->hikCentralService = $hikCentralService;
    }

    public function importLast24Hours()
    {
        try {
            // Calcular fechas para EXACTAMENTE las últimas 24 horas desde ahora
            $endTime = now()->setTimezone('+08:00')->format('Y-m-d\TH:i:sP');
            $startTime = now()->subHours(24)->setTimezone('+08:00')->format('Y-m-d\TH:i:sP');

            Log::info('Iniciando importación de registros de cruce (24h exactas)', [
                'start_time' => $startTime,
                'end_time' => $endTime,
                'now_utc' => now()->toISOString(),
                'now_timezone' => now()->setTimezone('+08:00')->toISOString()
            ]);

            $results = $this->hikCentralService->importCrossRecords($startTime, $endTime);

            // Obtener estadísticas actualizadas después de la importación
            $totalRecords = AnprPassingRecord::count();
            $todayRecords = AnprPassingRecord::whereDate('cross_time', today())->count();
            $uniquePlates = AnprPassingRecord::distinct('plate_no')->count('plate_no');

            

            return response()->json([
                'success' => true,
                'message' => "Importación completada. {$results['total_imported']} registros procesados.",
                'data' => [
                    'api_response' => $results,
                    'total_records' => $totalRecords,
                    'today_records' => $todayRecords,
                    'unique_plates' => $uniquePlates,
                    'imported_count' => $results['total_imported'],
                    'time_range' => [
                        'start_time' => $startTime,
                        'end_time' => $endTime
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error en importación de registros de 24h: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error en la importación: ' . $e->getMessage()
            ], 500);
        }
    }

    public function importByDateRange(Request $request)
    {
        $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'camera_index_code' => 'sometimes|string'
        ]);

        try {
            // Convertir fechas a formato requerido con timezone +08:00
            $startTime = \Carbon\Carbon::parse($request->start_time)
                ->setTimezone('+08:00')
                ->format('Y-m-d\TH:i:sP');
            
            $endTime = \Carbon\Carbon::parse($request->end_time)
                ->setTimezone('+08:00')
                ->format('Y-m-d\TH:i:sP');

            Log::info('Iniciando importación de registros por rango', [
                'start_time' => $startTime,
                'end_time' => $endTime,
                'camera' => $request->camera_index_code ?? '101'
            ]);

            $results = $this->hikCentralService->importCrossRecords($startTime, $endTime);

            return response()->json([
                'success' => true,
                'message' => 'Importación completada',
                'data' => $results
            ]);

        } catch (\Exception $e) {
            Log::error('Error en importación por rango: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error en la importación: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $query = AnprPassingRecord::query();

        // Filtros
        if ($request->has('plate_no')) {
            $query->where('plate_no', 'like', '%' . $request->plate_no . '%');
        }

        if ($request->has('camera_index_code')) {
            $query->where('camera_index_code', $request->camera_index_code);
        }

        if ($request->has('start_date')) {
            $query->where('cross_time', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('cross_time', '<=', $request->end_date);
        }

        $totalRecords = AnprPassingRecord::count();
        $todayRecords = AnprPassingRecord::whereDate('cross_time', today())->count();
        $uniquePlates = AnprPassingRecord::distinct('plate_no')->count('plate_no');
        
        // Última importación (basado en el registro más reciente)
        $lastRecord = AnprPassingRecord::orderBy('created_at', 'desc')->first();
        $lastImportTime = $lastRecord ? $lastRecord->created_at->diffForHumans() : null;

        $uniquePlatesList = AnprPassingRecord::distinct('plate_no')
            ->whereNotNull('plate_no')
            ->where('plate_no', '!=', '')
            ->orderBy('plate_no')
            ->pluck('plate_no');

        $camerasList = AnprPassingRecord::distinct('camera_index_code')
            ->whereNotNull('camera_index_code')
            ->orderBy('camera_index_code')
            ->pluck('camera_index_code');

        // Ordenamiento
        $sortField = $request->get('sort_field', 'cross_time');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        

        $records = $query->paginate($request->get('per_page', 50));

        return view('anpr-records.index', compact(
            'records', 
            'totalRecords', 
            'todayRecords', 
            'uniquePlates',
            'lastImportTime',
            'camerasList',
            'uniquePlatesList'
        ));
    }
}
