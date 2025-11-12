<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\AnprPassingRecord;
use App\Models\AnprEventImage;
use App\Services\HikCentralService;
use App\Services\HikCentralImageService;
use Illuminate\Support\Facades\Log;

class AnprPassingRecordController extends Controller
{
    protected $hikImageService;

    public function __construct(HikCentralImageService $hikImageService, HikCentralService $hikCentralService)
    {
        $this->hikImageService = $hikImageService;
        $this->hikCentralService = $hikCentralService;
    }

    public function processImageAfterImport($anprRecord): void
    {
        if (is_array($anprRecord)) {
            $recordId = $anprRecord['id'];
            $picUri = $anprRecord['vehicle_pic_uri'] ?? null;
            $plateNo = $anprRecord['plate_no'] ?? 'unknown';
            
            Log::info('[PROCESS_IMAGE] Procesando desde array', [
                'record_id' => $recordId,
                'pic_uri' => $picUri,
                'plate_no' => $plateNo
            ]);
            
        } else {
            // Si es modelo Eloquent
            $recordId = $anprRecord->id;
            $picUri = $anprRecord->vehicle_pic_uri ?? null;
            $plateNo = $anprRecord->plate_no ?? 'unknown';
            Log::info('[PROCESS_IMAGE] Procesando desde modelo', [
                'record_id' => $recordId,
                'pic_uri' => $picUri,
                'plate_no' => $plateNo
            ]);
        }

        if (empty($picUri) || !str_starts_with($picUri, 'Vsm://')) {
            Log::warning('[IMAGE_PROCESSING_SKIP] URI no compatible', [
                'anpr_record_id' => $recordId,
                'plate_no' => $plateNo,
                'uri_type' => empty($picUri) ? 'empty' : 'non-vsm'
            ]);
            return;
        }

        try {
            Log::info('[IMAGE_PROCESSING_START] Iniciando procesamiento de imagen', [
                'anpr_record_id' => $recordId,
                'pic_uri' => $picUri,
                'plate_no' => $plateNo
            ]);
            // Obtener el path de la imagen de la API HikCentral
            $imagePath = $this->hikImageService->fetchImagePathFromHikCentral($picUri);
            
            if ($imagePath && str_starts_with($imagePath, 'data:image/jpeg;base64,')) {
                Log::info('El registro cumple con las condiciones adecuadas');
                // Crear registro en anpr_event_images con el path obtenido
                $imageBase64 = str_replace('data:image/jpeg;base64,', '', $imagePath);
                $eventImage = AnprEventImage::create([
                    'anpr_record_id' => $recordId,
                    'veh_pic_uri' => $picUri,
                    'image_path' => $imagePath,
                    'image_base64' => $imageBase64,
                    'status' => 'base64_obtained',
                    'file_size' => strlen($imageBase64)
                ]);

                Log::info('[IMAGE_PROCESSING_SUCCESS] Path de imagen obtenido exitosamente y registro creado', [
                    'anpr_record_id' => $recordId,
                    'event_image_id' => $eventImage->id,
                    'image_path_length' => strlen($imagePath),
                    'plate_no' => $plateNo
                ]);
            } else {
                Log::error('[IMAGE_PROCESSING_ERROR] Error crítico al procesar imagen después de importar', [
                'anpr_record_id' => $recordId,
                'pic_uri' => $picUri,
                'plate_no' => $plateNo
            ]);

            }
        } catch (\Exception $e) {
            Log::error('Error al procesar imagen después de importar', [
                'anpr_record_id' => $recordId,
                'pic_uri' => $picUri,
                'plate_no' => $plateNo,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);
        }
    }

    //PARA AGREGAR A UN CRONOJOB
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

            Log::info('🔍 [DEBUG] Estructura completa de $results', [
                'results_keys' => array_keys($results),
                'results_full' => $results
            ]);

        

            if (isset($results['imported_records']) && count($results['imported_records']) > 0) {
                Log::info('🎯 [IMAGE_PROCESSING] Condición cumplida, procesando imágenes', [
                    'total_records' => count($results['imported_records'])
                ]);
                
                $processedCount = 0;
                $skippedCount = 0;
                
                foreach ($results['imported_records'] as $index => $importedRecord) {
                    Log::info("[IMAGE_PROCESSING] Procesando registro {$index}", [
                        'record_id' => $importedRecord['id'] ?? 'unknown',
                        'vehicle_pic_uri' => $importedRecord['vehicle_pic_uri'] ?? 'null',
                        'plate_no' => $importedRecord['plate_no'] ?? 'unknown'
                    ]);
                    
                    if (!empty($importedRecord['vehicle_pic_uri'])) {
                        Log::info('📸 [IMAGE_PROCESSING] Llamando a processImageAfterImport');
                        $this->processImageAfterImport($importedRecord);
                        $processedCount++;
                    } else {
                        Log::warning('🚫 [IMAGE_PROCESSING] pic_uri vacío, saltando registro', [
                            'record_id' => $importedRecord['id'] ?? 'unknown',
                            'plate_no' => $importedRecord['plate_no'] ?? 'unknown'
                        ]);
                        $skippedCount++;
                    }
                }
                
                Log::info('✅ [IMAGE_PROCESSING] Procesamiento completado', [
                    'total_processed' => $processedCount,
                    'total_skipped' => $skippedCount
                ]);
            } else {
                Log::warning('❌ [IMAGE_PROCESSING] No hay registros importados para procesar imágenes', [
                    'has_imported_records' => isset($results['imported_records']),
                    'imported_records_count' => isset($results['imported_records']) ? count($results['imported_records']) : 0,
                    'total_imported' => $results['total_imported'] ?? 0
                ]);
            }
            

            Log::info('Si despues de Resultados obtenidos ves este mensaje es porque no se ejecuta la condicion');

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
