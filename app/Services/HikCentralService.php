<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class HikCentralService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $apiSecret;

    public function __construct()
    {
        $this->baseUrl   = rtrim(env('HIKCENTRAL_URL', ''), '/');
        $apiKey = env('HIKCENTRAL_API_KEY');  
        $apiSecret = env('HIKCENTRAL_API_SECRET');

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
        // Método para obtener las cámaras con la relación correcta
        $path = '/artemis/api/resource/v1/cameras';
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
            'pageNo' => 1,
            'pageSize' => 100,
            'siteIndexCode' => '0',
            'deviceType' => 'encodeDevice',
            'bRecordSetting' => 1
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['code']) && $data['code'] === '0') {
                return $data['data']['list'] ?? [];
            }
        }

        throw new \Exception('Error obteniendo lista de cámaras: '.$response->body());
    }
}
