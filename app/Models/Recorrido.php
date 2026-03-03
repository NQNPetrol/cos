<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recorrido extends Model
{
    protected $table = 'recorridos';

    protected $fillable = [
        'cliente_id',
        'empresa_asociada_id',
        'user_id',
        'nombre',
        'descripcion',
        'objetivos',
        'waypoints',
        'longitud_mts',
        'velocidadmax_permitida',
        'duracion_promedio',
    ];

    protected $casts = [
        'waypoints' => 'array',
        'longitud_mts' => 'decimal:2',
        'velocidadmax_permitida' => 'integer',
        'duracion_promedio' => 'integer',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function empresaAsociada(): BelongsTo
    {
        return $this->belongsTo(EmpresaAsociada::class, 'empresa_asociada_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function timetable(): HasMany
    {
        return $this->hasMany(RecorridoTimetable::class, 'recorrido_id');
    }

    public function scopeForCliente($query, $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }

    public function scopeForEmpresa($query, $empresaId)
    {
        return $query->where('empresa_asociada_id', $empresaId);
    }

    /**
     * Parse a KML or KMZ file and extract waypoints, distance, and metadata.
     * Supports KML 2.2 with or without namespace, LineString and Point coordinates.
     * KMZ: opens as ZIP and parses the first .kml file inside.
     */
    public static function parseKmlFile($file): array
    {
        $safe = ['waypoints' => [], 'longitud_mts' => 0, 'metadata' => []];
        $path = $file->getRealPath();
        if (! $path || ! is_readable($path)) {
            return $safe;
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $content = null;
        $filename = $file->getClientOriginalName();

        if ($extension === 'kmz') {
            $content = self::extractKmlFromKmz($path);
            if ($content === null) {
                return $safe;
            }
        } else {
            $content = @file_get_contents($path);
        }

        if ($content === false || $content === null) {
            return $safe;
        }

        return self::parseKmlContent($content, $filename);
    }

    /**
     * Extract KML XML string from a KMZ (ZIP) file path.
     */
    protected static function extractKmlFromKmz(string $path): ?string
    {
        if (! class_exists(\ZipArchive::class)) {
            return null;
        }
        $zip = new \ZipArchive;
        if ($zip->open($path, \ZipArchive::RDONLY) !== true) {
            return null;
        }
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if (strtolower(pathinfo($name, PATHINFO_EXTENSION)) === 'kml') {
                $content = $zip->getFromIndex($i);
                $zip->close();

                return $content !== false ? $content : null;
            }
        }
        $zip->close();

        return null;
    }

    /**
     * Parse KML XML string and return waypoints, length and metadata.
     */
    public static function parseKmlContent(string $content, string $filename = ''): array
    {
        $safe = ['waypoints' => [], 'longitud_mts' => 0, 'metadata' => []];

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($content);
        if ($xml === false) {
            libxml_clear_errors();

            return $safe;
        }

        $waypoints = self::extractWaypointsFromKml($xml);
        libxml_clear_errors();

        // Extract name if available (namespace-agnostic)
        $name = '';
        $nameNodes = $xml->xpath('//*[local-name()="name"]');
        if (! empty($nameNodes)) {
            $name = trim((string) $nameNodes[0]);
        }

        // Extract description if available
        $description = '';
        $descNodes = $xml->xpath('//*[local-name()="description"]');
        if (! empty($descNodes)) {
            $description = trim((string) $descNodes[0]);
        }

        // Calculate total distance using Haversine
        $longitudTotal = 0;
        for ($i = 0; $i < count($waypoints) - 1; $i++) {
            $longitudTotal += self::calcularDistanciaHaversine(
                $waypoints[$i]['lat'], $waypoints[$i]['lng'],
                $waypoints[$i + 1]['lat'], $waypoints[$i + 1]['lng']
            );
        }

        return [
            'waypoints' => $waypoints,
            'longitud_mts' => round($longitudTotal, 2),
            'metadata' => [
                'total_points' => count($waypoints),
                'filename' => $filename,
                'imported_at' => now()->toDateTimeString(),
                'kml_name' => $name,
                'kml_description' => $description,
                'start_point' => ! empty($waypoints) ? [
                    'lat' => $waypoints[0]['lat'],
                    'lng' => $waypoints[0]['lng'],
                ] : null,
                'end_point' => ! empty($waypoints) ? [
                    'lat' => end($waypoints)['lat'],
                    'lng' => end($waypoints)['lng'],
                ] : null,
            ],
        ];
    }

    /**
     * Extract waypoints from parsed KML (SimpleXMLElement).
     * Uses namespace-agnostic XPath so it works with or without xmlns.
     */
    protected static function extractWaypointsFromKml(\SimpleXMLElement $xml): array
    {
        $waypoints = [];

        // Strategy 1: coordinates with namespace (KML 2.2)
        $xml->registerXPathNamespace('kml', 'http://www.opengis.net/kml/2.2');
        $coordinates = $xml->xpath('//kml:coordinates');
        if (empty($coordinates)) {
            // Strategy 2: local-name() works with default namespace or no namespace
            $coordinates = $xml->xpath('//*[local-name()="coordinates"]');
        }

        if (! empty($coordinates)) {
            foreach ($coordinates as $coordSet) {
                $coordString = trim((string) $coordSet);
                if ($coordString === '') {
                    continue;
                }
                // Split by whitespace or newlines (KML uses space/newline between lon,lat,alt pairs)
                $coordPairs = preg_split('/[\s]+/', $coordString, -1, PREG_SPLIT_NO_EMPTY);
                foreach ($coordPairs as $pair) {
                    $pair = trim($pair);
                    if ($pair === '') {
                        continue;
                    }
                    $point = self::parseCoordinatePair($pair);
                    if ($point !== null) {
                        $point['order'] = count($waypoints) + 1;
                        $waypoints[] = $point;
                    }
                }
            }
        }

        return $waypoints;
    }

    /**
     * Parse a single "lon,lat,alt" or "lon,lat" string.
     *
     * @return array{lat: float, lng: float, alt: float|null}|null
     */
    protected static function parseCoordinatePair(string $pair): ?array
    {
        $parts = array_map('trim', explode(',', $pair));
        if (count($parts) < 2) {
            return null;
        }
        $lng = (float) $parts[0];
        $lat = (float) $parts[1];
        if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
            return null;
        }

        return [
            'lat' => $lat,
            'lng' => $lng,
            'alt' => isset($parts[2]) && $parts[2] !== '' ? (float) $parts[2] : null,
        ];
    }

    /**
     * Calculate distance between two GPS points using the Haversine formula.
     * Returns distance in meters.
     */
    public static function calcularDistanciaHaversine(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000; // meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Calculate total route distance from stored waypoints.
     */
    public function calcularDistanciaTotal(): float
    {
        $waypoints = $this->waypoints ?? [];
        $total = 0;

        for ($i = 0; $i < count($waypoints) - 1; $i++) {
            $total += self::calcularDistanciaHaversine(
                $waypoints[$i]['lat'], $waypoints[$i]['lng'],
                $waypoints[$i + 1]['lat'], $waypoints[$i + 1]['lng']
            );
        }

        return round($total, 2);
    }
}
