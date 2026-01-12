@extends('layouts.cliente')
@section('content')
    <div class="py-4">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-zinc-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-100 dark:text-gray-100">
                    <!-- Header -->
                    <div class="mb-4">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-gray-100">Despliegue de Misiones</h2>
                            <a href="https://console.flytbase.com" target="_blank" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 transition ease-in-out duration-150">
                                Flytbase Console
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="space-y-4 mt-2">
                        <!-- Panel de Configuración de Alertas -->
                        <div class="bg-zinc-800 rounded-lg shadow-sm border border-zinc-700">
                            <div class="p-4">
                                <div class="space-y-4">
                                    <!-- Selección de Misión -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-3">Seleccionar Misión</label>
                                        <select id="misionSelect" name="mision_id" class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-4 py-3 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition-colors">
                                            <option value="">Seleccione una misión disponible</option>
                                            @foreach($misiones as $mision)
                                                <option value="{{ $mision->id }}"
                                                    data-nombre="{{ $mision->nombre }}"
                                                    data-drone="{{ $mision->drone->drone ?? 'No asignado'}}"
                                                    data-descripcion="{{ $mision->descripcion ?? 'Sin descripcion disponible' }}"
                                                    data-waypoints="{{ $mision->waypoints ? json_encode($mision->waypoints) : '' }}"
                                                    data-kmz-file-path="{{ $mision->kmz_file_path ?? '' }}"
                                                    data-dock-latitud="{{ $mision->dock->latitud ?? '' }}"
                                                    data-dock-longitud="{{ $mision->dock->longitud ?? '' }}"
                                                    data-dock-altitude="{{ $mision->dock->altitude ?? '' }}"
                                                    data-dock-nombre="{{ $mision->dock->nombre ?? '' }}"
                                                    data-site-nombre="{{ $mision->site->nombre ?? '' }}"
                                                    data-observaciones="{{ $mision->observaciones ?? '' }}"
                                                    data-route-altitude="{{ $mision->route_altitude ?? '' }}"
                                                    data-route-speed="{{ $mision->route_speed ?? '' }}"
                                                    data-est-total-duration="{{ $mision->est_total_duration ?? '' }}"
                                                    data-est-total-distance="{{ $mision->est_total_distance ?? '' }}"
                                                    data-route-waypoint-type="{{ $mision->route_waypoint_type ?? '' }}"
                                                    data-waypoints-count="{{ $mision->waypoints_count ?? '' }}">
                                                    {{ $mision->nombre}}
                                                    @if($mision->cliente)
                                                        - {{ $mision->cliente->nombre }}
                                                    @endif
                                                    @if($mision->drone)
                                                        ({{ $mision->drone->drone }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @if($misiones->isEmpty())
                                            <p class="text-xs text-yellow-400 mt-2">
                                                No hay misiones disponibles para su cliente. Contacte al administrador.
                                            </p>
                                        @else
                                            
                                        @endif
                                    </div>

                                    <!-- Información de configuración -->
                                    <div class="bg-zinc-750 rounded-lg p-4 border border-zinc-600">
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-blue-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div class="flex-1">
                                                <h4 class="text-sm font-medium text-gray-200">Tener en cuenta</h4>
                                                <p class="text-xs text-gray-400 mt-1">Condiciones climáticas entre otros factores inesperados pueden impedir que el drone despliegue. En caso de que salte un error, comunicarse con el soporte del COS (cos.support@cyhsur.com)</p>
                                                
                                                <!-- Mensaje dinámico para trigger misión exitoso -->
                                                <div id="triggerSuccessMessage" class="hidden mt-4 p-3 bg-green-900/30 border border-green-700 rounded-lg">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex-1">
                                                            <p class="text-xs text-green-300 font-medium mb-2">
                                                                Misión desplegada exitosamente. El dron está en camino.
                                                            </p>
                                                            <div class="flex items-center space-x-3">
                                                                <a id="liveviewButton" href="#" 
                                                                    class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 transition ease-in-out duration-150">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                                    </svg>
                                                                    Ver Streaming del Drone
                                                                </a>
                                                                <button type="button" onclick="hideTriggerSuccessMessage()" 
                                                                        class="text-green-300 hover:text-white transition-colors duration-200">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botón Enviar -->
                                <div class="flex justify-end mt-8 pt-6 border-t border-zinc-700">
                                    <button id="triggerAlarmBtn"
                                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 border border-transparent rounded-lg font-medium text-sm text-white uppercase tracking-widest hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-105"
                                            {{ $misiones->isEmpty() ? 'disabled' : '' }}>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-send-exclamation mr-3" viewBox="0 0 16 16">
                                            <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855a.75.75 0 0 0-.124 1.329l4.995 3.178 1.531 2.406a.5.5 0 0 0 .844-.536L6.637 10.07l7.494-7.494-1.895 4.738a.5.5 0 1 0 .928.372zm-2.54 1.183L5.93 9.363 1.591 6.602z"/>
                                            <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1.5a.5.5 0 0 1-1 0V11a.5.5 0 0 1 1 0m0 3a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"/>
                                        </svg>
                                        Enviar Drone
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Resumen de Misión Seleccionada -->
                        <div class="bg-zinc-800 rounded-lg shadow-sm border border-zinc-700">
                            <div class="p-6">
                                

                                <!-- Estado vacío -->
                                <div id="emptyMissionState" class="text-center py-12">
                                    <div class="w-20 h-20 mx-auto mb-4 bg-zinc-700 rounded-full flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-medium text-gray-300 mb-2">Selecciona una misión</h4>
                                    <p class="text-gray-400">Primero debes seleccionar una misión del listado superior para ver los detalles.</p>
                                </div>

                                <!-- Contenido del resumen (oculto inicialmente) -->
                                <div id="missionSummary" class="hidden">
                                    <div class="w-full">
                                        <div class="grid grid-cols-1 md:grid-cols-10 gap-3 mb-3">
                                            <!-- Información Básica -->
                                            <div class="bg-zinc-750 rounded-lg p-3 border border-zinc-600 md:col-span-2">
                                                <div class="flex items-center mb-2">
                                                    <svg class="w-4 h-4 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <h4 class="text-xs font-medium text-gray-200">Información Básica</h4>
                                                </div>
                                                <div class="space-y-1.5">
                                                    <div>
                                                        <p class="text-xs text-gray-400">Site</p>
                                                        <p id="summarySite" class="text-xs font-medium text-gray-100">-</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-400">Nombre de la Misión</p>
                                                        <p id="summaryNombre" class="text-xs font-medium text-gray-100">-</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-400">Dispositivos Asignados</p>
                                                        <p id="summaryDispositivos" class="text-xs font-medium text-gray-100">-</p>
                                                    </div>
                                                    <div id="summaryObservacionesContainer" class="hidden">
                                                        <p class="text-xs text-gray-400">Observaciones</p>
                                                        <p id="summaryObservaciones" class="text-xs text-gray-300">-</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Información de Vuelo -->
                                            <div class="bg-zinc-750 rounded-lg px-2 py-2 border border-zinc-600 md:col-span-2">
                                                <div class="flex items-center mb-2">
                                                    <svg class="w-4 h-4 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                                    </svg>
                                                    <h4 class="text-xs font-medium text-gray-200">Información de Vuelo</h4>
                                                </div>
                                                <div class="grid grid-cols-2">
                                                    <div>
                                                        <p class="text-xs text-gray-400">Altitud de Vuelo</p>
                                                        <p id="summaryRouteAltitude" class="text-xs font-medium text-gray-100">-</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-400">Velocidad de Vuelo</p>
                                                        <p id="summaryRouteSpeed" class="text-xs font-medium text-gray-100">-</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-400">Duración Estimada</p>
                                                        <p id="summaryEstDuration" class="text-xs font-medium text-gray-100">-</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-400">Distancia Estimada</p>
                                                        <p id="summaryEstDistance" class="text-xs font-medium text-gray-100">-</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-400">Tipo de Waypoint</p>
                                                        <p id="summaryWaypointType" class="text-xs font-medium text-gray-100">-</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-400">Recuento de Waypoints</p>
                                                        <p id="summaryWaypointsCount" class="text-xs font-medium text-gray-100">-</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Descripción -->
                                            <div class="bg-zinc-750 rounded-lg p-3 border border-zinc-600 md:col-span-6">
                                            <div class="flex items-center mb-2">
                                                <svg class="w-4 h-4 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                <h4 class="text-xs font-medium text-gray-200">Descripción</h4>
                                            </div>
                                            <p id="summaryDescripcion" class="text-xs text-gray-300 leading-relaxed">-</p>
                                        </div>
                                    </div>

                                    <!-- Mapa de Ruta -->
                                    <div id="missionMapContainer" class="bg-zinc-750 rounded-lg p-3 border border-zinc-600 hidden mt-3">
                                        <div class="flex items-center mb-2">
                                            <svg class="w-4 h-4 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                            </svg>
                                            <h4 class="text-xs font-medium text-gray-200">Ruta de la Misión</h4>
                                        </div>
                                        <div id="missionMap" style="height: 300px; width: 100%; border-radius: 8px; overflow: hidden;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para manejar el trigger -->
    <script>
        // Manejar cambio en la selección de misión
        document.getElementById('misionSelect').addEventListener('change', function() {
            updateMissionSummary(this);
        });

        let missionMap = null;
        let missionMapLayer = null;

        function updateMissionSummary(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const emptyState = document.getElementById('emptyMissionState');
            const missionSummary = document.getElementById('missionSummary');
            const mapContainer = document.getElementById('missionMapContainer');

            if (selectedOption.value === '') {
                // Mostrar estado vacío
                emptyState.classList.remove('hidden');
                missionSummary.classList.add('hidden');
                mapContainer.classList.add('hidden');
                if (missionMap) {
                    missionMap.remove();
                    missionMap = null;
                }
            } else {
                // Ocultar estado vacío y mostrar resumen
                emptyState.classList.add('hidden');
                missionSummary.classList.remove('hidden');

                // Actualizar información del resumen
                const siteNombre = selectedOption.getAttribute('data-site-nombre') || '-';
                const dockNombre = selectedOption.getAttribute('data-dock-nombre') || '';
                const droneNombre = selectedOption.getAttribute('data-drone') || '';
                const observaciones = selectedOption.getAttribute('data-observaciones') || '';
                
                document.getElementById('summarySite').textContent = siteNombre;
                document.getElementById('summaryNombre').textContent = selectedOption.getAttribute('data-nombre') || '-';
                
                // Dispositivos asignados: dock - drone
                let dispositivosTexto = '-';
                if (dockNombre && droneNombre && droneNombre !== 'No asignado') {
                    dispositivosTexto = `${dockNombre} - ${droneNombre}`;
                } else if (dockNombre) {
                    dispositivosTexto = dockNombre;
                } else if (droneNombre && droneNombre !== 'No asignado') {
                    dispositivosTexto = droneNombre;
                }
                document.getElementById('summaryDispositivos').textContent = dispositivosTexto;
                
                // Observaciones (solo si no es null/vacío)
                const observacionesContainer = document.getElementById('summaryObservacionesContainer');
                if (observaciones && observaciones.trim() !== '') {
                    document.getElementById('summaryObservaciones').textContent = observaciones;
                    observacionesContainer.classList.remove('hidden');
                } else {
                    observacionesContainer.classList.add('hidden');
                }
                
                document.getElementById('summaryDescripcion').textContent = selectedOption.getAttribute('data-descripcion') || 'Sin descripción disponible';
                
                // Información de vuelo
                const routeAltitude = selectedOption.getAttribute('data-route-altitude') || '';
                const routeSpeed = selectedOption.getAttribute('data-route-speed') || '';
                const estDuration = selectedOption.getAttribute('data-est-total-duration') || '';
                const estDistance = selectedOption.getAttribute('data-est-total-distance') || '';
                const waypointType = selectedOption.getAttribute('data-route-waypoint-type') || '';
                const waypointsCount = selectedOption.getAttribute('data-waypoints-count') || '';
                
                document.getElementById('summaryRouteAltitude').textContent = routeAltitude ? `${routeAltitude} m` : '-';
                document.getElementById('summaryRouteSpeed').textContent = routeSpeed ? `${routeSpeed} m/s` : '-';
                
                // Formatear duración estimada (segundos a minutos:segundos)
                if (estDuration && estDuration !== '') {
                    const seconds = parseInt(estDuration);
                    const minutes = Math.floor(seconds / 60);
                    const remainingSeconds = seconds % 60;
                    document.getElementById('summaryEstDuration').textContent = `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
                } else {
                    document.getElementById('summaryEstDuration').textContent = '-';
                }
                
                // Formatear distancia estimada (metros a km si es mayor a 1000)
                if (estDistance && estDistance !== '') {
                    const distance = parseFloat(estDistance);
                    if (distance >= 1000) {
                        document.getElementById('summaryEstDistance').textContent = `${(distance / 1000).toFixed(2)} km`;
                    } else {
                        document.getElementById('summaryEstDistance').textContent = `${distance.toFixed(0)} m`;
                    }
                } else {
                    document.getElementById('summaryEstDistance').textContent = '-';
                }
                
                // Tipo de waypoint (traducir)
                const waypointTypeLabels = {
                    'linear_route': 'Ruta Lineal',
                    'transits_waypoint': 'Tránsitos Waypoint',
                    'curved_route_drone_stops': 'Ruta Curva (Drone Se Detiene)',
                    'curved_route_drone_continues': 'Ruta Curva (Drone Continúa)'
                };
                document.getElementById('summaryWaypointType').textContent = waypointType ? (waypointTypeLabels[waypointType] || waypointType) : '-';
                
                document.getElementById('summaryWaypointsCount').textContent = waypointsCount && waypointsCount !== '' ? `${waypointsCount} waypoint${parseInt(waypointsCount) !== 1 ? 's' : ''}` : '-';

                // Obtener waypoints y datos del dock
                const waypointsData = selectedOption.getAttribute('data-waypoints');
                const kmzFilePath = selectedOption.getAttribute('data-kmz-file-path');
                const dockLatitud = selectedOption.getAttribute('data-dock-latitud');
                const dockLongitud = selectedOption.getAttribute('data-dock-longitud');
                const dockAltitude = selectedOption.getAttribute('data-dock-altitude');
                

                // Preparar datos del dock si existen
                const dockData = (dockLatitud && dockLongitud) ? {
                    latitud: dockLatitud,
                    longitud: dockLongitud,
                    altitude: dockAltitude || 'N/A',
                    nombre: dockNombre || 'Base'
                } : null;

                if (waypointsData && waypointsData !== '') {
                    try {
                        const waypoints = JSON.parse(waypointsData);
                        if (waypoints && waypoints.length > 0) {
                            // Mostrar mapa y dibujar ruta con dock
                            mapContainer.classList.remove('hidden');
                            drawMissionRoute(waypoints, dockData);
                        } else {
                            mapContainer.classList.add('hidden');
                        }
                    } catch (e) {
                        console.error('Error al parsear waypoints:', e);
                        mapContainer.classList.add('hidden');
                    }
                } else {
                    mapContainer.classList.add('hidden');
                }
            }
        }

        function drawMissionRoute(waypoints, dockData = null) {
            // Esperar a que Leaflet esté disponible
            if (typeof L === 'undefined') {
                console.error('Leaflet no está disponible, esperando...');
                setTimeout(() => drawMissionRoute(waypoints, dockData), 100);
                return;
            }

            // Limpiar mapa anterior si existe
            if (missionMap) {
                missionMap.remove();
            }

            // Determinar centro inicial (dock si existe, sino primer waypoint)
            const initialCenter = dockData 
                ? [parseFloat(dockData.latitud), parseFloat(dockData.longitud)]
                : [parseFloat(waypoints[0].latitud), parseFloat(waypoints[0].longitud)];

            // Crear mapa
            missionMap = L.map('missionMap', {
                center: initialCenter,
                zoom: 15,
                zoomControl: true
            });

            // Configurar iconos de Leaflet
            delete L.Icon.Default.prototype._getIconUrl;
            L.Icon.Default.mergeOptions({
                iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon-2x.png',
                iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
            });

            // Añadir capa base satelital
            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '© Esri, Maxar, Earthstar Geographics',
                maxZoom: 19
            }).addTo(missionMap);

            // Capa de etiquetas
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}{r}.png', {
                attribution: '',
                subdomains: 'abcd',
                maxZoom: 19
            }).addTo(missionMap);

            // Convertir waypoints a coordenadas de Leaflet [lat, lng]
            const routeCoordinates = waypoints.map(wp => [
                parseFloat(wp.latitud),
                parseFloat(wp.longitud)
            ]);

            // Dibujar línea que conecta todos los waypoints
            const polyline = L.polyline(routeCoordinates, {
                color: '#3b82f6',
                weight: 4,
                opacity: 0.8
            }).addTo(missionMap);

            // Agregar marcador del dock si existe
            let dockMarker = null;
            if (dockData && dockData.latitud && dockData.longitud) {
                // Función para crear icono redondo verde para el dock
                function createDockIcon() {
                    return L.divIcon({
                        className: 'custom-dock-icon',
                        html: `<div style="
                            background-color: #22c55e;
                            width: 32px;
                            height: 32px;
                            border-radius: 50%;
                            border: 3px solid white;
                            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            color: white;
                        ">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="18"
                                height="18"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M6 12h12v-2a2 2 0 0 1 2 -2a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2a2 2 0 0 1 2 2v2z" />
                                <path d="M6 15h12" />
                                <path d="M6 18h12" />
                                <path d="M21 11v7" />
                                <path d="M3 11v7" />
                            </svg>
                        </div>`,
                        iconSize: [32, 32],
                        iconAnchor: [16, 16],
                        popupAnchor: [0, -16]
                    });
                }

                // Crear marcador del dock
                dockMarker = L.marker([parseFloat(dockData.latitud), parseFloat(dockData.longitud)], {
                    icon: createDockIcon()
                }).addTo(missionMap);

                // Popup del dock
                const dockPopupContent = `
                    <div class="text-sm" style="min-width: 200px;">
                        <strong>Base</strong><br>
                        <div class="mt-2 space-y-1">
                            <div><span class="text-gray-600">Latitud:</span> ${dockData.latitud}</div>
                            <div><span class="text-gray-600">Longitud:</span> ${dockData.longitud}</div>
                            <div><span class="text-gray-600">Altitud:</span> ${dockData.altitude} m</div>
                        </div>
                    </div>
                `;
                dockMarker.bindPopup(dockPopupContent);

                // Dibujar línea de despegue (dock al primer waypoint) - verde punteada
                if (waypoints.length > 0) {
                    const takeoffLine = L.polyline([
                        [parseFloat(dockData.latitud), parseFloat(dockData.longitud)],
                        [parseFloat(waypoints[0].latitud), parseFloat(waypoints[0].longitud)]
                    ], {
                        color: '#22c55e',
                        weight: 3,
                        opacity: 0.7,
                        dashArray: '10, 5'
                    }).addTo(missionMap);
                }

                // Dibujar línea de retorno RTH (último waypoint al dock) - roja punteada
                if (waypoints.length > 0) {
                    const lastWaypoint = waypoints[waypoints.length - 1];
                    const rthLine = L.polyline([
                        [parseFloat(lastWaypoint.latitud), parseFloat(lastWaypoint.longitud)],
                        [parseFloat(dockData.latitud), parseFloat(dockData.longitud)]
                    ], {
                        color: '#ef4444',
                        weight: 3,
                        opacity: 0.7,
                        dashArray: '10, 5'
                    }).addTo(missionMap);
                }
            }

            // Función para crear icono circular personalizado con número
            function createNumberedIcon(number) {
                return L.divIcon({
                    className: 'custom-numbered-marker',
                    html: `<div style="
                        background-color: #3b82f6;
                        width: 32px;
                        height: 32px;
                        border-radius: 50%;
                        border: 3px solid white;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-weight: bold;
                        font-size: 14px;
                        text-align: center;
                        line-height: 1;
                    ">${number}</div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 16],
                    popupAnchor: [0, -16]
                });
            }

            // Agregar marcadores en cada waypoint
            waypoints.forEach((wp, index) => {
                const waypointNumber = index + 1;
                const marker = L.marker([parseFloat(wp.latitud), parseFloat(wp.longitud)], {
                    icon: createNumberedIcon(waypointNumber)
                }).addTo(missionMap);

                // Función helper para obtener etiqueta de acción
                function getActionLabel(action) {
                    // Si es un objeto (drone_yaw)
                    if (typeof action === 'object' && action.type === 'drone_yaw') {
                        return `Rotar Yaw del Drone: ${action.angle}°`;
                    }
                    
                    const acciones = {
                        'take_thermal_image': 'Capturar Imagen Térmica',
                        'take_wide_image': 'Capturar Imagen Angular',
                        'take_panorama_image': 'Capturar Imagen Panoramica',
                        'start_recording': 'Iniciar Grabación',
                        'stop_recording': 'Detener Grabación',
                        'zoom_in': 'Activar Zoom',
                        'set_gimbal_90': 'Rotar Camara a 90°',
                        'set_gimbal_45': 'Rotar Camara 45°'
                    };
                    return acciones[action] || action;
                }

                // Agregar popup con información del waypoint
                let popupContent = `
                    <div class="text-sm" style="min-width: 200px;">
                        <strong>Waypoint ${waypointNumber}</strong><br>
                        <div class="mt-2 space-y-1">
                            <div><span class="text-gray-600">Lat:</span> ${wp.latitud}</div>
                            <div><span class="text-gray-600">Lng:</span> ${wp.longitud}</div>
                            <div><span class="text-gray-600">Altitud:</span> ${wp.altitud || 'N/A'} m</div>
                        </div>
                `;
                
                if (wp.acciones && wp.acciones.length > 0) {
                    popupContent += `
                        <div class="mt-3 pt-2 border-t border-zinc-300">
                            <div class="text-xs font-medium text-gray-600 mb-1">Acciones:</div>
                            <div class="flex flex-wrap gap-1">
                                ${wp.acciones.map(accion => `
                                    <span class="inline-block px-2 py-1 bg-blue-600 text-white text-xs rounded">
                                        ${getActionLabel(accion)}
                                    </span>
                                `).join('')}
                            </div>
                        </div>
                    `;
                }
                
                popupContent += `</div>`;
                marker.bindPopup(popupContent);
            });

            // Ajustar el mapa para mostrar toda la ruta incluyendo el dock
            let bounds = polyline.getBounds();
            if (dockMarker) {
                bounds = bounds.extend(dockMarker.getLatLng());
            }
            missionMap.fitBounds(bounds, {
                padding: [20, 20]
            });
        }

        // Inicializar el resumen al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            const selectElement = document.getElementById('misionSelect');
            updateMissionSummary(selectElement);
        });


        document.getElementById('triggerAlarmBtn').addEventListener('click', function() {
            const button = this;
            const originalText = button.innerHTML;
            const misionId = document.getElementById('misionSelect').value;
            
            // Validar que tenga misión seleccionada
            if (!misionId) {
                showTemporaryAlert('error', 'Debe seleccionar una misión para continuar.');
                return;
            }
            
            // Mostrar loading
            button.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Enviando...
            `;
            button.disabled = true;

            // Preparar datos para enviar - SIEMPRE será trigger_mision
            const requestData = {
                tipo_alerta: 'trigger_mision',
                mision_id: misionId
            };

            // Hacer la petición AJAX
            fetch('{{ route("client.alertas.trigger-alarm") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta recibida del servidor:', data);
                
                if (data.success) {
                    console.log('Has liveview:', data.has_liveview);
                    
                    // Si tiene liveview, mostrar el mensaje especial
                    if (data.has_liveview) {
                        console.log('Condición cumplida - mostrando mensaje');
                        showTriggerSuccessMessage(data);
                    } else {
                        console.log('Condición NO cumplida - mostrando alerta temporal');
                        // Para misiones sin liveview, mostrar alerta temporal
                        showTemporaryAlert('success', data.message);
                    }
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                showTemporaryAlert('error', 'Error al enviar la alarma: ' + error.message);
            })
            .finally(() => {
                // Restaurar botón después de 3 segundos
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 3000);
            });
        });

        function showTriggerSuccessMessage(data) {
            const successMessage = document.getElementById('triggerSuccessMessage');
            const liveviewButton = document.getElementById('liveviewButton');
            
            console.log('Elemento successMessage:', successMessage); 
            console.log('Elemento liveviewButton:', liveviewButton);
            console.log('Datos para liveview:', data); 
            
            if (data.drone_name) {
                
                const droneName = data.drone_name.toLowerCase();
                
                // Construir la ruta para clientes: /client/drones/{droneName}/liveview?mision_id={misionId}
                const liveviewUrl = "{{ route('client.streaming.drone.liveview', ['droneName' => 'DRONE_PLACEHOLDER']) }}".replace('DRONE_PLACEHOLDER', droneName);
                liveviewButton.href = liveviewUrl + '?mision_id=' + data.mision_id;
                
                console.log('URL de liveview construida:', liveviewButton.href);
            } else {
                console.log('Usando URL de liveview genérica:', liveviewButton.href);
            }
            
            // Mostrar el mensaje
            console.log('Clases antes:', successMessage.className); 
            successMessage.classList.remove('hidden');
            successMessage.classList.add('block');
            console.log('Clases después:', successMessage.className);
            
            console.log('Mensaje de éxito mostrado para misión:', data.mision_nombre);
        }

        function hideTriggerSuccessMessage() {
            const successMessage = document.getElementById('triggerSuccessMessage');
            successMessage.classList.remove('block');
            successMessage.classList.add('hidden');
        }

        function showTemporaryAlert(type, message) {
            // Crear elemento de alerta temporal
            const alertDiv = document.createElement('div');
            alertDiv.className = `mb-6 p-4 ${
                type === 'success' 
                    ? 'bg-green-800 border-green-600 text-green-100' 
                    : 'bg-red-800 border-red-600 text-red-100'
            } border rounded-lg flex items-center justify-between shadow-lg`;
            
            alertDiv.innerHTML = `
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${
                                type === 'success' 
                                    ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                                    : 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                            }"/>
                        </svg>
                        <span class="text-sm font-medium">${message}</span>
                    </div>
                    <button type="button" class="text-${type === 'success' ? 'green' : 'red'}-300 hover:text-white" onclick="this.parentElement.parentElement.remove()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            
            // Insertar después del header
            const header = document.querySelector('h2');
            header.parentNode.insertBefore(alertDiv, header.nextSibling);
            
            // Auto-remover después de 5 segundos
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }

        // Ocultar mensaje de éxito al cargar la página (para asegurar estado inicial)
        document.addEventListener('DOMContentLoaded', function() {
            hideTriggerSuccessMessage();
        });
    </script>
@push('scripts')
<!-- Leaflet CSS y JS para el mapa de rutas -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endpush
@endsection