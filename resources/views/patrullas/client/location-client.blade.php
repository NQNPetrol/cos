@extends('layouts.cliente')
@section('content')
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
                        Actualizando en: <span id="countdown">10</span>s
                    </div>
                </div>
            </div>

            <!-- Header -->
            <div class="bg-slate-900 overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <div class="p-6 text-gray-100">
                    <!-- Header -->
                    <div class="mb-6">
                        <a href="{{ route('patrullas.index') }}" 
                        class="flex items-center text-blue-400 hover:text-blue-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Ver Patrullas
                        </a>
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-3xl font-bold text-white mb-2">Mapa de vehiculos Moviles</h2>
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

                    <!-- Contenedor del Mapa -->
                    <div class="bg-gray-800 rounded-lg p-4 mb-6">
                        
                        <div class="h-96 bg-gray-700 rounded-lg" id="map-container">
                            <div id="map" style="height: 100%; width: 100%;"></div>
                        </div>
                    </div>

                    <!-- Tabla de Vehículos Móviles -->
                    <div class="bg-gray-700 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-600">
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
                                <tbody class="bg-gray-800 divide-y divide-gray-600" id="vehicles-table-body">
                                    @forelse($mobileVehicles as $vehicle)
                                        <tr class="hover:bg-gray-750 transition-colors" id="vehicle-{{ $vehicle->mobile_vehicle_index_code }}">
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
                                                            <span class="px-2 py-1 bg-gray-900/30 text-gray-300 rounded text-xs">TECHINT</span>
                                                            @break
                                                        @case('11')
                                                            <span class="px-2 py-1 bg-gray-900/30 text-gray-300 rounded text-xs">PECOM</span>
                                                            @break
                                                        @case('23')
                                                            <span class="px-2 py-1 bg-gray-900/30 text-gray-300 rounded text-xs">VARIOS</span>
                                                            @break
                                                        @default
                                                            <span class="px-2 py-1 bg-gray-900/30 text-gray-300 rounded text-xs">VARIOS</span>
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
                                            <td colspan="5" class="px-6 py-4 text-center text-gray-400">
                                                No hay vehículos móviles registrados.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Paginación -->
                    @if($mobileVehicles->hasPages())
                        <div class="mt-6">
                            {{ $mobileVehicles->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap" async defer></script>
    
    <script>
        let map;
        let markers = {};
        let refreshInterval;
        let countdownInterval;
        let countdownValue = 10;
        let isMapInitialized = false;
        
        // Inicializar el mapa
        function initMap() {
            // Centro inicial del mapa (puedes ajustar estas coordenadas)
            const initialCenter = { lat: -34.6037, lng: -58.3816 }; // Buenos Aires por defecto
            
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: initialCenter,
                mapTypeId: google.maps.MapTypeId.SATELLITE,
                mapTypeControl: true, // Permitir cambiar entre tipos de mapa
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                    position: google.maps.ControlPosition.TOP_RIGHT
                },
                zoomControl: true,
                streetViewControl: true,
                fullscreenControl: true
                
            });
            
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
                
                // Procesar cada ubicación - locations ahora es un objeto plano
                for (const [vehicleCode, location] of Object.entries(locations)) {
                    const vehicle = vehiclesMap[vehicleCode];
                    
                    // Validar que la ubicación tenga coordenadas
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
            Object.values(markers).forEach(marker => marker.setMap(null));
            markers = {};
            
            // Crear nuevos marcadores
            vehiclesData.forEach(vehicle => {
                if (vehicle.latitude && vehicle.longitude) {
                    const position = { 
                        lat: parseFloat(vehicle.latitude), 
                        lng: parseFloat(vehicle.longitude) 
                    };
                    
                    const marker = new google.maps.Marker({
                        position: position,
                        map: map,
                        title: vehicle.vehicle_name,
                        icon: {
                            url: vehicle.status == 1 
                                ? 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
                                : 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
                            scaledSize: new google.maps.Size(32, 32)
                        }
                    });
                    
                    // Info window con detalles del vehículo
                    const infoWindow = new google.maps.InfoWindow({
                        content: `
                            <div class="text-gray-800 p-2">
                                <h3 class="font-bold text-lg">${vehicle.vehicle_name}</h3>
                                <p class="text-sm"><strong>Patente:</strong> ${vehicle.plate_no}</p>
                                <p class="text-sm"><strong>Velocidad:</strong> ${vehicle.speed} km/h</p>
                                <p class="text-sm"><strong>Dirección:</strong> ${vehicle.direction}°</p>
                                <p class="text-sm"><strong>Última actualización:</strong> ${vehicle.occur_time}</p>
                                <p class="text-sm"><strong>Coordenadas:</strong> ${vehicle.latitude.toFixed(6)}, ${vehicle.longitude.toFixed(6)}</p>
                            </div>
                        `
                    });
                    
                    marker.addListener('click', () => {
                        infoWindow.open(map, marker);
                    });
                    
                    markers[vehicle.vehicle_code] = marker;
                }
            });
            
            // Ajustar zoom y centro si hay marcadores
            if (vehiclesData.length > 0 && vehiclesData[0].latitude && vehiclesData[0].longitude) {
                const bounds = new google.maps.LatLngBounds();
                vehiclesData.forEach(vehicle => {
                    if (vehicle.latitude && vehicle.longitude) {
                        bounds.extend(new google.maps.LatLng(
                            parseFloat(vehicle.latitude), 
                            parseFloat(vehicle.longitude)
                        ));
                    }
                });
                map.fitBounds(bounds);
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
                
                // Actualizar el texto en el header
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
            console.log('Iniciando auto-refresh cada 10 segundos');
            
            // Configurar intervalo de actualización (10 segundos)
            refreshInterval = setInterval(() => {
                loadMapData();
                resetCountdown();
            }, 10000);
            
            // Configurar contador regresivo
            resetCountdown();
        }
        
        // Reiniciar contador regresivo
        function resetCountdown() {
            countdownValue = 10;
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
        
        // Verificar si Google Maps está cargado y inicializar
        function checkGoogleMaps() {
            if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
                initMap();
            } else {
                // Reintentar después de un breve delay
                setTimeout(checkGoogleMaps, 100);
            }
        }
        
        // Iniciar cuando el documento esté listo
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM cargado, inicializando mapa...');
            checkGoogleMaps();
        });
        
        // Manejar visibilidad de la página para optimizar recursos
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                // Página oculta, pausar actualizaciones
                if (refreshInterval) clearInterval(refreshInterval);
                if (countdownInterval) clearInterval(countdownInterval);
                console.log('Actualizaciones pausadas (página oculta)');
            } else {
                // Página visible, reanudar actualizaciones
                startAutoRefresh();
                console.log('Actualizaciones reanudadas (página visible)');
            }
        });
    </script>
@endsection