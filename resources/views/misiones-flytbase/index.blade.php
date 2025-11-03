<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
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
                    <div class="overflow-x-auto rounded-lg border border-gray-700">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-gray-750">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">URL</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-700">
                                @forelse($misiones as $mision)
                                <tr class="hover:bg-gray-750 transition-colors">
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
                                            <button onclick="openEditModal({{ $mision->id }}, '{{ addslashes($mision->nombre) }}', '{{ addslashes($mision->descripcion) }}', {{ $mision->cliente_id }}, '{{ $mision->url }}', {{ $mision->activo ? 'true' : 'false' }}, {{ $mision->drone_id ?? 'null' }}, {{ $mision->dock_id ?? 'null' }}, {{ $mision->site_id ?? 'null' }}, '{{ $mision->route_altitude }}', '{{ $mision->route_speed }}', '{{ $mision->route_waypoint_type }}', '{{ addslashes($mision->observaciones) }}', `{{ addslashes(json_encode($mision->waypoints, JSON_PRETTY_PRINT)) }}`)"
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
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-100">Crear Nueva Misión</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form id="createForm" method="POST" action="{{ route('misiones-flytbase.store') }}">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna 1: Información Básica -->
                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-gray-200 border-b border-gray-700 pb-2">Información Básica</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Nombre *</label>
                            <input type="text" name="nombre" required 
                                   class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Descripción</label>
                            <textarea name="descripcion" rows="3"
                                      class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Cliente *</label>
                            <select name="cliente_id" required 
                                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Seleccione un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">URL del Webhook *</label>
                            <input type="url" name="url" required 
                                   class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                   placeholder="https://api.flytbase.com/rest/alarms/trigger">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Observaciones</label>
                            <textarea name="observaciones" rows="2"
                                      class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                      placeholder="Observaciones adicionales sobre la misión..."></textarea>
                        </div>
                    </div>

                    <!-- Columna 2: Configuración de Vuelo -->
                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-gray-200 border-b border-gray-700 pb-2">Configuración de Vuelo</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Drone</label>
                            <select name="drone_id" 
                                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Seleccione un drone</option>
                                @foreach($drones as $drone)
                                    <option value="{{ $drone->id }}">{{ $drone->drone }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Dock</label>
                            <select name="dock_id" 
                                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Seleccione un dock</option>
                                @foreach($docks as $dock)
                                    <option value="{{ $dock->id }}">{{ $dock->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Site</label>
                            <select name="site_id" 
                                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
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
                                       class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Velocidad (m/s) *</label>
                                <input type="number" name="route_speed" step="0.01" value="5.33" required 
                                       class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Ruta *</label>
                            <select name="route_waypoint_type" required 
                                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="linear_route">Ruta Lineal</option>
                                <option value="transits_waypoint">Vuela sobre waypoints</option>
                                <option value="curved_route_drone_stops">Ruta Curva con Paradas</option>
                                <option value="curved_route_drone_continues">Ruta Curva Contínua</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">JSON Waypoints</label>
                            <textarea name="waypoints" rows="8"
                                      class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 font-mono text-sm"
                                      placeholder='[
                                                        {
                                                            "altitud": 35,
                                                            "latitud": "-38.85047",
                                                            "acciones": ["take_panorama_image"],
                                                            "longitud": "-68.12660"
                                                        },
                                                        {
                                                            "altitud": 35,
                                                            "latitud": "-38.84919",
                                                            "acciones": [],
                                                            "longitud": "-68.12503"
                                                        }
                                                    ]'></textarea>
                            <p class="text-xs text-gray-400 mt-1">Ingrese el JSON con los waypoints de la misión. Formato válido requerido.</p>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="activo" id="createActivo" value="1" checked
                                   class="rounded bg-gray-700 border-gray-600 text-blue-600 focus:ring-blue-500 focus:ring-offset-gray-800">
                            <label for="createActivo" class="ml-2 text-sm text-gray-300">Misión activa</label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-700">
                    <button type="button" onclick="closeCreateModal()" 
                            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
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
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-100">Editar Misión</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-gray-200 border-b border-gray-700 pb-2">Información Básica</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Nombre *</label>
                            <input type="text" name="nombre" id="editNombre" required 
                                   class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Descripción</label>
                            <textarea name="descripcion" id="editDescripcion" rows="3"
                                      class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Cliente *</label>
                            <select name="cliente_id" id="editClienteId" required 
                                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Seleccione un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">URL del Webhook *</label>
                            <input type="url" name="url" id="editUrl" required 
                                   class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                   placeholder="https://api.flytbase.com/rest/alarms/trigger">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Observaciones</label>
                            <textarea name="observaciones" id="editObservaciones" rows="2"
                                      class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                      placeholder="Observaciones adicionales sobre la misión..."></textarea>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-gray-200 border-b border-gray-700 pb-2">Configuración de Vuelo</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Drone</label>
                            <select name="drone_id" id="editDroneId" 
                                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Seleccione un drone</option>
                                @foreach($drones as $drone)
                                    <option value="{{ $drone->id }}">{{ $drone->drone }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Dock</label>
                            <select name="dock_id" id="editDockId" 
                                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Seleccione un dock</option>
                                @foreach($docks as $dock)
                                    <option value="{{ $dock->id }}">{{ $dock->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Site</label>
                            <select name="site_id" id="editSiteId" 
                                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
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
                                       class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Velocidad (m/s) *</label>
                                <input type="number" name="route_speed" id="editRouteSpeed" step="0.01" required 
                                       class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Ruta *</label>
                            <select name="route_waypoint_type" id="editRouteWaypointType" required 
                                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="linear_route">Ruta Lineal</option>
                                <option value="transits_waypoint">Vuela sobre waypoints</option>
                                <option value="curved_route_drone_stops">Ruta Curva con Paradas</option>
                                <option value="curved_route_drone_continues">Ruta Curva Contínua</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">JSON Waypoints</label>
                            <textarea name="waypoints" id="editWaypoints" rows="8"
                                      class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 font-mono text-sm"
                                      placeholder='[
                                                    {
                                                        "altitud": 35,
                                                        "latitud": "-38.85047",
                                                        "acciones": ["take_panorama_image"],
                                                        "longitud": "-68.12660"
                                                    },
                                                    {
                                                        "altitud": 35,
                                                        "latitud": "-38.84919",
                                                        "acciones": [],
                                                        "longitud": "-68.12503"
                                                    }
                                                    ]'></textarea>
                            <p class="text-xs text-gray-400 mt-1">Ingrese el JSON con los waypoints de la misión. Formato válido requerido.</p>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="activo" id="editActivo" value="1"
                                   class="rounded bg-gray-700 border-gray-600 text-blue-600 focus:ring-blue-500 focus:ring-offset-gray-800">
                            <label for="editActivo" class="ml-2 text-sm text-gray-300">Misión activa</label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-700">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
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
    <script>
        // Funciones para el modal de crear
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
            document.getElementById('createForm').reset();
            document.getElementById('createActivo').checked = true;
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        // Funciones para el modal de editar
        function openEditModal(id, nombre, descripcion, clienteId, url, activo, droneId = null, dockId = null, siteId = null, routeAltitude = '35.00', routeSpeed = '5.33', routeWaypointType = 'linear_route', observaciones = '', waypoints = '') {
            console.log('Abriendo modal de edición con datos:', { 
                id, nombre, descripcion, clienteId, url, activo, 
                droneId, dockId, siteId, routeAltitude, routeSpeed, 
                routeWaypointType, observaciones, waypoints 
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

            // Manejar waypoints
            if (waypoints && waypoints !== 'null' && waypoints !== '""') {
                try {
                    // Si waypoints ya es un string JSON, intentar formatearlo
                    if (typeof waypoints === 'string') {
                        const parsedWaypoints = JSON.parse(waypoints);
                        document.getElementById('editWaypoints').value = JSON.stringify(parsedWaypoints, null, 2);
                    } else {
                        document.getElementById('editWaypoints').value = JSON.stringify(waypoints, null, 2);
                    }
                } catch (e) {
                    console.error('Error al parsear waypoints:', e);
                    // Si hay error al parsear, usar el valor original
                    document.getElementById('editWaypoints').value = waypoints;
                }
            } else {
                document.getElementById('editWaypoints').value = '';
            }
            
            console.log('Modal configurado correctamente');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Cerrar modales al hacer click fuera
        document.addEventListener('click', function(event) {
            const createModal = document.getElementById('createModal');
            const editModal = document.getElementById('editModal');
            
            if (event.target === createModal) {
                closeCreateModal();
            }
            if (event.target === editModal) {
                closeEditModal();
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
            }
        });
    </script>
</x-app-layout>