<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::middleware('auth:sanctum')->get('/events', [EventController::class, 'index']);
Route::get('eventos/barras', [EventoController::class, 'eventosBarras']);