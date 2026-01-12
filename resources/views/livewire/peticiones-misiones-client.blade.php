<div class="bg-zinc-900 text-gray-100 p-6 rounded-lg shadow">
    @if (session()->has('success'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-transition
         x-init="setTimeout(() => show = false, 5000)"
         class="fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <div class="flex items-center space-x-2">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-transition
         x-init="setTimeout(() => show = false, 5000)"
         class="fixed top-4 right-4 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <div class="flex items-center space-x-2">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    @if (session()->has('info'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-transition
         x-init="setTimeout(() => show = false, 3000)"
         class="fixed top-4 right-4 bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <div class="flex items-center space-x-2">
            <i class="fas fa-info-circle"></i>
            <span>{{ session('info') }}</span>
        </div>
    </div>
    @endif

    <!-- Mensajes de acción en waypoints -->
    @if (session()->has('action-added'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-transition
         x-init="setTimeout(() => show = false, 3000)"
         class="mb-4 bg-green-600 text-white px-4 py-2 rounded-lg">
        <div class="flex items-center space-x-2">
            <i class="fas fa-check-circle"></i>
            <span class="text-sm">{{ session('action-added') }}</span>
        </div>
    </div>
    @endif
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold">Misiones</h2>
            <p class="text-gray-400 text-sm mt-1">Gestiona tus solicitudes de misiones para drones</p>
        </div>
        
        @if(!$showCreateForm)
        <button wire:click="$set('showCreateForm', true)" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors">
            <i class="fas fa-plus mr-2"></i>Nueva Mision
        </button>
        @endif
    </div>

    <!-- Formulario de Creación -->
    @if($showCreateForm)
    <div class="bg-zinc-800 rounded-lg p-6 mb-6 border border-zinc-700">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Crea una nueva misión</h3>
            <button wire:click="$set('showCreateForm', false)" 
                    class="text-gray-400 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form wire:submit.prevent="crearPeticion">
            <!-- Información Básica -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nombre de la Misión *</label>
                    <input type="text" wire:model="nombre" 
                           class="w-full bg-zinc-700 border border-zinc-600 rounded-md px-3 py-2 text-gray-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @error('nombre') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Site *</label>
                    <select wire:model="site_id" 
                            class="w-full bg-zinc-700 border border-zinc-600 rounded-md px-3 py-2 text-gray-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">Seleccionar Site</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}">{{ $site->nombre }}</option>
                        @endforeach
                    </select>
                    @error('site_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Drone y Configuración de Vuelo -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Drone *</label>
                    <select wire:model="drone_id" 
                            class="w-full bg-zinc-700 border border-zinc-600 rounded-md px-3 py-2 text-gray-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">Seleccionar Drone</option>
                        @foreach($drones as $drone)
                            <option value="{{ $drone->id }}">{{ $drone->drone }}</option>
                        @endforeach
                    </select>
                    @error('drone_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Altitud de vuelo (metros) *</label>
                    <input type="number" step="0.1" wire:model="route_altitude" 
                           class="w-full bg-zinc-700 border border-zinc-600 rounded-md px-3 py-2 text-gray-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @error('route_altitude') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Velocidad de vuelo (m/s) *</label>
                    <input type="number" step="0.01" wire:model="route_speed" 
                           class="w-full bg-zinc-700 border border-zinc-600 rounded-md px-3 py-2 text-gray-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @error('route_speed') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Tipo de Ruta -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Ruta *</label>
                <select wire:model="route_waypoint_type" 
                        class="w-full bg-zinc-700 border border-zinc-600 rounded-md px-3 py-2 text-gray-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <option value="linear_route">Ruta Lineal - El drone sigue una línea recta entre waypoints</option>
                    <option value="transits_waypoint">Vuela sobre waypoints - El drone pasa por los waypoints sin detenerse</option>
                    <option value="curved_route_drone_stops">Ruta Curva con Paradas - Trayectoria curva, se detiene en cada waypoint</option>
                    <option value="curved_route_drone_continues">Ruta Curva Contínua - Trayectoria curva sin detenciones</option>
                </select>
                @error('route_waypoint_type') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Waypoints con Carrusel -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <label class="block text-sm font-medium text-gray-300">Waypoints *</label>
                    <div class="flex space-x-2">
                        <!-- Tabs para elegir entre Manual y KMZ -->
                        <div class="flex space-x-1 bg-zinc-700 rounded-lg p-1">
                            <button type="button" 
                                    wire:click="switchWaypointMode('manual')"
                                    class="px-3 py-1 rounded text-sm transition-colors {{ $waypointInputMode === 'manual' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:text-white' }}">
                                Manual
                            </button>
                            <button type="button" 
                                    wire:click="switchWaypointMode('kmz')"
                                    class="px-3 py-1 rounded text-sm transition-colors {{ $waypointInputMode === 'kmz' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:text-white' }}">
                                Importar KMZ
                            </button>
                        </div>
                        @if($waypointInputMode === 'manual')
                        <button type="button" wire:click="agregarWaypoint" 
                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                            <i class="fas fa-plus mr-1"></i>Agregar Waypoint
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Contenedor para importar KMZ -->
                @if($waypointInputMode === 'kmz')
                <div class="bg-zinc-700 rounded-lg p-4 mb-4 border border-zinc-600">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Subir archivo KMZ</label>
                    <input type="file" 
                           id="kmzFileInput"
                           accept=".kmz"
                           wire:ignore.self
                           class="w-full rounded-md bg-zinc-600 border-zinc-500 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                    @if($kmz_file_path)
                    <p class="text-xs text-green-400 mt-1">
                        <i class="fas fa-check-circle mr-1"></i>
                        Archivo cargado: {{ basename($kmz_file_path) }}
                    </p>
                    @endif
                    <p class="text-xs text-gray-400 mt-1">Seleccione un archivo .kmz para importar los waypoints de la misión.</p>
                    <p class="text-xs text-yellow-400 mt-1 font-medium">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Al importar un archivo KMZ, se reemplazarán todos los waypoints manuales existentes.
                    </p>
                    
                    <!-- Mensaje de carga -->
                    <div id="kmzLoading" class="hidden mt-3 p-3 bg-blue-900/30 border border-blue-700 rounded-lg">
                        <div class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-sm text-blue-300">Procesando archivo KMZ...</span>
                        </div>
                    </div>
                    
                    <!-- Mensaje de error -->
                    <div id="kmzError" class="hidden mt-3 p-3 bg-red-900/30 border border-red-700 rounded-lg">
                        <p class="text-sm text-red-300"></p>
                    </div>
                </div>
                @endif

                @if(count($waypoints) > 0)
                <!-- Navegación del carrusel -->
                <div class="flex justify-between items-center mb-4">
                    
                    <div class="flex space-x-2">
                        <button type="button" 
                                wire:click="previousWaypoint"
                                @if($currentWaypointIndex === 0) disabled @endif
                                class="px-3 py-1 bg-zinc-700 border border-zinc-600 rounded text-sm text-gray-300 hover:bg-zinc-600 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                            </svg>
                        </button>
                        <button type="button" 
                                wire:click="nextWaypoint"
                                @if($currentWaypointIndex === count($waypoints) - 1) disabled @endif
                                class="px-3 py-1 bg-zinc-700 border border-zinc-600 rounded text-sm text-gray-300 hover:bg-zinc-600 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Waypoint actual -->
                @foreach($waypoints as $index => $waypoint)
                @if($index === $currentWaypointIndex)
                <div class="bg-zinc-700 rounded-lg p-4 mb-4 border border-zinc-600">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-medium text-gray-200">Waypoint {{ $index + 1 }}</h4>
                        <button type="button" 
                                wire:click="eliminarWaypoint({{ $index }})" 
                                class="text-red-400 hover:text-red-300 transition-colors"
                                title="Eliminar waypoint">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Latitud *</label>
                            <input type="number" step="0.00000001" 
                                   wire:model="waypoints.{{ $index }}.latitud" 
                                   class="w-full bg-zinc-600 border border-zinc-500 rounded px-3 py-2 text-gray-100 text-sm">
                            @error("waypoints.{$index}.latitud") <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Longitud *</label>
                            <input type="number" step="0.00000001" 
                                   wire:model="waypoints.{{ $index }}.longitud" 
                                   class="w-full bg-zinc-600 border border-zinc-500 rounded px-3 py-2 text-gray-100 text-sm">
                            @error("waypoints.{$index}.longitud") <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Acciones del waypoint -->
                    <div class="mt-4">
                        <div class="flex justify-between items-center mb-3">
                            <label class="block text-sm font-medium text-gray-300">Acciones</label>
                            <button type="button" 
                                    wire:click="abrirModalAcciones"
                                    wire:key="add-action-{{ $index }}"
                                    class="text-blue-400 hover:text-blue-300 transition-colors"
                                    title="Agregar acción">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Acciones agregadas -->
                        @if(!empty($waypoint['acciones']))
                        <div class="space-y-2">
                            @foreach($waypoint['acciones'] as $accionIndex => $accion)
                            <div class="flex justify-between items-center bg-zinc-600 rounded px-3 py-2">
                                <span class="text-xs text-gray-200">
                                    {{ $this->getActionLabel($accion) }}
                                </span>
                                <button type="button" 
                                        wire:click="eliminarAccion({{ $index }}, '{{ $accion }}')"
                                        class="text-red-400 hover:text-red-300 text-xs">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-xs text-gray-500 italic">No hay acciones agregadas</p>
                        @endif
                    </div>
                </div>
                @endif
                @endforeach
                @else
                <div class="bg-zinc-700 rounded-lg p-4 text-center text-gray-400">
                    <i class="fas fa-map-marker-alt text-2xl mb-2 block"></i>
                    No hay waypoints agregados
                </div>
                @endif
            </div>

            <!-- Descripción y Observaciones -->
            <div class="grid grid-cols-1 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Describe la funcionalidad/proposito de la misión</label>
                    <textarea wire:model="descripcion" rows="3" 
                              class="w-full bg-zinc-700 border border-zinc-600 rounded-md px-3 py-2 text-gray-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                              placeholder="Describe el propósito de esta misión..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Agrega detalles adicionales</label>
                    <textarea wire:model="observaciones" rows="2" 
                              class="w-full bg-zinc-700 border border-zinc-600 rounded-md px-3 py-2 text-gray-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                              placeholder="Observaciones adicionales..."></textarea>
                </div>
            </div>

            <!-- Botones del Formulario -->
            <div class="flex justify-end space-x-3">
                <button type="button" wire:click="$set('showCreateForm', false)" 
                        class="px-4 py-2 border border-zinc-600 rounded-md text-gray-300 hover:bg-zinc-700 transition-colors">
                    Cancelar
                </button>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors">
                    <i class="fas fa-paper-plane mr-2"></i>Guardar
                </button>
            </div>
        </form>
    </div>
    @endif

    <!-- Filtros -->
    <div class="bg-zinc-800 rounded-lg p-4 mb-6">
        <div class="flex items-center space-x-4">
            <label class="text-sm text-gray-300">Filtrar por estado:</label>
            <select wire:model="filtroEstado" 
                    class="bg-zinc-700 border border-zinc-600 rounded-md px-3 py-1 text-gray-100 text-sm">
                <option value="">Todos</option>
                <option value="pendiente">Pendientes</option>
                <option value="aprobada">Aprobadas</option>
                <option value="rechazada">Rechazadas</option>
            </select>
        </div>
    </div>

    <!-- Tabla de Peticiones -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-zinc-800 text-gray-300">
                <tr>
                    <th class="px-4 py-3 text-left">Nombre</th>
                    <th class="px-4 py-3 text-left">Site</th>
                    <th class="px-4 py-3 text-left">Drone</th>
                    <th class="px-4 py-3 text-left">Waypoints</th>
                    <th class="px-4 py-3 text-left">Estado</th>
                    <th class="px-4 py-3 text-left">Fecha</th>
                    <th class="px-4 py-3 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peticiones as $peticion)
                <tr class="border-b border-zinc-700 hover:bg-zinc-800 transition-colors">
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-100">{{ $peticion->nombre }}</div>
                        <div class="text-xs text-gray-400 truncate max-w-xs">{{ $peticion->descripcion ?: 'Sin descripción' }}</div>
                    </td>
                    <td class="px-4 py-3 text-gray-300">{{ $peticion->site->nombre ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-gray-300">{{ $peticion->drone->drone ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-gray-300">
                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-blue-100 bg-blue-600 rounded-full">
                            {{ $this->getWaypointsCount($peticion) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $estadoColors = [
                                'pendiente' => 'bg-yellow-100 text-yellow-800',
                                'aprobada' => 'bg-green-100 text-green-800',
                                'rechazada' => 'bg-red-100 text-red-800'
                            ];
                            $color = $estadoColors[$peticion->estado] ?? 'bg-zinc-100 text-gray-800';
                        @endphp
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                            {{ ucfirst($peticion->estado) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-300 text-sm">
                        {{ $peticion->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3 text-gray-300 text-sm">
                        <span class="max-w-xs truncate inline-block" title="{{ $this->getAccionesUnicas($peticion) }}">
                            {{ $this->getAccionesUnicas($peticion) }}
                        </span>
                    </td>
            
                    <td class="px-4 py-3">
                        <div class="flex space-x-2">
                            <button wire:click="verPeticion({{ $peticion->id }})" 
                                    class="text-blue-400 hover:text-blue-300 transition-colors"
                                    title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if($peticion->estaPendiente())
                            <button wire:click="anularPeticion({{ $peticion->id }})" 
                                    wire:confirm="¿Estás seguro de que quieres anular esta petición?"
                                    class="text-red-400 hover:text-red-300 transition-colors"
                                    title="Anular petición">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                        <i class="fas fa-inbox text-3xl mb-2 block"></i>
                        No hay peticiones de misión
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $peticiones->links() }}
    </div>

    <!-- Modal de Agregar Acción -->
    @if($showActionModal)
    <div class="fixed inset-0 z-[99999] flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.75);">
        <div class="bg-zinc-800 rounded-lg max-w-md w-full mx-auto shadow-2xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-100">Agregar Acción</h3>
                    <button wire:click="cerrarModalAcciones" 
                            type="button"
                            class="text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="space-y-3">
                    @php
                        $accionesDisponibles = [
                            'take_thermal_image' => 'Capturar Imagen Térmica',
                            'take_wide_image' => 'Capturar Imagen Angular',
                            'take_panorama_image' => 'Capturar Imagen Panoramica',
                            'start_recording' => 'Iniciar Grabación',
                            'stop_recording' => 'Detener Grabación',
                            'zoom_in' => 'Activar Zoom',
                            'set_gimbal_90' => 'Rotar Camara a 90°',
                            'set_gimbal_45' => 'Rotar Camara 45°',
                        ];
                    @endphp

                    @foreach($accionesDisponibles as $valor => $texto)
                    <button type="button"
                            wire:click="agregarAccion('{{ $valor }}')"
                            class="w-full text-left bg-zinc-700 hover:bg-zinc-600 rounded-lg p-4 transition-colors border border-zinc-600 hover:border-zinc-500">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-200 font-medium">{{ $texto }}</span>
                            <i class="fas fa-plus text-blue-400"></i>
                        </div>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal de confirmación para importar KMZ -->
    <div id="kmzWarningModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-zinc-800 rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-100">Confirmar Importación KMZ</h3>
                <button onclick="closeKmzWarningModal()" class="text-gray-400 hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="mb-4">
                <div class="bg-yellow-900/30 border border-yellow-700 rounded-lg p-4 mb-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-yellow-300 mb-2">Advertencia</p>
                            <p class="text-sm text-yellow-200">
                                Tienes <span id="warningWaypointsCount" class="font-bold"></span> waypoint(s) creado(s) manualmente.
                            </p>
                            <p class="text-sm text-yellow-200 mt-2">
                                Al importar el archivo KMZ, <strong>todos los waypoints manuales serán reemplazados</strong> y se perderán permanentemente.
                            </p>
                        </div>
                    </div>
                </div>
                <p class="text-sm text-gray-300">¿Deseas continuar con la importación?</p>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button onclick="closeKmzWarningModal()" 
                        class="px-4 py-2 bg-zinc-700 text-gray-300 rounded-md hover:bg-zinc-600 transition-colors">
                    Cancelar
                </button>
                <button onclick="confirmKmzImport()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Confirmar Importación
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Fin del componente Livewire -->

<!-- Modal de Ver Petición -->
@if($showViewModal && $selectedPeticion)
<div class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
    <div class="bg-zinc-800 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-100">Detalles de la Petición</h3>
                <button wire:click="$set('showViewModal', false)" 
                        class="text-gray-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Información de la petición -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h4 class="text-lg font-medium text-gray-200 mb-4">Información General</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-400">Nombre:</label>
                            <p class="text-gray-100">{{ $selectedPeticion->nombre }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Descripción:</label>
                            <p class="text-gray-100">{{ $selectedPeticion->descripcion ?: 'Sin descripción' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Site:</label>
                            <p class="text-gray-100">{{ $selectedPeticion->site->nombre ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Drone:</label>
                            <p class="text-gray-100">{{ $selectedPeticion->drone->drone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-lg font-medium text-gray-200 mb-4">Configuración de Vuelo</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-400">Altitud:</label>
                            <p class="text-gray-100">{{ $selectedPeticion->route_altitude }} m</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Velocidad:</label>
                            <p class="text-gray-100">{{ $selectedPeticion->route_speed }} m/s</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Tipo de Ruta:</label>
                            <p class="text-gray-100">{{ $selectedPeticion->obtenerTiposRutaDisponibles()[$selectedPeticion->route_waypoint_type] ?? $selectedPeticion->route_waypoint_type }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Waypoints:</label>
                            <p class="text-gray-100">{{ $selectedPeticion->obtenerCantidadWaypoints() }} puntos</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Waypoints -->
            <div class="mb-6">
                <h4 class="text-lg font-medium text-gray-200 mb-4">Waypoints</h4>
                <div class="space-y-3">
                    @foreach($selectedPeticion->waypoints as $index => $waypoint)
                    <div class="bg-zinc-700 rounded p-3">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-medium text-gray-200">Waypoint {{ $index + 1 }}</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <label class="text-gray-400">Latitud:</label>
                                <p class="text-gray-100">{{ $waypoint['latitud'] }}</p>
                            </div>
                            <div>
                                <label class="text-gray-400">Longitud:</label>
                                <p class="text-gray-100">{{ $waypoint['longitud'] }}</p>
                            </div>
                        </div>
                        @if(!empty($waypoint['acciones']))
                        <div class="mt-2">
                            <label class="text-gray-400 text-sm">Acciones:</label>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($waypoint['acciones'] as $accion)
                                <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded">
                                    {{ $selectedPeticion->obtenerAccionesDisponibles()[$accion] ?? $accion }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Información de Revisión -->
            @if($selectedPeticion->revisado_por)
            <div class="border-t border-zinc-700 pt-4">
                <h4 class="text-lg font-medium text-gray-200 mb-4">Información de Revisión</h4>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-400">Revisado por:</label>
                        <p class="text-gray-100">{{ $selectedPeticion->revisor->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-400">Fecha de revisión:</label>
                        <p class="text-gray-100">{{ $selectedPeticion->revisado_en?->format('d/m/Y H:i') ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-400">Comentarios:</label>
                        <p class="text-gray-100">{{ $selectedPeticion->comentarios_revisor ?: 'Sin comentarios' }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endif

<!-- Mensajes Flash -->
@if (session()->has('success'))
<div x-data="{ show: true }" 
     x-show="show" 
     x-transition
     x-init="setTimeout(() => show = false, 5000)"
     class="fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    <div class="flex items-center space-x-2">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif

@script
<script>
    let pendingKmzData = null;
    let pendingKmzFilePath = null;
    

    // Función para procesar archivo KMZ (debe estar disponible globalmente)
    window.processKmzFile = function(file) {
        console.log('[KMZ] Procesando archivo:', file.name, file.size, 'bytes');
        const loadingEl = document.getElementById('kmzLoading');
        const errorEl = document.getElementById('kmzError');
        
        // Mostrar loading
        if (loadingEl) loadingEl.classList.remove('hidden');
        if (errorEl) errorEl.classList.add('hidden');

        const formData = new FormData();
        formData.append('kmz_file', file);

        console.log('[KMZ] Enviando archivo al servidor...');
        fetch('{{ route("peticiones-misiones.process-kmz") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('[KMZ] Respuesta recibida:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('[KMZ] Datos recibidos:', data);
            if (loadingEl) loadingEl.classList.add('hidden');
            
            if (data.success && data.waypoints) {
                console.log('[KMZ] Waypoints extraídos:', data.waypoints.length);
                console.log('[KMZ] Primer waypoint:', data.waypoints[0]);
                console.log('[KMZ] Path del archivo:', data.kmz_file_path);
                
                // Guardar datos del KMZ procesado
                pendingKmzData = JSON.stringify(data.waypoints);
                pendingKmzFilePath = data.kmz_file_path || null;
                console.log('[KMZ] JSON generado, longitud:', pendingKmzData.length);
                
                // Llamar al método Livewire (sin confirmar aún)
                // Este método verificará si hay waypoints manuales y mostrará el modal
                console.log('[KMZ] Llamando a setWaypointsFromKmz...');
                @this.call('setWaypointsFromKmz', pendingKmzData, pendingKmzFilePath, false).then(() => {
                    console.log('[KMZ] setWaypointsFromKmz completado');
                });
            } else {
                console.error('[KMZ] Error en respuesta:', data);
                if (errorEl) {
                    errorEl.querySelector('p').textContent = data.message || 'Error al procesar el archivo KMZ';
                    errorEl.classList.remove('hidden');
                }
            }
        })
        .catch(error => {
            console.error('[KMZ] Error al procesar:', error);
            if (loadingEl) loadingEl.classList.add('hidden');
            if (errorEl) {
                errorEl.querySelector('p').textContent = 'Error al procesar el archivo: ' + error.message;
                errorEl.classList.remove('hidden');
            }
        });
    };

    // Función para inicializar el listener del input KMZ
    function initKmzInputListener() {
        console.log('[KMZ] Inicializando listener del input KMZ...');
        const kmzFileInput = document.getElementById('kmzFileInput');
        if (kmzFileInput) {
            console.log('[KMZ] Input encontrado, agregando listener');
            // Remover listener anterior si existe (clonar para limpiar listeners)
            const newInput = kmzFileInput.cloneNode(true);
            kmzFileInput.parentNode.replaceChild(newInput, kmzFileInput);
            
            // Agregar nuevo listener
            newInput.addEventListener('change', function(e) {
                console.log('[KMZ] Archivo seleccionado:', e.target.files[0]?.name);
                if (e.target.files.length > 0) {
                    window.processKmzFile(e.target.files[0]);
                }
            });
            console.log('[KMZ] Listener configurado correctamente');
        } else {
            console.log('[KMZ] Input no encontrado (puede que no esté en modo KMZ)');
        }
    }

    // Manejar cambio de archivo KMZ
    document.addEventListener('livewire:initialized', () => {
        console.log('[KMZ] Livewire inicializado, configurando listeners...');
        initKmzInputListener();
    });

    // Reinicializar cuando Livewire actualiza el DOM
    document.addEventListener('livewire:update', () => {
        console.log('[KMZ] Livewire actualizado, reinicializando listeners...');
        // Solo reinicializar si estamos en modo KMZ
        const currentMode = @this.get('waypointInputMode');
        if (currentMode === 'kmz') {
            console.log('[KMZ] Modo KMZ activo, preservando input del archivo');
            // Esperar un poco para que el DOM se actualice
            setTimeout(() => {
                initKmzInputListener();
            }, 100);
        }
    });

    // Manejar evento de advertencia desde Livewire
    Livewire.on('show-kmz-warning', (data) => {
        const modal = document.getElementById('kmzWarningModal');
        const countEl = document.getElementById('warningWaypointsCount');
        
        if (modal && countEl) {
            countEl.textContent = data.waypointsCount;
            pendingKmzData = data.waypointsJson;
            pendingKmzFilePath = data.kmzFilePath || null;
            modal.classList.remove('hidden');
        }
    });

    function closeKmzWarningModal() {
        const modal = document.getElementById('kmzWarningModal');
        if (modal) {
            modal.classList.add('hidden');
        }
        pendingKmzData = null;
        pendingKmzFilePath = null;
    }

    function confirmKmzImport() {
        console.log('[KMZ] Confirmando importación...');
        if (pendingKmzData) {
            console.log('[KMZ] Llamando a setWaypointsFromKmz con confirmación...');
            // Llamar al método Livewire con confirmación
            @this.call('setWaypointsFromKmz', pendingKmzData, pendingKmzFilePath, true).then(() => {
                console.log('[KMZ] Importación confirmada y completada');
                closeKmzWarningModal();
            });
        } else {
            console.error('[KMZ] No hay datos pendientes para importar');
        }
    }

    // Reiniciar input cuando cambia el modo
    Livewire.on('waypointModeChanged', () => {
        console.log('[KMZ] Modo de waypoint cambiado, reinicializando...');
        const kmzFileInput = document.getElementById('kmzFileInput');
        if (kmzFileInput) {
            // Solo limpiar el input si se cambia a modo manual
            // Si está en modo KMZ, preservar el archivo
            const currentMode = @this.get('waypointInputMode');
            if (currentMode === 'manual') {
                kmzFileInput.value = '';
            }
        }
        const loadingEl = document.getElementById('kmzLoading');
        const errorEl = document.getElementById('kmzError');
        if (loadingEl) loadingEl.classList.add('hidden');
        if (errorEl) errorEl.classList.add('hidden');
        
        // Reinicializar el listener cuando cambia el modo (con delay para que el DOM se actualice)
        setTimeout(() => {
            initKmzInputListener();
        }, 200);
    });
</script>
@endscript