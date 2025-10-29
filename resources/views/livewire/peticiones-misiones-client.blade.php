<div class="bg-gray-900 text-gray-100 p-6 rounded-lg shadow">
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
    <div class="bg-gray-800 rounded-lg p-6 mb-6 border border-gray-700">
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
                           class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @error('nombre') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Site *</label>
                    <select wire:model="site_id" 
                            class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
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
                            class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
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
                           class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @error('route_altitude') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Velocidad de vuelo (m/s) *</label>
                    <input type="number" step="0.01" wire:model="route_speed" 
                           class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @error('route_speed') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Tipo de Ruta -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Ruta *</label>
                <select wire:model="route_waypoint_type" 
                        class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <option value="linear_route">Ruta Lineal</option>
                    <option value="transits_waypoint">Vuela sobre waypoints</option>
                    <option value="curved_route_drone_stops">Ruta Curva con Paradas</option>
                    <option value="curved_route_drone_continues">Ruta Curva Contínua</option>
                </select>
                @error('route_waypoint_type') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Waypoints -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <label class="block text-sm font-medium text-gray-300">Waypoints *</label>
                    <button type="button" wire:click="agregarWaypoint" 
                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                        <i class="fas fa-plus mr-1"></i>Agregar Waypoint
                    </button>
                </div>

                @foreach($waypoints as $index => $waypoint)
                <div class="bg-gray-700 rounded-lg p-4 mb-4 border border-gray-600">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-medium text-gray-200">Waypoint {{ $index + 1 }}</h4>
                        @if(count($waypoints) > 1)
                        <button type="button" wire:click="eliminarWaypoint({{ $index }})" 
                                class="text-red-400 hover:text-red-300">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Latitud *</label>
                            <input type="number" step="0.00000001" wire:model="waypoints.{{ $index }}.latitud" 
                                   class="w-full bg-gray-600 border border-gray-500 rounded px-3 py-2 text-gray-100 text-sm">
                            @error("waypoints.{$index}.latitud") <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Longitud *</label>
                            <input type="number" step="0.00000001" wire:model="waypoints.{{ $index }}.longitud" 
                                   class="w-full bg-gray-600 border border-gray-500 rounded px-3 py-2 text-gray-100 text-sm">
                            @error("waypoints.{$index}.longitud") <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                
                    </div>

                    <!-- Acciones -->
                    <div>
                        <label class="block text-xs text-gray-400 mb-2">Acciones en este waypoint:</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
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
                            <label class="flex items-center space-x-2 text-xs cursor-pointer">
                                <input type="checkbox" 
                                       wire:model="waypoints.{{ $index }}.acciones"
                                       value="{{ $valor }}"
                                       class="rounded border-gray-600 bg-gray-700 text-blue-500 focus:ring-blue-500">
                                <span class="text-gray-300">{{ $texto }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Descripción y Observaciones -->
            <div class="grid grid-cols-1 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Describe la funcionalidad/proposito de la misión</label>
                    <textarea wire:model="descripcion" rows="3" 
                              class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                              placeholder="Describe el propósito de esta misión..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Agrega detalles adicionales</label>
                    <textarea wire:model="observaciones" rows="2" 
                              class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                              placeholder="Observaciones adicionales..."></textarea>
                </div>
            </div>

            <!-- Botones del Formulario -->
            <div class="flex justify-end space-x-3">
                <button type="button" wire:click="$set('showCreateForm', false)" 
                        class="px-4 py-2 border border-gray-600 rounded-md text-gray-300 hover:bg-gray-700 transition-colors">
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
    <div class="bg-gray-800 rounded-lg p-4 mb-6">
        <div class="flex items-center space-x-4">
            <label class="text-sm text-gray-300">Filtrar por estado:</label>
            <select wire:model="filtroEstado" 
                    class="bg-gray-700 border border-gray-600 rounded-md px-3 py-1 text-gray-100 text-sm">
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
            <thead class="bg-gray-800 text-gray-300">
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
                <tr class="border-b border-gray-700 hover:bg-gray-800 transition-colors">
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-100">{{ $peticion->nombre }}</div>
                        <div class="text-xs text-gray-400 truncate max-w-xs">{{ $peticion->descripcion ?: 'Sin descripción' }}</div>
                    </td>
                    <td class="px-4 py-3 text-gray-300">{{ $peticion->site->nombre ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-gray-300">{{ $peticion->drone->drone ?? 'N/A' }}</td>
                    <td class="px-4 py-3">
                        <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full">
                            {{ $peticion->obtenerCantidadWaypoints() }} puntos
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $estadoColors = [
                                'pendiente' => 'bg-yellow-100 text-yellow-800',
                                'aprobada' => 'bg-green-100 text-green-800',
                                'rechazada' => 'bg-red-100 text-red-800'
                            ];
                            $color = $estadoColors[$peticion->estado] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                            {{ ucfirst($peticion->estado) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-300 text-sm">
                        {{ $peticion->created_at->format('d/m/Y') }}
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
</div>

<!-- Modal de Ver Petición -->
@if($showViewModal && $selectedPeticion)
<div class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
    <div class="bg-gray-800 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
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
                    <div class="bg-gray-700 rounded p-3">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-medium text-gray-200">Waypoint {{ $index + 1 }}</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <label class="text-gray-400">Latitud:</label>
                                <p class="text-gray-100">{{ $waypoint['latitud'] }}</p>
                            </div>
                            <div>
                                <label class="text-gray-400">Longitud:</label>
                                <p class="text-gray-100">{{ $waypoint['longitud'] }}</p>
                            </div>
                            <div>
                                <label class="text-gray-400">Altitud:</label>
                                <p class="text-gray-100">{{ $waypoint['altitud'] }} m</p>
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
            <div class="border-t border-gray-700 pt-4">
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
