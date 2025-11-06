<div class="bg-gray-900 text-gray-100 p-6 rounded-lg shadow">
    @if (session()->has('approval-error'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-transition
         x-init="setTimeout(() => show = false, 6000)"
         class="fixed top-4 right-4 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 max-w-md">
        <div class="flex items-start space-x-2">
            <i class="fas fa-exclamation-triangle mt-1"></i>
            <div>
                <p class="font-medium">Error al Aprobar Misión</p>
                <p class="text-sm mt-1">{{ session('approval-error') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if (session()->has('approval-success'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-transition
         x-init="setTimeout(() => show = false, 5000)"
         class="fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <div class="flex items-center space-x-2">
            <i class="fas fa-check-circle"></i>
            <div>
                <p class="font-medium">Misión Aprobada</p>
                <p class="text-sm mt-1">{{ session('approval-success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if (session()->has('rejection-success'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-transition
         x-init="setTimeout(() => show = false, 5000)"
         class="fixed top-4 right-4 bg-yellow-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <div class="flex items-center space-x-2">
            <i class="fas fa-info-circle"></i>
            <div>
                <p class="font-medium">Misión Rechazada</p>
                <p class="text-sm mt-1">{{ session('rejection-success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Mensajes Flash Generales -->
    
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold">Peticiones de creación de misiones</h2>
            <p class="text-gray-400 text-sm mt-1">Vista de administrador/operador</p>
        </div>
    </div>

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
                    <th class="px-4 py-3 text-left">Cliente</th>
                    <th class="px-4 py-3 text-left">Usuario</th>
                    <th class="px-4 py-3 text-left">Site</th>
                    <th class="px-4 py-3 text-left">Drone</th>
                    <th class="px-4 py-3 text-left">Waypoints</th>
                    <th class="px-4 py-3 text-left">Acciones</th>
                    <th class="px-4 py-3 text-left">Estado</th>
                    <th class="px-4 py-3 text-left">Fecha</th>
                    <th class="px-4 py-3 text-left">Opciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peticiones as $peticion)
                <tr class="border-b border-gray-700 hover:bg-gray-800 transition-colors">
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-100">{{ $peticion->nombre }}</div>
                        <div class="text-xs text-gray-400 truncate max-w-xs">{{ $peticion->descripcion ?: 'Sin descripción' }}</div>
                    </td>
                    
                    <td class="px-4 py-3 text-gray-300">
                        {{ $peticion->cliente->nombre ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-3 text-gray-300">
                        {{ $peticion->usuario->name ?? 'N/A' }}
                    </td>
                    
                    <td class="px-4 py-3 text-gray-300">{{ $peticion->site->nombre ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-gray-300">{{ $peticion->drone->drone ?? 'N/A' }}</td>
                    
                    <td class="px-4 py-3 text-gray-300">
                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-blue-100 bg-blue-600 rounded-full">
                            {{ $this->getWaypointsCount($peticion) }}
                        </span>
                    </td>
                    
                    <td class="px-4 py-3 text-gray-300 text-sm">
                        <span class="max-w-xs truncate inline-block" title="{{ $this->getAccionesUnicas($peticion) }}">
                            {{ $this->getAccionesUnicas($peticion) }}
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
                        {{ $peticion->created_at->format('d/m/Y H:i') }}
                    </td>
                    
                    <td class="px-4 py-3">
                        <div class="flex space-x-2">
                            <button wire:click="verPeticion({{ $peticion->id }})" 
                                    class="text-blue-400 hover:text-blue-300 transition-colors p-1 rounded hover:bg-gray-700"
                                    title="Ver detalles">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-up-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5"/>
                                    <path fill-rule="evenodd" d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0z"/>
                                </svg>
                            </button>
                            
                            <button wire:click="eliminarPeticion({{ $peticion->id }})" 
                                    wire:confirm="¿Estás seguro de que quieres eliminar esta petición?"
                                    class="text-red-400 hover:text-red-300 transition-colors p-1 rounded hover:bg-gray-700"
                                    title="Eliminar petición">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-4 py-8 text-center text-gray-400">
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

    <!-- Modal de Ver Petición -->
    @if($showViewModal && $selectedPeticion)
    <div class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4"
         wire:click="closeModal">
        @if (session()->has('success') && !session()->has('approval-success') && !session()->has('rejection-success'))
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

        @if (session()->has('error') && !session()->has('approval-error'))
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
        <style>
            .modal-scroll::-webkit-scrollbar {
                width: 10px;
            }
            .modal-scroll::-webkit-scrollbar-track {
                background: #374151; /* gray-700 */
                border-radius: 6px;
                margin: 4px;
            }
            .modal-scroll::-webkit-scrollbar-thumb {
                background: #4B5563; /* gray-600 */
                border-radius: 6px;
                border: 2px solid #374151; /* mismo color que el track */
            }
            .modal-scroll::-webkit-scrollbar-thumb:hover {
                background: #6B7280; /* gray-500 */
            }

            /* Estilos para el scroll de waypoints */
            .waypoints-scroll::-webkit-scrollbar {
                width: 8px;
            }
            .waypoints-scroll::-webkit-scrollbar-track {
                background: #4B5563; /* gray-600 */
                border-radius: 4px;
            }
            .waypoints-scroll::-webkit-scrollbar-thumb {
                background: #6B7280; /* gray-500 */
                border-radius: 4px;
            }
            .waypoints-scroll::-webkit-scrollbar-thumb:hover {
                background: #9CA3AF; /* gray-400 */
            }
        </style>
        <div class="bg-gray-800 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto modal-scroll"
             wire:click.stop>
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-100">Detalles de la Petición</h3>
                    <button wire:click="closeModal" 
                            class="text-gray-400 hover:text-white p-2 rounded-full hover:bg-gray-700 transition-colors lg:hidden">
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
                                <label class="text-sm text-gray-400">Cliente:</label>
                                <p class="text-gray-100">{{ $selectedPeticion->cliente->nombre ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-400">Usuario solicitante:</label>
                                <p class="text-gray-100">{{ $selectedPeticion->usuario->name ?? 'N/A' }}</p>
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
                                <p class="text-gray-100">
                                    @php
                                        $tiposRuta = [
                                            'linear_route' => 'Ruta Lineal',
                                            'transits_waypoint' => 'Vuela sobre waypoints',
                                            'curved_route_drone_stops' => 'Ruta Curva con Paradas',
                                            'curved_route_drone_continues' => 'Ruta Curva Contínua'
                                        ];
                                    @endphp
                                    {{ $tiposRuta[$selectedPeticion->route_waypoint_type] ?? $selectedPeticion->route_waypoint_type }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-400">Waypoints:</label>
                                <p class="text-gray-100">{{ $this->getWaypointsCount($selectedPeticion) }} puntos</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-400">Acciones únicas:</label>
                                <p class="text-gray-100">{{ $this->getAccionesUnicas($selectedPeticion) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Waypoints -->
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-200 mb-4">Waypoints ({{ $this->getWaypointsCount($selectedPeticion) }})</h4>
                    <div class="space-y-3 max-h-60 overflow-y-auto">
                        @php
                            $waypointsData = is_array($selectedPeticion->waypoints) ? $selectedPeticion->waypoints : json_decode($selectedPeticion->waypoints, true);
                        @endphp
                        
                        @if(!empty($waypointsData) && is_array($waypointsData))
                            @foreach($waypointsData as $index => $waypoint)
                            <div class="bg-gray-700 rounded p-3">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-medium text-gray-200">Waypoint {{ $index + 1 }}</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <label class="text-gray-400">Latitud:</label>
                                        <p class="text-gray-100">{{ $waypoint['latitud'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-gray-400">Longitud:</label>
                                        <p class="text-gray-100">{{ $waypoint['longitud'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                @if(!empty($waypoint['acciones']) && is_array($waypoint['acciones']))
                                <div class="mt-2">
                                    <label class="text-gray-400 text-sm">Acciones:</label>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach($waypoint['acciones'] as $accion)
                                        <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded">
                                            {{ $this->getActionLabel($accion) }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        @else
                            <div class="bg-gray-700 rounded p-4 text-center text-gray-400">
                                <i class="fas fa-map-marker-alt text-xl mb-2 block"></i>
                                No hay waypoints definidos
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Comentarios del revisor -->
                @if($selectedPeticion->estado === 'pendiente')
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-200 mb-4">Comentarios de Revisión</h4>
                    <textarea wire:model="comentariosRevisor" 
                              rows="3"
                              placeholder="Agregar comentarios sobre la revisión de esta petición..."
                              class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></textarea>
                </div>
                @endif

                <!-- Información de Revisión -->
                @if($selectedPeticion->revisado_por)
                <div class="border-t border-gray-700 pt-4">
                    <h4 class="text-lg font-medium text-gray-200 mb-4">Información de Revisión</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-400">Comentarios:</label>
                            <p class="text-gray-100">{{ $selectedPeticion->comentarios_revisor ?: 'Sin comentarios' }}</p>
                        </div>
                        @if($selectedPeticion->mision_aprobada_id)
                        <div>
                            <label class="text-sm text-gray-400">Misión creada:</label>
                            <p class="text-gray-100">ID #{{ $selectedPeticion->mision_aprobada_id }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Botones de acción -->
                @if($selectedPeticion->estado === 'pendiente')
                <div class="border-t border-gray-700 pt-6 mt-6">
                    <div class="flex justify-end space-x-3">
                        <button wire:click="rechazarMision" 
                                wire:confirm="¿Estás seguro de que quieres rechazar esta petición?"
                                class="px-4 py-2 border border-red-600 text-red-400 rounded-md hover:bg-red-600 hover:text-white transition-colors">
                            <i class="fas fa-times mr-2"></i>Rechazar Petición
                        </button>
                        <button wire:click="aprobarMision" 
                                wire:confirm="¿Estás seguro de que quieres aprobar esta petición? Se creará una nueva misión."
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                            <i class="fas fa-check mr-2"></i>Aprobar Misión
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

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