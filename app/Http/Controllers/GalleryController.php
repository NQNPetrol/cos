<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GalleryController extends Controller
{
    private $galleryService;

    public function __construct()
    {
        $this->galleryService = new GalleryService();
    }

    /**
     * Vista principal de la galería
     */
    public function index(Request $request)
    {
        $filterDrone = $request->get('drone');
        $filterClient = $request->get('client'); 
        $filterMission = $request->get('mission');
        
        $galleryData = $this->galleryService->getOrganizedGallery([
            'drone' => $filterDrone,
            'client' => $filterClient,
            'mission' => $filterMission
        ]);

        return view('gallery.index', [
            'galleryData' => $galleryData,
            'filterDrone' => $filterDrone,
            'filterClient' => $filterClient, 
            'filterMission' => $filterMission,
            'galleryService' => $this->galleryService // Para usar en la vista
        ]);
    }

    /**
     * API para obtener datos de galería (para AJAX)
     */
    public function apiIndex(Request $request)
    {
        $filters = $request->only(['drone', 'client', 'mission', 'type']);
        
        $galleryData = $this->galleryService->getOrganizedGallery($filters);

        return response()->json([
            'success' => true,
            'data' => $galleryData,
            'filters' => $filters
        ]);
    }

    /**
     * Vista detallada de una misión específica
     */
    public function missionShow($drone, $client, $mission)
    {
        $missionData = $this->galleryService->getMissionDetails($drone, $client, $mission);
        
        if (!$missionData) {
            abort(404, 'Misión no encontrada');
        }

        return view('gallery.mission', compact('missionData'));
    }

    /**
     * Obtener miniaturas para un grid
     */
    public function getThumbnails(Request $request)
    {
        $missionKey = $request->get('mission_key');
        $limit = $request->get('limit', 12);
        
        $thumbnails = $this->galleryService->getMissionThumbnails($missionKey, $limit);

        return response()->json([
            'success' => true,
            'thumbnails' => $thumbnails
        ]);
    }
}