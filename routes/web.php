<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Models\Evento;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {


    Route::get('/clientes/create', [App\Http\Controllers\ClienteController::class, 'create'])
        // ->middleware('can:crear.cliente')
        ->name('crear.cliente');
    Route::get('/clientes/{cliente}/edit', [App\Http\Controllers\ClienteController::class, 'edit'])
        // ->middleware('can:crear.cliente')
        ->name('clientes.edit');
    Route::put('/clientes/{cliente}', [App\Http\Controllers\ClienteController::class, 'update'])
        ->name('clientes.update');

    Route::get('/configuracion/permisos', [App\Http\Controllers\SistemaController::class, 'crear_permiso'])
        ->name('crear.permiso');

    Route::get('/usuarios', [App\Http\Controllers\UserController::class, 'index'])
        // ->middleware('can:administrar.usuarios')
        ->name('usuarios.index');

    Route::post('/usuarios/{user}/roles', [App\Http\Controllers\UserController::class, 'asignarRol'])
        ->name('usuarios.roles');

    //CONTRATOS
    // INDEX
    Route::get('/contratos', [App\Http\Controllers\ContratoController::class, 'index'])
        ->middleware('can:ver.contratos')
        ->name('contratos.index');

    // CREATE
    Route::get('/contratos/create', [App\Http\Controllers\ContratoController::class, 'create'])
        ->middleware('can:crear.contratos')
        ->name('contratos.create');

    // STORE
    Route::post('/contratos', [App\Http\Controllers\ContratoController::class, 'store'])
        ->middleware('can:crear.contratos')
        ->name('contratos.store');

    // EDIT
    Route::get('/contratos/{contrato}/edit', [App\Http\Controllers\ContratoController::class, 'edit'])
        ->middleware('can:editar.contratos')
        ->name('contratos.edit');

    // UPDATE
    Route::put('/contratos/{contrato}', [App\Http\Controllers\ContratoController::class, 'update'])
        ->middleware('can:editar.contratos')
        ->name('contratos.update');

    // DELETE
    Route::delete('/contratos/{contrato}', [App\Http\Controllers\ContratoController::class, 'destroy'])
        ->middleware('can:borrar.contratos')
        ->name('contratos.destroy');

    //OBJETIVOS    
    Route::get('/objetivos', [App\Http\Controllers\ObjetivoController::class, 'index'])
        ->middleware('can:ver.objetivos')
        ->name('objetivos.index');
    
    //SEGUIMIENTOS
    Route::get('/seguimientos', [\App\Http\Controllers\SeguimientoController::class,'index'])
        ->name('seguimientos.index');
    Route::get('/seguimientos/nuevo', [\App\Http\Controllers\SeguimientoController::class,'create'])
        ->name('seguimientos.create');
    Route::post('/seguimientos', [\App\Http\Controllers\SeguimientoController::class, 'store'])->name('seguimientos.store');

    //RELEVOS
    Route::get('/relevos/nuevo', [\App\Http\Controllers\RelevoController::class, 'create'])->name('relevos.create');
    Route::get('/relevos', [\App\Http\Controllers\RelevoController::class,'index'])
        ->name('relevos.index');
    Route::post('/relevos', [\App\Http\Controllers\RelevoController::class, 'store'])->name('relevos.store');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/admin/sistema', [App\Http\Controllers\SistemaController::class, 'index'])
    ->middleware('role:admin')
    ->name('sistema.permisos');
    Route::get('/admin/permisos', [App\Http\Controllers\SistemaController::class, 'asignar_permisos'])
    ->middleware('role:admin')
    ->name('asignar.permisos');
    
});


