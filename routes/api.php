<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\CameraController;
use App\Http\Controllers\MobileVehicleController;
use App\Http\Controllers\Api\PersonalImportController;
use App\Http\Controllers\Api\PersonalCompareController;
use App\Http\Controllers\EncodingDeviceController;
use App\Http\Controllers\MobileVehicleClientController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API PARA IMPORTAR PERSONAL
Route::get('/personal', [PersonalImportController::class, 'index']);

Route::post('/personal/verificar', [PersonalCompareController::class, 'verificarExistencia']);

Route::post('/personal', [PersonalImportController::class, 'store'])
    ->middleware('auth:sanctum')
    ->name('api.personal.import');

Route::post('/personal/verificar/importar', [PersonalCompareController::class, 'store']);


//API PARA IMPORTAR ENCODING DEVICES
Route::get('/encoding-devices', [EncodingDeviceController::class, 'index']);

Route::post('/encoding-devices/import', [EncodingDeviceController::class, 'import']);

// API PARA IMPORTAR CAMERA LIST
Route::prefix('cameras')->group(function () {
    Route::get('/', [CameraController::class, 'index']);
    Route::post('/import', [CameraController::class, 'import']);
    Route::get('/with-devices', [CameraController::class, 'camerasWithDevices']);
    Route::post('/import-streams', [CameraController::class, 'importStreamingUrls']);
    Route::get('/{cameraIndexCode}/stream-url', [CameraController::class, 'getStreamUrl']);
    Route::get('/encoding-device/{encodeDevIndexCode}', [CameraController::class, 'findByEncodingDevice']);
});

//API PARA IMPORTAR VEHICLE LIST Y OBTENER GPS INFO
Route::prefix('/mobile-vehicles')->name('api.mobile-vehicles.')->group(function () {
    Route::get('/', [MobileVehicleController::class, 'api'])->name('index');
    Route::get('/{id}', [MobileVehicleController::class, 'apiShow'])->name('show');
    Route::post('/import', [MobileVehicleController::class, 'import'])->name('mobile-vehicles.import');
    Route::post('/relink', [MobileVehicleController::class, 'relinkAll'])->name('relink');
    Route::get('/map/data', [MobileVehicleController::class, 'apiMapData'])->name('map-data');
    Route::get('/locations/current', [MobileVehicleController::class, 'apiLocations'])->name('locations');
});

// API PARA OBTENER GPS INFO FILTRADA POR CLIENTE
Route::prefix('/client/mobile-vehicles')->name('api.client.mobile-vehicles.')->group(function () {
    Route::get('/locations/current', [MobileVehicleClientController::class, 'apiLocationsClient'])->name('locations');
    Route::get('/map/data', [MobileVehicleClientController::class, 'apiMapDataClient'])->name('map-data');
    Route::get('/stats', [MobileVehicleClientController::class, 'statsClient'])->name('stats');
});

// Route::middleware('auth:sanctum')->get('/events', [EventController::class, 'index']);
Route::get('eventos/barras', [EventoController::class, 'eventosBarras']);