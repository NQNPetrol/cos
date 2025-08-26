<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\Api\PersonalImportController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/personal', [PersonalImportController::class, 'index']);


Route::post('/personal', [PersonalImportController::class, 'store'])
    ->middleware('auth:sanctum')
    ->name('api.personal.import');


// Route::middleware('auth:sanctum')->get('/events', [EventController::class, 'index']);
Route::get('eventos/barras', [EventoController::class, 'eventosBarras']);