# Dashboard Operacional - Lista de Tareas Detallada

Este documento complementa el plan principal con una lista exhaustiva de tareas organizadas por fases.

## Fase 1: Base de Datos

### Migración
- [ ] Crear archivo de migración `YYYY_MM_DD_HHMMSS_add_dispositivo_id_to_cameras_table.php`
- [ ] Agregar columna `dispositivo_id` nullable después de `encode_dev_index_code`
- [ ] Agregar foreign key constraint a tabla `dispositivos` con `onDelete('set null')`
- [ ] Agregar índice en columna `dispositivo_id` para optimizar consultas
- [ ] Ejecutar migración en ambiente de desarrollo
- [ ] Verificar que la migración se ejecutó correctamente en base de datos
- [ ] Probar relación cámara-dispositivo con datos de prueba
- [ ] Verificar que las cámaras existentes no se afectan (campo nullable)

## Fase 2: Backend - Modelos y Relaciones

### Modelo Camera
- [ ] Agregar relación `dispositivo()` usando `belongsTo(Dispositivo::class)`
- [ ] Verificar que la relación funciona correctamente
- [ ] Probar eager loading con `Camera::with('dispositivo')->get()`

### Modelo Dispositivo
- [ ] Agregar relación `camera()` usando `hasOne(Camera::class)`
- [ ] Implementar accessor `getCoordenadasAttribute()` con parsing robusto
- [ ] Implementar método `tieneCoordenadas()` que retorna boolean
- [ ] Probar parsing con formato: `-34.6037, -58.3816`
- [ ] Probar parsing con formato: `-34.6037,-58.3816`
- [ ] Probar parsing con formato: `Oficina: -34.6037, -58.3816`
- [ ] Probar parsing con formato JSON: `{"lat": -34.6037, "lng": -58.3816}`
- [ ] Manejar caso de solo texto descriptivo (retornar null)
- [ ] Validar rangos de coordenadas (lat: -90 a 90, lng: -180 a 180)

### Modelo Evento
- [ ] Agregar relación `ultimoSeguimiento()` usando `hasOne(Seguimiento::class)->latestOfMany('fecha')`
- [ ] Implementar accessor `getEstadoActualAttribute()` que retorna estado del último seguimiento o 'ABIERTO' por defecto
- [ ] Probar relación con eventos que tienen seguimientos
- [ ] Probar relación con eventos sin seguimientos (debe retornar null)

### Modelo Rodado
- [ ] Verificar si existe relación `dispositivos()` o `dispositivo()`
- [ ] Si no existe, crear relación según estructura de base de datos
- [ ] Verificar si existe columna `rodado_id` en tabla `dispositivos`
- [ ] O verificar si existe tabla pivot `dispositivo_rodado`
- [ ] Implementar scope para vehículos equipados si es necesario

## Fase 2: Backend - Controlador Principal

### Creación del Controlador
- [ ] Crear archivo `app/Http/Controllers/OperacionesDashboardController.php`
- [ ] Agregar namespace y use statements necesarios
- [ ] Crear clase que extienda `Controller`

### Método index()
- [ ] Implementar detección de layout (cliente vs principal)
- [ ] Verificar permisos con `authorize('ver.operaciones')` o similar
- [ ] Obtener `isClient` del usuario autenticado
- [ ] Pasar datos iniciales a la vista
- [ ] Retornar vista `operaciones.dashboard` con datos

### Método getKPIs()
- [ ] Implementar cálculo de cantidad de cámaras (EncodingDevice activos)
- [ ] Implementar cálculo de vehículos total (Rodado::count())
- [ ] Implementar cálculo de vehículos equipados (con dispositivos instalados)
- [ ] Implementar cálculo de trending de eventos (últimos 30 días vs anteriores 30 días)
- [ ] Calcular porcentaje de cambio y determinar tendencia (aumento/disminución)
- [ ] Implementar cálculo de eventos abiertos (con último seguimiento ABIERTO o sin seguimientos)
- [ ] Implementar cálculo de tiempo promedio de cierre (días y horas)
- [ ] Implementar cálculo de vuelos total (FlightLog::count())
- [ ] Implementar cálculo de vuelos incompletos (estado en_proceso o interrumpido)
- [ ] Implementar cálculo de triggers de misiones agrupados por dron y cliente
- [ ] Agregar caché de 60 segundos para KPIs
- [ ] Aceptar parámetro `cliente_id` opcional para filtrado
- [ ] Retornar JSON con estructura definida en el plan

### Método getMapData()
- [ ] Implementar obtención de eventos con coordenadas
- [ ] Aplicar filtro por cliente si existe
- [ ] Aplicar filtro por estado de evento si existe
- [ ] Incluir último seguimiento para obtener estado actual
- [ ] Implementar obtención de vehículos desde HikCentral (MobileVehicleController)
- [ ] Obtener ubicaciones actuales de vehículos
- [ ] Implementar obtención de docks activos con coordenadas
- [ ] Implementar obtención de cámaras con coordenadas desde dispositivos
- [ ] Filtrar cámaras que tienen dispositivo con coordenadas válidas
- [ ] Aplicar filtros de cliente y estado a todos los datos
- [ ] Retornar JSON con estructura definida en el plan
- [ ] Agregar manejo de errores con try-catch

### Método getEventos()
- [ ] Implementar query base con paginación
- [ ] Aplicar filtro por cliente si existe
- [ ] Aplicar filtro por estado de evento si existe
- [ ] Ordenar por fecha_hora DESC (más recientes primero)
- [ ] Incluir relaciones: cliente, categoria, ultimoSeguimiento
- [ ] Retornar JSON con eventos paginados
- [ ] Incluir metadata de paginación

### Métodos Privados Helper
- [ ] Implementar `getClienteFilter()` que detecta si es cliente y retorna su cliente_id
- [ ] Implementar `getEventosParaMapa(array $filters)`
- [ ] Implementar `getVehiculosParaMapa(array $filters)`
- [ ] Implementar `getDocksParaMapa()`
- [ ] Implementar `getCamarasParaMapa(array $filters)`
- [ ] Agregar logging de errores en cada método helper

## Fase 2: Backend - Helpers

### CoordinatesHelper
- [ ] Crear archivo `app/Helpers/CoordinatesHelper.php` si no existe
- [ ] Implementar método estático `isValidLatitude($lat)`
- [ ] Implementar método estático `isValidLongitude($lng)`
- [ ] Implementar método estático `parseFromString($string)` con múltiples formatos
- [ ] Agregar helper a autoload en `composer.json` si es necesario

## Fase 2: Backend - Rutas

### Rutas Web
- [ ] Agregar ruta GET `/operaciones/dashboard` apuntando a `OperacionesDashboardController@index`
- [ ] Agregar middleware `auth` a la ruta
- [ ] Agregar middleware de permisos `can:ver.operaciones` si existe
- [ ] Nombrar ruta como `operaciones.dashboard`
- [ ] Agregar ruta GET `/client/operaciones/dashboard` para layout cliente
- [ ] Agregar middleware `auth` a ruta cliente
- [ ] Nombrar ruta como `client.operaciones.dashboard`

### Rutas API
- [ ] Crear grupo de rutas `Route::prefix('api/operaciones')`
- [ ] Agregar ruta GET `/api/operaciones/kpis` apuntando a `getKPIs`
- [ ] Agregar ruta GET `/api/operaciones/map-data` apuntando a `getMapData`
- [ ] Agregar ruta GET `/api/operaciones/eventos` apuntando a `getEventos`
- [ ] Agregar middleware `auth` a todas las rutas API

### Actualización de Navegación
- [ ] Abrir archivo `resources/views/components/modern/top-nav.blade.php`
- [ ] Localizar botón con `data-dashboard="operaciones"`
- [ ] Cambiar `data-route="#"` a `data-route="{{ route('operaciones.dashboard') }}"`

## Fase 3: Frontend - Vistas Principales

### Estructura de Directorios
- [ ] Crear directorio `resources/views/operaciones/` si no existe
- [ ] Crear subdirectorio `resources/views/operaciones/partials/` si no existe

### Vista Principal
- [ ] Crear archivo `resources/views/operaciones/dashboard.blade.php`
- [ ] Extender layout correcto (`@extends('layouts.app')` o `layouts.cliente`)
- [ ] Agregar sección de título
- [ ] Incluir partial de KPIs: `@include('operaciones.partials.kpis')`
- [ ] Crear grid principal con mapa y eventos
- [ ] Incluir partial de mapa: `@include('operaciones.partials.map-container')`
- [ ] Incluir partial de eventos: `@include('operaciones.partials.eventos-list')`
- [ ] Incluir partial de controles: `@include('operaciones.partials.controls')`
- [ ] Incluir partial de acceso rápido: `@include('operaciones.partials.quick-access')`
- [ ] Agregar scripts necesarios (Vite, Google Maps, operaciones-dashboard.js)
- [ ] Agregar meta tags y título de página

### Partial de KPIs
- [ ] Crear archivo `resources/views/operaciones/partials/kpis.blade.php`
- [ ] Crear grid responsive con clase `operaciones-kpis`
- [ ] Crear card para KPI de cámaras con icono, valor y label
- [ ] Crear card para KPI de vehículos (total/equipados) con tooltip
- [ ] Crear card para KPI de trending de eventos con indicador de tendencia
- [ ] Crear card para KPI de eventos abiertos
- [ ] Crear card para KPI de tiempo promedio de cierre
- [ ] Crear card para KPI de vuelos (total/incompletos)
- [ ] Crear card para KPI de triggers con desglose expandible
- [ ] Agregar estados de carga (skeleton loaders)
- [ ] Agregar manejo de errores en visualización

### Partial de Mapa
- [ ] Crear archivo `resources/views/operaciones/partials/map-container.blade.php`
- [ ] Crear contenedor con ID `operaciones-map-container`
- [ ] Crear div con ID `operaciones-map` para Google Maps
- [ ] Agregar controles de tipo de mapa (satelital/detallado) en esquina superior izquierda
- [ ] Agregar filtro de estado de evento (tabs múltiples) en esquina superior derecha
- [ ] Agregar filtro de cliente (dropdown) solo si no es layout cliente
- [ ] Agregar botón expandir mapa en esquina superior derecha
- [ ] Agregar estados de carga del mapa
- [ ] Agregar mensaje cuando no hay datos para mostrar

### Partial de Eventos
- [ ] Crear archivo `resources/views/operaciones/partials/eventos-list.blade.php`
- [ ] Crear contenedor scrollable con clase `operaciones-eventos-list`
- [ ] Crear estructura de card de evento con header, body y footer
- [ ] Agregar tag de cliente con color
- [ ] Agregar badge de estado con colores (abierto, en-revision, cerrado)
- [ ] Agregar información de fecha/hora
- [ ] Agregar categoría y descripción truncada
- [ ] Agregar botón "Ver Detalles"
- [ ] Agregar estado vacío cuando no hay eventos
- [ ] Agregar indicador de carga

### Partial de Controles
- [ ] Crear archivo `resources/views/operaciones/partials/controls.blade.php`
- [ ] Crear botón nueva pestaña con icono `bi-box-arrow-up-right`
- [ ] Posicionar en esquina superior derecha
- [ ] Agregar tooltip "Abrir en nueva pestaña"
- [ ] Crear botón pantalla completa con icono `bi-arrows-fullscreen`
- [ ] Agregar tooltip "Pantalla completa"
- [ ] Agregar IDs a los botones para JavaScript

### Partial de Acceso Rápido
- [ ] Crear archivo `resources/views/operaciones/partials/quick-access.blade.php`
- [ ] Crear botón HikCentral con link a `http://central.cyhsur.com`
- [ ] Agregar icono de cámara o monitor
- [ ] Configurar para abrir en nueva pestaña
- [ ] Crear botón Flytbase con link a `https://console.flytbase.com`
- [ ] Agregar icono de dron
- [ ] Configurar para abrir en nueva pestaña
- [ ] Posicionar botones de forma accesible

## Fase 4: Frontend - Estilos CSS

### Estilos del Dashboard
- [ ] Abrir archivo `resources/css/modern-ui.css`
- [ ] Agregar estilos para `.operaciones-dashboard`
- [ ] Agregar estilos para `.operaciones-dashboard.fullscreen-mode`
- [ ] Agregar padding y min-height apropiados

### Estilos de KPIs
- [ ] Agregar estilos para `.operaciones-kpis` (grid)
- [ ] Agregar estilos para `.operaciones-kpi-card`
- [ ] Agregar hover effects en cards
- [ ] Agregar estilos para `.operaciones-kpi-value`
- [ ] Agregar estilos para `.operaciones-kpi-label`
- [ ] Agregar estilos para `.operaciones-kpi-icon`
- [ ] Agregar transiciones suaves

### Estilos del Mapa
- [ ] Agregar estilos para `.operaciones-map-container`
- [ ] Agregar altura fija de 600px
- [ ] Agregar border-radius y border
- [ ] Agregar estilos para `.map-controls`
- [ ] Agregar estilos para `.map-filter-controls`
- [ ] Agregar estilos para `.map-filter-tab`
- [ ] Agregar estilos para `.map-filter-tab.active`
- [ ] Agregar hover effects en tabs

### Estilos de Eventos
- [ ] Agregar estilos para `.operaciones-eventos-list`
- [ ] Agregar altura fija y overflow-y auto
- [ ] Agregar estilos para `.evento-card`
- [ ] Agregar hover effects en cards
- [ ] Agregar estilos para `.evento-header`
- [ ] Agregar estilos para `.evento-cliente-tag`
- [ ] Agregar estilos para `.evento-estado-badge` y variantes (abierto, en-revision, cerrado)

### Estilos de Tooltips
- [ ] Agregar estilos para `.map-tooltip`
- [ ] Agregar estilos para `.tooltip-header`
- [ ] Agregar estilos para `.tooltip-body`
- [ ] Agregar estilos para `.tooltip-row`
- [ ] Agregar estilos para `.tooltip-label` y `.tooltip-value`
- [ ] Agregar estilos para `.tooltip-footer`
- [ ] Agregar estilos para `.tooltip-btn`
- [ ] Agregar variantes de color por tipo de tooltip

### Estilos de Controles
- [ ] Agregar estilos para `.dashboard-control-btn`
- [ ] Agregar tamaño circular (40px x 40px)
- [ ] Agregar hover effects
- [ ] Agregar estilos para iconos dentro de botones

### Estilos Responsive
- [ ] Agregar media query para `.operaciones-main-grid` en pantallas < 1200px
- [ ] Cambiar grid a una columna en móviles
- [ ] Ajustar altura de lista de eventos en móviles
- [ ] Ajustar tamaño de KPIs en móviles
- [ ] Probar en diferentes tamaños de pantalla

## Fase 5: Frontend - JavaScript

### Archivo Principal
- [ ] Crear archivo `resources/js/operaciones-dashboard.js`
- [ ] Crear clase `OperacionesDashboard`
- [ ] Agregar constructor con inicialización de propiedades
- [ ] Agregar propiedades: map, markers, filters, isClient, refreshInterval

### Inicialización
- [ ] Implementar método `init()` que llama a todos los métodos de inicialización
- [ ] Implementar método `initMap()` para inicializar Google Maps
- [ ] Configurar centro del mapa (Buenos Aires por defecto)
- [ ] Configurar zoom inicial
- [ ] Configurar mapTypeId como 'satellite' por defecto
- [ ] Agregar listener para resize del mapa

### Carga de KPIs
- [ ] Implementar método `loadKPIs()`
- [ ] Hacer fetch a `/api/operaciones/kpis`
- [ ] Manejar respuesta JSON
- [ ] Implementar método `renderKPIs(data)`
- [ ] Actualizar DOM con valores de KPIs
- [ ] Agregar indicadores de tendencia (flechas, colores)
- [ ] Agregar manejo de errores

### Carga de Datos del Mapa
- [ ] Implementar método `loadMapData(filters)`
- [ ] Construir query params desde filtros
- [ ] Hacer fetch a `/api/operaciones/map-data`
- [ ] Manejar respuesta JSON
- [ ] Implementar método `renderMapData(data)`
- [ ] Llamar a `clearMarkers()` antes de renderizar
- [ ] Crear markers para cada tipo de dato
- [ ] Llamar a `fitMapToMarkers()` al finalizar

### Gestión de Markers
- [ ] Implementar método `clearMarkers()` que elimina todos los markers del mapa
- [ ] Implementar método `createEventoMarker(evento)` con icono rojo y tooltip
- [ ] Implementar método `createVehiculoMarker(vehiculo)` con icono azul y tooltip
- [ ] Implementar método `createDockMarker(dock)` con icono verde y tooltip
- [ ] Implementar método `createCamaraMarker(camara)` con icono púrpura y tooltip
- [ ] Agregar listeners de click a cada marker para mostrar tooltip
- [ ] Implementar método `fitMapToMarkers()` para ajustar zoom

### Generación de Tooltips
- [ ] Implementar método `generateEventoTooltip(evento)` que retorna HTML
- [ ] Implementar método `generateVehiculoTooltip(vehiculo)` que retorna HTML
- [ ] Implementar método `generateDockTooltip(dock)` que retorna HTML
- [ ] Implementar método `generateCamaraTooltip(camara)` que retorna HTML
- [ ] Agregar botones de acción en cada tooltip
- [ ] Sanitizar datos antes de insertar en HTML

### Filtros
- [ ] Implementar método `setupFilters()` que agrega event listeners
- [ ] Agregar listener para cambio de tipo de mapa
- [ ] Agregar listeners para tabs de estado de evento (toggle múltiple)
- [ ] Agregar listener para dropdown de cliente
- [ ] Implementar método `applyFilters(filters)` con debounce de 300ms
- [ ] Actualizar propiedad `this.filters`
- [ ] Recargar datos del mapa y eventos al cambiar filtros

### Pantalla Completa
- [ ] Implementar método `toggleFullscreen()` para dashboard completo
- [ ] Ocultar top-nav y sidebar al entrar en fullscreen
- [ ] Agregar clase `fullscreen-mode` al contenedor
- [ ] Restaurar elementos al salir de fullscreen
- [ ] Implementar método `toggleMapFullscreen()` usando Fullscreen API
- [ ] Ajustar tamaño del mapa al entrar/salir de fullscreen
- [ ] Agregar listener para evento `fullscreenchange`
- [ ] Actualizar icono del botón según estado

### Listado de Eventos
- [ ] Implementar método `loadEventos(filters)`
- [ ] Construir query params desde filtros
- [ ] Hacer fetch a `/api/operaciones/eventos`
- [ ] Implementar método `renderEventos(eventos)`
- [ ] Crear cards de evento en el DOM
- [ ] Agregar listener de click en eventos para centrar mapa
- [ ] Agregar paginación o scroll infinito si es necesario

### Auto-refresh
- [ ] Implementar método `startAutoRefresh()`
- [ ] Configurar intervalo de 30 segundos
- [ ] Recargar KPIs, datos del mapa y eventos
- [ ] Implementar método `stopAutoRefresh()`
- [ ] Limpiar intervalo al salir de la página

### Utilidades
- [ ] Implementar método `showLoadingState()`
- [ ] Implementar método `hideLoadingState()`
- [ ] Implementar método `showErrorMessage(message)`
- [ ] Implementar método `parseCoordenadasFromUbicacion(ubicacion)`
- [ ] Agregar inicialización en `DOMContentLoaded`

### Integración con Navegación
- [ ] Abrir archivo `resources/js/modern-navigation.js`
- [ ] Localizar método `setupTopBarButtons()`
- [ ] Modificar lógica para botón "operaciones"
- [ ] Navegar directamente al dashboard cuando se hace click
- [ ] Mantener estado activo del botón

## Fase 6: Funcionalidades Específicas

### Parsing de Coordenadas
- [ ] Probar parsing con formato: `-34.6037, -58.3816`
- [ ] Probar parsing con formato: `-34.6037,-58.3816`
- [ ] Probar parsing con formato: `Oficina: -34.6037, -58.3816`
- [ ] Probar parsing con formato JSON
- [ ] Manejar caso de solo texto (retornar null)
- [ ] Validar rangos de coordenadas

### Filtros
- [ ] Probar filtro de tipo de mapa (satelital/detallado)
- [ ] Probar filtro de estado de evento con selección múltiple
- [ ] Probar filtro de cliente en layout principal
- [ ] Verificar que filtro de cliente no aparece en layout cliente
- [ ] Probar que filtros se aplican correctamente a mapa y eventos

### Pantalla Completa
- [ ] Probar pantalla completa del dashboard completo
- [ ] Verificar que top-nav y sidebar se ocultan
- [ ] Verificar que contenido se expande correctamente
- [ ] Probar pantalla completa solo del mapa
- [ ] Verificar que mapa se ajusta correctamente
- [ ] Probar en diferentes navegadores

### Redirecciones
- [ ] Probar redirección a liveview desde tooltip de cámara
- [ ] Verificar que URL de liveview es correcta
- [ ] Probar redirección a detalles de evento
- [ ] Probar redirección a ubicación de vehículo
- [ ] Probar redirección a vista de dock

## Fase 7: Optimización

### Caché
- [ ] Implementar caché de KPIs con TTL de 60 segundos
- [ ] Agregar invalidación de caché cuando sea necesario
- [ ] Probar que caché funciona correctamente

### Consultas
- [ ] Optimizar consultas con eager loading
- [ ] Usar select específico para reducir datos
- [ ] Agregar índices en base de datos si es necesario
- [ ] Probar performance de consultas

### Frontend
- [ ] Implementar lazy loading de Google Maps API
- [ ] Agregar debounce en filtros (300ms)
- [ ] Optimizar renderizado de markers
- [ ] Considerar clustering si hay muchos markers

## Fase 8: Testing

### Backend
- [ ] Crear test unitario para cálculo de KPIs
- [ ] Crear test unitario para filtrado por cliente
- [ ] Crear test unitario para filtrado por estado
- [ ] Crear test unitario para parsing de coordenadas
- [ ] Crear test de integración para APIs
- [ ] Probar permisos (admin vs cliente)

### Frontend
- [ ] Probar inicialización del mapa
- [ ] Probar creación de todos los tipos de markers
- [ ] Probar tooltips de todos los tipos
- [ ] Probar filtros (aplicación y actualización)
- [ ] Probar pantalla completa
- [ ] Probar auto-refresh
- [ ] Probar responsive design
- [ ] Probar en Chrome
- [ ] Probar en Firefox
- [ ] Probar en Safari
- [ ] Probar en Edge

## Fase 9: Documentación

- [ ] Documentar estructura de respuesta de API `/api/operaciones/kpis`
- [ ] Documentar estructura de respuesta de API `/api/operaciones/map-data`
- [ ] Documentar estructura de respuesta de API `/api/operaciones/eventos`
- [ ] Documentar formato de coordenadas en `dispositivos.ubicacion`
- [ ] Documentar permisos necesarios
- [ ] Documentar cómo agregar nuevos tipos de markers
- [ ] Documentar cómo agregar nuevos KPIs
- [ ] Actualizar README con información del dashboard

## Fase 10: Deployment

- [ ] Ejecutar migración en ambiente de staging
- [ ] Verificar que no hay errores en logs
- [ ] Probar dashboard completo en staging
- [ ] Ejecutar migración en producción
- [ ] Verificar dashboard en producción
- [ ] Monitorear performance en producción
- [ ] Recopilar feedback de usuarios
- [ ] Crear issues para mejoras futuras

