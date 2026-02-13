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
     * Parse a KML file and extract waypoints, distance, and metadata.
     */
    public static function parseKmlFile($file): array
    {
        $content = file_get_contents($file->getRealPath());
        $xml = simplexml_load_string($content);

        if (!$xml) {
            return ['waypoints' => [], 'longitud_mts' => 0, 'metadata' => []];
        }

        $xml->registerXPathNamespace('kml', 'http://www.opengis.net/kml/2.2');

        $waypoints = [];

        // Try standard KML coordinates
        $coordinates = $xml->xpath('//kml:coordinates');
        if (empty($coordinates)) {
            // Fallback: try without namespace
            $coordinates = $xml->xpath('//coordinates');
        }

        if ($coordinates) {
            foreach ($coordinates as $coordSet) {
                $coordString = trim((string)$coordSet);
                // Split by whitespace or newlines
                $coordPairs = preg_split('/[\s]+/', $coordString);

                foreach ($coordPairs as $pair) {
                    $pair = trim($pair);
                    if (empty($pair)) continue;

                    $parts = explode(',', $pair);
                    if (count($parts) >= 2) {
                        $waypoints[] = [
                            'order' => count($waypoints) + 1,
                            'lng' => (float)$parts[0],
                            'lat' => (float)$parts[1],
                            'alt' => isset($parts[2]) ? (float)$parts[2] : null,
                        ];
                    }
                }
            }
        }

        // Extract name if available
        $name = '';
        $nameNodes = $xml->xpath('//kml:name');
        if (empty($nameNodes)) {
            $nameNodes = $xml->xpath('//name');
        }
        if (!empty($nameNodes)) {
            $name = (string)$nameNodes[0];
        }

        // Extract description if available
        $description = '';
        $descNodes = $xml->xpath('//kml:description');
        if (empty($descNodes)) {
            $descNodes = $xml->xpath('//description');
        }
        if (!empty($descNodes)) {
            $description = (string)$descNodes[0];
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
                'filename' => $file->getClientOriginalName(),
                'imported_at' => now()->toDateTimeString(),
                'kml_name' => $name,
                'kml_description' => $description,
                'start_point' => !empty($waypoints) ? [
                    'lat' => $waypoints[0]['lat'],
                    'lng' => $waypoints[0]['lng'],
                ] : null,
                'end_point' => !empty($waypoints) ? [
                    'lat' => end($waypoints)['lat'],
                    'lng' => end($waypoints)['lng'],
                ] : null,
            ]
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
