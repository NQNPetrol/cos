<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GalleryService
{
    private const CACHE_KEY = 'gallery_organization';
    private const CACHE_TTL = 1800; // 30 minutos

    /**
     * Obtener galería organizada por drone → cliente → misión
     */
    public function getOrganizedGallery(array $filters = [])
    {
        Log::info('GALLERY SERVICE: Iniciando getOrganizedGallery');
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function() use ($filters) {
            $images = $this->getAllImages();
            $videos = $this->getAllVideos();
            
            return $this->organizeMedia($images, $videos, $filters);
        });
    }

    /**
     * Obtener todas las imágenes del storage
     */
    private function getAllImages()
    {
        $images = [];
        $directory = 's3-images';

        Log::info("GALLERY SERVICE: Buscando imágenes en directorio: s3-images");
        
        if (Storage::disk('public')->exists('s3-images')) {
            $files = Storage::disk('public')->files('s3-images');
            Log::info("GALLERY SERVICE: Archivos encontrados en s3-images: " . count($files));
            
            foreach ($files as $file) {
                $filename = basename($file);
                $patterns = $this->extractPatterns($filename);

                $physicalPath = Storage::disk('public')->path($file);
                $fileExists = file_exists($physicalPath);
                $fileSize = Storage::disk('public')->size($file);
                $fileUrl = Storage::url($file);
                $lastModified = Storage::disk('public')->lastModified($file);

                Log::info("GALLERY SERVICE: Procesando imagen - Archivo: {$filename}");
                Log::info("Ruta física: {$physicalPath}");
                Log::info("Existe físicamente: " . ($fileExists ? 'SÍ' : 'NO'));
                Log::info("Tamaño: {$fileSize} bytes");
                Log::info("URL Storage: {$fileUrl}");
                Log::info("Patrones: " . json_encode($patterns));
                
                $images[] = [
                    'filename' => $filename,
                    'path' => $file,
                    'url' => Storage::url($file),
                    'size' => $fileSize,
                    'last_modified' => $lastModified,
                    'patterns' => $patterns,
                    'type' => 'image',
                    'mission_key' => $this->createMissionKey($patterns)
                ];
            }
        } else {
           Log::warning("GALLERY SERVICE: El directorio {$directory} NO existe en storage");
        }
        Log::info("GALLERY SERVICE: Total imágenes procesadas: " . count($images));
        return $images;
    }

    /**
     * Obtener todos los videos del storage
     */
    private function getAllVideos()
    {
        $videos = [];
        $directory = 's3-videos';
        
        Log::info("GALLERY SERVICE: Buscando videos en directorio: {$directory}");
        
        if (Storage::disk('public')->exists('s3-videos')) {
            $files = Storage::disk('public')->files('s3-videos');
            Log::info("GALLERY SERVICE: Archivos encontrados en {$directory}: " . count($files));
            
            foreach ($files as $file) {
                $filename = basename($file);
                $patterns = $this->extractPatterns($filename);

                $physicalPath = Storage::disk('public')->path($file);
                $fileExists = file_exists($physicalPath);
                $fileSize = Storage::disk('public')->size($file);
                $fileUrl = Storage::url($file);
                $lastModified = Storage::disk('public')->lastModified($file);

                Log::info("GALLERY SERVICE: Procesando video - Archivo: {$filename}");
                Log::info("Ruta física: {$physicalPath}");
                Log::info("Existe físicamente: " . ($fileExists ? 'SÍ' : 'NO'));
                Log::info("Tamaño: {$fileSize} bytes");
                Log::info("URL Storage: {$fileUrl}");
                
                $videos[] = [
                    'filename' => $filename,
                    'path' => $file,
                    'url' => $fileUrl,
                    'size' => $fileSize,
                    'last_modified' => $lastModified,
                    'patterns' => $patterns,
                    'type' => 'video',
                    'mission_key' => $this->createMissionKey($patterns)
                ];
            }
        } else {
            Log::warning("GALLERY SERVICE: El directorio {$directory} NO existe en storage");
        }
        Log::info("GALLERY SERVICE: Total videos procesados: " . count($videos));
        return $videos;
    }

    /**
     * Organizar media en estructura jerárquica
     */
    private function organizeMedia(array $images, array $videos, array $filters)
    {
        Log::info("GALLERY SERVICE: Organizando media - Imágenes: " . count($images) . ", Videos: " . count($videos));
        $organized = [
            'drones' => [],
            'clients' => [],
            'missions' => [],
            'stats' => [
                'total_images' => count($images),
                'total_videos' => count($videos),
                'total_missions' => 0
            ]
        ];

        $allMedia = array_merge($images, $videos);
        Log::info("GALLERY SERVICE: Total media a organizar: " . count($allMedia));
        
        foreach ($allMedia as $index => $media) {
            $patterns = $media['patterns'];
            Log::info("GALLERY SERVICE: Procesando media {$index}/" . count($allMedia) . " - {$media['filename']}");
            
            // Aplicar filtros
            if ($this->shouldFilter($patterns, $filters)) {
                Log::info("GALLERY SERVICE: Media filtrada - {$media['filename']}");
                continue;
            }
            
            $drone = $patterns['prefix'] ?? 'unknown';
            $client = $this->extractClientFromMission($patterns['mission']);
            $mission = $patterns['mission'];
            $missionKey = $media['mission_key'];

            Log::info("GALLERY SERVICE: Asignando - Drone: {$drone}, Cliente: {$client}, Misión: {$mission}");

            
            // Organizar por drone
            if (!isset($organized['drones'][$drone])) {
                $organized['drones'][$drone] = [
                    'name' => $drone,
                    'clients' => [],
                    'stats' => ['images' => 0, 'videos' => 0, 'missions' => 0]
                ];
            }
            
            // Organizar por cliente dentro del drone
            if (!isset($organized['drones'][$drone]['clients'][$client])) {
                $organized['drones'][$drone]['clients'][$client] = [
                    'name' => $client,
                    'missions' => [],
                    'stats' => ['images' => 0, 'videos' => 0]
                ];
            }
            
            // Organizar por misión dentro del cliente
            if (!isset($organized['drones'][$drone]['clients'][$client]['missions'][$missionKey])) {
                $organized['drones'][$drone]['clients'][$client]['missions'][$missionKey] = [
                    'name' => $mission,
                    'key' => $missionKey,
                    'drone' => $drone,
                    'client' => $client,
                    'media' => [],
                    'cover_image' => null,
                    'latest_timestamp' => $patterns['timestamp'],
                    'stats' => ['images' => 0, 'videos' => 0]
                ];
                
                $organized['stats']['total_missions']++;
            }
            
            // Agregar media a la misión
            $missionData = &$organized['drones'][$drone]['clients'][$client]['missions'][$missionKey];
            $missionData['media'][] = $media;
            
            // Actualizar estadísticas
            $missionData['stats'][$media['type'] . 's']++;
            $organized['drones'][$drone]['clients'][$client]['stats'][$media['type'] . 's']++;
            $organized['drones'][$drone]['stats'][$media['type'] . 's']++;
            
            // Establecer imagen de portada (primera imagen encontrada)
            if ($media['type'] === 'image' && !$missionData['cover_image']) {
                $missionData['cover_image'] = $media['url'];
            }
            
            // Mantener el timestamp más reciente
            if ($patterns['timestamp'] > $missionData['latest_timestamp']) {
                $missionData['latest_timestamp'] = $patterns['timestamp'];
            }
        }
        
        // Ordenar misiones por timestamp más reciente
        foreach ($organized['drones'] as &$drone) {
            foreach ($drone['clients'] as &$client) {
                uasort($client['missions'], function($a, $b) {
                    return $b['latest_timestamp'] <=> $a['latest_timestamp'];
                });
            }
        }
        Log::info("GALLERY SERVICE: Organización completada - Misiones: {$organized['stats']['total_missions']}");
        return $organized;
    }

    /**
     * Extraer cliente del nombre de misión
     * Ejemplo: "pozo-1-cliente-a" → "cliente-a"
     */
    public function extractClientFromMission(string $mission): string
    {
        // Asumimos que el cliente es la última parte después del último guión
        $parts = explode('-', $mission);
        return end($parts) ?: 'unknown';
    }

    /**
     * Crear clave única para misión
     */
    private function createMissionKey(array $patterns): string
    {
        return "{$patterns['prefix']}-{$patterns['mission']}";
    }

    /**
     * Aplicar filtros
     */
    private function shouldFilter(array $patterns, array $filters): bool
    {
        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                switch ($key) {
                    case 'drone':
                        if ($patterns['prefix'] !== $value) return true;
                        break;
                    case 'client':
                        $client = $this->extractClientFromMission($patterns['mission']);
                        if ($client !== $value) return true;
                        break;
                    case 'mission':
                        if ($patterns['mission'] !== $value) return true;
                        break;
                }
            }
        }
        
        return false;
    }

    /**
     * Reutilizar el extractor de patrones del S3WebhookController
     */
    private function extractPatterns(string $fileName): array
    {
        $pattern = '/^([A-Z]+)_(\d+)_(\d+)_([A-Z])_(.+)\.([a-zA-Z0-9]+)$/';
        
        if (preg_match($pattern, $fileName, $matches)) {
            return [
                'prefix' => $matches[1],
                'timestamp' => $matches[2],
                'sequence' => $matches[3],
                'version' => $matches[4],
                'mission' => $matches[5],
                'extension' => $matches[6]
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

    /**
     * Obtener detalles de una misión específica
     */
    public function getMissionDetails(string $drone, string $client, string $mission)
    {
        $galleryData = $this->getOrganizedGallery();
        
        $missionKey = "{$drone}-{$mission}";
        
        foreach ($galleryData['drones'] as $droneData) {
            if ($droneData['name'] === $drone) {
                foreach ($droneData['clients'] as $clientData) {
                    if ($clientData['name'] === $client) {
                        foreach ($clientData['missions'] as $missionData) {
                            if ($missionData['key'] === $missionKey) {
                                return $missionData;
                            }
                        }
                    }
                }
            }
        }
        
        return null;
    }

    /**
     * Obtener miniaturas para una misión
     */
    public function getMissionThumbnails(string $missionKey, int $limit = 12)
    {
        $galleryData = $this->getOrganizedGallery();
        
        foreach ($galleryData['drones'] as $droneData) {
            foreach ($droneData['clients'] as $clientData) {
                foreach ($clientData['missions'] as $missionData) {
                    if ($missionData['key'] === $missionKey) {
                        $images = array_filter($missionData['media'], function($media) {
                            return $media['type'] === 'image';
                        });
                        
                        return array_slice($images, 0, $limit);
                    }
                }
            }
        }
        
        return [];
    }
}
