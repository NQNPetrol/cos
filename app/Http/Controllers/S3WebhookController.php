<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Cache;

class S3WebhookController extends Controller
{
    private $filePatternsCache;
    private const CACHE_KEY = 's3_files_pattern_index';
    private const CACHE_TTL = 3600; // 1 hora

    public function __construct()
    {
        // Inicializar el índice en memoria
        $this->loadPatternsCache();
    }

    public function handleWebhook(Request $request)
    {
        $validated = $request->validate([
            'bucket' => 'required|string',
            'key' => 'required|string', 
            'file_name' => 'required|string',
            'size' => 'sometimes|string',
            'event_time' => 'sometimes|string'
        ]);

        Log::info('Webhook S3 recibido', $validated);

        try {
            $result = $this->downloadWithReplacement($validated);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Archivo procesado correctamente',
                'data' => [
                    'local_path' => $result['local_path'],
                    'replaced_files' => $result['replaced_files'],
                    'file_info' => $validated
                ]
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            Log::error('Error procesando webhook S3: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error procesando el archivo: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function downloadWithReplacement(array $fileData)
    {
        $patterns = $this->extractPatterns($fileData['file_name']);
        $mission = $patterns['mission'];
        
        // LOCK por misión específica
        $lockKey = "mission_lock_{$mission}";
        $lock = Cache::lock($lockKey, 30);
        
        if (!$lock->get()) {
            Log::warning("Timeout esperando lock para misión: {$mission}");
            throw new \Exception("Sistema ocupado procesando misión {$mission}, reintente más tarde");
        }
        
        try {
            // ESTRATEGIA CORREGIDA: Descargar primero, luego limpiar duplicados
            return $this->processMissionFileSafe($fileData, $patterns);
        } finally {
            $lock->release();
        }
    }

    private function processMissionFileSafe(array $fileData, array $patterns)
    {
        // Verificar que existe en S3
        $this->verifyS3FileExists($fileData['bucket'], $fileData['key']);
        
        // primero descarga
        $result = $this->downloadNewFile($fileData['bucket'], $fileData['key'], $fileData['file_name']);

        $this->updatePatternsCache($result['local_path'], $patterns, 'add');
        
        // filtra x mision
        $allMissionFiles = $this->findAllMissionFiles($patterns['mission']);
        
        // elimina duplicados con misma sequencia
        $deletedFiles = $this->cleanupMissionDuplicates($allMissionFiles);
        
        Log::info("Procesamiento completado para misión {$patterns['mission']}: " . 
                 count($deletedFiles) . " archivos eliminados");

        return [
            'local_path' => $result['local_path'],
            'replaced_files' => $deletedFiles,
            'mission' => $patterns['mission'],
            'files_remaining' => count($allMissionFiles) - count($deletedFiles)
        ];
    }

    private function findAllMissionFiles(string $mission): array
    {
        $missionFiles = [];
        
        foreach ($this->filePatternsCache as $cachedFile => $cachedPatterns) {
            if ($cachedPatterns['mission'] === $mission && 
                Storage::disk('local')->exists($cachedFile)) {
                $missionFiles[] = [
                    'path' => $cachedFile,
                    'patterns' => $cachedPatterns,
                    'timestamp' => $cachedPatterns['timestamp'],
                    'last_modified' => Storage::lastModified($cachedFile)
                ];
            }
        }
        
        return $missionFiles;
    }

    private function cleanupMissionDuplicates(array $missionFiles): array
    {
        if (count($missionFiles) <= 1) {
            return []; // No hay duplicados
        }
        
        // Agrupar por combinación única de patrones (excluyendo timestamp)
        $groups = [];
        foreach ($missionFiles as $fileInfo) {
            $groupKey = $this->createGroupKey($fileInfo['patterns']);
            $groups[$groupKey][] = $fileInfo;
        }
        
        $deletedFiles = [];
        
        // Para cada grupo, mantener solo el más reciente
        foreach ($groups as $groupFiles) {
            if (count($groupFiles) > 1) {
                // Ordenar por timestamp descendente (más reciente primero)
                usort($groupFiles, function($a, $b) {
                    return $b['timestamp'] <=> $a['timestamp'];
                });
                
                // Mantener el primero (más reciente), eliminar el resto
                $filesToDelete = array_slice($groupFiles, 1);
                foreach ($filesToDelete as $fileToDelete) {
                    try {
                        Storage::disk('local')->delete($fileToDelete['path']);
                        $this->updatePatternsCache($fileToDelete['path'], $fileToDelete['patterns'], 'remove');
                        $deletedFiles[] = $fileToDelete['path'];
                        Log::info("🗑️ Duplicado eliminado: {$fileToDelete['path']}");
                    } catch (\Exception $e) {
                        Log::warning("No se pudo eliminar duplicado: {$fileToDelete['path']}");
                    }
                }
            }
        }
        
        return $deletedFiles;
    }

    private function extractPatterns(string $fileName): array
    {
        // Ejemplo: DJI_20251014105939_0001_V_mision-rodial.jpeg
        $pattern = '/^([A-Z]+)_(\d+)_(\d+)_([A-Z])_(.+)\.([a-zA-Z0-9]+)$/';
        
        if (preg_match($pattern, $fileName, $matches)) {
            return [
                'prefix' => $matches[1],     // DJI
                'timestamp' => $matches[2],  // 20251014105939
                'sequence' => $matches[3],   // 0001
                'version' => $matches[4],    // V
                'mission' => $matches[5],    // mision-rodial
                'extension' => $matches[6]   // jpeg
            ];
        }
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $nameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);
        
        return [
            'prefix' => 'unknown',
            'timestamp' => 'unknown',
            'sequence' => 'unknown', 
            'version' => 'unknown',
            'mission' => $nameWithoutExt,
            'extension' => $extension
        ];
    }

    private function verifyS3FileExists(string $bucket, string $key): void
    {
        $s3Client = $this->getS3Client();
        
        try {
            $s3Client->headObject([
                'Bucket' => $bucket,
                'Key' => $key
            ]);
            Log::info("Archivo verificado en S3: {$bucket}/{$key}");
        } catch (AwsException $e) {
            Log::error("Archivo no encontrado en S3: {$bucket}/{$key}");
            throw new \Exception("El archivo no existe en S3: {$key}");
        }
    }

    private function findExistingFilesByPatterns(array $patterns): array
    {
        $matchingFiles = [];
        
        foreach ($this->filePatternsCache as $cachedFile => $cachedPatterns) {
            if ($this->patternsMatch($cachedPatterns, $patterns)) {
                // Verificar que el archivo aún existe físicamente
                if (Storage::disk('local')->exists($cachedFile)) {
                    $matchingFiles[] = $cachedFile;
                } else {
                    // Limpiar entrada de cache si el archivo no existe
                    $this->updatePatternsCache($cachedFile, $cachedPatterns, 'remove');
                }
            }
        }

        return $matchingFiles;
    }

     private function patternsMatch(array $patterns1, array $patterns2): bool
    {
        return $patterns1['prefix'] === $patterns2['prefix'] &&
               $patterns1['sequence'] === $patterns2['sequence'] &&
               $patterns1['version'] === $patterns2['version'] &&
               $patterns1['mission'] === $patterns2['mission'] &&
               $patterns1['extension'] === $patterns2['extension'];
    }

    private function createGroupKey(array $patterns): string
    {
        return "{$patterns['prefix']}_{$patterns['sequence']}_{$patterns['version']}_{$patterns['mission']}_{$patterns['extension']}";
    }

    private function createBackupInfo(array $existingFiles): array
    {
        $backupInfo = [];
        
        foreach ($existingFiles as $file) {
            $backupInfo[$file] = [
                'patterns' => $this->filePatternsCache[$file] ?? $this->extractPatterns(basename($file)),
                'size' => Storage::size($file),
                'last_modified' => Storage::lastModified($file)
            ];
        }
        
        return $backupInfo;
    }

    private function deleteExistingFiles(array $files): array
    {
        $deletedFiles = [];
        
        foreach ($files as $file) {
            try {
                Storage::disk('local')->delete($file);
                $this->updatePatternsCache($file, $this->filePatternsCache[$file] ?? [], 'remove');
                $deletedFiles[] = $file;
                Log::info("🗑️ Archivo eliminado: {$file}");
            } catch (\Exception $e) {
                Log::warning("No se pudo eliminar archivo: {$file} - " . $e->getMessage());
            }
        }
        
        return $deletedFiles;
    }

    private function downloadNewFile(string $bucket, string $key, string $fileName)
    {
        $s3Client = $this->getS3Client();
        
        // Determinar la ruta local
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $localDirectory = $this->getLocalDirectory($fileExtension);
        $localPath = $localDirectory . '/' . $fileName;

        // Crear directorio si no existe
        if (!Storage::disk('public')->exists($localDirectory)) {
            Storage::disk('public')->makeDirectory($localDirectory, 0755, true);
        }

        $fullLocalPath = Storage::disk('public')->path($localPath);

        // Descargar archivo
        $result = $s3Client->getObject([
            'Bucket' => $bucket,
            'Key' => $key,
            'SaveAs' => $fullLocalPath
        ]);

        // Verificar que se descargó correctamente
        if (!file_exists($fullLocalPath)) {
            throw new \Exception("El archivo no se guardó localmente después de la descarga");
        }

        $fileSize = filesize($fullLocalPath);
        Log::info("✅ Nuevo archivo descargado: {$localPath} ({$fileSize} bytes)");

        return [
            'local_path' => $localPath,
            'full_path' => $fullLocalPath,
            'size' => $fileSize,
            'mime_type' => $result->get('ContentType')
        ];
    }

    private function handleDownloadFailure(array $backupInfo, array $patterns, string $fileName, \Exception $error): void
    {
        Log::error("Fallo en descarga, restaurando estado: " . $error->getMessage());
        
        // Limpiar cualquier archivo parcial descargado
        $localDirectory = $this->getLocalDirectory($patterns['extension']);
        $partialFile = $localDirectory . '/' . $fileName;
        
        if (Storage::disk('local')->exists($partialFile)) {
            Storage::disk('local')->delete($partialFile);
            $this->updatePatternsCache($partialFile, $patterns, 'remove');
        }

        // Restaurar entradas en el cache para archivos que no fueron eliminados
        // (En este caso, como eliminamos antes de descargar, no podemos restaurar los archivos físicos,
        // pero mantenemos el cache consistente con lo que existe físicamente)
        
        Log::warning("Estado después del fallo: Archivos eliminados no se pueden restaurar, pero el cache está limpio");
    }

    private function loadPatternsCache(): void
    {
        $this->filePatternsCache = Cache::get(self::CACHE_KEY, []);
        
        // Si el cache está vacío, construirlo desde los archivos físicos
        if (empty($this->filePatternsCache)) {
            $this->rebuildPatternsCache();
        }
    }

     private function rebuildPatternsCache(): void
    {
        $this->filePatternsCache = [];
        
        $directories = ['s3-images', 's3-videos', 's3-files'];
        
        foreach ($directories as $directory) {
            if (Storage::disk('local')->exists($directory)) {
                $files = Storage::disk('local')->allFiles($directory);
                foreach ($files as $file) {
                    $patterns = $this->extractPatterns(basename($file));
                    $this->filePatternsCache[$file] = $patterns;
                }
            }
        }
        
        Cache::put(self::CACHE_KEY, $this->filePatternsCache, self::CACHE_TTL);
        Log::info("Índice de patrones reconstruido: " . count($this->filePatternsCache) . " archivos");
    }

    private function updatePatternsCache(string $filePath, array $patterns, string $action): void
    {
        switch ($action) {
            case 'add':
                $this->filePatternsCache[$filePath] = $patterns;
                break;
            case 'remove':
                unset($this->filePatternsCache[$filePath]);
                break;
        }
        
        // Actualizar cache persistente
        Cache::put(self::CACHE_KEY, $this->filePatternsCache, self::CACHE_TTL);
    }

    private function getS3Client(): S3Client
    {
        return new S3Client([
            'region' => 'sa-east-1',
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
            'http' => ['verify' => false]
        ]);
    }
    
    private function downloadFromS3(array $fileData)
    {
        return $this->downloadWithReplacement($fileData);
    }

    private function getLocalDirectory(string $fileExtension): string
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'];

        if (in_array($fileExtension, $imageExtensions)) {
            return 's3-images';
        } elseif (in_array($fileExtension, $videoExtensions)) {
            return 's3-videos';
        } else {
            return 's3-files';
        }
    }

    public function listDownloadedFiles(Request $request)
    {
        $type = $request->get('type', 'all');
        
        $directories = [
            'images' => 's3-images',
            'videos' => 's3-videos', 
            'files' => 's3-files',
            'all' => ['s3-images', 's3-videos', 's3-files']
        ];

        $directoriesToScan = (array) ($directories[$type] ?? 's3-images');
        $allFiles = [];

        foreach ($directoriesToScan as $dir) {
            if (Storage::disk('local')->exists($dir)) {
                $files = Storage::disk('local')->files($dir);
                foreach ($files as $file) {
                    $allFiles[] = [
                        'name' => basename($file),
                        'path' => $file,
                        'url' => Storage::url($file),
                        'size' => Storage::size($file),
                        'last_modified' => date('Y-m-d H:i:s', Storage::lastModified($file)),
                        'patterns' => $this->filePatternsCache[$file] ?? $this->extractPatterns(basename($file))
                    ];
                }
            }
        }

        return response()->json([
            'files' => $allFiles,
            'count' => count($allFiles),
            'cache_size' => count($this->filePatternsCache)
        ]);
    }

    public function rebuildCache(Request $request)
    {
        $this->rebuildPatternsCache();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Índice reconstruido',
            'cache_entries' => count($this->filePatternsCache)
        ]);
    }

    public function cacheStatus(Request $request)
    {
        return response()->json([
            'cache_entries' => count($this->filePatternsCache),
            'cache_key' => self::CACHE_KEY,
            'cache_ttl' => self::CACHE_TTL,
            'sample_entries' => array_slice($this->filePatternsCache, 0, 5, true)
        ]);
    }
}
