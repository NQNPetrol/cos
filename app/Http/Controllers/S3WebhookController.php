<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class S3WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Validar el request
        $validated = $request->validate([
            'bucket' => 'required|string',
            'key' => 'required|string',
            'file_name' => 'required|string',
            'size' => 'sometimes|string',
            'event_time' => 'sometimes|string'
        ]);

        Log::info('Webhook S3 recibido', $validated);

        try {
            // Procesar y descargar el archivo
            $result = $this->downloadFromS3($validated);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Archivo procesado correctamente',
                'local_path' => $result['local_path'],
                'file_info' => $validated
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            Log::error('Error procesando webhook S3: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Error procesando el archivo: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function downloadFromS3(array $fileData)
    {
        // Configurar cliente S3
        $s3Client = new S3Client([
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $bucket = $fileData['bucket'];
        $key = $fileData['key'];
        $fileName = $fileData['file_name'];

        // Determinar la ruta local basada en el tipo de archivo
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $localDirectory = $this->getLocalDirectory($fileExtension);
        $localPath = $localDirectory . '/' . $fileName;

        // Crear directorio si no existe
        if (!Storage::disk('local')->exists($localDirectory)) {
            Storage::disk('local')->makeDirectory($localDirectory);
        }

        // Descargar archivo desde S3
        try {
            $result = $s3Client->getObject([
                'Bucket' => $bucket,
                'Key' => $key,
                'SaveAs' => storage_path('app/' . $localPath)
            ]);

            Log::info("Archivo descargado exitosamente: {$localPath}");

            return [
                'local_path' => $localPath,
                'size' => $result->get('ContentLength'),
                'mime_type' => $result->get('ContentType')
            ];

        } catch (AwsException $e) {
            Log::error('Error descargando de S3: ' . $e->getMessage());
            throw new \Exception('No se pudo descargar el archivo desde S3: ' . $e->getMessage());
        }
    }

    private function getLocalDirectory(string $fileExtension): string
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'];

        if (in_array($fileExtension, $imageExtensions)) {
            return 'public/s3-images';
        } elseif (in_array($fileExtension, $videoExtensions)) {
            return 'public/s3-videos';
        } else {
            return 'public/s3-files';
        }
    }

    // Método para listar archivos descargados
    public function listDownloadedFiles(Request $request)
    {
        $type = $request->get('type', 'images'); // images, videos, files
        
        $directories = [
            'images' => 's3-images',
            'videos' => 's3-videos', 
            'files' => 's3-files'
        ];

        $directory = $directories[$type] ?? 's3-files';
        $files = Storage::disk('local')->files('public/' . $directory);

        $fileList = array_map(function($file) {
            return [
                'name' => basename($file),
                'path' => $file,
                'url' => Storage::url($file),
                'size' => Storage::size($file),
                'last_modified' => Storage::lastModified($file)
            ];
        }, $files);

        return response()->json([
            'files' => $fileList,
            'count' => count($files)
        ]);
    }
}
