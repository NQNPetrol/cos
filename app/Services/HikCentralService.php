<?php

namespace App\Services;

use App\Models\AnprPassingRecord;
use App\Models\Camera;
use App\Models\MobileVehicle;
use App\Models\Patrulla;
use App\Models\StreamUrl;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HikCentralService
{
    protected string $baseUrl;

    protected string $apiKey;

    protected string $apiSecret;

    public function __construct()
    {
        $url = env('HIKCENTRAL_URL', '');
        $this->baseUrl = rtrim(env('HIKCENTRAL_URL', ''), '/');
        $apiKey = env('HIKCENTRAL_API_KEY');
        $apiSecret = env('HIKCENTRAL_API_SECRET');

        Log::info('HikCentral ENV Debug', [
            'url' => $url,
            'api_key' => $apiKey ? 'SET' : 'NOT SET',
            'api_secret' => $apiSecret ? 'SET' : 'NOT SET',
        ]);

        if (! $apiKey || ! $apiSecret) {
            throw new \Exception('Las variables de entorno HIKCENTRAL_API_KEY y HIKCENTRAL_API_SECRET son requeridas');
        }

        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    protected function signRequest(string $method, string $accept, string $contentType, string $path, int $timestamp): string
    {
        $stringToSign = strtoupper($method)."\n".
                    $accept."\n".
                    $contentType."\n".
                    $path;

        return base64_encode(hash_hmac('sha256', $stringToSign, $this->apiSecret, true));
    }

    public function getEncodingDevices(int $pageNo = 1, int $pageSize = 50): array
    {
        $path = '/artemis/api/resource/v1/encodeDevice/encodeDeviceList';
        $url = $this->baseUrl.$path;
        $accept = 'application/json';

        $timestamp = round(microtime(true) * 1000);
        $contentType = 'application/json';
        $signature = $this->signRequest('POST', $accept, $contentType, $path, $timestamp);

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
                return $data['data']['list'] ?? [];
            }
        }

        throw new \Exception('Error en la API HikCentral: '.$response->body());
    }

    public function getStreamingUrl(string $cameraIndexCode): array
    {
        $path = '/artemis/api/video/v1/cameras/previewURLs';
        $url = $this->baseUrl.$path;
        $accept = 'application/json';

        $timestamp = round(microtime(true) * 1000);
        $contentType = 'application/json';
        $signature = $this->signRequest('POST', $accept, $contentType, $path, $timestamp);

        $requestBody = [
            'cameraIndexCode' => $cameraIndexCode,
            'streamType' => 0,      // 0: main stream, 1: sub stream
            'protocol' => 'rtsp',   // rtsp, http, https
            'transmode' => 1,       // 0: UDP, 1: TCP
            'requestWebsocketProtocol' => 0,
        ];

        $response = Http::withOptions(['verify' => false])
            ->withHeaders([
                'Accept' => $accept,
                'Content-Type' => $contentType,
                'x-ca-key' => $this->apiKey,
                'x-ca-signature' => $signature,
                'x-ca-timestamp' => $timestamp,
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
        $url = $this->baseUrl.$path;
        $accept = 'application/json';

        $timestamp = round(microtime(true) * 1000);
        $contentType = 'application/json';
        $signature = $this->signRequest('POST', $accept, $contentType, $path, $timestamp);

        Log::info('HikCentral Request Debug', [
            'url' => $url,
            'timestamp' => $timestamp,
            'api_key' => substr($this->apiKey, 0, 4).'***', // Solo primeros 4 caracteres por seguridad
            'signature' => substr($signature, 0, 10).'...',
        ]);

        $response = Http::withOptions(['verify' => false])
            ->withHeaders([
                'Accept' => $accept,
                'Content-Type' => $contentType,
                'x-ca-key' => $this->apiKey,
                'x-ca-signature' => $signature,
                'x-ca-timestamp' => $timestamp,
            ])->post($url, [
             'pageNo' => 1,
             'pageSize' => 100,
             'siteIndexCode' => '0',
             'deviceType' => 'encodeDevice',
             'bRecordSetting' => 1,
         ]);

        if ($response->successful()) {
            $data = $response->json();

            Log::info('Response JSON structure', [
                'has_code' => isset($data['code']),
                'code_value' => $data['code'] ?? 'not set',
                'has_data' => isset($data['data']),
                'has_list' => isset($data['data']['list']),
                'list_count' => isset($data['data']['list']) ? count($data['data']['list']) : 0,
            ]);

            if (isset($data['code']) && $data['code'] === '0') {
                return $data['data']['list'] ?? [];
            }

            throw new \Exception('API devolvió código de error: '.($data['code'] ?? 'unknown').'. Response: '.$response->body());
        }

        throw new \Exception('Error obteniendo lista de cámaras: '.$response->body());
    }

    public function importAllStreamingUrls(): array
    {
        $cameras = Camera::all();
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($cameras as $camera) {
            try {
                $streamData = $this->getStreamingUrl($camera->camera_index_code);

                if (! empty($streamData['url'])) {
                    StreamUrl::updateOrCreate(
                        ['camera_index_code' => $camera->camera_index_code],
                        [
                            'url' => $streamData['url'],
                            'authentication' => $streamData['authentication'] ?? null,
                            'protocol' => 'rtsp',
                            'stream_type' => 0,
                            'is_active' => true,
                            'last_updated' => now(),
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
        $url = $this->baseUrl.$path;
        $accept = 'application/json';

        $timestamp = round(microtime(true) * 1000);
        $contentType = 'application/json';
        $signature = $this->signRequest('POST', $accept, $contentType, $path, $timestamp);

        Log::info('HikCentral MobileVehicle Request', [
            'url' => $url,
            'pageNo' => $pageNo,
            'pageSize' => $pageSize,
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

        throw new \Exception('Error obteniendo lista de vehículos móviles: '.$response->body());
    }

    public function importMobileVehicles(): array
    {
        $results = [
            'total_imported' => 0,
            'total_updated' => 0,
            'total_linked' => 0,
            'total_processed' => 0,
            'errors' => [],
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

            Log::info('Total de vehículos obtenidos: '.count($allVehicles));

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
                        'person_family_name' => ! empty($vehicleInfo['personFamilyName']) ? $vehicleInfo['personFamilyName'] : null,
                        'person_given_name' => ! empty($vehicleInfo['personGivenName']) ? $vehicleInfo['personGivenName'] : null,
                        'person_name' => ! empty($vehicleInfo['personName']) ? $vehicleInfo['personName'] : null,
                        'phone_no' => ! empty($vehicleInfo['phoneNo']) ? $vehicleInfo['phoneNo'] : null,
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
                        'error' => $e->getMessage(),
                    ];
                    Log::error('Error procesando vehículo: '.$e->getMessage(), [
                        'vehicle_data' => $vehicleInfo,
                    ]);
                }
            }

            Log::info('Importación de vehículos completada', $results);

        } catch (\Exception $e) {
            Log::error('Error en importación de vehículos: '.$e->getMessage());
            throw $e;
        }

        return $results;
    }

    /**
     * Obtiene las estadísticas GPS de los dispositivos móviles
     */
    public function getGpsStatistics(array $mobileVehicleIndexCodes, string $startTime, string $endTime, int $pageNo = 1, int $pageSize = 100): array
    {
        $path = '/artemis/api/mobilesurveillance/v1/gpsDetails';
        $url = $this->baseUrl.$path;
        $accept = 'application/json';

        $timestamp = round(microtime(true) * 1000);
        $contentType = 'application/json';
        $signature = $this->signRequest('POST', $accept, $contentType, $path, $timestamp);

        // Convertir array de códigos a string separado por comas
        $indexCodesString = implode(',', $mobileVehicleIndexCodes);

        $requestBody = [
            'pageNo' => $pageNo,
            'pageSize' => $pageSize,
            'mobilevehicleIndexCodes' => $indexCodesString,
            'startTime' => $startTime,
            'endTime' => $endTime,
        ];

        Log::info('Solicitando datos GPS', [
            'vehicles' => $indexCodesString,
            'startTime' => $startTime,
            'endTime' => $endTime,
        ]);

        $response = Http::withOptions(['verify' => false])
            ->timeout(30) // Aumentar timeout para esta solicitud
            ->withHeaders([
                'Accept' => $accept,
                'Content-Type' => $contentType,
                'x-ca-key' => $this->apiKey,
                'x-ca-signature' => $signature,
                'x-ca-timestamp' => $timestamp,
            ])->post($url, $requestBody);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['code']) && $data['code'] === '0') {
                Log::info('Datos GPS obtenidos exitosamente', [
                    'total' => $data['data']['total'] ?? 0,
                    'vehicles' => count($data['data']['list'] ?? []),
                ]);

                return $data['data'] ?? [];
            }

            Log::error('Error en respuesta GPS', ['response' => $data]);
            throw new \Exception('API devolvió código de error: '.($data['code'] ?? 'unknown'));
        }

        Log::error('Error en solicitud GPS', ['response' => $response->body()]);
        throw new \Exception('Error obteniendo datos GPS: '.$response->body());
    }

    /**
     * Obtiene la última ubicación conocida de cada vehículo
     */
    public function getLatestGpsLocations(array $mobileVehicleIndexCodes): array
    {
        $timezone = '-03:00';

        $today = now();
        $startTime = $today->copy()->startOfDay()->format('Y-m-d\TH:i:s').$timezone;
        $endTime = $today->copy()->endOfDay()->format('Y-m-d\TH:i:s').$timezone;

        Log::debug('Solicitando ubicaciones GPS de HOY', [
            'vehicles_count' => count($mobileVehicleIndexCodes),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'timezone' => $timezone,
            'today' => $today->format('Y-m-d'),
            'today_argentina' => $today->setTimezone('America/Argentina/Buenos_Aires')->format('Y-m-d H:i:s'),
        ]);

        try {
            $gpsData = $this->getGpsStatistics($mobileVehicleIndexCodes, $startTime, $endTime, 1, 5);

            $locations = [];
            $latestTimestamp = null;

            if (! empty($gpsData['list'])) {
                foreach ($gpsData['list'] as $vehicleData) {
                    $vehicleCode = $vehicleData['mobilevehicleIndexCode'];

                    // Obtener el último punto GPS (más reciente)
                    if (! empty($vehicleData['gpsInfo'])) {
                        $gpsInfo = $vehicleData['gpsInfo'];
                        usort($gpsInfo, function ($a, $b) {
                            return strtotime($b['occurTime']) - strtotime($a['occurTime']);
                        });

                        $latestGps = $gpsInfo[0];

                        $gpsTime = strtotime($latestGps['occurTime']);
                        if ($latestTimestamp === null || $gpsTime > $latestTimestamp) {
                            $latestTimestamp = $gpsTime;
                        }

                        $locations[$vehicleCode] = [
                            'longitude' => $latestGps['longitude'],
                            'latitude' => $latestGps['latitude'],
                            'occurTime' => $latestGps['occurTime'],
                            'direction' => $latestGps['direction'],
                            'speed' => $latestGps['speed'],
                            'plateNo' => $vehicleData['plateNo'] ?? '',
                            'regionIndexCode' => $vehicleData['regionIndexCode'] ?? '',
                            'timestamp' => $gpsTime,
                        ];

                        Log::debug('Datos GPS para vehículo', [
                            'vehicle' => $vehicleCode,
                            'time' => $latestGps['occurTime'],
                            'coordinates' => $latestGps['latitude'].', '.$latestGps['longitude'],
                        ]);
                    } else {
                        Log::warning('Vehículo sin datos GPS HOY', ['vehicle' => $vehicleCode]);
                    }
                }
            } else {
                Log::warning('No se encontraron datos GPS en la respuesta');
            }

            return [
                'locations' => $locations,
                'latest_timestamp' => $latestTimestamp,
            ];

        } catch (\Exception $e) {
            Log::error('Error obteniendo ubicaciones GPS: '.$e->getMessage());

            return [
                'locations' => [],
                'latest_timestamp' => null,
            ];
        }
    }

    public function getCrossRecords(
        string $cameraIndexCode = '101',
        string $plateNo = '',
        string $ownerName = '',
        string $contact = '',
        ?string $startTime = null,
        ?string $endTime = null,
        int $pageNo = 1,
        int $pageSize = 10,
        string $sortField = 'PassTime',
        int $orderType = 1
    ): array {
        $path = '/artemis/api/pms/v1/crossRecords/page';
        $url = $this->baseUrl.$path;
        $accept = 'application/json';

        $timestamp = round(microtime(true) * 1000);
        $contentType = 'application/json';
        $signature = $this->signRequest('POST', $accept, $contentType, $path, $timestamp);

        // Si no se proporcionan fechas, usar últimas 24 horas
        if ($startTime === null) {
            $startTime = now()->subDay()->setTimezone('+08:00')->format('Y-m-d\TH:i:sP');
        }

        if ($endTime === null) {
            $endTime = now()->setTimezone('+08:00')->format('Y-m-d\TH:i:sP');
        }

        $requestBody = [
            'cameraIndexCode' => $cameraIndexCode,
            'plateNo' => $plateNo,
            'ownerName' => $ownerName,
            'contact' => $contact,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'pageNo' => $pageNo,
            'pageSize' => $pageSize,
            'sortField' => $sortField,
            'orderType' => $orderType,
        ];

        Log::info('Solicitando registros de cruce', [
            'camera' => $cameraIndexCode,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'page' => $pageNo,
        ]);

        $response = Http::withOptions(['verify' => false])
            ->timeout(60)
            ->withHeaders([
                'Accept' => $accept,
                'Content-Type' => $contentType,
                'x-ca-key' => $this->apiKey,
                'x-ca-signature' => $signature,
                'x-ca-timestamp' => $timestamp,
            ])->post($url, $requestBody);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['code']) && $data['code'] === '0') {
                Log::info('Registros de cruce obtenidos exitosamente', [
                    'total' => $data['data']['total'] ?? 0,
                    'current_page' => $data['data']['pageNo'] ?? 1,
                    'records_count' => count($data['data']['list'] ?? []),
                ]);

                return $data['data'] ?? [];
            }

            Log::error('Error en respuesta de registros de cruce', ['response' => $data]);
            throw new \Exception('API devolvió código de error: '.($data['code'] ?? 'unknown'));
        }

        Log::error('Error en solicitud de registros de cruce', ['response' => $response->body()]);
        throw new \Exception('Error obteniendo registros de cruce: '.$response->body());
    }

    /**
     * Importa todos los registros de cruce paginando
     */
    public function importCrossRecords(?string $startTime = null, ?string $endTime = null): array
    {
        $results = [
            'total_imported' => 0,
            'total_updated' => 0,
            'total_pages' => 0,
            'total_records' => 0,
            'imported_records' => [],
            'errors' => [],
        ];

        try {
            $pageNo = 1;
            $pageSize = 100; // Máximo por página

            do {
                Log::info("Obteniendo página {$pageNo} de registros de cruce");

                $crossRecordsData = $this->getCrossRecords(
                    cameraIndexCode: '101',
                    startTime: $startTime,
                    endTime: $endTime,
                    pageNo: $pageNo,
                    pageSize: $pageSize
                );

                if (empty($crossRecordsData['list'])) {
                    break;
                }

                // Guardar el total en la primera página
                if ($pageNo === 1) {
                    $results['total_records'] = $crossRecordsData['total'] ?? 0;
                }

                // Procesar cada registro
                foreach ($crossRecordsData['list'] as $record) {
                    try {
                        // Convertir el tiempo de cruce a formato correcto
                        $crossTime = $record['crossTime'] ?? $record['cross_time'] ?? null;

                        $recordData = [
                            'cross_record_syscode' => $record['crossRecordSyscode'],
                            'camera_index_code' => $record['cameraIndexCode'],
                            'plate_no' => $record['plateNo'],
                            'owner_name' => $record['ownerName'] ?? '',
                            'contact' => $record['contact'] ?? '',
                            'vehicle_pic_uri' => $record['vehiclePicUri'] ?? '',
                            'cross_time' => $crossTime,
                            'vehicle_color' => $record['vehicleColor'] ?? null,
                            'vehicle_type' => $record['vehicleType'] ?? null,
                            'country' => $record['country'] ?? null,
                            'vehicle_direction_type' => $record['vehicleDirectionType'] ?? null,
                            'vehicle_brand' => $record['vehicleBrand'] ?? null,
                            'vehicle_speed' => $record['vehicleSpeed'] ?? null,
                        ];

                        // Usar updateOrCreate para evitar duplicados
                        $savedRecord = AnprPassingRecord::updateOrCreate(
                            ['cross_record_syscode' => $recordData['cross_record_syscode']],
                            $recordData
                        );

                        $results['imported_records'][] = [
                            'id' => $savedRecord->id,
                            'cross_record_syscode' => $savedRecord->cross_record_syscode,
                            'plate_no' => $savedRecord->plate_no,
                            'vehicle_pic_uri' => $savedRecord->vehicle_pic_uri,
                            'cross_time' => $savedRecord->cross_time,
                            'camera_index_code' => $savedRecord->camera_index_code,
                        ];

                        $results['total_imported']++;

                    } catch (\Exception $e) {
                        $results['errors'][] = [
                            'record' => $record['crossRecordSyscode'] ?? 'Unknown',
                            'error' => $e->getMessage(),
                        ];
                        Log::error('Error procesando registro: '.$e->getMessage(), [
                            'record_data' => $record,
                        ]);
                    }
                }

                $pageNo++;
                $results['total_pages']++;

                // Verificar si hay más páginas
                $currentCount = $pageNo * $pageSize;
                $totalRecords = $crossRecordsData['total'] ?? 0;

            } while ($currentCount < $totalRecords);

            Log::info('Importación de registros de cruce completada', [
                'total_imported' => $results['total_imported'],
                'imported_records_count' => count($results['imported_records']),
                'total_pages' => $results['total_pages'],
            ]);

        } catch (\Exception $e) {
            Log::error('Error en importación de registros de cruce: '.$e->getMessage());
            throw $e;
        }

        return $results;
    }
}
