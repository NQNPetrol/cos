<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camera;
use App\Models\EncodingDevice;
use App\Models\Dispositivo;
use App\Services\HikCentralService;

class CameraController extends Controller
{
    protected HikCentralService $hikCentral;

    public function __construct(HikCentralService $hikCentral)
    {
        $this->hikCentral = $hikCentral;
    }

    public function index()
    {
        $cameras = Camera::with('encodingDevice')->paginate(10);
        return view('cameras.index', compact('cameras'));
    }

    public function import()
    {
        try {
            \Log::info('Iniciando importación de cámaras...');
            $cameras = $this->hikCentral->getCameraList();
            \Log::info('Cámaras obtenidas: ' . count($cameras));

            $imported = 0;
            $updated = 0;

            foreach ($cameras as $cameraData) {
                $camera = Camera::updateOrCreate(
                    ['camera_index_code' => $cameraData['cameraIndexCode']],
                    [
                        'camera_name' => $cameraData['cameraName'],
                        'capability_set' => $cameraData['capabilitySet'] ?? null,
                        'dev_resource_type' => $cameraData['devResourceType'] ?? 'encodeDevice',
                        'encode_dev_index_code' => $cameraData['encodeDevIndexCode'] ?? null,
                        'record_type' => $cameraData['recordType'] ?? null,
                        'record_location' => $cameraData['recordLocation'] ?? null,
                        'region_index_code' => $cameraData['regionIndexCode'] ?? null,
                        'site_index_code' => $cameraData['siteIndexCode'] ?? '0',
                        'status' => $cameraData['status'] ?? 1,
                        'is_support_wake_up' => $cameraData['isSupportWakeUp'] ?? false,
                        'wake_up_status' => $cameraData['wakeUpStatus'] ?? 0,
                    ]
                );

                if ($camera->wasRecentlyCreated) {
                    $imported++;
                } else {
                    $updated++;
                }
            }

            return response()->json([
                'message' => "Importación completada: $imported nuevas cámaras, $updated actualizadas",
                'imported' => $imported,
                'updated' => $updated,
                'total' => count($cameras)
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en importación: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error importando cámaras: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStreamUrl($cameraIndexCode)
    {
        try {
            $stream = StreamUrl::where('camera_index_code', $cameraIndexCode)->first();
            
            if (!$stream) {
                // Si no existe, obtenerla de HikCentral
                $streamData = $this->hikCentral->getStreamingUrl($cameraIndexCode);
                
                $stream = StreamUrl::create([
                    'camera_index_code' => $cameraIndexCode,
                    'url' => $streamData['url'],
                    'authentication' => $streamData['authentication'] ?? null,
                    'protocol' => 'rtsp',
                    'stream_type' => 0,
                    'is_active' => true
                ]);
            }

            return response()->json([
                'success' => true,
                'stream_url' => $stream->url,
                'authentication' => $stream->authentication
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function importStreamingUrls()
    {
        try {
            $results = $this->hikCentral->importAllStreamingUrls();
            
            return response()->json([
                'message' => "Importación de URLs completada: {$results['success']} exitosas, {$results['failed']} fallidas",
                'results' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error importando URLs: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showStream($cameraIndexCode)
    {
        $camera = Camera::with('stream')->where('camera_index_code', $cameraIndexCode)->first();
        
        if (!$camera) {
            abort(404, 'Cámara no encontrada');
        }

        return view('cameras.liveview', compact('camera'));
    }

    public function camerasWithDevices()
    {
        $cameras = Camera::with('encodingDevice')
            ->active()
            ->byDeviceType('encodeDevice')
            ->get()
            ->map(function ($camera) {
                return [
                    'camera_index_code' => $camera->camera_index_code,
                    'camera_name' => $camera->camera_name,
                    'status' => $camera->status,
                    'capability_set' => $camera->capability_set,
                    'encoding_device' => $camera->encodingDevice ? [
                        'encode_dev_index_code' => $camera->encodingDevice->encode_dev_index_code,
                        'name' => $camera->encodingDevice->name,
                        'ip' => $camera->encodingDevice->ip,
                        'port' => $camera->encodingDevice->port,
                        'status' => $camera->encodingDevice->status
                    ] : null
                ];
            });

        return response()->json($cameras);
    }

    public function findByEncodingDevice($encodeDevIndexCode)
    {
        $cameras = Camera::where('encode_dev_index_code', $encodeDevIndexCode)->get();
        
        return response()->json([
            'encode_dev_index_code' => $encodeDevIndexCode,
            'cameras' => $cameras
        ]);
    }

    /**
     * Listado de dispositivos disponibles para vincular con cámaras
     * Filtrados por tipo: camara_ip y camara_ptz
     */
    public function availableDevices(Request $request)
    {
        $query = Dispositivo::query()
            ->whereIn('tipo', ['camara_ip', 'camara_ptz'])
            ->orderBy('nombre');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('modelo', 'like', "%{$search}%")
                  ->orWhere('direccion_ip', 'like', "%{$search}%");
            });
        }

        $dispositivos = $query->limit(100)->get([
            'id',
            'nombre',
            'tipo',
            'modelo',
            'direccion_ip',
            'estado_inventario',
            'observaciones',
        ]);

        return response()->json($dispositivos);
    }

    /**
     * Vincular un dispositivo a una cámara
     */
    public function linkDevice(Request $request, Camera $camera)
    {
        $validated = $request->validate([
            'dispositivo_id' => 'required|exists:dispositivos,id',
        ]);

        $camera->dispositivo_id = $validated['dispositivo_id'];
        $camera->save();

        return response()->json([
            'success' => true,
            'message' => 'Dispositivo vinculado correctamente a la cámara.',
        ]);
    }
}
