<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HikCentralService;
use App\Models\MobileVehicle;
use App\Models\Patrulla;
use Illuminate\Support\Facades\Log;

class MobileVehicleController extends Controller
{
    protected HikCentralService $hikCentral;

    public function __construct(HikCentralService $hikCentral)
    {
        $this->hikCentral = $hikCentral;
    }


    /**
     * Muestra la lista de vehículos móviles
     */
    public function index()
    {
        $mobileVehicles = MobileVehicle::with('patrulla')
            ->orderBy('mobile_vehicle_name')
            ->paginate(20);

        return view('patrullas.location', compact('mobileVehicles'));
    }

    /**
     * Importa los vehículos móviles desde HikCentral
     */
    public function import()
    {
        try {
            Log::info('Iniciando importación de vehículos móviles');

            $results = $this->hikCentral->importMobileVehicles();

            $message = sprintf(
                'Importación completada. Procesados: %d, Nuevos: %d, Actualizados: %d, Vinculados: %d',
                $results['total_processed'],
                $results['total_imported'],
                $results['total_updated'],
                $results['total_linked']
            );

            if (!empty($results['errors'])) {
                $message .= sprintf('. Errores: %d', count($results['errors']));
                Log::warning('Errores durante la importación', $results['errors']);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'results' => $results
            ]);

        } catch (\Exception $e) {
            Log::error('Error en importación de vehículos móviles: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error durante la importación: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vincula manualmente un vehículo móvil con una patrulla
     */
    public function linkToPatrulla(Request $request, MobileVehicle $mobileVehicle)
    {
        $request->validate([
            'patrulla_id' => 'nullable|exists:patrullas,id'
        ]);

        try {
            $mobileVehicle->update([
                'patrulla_id' => $request->patrulla_id
            ]);

            $message = $request->patrulla_id 
                ? 'Vehículo vinculado exitosamente con la patrulla'
                : 'Vinculación con patrulla removida exitosamente';

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al vincular: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Revincula automáticamente todos los vehículos con sus patrullas por patente
     */
    public function relinkAll()
    {
        try {
            $linked = 0;
            $unlinked = 0;

            $mobileVehicles = MobileVehicle::all();

            foreach ($mobileVehicles as $mobileVehicle) {
                $patrulla = Patrulla::where('patente', $mobileVehicle->plate_no)->first();
                
                if ($patrulla && $mobileVehicle->patrulla_id !== $patrulla->id) {
                    $mobileVehicle->update(['patrulla_id' => $patrulla->id]);
                    $linked++;
                } elseif (!$patrulla && $mobileVehicle->patrulla_id) {
                    $mobileVehicle->update(['patrulla_id' => null]);
                    $unlinked++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Revinculación completada. Vinculados: {$linked}, Desvinculados: {$unlinked}",
                'results' => [
                    'linked' => $linked,
                    'unlinked' => $unlinked
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error en revinculación: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error durante la revinculación: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Muestra los detalles de un vehículo móvil
     */
    public function show(MobileVehicle $mobileVehicle)
    {
        $mobileVehicle->load('patrulla');
        
        return view('mobile-vehicles.show', compact('mobileVehicle'));
    }

    /**
     * API: Obtiene la lista de vehículos móviles en formato JSON
     */
    public function api()
    {
        $mobileVehicles = MobileVehicle::with('patrulla')
            ->select([
                'id',
                'mobile_vehicle_index_code',
                'mobile_vehicle_name',
                'status',
                'plate_no',
                'patrulla_id'
            ])
            ->get();

        return response()->json($mobileVehicles);
    }

    /**
     * API: Obtiene un vehículo móvil específico
     */
    public function apiShow($id)
    {
        $mobileVehicle = MobileVehicle::with('patrulla')->findOrFail($id);
        
        return response()->json($mobileVehicle);
    }

    /**
     * Obtiene estadísticas de los vehículos móviles
     */
    public function stats()
    {
        $stats = [
            'total' => MobileVehicle::count(),
            'active' => MobileVehicle::where('status', 1)->count(),
            'inactive' => MobileVehicle::where('status', 2)->count(),
            'linked' => MobileVehicle::whereNotNull('patrulla_id')->count(),
            'unlinked' => MobileVehicle::whereNull('patrulla_id')->count(),
        ];

        return response()->json($stats);
    }
}
