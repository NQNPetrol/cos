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
        $this->apiKey    = env('HIKCENTRAL_API_KEY');
        $this->apiSecret = env('HIKCENTRAL_API_SECRET');
    }

    protected function signRequest(string $method, string $contentType, string $path, int $timestamp): string
    {
        $stringToSign = strtoupper($method) . "\n" .
                    "" . "\n" .
                    $contentType . "\n" .
                    "x-ca-key:" . $this->apiKey . "\n" .
                    "x-ca-timestamp:" . $timestamp . "\n" .
                    $path;

    return base64_encode(hash_hmac('sha256', $stringToSign, $this->apiSecret, true));
    }

    public function getEncodingDevices(int $pageNo = 1, int $pageSize = 50): array
    {
        $path = '/artemis/api/resource/v1/encodeDevice/encodeDeviceList';
        $url  = $this->baseUrl.$path;

        $timestamp  = round(microtime(true) * 1000);
        $contentType = 'application/json;charset=UTF-8';
        $signature = $this->signRequest('POST', $contentType, $path, $timestamp);

        $response = Http::withOptions(['verify' => false])
         ->withHeaders([
            'Accept'          => '*/*',
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
}
