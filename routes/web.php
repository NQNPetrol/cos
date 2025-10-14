<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Models\Evento;
use Illuminate\Support\Facades\Route;
use App\Livewire\DispositivoPatrulla\AsignarDispositivos;
use App\Http\Controllers\DispositivoPatrullaController;
use App\Http\Controllers\NotificationController;
use App\Models\Patrulla;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\UserClienteController;


Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// DASHBOARD LAYOUT PRINCIPAL
Route::get('/main-dashboard', function () {
    return view('dashboard'); // Vista normal con layout app
})->middleware(['auth', 'verified'])
  ->name('main.dashboard');

// LAYOUT CLIENTES
Route::middleware(['auth', 'verified'])->prefix('client')->name('client.')->group(function () {
    //DASHBOARD
    Route::get('/dashboard', function () {
        return view('client.dashboard');
    })->name('dashboard');

    //EVENTOS (USA CONTROLADOR DIFERENTE)
    Route::get('/eventos/nuevo', [\App\Http\Controllers\EventoClientController::class, 'create'])
        ->middleware('can:crear.eventos')
        ->name('eventos.create');

    Route::get('/eventos', [\App\Http\Controllers\EventoClientController::class, 'index'])
        ->middleware('can:ver.eventos')
        ->name('eventos.index');

    Route::post('/eventos/store', [\App\Http\Controllers\EventoClientController::class, 'store'])
        ->middleware('can:crear.eventos')
        ->name('eventos.store');

    Route::get('/eventos/{evento}/edit', [\App\Http\Controllers\EventoClientController::class, 'edit'])
        ->middleware('can:editar.eventos')
        ->name('eventos.edit');

    Route::put('/eventos/{evento}/update', [\App\Http\Controllers\EventoClientController::class, 'update'])
        ->middleware('can:editar.eventos')
        ->name('eventos.update');

    Route::delete('/eventos/{evento}/destroy', [\App\Http\Controllers\EventoClientController::class, 'destroy'])
        ->middleware('can:eliminar.eventos')
        ->name('eventos.destroy');
    
    Route::post('/eventos/{evento}/anular', [\App\Http\Controllers\EventoClientController::class, 'anular'])
        ->name('eventos.anular');

    //REPORTES (USA CONTROLADOR DIFERENTE)
    Route::get('/eventos/{evento}/reporte', [\App\Http\Controllers\ReporteClientController::class, 'preview'])
        ->middleware('can:ver.reportes')
        ->name('eventos.reporte.preview');
    Route::post('/eventos/{evento}/reporte/generar', [\App\Http\Controllers\ReporteClientController::class, 'generate'])
        ->middleware('can:generar.reportes')
        ->name('eventos.reporte.generate');
    Route::get('/reportes/{reporte}/download', [\App\Http\Controllers\ReporteClientController::class, 'download'])
        ->middleware('can:generar.reportes')
        ->name('reportes.download');
    Route::get('/reportes/{reporte}/view', [\App\Http\Controllers\ReporteClientController::class, 'view'])
        ->middleware('can:ver.reportes')
        ->name('reportes.view');
    Route::get('/eventos/{evento}/preview-iframe', [\App\Http\Controllers\ReporteClientController::class, 'previewIframe'])
        ->middleware('can:ver.reportes')
        ->name('eventos.reporte.preview-iframe');
    
        
    //SEGUIMIENTOS (USA MISMO CONTROLADOR)
    Route::get('/seguimientos', [\App\Http\Controllers\SeguimientoController::class,'indexClientLayout'])
        ->middleware('can:ver.seguimientos')
        ->name('seguimientos.index');

    // PATRULLAS (USA MISMO CONTROLADOR)
    Route::get('/patrullas', [\App\Http\Controllers\PatrullaController::class, 'indexClient'])
        ->name('patrullas.index');

    //MOBILE VEHICLE USA NUEVO CONTROLADOR

    Route::get('/patrullas/mapa', [\App\Http\Controllers\MobileVehicleClientController::class, 'locationClient'])
        ->name('patrullas.location');

    //TICKETS Y NOTIFICACIONES
    Route::get('/tickets/nuevo', [App\Http\Controllers\TicketController::class, 'indexClient'])
        ->middleware('can:ver.tickets')
        ->name('tickets.nuevo');

});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';

Route::middleware([
    'auth',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    //CLIENTES
    Route::get('/clientes/create', [App\Http\Controllers\ClienteController::class, 'create'])
        ->middleware('can:crear.cliente')
        ->name('crear.cliente');

    Route::get('/clientes/{cliente}/edit', [App\Http\Controllers\ClienteController::class, 'edit'])
        ->middleware('can:editar.cliente')
        ->name('clientes.edit');

    Route::put('/clientes/{cliente}', [App\Http\Controllers\ClienteController::class, 'update'])
        ->middleware('can:editar.cliente')
        ->name('clientes.update');

    Route::get('/configuracion/permisos', [App\Http\Controllers\SistemaController::class, 'crear_permiso'])
        ->middleware('can:crear.permiso')
        ->name('crear.permiso');

    //USUARIOS
    Route::get('/usuarios', [App\Http\Controllers\UserController::class, 'index'])
        ->middleware('can:administrar.usuarios')
        ->name('usuarios.index');

    Route::get('/usuarios/administrar/roles', [App\Http\Controllers\UserController::class, 'roles'])
        ->middleware('can:administrar.usuarios')
        ->name('usuarios.admin-roles');

    Route::post('/usuarios/{user}/roles', [App\Http\Controllers\UserController::class, 'asignarRol'])
        ->middleware('can:administrar.roles')
        ->name('usuarios.roles');
    // Crear, editar y eliminar usuarios
    Route::post('/usuarios', [App\Http\Controllers\UserController::class, 'store'])
        ->middleware('can:administrar.usuarios')
        ->name('usuarios.store');

    Route::put('/usuarios/{user}', [App\Http\Controllers\UserController::class, 'update'])
        ->middleware('can:administrar.usuarios')
        ->name('usuarios.update');

    Route::delete('/usuarios/{user}', [App\Http\Controllers\UserController::class, 'destroy'])
        ->middleware('can:administrar.usuarios')
        ->name('usuarios.destroy');

    //resetar contraseña
    Route::put('/usuarios/{user}/reset-password', [App\Http\Controllers\UserController::class, 'resetPassword'])->name('usuarios.reset-password');
    
    //CLIENTE-USUARIO
    Route::get('usuarios/asignar-clientes', [App\Http\Controllers\UserClienteController::class, 'index'])->middleware('can:asignar.clientes')->name('user-cliente.index');
        
    Route::post('usuarios/asignar-clientes', [App\Http\Controllers\UserClienteController::class, 'store'])->middleware('can:asignar.clientes')->name('user-cliente.store');
        
    Route::delete('usuarios/remover-cliente', [App\Http\Controllers\UserClienteController::class, 'destroy'])->middleware('can:asignar.clientes')->name('user-cliente.destroy');
        
    Route::get('/usuarios/{user}/clientes', [App\Http\Controllers\UserClienteController::class, 'getClientesPorUsuario'])->middleware('can:asignar.clientes')->name('user-cliente.clientes-por-usuario');
        
    Route::get('/clientes/{cliente}/usuarios', [App\Http\Controllers\UserClienteController::class, 'getUsuariosPorCliente'])->middleware('can:asignar.clientes')->name('user-cliente.usuarios-por-cliente');
        
    Route::post('/admin/user-clientes/remove-all', [UserClienteController::class, 'removeAllClientesFromUser'])
        ->name('user-cliente.removeAll')
        ->middleware(['auth', 'role:admin']);
    
    //ROLES
    Route::get('/roles', function () {
        return view('admin.roles');
    })->middleware('can:administrar.roles')->name('crear.roles');

    //CONTRATOS
    Route::get('/contratos', [App\Http\Controllers\ContratoController::class, 'index'])
        ->middleware('can:ver.contratos')
        ->name('contratos.index');

    Route::get('/contratos/create', [App\Http\Controllers\ContratoController::class, 'create'])
        ->middleware('can:crear.contratos')
        ->name('contratos.create');

 
    Route::post('/contratos', [App\Http\Controllers\ContratoController::class, 'store'])
        ->middleware('can:crear.contratos')
        ->name('contratos.store');

    Route::get('/contratos/{contrato}/edit', [App\Http\Controllers\ContratoController::class, 'edit'])
        ->middleware('can:editar.contratos')
        ->name('contratos.edit');
        
    Route::get('/contratos/{contrato}/edit-livewire', \App\Livewire\Contratos\Edit::class)
        ->name('contratos.edit-livewire');

    Route::put('/contratos/{contrato}', [App\Http\Controllers\ContratoController::class, 'update'])
        ->middleware('can:editar.contratos')
        ->name('contratos.update');

  
    Route::delete('/contratos/{contrato}', [App\Http\Controllers\ContratoController::class, 'destroy'])
        ->middleware('can:eliminar.contratos')
        ->name('contratos.destroy');

    //EMPRESAS ASOCIADAS A CLIENTES

    Route::get('/empresas-asociadas', function() {
        $empresas = \App\Models\EmpresaAsociada::with('cliente')->paginate(10);
        return view('clientes.nueva-empresa-asociada', ['empresas' => $empresas]);
    })->middleware('can:crear.empresas')->name('empresas-asociadas.index');

    
    Route::get('/clientes/{clienteId}/empresas-asociadas', [App\Http\Controllers\ClienteEmpresasAsociadasController::class, 'index'])
        ->middleware('can:ver.empresas')
        ->name('cliente-empresas-asociadas.index');

    //OBJETIVOS    
    Route::get('/objetivos', [App\Http\Controllers\ObjetivoController::class, 'index'])
        ->middleware('can:ver.objetivos')
        ->name('objetivos.index');
    
    //SEGUIMIENTOS
    Route::get('/seguimientos', [\App\Http\Controllers\SeguimientoController::class,'index'])
        ->middleware('can:ver.seguimientos')
        ->name('seguimientos.index');

    Route::get('/seguimientos/nuevo', [\App\Http\Controllers\SeguimientoController::class,'create'])
        ->middleware('can:crear.seguimientos')
        ->name('seguimientos.create');

    Route::post('/seguimientos', [\App\Http\Controllers\SeguimientoController::class, 'store'])->middleware('can:crear.seguimientos')->name('seguimientos.store');

    //EVENTOS
    Route::get('/eventos/nuevo', [\App\Http\Controllers\EventoController::class, 'create'])
        ->middleware('can:crear.eventos')
        ->name('eventos.create');

    Route::get('/eventos', [\App\Http\Controllers\EventoController::class, 'index'])
        ->middleware('can:ver.eventos')
        ->name('eventos.index');

    Route::post('/eventos', [\App\Http\Controllers\EventoController::class, 'store'])
        ->middleware('can:crear.eventos')
        ->name('eventos.store');

    Route::get('/eventos/{evento}/edit', [\App\Http\Controllers\EventoController::class, 'edit'])
        ->middleware('can:editar.eventos')
        ->name('eventos.edit');

    Route::put('/eventos/{evento}', [\App\Http\Controllers\EventoController::class, 'update'])
        ->middleware('can:editar.eventos')
        ->name('eventos.update');

    Route::delete('/eventos/{evento}', [\App\Http\Controllers\EventoController::class, 'destroy'])
        ->middleware('can:eliminar.eventos')
        ->name('eventos.destroy');
    
    //REPORTES
    Route::middleware(['auth'])->group(function () {
        Route::get('/eventos/{evento}/reporte', [\App\Http\Controllers\ReporteController::class, 'preview'])->middleware('can:ver.reportes')->name('eventos.reporte.preview');
        Route::post('/eventos/{evento}/reporte/generar', [\App\Http\Controllers\ReporteController::class, 'generate'])->middleware('can:generar.reportes')->name('eventos.reporte.generate');
        Route::get('/reportes/{reporte}/download', [\App\Http\Controllers\ReporteController::class, 'download'])->middleware('can:generar.reportes')->name('reportes.download');
        Route::get('/reportes/{reporte}/view', [\App\Http\Controllers\ReporteController::class, 'view'])->middleware('can:ver.reportes')->name('reportes.view');
        Route::get('/eventos/{evento}/preview-iframe', [\App\Http\Controllers\ReporteController::class, 'previewIframe'])->middleware('can:ver.reportes')->name('eventos.reporte.preview-iframe');
    });
    
    //MEDIA
    Route::get('/eventos/media/{media}', [\App\Http\Controllers\EventoController::class, 'destroyMedia'])
        ->middleware('can:eliminar.media')
        ->name('media.eventos.destroy');

    
    //PERSONAL
    Route::get('/personal', [\App\Http\Controllers\PersonalController::class, 'index'])
        ->middleware('can:ver.personal')
        ->name('personal.index');

    Route::get('/personal/create', [\App\Http\Controllers\PersonalController::class, 'create'])
        ->middleware('can:crear.personal')
        ->name('personal.create');

    Route::post('/personal/store/', [\App\Http\Controllers\PersonalController::class, 'store'])
        ->middleware('can:crear.personal')
        ->name('personal.store');

    Route::get('/personal/{id}/edit', [App\Http\Controllers\PersonalController::class, 'edit'])
        ->middleware('can:editar.personal')
        ->name('personal.edit');

    Route::put('/personal/{id}', [App\Http\Controllers\PersonalController::class, 'update'])
        ->middleware('can:editar.personal')
        ->name('personal.update');


    //INVENTARIO
    Route::get('/inventario', [\App\Http\Controllers\InventarioController::class, 'index'])
        ->middleware('can:ver.inventario')
        ->name('inventario.index');

    Route::get('/inventario/create', [\App\Http\Controllers\InventarioController::class, 'create'])
        ->middleware('can:crear.inventario')
        ->name('inventario.create');

    Route::post('/inventario/store/', [\App\Http\Controllers\InventarioController::class, 'store'])
        ->middleware('can:crear.inventario')
        ->name('inventario.store');

    Route::get('/inventario/{id}/edit', [App\Http\Controllers\InventarioController::class, 'edit'])
        ->middleware('can:editar.inventario')
        ->name('inventario.edit');

    Route::put('/inventario/{id}', [App\Http\Controllers\InventarioController::class, 'update'])
        ->middleware('can:editar.inventario')
        ->name('inventario.update');

    //PATRULLAS
    Route::get('/patrullas', [\App\Http\Controllers\PatrullaController::class, 'index'])
        ->middleware('can:ver.patrullas')
        ->name('patrullas.index');

    Route::get('/livewire/patrullas', [\App\Http\Controllers\PatrullaController::class, 'create'])
        ->middleware('can:crear.patrullas')
        ->name('patrullas.create');

    Route::get('/patrullas/location', [\App\Http\Controllers\MobileVehicleController::class, 'location'])
        // ->middleware('can:ver.location')
        ->name('patrullas.location');

    //DISPOSiTIVO-PATRULLA
    Route::get('/patrullas/{patrulla}/dispositivos', [\App\Http\Controllers\DispositivoPatrullaController::class, 'index'])
        ->middleware('can:asignar.dispositivos')
        ->name('patrullas.dispositivos');

    //TICKETS
    Route::get('/tickets/nuevo', [App\Http\Controllers\TicketController::class, 'index'])
        ->middleware('can:ver.tickets')
        ->name('tickets.nuevo');

    //NOTIFICACIONES
    Route::get('/admin/notificaciones', [App\Http\Controllers\NotificationController::class, 'admin'])
        ->middleware('can:administrar.notificaciones')
        ->name('notifications.admin');
    
    Route::get('/admin/notificaciones/crear', [App\Http\Controllers\NotificationController::class, 'create'])
        ->middleware('can:crear.notificaciones')
        ->name('admin.nueva-notif');
    
    Route::post('/admin/notificaciones', [App\Http\Controllers\NotificationController::class, 'store'])
        ->middleware('can:crear.notificaciones')
        ->name('notifications.store');

    Route::post('/notificaciones/{notification}/toggle', [NotificationController::class, 'toggle'])->name('notifications.toggle');
    
    Route::delete('/admin/notificaciones/{notification}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    Route::put('/admin/notificaciones/{notification}', [App\Http\Controllers\NotificationController::class, 'update'])->name('notifications.update');

    Route::get('/admin/notificaciones/{notification}/editar-datos', [App\Http\Controllers\NotificationController::class, 'editData'])
    ->middleware('can:administrar.notificaciones')
    ->name('notifications.edit.data');

    // RUTAS API
    Route::middleware('auth')->group(function () {
        // Obtener notificaciones del usuario actual
        Route::get('/notificaciones', [App\Http\Controllers\NotificationController::class, 'index'])
            ->name('notifications.index');
        
        // Contador de notificaciones sin leer
        Route::get('/notificaciones/contador', [App\Http\Controllers\NotificationController::class, 'unreadCount'])
            ->name('notifications.unread.count');
        
        // Marcar notificación como leída
        Route::post('/notificaciones/{notification}/leer', [App\Http\Controllers\NotificationController::class, 'markAsRead'])
            ->name('notifications.mark.read');
        
        // Descartar notificación
        Route::delete('/notificaciones/{notification}/descartar', [App\Http\Controllers\NotificationController::class, 'dismiss'])
            ->name('notifications.dismiss');
        
        // Marcar todas las notificaciones como leídas
        Route::post('/notificaciones/leer-todas', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])
            ->name('notifications.mark.all.read');
    });

    // CAMARAS LIST Y LIVEVIEW
    Route::get('/cameras', [App\Http\Controllers\CameraController::class, 'index'])->name('cameras.index');
    Route::get('/cameras/stream/{cameraIndexCode}', [App\Http\Controllers\CameraController::class, 'showStream'])->name('cameras.stream');
    
    Route::get('/test-env', function () {
        return [
            'HIKCENTRAL_URL' => env('HIKCENTRAL_URL'),
            'HIKCENTRAL_API_KEY' => env('HIKCENTRAL_API_KEY'),
            'HIKCENTRAL_API_SECRET' => env('HIKCENTRAL_API_SECRET'),
        ];
    });

    Route::get('/debug-env', function() {
        return [
            'HIKCENTRAL_URL' => env('HIKCENTRAL_URL'),
            'HIKCENTRAL_API_KEY' => env('HIKCENTRAL_API_KEY') ? 'SET' : 'NOT SET',
            'HIKCENTRAL_API_SECRET' => env('HIKCENTRAL_API_SECRET') ? 'SET' : 'NOT SET',
            'environment' => app()->environment()
        ];
    });

    // EMAILS
    Route::get('/preview-email', function () {
        $ticket = App\Models\Ticket::first();
        $user = App\Models\User::first();
        
        return new App\Mail\TicketCreatedNotification($ticket, $user->name);
    });

    Route::get('env', function () {
        return [
            'MAIL_MAILER' => env('MAIL_MAILER'),
            'MAIL_HOST' => env('MAIL_HOST'),
            'MAIL_PORT' => env('MAIL_PORT'),
            'MAIL_USERNAME' => env('MAIL_USERNAME'),
            'MAIL_PASSWORD' => env('MAIL_PASSWORD'),
            'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS'),
            'environment' => app()->environment()
        ];
    });

    Route::get('/test-email/{ticketId}', function ($ticketId) {
        $ticket = App\Models\Ticket::find($ticketId);
        $user = App\Models\User::first();
        
        if (!$ticket) {
            return response()->json(['error' => 'Ticket no encontrado'], 404);
        }
        
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)
                ->send(new App\Mail\TicketCreatedNotification($ticket, $user->name));
                
            return response()->json([
                'success' => true,
                'message' => 'Email enviado exitosamente',
                'to' => $user->email,
                'ticket_id' => $ticket->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    });

    // ALERTAS
    Route::get('/alertas', [App\Http\Controllers\AlertasController::class, 'index'])
        // ->middleware('can:ver.alertas')
        ->name('alertas.index');
    Route::post('/alertas/trigger-alarm', [App\Http\Controllers\AlertasController::class, 'triggerAlarm'])
        ->name('alertas.trigger-alarm');

    // MISIONES FLYTBASE
    Route::get('/misiones-flytbase', [\App\Http\Controllers\MisionFlytbaseController::class, 'index'])
        ->name('misiones-flytbase.index')
        ->middleware('can:ver.misiones');

    Route::post('/misiones-flytbase/{misionesFlytbase}/toggle-status', [\App\Http\Controllers\MisionFlytbaseController::class, 'toggleStatus'])
        ->name('misiones-flytbase.toggle-status')
        ->middleware('can:crear.misiones');
    
    Route::post('/misiones-flytbase', [App\Http\Controllers\MisionFlytbaseController::class, 'store'])
        ->name('misiones-flytbase.store')
        ->middleware('can:crear.misiones');
    
    Route::put('/misiones-flytbase/{misionesFlytbase}', [App\Http\Controllers\MisionFlytbaseController::class, 'update'])
        ->name('misiones-flytbase.update')
        ->middleware('can:crear.misiones');

    Route::delete('/misiones-flytbase/{misionesFlytbase}', [App\Http\Controllers\MisionFlytbaseController::class, 'destroy'])
        ->name('misiones-flytbase.destroy')
        ->middleware('can:crear.misiones');
    
    // LIVESTREAM 
    Route::get('/drones/{droneName}/liveview', [App\Http\Controllers\FlytbaseDroneController::class, 'liveview'])
        ->name('streaming.drone.liveview');


    Route::get('/alertas/liveview', [App\Http\Controllers\FlytbaseDroneController::class, 'liveview'])
        ->name('alertas.liveview');

    // API para obtener información del drone
    Route::get('/api/drones/info', [App\Http\Controllers\FlytbaseDroneController::class, 'getDroneInfo'])
        ->name('api.drones.info');

    Route::get('/debug-routes', function() {
        $routes = [
            'alertas.liveview' => route('alertas.liveview'),
            'alertas.index' => route('alertas.index'),
        ];
        
        \Log::debug('Rutas disponibles:', $routes);
        
        return response()->json($routes);
    })->name('debug.routes');

    Route::prefix('gallery')->name('gallery.')->group(function () {
        Route::get('/', [App\Http\Controllers\GalleryController::class, 'index'])->name('index');
        Route::get('/api', [App\Http\Controllers\GalleryController::class, 'apiIndex'])->name('api.index');
        Route::get('/mission/{drone}/{client}/{mission}', [App\Http\Controllers\GalleryController::class, 'missionShow'])->name('mission.show');
        Route::get('/thumbnails', [App\Http\Controllers\GalleryController::class, 'getThumbnails'])->name('thumbnails');
    });


});


Route::middleware(['auth'])->group(function () {
    Route::get('/admin/sistema', [App\Http\Controllers\SistemaController::class, 'index'])
    ->middleware('role:admin')
    ->name('sistema.permisos');
    Route::get('/admin/permisos', [App\Http\Controllers\SistemaController::class, 'asignar_permisos'])
    ->middleware('role:admin')
    ->name('asignar.permisos');
    
});


