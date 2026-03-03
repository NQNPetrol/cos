# Blueprint — Centro de Operaciones de Seguridad (CyH Sur)

> Documento de arquitectura técnica para agentes de desarrollo. Describe el stack, estructura, módulos, rutas, vistas y convenciones del proyecto COS.

---

## 🎯 Visión técnica

COS es una aplicación web fullstack que centraliza la administración y las operaciones de CyH Sur SA: gestión de clientes y empresas asociadas, flota de rodados, patrullas, eventos, contratos, seguimientos, integración con drones (Flytbase) y cámaras (HikCentral), tickets, ingresos y nómina.

La arquitectura prioriza una única aplicación web servida en un servidor: dos perfiles de uso (admin y cliente) mediante rutas y permisos, sin separación física de frontends. El backend es monolítico Laravel; la interactividad se resuelve con Livewire para evitar duplicar lógica en una SPA.

---

## 🏗️ Stack tecnológico

| Capa | Tecnología | Notas |
|------|-----------|--------|
| Backend | PHP 8.2 + Laravel 12 | API web, lógica de negocio, colas |
| Auth / equipos | Laravel Jetstream 5.3 + Sanctum | Autenticación, equipos, API tokens |
| UI reactiva | Livewire 3, Volt, Flux | Componentes sin escribir JS dedicado |
| Base de datos | MySQL | Persistencia principal |
| Frontend build | Vite 6 | Bundling de CSS/JS |
| Estilos | Tailwind CSS 4, Alpine.js | UI y comportamiento en el cliente |
| Gráficos / mapas | Chart.js, Leaflet (+ heat) | Dashboards y mapas |
| PDF | Barryvdh DomPDF | Reportes y documentos |
| Permisos / auditoría | Spatie Permission, Spatie Activity Log | Roles y registro de actividad |
| Integraciones | AWS SDK, Guzzle | S3, servicios externos |
| Testing | Pest | Tests automatizados |
| Estilo de código | Laravel Pint | Formato consistente |
| CI/CD | GitHub Actions | Workflow en el repo |

---

## 📁 Estructura del proyecto

```
cos/
│
├── app/
│   ├── Http/Controllers/     # Controladores web (admin y client)
│   ├── Livewire/             # Componentes Livewire por módulo
│   │   ├── Admin/            # Roles, permisos
│   │   ├── Client/           # Perfil, empresas, usuarios cliente, activity log
│   │   │   ├── ActivityLog/Index.php
│   │   │   ├── EmpresasAsociadas/Index.php
│   │   │   ├── Settings/UserProfile.php, SystemSettings.php
│   │   │   └── UsuariosCliente/Index.php
│   │   ├── Contratos/        # CRUD contratos (Edit.php)
│   │   ├── DispositivoPatrulla/
│   │   ├── EmpresasAsociadas/
│   │   ├── FlotasVehiculares/ # DocumentacionPatrullaListado.php, SistemaPatrullaListado.php
│   │   ├── HikCentralImages/  # ViewEventImage.php
│   │   ├── Inventario/
│   │   ├── Objetivos/
│   │   ├── Patrullas/        # Listado.php, ListadoCliente.php, Patrullas.php
│   │   ├── Personal/
│   │   ├── Rodados/          # CalendarioRodados.php
│   │   ├── Seguimientos/     # NuevoSeguimiento.php, VerSeguimientos.php, VerSeguimientosCliente.php
│   │   ├── Settings/         # Appearance, Password, Profile, UserProfile, SystemSettings
│   │   ├── Sites/
│   │   └── Tickets/          # ManageTickets.php, ManageTicketsClient.php
│   ├── Models/               # Eloquent (Cliente, Rodado, Evento, Patrulla, etc.)
│   ├── Services/             # HikCentral, Gallery, KmzParser
│   ├── Mail/                 # TicketCreatedNotification
│   ├── Providers/
│   ├── Observers/            # PermissionObserver
│   └── Listeners/
│
├── config/                   # Configuración Laravel
├── database/                 # Migraciones, seeders
├── resources/
│   ├── views/                # Blade (layouts admin, client, livewire)
│   ├── css/                  # app.css, modern-ui.css
│   └── js/                   # app.js, dashboards, landing
├── routes/
│   └── web.php               # Rutas públicas, client/*, admin (auth)
├── docs/                     # Documentación y kit de agentes
└── tests/
```

---

## 🔄 Flujo de datos principal

- **Web (navegador):** el usuario accede a la app vía HTTP; las rutas distinguen prefijo `client/` (layout cliente) del resto (layout admin).
- **Laravel:** middleware `auth` + `verified`; permisos con Spatie (`can:ver.eventos`, etc.). Controladores y Livewire cargan modelos y devuelven vistas o respuestas Livewire.
- **Persistencia:** MySQL vía Eloquent; archivos/medios en disco o S3 según configuración.
- **Integraciones:** llamadas salientes a HikCentral (eventos, imágenes), Flytbase (drones, misiones), AWS (S3, webhooks entrantes en `S3WebhookController`).

```
Usuario → Navegador → Laravel (routes → middleware → Controller/Livewire)
                            → Models (MySQL)
                            → Services (HikCentral, Flytbase, S3)
                            → Vista Blade / respuesta Livewire → HTML/JS
```

---

## 🗺️ Mapa de rutas, vistas y componentes Livewire

> **Convención de layouts:**
> - Rutas con prefijo `/client/*` → usan layout **cliente** (`resources/views/layouts/client.blade.php`)
> - Resto de rutas autenticadas → usan layout **admin** (`resources/views/layouts/app.blade.php`)
> - Rutas con componente Livewire directo (`Route::get(..., ComponentClass::class)`) → el componente devuelve su propia vista Livewire.

---

### 🌐 Rutas públicas

| Método | URI | Controlador/Acción | Nombre |
|--------|-----|-------------------|---------|
| GET | `/landing` | `LandingController@index` | `landing` |
| GET | `/landing-alt` | `LandingController@indexAlt` | `landing.alt` |
| POST | `/landing/contact` | `LandingController@submitContact` | `landing.contact` |
| GET | `/` | closure (redirect) | `home` |
| GET | `/login` | Jetstream (`routes/auth.php`) | `login` |
| POST | `/login` | Jetstream — procesa autenticación | — |
| POST | `/logout` | Jetstream | `logout` |
| GET | `/no-access` | view `auth.no-access` | `no-access` |

---

### 🏠 Dashboards

| Método | URI | Controlador/Acción | Nombre | Permiso |
|--------|-----|-------------------|--------|---------|
| GET | `/dashboard` | `DashboardController@index` | `dashboard` | auth |
| GET | `/main-dashboard` | `AdminDashboardController@index` | `main.dashboard` | auth |
| GET | `/main-dashboard/eventos-por-cliente` | `AdminDashboardController@getEventosPorCliente` | `admin.dashboard.eventos-por-cliente` | auth |
| GET | `/main-dashboard/eventos-por-categoria` | `AdminDashboardController@getEventosPorCategoria` | `admin.dashboard.eventos-por-categoria` | auth |

---

### 👤 Layout Cliente (`/client/*`) — middleware: auth, verified

#### Dashboards cliente

| Método | URI | Controlador/Acción | Nombre | Permiso |
|--------|-----|-------------------|--------|---------|
| GET | `/client/dashboard` | `ClientDashboardController@index` | `client.dashboard` | auth |
| GET | `/client/dashboard/pdf` | `ClientDashboardController@generatePdf` | `client.dashboard.pdf` | auth |
| GET | `/client/operaciones/dashboard` | `ClientOperacionesDashboardController@index` | `client.operaciones.dashboard` | `can:ver.operaciones-cliente` |
| GET | `/client/dashboard-patrullas` | `PatrullasDashboardController@index` | `client.dashboard-patrullas` | auth |

#### Eventos cliente

| Método | URI | Controlador/Acción | Nombre | Permiso |
|--------|-----|-------------------|--------|---------|
| GET | `/client/eventos` | `EventoClientController@index` | `client.eventos.index` | `can:ver.eventos-cliente` |
| GET | `/client/eventos/nuevo` | `EventoClientController@create` | `client.eventos.create` | `can:crear.eventos-cliente` |
| POST | `/client/eventos/store` | `EventoClientController@store` | `client.eventos.store` | `can:crear.eventos-cliente` |
| GET | `/client/eventos/{evento}/edit` | `EventoClientController@edit` | `client.eventos.edit` | `can:editar.eventos-cliente` |
| PUT | `/client/eventos/{evento}/update` | `EventoClientController@update` | `client.eventos.update` | `can:editar.eventos-cliente` |
| DELETE | `/client/eventos/{evento}/destroy` | `EventoClientController@destroy` | `client.eventos.destroy` | `can:eliminar.eventos-cliente` |
| POST | `/client/eventos/{evento}/anular` | `EventoClientController@anular` | `client.eventos.anular` | `can:anular.eventos-cliente` |
| POST | `/client/eventos/{evento}/notas-adicionales` | `EventoClientController@agregarNotasAdicionales` | `client.eventos.notas-adicionales` | `can:agregar-notas.eventos-cliente` |

#### Reportes cliente

| Método | URI | Controlador/Acción | Nombre | Permiso |
|--------|-----|-------------------|--------|---------|
| GET | `/client/eventos/{evento}/reporte` | `ReporteClientController@preview` | `client.eventos.reporte.preview` | `can:ver.reportes-cliente` |
| POST | `/client/eventos/{evento}/reporte/generar` | `ReporteClientController@generate` | `client.eventos.reporte.generate` | `can:generar.reportes-cliente` |
| GET | `/client/reportes/{reporte}/download` | `ReporteClientController@download` | `client.reportes.download` | `can:generar.reportes-cliente` |
| GET | `/client/reportes/{reporte}/view` | `ReporteClientController@view` | `client.reportes.view` | `can:ver.reportes-cliente` |

#### Seguimientos, Patrullas, Recorridos cliente

| Método | URI | Controlador/Acción | Nombre | Permiso |
|--------|-----|-------------------|--------|---------|
| GET | `/client/seguimientos` | `SeguimientoController@indexClientLayout` | `client.seguimientos.index` | `can:ver.seguimientos-cliente` |
| GET | `/client/patrullas` | `PatrullaController@indexClient` | `client.patrullas.index` | `can:ver.patrullas-cliente` |
| GET | `/client/patrullas/mapa` | `MobileVehicleClientController@locationClient` | `client.patrullas.location` | `can:ver.location-cliente` |
| GET | `/client/recorridos` | `RecorridosController@index` | `client.recorridos.index` | `can:ver.recorridos-cliente` |
| POST | `/client/recorridos` | `RecorridosController@store` | `client.recorridos.store` | `can:crear.recorridos-cliente` |
| GET | `/client/recorridos/{recorrido}` | `RecorridosController@show` | `client.recorridos.show` | `can:ver.recorridos-cliente` |
| PUT | `/client/recorridos/{recorrido}` | `RecorridosController@update` | `client.recorridos.update` | `can:editar.recorridos-cliente` |
| DELETE | `/client/recorridos/{recorrido}` | `RecorridosController@destroy` | `client.recorridos.destroy` | `can:eliminar.recorridos-cliente` |
| POST | `/client/recorridos/import-kml` | `RecorridosController@importKml` | `client.recorridos.import-kml` | `can:crear.recorridos-cliente` |
| GET | `/client/recorridos/historial` | `RecorridosHistorialController@index` | `client.recorridos.historial` | `can:ver.recorridos-cliente` |

#### Supervisores cliente

| Método | URI | Controlador/Acción | Nombre |
|--------|-----|-------------------|--------|
| GET | `/client/supervisores` | `SupervisoresController@index` | `client.supervisores.index` |
| POST | `/client/supervisores/asignar-personal` | `SupervisoresController@asignarPersonal` | `client.supervisores.asignar-personal` |
| POST | `/client/supervisores/asignar-patrulla` | `SupervisoresController@asignarPatrulla` | `client.supervisores.asignar-patrulla` |
| POST | `/client/supervisores/asignar-empresas` | `SupervisoresController@asignarEmpresas` | `client.supervisores.asignar-empresas` |

#### Tickets, Alertas, Drones, Galería cliente

| Método | URI | Controlador/Acción | Nombre | Permiso |
|--------|-----|-------------------|--------|---------|
| GET | `/client/tickets/nuevo` | `TicketController@indexClient` | `client.tickets.nuevo` | `can:ver.tickets-cliente` |
| GET | `/client/misiones` | `AlertasClientController@index` | `client.alertas.index` | `can:ver.alertas-cliente` |
| POST | `/client/misiones/trigger` | `AlertasClientController@triggerAlarm` | `client.alertas.trigger-alarm` | `can:trigger.alertas-cliente` |
| GET | `/client/drones/{droneName}/liveview` | `FlytbaseDroneController@liveviewClient` | `client.streaming.drone.liveview` | `can:ver.liveview-cliente` |
| GET | `/client/flight-logs` | view `flightlogs.client.index` | `client.flight-logs` | `can:ver.flightlogs-cliente` |
| GET | `/client/gallery` | `GalleryClientController@index` | `client.gallery.index` | `can:ver.galeria-cliente` |
| GET | `/client/planificar-misiones` | view `misiones-flytbase.client.index` | `client.misiones` | `can:crear.peticion-misiones` |

#### Livewire directo — Layout cliente

| URI | Componente Livewire | Nombre | Permiso |
|-----|---------------------|--------|---------|
| `/client/settings/user-profile` | `Client\Settings\UserProfile` | `client.settings.user-profile` | auth |
| `/client/settings/system` | `Client\Settings\SystemSettings` | `client.settings.system` | auth |
| `/client/activity-log` | `Client\ActivityLog\Index` | `client.activity-log` | auth |
| `/client/empresas-asociadas` | `Client\EmpresasAsociadas\Index` | `client.empresas-asociadas.index` | auth |
| `/client/usuarios` | `Client\UsuariosCliente\Index` | `client.usuarios.index` | `role:clientadmin` |

#### Checklist y Calendario cliente

| Método | URI | Controlador/Acción | Nombre |
|--------|-----|-------------------|--------|
| GET | `/client/checklist` | `ChecklistPatrullaController@index` | `client.checklist.index` |
| POST | `/client/checklist` | `ChecklistPatrullaController@store` | `client.checklist.store` |
| GET | `/client/calendario` | `CalendarioClienteController@index` | `client.calendario.index` |
| GET | `/client/calendario/eventos` | `CalendarioClienteController@getEventos` | `client.calendario.eventos` |

---

### 🔐 Layout Admin — middleware: auth, verified, Jetstream

#### Clientes y Usuarios admin

| Método | URI | Controlador/Acción | Nombre | Permiso |
|--------|-----|-------------------|--------|---------|
| GET | `/clientes/create` | `ClienteController@create` | `crear.cliente` | `can:crear.cliente` |
| GET | `/clientes/{cliente}/edit` | `ClienteController@edit` | `clientes.edit` | `can:editar.cliente` |
| PUT | `/clientes/{cliente}` | `ClienteController@update` | `clientes.update` | `can:editar.cliente` |
| GET | `/usuarios` | `UserController@index` | `usuarios.index` | `can:administrar.usuarios` |
| POST | `/usuarios` | `UserController@store` | `usuarios.store` | `can:administrar.usuarios` |
| PUT | `/usuarios/{user}` | `UserController@update` | `usuarios.update` | `can:administrar.usuarios` |
| DELETE | `/usuarios/{user}` | `UserController@destroy` | `usuarios.destroy` | `can:administrar.usuarios` |
| POST | `/usuarios/{user}/roles` | `UserController@asignarRol` | `usuarios.roles` | `can:administrar.roles` |
| PUT | `/usuarios/{user}/reset-password` | `UserController@resetPassword` | `usuarios.reset-password` | `can:resetar.contraseña` |
| GET | `/usuarios/asignar-clientes` | `UserClienteController@index` | `user-cliente.index` | `can:asignar.clientes` |
| GET | `/roles` | view `admin.roles` | `crear.roles` | `can:administrar.roles` |

#### Contratos admin

| Método | URI | Controlador/Acción | Nombre | Permiso |
|--------|-----|-------------------|--------|---------|
| GET | `/contratos` | `ContratoController@index` | `contratos.index` | `can:ver.contratos` |
| GET | `/contratos/create` | `ContratoController@create` | `contratos.create` | `can:crear.contratos` |
| POST | `/contratos` | `ContratoController@store` | `contratos.store` | `can:crear.contratos` |
| GET | `/contratos/{contrato}/edit` | `ContratoController@edit` | `contratos.edit` | `can:editar.contratos` |
| GET | `/contratos/{contrato}/edit-livewire` | `Livewire\Contratos\Edit` | `contratos.edit-livewire` | auth |
| PUT | `/contratos/{contrato}` | `ContratoController@update` | `contratos.update` | `can:editar.contratos` |
| DELETE | `/contratos/{contrato}` | `ContratoController@destroy` | `contratos.destroy` | `can:eliminar.contratos` |

#### Eventos admin

| Método | URI | Controlador/Acción | Nombre | Permiso |
|--------|-----|-------------------|--------|---------|
| GET | `/eventos` | `EventoController@index` | `eventos.index` | `can:ver.eventos` |
| GET | `/eventos/nuevo` | `EventoController@create` | `eventos.create` | `can:crear.eventos` |
| POST | `/eventos` | `EventoController@store` | `eventos.store` | `can:crear.eventos` |
| GET | `/eventos/{evento}/edit` | `EventoController@edit` | `eventos.edit` | `can:editar.eventos` |
| PUT | `/eventos/{evento}/update` | `EventoController@update` | `eventos.update` | `can:editar.eventos` |
| DELETE | `/eventos/{evento}/destroy` | `EventoController@destroy` | `eventos.destroy` | `can:eliminar.eventos` |
| GET | `/eventos/{evento}/reporte` | `ReporteController@preview` | `eventos.reporte.preview` | `can:ver.reportes` |
| POST | `/eventos/{evento}/reporte/generar` | `ReporteController@generate` | `eventos.reporte.generate` | `can:generar.reportes` |

#### Personal, Inventario, Objetivos, Seguimientos admin

| Método | URI | Controlador/Acción | Nombre | Permiso |
|--------|-----|-------------------|--------|---------|
| GET | `/personal` | `PersonalController@index` | `personal.index` | `can:ver.personal` |
| GET | `/personal/create` | `PersonalController@create` | `personal.create` | `can:crear.personal` |
| POST | `/personal/store` | `PersonalController@store` | `personal.store` | `can:crear.personal` |
| GET | `/personal/{id}/edit` | `PersonalController@edit` | `personal.edit` | `can:editar.personal` |
| PUT | `/personal/{id}` | `PersonalController@update` | `personal.update` | `can:editar.personal` |
| GET | `/inventario` | `InventarioController@index` | `inventario.index` | `can:ver.inventario` |
| GET | `/objetivos` | `ObjetivoController@index` | `objetivos.index` | `can:ver.objetivos` |
| GET | `/seguimientos` | `SeguimientoController@index` | `seguimientos.index` | `can:ver.seguimientos` |
| GET | `/seguimientos/nuevo` | `SeguimientoController@create` | `seguimientos.create` | `can:crear.seguimientos` |
| POST | `/seguimientos` | `SeguimientoController@store` | `seguimientos.store` | `can:crear.seguimientos` |

#### Patrullas admin

| Método | URI | Controlador/Acción | Nombre | Permiso |
|--------|-----|-------------------|--------|---------|
| GET | `/patrullas` | `PatrullaController@index` | `patrullas.index` | `can:ver.patrullas` |
| GET | `/livewire/patrullas` | `PatrullaController@create` | `patrullas.create` | `can:crear.patrullas` |
| GET | `/patrullas/location` | `MobileVehicleController@location` | `patrullas.location` | `can:ver.location` |
| GET | `/patrullas/{patrulla}/dispositivos` | `DispositivoPatrullaController@index` | `patrullas.dispositivos` | `can:asignar.dispositivos` |

#### Tickets, Notificaciones admin

| Método | URI | Controlador/Acción | Nombre | Permiso |
|--------|-----|-------------------|--------|---------|
| GET | `/tickets/nuevo` | `TicketController@index` | `tickets.nuevo` | `can:ver.tickets` |
| GET | `/admin/notificaciones` | `NotificationController@admin` | `notifications.admin` | `can:administrar.notificaciones` |
| GET | `/admin/notificaciones/crear` | `NotificationController@create` | `admin.nueva-notif` | `can:crear.notificaciones` |
| POST | `/admin/notificaciones` | `NotificationController@store` | `notifications.store` | `can:crear.notificaciones` |

#### Cámaras (HikCentral) admin

| Método | URI | Controlador/Acción | Nombre | Permiso |
|--------|-----|-------------------|--------|---------|
| GET | `/cameras` | `CameraController@index` | `cameras.index` | `can:ver.camaras` |
| GET | `/cameras/stream/{cameraIndexCode}` | `CameraController@showStream` | `cameras.stream` | `can:ver.camaras` |
| POST | `/cameras/{camera}/link-device` | `CameraController@linkDevice` | `cameras.link-device` | `can:ver.camaras` |
| GET | `/anpr/records` | `AnprPassingRecordController@index` | `anpr.index` | `can:ver.registros-anpr` |
| POST | `/anpr/import` | `AnprPassingRecordController@importLast24Hours` | `anpr.import` | `can:importar.registros-anpr` |
| GET | `/anpr/event-image/{recordId}` | `Livewire\HikCentralImages\ViewEventImage` | `anpr.view-image` | `can:ver.registros-anpr` |

#### Drones y Flytbase admin

| Método | URI | Controlador/Acción | Nombre | Permiso |
|--------|-----|-------------------|--------|---------|
| GET | `/drones-flytbase` | `FlytbaseDroneController@index` | `drones-flytbase.index` | `can:ver.drones` |
| POST | `/drones-flytbase` | `FlytbaseDroneController@store` | `drones-flytbase.store` | `can:crear.drones` |
| PUT | `/drones-flytbase/{drone}` | `FlytbaseDroneController@update` | `drones-flytbase.update` | `can:crear.drones` |
| DELETE | `/drones-flytbase/{drone}` | `FlytbaseDroneController@destroy` | `drones-flytbase.destroy` | `can:eliminar.drones` |
| GET | `/docks-flytbase` | `FlytbaseDockController@index` | `docks-flytbase.index` | `can:ver.docks` |
| GET | `/misiones-flytbase` | `MisionFlytbaseController@index` | `misiones-flytbase.index` | `can:ver.misiones` |
| POST | `/misiones-flytbase` | `MisionFlytbaseController@store` | `misiones-flytbase.store` | `can:crear.misiones` |
| PUT | `/misiones-flytbase/{mision}` | `MisionFlytbaseController@update` | `misiones-flytbase.update` | `can:crear.misiones` |
| DELETE | `/misiones-flytbase/{mision}` | `MisionFlytbaseController@destroy` | `misiones-flytbase.destroy` | `can:crear.misiones` |
| GET | `/drones/{droneName}/liveview` | `FlytbaseDroneController@liveview` | `streaming.drone.liveview` | `can:ver.liveview` |
| GET | `/flight-logs` | view `flightlogs.admin.index` | `flight-logs.index` | `can:ver.flightlogs` |
| GET | `/misiones/peticiones-clientes` | `PeticionesMisionesClient@index` | `peticiones.index` | `can:ver.peticiones` |
| GET | `/alertas` | `AlertasController@index` | `alertas.index` | `can:ver.alertas` |
| POST | `/alertas/trigger-alarm` | `AlertasController@triggerAlarm` | `alertas.trigger-alarm` | `can:trigger.alertas` |
| GET | `/gallery` | `GalleryController@index` | `gallery.index` | `can:ver.galeria` |
| GET | `/pilotos/asignar-clientes` | view `pilotos.index` | `pilotos.index` | `can:ver.pilotos` |
| GET | `/sites` | view `sites-flytbase.index` | `sites.index` | `can:ver.sites` |
| GET | `/objetivos-a` | view `objetivos.aipem.index` | `objetivos-aipem.index` | `can:ver.objetivos-aipem` |

#### Rodados admin (`/rodados/*`)

| Método | URI | Controlador/Acción | Nombre | Permiso |
|--------|-----|-------------------|--------|---------|
| GET | `/rodados` | `RodadoController@index` | `rodados.index` | auth |
| POST | `/rodados` | `RodadoController@store` | `rodados.store` | auth |
| PUT | `/rodados/{rodado}` | `RodadoController@update` | `rodados.update` | auth |
| DELETE | `/rodados/{rodado}` | `RodadoController@destroy` | `rodados.destroy` | auth |
| GET | `/rodados/calendario` | `CalendarioRodadosController@index` | `rodados.calendario.index` | auth |
| GET | `/rodados/admin-dashboard` | `AdminRodadosDashboardController@index` | `rodados.admin-dashboard` | auth |
| GET | `/rodados/proveedores-talleres` | closure, view `rodados.proveedores-talleres` | `rodados.proveedores-talleres.index` | auth |
| GET | `/rodados/pagos-servicios` | closure, view `rodados.pagos` | `rodados.pagos-servicios.index` | auth |
| GET | `/rodados/cobranzas` | `CobranzaController@index` | `rodados.cobranzas.index` | auth |
| GET | `/rodados/alertas-admin` | `AlertaAdminController@index` | `rodados.alertas-admin.index` | auth |

#### Operaciones Dashboard admin

| Método | URI | Controlador/Acción | Nombre | Permiso |
|--------|-----|-------------------|--------|---------|
| GET | `/operaciones/dashboard` | `OperacionesDashboardController@index` | `operaciones.dashboard` | `can:ver.operaciones` |
| GET | `/admin/sistema` | `SistemaController@index` | `sistema.permisos` | `role:admin` |
| GET | `/admin/permisos` | `SistemaController@asignar_permisos` | `asignar.permisos` | `role:admin` |

#### Settings (layout admin)

| URI | Componente Livewire | Nombre |
|-----|---------------------|--------|
| `/settings/user-profile` | `Settings\UserProfile` | `settings.user-profile` |
| `/settings/profile` | `Settings\Profile` | `settings.profile` |
| `/settings/password` | `Settings\Password` | `settings.password` |
| `/settings/appearance` | `Settings\Appearance` | `settings.appearance` |
| `/settings/system` | `Settings\SystemSettings` | `settings.system` |
| `/activity-log` | `ActivityLog\Index` | `activity-log.index` |

---

## 🔑 Catálogo de permisos Spatie

> Convención de nombres: `accion.recurso` (ej: `ver.eventos`, `crear.eventos-cliente`).
> Los permisos de cliente llevan el sufijo `-cliente`.

### Permisos Layout Admin

| Permiso | Qué habilita |
|---------|-------------|
| `crear.cliente` | Crear cliente nuevo |
| `editar.cliente` | Editar datos de cliente |
| `administrar.usuarios` | CRUD usuarios admin |
| `administrar.roles` | Asignar/gestionar roles |
| `resetar.contraseña` | Resetear contraseña de usuario |
| `asignar.clientes` | Vincular usuarios a clientes |
| `ver.contratos` | Ver lista de contratos |
| `crear.contratos` | Crear contrato nuevo |
| `editar.contratos` | Editar contrato existente |
| `eliminar.contratos` | Eliminar contrato |
| `ver.eventos` | Ver eventos (admin) |
| `crear.eventos` | Crear evento |
| `editar.eventos` | Editar evento |
| `eliminar.eventos` | Eliminar evento |
| `ver.reportes` | Ver reportes de eventos |
| `generar.reportes` | Generar/descargar PDF de reportes |
| `ver.personal` | Ver personal |
| `crear.personal` | Crear personal |
| `editar.personal` | Editar personal |
| `ver.inventario` | Ver inventario |
| `crear.inventario` | Crear dispositivo |
| `editar.inventario` | Editar dispositivo |
| `ver.objetivos` | Ver objetivos |
| `ver.seguimientos` | Ver seguimientos |
| `crear.seguimientos` | Crear seguimiento |
| `ver.patrullas` | Ver patrullas (admin) |
| `crear.patrullas` | Crear patrulla |
| `ver.location` | Ver mapa ubicación (admin) |
| `asignar.dispositivos` | Vincular dispositivos a patrulla |
| `ver.tickets` | Ver tickets (admin) |
| `administrar.notificaciones` | Gestionar notificaciones |
| `crear.notificaciones` | Crear notificación |
| `crear.notif` | Leer/descartar notificaciones propias |
| `ver.camaras` | Ver cámaras HikCentral |
| `ver.registros-anpr` | Ver registros ANPR |
| `importar.registros-anpr` | Importar registros ANPR desde HikCentral |
| `ver.drones` | Ver drones Flytbase |
| `crear.drones` | Crear/editar drone |
| `eliminar.drones` | Eliminar drone |
| `ver.docks` | Ver docks |
| `crear.docks` | Crear dock |
| `eliminar.docks` | Eliminar dock |
| `ver.misiones` | Ver misiones Flytbase |
| `crear.misiones` | Crear/editar misión |
| `ver.liveview` | Ver liveview drone (admin) |
| `ver.flightlogs` | Ver flight logs (admin) |
| `ver.peticiones` | Ver peticiones de misiones (admin) |
| `ver.alertas` | Ver alertas (admin) |
| `trigger.alertas` | Disparar alarma (admin) |
| `ver.galeria` | Ver galería S3 (admin) |
| `importar.galeria` | Importar/sincronizar galería |
| `ver.pilotos` | Ver pilotos |
| `ver.sites` | Ver sites Flytbase |
| `ver.objetivos-aipem` | Ver objetivos AIPEM |
| `ver.operaciones` | Ver dashboard operaciones (admin) |
| `crear.permiso` | Crear permiso nuevo en sistema |

### Permisos Layout Cliente

| Permiso | Qué habilita |
|---------|-------------|
| `ver.eventos-cliente` | Ver eventos propios |
| `crear.eventos-cliente` | Crear evento (cliente) |
| `editar.eventos-cliente` | Editar evento (cliente) |
| `eliminar.eventos-cliente` | Eliminar evento (cliente) |
| `anular.eventos-cliente` | Anular evento |
| `agregar-notas.eventos-cliente` | Agregar notas adicionales a evento |
| `ver.reportes-cliente` | Ver reportes propios |
| `generar.reportes-cliente` | Generar/descargar PDF (cliente) |
| `ver.seguimientos-cliente` | Ver seguimientos (cliente) |
| `ver.patrullas-cliente` | Ver patrullas (cliente) |
| `ver.location-cliente` | Ver mapa ubicación (cliente) |
| `ver.recorridos-cliente` | Ver recorridos |
| `crear.recorridos-cliente` | Crear recorrido |
| `editar.recorridos-cliente` | Editar recorrido |
| `eliminar.recorridos-cliente` | Eliminar recorrido |
| `eliminar.historial-recorridos-cliente` | Eliminar registro historial |
| `ver.tickets-cliente` | Ver formulario de tickets (cliente) |
| `ver.alertas-cliente` | Ver alertas/misiones (cliente) |
| `trigger.alertas-cliente` | Disparar alarma (cliente) |
| `ver.liveview-cliente` | Ver liveview drone (cliente) |
| `ver.flightlogs-cliente` | Ver flight logs (cliente) |
| `ver.galeria-cliente` | Ver galería S3 (cliente) |
| `crear.peticion-misiones` | Crear petición de misión |
| `ver.operaciones-cliente` | Ver dashboard operaciones (cliente) |

### Roles del sistema

| Rol | Descripción |
|-----|-------------|
| `admin` | Acceso total (sistema, permisos, rutas protegidas con `role:admin`) |
| `clientadmin` | Administra usuarios cliente (`/client/usuarios`) |
| `clientsupervisor` | Supervisor de patrullas, checklist y calendario (cliente) |

---

## 🧩 Módulos principales

### Administración

| Módulo | Controlador principal | Vista principal | Livewire | Dependencias |
|--------|-----------------------|----------------|----------|--------------|
| Clientes | `ClienteController` | `clientes.*` | `Clientes.php` | MySQL, permisos |
| Rodados | `RodadoController` | `rodados.*` | `Rodados\CalendarioRodados` | MySQL, DomPDF, archivos |
| Filmación | `CameraController` | `cameras.*` | `HikCentralImages\ViewEventImage` | HikCentral API |
| Tickets | `TicketController` | livewire | `Tickets\ManageTickets`, `ManageTicketsClient` | MySQL, Mail |
| Usuarios y roles | `UserController` | `admin.roles` | `Admin\*` | Spatie Permission |
| Notificaciones | `NotificationController` | `notificaciones.*` | — | MySQL |

### Operaciones

| Módulo | Controlador principal | Vista principal | Livewire | Dependencias |
|--------|-----------------------|----------------|----------|--------------|
| Eventos | `EventoController` | `eventos.*` | — | MySQL, DomPDF |
| Personal | `PersonalController` | `personal.*` | `Personal\*` | MySQL |
| Contratos | `ContratoController` | `contratos.*` | `Contratos\Edit` | MySQL |
| Seguimientos | `SeguimientoController` | `seguimientos.*` | `Seguimientos\*` | MySQL |
| Patrullas | `PatrullaController` | `patrullas.*` | `Patrullas\Listado`, `ListadoCliente` | MySQL, mapas |
| Drones / Flytbase | `FlytbaseDroneController` | `drones-flytbase.*` | `PeticionesMisionesClient` | Flytbase API, S3 |
| HikCentral | `CameraController`, `AnprPassingRecordController` | `cameras.*`, `anpr.*` | `HikCentralImages\ViewEventImage` | HikCentral API |

### Transversal

| Módulo | Controlador / Ruta | Dependencias |
|--------|-------------------|--------------|
| Dashboards | `AdminDashboardController`, `ClientDashboardController`, `OperacionesDashboardController` | Chart.js, APIs internas, Leaflet |
| Landing | `LandingController` | MySQL (ContactLead) |
| Inventario | `InventarioController` | MySQL |
| Objetivos / AIPEM | `ObjetivoController` | MySQL |
| Galería S3 | `GalleryController` / `GalleryClientController` | AWS S3 |
| Pilotos | `ManagePilotosFlytbase.php` (Livewire) | MySQL |
| Sites | `Sites\*` (Livewire) | MySQL |

---

## 🔄 Flujo de datos principal

```
Usuario → Navegador → Laravel (routes → middleware → Controller/Livewire)
                            → Models (MySQL)
                            → Services (HikCentral, Flytbase, S3)
                            → Vista Blade / respuesta Livewire → HTML/JS
```

---

## ⚙️ Convenciones de código

### Naming

| Elemento | Convención | Ejemplo |
|----------|-----------|---------|
| Controladores | PascalCase + `Controller` | `EventoController`, `EventoClientController` |
| Livewire (componentes) | PascalCase, directorio por módulo | `Tickets\ManageTickets` |
| Modelos | Singular PascalCase | `Evento`, `Rodado`, `Patrulla` |
| Vistas Blade | snake_case, directorio por módulo | `eventos.index`, `rodados.calendario` |
| Nombres de rutas | `modulo.accion` | `eventos.index`, `rodados.turnos.store` |
| Permisos Spatie | `accion.recurso` (kebab-case) | `ver.eventos`, `crear.eventos-cliente` |
| Branches feature | `feature/vX.Y.Z-slug` | `feature/v0.5.0-tickets-mejoras` |
| Branches fix | `fix/vX.Y.Z-slug` | `fix/v0.5.1-login-redirect` |
| Branches hotfix | `hotfix/vX.Y.Z-slug` | `hotfix/v0.5.2-crash-eventos` |
| Commits | Conventional Commits | `feat(tickets): agregar panel inline` |

### Patrón de controladores cliente vs admin

- Módulos con doble perfil tienen **dos controladores separados**:
  - `EventoController` → admin
  - `EventoClientController` → cliente
  - Mismo patrón: `ReporteController` / `ReporteClientController`
- Las rutas cliente están bajo el grupo `/client/*` con sus propios permisos `*-cliente`.

### Livewire

- Los componentes Livewire usan `wire:model`, `$rules` con `validate()` para formularios.
- El componente devuelve su propia vista desde `resources/views/livewire/`.
- Los tickets usan **solo Livewire** (sin controlador tradicional): `ManageTickets` y `ManageTicketsClient`.
- Los componentes Volt usan sintaxis de funciones (no clases).

### Middlewares relevantes

| Middleware | Uso |
|-----------|-----|
| `auth` | Usuario autenticado |
| `verified` | Email verificado |
| `can:PERMISO` | Spatie — verifica permiso específico |
| `role:ROLE` | Spatie — verifica rol exacto |
| `config('jetstream.auth_session')` | Sesión Jetstream (grupo admin principal) |

---

## 🧪 Convenciones de testing (Pest)

- Archivo de configuración: `phpunit.xml` + `tests/Pest.php`
- Entorno de tests: `.env.testing` (base `cos_test` en MySQL)
- Factories: en `database/factories/`, una por modelo principal.
- Tests de Feature: en `tests/Feature/`, organizados por módulo.
- Tests de Unit: en `tests/Unit/`.
- Ejecutar todos: `php artisan test` o `./vendor/bin/pest`
- Patrón básico de un test de Feature:

```php
it('redirige si no está autenticado', function () {
    $response = $this->get(route('eventos.index'));
    $response->assertRedirect('/login');
});

it('puede ver eventos con permiso', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('ver.eventos');

    $this->actingAs($user)
        ->get(route('eventos.index'))
        ->assertOk();
});
```

---

## 🔑 Decisiones de arquitectura (ADRs)

### ADR-001: Laravel + Livewire como stack principal

**Estado:** Aceptado

**Contexto:** Necesidad de una aplicación web con alta interactividad (listados, filtros, formularios, dashboards) mantenida por un equipo pequeño.

**Decisión:** Laravel 12 con Livewire 3 (y Volt/Flux) para evitar un frontend SPA separado y mantener lógica y permisos en el servidor.

**Consecuencias:** Desarrollo ágil, menos contexto front/back; posible límite en UX muy dinámica sin tocar Alpine/JS.

### ADR-002: Dos layouts (admin vs cliente) por rutas

**Estado:** Aceptado

**Contexto:** Dos perfiles de usuario (interno CyH Sur vs clientes que consumen servicios) con permisos y menús distintos.

**Decisión:** Prefijo de rutas `client/` para el layout cliente; resto bajo layout admin. Mismo código base, permisos con Spatie.

**Consecuencias:** Un solo deploy; permisos y middleware definen el acceso por ruta.

### ADR-003: Integraciones externas (HikCentral, Flytbase, AWS)

**Estado:** Aceptado

**Contexto:** Requerimientos de video vigilancia (HikCentral), operación de drones (Flytbase) y almacenamiento de medios (S3).

**Decisión:** Servicios dedicados en `app/Services`, controladores que los consumen; configuración vía `.env`.

**Consecuencias:** Funcionalidad operativa; dependencia de disponibilidad y APIs de terceros.

### ADR-004: Tickets solo con Livewire (sin controlador REST)

**Estado:** Aceptado

**Contexto:** Los tickets requieren lógica interactiva compleja (búsqueda en tiempo real, filtros, estados).

**Decisión:** `ManageTickets` y `ManageTicketsClient` son componentes Livewire completos, sin controlador tradicional.

**Consecuencias:** Toda la lógica de tickets vive en el componente; no hay rutas REST para tickets.

---

## ⚡ Principios de diseño

1. **Una app, dos perfiles:** Misma aplicación para admin y cliente; la seguridad se basa en autenticación y permisos por ruta.
2. **Livewire para interactividad:** Formularios, listados y acciones sin construir una API REST separada para el front.
3. **MySQL como única fuente de verdad:** Datos relacionales en MySQL; medios en filesystem o S3.
4. **Servicios para terceros:** Lógica de HikCentral, Flytbase y S3 encapsulada en clases de servicio.
5. **Permiso antes de ruta nueva:** Toda ruta nueva debe tener su permiso Spatie definido; nunca dejar rutas solo con `auth`.

---

## 🚫 Limitaciones conocidas

- **Sin licencia explícita:** El proyecto no declara licencia de uso o distribución.
- **Dependencia de integraciones:** HikCentral, Flytbase y AWS son críticos para varias funcionalidades; caídas o cambios de API impactan el sistema.
- **Un solo servidor web:** La aplicación está pensada para correr en un servidor; colas (queue) deben estar activas para jobs asíncronos si se usan.

---

## 📚 Referencias

- [Laravel](https://laravel.com/docs)
- [Livewire](https://livewire.laravel.com/)
- [Laravel Jetstream](https://jetstream.laravel.com/)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
- [HikCentral](https://www.hikvision.com/)
- [Flytbase](https://flytbase.com/)
- [Tailwind CSS](https://tailwindcss.com/docs)
