<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class KmzParserService
{
    /**
     * Parsea un archivo KMZ y extrae los waypoints
     *
     * @param  string  $kmzPath  Ruta del archivo KMZ
     * @return array Array de waypoints con formato: [['latitud' => float, 'longitud' => float, 'altitud' => float, 'acciones' => []]]
     */
    public function parseKmzToWaypoints(string $kmzPath): array
    {
        Log::info('=== INICIO PARSEO KMZ ===');
        Log::info('Ruta del archivo KMZ:', ['path' => $kmzPath]);

        $waypoints = [];

        try {
            // El archivo se guarda en el disco 'public', así que debemos verificar ahí
            $disk = Storage::disk('public');

            // Verificar que el archivo existe
            if (! $disk->exists($kmzPath)) {
                Log::error('El archivo KMZ no existe en la ruta:', [
                    'path' => $kmzPath,
                    'disk' => 'public',
                    'storage_path' => storage_path('app/public/'.$kmzPath),
                    'file_exists' => file_exists(storage_path('app/public/'.$kmzPath)),
                ]);
                throw new \Exception("El archivo KMZ no existe en la ruta: {$kmzPath}");
            }

            Log::info('Archivo KMZ encontrado, obteniendo ruta completa...');
            // Obtener la ruta completa del archivo desde el disco 'public'
            $fullPath = $disk->path($kmzPath);
            Log::info('Ruta completa del archivo:', [
                'full_path' => $fullPath,
                'file_exists' => file_exists($fullPath),
                'is_readable' => is_readable($fullPath),
            ]);

            // Abrir el archivo KMZ (que es un ZIP)
            $zip = new ZipArchive;
            $zipResult = $zip->open($fullPath);
            Log::info('Intento de abrir archivo ZIP:', ['result' => $zipResult, 'zip_error_code' => $zipResult]);

            if ($zipResult !== true) {
                Log::error('No se pudo abrir el archivo KMZ:', [
                    'result' => $zipResult,
                    'full_path' => $fullPath,
                    'file_exists' => file_exists($fullPath),
                ]);
                throw new \Exception('No se pudo abrir el archivo KMZ. Verifique que sea un archivo válido.');
            }

            Log::info('Archivo ZIP abierto correctamente. Número de archivos:', ['num_files' => $zip->numFiles]);

            // Buscar el archivo KML dentro del KMZ
            $kmlContent = null;
            Log::info('Buscando archivo KML dentro del KMZ...');
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                Log::info('Archivo encontrado en ZIP:', ['index' => $i, 'filename' => $filename, 'extension' => pathinfo($filename, PATHINFO_EXTENSION)]);
                if (pathinfo($filename, PATHINFO_EXTENSION) === 'kml') {
                    Log::info('Archivo KML encontrado:', ['filename' => $filename]);
                    $kmlContent = $zip->getFromIndex($i);
                    Log::info('Contenido KML extraído:', ['size' => strlen($kmlContent), 'preview' => substr($kmlContent, 0, 200)]);
                    break;
                }
            }

            $zip->close();
            Log::info('Archivo ZIP cerrado');

            if (! $kmlContent) {
                Log::error('No se encontró archivo KML dentro del KMZ');
                throw new \Exception('No se encontró un archivo KML dentro del KMZ.');
            }

            // Parsear el contenido KML
            Log::info('Iniciando parseo del contenido KML...');
            $waypoints = $this->parseKmlContent($kmlContent);
            Log::info('Parseo KML completado. Waypoints encontrados:', ['count' => count($waypoints)]);

            return $waypoints;

        } catch (\Exception $e) {
            Log::error('Error al parsear archivo KMZ: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Parsea el contenido XML de un archivo KML y extrae los waypoints
     *
     * @param  string  $kmlContent  Contenido XML del KML
     * @return array Array de waypoints
     */
    private function parseKmlContent(string $kmlContent): array
    {
        Log::info('=== INICIO PARSEO CONTENIDO KML ===');
        $waypoints = [];

        try {
            // Suprimir errores de XML y parsear
            libxml_use_internal_errors(true);
            Log::info('Intentando parsear XML del KML...');
            $xml = simplexml_load_string($kmlContent);

            if ($xml === false) {
                $errors = libxml_get_errors();
                $errorMessages = array_map(function ($error) {
                    return trim($error->message);
                }, $errors);
                Log::error('Error al parsear XML:', [
                    'errors' => $errorMessages,
                    'content_preview' => substr($kmlContent, 0, 500),
                ]);
                libxml_clear_errors();
                throw new \Exception('Error al parsear XML: '.implode(', ', $errorMessages));
            }

            Log::info('XML parseado correctamente');

            // Registrar namespaces
            $xml->registerXPathNamespace('kml', 'http://www.opengis.net/kml/2.2');
            $xml->registerXPathNamespace('gx', 'http://www.google.com/kml/ext/2.2');

            // Buscar Placemarks con coordenadas
            $placemarks = $xml->xpath('//kml:Placemark | //Placemark');
            $placemarks = $placemarks === false ? [] : $placemarks;

            Log::info('Placemarks encontrados:', ['count' => count($placemarks)]);

            foreach ($placemarks as $placemark) {
                // Buscar coordenadas en Point
                $points = $placemark->xpath('.//kml:Point | .//Point');
                $points = $points === false ? [] : $points;

                foreach ($points as $point) {
                    $coordinates = $point->xpath('.//kml:coordinates | .//coordinates');
                    $coordinates = $coordinates === false ? [] : $coordinates;

                    if (! empty($coordinates)) {
                        $coordString = (string) $coordinates[0];
                        $coords = $this->parseCoordinates($coordString);
                        if ($coords) {
                            $waypoints[] = [
                                'latitud' => (string) $coords['lat'],
                                'longitud' => (string) $coords['lon'],
                                'altitud' => isset($coords['alt']) ? (string) $coords['alt'] : '35',
                                'acciones' => [],
                            ];
                        }
                    }
                }

                // Buscar coordenadas en LineString o Path
                $lineStrings = $placemark->xpath('.//kml:LineString | .//LineString | .//kml:gx:Track | .//gx:Track');
                $lineStrings = $lineStrings === false ? [] : $lineStrings;

                foreach ($lineStrings as $lineString) {
                    $coordinates = $lineString->xpath('.//kml:coordinates | .//coordinates | .//kml:gx:coord | .//gx:coord');
                    $coordinates = $coordinates === false ? [] : $coordinates;

                    foreach ($coordinates as $coord) {
                        $coords = null;
                        if (strpos((string) $coord, ',') !== false) {
                            // Formato: lon,lat,alt o lon,lat
                            $coords = $this->parseCoordinates((string) $coord);
                        } else {
                            // Formato gx:coord: lon lat alt
                            $parts = explode(' ', trim((string) $coord));
                            if (count($parts) >= 2) {
                                $coords = [
                                    'lon' => floatval($parts[0]),
                                    'lat' => floatval($parts[1]),
                                    'alt' => isset($parts[2]) ? floatval($parts[2]) : 35,
                                ];
                            }
                        }

                        if ($coords) {
                            $waypoints[] = [
                                'latitud' => (string) $coords['lat'],
                                'longitud' => (string) $coords['lon'],
                                'altitud' => isset($coords['alt']) ? (string) $coords['alt'] : '35',
                                'acciones' => [],
                            ];
                        }
                    }
                }
            }

            // Si no se encontraron waypoints en Placemarks, buscar directamente coordenadas
            if (empty($waypoints)) {
                Log::info('No se encontraron waypoints en Placemarks, buscando coordenadas directamente...');
                $allCoordinates = $xml->xpath('//kml:coordinates | //coordinates');
                $allCoordinates = $allCoordinates === false ? [] : $allCoordinates;

                Log::info('Coordenadas encontradas directamente:', ['count' => count($allCoordinates)]);

                foreach ($allCoordinates as $coordElement) {
                    $coordString = (string) $coordElement;
                    // Las coordenadas pueden estar separadas por espacios o saltos de línea
                    $coordLines = preg_split('/[\s\n]+/', trim($coordString));
                    foreach ($coordLines as $coordLine) {
                        if (empty(trim($coordLine))) {
                            continue;
                        }
                        $coords = $this->parseCoordinates(trim($coordLine));
                        if ($coords) {
                            $waypoints[] = [
                                'latitud' => (string) $coords['lat'],
                                'longitud' => (string) $coords['lon'],
                                'altitud' => isset($coords['alt']) ? (string) $coords['alt'] : '35',
                                'acciones' => [],
                            ];
                        }
                    }
                }
            }

            // Eliminar duplicados basados en latitud y longitud
            $waypoints = $this->removeDuplicateWaypoints($waypoints);

            return $waypoints;

        } catch (\Exception $e) {
            Log::error('Error al parsear contenido KML: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Parsea una cadena de coordenadas en formato "lon,lat,alt" o "lon,lat"
     *
     * @param  string  $coordString  Cadena de coordenadas
     * @return array|null Array con 'lon', 'lat', 'alt' o null si no es válido
     */
    private function parseCoordinates(string $coordString): ?array
    {
        $coordString = trim($coordString);
        if (empty($coordString)) {
            return null;
        }

        // Separar por coma
        $parts = explode(',', $coordString);

        if (count($parts) < 2) {
            return null;
        }

        $lon = floatval(trim($parts[0]));
        $lat = floatval(trim($parts[1]));
        $alt = isset($parts[2]) ? floatval(trim($parts[2])) : 35;

        // Validar que las coordenadas sean válidas
        if ($lat < -90 || $lat > 90 || $lon < -180 || $lon > 180) {
            return null;
        }

        return [
            'lon' => $lon,
            'lat' => $lat,
            'alt' => $alt,
        ];
    }

    /**
     * Elimina waypoints duplicados basándose en latitud y longitud
     *
     * @param  array  $waypoints  Array de waypoints
     * @return array Array de waypoints sin duplicados
     */
    private function removeDuplicateWaypoints(array $waypoints): array
    {
        $seen = [];
        $unique = [];

        foreach ($waypoints as $waypoint) {
            $key = $waypoint['latitud'].','.$waypoint['longitud'];
            if (! isset($seen[$key])) {
                $seen[$key] = true;
                $unique[] = $waypoint;
            }
        }

        return $unique;
    }
}
