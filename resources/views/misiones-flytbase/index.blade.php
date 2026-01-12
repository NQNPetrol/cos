<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-zinc-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-100">Gestión de Misiones Flytbase</h2>
                            <p class="text-gray-400 mt-1">Administra las misiones disponibles para los drones</p>
                            <!-- Boton de peticiones -->
                            <a href="{{ route('peticiones.index') }}" 
                                class="flex items-center text-blue-400 hover:text-blue-300">
                                Peticiones Clientes
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
                                    <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0M7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0"/>
                                </svg>
                            </a>
                        </div>
            
                        <div class="flex space-x-3">
                            <button onclick="openCreateModal()"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Nueva Misión
                            </button>
                        </div>
                    </div>

                    <!-- Mensajes -->
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-800 border border-green-600 text-green-100 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-800 border border-red-600 text-red-100 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Tabla de Misiones -->
                    <div class="overflow-x-auto rounded-lg border border-zinc-700">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-zinc-750">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">URL</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-zinc-800 divide-y divide-gray-700">
                                @forelse($misiones as $mision)
                                <tr class="hover:bg-zinc-750 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-100">{{ $mision->nombre }}</div>
                                        @if($mision->descripcion)
                                            <div class="text-sm text-gray-400 mt-1">{{ Str::limit($mision->descripcion, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $mision->cliente->nombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        <div class="truncate max-w-xs" title="{{ $mision->url }}">
                                            {{ $mision->url }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('misiones-flytbase.toggle-status', $mision) }}" method="POST" class="inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border transition-colors {{ $mision->activo ? 'bg-green-900/30 text-green-400 border-green-800 hover:bg-green-800/40' : 'bg-red-900/30 text-red-400 border-red-800 hover:bg-red-800/40' }}">
                                                {{ $mision->activo ? 'Activa' : 'Inactiva' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button onclick="openEditModal({{ $mision->id }}, '{{ addslashes($mision->nombre) }}', '{{ addslashes($mision->descripcion) }}', {{ $mision->cliente_id }}, '{{ $mision->url }}', {{ $mision->activo ? 'true' : 'false' }}, {{ $mision->drone_id ?? 'null' }}, {{ $mision->dock_id ?? 'null' }}, {{ $mision->site_id ?? 'null' }}, '{{ $mision->route_altitude }}', '{{ $mision->route_speed }}', '{{ $mision->route_waypoint_type }}', '{{ addslashes($mision->observaciones) }}', `{{ addslashes(json_encode($mision->waypoints, JSON_PRETTY_PRINT)) }}`, '{{ $mision->kmz_file_path ?? 'null' }}', {{ $mision->est_total_distance ?? 'null' }}, {{ $mision->est_total_duration ?? 'null' }}, {{ $mision->waypoints_count ?? 'null' }})"
                                                    class="text-blue-400 hover:text-blue-300 transition-colors p-1 rounded hover:bg-blue-900/30"
                                                    title="Editar misión">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </button>
                                            <form action="{{ route('misiones-flytbase.destroy', $mision) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-400 hover:text-red-300 transition-colors p-1 rounded hover:bg-red-900/30"
                                                        title="Eliminar misión"
                                                        onclick="return confirm('¿Está seguro de eliminar esta misión?')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-400">
                                        No se encontraron misiones registradas.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($misiones->hasPages())
                        <div class="mt-4">
                            {{ $misiones->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Crear Misión -->
    <div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-zinc-800 rounded-lg p-6 w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-100">Crear Nueva Misión</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form id="createForm" method="POST" action="{{ route('misiones-flytbase.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna 1: Información Básica -->
                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-gray-200 border-b border-zinc-700 pb-2">Información Básica</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Nombre *</label>
                            <input type="text" name="nombre" required 
                                   class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Descripción</label>
                            <textarea name="descripcion" rows="3"
                                      class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Cliente *</label>
                            <select name="cliente_id" required 
                                    class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Seleccione un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">URL del Webhook *</label>
                            <input type="url" name="url" required 
                                   class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                   placeholder="https://api.flytbase.com/rest/alarms/trigger">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Observaciones</label>
                            <textarea name="observaciones" rows="2"
                                      class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                      placeholder="Observaciones adicionales sobre la misión..."></textarea>
                        </div>
                    </div>

                    <!-- Columna 2: Configuración de Vuelo -->
                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-gray-200 border-b border-zinc-700 pb-2">Configuración de Vuelo</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Drone</label>
                            <select name="drone_id" 
                                    class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Seleccione un drone</option>
                                @foreach($drones as $drone)
                                    <option value="{{ $drone->id }}">{{ $drone->drone }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Dock</label>
                            <select name="dock_id" 
                                    class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Seleccione un dock</option>
                                @foreach($docks as $dock)
                                    <option value="{{ $dock->id }}">{{ $dock->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Site</label>
                            <select name="site_id" 
                                    class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Seleccione un site</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->id }}">{{ $site->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Altitud (m) *</label>
                                <input type="number" name="route_altitude" step="0.01" value="35.00" required 
                                       class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Velocidad (m/s) *</label>
                                <input type="number" name="route_speed" step="0.01" value="5.33" required 
                                       class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Ruta *</label>
                            <select name="route_waypoint_type" required 
                                    class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="linear_route">Ruta Lineal</option>
                                <option value="transits_waypoint">Vuela sobre waypoints</option>
                                <option value="curved_route_drone_stops">Ruta Curva con Paradas</option>
                                <option value="curved_route_drone_continues">Ruta Curva Contínua</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Distancia Total Estimada (m)</label>
                                <input type="number" name="est_total_distance" step="0.01" 
                                       class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                       placeholder="0.00">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Duración Total Estimada (seg)</label>
                                <input type="number" name="est_total_duration" 
                                       class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                       placeholder="0">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Waypoints</label>
                            
                            <!-- Input para archivo KMZ -->
                            <div id="createKmzUploadContainer">
                                <input type="file" name="kmz_file" id="createKmzFile" accept=".kmz"
                                       class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                                <p class="text-xs text-gray-400 mt-1">Seleccione un archivo .kmz para importar los waypoints de la misión. El archivo debe contener coordenadas válidas.</p>
                                
                                <!-- Mensaje de carga -->
                                <div id="createKmzLoading" class="hidden mt-3 p-3 bg-blue-900/30 border border-blue-700 rounded-lg">
                                    <div class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-sm text-blue-300">Procesando archivo KMZ...</span>
                                    </div>
                                </div>
                                
                                <!-- Mensaje de error -->
                                <div id="createKmzError" class="hidden mt-3 p-3 bg-red-900/30 border border-red-700 rounded-lg">
                                    <p class="text-sm text-red-300"></p>
                                </div>
                            </div>

                            <!-- Contenedor de waypoints procesados -->
                            <div id="createWaypointsContainer" class="hidden mt-4">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="text-sm font-medium text-gray-300">Waypoints Importados</h4>
                                    <span id="createWaypointsCount" class="text-xs text-gray-400">0 waypoints</span>
                                </div>
                                
                                <div id="createWaypointsList" class="space-y-3 max-h-96 overflow-y-auto">
                                    <!-- Los waypoints se agregarán aquí dinámicamente -->
                                </div>
                                
                                <!-- Campo hidden para enviar waypoints como JSON -->
                                <input type="hidden" name="waypoints" id="createWaypointsJson">
                                <!-- Campo hidden para enviar contador de waypoints -->
                                <input type="hidden" name="waypoints_count" id="createWaypointsCount">
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="activo" id="createActivo" value="1" checked
                                   class="rounded bg-zinc-700 border-zinc-600 text-blue-600 focus:ring-blue-500 focus:ring-offset-gray-800">
                            <label for="createActivo" class="ml-2 text-sm text-gray-300">Misión activa</label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-zinc-700">
                    <button type="button" onclick="closeCreateModal()" 
                            class="px-4 py-2 bg-zinc-600 text-white rounded-md hover:bg-zinc-700 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Crear Misión
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Editar Misión -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-zinc-800 rounded-lg p-6 w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-100">Editar Misión</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-gray-200 border-b border-zinc-700 pb-2">Información Básica</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Nombre *</label>
                            <input type="text" name="nombre" id="editNombre" required 
                                   class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Descripción</label>
                            <textarea name="descripcion" id="editDescripcion" rows="3"
                                      class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Cliente *</label>
                            <select name="cliente_id" id="editClienteId" required 
                                    class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Seleccione un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">URL del Webhook *</label>
                            <input type="url" name="url" id="editUrl" required 
                                   class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                   placeholder="https://api.flytbase.com/rest/alarms/trigger">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Observaciones</label>
                            <textarea name="observaciones" id="editObservaciones" rows="2"
                                      class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                      placeholder="Observaciones adicionales sobre la misión..."></textarea>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-gray-200 border-b border-zinc-700 pb-2">Configuración de Vuelo</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Drone</label>
                            <select name="drone_id" id="editDroneId" 
                                    class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Seleccione un drone</option>
                                @foreach($drones as $drone)
                                    <option value="{{ $drone->id }}">{{ $drone->drone }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Dock</label>
                            <select name="dock_id" id="editDockId" 
                                    class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Seleccione un dock</option>
                                @foreach($docks as $dock)
                                    <option value="{{ $dock->id }}">{{ $dock->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Site</label>
                            <select name="site_id" id="editSiteId" 
                                    class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Seleccione un site</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->id }}">{{ $site->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Altitud (m) *</label>
                                <input type="number" name="route_altitude" id="editRouteAltitude" step="0.01" required 
                                       class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Velocidad (m/s) *</label>
                                <input type="number" name="route_speed" id="editRouteSpeed" step="0.01" required 
                                       class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Ruta *</label>
                            <select name="route_waypoint_type" id="editRouteWaypointType" required 
                                    class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="linear_route">Ruta Lineal</option>
                                <option value="transits_waypoint">Vuela sobre waypoints</option>
                                <option value="curved_route_drone_stops">Ruta Curva con Paradas</option>
                                <option value="curved_route_drone_continues">Ruta Curva Contínua</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Distancia Total Estimada (m)</label>
                                <input type="number" name="est_total_distance" id="editEstTotalDistance" step="0.01" 
                                       class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                       placeholder="0.00">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Duración Total Estimada (seg)</label>
                                <input type="number" name="est_total_duration" id="editEstTotalDuration" 
                                       class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                       placeholder="0">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Waypoints</label>
                            
                            <!-- Input para archivo KMZ -->
                            <div id="editKmzUploadContainer">
                                <input type="file" name="kmz_file" id="editKmzFile" accept=".kmz"
                                       class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                                <p class="text-xs text-gray-400 mt-1">Seleccione un archivo .kmz para importar los waypoints de la misión. El archivo debe contener coordenadas válidas.</p>
                                
                                <!-- Mensaje de carga -->
                                <div id="editKmzLoading" class="hidden mt-3 p-3 bg-blue-900/30 border border-blue-700 rounded-lg">
                                    <div class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-sm text-blue-300">Procesando archivo KMZ...</span>
                                    </div>
                                </div>
                                
                                <!-- Mensaje de error -->
                                <div id="editKmzError" class="hidden mt-3 p-3 bg-red-900/30 border border-red-700 rounded-lg">
                                    <p class="text-sm text-red-300"></p>
                                </div>
                                
                                <!-- Info de archivo existente -->
                                <p id="editKmzFileInfo" class="text-xs text-green-400 mt-1 hidden"></p>
                            </div>

                            <!-- Contenedor de waypoints procesados -->
                            <div id="editWaypointsContainer" class="hidden mt-4">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="text-sm font-medium text-gray-300">Waypoints</h4>
                                    <span id="editWaypointsCount" class="text-xs text-gray-400">0 waypoints</span>
                                </div>
                                
                                <div id="editWaypointsList" class="space-y-3 max-h-96 overflow-y-auto">
                                    <!-- Los waypoints se agregarán aquí dinámicamente -->
                                </div>
                                
                                <!-- Campo hidden para enviar waypoints como JSON -->
                                <input type="hidden" name="waypoints" id="editWaypointsJson">
                                <!-- Campo hidden para enviar contador de waypoints -->
                                <input type="hidden" name="waypoints_count" id="editWaypointsCount">
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="activo" id="editActivo" value="1"
                                   class="rounded bg-zinc-700 border-zinc-600 text-blue-600 focus:ring-blue-500 focus:ring-offset-gray-800">
                            <label for="editActivo" class="ml-2 text-sm text-gray-300">Misión activa</label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-zinc-700">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 bg-zinc-600 text-white rounded-md hover:bg-zinc-700 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Actualizar Misión
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Acciones para Waypoints -->
    <div id="actionsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-zinc-800 rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-100">Agregar Acciones</h3>
                <button onclick="closeActionsModal()" class="text-gray-400 hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div id="actionsList" class="space-y-3 max-h-96 overflow-y-auto">
                <!-- Las acciones se agregarán aquí dinámicamente -->
            </div>
        </div>
    </div>

    <!-- Modal para configurar Yaw del Drone -->
    <div id="yawModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-zinc-800 rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-100">Configurar Yaw del Drone</h3>
                <button onclick="closeYawModal()" class="text-gray-400 hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Ángulo (-180° a 180°)</label>
                    <input type="number" id="yawAngle" min="-180" max="180" step="1" value="0"
                           class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <p class="text-xs text-gray-400 mt-1">Ingrese el ángulo de rotación del yaw del drone</p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button onclick="closeYawModal()" 
                            class="px-4 py-2 bg-zinc-700 text-gray-300 rounded-md hover:bg-zinc-600 transition-colors">
                        Cancelar
                    </button>
                    <button onclick="confirmYaw()" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Estilos para scrollbars - mismo color que el contenedor */
        .overflow-y-auto::-webkit-scrollbar,
        .overflow-x-auto::-webkit-scrollbar,
        .overflow-auto::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-track,
        .overflow-x-auto::-webkit-scrollbar-track,
        .overflow-auto::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb,
        .overflow-x-auto::-webkit-scrollbar-thumb,
        .overflow-auto::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.3);
            border-radius: 4px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb:hover,
        .overflow-x-auto::-webkit-scrollbar-thumb:hover,
        .overflow-auto::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.5);
        }
        
        /* Scrollbars para contenedores específicos */
        #actionsList::-webkit-scrollbar,
        #createWaypointsList::-webkit-scrollbar,
        #editWaypointsList::-webkit-scrollbar {
            width: 8px;
        }
        
        #actionsList::-webkit-scrollbar-track,
        #createWaypointsList::-webkit-scrollbar-track,
        #editWaypointsList::-webkit-scrollbar-track {
            background: transparent;
        }
        
        #actionsList::-webkit-scrollbar-thumb,
        #createWaypointsList::-webkit-scrollbar-thumb,
        #editWaypointsList::-webkit-scrollbar-thumb {
            background: rgba(55, 65, 81, 0.6);
            border-radius: 4px;
        }
        
        #actionsList::-webkit-scrollbar-thumb:hover,
        #createWaypointsList::-webkit-scrollbar-thumb:hover,
        #editWaypointsList::-webkit-scrollbar-thumb:hover {
            background: rgba(55, 65, 81, 0.8);
        }
        
        /* Scrollbars para modales y otros contenedores */
        .bg-zinc-800::-webkit-scrollbar,
        .bg-zinc-900::-webkit-scrollbar,
        .bg-zinc-700::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        .bg-zinc-800::-webkit-scrollbar-track,
        .bg-zinc-900::-webkit-scrollbar-track,
        .bg-zinc-700::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .bg-zinc-800::-webkit-scrollbar-thumb,
        .bg-zinc-900::-webkit-scrollbar-thumb,
        .bg-zinc-700::-webkit-scrollbar-thumb {
            background: rgba(55, 65, 81, 0.6);
            border-radius: 4px;
        }
        
        .bg-zinc-800::-webkit-scrollbar-thumb:hover,
        .bg-zinc-900::-webkit-scrollbar-thumb:hover,
        .bg-zinc-700::-webkit-scrollbar-thumb:hover {
            background: rgba(55, 65, 81, 0.8);
        }
        
        /* Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(55, 65, 81, 0.6) transparent;
        }
        
        #actionsList,
        #createWaypointsList,
        #editWaypointsList {
            scrollbar-width: thin;
            scrollbar-color: rgba(55, 65, 81, 0.6) transparent;
        }
    </style>

    <script>
        // Variables globales para manejar waypoints
        let createWaypoints = [];
        let editWaypoints = [];
        let currentWaypointIndex = null;
        let currentModal = null; // 'create' o 'edit'

        // Acciones disponibles
        const accionesDisponibles = {
            'take_thermal_image': 'Capturar Imagen Térmica',
            'take_wide_image': 'Capturar Imagen Angular',
            'take_panorama_image': 'Capturar Imagen Panoramica',
            'start_recording': 'Iniciar Grabación',
            'stop_recording': 'Detener Grabación',
            'zoom_in': 'Activar Zoom',
            'set_gimbal_90': 'Rotar Camara a 90°',
            'set_gimbal_45': 'Rotar Camara 45°',
            'drone_yaw': 'Rotar Yaw del Drone'
        };

        // Funciones para el modal de crear
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
            document.getElementById('createForm').reset();
            document.getElementById('createActivo').checked = true;
            createWaypoints = [];
            document.getElementById('createWaypointsContainer').classList.add('hidden');
            document.getElementById('createWaypointsList').innerHTML = '';
            document.getElementById('createWaypointsJson').value = '';
            const countField = document.getElementById('createWaypointsCount');
            if (countField) {
                countField.value = '';
            }
            document.getElementById('createKmzLoading').classList.add('hidden');
            document.getElementById('createKmzError').classList.add('hidden');
            const kmzFileInput = document.getElementById('createKmzFile');
            if (kmzFileInput) {
                kmzFileInput.value = '';
            }
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        // Funciones para el modal de editar
        function openEditModal(id, nombre, descripcion, clienteId, url, activo, droneId = null, dockId = null, siteId = null, routeAltitude = '35.00', routeSpeed = '5.33', routeWaypointType = 'linear_route', observaciones = '', waypoints = '', kmzFilePath = null, estTotalDistance = null, estTotalDuration = null, waypointsCount = null) {
            console.log('Abriendo modal de edición con datos:', { 
                id, nombre, descripcion, clienteId, url, activo, 
                droneId, dockId, siteId, routeAltitude, routeSpeed, 
                routeWaypointType, observaciones, waypoints, kmzFilePath,
                estTotalDistance, estTotalDuration, waypointsCount
            });
            
            document.getElementById('editModal').classList.remove('hidden');
            
            // Actualizar el formulario
            document.getElementById('editForm').action = `/misiones-flytbase/${id}`;
            document.getElementById('editNombre').value = nombre || '';
            document.getElementById('editDescripcion').value = descripcion || '';
            document.getElementById('editClienteId').value = clienteId || '';
            document.getElementById('editUrl').value = url || '';
            document.getElementById('editActivo').checked = activo === true || activo === 'true';
            document.getElementById('editDroneId').value = droneId && droneId !== 'null' ? droneId : '';
            document.getElementById('editDockId').value = dockId && dockId !== 'null' ? dockId : '';
            document.getElementById('editSiteId').value = siteId && siteId !== 'null' ? siteId : '';
            document.getElementById('editRouteAltitude').value = routeAltitude || '35.00';
            document.getElementById('editRouteSpeed').value = routeSpeed || '5.33';
            document.getElementById('editRouteWaypointType').value = routeWaypointType || 'linear_route';
            document.getElementById('editObservaciones').value = observaciones || '';
            document.getElementById('editEstTotalDistance').value = estTotalDistance && estTotalDistance !== 'null' ? estTotalDistance : '';
            document.getElementById('editEstTotalDuration').value = estTotalDuration && estTotalDuration !== 'null' ? estTotalDuration : '';

            // Manejar waypoints existentes
            editWaypoints = [];
            if (waypoints && waypoints !== 'null' && waypoints !== '""') {
                try {
                    let parsedWaypoints;
                    if (typeof waypoints === 'string') {
                        parsedWaypoints = JSON.parse(waypoints);
                    } else {
                        parsedWaypoints = waypoints;
                    }
                    
                    if (Array.isArray(parsedWaypoints) && parsedWaypoints.length > 0) {
                        editWaypoints = parsedWaypoints;
                        displayWaypoints('edit', editWaypoints);
                        // Actualizar contador de waypoints si no viene en el parámetro
                        if (!waypointsCount || waypointsCount === 'null') {
                            const countField = document.getElementById('editWaypointsCount');
                            if (countField) {
                                countField.value = parsedWaypoints.length;
                            }
                        }
                    } else {
                        document.getElementById('editWaypointsContainer').classList.add('hidden');
                    }
                } catch (e) {
                    console.error('Error al parsear waypoints:', e);
                    document.getElementById('editWaypointsContainer').classList.add('hidden');
                }
            } else {
                document.getElementById('editWaypointsContainer').classList.add('hidden');
            }
            
            // Cargar waypoints_count si existe
            const countField = document.getElementById('editWaypointsCount');
            if (countField && waypointsCount && waypointsCount !== 'null') {
                countField.value = waypointsCount;
            } else if (countField && editWaypoints.length > 0) {
                // Si no viene waypointsCount pero hay waypoints, calcularlo
                countField.value = editWaypoints.length;
            } else if (countField) {
                countField.value = '';
            }
            
            // Mostrar info de archivo KMZ si existe
            if (kmzFilePath && kmzFilePath !== 'null' && kmzFilePath !== '""') {
                document.getElementById('editKmzFileInfo').textContent = 'Archivo KMZ actual: ' + kmzFilePath.split('/').pop();
                document.getElementById('editKmzFileInfo').classList.remove('hidden');
            } else {
                document.getElementById('editKmzFileInfo').classList.add('hidden');
            }
            
            console.log('Modal configurado correctamente');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            editWaypoints = [];
            document.getElementById('editWaypointsContainer').classList.add('hidden');
            document.getElementById('editWaypointsList').innerHTML = '';
            document.getElementById('editWaypointsJson').value = '';
            const countField = document.getElementById('editWaypointsCount');
            if (countField) {
                countField.value = '';
            }
            document.getElementById('editKmzLoading').classList.add('hidden');
            document.getElementById('editKmzError').classList.add('hidden');
            const kmzFileInput = document.getElementById('editKmzFile');
            if (kmzFileInput) {
                kmzFileInput.value = '';
            }
        }

        // Cerrar modales al hacer click fuera
        document.addEventListener('click', function(event) {
            const createModal = document.getElementById('createModal');
            const editModal = document.getElementById('editModal');
            const actionsModal = document.getElementById('actionsModal');
            
            if (event.target === createModal) {
                closeCreateModal();
            }
            if (event.target === editModal) {
                closeEditModal();
            }
            if (event.target === actionsModal) {
                closeActionsModal();
            }
            const yawModal = document.getElementById('yawModal');
            if (event.target === yawModal) {
                closeYawModal();
            }
        });

        // Manejar envío de formularios
        document.getElementById('createForm').addEventListener('submit', function(e) {
            e.preventDefault();
            submitForm(this);
        });

        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            submitForm(this);
        });

        function submitForm(form) {
            // Asegurar que los waypoints estén en el formulario
            const isCreate = form.id === 'createForm';
            const modal = isCreate ? 'create' : 'edit';
            updateWaypointsJson(modal);
            
            // Validar que haya waypoints
            const waypoints = isCreate ? createWaypoints : editWaypoints;
            if (!waypoints || waypoints.length === 0) {
                alert('Debe importar al menos un waypoint desde un archivo KMZ');
                return;
            }
            
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Mostrar loading
            submitButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Guardando...
            `;
            submitButton.disabled = true;

            fetch(form.action, {
                method: form.method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    return response.json().then(data => {
                        if (data.success || response.ok) {
                            window.location.reload();
                        } else {
                            throw new Error(data.message || 'Error al guardar');
                        }
                    });
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        }

        // Cerrar con ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeCreateModal();
                closeEditModal();
                closeActionsModal();
            }
        });

        // ========== FUNCIONES PARA PROCESAR KMZ ==========
        
        // Manejar cambio de archivo KMZ en modal de crear
        document.getElementById('createKmzFile').addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                processKmzFile('create', e.target.files[0]);
            }
        });

        // Manejar cambio de archivo KMZ en modal de editar
        document.getElementById('editKmzFile').addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                processKmzFile('edit', e.target.files[0]);
            }
        });

        // Función para procesar archivo KMZ
        function processKmzFile(modal, file) {
            const loadingEl = document.getElementById(modal + 'KmzLoading');
            const errorEl = document.getElementById(modal + 'KmzError');
            const containerEl = document.getElementById(modal + 'WaypointsContainer');
            
            // Mostrar loading
            loadingEl.classList.remove('hidden');
            errorEl.classList.add('hidden');
            containerEl.classList.add('hidden');

            const formData = new FormData();
            formData.append('kmz_file', file);

            fetch('{{ route("misiones-flytbase.process-kmz") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                loadingEl.classList.add('hidden');
                
                if (data.success && data.waypoints) {
                    if (modal === 'create') {
                        createWaypoints = data.waypoints;
                    } else {
                        editWaypoints = data.waypoints;
                    }
                    // Actualizar contador de waypoints
                    const waypointsCount = data.waypoints.length;
                    const countField = document.getElementById(modal + 'WaypointsCount');
                    if (countField) {
                        countField.value = waypointsCount;
                    }
                    displayWaypoints(modal, data.waypoints);
                } else {
                    errorEl.querySelector('p').textContent = data.message || 'Error al procesar el archivo KMZ';
                    errorEl.classList.remove('hidden');
                }
            })
            .catch(error => {
                loadingEl.classList.add('hidden');
                errorEl.querySelector('p').textContent = 'Error al procesar el archivo: ' + error.message;
                errorEl.classList.remove('hidden');
                console.error('Error:', error);
            });
        }

        // Función para mostrar waypoints
        function displayWaypoints(modal, waypoints) {
            const container = document.getElementById(modal + 'WaypointsContainer');
            const list = document.getElementById(modal + 'WaypointsList');
            const count = document.getElementById(modal + 'WaypointsCount');
            
            list.innerHTML = '';
            
            if (!waypoints || waypoints.length === 0) {
                container.classList.add('hidden');
                return;
            }

            count.textContent = waypoints.length + ' waypoint' + (waypoints.length !== 1 ? 's' : '');
            
            waypoints.forEach((wp, index) => {
                const waypointCard = createWaypointCard(modal, wp, index);
                list.appendChild(waypointCard);
            });
            
            container.classList.remove('hidden');
            updateWaypointsJson(modal);
        }

        // Función para crear tarjeta de waypoint
        function createWaypointCard(modal, waypoint, index) {
            const card = document.createElement('div');
            card.className = 'bg-zinc-700 rounded-lg p-4 border border-zinc-600';
            card.dataset.index = index;
            
            const acciones = waypoint.acciones || [];
            const accionesHtml = acciones.length > 0 
                ? acciones.map((accion, accIndex) => {
                    let accionLabel = '';
                    let accionKey = '';
                    
                    if (typeof accion === 'object' && accion.type === 'drone_yaw') {
                        accionLabel = `Rotar Yaw del Drone: ${accion.angle}°`;
                        accionKey = JSON.stringify(accion);
                    } else {
                        accionLabel = accionesDisponibles[accion] || accion;
                        accionKey = accion;
                    }
                    
                    return `
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-600 text-white mr-1 mb-1">
                            ${accionLabel}
                            <button type="button" onclick="removeAction('${modal}', ${index}, ${accIndex})" 
                                    class="ml-2 text-blue-200 hover:text-white">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </span>
                    `;
                }).join('')
                : '<span class="text-xs text-gray-500 italic">No hay acciones</span>';
            
            card.innerHTML = `
                <div class="flex justify-between items-start mb-2">
                    <h5 class="text-sm font-medium text-gray-200">Waypoint ${index + 1}</h5>
                    <button type="button" onclick="openActionsModal('${modal}', ${index})" 
                            class="text-blue-400 hover:text-blue-300 text-xs flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Agregar Acciones
                    </button>
                </div>
                <div class="grid grid-cols-3 gap-2 text-xs mb-3">
                    <div>
                        <span class="text-gray-400">Latitud:</span>
                        <span class="text-gray-200 ml-1">${waypoint.latitud}</span>
                    </div>
                    <div>
                        <span class="text-gray-400">Longitud:</span>
                        <span class="text-gray-200 ml-1">${waypoint.longitud}</span>
                    </div>
                    <div>
                        <span class="text-gray-400">Altitud:</span>
                        <span class="text-gray-200 ml-1">${waypoint.altitud || '35'} m</span>
                    </div>
                </div>
                <div>
                    <span class="text-xs text-gray-400">Acciones:</span>
                    <div class="mt-1 flex flex-wrap">
                        ${accionesHtml}
                    </div>
                </div>
            `;
            
            return card;
        }

        // Función para abrir modal de acciones
        function openActionsModal(modal, waypointIndex) {
            currentModal = modal;
            currentWaypointIndex = waypointIndex;
            
            const modalEl = document.getElementById('actionsModal');
            const actionsList = document.getElementById('actionsList');
            actionsList.innerHTML = '';
            
            // Agregar botones para cada acción disponible
            Object.keys(accionesDisponibles).forEach(accionKey => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'w-full text-left bg-zinc-700 hover:bg-zinc-600 rounded-lg p-4 transition-colors border border-zinc-600 hover:border-zinc-500';
                button.innerHTML = `
                    <div class="flex justify-between items-center">
                        <span class="text-gray-200 font-medium">${accionesDisponibles[accionKey]}</span>
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                `;
                // Si es drone_yaw, abrir modal de configuración, sino agregar directamente
                if (accionKey === 'drone_yaw') {
                    button.onclick = () => openYawModal(modal, waypointIndex);
                } else {
                    button.onclick = () => addAction(modal, waypointIndex, accionKey);
                }
                actionsList.appendChild(button);
            });
            
            modalEl.classList.remove('hidden');
        }

        // Función para cerrar modal de acciones
        function closeActionsModal() {
            document.getElementById('actionsModal').classList.add('hidden');
            currentModal = null;
            currentWaypointIndex = null;
        }

        // Función para agregar acción a un waypoint
        function addAction(modal, waypointIndex, accion, angle = null) {
            const waypoints = modal === 'create' ? createWaypoints : editWaypoints;
            
            if (!waypoints[waypointIndex].acciones) {
                waypoints[waypointIndex].acciones = [];
            }
            
            // Si es drone_yaw, guardar como objeto con el ángulo
            let actionToAdd;
            if (accion === 'drone_yaw' && angle !== null) {
                actionToAdd = {
                    type: 'drone_yaw',
                    angle: parseInt(angle)
                };
            } else {
                actionToAdd = accion;
            }
            
            // Verificar si la acción ya existe
            const exists = waypoints[waypointIndex].acciones.some(a => {
                if (typeof a === 'object' && a.type === 'drone_yaw' && accion === 'drone_yaw') {
                    return true;
                }
                return a === accion;
            });
            
            if (!exists) {
                waypoints[waypointIndex].acciones.push(actionToAdd);
                displayWaypoints(modal, waypoints);
                closeActionsModal();
            }
        }

        // Función para abrir modal de yaw
        function openYawModal(modal, waypointIndex) {
            currentModal = modal;
            currentWaypointIndex = waypointIndex;
            document.getElementById('yawAngle').value = '0';
            document.getElementById('yawModal').classList.remove('hidden');
            document.getElementById('actionsModal').classList.add('hidden');
        }

        // Función para cerrar modal de yaw
        function closeYawModal() {
            document.getElementById('yawModal').classList.add('hidden');
            document.getElementById('actionsModal').classList.remove('hidden');
        }

        // Función para confirmar yaw
        function confirmYaw() {
            const angle = document.getElementById('yawAngle').value;
            if (angle === '' || angle < -180 || angle > 180) {
                alert('Por favor ingrese un ángulo válido entre -180° y 180°');
                return;
            }
            addAction(currentModal, currentWaypointIndex, 'drone_yaw', angle);
            closeYawModal();
        }

        // Función para eliminar acción de un waypoint
        function removeAction(modal, waypointIndex, accionIndex) {
            const waypoints = modal === 'create' ? createWaypoints : editWaypoints;
            
            if (waypoints[waypointIndex].acciones) {
                waypoints[waypointIndex].acciones.splice(accionIndex, 1);
                displayWaypoints(modal, waypoints);
            }
        }

        // Función para actualizar el campo hidden con el JSON de waypoints
        function updateWaypointsJson(modal) {
            const waypoints = modal === 'create' ? createWaypoints : editWaypoints;
            const jsonField = document.getElementById(modal + 'WaypointsJson');
            const countField = document.getElementById(modal + 'WaypointsCount');
            
            if (jsonField && waypoints.length > 0) {
                jsonField.value = JSON.stringify(waypoints);
            }
            
            // Actualizar contador de waypoints
            if (countField) {
                countField.value = waypoints.length;
            }
        }
    </script>
</x-app-layout>