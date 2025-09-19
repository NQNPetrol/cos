<x-app-layout>
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
            <!-- Header -->
            <div class="bg-slate-900 overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <div class="p-6 text-gray-100">
                    <!-- Header -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-3xl font-bold text-white mb-2">Vehículos Móviles</h2>
                                <p class="text-sm text-gray-300">Seguimiento y localización de patrullas en tiempo real</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contenedor del Mapa -->
                    <div class="bg-gray-800 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Mapa de Localización</h3>
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
                                            Estado
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-600">
                                    @forelse($mobileVehicles as $vehicle)
                                        <tr class="hover:bg-gray-750 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-white">{{ $vehicle->mobile_vehicle_index_code }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-blue-400 font-medium">{{ $vehicle->mobile_vehicle_name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-300">{{ $vehicle->region_index_code }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-medium rounded {{ $vehicle->status == 1 ? 'bg-green-900/30 text-green-300 border border-green-600/50' : 'bg-red-900/30 text-red-300 border border-red-600/50' }}">
                                                    {{ $vehicle->status == 1 ? 'ACTIVO' : 'INACTIVO' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-gray-400">
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
        
        // Inicializar el mapa
        function initMap() {
            // Centro inicial del mapa (puedes ajustar estas coordenadas)
            const initialCenter = { lat: -34.6037, lng: -58.3816 }; // Buenos Aires por defecto
            
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: initialCenter,
                styles: [
                    {
                        "elementType": "geometry",
                        "stylers": [{ "color": "#242f3e" }]
                    },
                    {
                        "elementType": "labels.text.fill",
                        "stylers": [{ "color": "#746855" }]
                    },
                    {
                        "elementType": "labels.text.stroke",
                        "stylers": [{ "color": "#242f3e" }]
                    }
                ]
            });
            
            // Cargar datos iniciales
            loadMapData();
            
            // Iniciar actualización automática
            startAutoRefresh();
        }
        
        // Cargar datos del mapa desde la API
        async function loadMapData() {
            try {
                const response = await fetch('/api/mobile-vehicles/map-data');
                const data = await response.json();
                
                if (data.success) {
                    updateMap(data.data);
                    updateTable(data.data);
                    updateLastUpdateTime();
                } else {
                    console.error('Error loading map data:', data.message);
                }
            } catch (error) {
                console.error('Error fetching map data:', error);
            }
        }
        
        // Actualizar el mapa con nuevos marcadores
        function updateMap(vehiclesData) {
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
                            <div class="text-gray-800">
                                <h3 class="font-bold">${vehicle.vehicle_name}</h3>
                                <p>Patente: ${vehicle.plate_no}</p>
                                <p>Velocidad: ${vehicle.speed} km/h</p>
                                <p>Última actualización: ${vehicle.occur_time}</p>
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
            vehiclesData.forEach(vehicle => {
                const locationElement = document.querySelector(`.location-data[data-vehicle-id="${vehicle.vehicle_code}"]`);
                if (locationElement) {
                    if (vehicle.latitude && vehicle.longitude) {
                        locationElement.innerHTML = `
                            ${vehicle.latitude.toFixed(6)}, ${vehicle.longitude.toFixed(6)}<br>
                            <small class="text-gray-400">${vehicle.occur_time}</small>
                        `;
                    } else {
                        locationElement.textContent = 'Sin datos de ubicación';
                    }
                }
            });
        }
        
        // Actualizar el tiempo de última actualización
        function updateLastUpdateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString();
            document.querySelector('#last-update span').textContent = timeString;
        }
        
        // Iniciar actualización automática
        function startAutoRefresh() {
            // Mostrar contador
            document.getElementById('refresh-counter').classList.remove('hidden');
            
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
            document.getElementById('countdown').textContent = countdownValue;
        }
        
        // Función para actualizar manualmente
        function refreshData() {
            loadMapData();
            resetCountdown();
        }
        
        // Cargar datos iniciales cuando la página esté lista
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof google !== 'undefined' && google.maps) {
                // Google Maps ya está cargado, inicializar
                initMap();
            } else {
                // Esperar a que Google Maps se cargue
                window.initMap = initMap;
            }
        });
    </script>
</x-app-layout>