<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HikCentralImageService
{
    private $baseUrl;
    private $appKey;
    private $appSecret;

    public function __construct()
    {
        $this->baseUrl = 'http://190.93.206.216:9016/artemis';
        $this->appKey = env('HIKCENTRAL_APP_KEY');
        $this->appSecret = env('HIKCENTRAL_APP_SECRET');
    }

    /**
     * Obtener el path de la imagen desde HikCentral API
     */
    public function fetchImagePathFromHikCentral(string $picUri): ?string
    {
        try {
            $headers = $this->generateAuthHeaders();
            
            Log::info('Solicitando imagen a HikCentral API', [
                'pic_uri' => $picUri,
                'url' => $this->baseUrl . '/api/pms/v1/image'
            ]);

            $response = Http::withOptions(['verify' => false])
                ->withHeaders($headers)
                ->timeout(15)
                ->post($this->baseUrl . '/api/pms/v1/image', [
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

    /**
     * Generar headers de autenticación AK/SK
     */
    private function generateAuthHeaders(): array
    {
        $timestamp = round(microtime(true) * 1000);
        $nonce = uniqid();
        
        $signature = base64_encode(hash_hmac('sha256', 
            $this->appKey . $timestamp . $nonce, 
            $this->appSecret, 
            true
        ));

        return [
            'Content-Type' => 'application/json',
            'appKey' => $this->appKey,
            'timestamp' => $timestamp,
            'nonce' => $nonce,
            'signature' => $signature,
        ];
    }


}
