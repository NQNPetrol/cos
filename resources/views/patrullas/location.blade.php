<x-app-layout>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        /* Estilos para el popup personalizado del mapa */
        .vehicle-popup .leaflet-popup-content-wrapper {
            background: #1f2937;
            color: #fff;
            border-radius: 8px;
            padding: 0;
        }
        
        .vehicle-popup .leaflet-popup-content {
            margin: 0;
            padding: 12px 16px;
            min-width: 200px;
        }
        
        .vehicle-popup .leaflet-popup-tip {
            background: #1f2937;
        }
        
        /* Estilos para el botón de restablecer vista del mapa */
        .leaflet-control-reset-view {
            background-color: #fff;
            border-bottom: 1px solid #ccc;
            width: 30px;
            height: 30px;
            line-height: 30px;
            display: block;
            text-align: center;
            text-decoration: none;
            color: black;
            box-sizing: border-box;
        }
        
        .leaflet-control-reset-view:hover {
            background-color: #f4f4f4;
        }
        
        .leaflet-control-reset-view svg {
            width: 18px;
            height: 18px;
            display: inline-block;
            vertical-align: middle;
            stroke: currentColor;
        }
        
        /* Estilos para el botón de cambio de estilo del mapa */
        .leaflet-control-map-style {
            background-color: #fff;
            border-bottom: 1px solid #ccc;
            width: 30px;
            height: 30px;
            line-height: 30px;
            display: block;
            text-align: center;
            text-decoration: none;
            color: black;
            box-sizing: border-box;
        }
        
        .leaflet-control-map-style:hover {
            background-color: #f4f4f4;
        }
        
        .leaflet-control-map-style svg {
            width: 18px;
            height: 18px;
            display: inline-block;
            vertical-align: middle;
            stroke: currentColor;
        }
        
        /* Estilo para el icono del vehículo */
        .vehicle-icon {
            background: none;
            border: none;
        }
        
        .vehicle-marker {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .vehicle-marker svg {
            filter: drop-shadow(1px 1px 2px rgba(0,0,0,0.5));
        }
    </style>
    
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="bg-red-600 text-white p-4 rounded-lg mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif
            
            <!-- Última actualización -->
            <div class="bg-blue-900 text-blue-200 p-4 rounded-lg mb-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <span class="font-semibold">Ultimos datos del</span>
                            <span class="ml-2">{{ $lastUpdate ?? 'No hay datos disponibles' }}</span>
                            </span>
                        </div>
                    </div>
                    <div id="refresh-counter" class="bg-blue-800 text-blue-200 px-3 py-1 rounded text-sm">
                        Actualizando en: <span id="countdown">90</span>s
                    </div>
                </div>
            </div>

            <!-- Contenedor 1: Header (Título + Botón Volver + Subtítulo + Botón Actualizar) -->
            <div class="bg-[#252728] rounded-lg p-6 mb-6 border border-transparent">
                <div class="text-gray-100">
                    <a href="{{ route('patrullas.index') }}" 
                    class="flex items-center text-blue-400 hover:text-blue-300 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Ver Patrullas
                    </a>
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-3xl font-bold text-white mb-2">Mapa de Vehículos Móviles</h2>
                            <p class="text-sm text-gray-300">Localización de on-board devices y patrullas</p>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="refreshData()" 
                               class="bg-blue-700 hover:bg-blue-600 text-gray-200 px-4 py-2 rounded-lg transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Actualizar ahora
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenedor 2: Mapa + Mensaje de "Sin información" -->
            <div class="bg-[#252728] rounded-lg p-6 mb-6 border border-transparent">
                <!-- Contenedor del Mapa -->
                <div class="bg-[#252728] rounded-lg p-4">
                    <div class="h-[600px] bg-zinc-700 rounded-lg overflow-hidden" id="map-container">
                        <div id="map" style="height: 100%; width: 100%;"></div>
                    </div>
                </div>
            </div>

            <!-- Contenedor 3: Listado (Tabla de Vehículos Móviles) -->
            <div class="bg-[#252728] rounded-lg p-6 mb-6 border border-transparent">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-[#3b3d3f]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Código
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Dominio
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Región
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Última ubicación
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-[#232527] divide-y divide-gray-600" id="vehicles-table-body">
                            @forelse($mobileVehicles as $vehicle)
                                <tr class="hover:bg-zinc-750 transition-colors" id="vehicle-{{ $vehicle->mobile_vehicle_index_code }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-white">{{ $vehicle->mobile_vehicle_index_code }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-blue-400 font-medium">{{ $vehicle->mobile_vehicle_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-300">
                                            @switch($vehicle->region_index_code)
                                                @case('6')
                                                    <span class="px-2 py-1 bg-zinc-900/30 text-gray-300 rounded text-xs">TECHINT</span>
                                                    @break
                                                @case('11')
                                                    <span class="px-2 py-1 bg-zinc-900/30 text-gray-300 rounded text-xs">PECOM</span>
                                                    @break
                                                @case('23')
                                                    <span class="px-2 py-1 bg-zinc-900/30 text-gray-300 rounded text-xs">VARIOS</span>
                                                    @break
                                                @default
                                                    <span class="px-2 py-1 bg-zinc-900/30 text-gray-300 rounded text-xs">VARIOS</span>
                                            @endswitch
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-green-300 location-data" data-vehicle-id="{{ $vehicle->mobile_vehicle_index_code }}">
                                            @if(isset($locations[$vehicle->mobile_vehicle_index_code]) && $locations[$vehicle->mobile_vehicle_index_code]['latitude'])
                                                {{ number_format($locations[$vehicle->mobile_vehicle_index_code]['latitude'], 6) }}, 
                                                {{ number_format($locations[$vehicle->mobile_vehicle_index_code]['longitude'], 6) }}
                                                <br>
                                                <small class="text-gray-400">
                                                    {{ $locations[$vehicle->mobile_vehicle_index_code]['occurTime'] }}
                                                </small>
                                                <br>
                                                <small class="text-blue-400">
                                                    Velocidad: {{ $locations[$vehicle->mobile_vehicle_index_code]['speed'] }} km/h
                                                </small>
                                            @else
                                                <span class="text-red-300">Sin datos de ubicación recientes</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-300">
                                        No hay vehículos móviles registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if($mobileVehicles->hasPages())
                    <div class="mt-4">
                        {{ $mobileVehicles->links() }}
                    </div>
                @endif
            </div>

            <!-- Contenedor 4: Carta de Recuento -->
            <div class="bg-[#252728] rounded-lg p-6 border border-transparent">
                <div class="text-sm text-gray-300">
                    <p class="mb-2"><strong class="text-gray-200">Vehículos mostrados:</strong> {{ $mobileVehicles->count() }} de {{ $mobileVehicles->total() }}</p>
                    <p><strong class="text-gray-200">Con ubicación disponible:</strong> {{ isset($locations) ? count(array_filter($locations, function($loc) { return isset($loc['latitude']) && $loc['latitude']; })) : 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        let map;
        let markers = {};
        let markersLayer;
        let refreshInterval;
        let countdownInterval;
        let countdownValue = 90;
        let isMapInitialized = false;
        let baseLayers = {};
        let labelsLayer;
        let currentBaseLayer = 'satelital';
        
        // Centro inicial del mapa
        const initialCenter = [-34.6037, -58.3816]; // Buenos Aires por defecto
        const initialZoom = 12;
        
        // Crear icono de patrulla de seguridad - Escudo con vehículo
        function createVehicleIcon(status, direction = 0) {
            const isActive = status == 1;
            const mainColor = isActive ? '#3b82f6' : '#6b7280'; // Azul si activo, gris si no
            const accentColor = isActive ? '#22c55e' : '#ef4444'; // Verde activo, rojo inactivo
            const glowColor = isActive ? '#3b82f6' : '#ef4444';
            
            // Marcador estilo escudo de seguridad con indicador de estado
            const svgIcon = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 56" width="48" height="56" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5)) drop-shadow(0 0 10px ${glowColor}80);">
                    <!-- Pin/Marcador base -->
                    <path d="M24 0 C10.745 0 0 10.745 0 24 C0 36 24 56 24 56 C24 56 48 36 48 24 C48 10.745 37.255 0 24 0Z" 
                          fill="${mainColor}" stroke="#1e3a8a" stroke-width="2"/>
                    
                    <!-- Círculo interior blanco -->
                    <circle cx="24" cy="22" r="16" fill="#ffffff" stroke="#e5e7eb" stroke-width="1"/>
                    
                    <!-- Escudo de seguridad -->
                    <path d="M24 8 L34 12 L34 22 C34 28 30 33 24 36 C18 33 14 28 14 22 L14 12 Z" 
                          fill="${mainColor}" stroke="#1e3a8a" stroke-width="1.5"/>
                    
                    <!-- Símbolo de patrulla/camioneta dentro del escudo -->
                    <g transform="translate(17, 14)">
                        <!-- Carrocería SUV -->
                        <rect x="1" y="6" width="12" height="8" rx="1.5" fill="#ffffff" stroke="#1e3a8a" stroke-width="0.8"/>
                        <!-- Cabina -->
                        <path d="M3 6 L4 3 L10 3 L11 6" fill="#ffffff" stroke="#1e3a8a" stroke-width="0.8"/>
                        <!-- Ventanas -->
                        <rect x="4" y="3.5" width="6" height="2" rx="0.5" fill="#0f172a"/>
                        <!-- Ruedas -->
                        <circle cx="3.5" cy="14" r="2" fill="#1f2937" stroke="#374151" stroke-width="0.5"/>
                        <circle cx="10.5" cy="14" r="2" fill="#1f2937" stroke="#374151" stroke-width="0.5"/>
                        <!-- Barra de luces en techo -->
                        <rect x="4" y="2" width="6" height="1.5" rx="0.5" fill="#3b82f6" stroke="#1e40af" stroke-width="0.3">
                            ${isActive ? '<animate attributeName="fill" values="#3b82f6;#ef4444;#3b82f6" dur="0.8s" repeatCount="indefinite"/>' : ''}
                        </rect>
                    </g>
                    
                    <!-- Indicador de estado (punto) -->
                    <circle cx="38" cy="8" r="6" fill="${accentColor}" stroke="#ffffff" stroke-width="2">
                        ${isActive ? '<animate attributeName="r" values="5;6;5" dur="1s" repeatCount="indefinite"/>' : ''}
                    </circle>
                    
                    <!-- Texto PATROL si está activo -->
                    ${isActive ? '<text x="24" y="42" text-anchor="middle" fill="#ffffff" font-size="5" font-weight="bold" font-family="Arial">ACTIVO</text>' : ''}
                </svg>
            `;
            
            return L.divIcon({
                html: `<div class="vehicle-marker">${svgIcon}</div>`,
                className: 'vehicle-icon',
                iconSize: [48, 56],
                iconAnchor: [24, 56],
                popupAnchor: [0, -56]
            });
        }
        
        // Inicializar el mapa con Leaflet
        function initMap() {
            if (isMapInitialized) return;
            
            map = L.map('map', {
                center: initialCenter,
                zoom: initialZoom,
                zoomControl: true
            });
            
            // Capa satelital (Esri World Imagery)
            baseLayers.satelital = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '&copy; Esri, Maxar, Earthstar Geographics',
                maxZoom: 19
            });
            
            // Capa física/normal (OpenStreetMap)
            baseLayers.fisico = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            });
            
            // Capa de etiquetas con rutas y nombres (solo para satelital)
            labelsLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}{r}.png', {
                attribution: '',
                subdomains: 'abcd',
                maxZoom: 19,
                pane: 'shadowPane'
            });
            
            // Agregar capa satelital por defecto con etiquetas
            baseLayers.satelital.addTo(map);
            labelsLayer.addTo(map);
            currentBaseLayer = 'satelital';
            
            // Capa para los marcadores
            markersLayer = L.layerGroup().addTo(map);
            
            // Crear control personalizado para restablecer vista
            const ResetViewControl = L.Control.extend({
                onAdd: function(map) {
                    const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
                    const button = L.DomUtil.create('a', 'leaflet-control-reset-view', container);
                    button.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
                    button.href = '#';
                    button.title = 'Restablecer vista inicial';
                    
                    L.DomEvent.disableClickPropagation(button);
                    L.DomEvent.on(button, 'click', function(e) {
                        L.DomEvent.stopPropagation(e);
                        L.DomEvent.preventDefault(e);
                        map.setView(initialCenter, initialZoom, {
                            animate: true,
                            duration: 0.5
                        });
                    });
                    
                    return container;
                }
            });
            
            new ResetViewControl({ position: 'topleft' }).addTo(map);
            
            // Crear control para cambiar estilo del mapa
            const MapStyleControl = L.Control.extend({
                onAdd: function(map) {
                    const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
                    const button = L.DomUtil.create('a', 'leaflet-control-map-style', container);
                    button.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>';
                    button.href = '#';
                    button.title = 'Cambiar estilo del mapa';
                    
                    L.DomEvent.disableClickPropagation(button);
                    L.DomEvent.on(button, 'click', function(e) {
                        L.DomEvent.stopPropagation(e);
                        L.DomEvent.preventDefault(e);
                        
                        if (currentBaseLayer === 'satelital') {
                            map.removeLayer(baseLayers.satelital);
                            map.removeLayer(labelsLayer);
                            baseLayers.fisico.addTo(map);
                            currentBaseLayer = 'fisico';
                        } else {
                            map.removeLayer(baseLayers.fisico);
                            baseLayers.satelital.addTo(map);
                            labelsLayer.addTo(map);
                            currentBaseLayer = 'satelital';
                        }
                    });
                    
                    return container;
                }
            });
            
            new MapStyleControl({ position: 'topleft' }).addTo(map);
            
            isMapInitialized = true;
            
            // Cargar datos iniciales
            loadMapData();
            
            // Iniciar actualización automática
            startAutoRefresh();
        }
        
        // Cargar datos del mapa desde la API
        async function loadMapData() {
            try {
                console.log('Cargando datos del mapa...');
                const response = await fetch('/api/mobile-vehicles/locations/current');
                const data = await response.json();

                console.log('Respuesta de locations/current API:', data);
                
                if (data.success && data.locations) {
                    console.log('Datos recibidos:', Object.keys(data.locations).length, 'vehículos');
                    const mapData = await convertLocationsToMapData(data.locations);
                    console.log('Datos convertidos para mapa:', mapData);
                    updateMap(mapData);
                    updateTable(mapData);
                    updateLastUpdateTime(data.latest_timestamp || data.timestamp);
                } else {
                    console.error('Error loading map data:', data.message);
                }
            } catch (error) {
                console.error('Error fetching map data:', error);
            }
        }

        async function convertLocationsToMapData(locations) {
            try {
                console.log('Convirtiendo formato de datos...');
                console.log('Estructura de locations recibida:', locations);
                
                // Primero obtener información de todos los vehículos
                const vehiclesResponse = await fetch('/api/mobile-vehicles/');
                const vehiclesData = await vehiclesResponse.json();
                
                // Crear mapa de vehículos por código
                const vehiclesMap = {};
                vehiclesData.forEach(vehicle => {
                    vehiclesMap[vehicle.mobile_vehicle_index_code] = vehicle;
                });
                
                const mapData = [];
                
                // Procesar cada ubicación
                for (const [vehicleCode, location] of Object.entries(locations)) {
                    const vehicle = vehiclesMap[vehicleCode];
                    
                    if (location && location.latitude && location.longitude) {
                        mapData.push({
                            'vehicle_code': vehicleCode,
                            'vehicle_name': vehicle ? vehicle.mobile_vehicle_name : 'Desconocido',
                            'plate_no': location.plateNo || '',
                            'latitude': parseFloat(location.latitude),
                            'longitude': parseFloat(location.longitude),
                            'occur_time': location.occurTime,
                            'direction': location.direction || 0,
                            'speed': location.speed || 0,
                            'status': vehicle ? vehicle.status : 0
                        });
                        
                        console.log('Vehículo procesado:', vehicleCode, location.latitude, location.longitude);
                    } else {
                        console.warn('Vehículo sin coordenadas válidas:', vehicleCode, location);
                    }
                }
                
                console.log('Conversión completada:', mapData.length, 'vehículos válidos');
                return mapData;
                
            } catch (error) {
                console.error('Error convirtiendo datos:', error);
                return [];
            }
        }
        
        // Actualizar el mapa con nuevos marcadores
        function updateMap(vehiclesData) {
            if (!isMapInitialized) return;
            
            console.log('Actualizando mapa con', vehiclesData.length, 'vehículos');
            
            // Limpiar marcadores antiguos
            markersLayer.clearLayers();
            markers = {};
            
            const bounds = [];
            
            // Crear nuevos marcadores
            vehiclesData.forEach(vehicle => {
                if (vehicle.latitude && vehicle.longitude) {
                    const position = [parseFloat(vehicle.latitude), parseFloat(vehicle.longitude)];
                    bounds.push(position);
                    
                    // Crear icono de vehículo
                    const vehicleIcon = createVehicleIcon(vehicle.status, vehicle.direction);
                    
                    // Crear marcador
                    const marker = L.marker(position, {
                        icon: vehicleIcon,
                        title: vehicle.vehicle_name
                    });
                    
                    // Popup con detalles del vehículo
                    const popupContent = `
                        <div class="text-white">
                            <h3 class="font-bold text-lg mb-2 text-blue-400">${vehicle.vehicle_name}</h3>
                            <div class="space-y-1 text-sm">
                                <p><span class="text-gray-400">Patente:</span> <span class="text-white">${vehicle.plate_no || 'N/A'}</span></p>
                                <p><span class="text-gray-400">Velocidad:</span> <span class="text-green-400">${vehicle.speed} km/h</span></p>
                                <p><span class="text-gray-400">Dirección:</span> <span class="text-white">${vehicle.direction}°</span></p>
                                <p><span class="text-gray-400">Última actualización:</span><br><span class="text-yellow-400">${vehicle.occur_time}</span></p>
                                <p><span class="text-gray-400">Coordenadas:</span><br><span class="text-gray-300 text-xs">${vehicle.latitude.toFixed(6)}, ${vehicle.longitude.toFixed(6)}</span></p>
                            </div>
                        </div>
                    `;
                    
                    marker.bindPopup(popupContent, {
                        className: 'vehicle-popup',
                        maxWidth: 280
                    });
                    
                    marker.addTo(markersLayer);
                    markers[vehicle.vehicle_code] = marker;
                }
            });
            
            // Ajustar vista si hay marcadores
            if (bounds.length > 0) {
                map.fitBounds(bounds, {
                    padding: [50, 50],
                    maxZoom: 15
                });
            }
        }
        
        // Actualizar la tabla con la información de ubicación
        function updateTable(vehiclesData) {
            console.log('📝 Actualizando tabla con:', vehiclesData.length, 'vehículos');
            vehiclesData.forEach(vehicle => {
                const locationElement = document.querySelector(`.location-data[data-vehicle-id="${vehicle.vehicle_code}"]`);
                if (locationElement) {
                    if (vehicle.latitude && vehicle.longitude) {
                        locationElement.innerHTML = `
                            ${vehicle.latitude.toFixed(6)}, ${vehicle.longitude.toFixed(6)}<br>
                            <small class="text-gray-400">${vehicle.occur_time}</small>
                            <br>
                            <small class="text-blue-400">Velocidad: ${vehicle.speed} km/h</small>
                        `;

                        const statusElement = locationElement.closest('tr').querySelector('.px-2.py-1.text-xs.font-medium.rounded');
                        if (statusElement && vehicle.status === 1) {
                            statusElement.className = 'px-2 py-1 text-xs font-medium rounded bg-green-900/30 text-green-300 border border-green-600/50';
                            statusElement.textContent = 'ACTIVO';
                        }
                    } else {
                        locationElement.innerHTML = '<span class="text-yellow-400">Sin datos de ubicación</span>';
                    }
                }
            });
        }
        
        // Actualizar el tiempo de última actualización
        function updateLastUpdateTime(timestamp) {
            let timeString;
            if (timestamp) {
                const date = typeof timestamp === 'string' ? 
                    new Date(timestamp) : 
                    new Date(timestamp * 1000);
                timeString = date.toLocaleDateString('es-AR') + ' ' + date.toLocaleTimeString('es-AR');
                
                const lastUpdateElement = document.querySelector('#last-update span');
                if (lastUpdateElement) {
                    lastUpdateElement.textContent = timeString;
                }
            } else {
                timeString = 'No hay datos disponibles';
            }
            console.log('Última actualización:', timeString);
        }
        
        // Iniciar actualización automática
        function startAutoRefresh() {
            console.log('Iniciando auto-refresh cada 90 segundos');
            
            refreshInterval = setInterval(() => {
                loadMapData();
                resetCountdown();
            }, 90000);
            
            resetCountdown();
        }
        
        // Reiniciar contador regresivo
        function resetCountdown() {
            countdownValue = 90;
            updateCountdown();
            
            if (countdownInterval) clearInterval(countdownInterval);
            countdownInterval = setInterval(() => {
                countdownValue--;
                updateCountdown();
                
                if (countdownValue <= 0) {
                    clearInterval(countdownInterval);
                }
            }, 1000);
        }
        
        // Actualizar visualización del contador
        function updateCountdown() {
            const countdownElement = document.getElementById('countdown');
            if (countdownElement) {
                countdownElement.textContent = countdownValue;
            }
        }
        
        // Función para actualizar manualmente
        function refreshData() {
            console.log('Actualización manual solicitada');
            loadMapData();
            resetCountdown();
        }
        
        // Iniciar cuando el documento esté listo
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM cargado, inicializando mapa Leaflet...');
            initMap();
        });
        
        // Manejar visibilidad de la página para optimizar recursos
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                if (refreshInterval) clearInterval(refreshInterval);
                if (countdownInterval) clearInterval(countdownInterval);
                console.log('Actualizaciones pausadas (página oculta)');
            } else {
                startAutoRefresh();
                console.log('Actualizaciones reanudadas (página visible)');
            }
        });
    </script>
</x-app-layout>