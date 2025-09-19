<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\StreamUrl;
use App\Models\Camera;
use App\Models\MobileVehicle;
use App\Models\Patrulla;

class HikCentralService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $apiSecret;

    public function __construct()
    {
        $url = env('HIKCENTRAL_URL', '');
        $this->baseUrl   = rtrim(env('HIKCENTRAL_URL', ''), '/');
        $apiKey = env('HIKCENTRAL_API_KEY');  
        $apiSecret = env('HIKCENTRAL_API_SECRET');

        Log::info('HikCentral ENV Debug', [
            'url' => $url,
            'api_key' => $apiKey ? 'SET' : 'NOT SET',
            'api_secret' => $apiSecret ? 'SET' : 'NOT SET'
        ]);

        if (!$apiKey || !$apiSecret) {
            throw new \Exception('Las variables de entorno HIKCENTRAL_API_KEY y HIKCENTRAL_API_SECRET son requeridas');
        }

        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    protected function signRequest(string $method, string $accept, string $contentType, string $path, int $timestamp): string
    {
        $stringToSign = strtoupper($method) . "\n" .
                    $accept . "\n" .
                    $contentType . "\n" .
                    $path;

    return base64_encode(hash_hmac('sha256', $stringToSign, $this->apiSecret, true));
    }

    public function getEncodingDevices(int $pageNo = 1, int $pageSize = 50): array
    {
        $path = '/artemis/api/resource/v1/encodeDevice/encodeDeviceList';
        $url  = $this->baseUrl.$path;
        $accept = 'application/json';

        $timestamp  = round(microtime(true) * 1000);
        $contentType = 'application/json';
        $signature = $this->signRequest('POST', $accept, $contentType, $path, $timestamp);

        $response = Http::withOptions(['verify' => false])
         ->withHeaders([
            'Accept'          => $accept,
            'Content-Type'    => $contentType,
            'x-ca-key'        => $this->apiKey,
            'x-ca-signature'  => $signature,
            'x-ca-timestamp'  => $timestamp,
        ])->post($url, [
            'pageNo'   => $pageNo,
            'pageSize' => $pageSize,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['code']) && $data['code'] === '0') {
                return $data['data']['list'] ?? [];
            }
        }

        throw new \Exception('Error en la API HikCentral: '.$response->body());
    }

    public function getStreamingUrl(string $cameraIndexCode): array
    {
        $path = '/artemis/api/video/v1/cameras/previewURLs';
        $url  = $this->baseUrl.$path;
        $accept = 'application/json';

        $timestamp  = round(microtime(true) * 1000);
        $contentType = 'application/json';
        $signature = $this->signRequest('POST', $accept, $contentType, $path, $timestamp);

        $requestBody = [
            'cameraIndexCode' => $cameraIndexCode,
            'streamType' => 0,      // 0: main stream, 1: sub stream
            'protocol' => 'rtsp',   // rtsp, http, https
            'transmode' => 1,       // 0: UDP, 1: TCP
            'requestWebsocketProtocol' => 0
        ];

        $response = Http::withOptions(['verify' => false])
         ->withHeaders([
            'Accept'          => $accept,
            'Content-Type'    => $contentType,
            'x-ca-key'        => $this->apiKey,
            'x-ca-signature'  => $signature,
            'x-ca-timestamp'  => $timestamp,
        ])->post($url, $requestBody);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['code']) && $data['code'] === '0') {
                return $data['data'] ?? [];
            }
        }

        throw new \Exception('Error obteniendo streaming URL: '.$response->body());
    }

    public function getCameraList(): array
    {
        Log::info('Iniciando getCameraList()');

        // Método para obtener las cámaras con la relación correcta
        $path = '/artemis/api/resource/v1/cameras';
        $url  = $this->baseUrl.$path;
        $accept = 'application/json';

        $timestamp  = round(microtime(true) * 1000);
        $contentType = 'application/json';
        $signature = $this->signRequest('POST', $accept, $contentType, $path, $timestamp);

        Log::info('HikCentral Request Debug', [
            'url' => $url,
            'timestamp' => $timestamp,
            'api_key' => substr($this->apiKey, 0, 4) . '***', // Solo primeros 4 caracteres por seguridad
            'signature' => substr($signature, 0, 10) . '...'
        ]);

        $response = Http::withOptions(['verify' => false])
         ->withHeaders([
            'Accept'          => $accept,
            'Content-Type'    => $contentType,
            'x-ca-key'        => $this->apiKey,
            'x-ca-signature'  => $signature,
            'x-ca-timestamp'  => $timestamp,
        ])->post($url, [
            'pageNo' => 1,
            'pageSize' => 100,
            'siteIndexCode' => '0',
            'deviceType' => 'encodeDevice',
            'bRecordSetting' => 1
        ]);

        if ($response->successful()) {
            $data = $response->json();

            Log::info('Response JSON structure', [
                'has_code' => isset($data['code']),
                'code_value' => $data['code'] ?? 'not set',
                'has_data' => isset($data['data']),
                'has_list' => isset($data['data']['list']),
                'list_count' => isset($data['data']['list']) ? count($data['data']['list']) : 0
            ]);

            if (isset($data['code']) && $data['code'] === '0') {
                return $data['data']['list'] ?? [];
            }

            throw new \Exception('API devolvió código de error: ' . ($data['code'] ?? 'unknown') . '. Response: ' . $response->body());
        }

        throw new \Exception('Error obteniendo lista de cámaras: '.$response->body());
    }

    public function importAllStreamingUrls(): array
    {
        $cameras = Camera::all();
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => []
        ];

        foreach ($cameras as $camera) {
            try {
                $streamData = $this->getStreamingUrl($camera->camera_index_code);
                
                if (!empty($streamData['url'])) {
                    StreamUrl::updateOrCreate(
                        ['camera_index_code' => $camera->camera_index_code],
                        [
                            'url' => $streamData['url'],
                            'authentication' => $streamData['authentication'] ?? null,
                            'protocol' => 'rtsp',
                            'stream_type' => 0,
                            'is_active' => true,
                            'last_updated' => now()
                        ]
                    );
                    $results['success']++;
                }
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][$camera->camera_index_code] = $e->getMessage();
            }
        }

        return $results;
    }

    public function getMobileVehicleList(int $pageNo = 1, int $pageSize = 50): array
    {
        $path = '/artemis/api/resource/v1/mobilevehicle/mobilevehicleList';
        $url  = $this->baseUrl . $path;
        $accept = 'application/json';

        $timestamp = round(microtime(true) * 1000);
        $contentType = 'application/json';
        $signature = $this->signRequest('POST', $accept, $contentType, $path, $timestamp);

        Log::info('HikCentral MobileVehicle Request', [
            'url' => $url,
            'pageNo' => $pageNo,
            'pageSize' => $pageSize
        ]);

        $response = Http::withOptions(['verify' => false])
            ->withHeaders([
                'Accept' => $accept,
                'Content-Type' => $contentType,
                'x-ca-key' => $this->apiKey,
                'x-ca-signature' => $signature,
                'x-ca-timestamp' => $timestamp,
            ])->post($url, [
                'pageNo' => $pageNo,
                'pageSize' => $pageSize,
            ]);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['code']) && $data['code'] === '0') {
                return $data['data'] ?? [];
            }
        }

        throw new \Exception('Error obteniendo lista de vehículos móviles: ' . $response->body());
    }

    public function importMobileVehicles(): array
    {
        $results = [
            'total_imported' => 0,
            'total_updated' => 0,
            'total_linked' => 0,
            'total_processed' => 0,
            'errors' => []
        ];

        try {
            $pageNo = 1;
            $pageSize = 50;
            $allVehicles = [];

            // Obtener todos los vehículos paginando
            do {
                Log::info("Obteniendo página {$pageNo}");
                $vehicleData = $this->getMobileVehicleList($pageNo, $pageSize);
                
                if (empty($vehicleData['list'])) {
                    break;
                }

                $allVehicles = array_merge($allVehicles, $vehicleData['list']);
                $pageNo++;

            } while (count($vehicleData['list']) === $pageSize && isset($vehicleData['total']) && count($allVehicles) < $vehicleData['total']);

            Log::info("Total de vehículos obtenidos: " . count($allVehicles));

            // Procesar cada vehículo
            foreach ($allVehicles as $vehicleInfo) {
                try {
                    $results['total_processed']++;

                    // Buscar si ya existe el vehículo
                    $mobileVehicle = MobileVehicle::where('mobile_vehicle_index_code', $vehicleInfo['mobilevehicleIndexCode'])->first();

                    $vehicleData = [
                        'mobile_vehicle_index_code' => $vehicleInfo['mobilevehicleIndexCode'],
                        'mobile_vehicle_name' => $vehicleInfo['mobilevehicleName'],
                        'status' => $vehicleInfo['status'],
                        'dev_index_code' => $vehicleInfo['DevIndexCode'],
                        'region_index_code' => $vehicleInfo['regionIndexCode'],
                        'plate_no' => $vehicleInfo['plateNo'],
                        'person_family_name' => !empty($vehicleInfo['personFamilyName']) ? $vehicleInfo['personFamilyName'] : null,
                        'person_given_name' => !empty($vehicleInfo['personGivenName']) ? $vehicleInfo['personGivenName'] : null,
                        'person_name' => !empty($vehicleInfo['personName']) ? $vehicleInfo['personName'] : null,
                        'phone_no' => !empty($vehicleInfo['phoneNo']) ? $vehicleInfo['phoneNo'] : null,
                        'vehicle_type' => $vehicleInfo['vehicleType'] !== -1 ? $vehicleInfo['vehicleType'] : null,
                        'vehicle_brand' => $vehicleInfo['vehicleBrand'] !== -1 ? $vehicleInfo['vehicleBrand'] : null,
                        'vehicle_color' => $vehicleInfo['vehicleColor'] !== -1 ? $vehicleInfo['vehicleColor'] : null,
                    ];

                    // Buscar patrulla correspondiente por patente
                    $patrulla = Patrulla::where('patente', $vehicleInfo['plateNo'])->first();
                    if ($patrulla) {
                        $vehicleData['patrulla_id'] = $patrulla->id;
                        $results['total_linked']++;
                    }

                    if ($mobileVehicle) {
                        // si existe actualizar registro
                        $mobileVehicle->update($vehicleData);
                        $results['total_updated']++;
                        Log::info("Vehículo actualizado: {$vehicleInfo['plateNo']}");
                    } else {
                        // Crear nuevo vehículo
                        MobileVehicle::create($vehicleData);
                        $results['total_imported']++;
                        Log::info("Vehículo importado: {$vehicleInfo['plateNo']}");
                    }

                } catch (\Exception $e) {
                    $results['errors'][] = [
                        'vehicle' => $vehicleInfo['plateNo'] ?? 'Unknown',
                        'error' => $e->getMessage()
                    ];
                    Log::error("Error procesando vehículo: " . $e->getMessage(), [
                        'vehicle_data' => $vehicleInfo
                    ]);
                }
            }

            Log::info('Importación de vehículos completada', $results);

        } catch (\Exception $e) {
            Log::error('Error en importación de vehículos: ' . $e->getMessage());
            throw $e;
        }

        return $results;
    }
}
