<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HikCentralImageService
{
    private $baseUrl;
    private $apiKey;
    private $apiSecret;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('HIKCENTRAL_URL', ''), '/');
        $this->apiKey = env('HIKCENTRAL_API_KEY');
        $this->apiSecret = env('HIKCENTRAL_API_SECRET');

        Log::info('[IMAGE_SERVICE_INIT] HikCentralImageService inicializado', [
            'base_url' => $this->baseUrl,
            'api_key_set' => !empty($this->apiKey)
        ]);
        
    }

    /**
     * Obtener el path de la imagen desde HikCentral API
     */
    public function fetchImagePathFromHikCentral(string $picUri): ?string
    {
        try {
            $path = '/artemis/api/acs/v1/event/pictures';
            $url = $this->baseUrl . $path;
            $accept = 'application/json';
            
            $timestamp = round(microtime(true) * 1000);
            $contentType = 'application/json';
            $signature = $this->signRequest('POST', $accept, $contentType, $path, $timestamp);
            
            Log::info('[IMAGE_API_REQUEST] Solicitando imagen a HikCentral API', [
                'pic_uri' => $picUri,
                'url' => $url,
                'timestamp' => $timestamp
            ]);

            $response = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'Accept' => $accept,
                    'Content-Type' => $contentType,
                    'x-ca-key' => $this->apiKey,
                    'x-ca-signature' => $signature,
                    'x-ca-timestamp' => $timestamp,
                    'domainId' => '0' 
                ])
                ->timeout(15)
                ->post($url, [
                    'picUri' => $picUri
                ]);

            if ($response->successful()) {
                $imagePath = $response->body();
                
                Log::info('Path de imagen obtenido exitosamente', [
                    'pic_uri' => $picUri,
                    'path_length' => strlen($imagePath),
                    'path_preview' => substr($imagePath, 0, 100) . '...'
                ]);

                return $imagePath;
            }

            Log::warning('Respuesta no exitosa de HikCentral API', [
                'pic_uri' => $picUri,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching image path from HikCentral', [
                'pic_uri' => $picUri,
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

   private function signRequest(string $method, string $accept, string $contentType, string $path, int $timestamp): string
    {
        $stringToSign = strtoupper($method) . "\n" .
                    $accept . "\n" .
                    $contentType . "\n" .
                    $path;

        return base64_encode(hash_hmac('sha256', $stringToSign, $this->apiSecret, true));
    }


}
