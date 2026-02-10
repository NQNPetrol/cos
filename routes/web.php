<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Models\Evento;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\DispositivoPatrulla\AsignarDispositivos;
use App\Http\Controllers\DispositivoPatrullaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\LandingController;
use App\Models\Patrulla;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\UserClienteController;

// Landing Page Routes (públicas)
Route::get('/landing', [LandingController::class, 'index'])->name('landing');
Route::get('/landing-alt', [LandingController::class, 'indexAlt'])->name('landing.alt');
Route::post('/landing/contact', [LandingController::class, 'submitContact'])->name('landing.contact');

// Home - Redirige al landing para no autenticados, dashboard para autenticados
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('landing');
})->name('home');

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Página de sin acceso para usuarios sin rol asignado
Route::get('/no-access', function () {
    return view('auth.no-access');
})->middleware(['auth'])->name('no-access');

// DASHBOARD LAYOUT PRINCIPAL (ADMIN)
Route::get('/main-dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
  ->name('main.dashboard');

// API para gráficos del dashboard admin
Route::get('/main-dashboard/eventos-por-cliente', [\App\Http\Controllers\AdminDashboardController::class, 'getEventosPorCliente'])
    ->middleware(['auth', 'verified'])
    ->name('admin.dashboard.eventos-por-cliente');

Route::get('/main-dashboard/eventos-por-categoria', [\App\Http\Controllers\AdminDashboardController::class, 'getEventosPorCategoria'])
    ->middleware(['auth', 'verified'])
    ->name('admin.dashboard.eventos-por-categoria');

// LAYOUT CLIENTES
Route::middleware(['auth', 'verified'])->prefix('client')->name('client.')->group(function () {
    //DASHBOARD
    Route::get('/dashboard', [\App\Http\Controllers\ClientDashboardController::class, 'index'])
        ->name('dashboard');
    
    // API para gráficos del dashboard
    Route::get('/dashboard/eventos-por-empresa', [\App\Http\Controllers\ClientDashboardController::class, 'getEventosPorEmpresa'])
        ->name('dashboard.eventos-por-empresa');
    
    Route::get('/dashboard/eventos-por-categoria', [\App\Http\Controllers\ClientDashboardController::class, 'getEventosPorCategoria'])
        ->name('dashboard.eventos-por-categoria');
    
    Route::get('/dashboard/eventos-stacked', [\App\Http\Controllers\ClientDashboardController::class, 'getEventosStacked'])
        ->name('dashboard.eventos-stacked');
    
    Route::get('/dashboard/eventos-mensual', [\App\Http\Controllers\ClientDashboardController::class, 'getEventosMensual'])
        ->name('dashboard.eventos-mensual');

    Route::get('/dashboard/eventos-mapa-calor', [\App\Http\Controllers\ClientDashboardController::class, 'getEventosMapaCalor'])
        ->name('dashboard.eventos-mapa-calor');
    Route::get('/dashboard/eventos-por-ubicacion', [\App\Http\Controllers\ClientDashboardController::class, 'getEventosPorUbicacion'])
        ->name('dashboard.eventos-por-ubicacion');
    
    Route::get('/dashboard/pdf', [\App\Http\Controllers\ClientDashboardController::class, 'generatePdf'])
        ->name('dashboard.pdf');

    // PROFILE
    Route::get('/profile', function () {
        return view('client.profile.show');
    })->name('profile.show');

    // SETTINGS (CLIENT LAYOUT)
    Route::get('/settings/user-profile', \App\Livewire\Client\Settings\UserProfile::class)->name('settings.user-profile');
    Route::get('/settings/system', \App\Livewire\Client\Settings\SystemSettings::class)->name('settings.system');
    Route::get('/activity-log', \App\Livewire\Client\ActivityLog\Index::class)->name('activity-log');

    //EVENTOS (USA CONTROLADOR DIFERENTE)
    Route::get('/eventos/nuevo', [\App\Http\Controllers\EventoClientController::class, 'create'])
        ->middleware('can:crear.eventos-cliente')
        ->name('eventos.create');

    Route::get('/eventos', [\App\Http\Controllers\EventoClientController::class, 'index'])
        ->middleware('can:ver.eventos-cliente')
        ->name('eventos.index');

    Route::post('/eventos/store', [\App\Http\Controllers\EventoClientController::class, 'store'])
        ->middleware('can:crear.eventos-cliente')
        ->name('eventos.store');

    Route::get('/eventos/{evento}/edit', [\App\Http\Controllers\EventoClientController::class, 'edit'])
        ->middleware('can:editar.eventos-cliente')
        ->name('eventos.edit');

    Route::put('/eventos/{evento}/update', [\App\Http\Controllers\EventoClientController::class, 'update'])
        ->middleware('can:editar.eventos-cliente')
        ->name('eventos.update');

    Route::delete('/eventos/{evento}/destroy', [\App\Http\Controllers\EventoClientController::class, 'destroy'])
        ->middleware('can:eliminar.eventos-cliente')
        ->name('eventos.destroy');
    
    Route::post('/eventos/{evento}/anular', [\App\Http\Controllers\EventoClientController::class, 'anular'])
        ->middleware('can:anular.eventos-cliente')
        ->name('eventos.anular');

    Route::post('/eventos/{evento}/notas-adicionales', [\App\Http\Controllers\EventoClientController::class, 'agregarNotasAdicionales'])
        ->middleware('can:agregar-notas.eventos-cliente')
        ->name('eventos.notas-adicionales');

    //REPORTES (USA CONTROLADOR DIFERENTE)
    Route::get('/eventos/{evento}/reporte', [\App\Http\Controllers\ReporteClientController::class, 'preview'])
        ->middleware('can:ver.reportes-cliente')
        ->name('eventos.reporte.preview');

    Route::post('/eventos/{evento}/reporte/generar', [\App\Http\Controllers\ReporteClientController::class, 'generate'])
        ->middleware('can:generar.reportes-cliente')
        ->name('eventos.reporte.generate');

    Route::get('/reportes/{reporte}/download', [\App\Http\Controllers\ReporteClientController::class, 'download'])
        ->middleware('can:generar.reportes-cliente')
        ->name('reportes.download');

    Route::get('/reportes/{reporte}/view', [\App\Http\Controllers\ReporteClientController::class, 'view'])
        ->middleware('can:ver.reportes-cliente')
        ->name('reportes.view');

    Route::get('/eventos/{evento}/preview-iframe', [\App\Http\Controllers\ReporteClientController::class, 'previewIframe'])
        ->middleware('can:ver.reportes-cliente')
        ->name('eventos.reporte.preview-iframe');
    
        
    //SEGUIMIENTOS (USA MISMO CONTROLADOR)
    Route::get('/seguimientos', [\App\Http\Controllers\SeguimientoController::class,'indexClientLayout'])
        ->middleware('can:ver.seguimientos-cliente')
        ->name('seguimientos.index');

    // PATRULLAS (USA MISMO CONTROLADOR)
    Route::get('/patrullas', [\App\Http\Controllers\PatrullaController::class, 'indexClient'])
        ->name('patrullas.index')
        ->middleware('can:ver.patrullas-cliente');

    //MOBILE VEHICLE USA NUEVO CONTROLADOR

    Route::get('/patrullas/mapa', [\App\Http\Controllers\MobileVehicleClientController::class, 'locationClient'])
        ->name('patrullas.location')
        ->middleware('can:ver.location-cliente');

    //TICKETS Y NOTIFICACIONES (mismo controlador)
    Route::get('/tickets/nuevo', [App\Http\Controllers\TicketController::class, 'indexClient'])
        ->middleware('can:ver.tickets-cliente')
        ->name('tickets.nuevo');

    //TRIGGER ALERTAS FLYTBASE (nuevo controllador)
    Route::get('/misiones', [App\Http\Controllers\AlertasClientController::class, 'index'])
        ->middleware('can:ver.alertas-cliente')
        ->name('alertas.index');

    Route::post('/misiones/trigger', [App\Http\Controllers\AlertasClientController::class, 'triggerAlarm'])
        ->name('alertas.trigger-alarm')
        ->middleware('can:trigger.alertas-cliente');

    // LIVESTREAM 
    Route::get('/drones/{droneName}/liveview', [App\Http\Controllers\FlytbaseDroneController::class, 'liveviewClient'])
        ->name('streaming.drone.liveview')
        ->middleware('can:ver.liveview-cliente');
        

    //FLIGHT LOGS
    Route::get('/flight-logs', function () {
        return view('flightlogs.client.index');
    })->name('flight-logs')->middleware('can:ver.flightlogs-cliente');

    //GALLERY
    Route::prefix('gallery')->name('gallery.')->group(function () {
        Route::get('/', [App\Http\Controllers\GalleryClientController::class, 'index'])->name('index')->middleware('can:ver.galeria-cliente');
        Route::get('/api', [App\Http\Controllers\GalleryClientController::class, 'apiIndex'])->name('api.index')->middleware('can:ver.galeria-cliente');
        Route::get('/mission/{drone}/{client}/{mission}', [App\Http\Controllers\GalleryClientController::class, 'missionShow'])->name('mission.show')->middleware('can:ver.galeria-cliente');
        Route::get('/thumbnails', [App\Http\Controllers\GalleryClientController::class, 'getThumbnails'])->name('thumbnails')->middleware('can:ver.galeria-cliente');
    });

    //MISIONES
    Route::get('/planificar-misiones', function () {
        return view('misiones-flytbase.client.index');
    })->name('misiones')->middleware('can:crear.peticion-misiones');

    // EMPRESAS ASOCIADAS (CLIENT ADMIN)
    Route::get('/empresas-asociadas', \App\Livewire\Client\EmpresasAsociadas\Index::class)
        ->name('empresas-asociadas.index');

    // ADMINISTRACIÓN DE USUARIOS (CLIENT ADMIN)
    Route::get('/usuarios', \App\Livewire\Client\UsuariosCliente\Index::class)
        ->name('usuarios.index')
        ->middleware('role:clientadmin');

    // DASHBOARD OPERACIONES
    Route::get('/operaciones/dashboard', [\App\Http\Controllers\ClientOperacionesDashboardController::class, 'index'])
        ->name('operaciones.dashboard')
        ->middleware('can:ver.operaciones-cliente');

    // Checklist (clientsupervisor)
    Route::get('/checklist', [\App\Http\Controllers\ChecklistPatrullaController::class, 'index'])->name('checklist.index');
    Route::post('/checklist', [\App\Http\Controllers\ChecklistPatrullaController::class, 'store'])->name('checklist.store');

    // Calendario (clientsupervisor, solo lectura)
    Route::get('/calendario', [\App\Http\Controllers\CalendarioClienteController::class, 'index'])->name('calendario.index');
    Route::get('/calendario/eventos', [\App\Http\Controllers\CalendarioClienteController::class, 'getEventos'])->name('calendario.eventos');

});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/user-profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    Route::get('settings/user-profile', \App\Livewire\Settings\UserProfile::class)->name('settings.user-profile');
    Route::get('settings/system', \App\Livewire\Settings\SystemSettings::class)->name('settings.system');
    Route::get('activity-log', \App\Livewire\ActivityLog\Index::class)->name('activity-log.index');
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

    Route::delete('/clientes/{cliente}/delete-logo', [\App\Http\Controllers\ClienteController::class, 'deleteLogo'])
        ->name('cliente.delete-logo');

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
    Route::put('/usuarios/{user}/reset-password', [App\Http\Controllers\UserController::class, 'resetPassword'])->middleware('can:resetar.contraseña')->name('usuarios.reset-password');
    
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

    Route::put('/eventos/{evento}/update', [\App\Http\Controllers\EventoController::class, 'update'])
        ->middleware('can:editar.eventos')
        ->name('eventos.update');

    Route::delete('/eventos/{evento}/destroy', [\App\Http\Controllers\EventoController::class, 'destroy'])
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
        ->middleware('can:ver.location')
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

    Route::post('/notificaciones/{notification}/toggle', [NotificationController::class, 'toggle'])->name('notifications.toggle')->middleware('can:crear.notif');
    
    Route::delete('/admin/notificaciones/{notification}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy')->middleware('can:crear.notif');
    
    Route::put('/admin/notificaciones/{notification}', [App\Http\Controllers\NotificationController::class, 'update'])->name('notifications.update')->middleware('can:crear.notif');

    Route::get('/admin/notificaciones/{notification}/editar-datos', [App\Http\Controllers\NotificationController::class, 'editData'])
        ->middleware('can:administrar.notificaciones')
        ->name('notifications.edit.data');

    // RUTAS API
    Route::middleware('auth')->group(function () {
        // Obtener notificaciones del usuario actual
        Route::get('/notificaciones', [App\Http\Controllers\NotificationController::class, 'index'])
            ->name('notifications.index')
            ->middleware('can:crear.notif');
        
        // Contador de notificaciones sin leer
        Route::get('/notificaciones/contador', [App\Http\Controllers\NotificationController::class, 'unreadCount'])
            ->name('notifications.unread.count')
            ->middleware('can:crear.notif');
        
        // Marcar notificación como leída
        Route::post('/notificaciones/{notification}/leer', [App\Http\Controllers\NotificationController::class, 'markAsRead'])
            ->name('notifications.mark.read')
            ->middleware('can:crear.notif');
        
        // Descartar notificación
        Route::delete('/notificaciones/{notification}/descartar', [App\Http\Controllers\NotificationController::class, 'dismiss'])
            ->name('notifications.dismiss')
            ->middleware('can:crear.notif');
        
        // Marcar todas las notificaciones como leídas
        Route::post('/notificaciones/leer-todas', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])
            ->name('notifications.mark.all.read')
            ->middleware('can:crear.notif');
    });

    // CAMARAS LIST Y LIVEVIEW
    Route::get('/cameras', [App\Http\Controllers\CameraController::class, 'index'])
        ->name('cameras.index')
        ->middleware('can:ver.camaras');

    Route::get('/cameras/stream/{cameraIndexCode}', [App\Http\Controllers\CameraController::class, 'showStream'])
        ->name('cameras.stream')
        ->middleware('can:ver.camaras');

    // API para vincular cámaras con dispositivos
    Route::get('/api/dispositivos/camaras', [App\Http\Controllers\CameraController::class, 'availableDevices'])
        ->name('api.dispositivos.camaras')
        ->middleware('can:ver.camaras');

    Route::post('/cameras/{camera}/link-device', [App\Http\Controllers\CameraController::class, 'linkDevice'])
        ->name('cameras.link-device')
        ->middleware('can:ver.camaras');
    
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
        ->middleware('can:ver.alertas')
        ->name('alertas.index');
    Route::post('/alertas/trigger-alarm', [App\Http\Controllers\AlertasController::class, 'triggerAlarm'])
        ->name('alertas.trigger-alarm')
        ->middleware('can:trigger.alertas');

    // MISIONES FLYTBASE
    Route::get('/misiones-flytbase', [\App\Http\Controllers\MisionFlytbaseController::class, 'index'])
        ->name('misiones-flytbase.index')
        ->middleware('can:ver.misiones');

    Route::post('/misiones-flytbase/process-kmz', [\App\Http\Controllers\MisionFlytbaseController::class, 'processKmz'])
        ->name('misiones-flytbase.process-kmz')
        ->middleware('can:crear.misiones');

    // Ruta para que clientes procesen KMZ en peticiones
    Route::post('/peticiones-misiones/process-kmz', [\App\Http\Controllers\MisionFlytbaseController::class, 'processKmz'])
        ->name('peticiones-misiones.process-kmz')
        ->middleware('can:crear.peticion-misiones');

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
    
    // FLIGHT LOGS ADMIN
    Route::get('/flight-logs', function () {
        return view('flightlogs.admin.index');
    })->name('flight-logs.index')->middleware('can:ver.flightlogs');

    // LIVESTREAM 
    Route::get('/drones/{droneName}/liveview', [App\Http\Controllers\FlytbaseDroneController::class, 'liveview'])
        ->name('streaming.drone.liveview')
        ->middleware('can:ver.liveview');


    Route::get('/alertas/liveview', [App\Http\Controllers\FlytbaseDroneController::class, 'liveview'])
        ->name('alertas.liveview')
        ->middleware('can:ver.liveview');

    //DRONES
    // API para obtener información del drone
    Route::get('/api/drones/info', [App\Http\Controllers\FlytbaseDroneController::class, 'getDroneInfo'])
        ->name('api.drones.info')
        ->middleware('can:ver.droneInfo');

    Route::get('/drones-flytbase', [App\Http\Controllers\FlytbaseDroneController::class, 'index'])
        ->name('drones-flytbase.index')
        ->middleware('can:ver.drones');

    Route::delete('/drones-flytbase/{drones_flytbase}', [App\Http\Controllers\FlytbaseDroneController::class, 'destroy'])
        ->name('drones-flytbase.destroy')
        ->middleware('can:eliminar.drones');

    Route::post('/drones-flytbase', [App\Http\Controllers\FlytbaseDroneController::class, 'store'])
        ->name('drones-flytbase.store')
        ->middleware('can:crear.drones');

    Route::put('/drones-flytbase/{drones_flytbase}', [App\Http\Controllers\FlytbaseDroneController::class, 'update'])
        ->name('drones-flytbase.update')
        ->middleware('can:crear.drones');

    //DOCKS
    Route::get('/docks-flytbase', [App\Http\Controllers\FlytbaseDockController::class, 'index'])
    ->name('docks-flytbase.index')
    ->middleware('can:ver.docks');

    Route::post('/docks-flytbase', [App\Http\Controllers\FlytbaseDockController::class, 'store'])
        ->name('docks-flytbase.store')
        ->middleware('can:crear.docks');

    Route::put('/docks-flytbase/{flytbase_dock}', [App\Http\Controllers\FlytbaseDockController::class, 'update'])
        ->name('docks-flytbase.update')
        ->middleware('can:crear.docks');

    Route::delete('/docks-flytbase/{flytbase_dock}', [App\Http\Controllers\FlytbaseDockController::class, 'destroy'])
        ->name('docks-flytbase.destroy')
        ->middleware('can:eliminar.docks');


    //DEBUG
    Route::get('/debug-routes', function() {
        $routes = [
            'alertas.liveview' => route('alertas.liveview'),
            'alertas.index' => route('alertas.index'),
        ];
        
        \Log::debug('Rutas disponibles:', $routes);
        
        return response()->json($routes);
    })->name('debug.routes');

    //GALERIA
    Route::prefix('gallery')->name('gallery.')->group(function () {
        Route::get('/', [App\Http\Controllers\GalleryController::class, 'index'])->name('index')->middleware('can:ver.galeria');
        Route::get('/api', [App\Http\Controllers\GalleryController::class, 'apiIndex'])->name('api.index')->middleware('can:importar.galeria');
        Route::get('/mission/{drone}/{client}/{mission}', [App\Http\Controllers\GalleryController::class, 'missionShow'])->name('mission.show');
        Route::get('/thumbnails', [App\Http\Controllers\GalleryController::class, 'getThumbnails'])->name('thumbnails')->middleware('can:ver.galeria');
    });

    // PILOTOS 
    Route::get('/pilotos/asignar-clientes', function () {
        return view('pilotos.index');
    })->name('pilotos.index')->middleware('can:ver.pilotos');

    //PETICIONES MISIONES 
    Route::get('/misiones/peticiones-clientes', [\App\Http\Controllers\PeticionesMisionesClient::class, 'index'])
        ->name('peticiones.index')
        ->middleware('can:ver.peticiones');

    //SITES
    Route::get('/sites', function () {
        return view('sites-flytbase.index');
    })->name('sites.index')->middleware('can:ver.sites');

    //OBJETIVOS AIPEM
    Route::get('/objetivos-a', function () {
        return view('objetivos.aipem.index');
    })->name('objetivos-aipem.index')->middleware('can:ver.objetivos-aipem');

    //ANPR RECORDS
    Route::get('/anpr/records', [\App\Http\Controllers\AnprPassingRecordController::class, 'index'])
        ->name('anpr.index')->middleware('can:ver.registros-anpr');
    Route::post('/anpr/import', [\App\Http\Controllers\AnprPassingRecordController::class, 'importLast24Hours'])->name('anpr.import')->middleware('can:importar.registros-anpr');
    Route::get('/anpr/stats', [\App\Http\Controllers\AnprPassingRecordController::class, 'getStats'])->name('stats')->middleware('can:ver.registros-anpr');

    Route::get('/anpr/event-image/{recordId}', \App\Livewire\HikCentralImages\ViewEventImage::class)
        ->name('anpr.view-image')->middleware('can:ver.registros-anpr');

    // RODADOS
    Route::prefix('rodados')->name('rodados.')->group(function () {
        // Vista principal (solo middleware en la ruta principal)
        Route::get('/', [\App\Http\Controllers\RodadoController::class, 'index'])->name('index');
        
        // CRUD Rodados
        Route::post('/', [\App\Http\Controllers\RodadoController::class, 'store'])->name('store');
        Route::put('/{rodado}', [\App\Http\Controllers\RodadoController::class, 'update'])->name('update');
        Route::delete('/{rodado}', [\App\Http\Controllers\RodadoController::class, 'destroy'])->name('destroy');
        
        // Turnos Rodados (unificado: service y mecánicos)
        Route::get('/turnos/{turno}', [\App\Http\Controllers\TurnoRodadoController::class, 'show'])->name('turnos.show');
        Route::post('/turnos', [\App\Http\Controllers\TurnoRodadoController::class, 'store'])->name('turnos.store');
        Route::put('/turnos/{turno}', [\App\Http\Controllers\TurnoRodadoController::class, 'update'])->name('turnos.update');
        Route::delete('/turnos/{turno}', [\App\Http\Controllers\TurnoRodadoController::class, 'destroy'])->name('turnos.destroy');
        Route::post('/turnos/{turno}/adjuntar-factura', [\App\Http\Controllers\TurnoRodadoController::class, 'adjuntarFactura'])->name('turnos.adjuntar-factura');
        Route::post('/turnos/{turno}/aprobar-cobertura', [\App\Http\Controllers\TurnoRodadoController::class, 'aprobarCobertura'])->name('turnos.aprobar-cobertura');
        Route::post('/turnos/{turno}/rechazar-cobertura', [\App\Http\Controllers\TurnoRodadoController::class, 'rechazarCobertura'])->name('turnos.rechazar-cobertura');
        Route::post('/turnos/{turno}/cancelar', [\App\Http\Controllers\TurnoRodadoController::class, 'cancelarTurno'])->name('turnos.cancelar');
        Route::post('/turnos/{turno}/reprogramar', [\App\Http\Controllers\TurnoRodadoController::class, 'reprogramarTurno'])->name('turnos.reprogramar');
        
        // Cambios de Equipos
        Route::post('/cambios-equipos', [\App\Http\Controllers\CambioEquipoRodadoController::class, 'store'])->name('cambios-equipos.store');
        Route::put('/cambios-equipos/{cambio}', [\App\Http\Controllers\CambioEquipoRodadoController::class, 'update'])->name('cambios-equipos.update');
        Route::delete('/cambios-equipos/{cambio}', [\App\Http\Controllers\CambioEquipoRodadoController::class, 'destroy'])->name('cambios-equipos.destroy');
        Route::post('/cambios-equipos/{cambio}/adjuntar-factura', [\App\Http\Controllers\CambioEquipoRodadoController::class, 'adjuntarFactura'])->name('cambios-equipos.adjuntar-factura');
        
        // Kilometraje
        Route::post('/kilometraje', [\App\Http\Controllers\RegistroKilometrajeController::class, 'store'])->name('kilometraje.store');
        Route::delete('/kilometraje/{registro}', [\App\Http\Controllers\RegistroKilometrajeController::class, 'destroy'])->name('kilometraje.destroy');
        
        // Pagos Servicios (unificado: patente, alquiler, proveedor, etc.)
        Route::post('/pagos', [\App\Http\Controllers\PagoServiciosRodadoController::class, 'store'])->name('pagos.store');
        Route::put('/pagos/{pago}', [\App\Http\Controllers\PagoServiciosRodadoController::class, 'update'])->name('pagos.update');
        Route::delete('/pagos/{pago}', [\App\Http\Controllers\PagoServiciosRodadoController::class, 'destroy'])->name('pagos.destroy');
        Route::post('/pagos/{pago}/adjuntar-factura', [\App\Http\Controllers\PagoServiciosRodadoController::class, 'adjuntarFactura'])->name('pagos.adjuntar-factura');
        
        // Calendario
        Route::get('/calendario', [\App\Http\Controllers\CalendarioRodadosController::class, 'index'])->name('calendario.index');
        Route::get('/calendario/eventos', [\App\Http\Controllers\CalendarioRodadosController::class, 'getEventos'])->name('calendario.eventos');
        Route::get('/calendario/evento/{tipo}/{id}', [\App\Http\Controllers\CalendarioRodadosController::class, 'getDetalleEvento'])->name('calendario.evento');
        
        // Proveedores y Talleres (CRUD auxiliar)
        Route::apiResource('proveedores', \App\Http\Controllers\ProveedorController::class)->parameters(['proveedores' => 'proveedor']);
        Route::apiResource('talleres', \App\Http\Controllers\TallerController::class)->parameters(['talleres' => 'taller']);

        // Dashboard Admin Rodados
        Route::get('/admin-dashboard', [\App\Http\Controllers\AdminRodadosDashboardController::class, 'index'])->name('admin-dashboard');
        Route::get('/admin-dashboard/pagos-mensuales', [\App\Http\Controllers\AdminRodadosDashboardController::class, 'getPagosMensuales'])->name('admin-dashboard.pagos-mensuales');
        Route::get('/admin-dashboard/turnos-por-estado', [\App\Http\Controllers\AdminRodadosDashboardController::class, 'getTurnosPorEstado'])->name('admin-dashboard.turnos-por-estado');
        Route::get('/admin-dashboard/cobros-vs-pagos', [\App\Http\Controllers\AdminRodadosDashboardController::class, 'getCobrosVsPagos'])->name('admin-dashboard.cobros-vs-pagos');

        // Proveedores y Talleres (vista unificada)
        Route::get('/proveedores-talleres', function () {
            $proveedores = \App\Models\Proveedor::with('talleres')->orderBy('nombre')->get();
            $talleres = \App\Models\Taller::with('proveedor')->orderBy('nombre')->get();
            return view('rodados.proveedores-talleres', compact('proveedores', 'talleres'));
        })->name('proveedores-talleres.index');

        // Pagos de Servicios (vista independiente)
        Route::get('/pagos-servicios', function () {
            $pagos = \App\Models\PagoServiciosRodado::with(['rodado', 'proveedor', 'servicioUsuario', 'turnoRodado.taller'])->get();
            $rodados = \App\Models\Rodado::with(['cliente', 'proveedor'])->get();
            $proveedores = \App\Models\Proveedor::orderBy('nombre')->get();
            $servicios = \App\Models\ServicioUsuario::activos()->orderBy('nombre')->get();
            $pagosRealizados = $pagos->where('estado', 'pagado')->sortByDesc('fecha_pago');
            $pagosPendientes = $pagos->where('estado', '!=', 'pagado')->sortBy('fecha_vencimiento');
            return view('rodados.pagos', compact('pagos', 'rodados', 'proveedores', 'servicios', 'pagosRealizados', 'pagosPendientes'));
        })->name('pagos-servicios.index');

        // Servicios del usuario (CRUD modal)
        Route::apiResource('servicios-usuario', \App\Http\Controllers\ServicioUsuarioController::class);

        // Cobranzas
        Route::prefix('cobranzas')->name('cobranzas.')->group(function () {
            Route::get('/', [\App\Http\Controllers\CobranzaController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\CobranzaController::class, 'store'])->name('store');
            Route::put('/{cobranza}', [\App\Http\Controllers\CobranzaController::class, 'update'])->name('update');
            Route::delete('/{cobranza}', [\App\Http\Controllers\CobranzaController::class, 'destroy'])->name('destroy');
            Route::post('/{cobranza}/adjuntar', [\App\Http\Controllers\CobranzaController::class, 'adjuntar'])->name('adjuntar');
        });

        // Alertas Admin
        Route::prefix('alertas-admin')->name('alertas-admin.')->group(function () {
            Route::get('/', [\App\Http\Controllers\AlertaAdminController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\AlertaAdminController::class, 'store'])->name('store');
            Route::put('/{alerta}', [\App\Http\Controllers\AlertaAdminController::class, 'update'])->name('update');
            Route::delete('/{alerta}', [\App\Http\Controllers\AlertaAdminController::class, 'destroy'])->name('destroy');
            Route::post('/{alerta}/toggle', [\App\Http\Controllers\AlertaAdminController::class, 'toggle'])->name('toggle');
        });

        // Documentacion unificada para turnos
        Route::post('/turnos/{turno}/documentacion', [\App\Http\Controllers\TurnoRodadoController::class, 'adjuntarDocumentacion'])->name('turnos.documentacion');
    });

});


Route::middleware(['auth'])->group(function () {
    Route::get('/admin/sistema', [App\Http\Controllers\SistemaController::class, 'index'])
    ->middleware('role:admin')
    ->name('sistema.permisos');
    Route::get('/admin/permisos', [App\Http\Controllers\SistemaController::class, 'asignar_permisos'])
    ->middleware('role:admin')
    ->name('asignar.permisos');
    
    // Dashboard Operacional - Principal (layout admin)
    Route::get('/operaciones/dashboard', [App\Http\Controllers\OperacionesDashboardController::class, 'index'])
        ->middleware('can:ver.operaciones')
        ->name('operaciones.dashboard');

    // Dashboard Operacional - Cliente (layout clientes)
    Route::get('/client/operaciones/dashboard', [App\Http\Controllers\OperacionesDashboardController::class, 'indexClient'])
        ->middleware('can:ver.operaciones-cliente')
        ->name('client.operaciones.dashboard');

    // APIs del Dashboard Operacional
    Route::prefix('api/operaciones')->group(function () {
        Route::get('/kpis', [App\Http\Controllers\OperacionesDashboardController::class, 'getKPIs'])
            ->name('api.operaciones.kpis');
        
        Route::get('/map-data', [App\Http\Controllers\OperacionesDashboardController::class, 'getMapData'])
            ->name('api.operaciones.map-data');
        
        Route::get('/eventos', [App\Http\Controllers\OperacionesDashboardController::class, 'getEventos'])
            ->name('api.operaciones.eventos');
    });
});


