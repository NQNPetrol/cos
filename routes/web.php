<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Models\Evento;
use Illuminate\Support\Facades\Route;
use App\Livewire\DispositivoPatrulla\AsignarDispositivos;
use App\Http\Controllers\DispositivoPatrullaController;
use App\Models\Patrulla;
use App\Http\Controllers\EventoController;

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

    //CLIENTES
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
    //USUARIOS
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
    Route::get('/contratos/{contrato}/edit-livewire', \App\Livewire\Contratos\Edit::class)
        ->name('contratos.edit-livewire');

    // UPDATE
    Route::put('/contratos/{contrato}', [App\Http\Controllers\ContratoController::class, 'update'])
        ->middleware('can:editar.contratos')
        ->name('contratos.update');

    // DELETE
    Route::delete('/contratos/{contrato}', [App\Http\Controllers\ContratoController::class, 'destroy'])
        ->middleware('can:borrar.contratos')
        ->name('contratos.destroy');

    //EMPRESAS ASOCIADAS A CLIENTES

    Route::get('/empresas-asociadas', function() {
        $empresas = \App\Models\EmpresaAsociada::with('cliente')->paginate(10);
        return view('clientes.nueva-empresa-asociada', ['empresas' => $empresas]);
    })->name('empresas-asociadas.index');

    
    Route::get('/clientes/{clienteId}/empresas-asociadas', [App\Http\Controllers\ClienteEmpresasAsociadasController::class, 'index'])
    ->name('cliente-empresas-asociadas.index');

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

    //EVENTOS
    Route::get('/eventos/nuevo', [\App\Http\Controllers\EventoController::class, 'create'])->name('eventos.create');
    Route::get('/eventos', [\App\Http\Controllers\EventoController::class, 'index'])->name('eventos.index');
    Route::post('/eventos', [\App\Http\Controllers\EventoController::class, 'store'])->name('eventos.store');
    Route::get('/eventos/{evento}/edit', [\App\Http\Controllers\EventoController::class, 'edit'])->name('eventos.edit');
    Route::put('/eventos/{evento}', [\App\Http\Controllers\EventoController::class, 'update'])->name('eventos.update');
    Route::delete('/eventos/{evento}', [\App\Http\Controllers\EventoController::class, 'destroy'])->name('eventos.destroy');
    
    //REPORTES
    Route::middleware(['auth'])->group(function () {
        Route::get('/eventos/{evento}/reporte', [\App\Http\Controllers\ReporteController::class, 'preview'])->name('eventos.reporte.preview');
        Route::post('/eventos/{evento}/reporte/generar', [\App\Http\Controllers\ReporteController::class, 'generate'])->name('eventos.reporte.generate');
        Route::get('/reportes/{reporte}/download', [\App\Http\Controllers\ReporteController::class, 'download'])->name('reportes.download');
        Route::get('/reportes/{reporte}/view', [\App\Http\Controllers\ReporteController::class, 'view'])->name('reportes.view');
        Route::get('/eventos/{evento}/preview-iframe', [\App\Http\Controllers\ReporteController::class, 'previewIframe'])->name('eventos.reporte.preview-iframe');
    });
    
    //MEDIA
    Route::get('/eventos/media/{media}', [\App\Http\Controllers\EventoController::class, 'destroyMedia'])
        ->name('media.eventos.destroy');

    
    //PERSONAL
    Route::get('/personal', [\App\Http\Controllers\PersonalController::class, 'index'])
        ->name('personal.index');
    Route::get('/personal/create', [\App\Http\Controllers\PersonalController::class, 'create'])
        ->name('personal.create');
    Route::post('/personal/store/', [\App\Http\Controllers\PersonalController::class, 'store'])
        ->name('personal.store');
    Route::get('/personal/{id}/edit', [App\Http\Controllers\PersonalController::class, 'edit'])
        //->middleware('can:editar.personal')
        ->name('personal.edit');
    Route::put('/personal/{id}', [App\Http\Controllers\PersonalController::class, 'update'])
        //->middleware('can:editar.personal')
        ->name('personal.update');

    //INVENTARIO
    Route::get('/inventario', [\App\Http\Controllers\InventarioController::class, 'index'])
        ->name('inventario.index');
    Route::get('/inventario/create', [\App\Http\Controllers\InventarioController::class, 'create'])
        ->name('inventario.create');
    Route::post('/inventario/store/', [\App\Http\Controllers\InventarioController::class, 'store'])
        ->name('inventario.store');
    Route::get('/inventario/{id}/edit', [App\Http\Controllers\InventarioController::class, 'edit'])
        //->middleware('can:editar.inventario')
        ->name('inventario.edit');
    Route::put('/inventario/{id}', [App\Http\Controllers\InventarioController::class, 'update'])
        //->middleware('can:editar.inventario')
        ->name('inventario.update');

    //PATRULLAS
    Route::get('/patrullas', [\App\Http\Controllers\PatrullaController::class, 'index'])
        ->name('patrullas.index');
    Route::get('/livewire/patrullas', [\App\Http\Controllers\PatrullaController::class, 'create'])
        ->name('patrullas.create');

    //DISPOSiTIVO-PATRULLA
    Route::get('/patrullas/{patrulla}/dispositivos', [DispositivoPatrullaController::class, 'index'])
    ->name('patrullas.dispositivos');

    //TICKETS
    Route::get('/tickets/nuevo', [App\Http\Controllers\TicketController::class, 'index'])->name('tickets.nuevo');

});


Route::middleware(['auth'])->group(function () {
    Route::get('/admin/sistema', [App\Http\Controllers\SistemaController::class, 'index'])
    ->middleware('role:admin')
    ->name('sistema.permisos');
    Route::get('/admin/permisos', [App\Http\Controllers\SistemaController::class, 'asignar_permisos'])
    ->middleware('role:admin')
    ->name('asignar.permisos');
    
});


