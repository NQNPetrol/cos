<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-5 text-gray-100 dark:text-gray-100">
                    <!-- Header -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-100">Administrar Drones Flytbase</h2>
                                <p class="text-sm text-gray-400 mt-1">Gestión y monitoreo de todos los drones disponibles en la plataforma</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button onclick="openCreateModal()"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Crear Drone
                                </button>
                                <a href="https://console.flytbase.com" target="_blank" 
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-800 focus:ring ring-gray-300 transition ease-in-out duration-150">
                                    Abrir Flytbase
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Panel de Drones -->
                    <div class="bg-gray-800 rounded-lg shadow-sm border border-gray-700">
                        <div class="p-6">
                           
                            <!-- Tabla de Drones -->
                            <div class="overflow-x-auto rounded-lg border border-gray-700">
                                <table class="min-w-full divide-y divide-gray-700">
                                    <thead class="bg-gray-750">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Nombre</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Estado</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Misiones Activas</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Liveview</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">URL</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                                        @forelse($drones as $drone)
                                        <tr class="hover:bg-gray-750 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-300">#{{ $drone->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-rocket" viewBox="0 0 16 16">
                                                            <path d="M8 8c.828 0 1.5-.895 1.5-2S8.828 4 8 4s-1.5.895-1.5 2S7.172 8 8 8"/>
                                                            <path d="M11.953 8.81c-.195-3.388-.968-5.507-1.777-6.819C9.707 1.233 9.23.751 8.857.454a3.5 3.5 0 0 0-.463-.315A2 2 0 0 0 8.25.064.55.55 0 0 0 8 0a.55.55 0 0 0-.266.073 2 2 0 0 0-.142.08 4 4 0 0 0-.459.33c-.37.308-.844.803-1.31 1.57-.805 1.322-1.577 3.433-1.774 6.756l-1.497 1.826-.004.005A2.5 2.5 0 0 0 2 12.202V15.5a.5.5 0 0 0 .9.3l1.125-1.5c.166-.222.42-.4.752-.57.214-.108.414-.192.625-.281l.198-.084c.7.428 1.55.635 2.4.635s1.7-.207 2.4-.635q.1.044.196.083c.213.09.413.174.627.282.332.17.586.348.752.57l1.125 1.5a.5.5 0 0 0 .9-.3v-3.298a2.5 2.5 0 0 0-.548-1.562zM12 10.445v.055c0 .866-.284 1.585-.75 2.14.146.064.292.13.425.199.39.197.8.46 1.1.86L13 14v-1.798a1.5 1.5 0 0 0-.327-.935zM4.75 12.64C4.284 12.085 4 11.366 4 10.5v-.054l-.673.82a1.5 1.5 0 0 0-.327.936V14l.225-.3c.3-.4.71-.664 1.1-.861.133-.068.279-.135.425-.199M8.009 1.073q.096.06.226.163c.284.226.683.621 1.09 1.28C10.137 3.836 11 6.237 11 10.5c0 .858-.374 1.48-.943 1.893C9.517 12.786 8.781 13 8 13s-1.517-.214-2.057-.607C5.373 11.979 5 11.358 5 10.5c0-4.182.86-6.586 1.677-7.928.409-.67.81-1.082 1.096-1.32q.136-.113.236-.18Z"/>
                                                            <path d="M9.479 14.361c-.48.093-.98.139-1.479.139s-.999-.046-1.479-.139L7.6 15.8a.5.5 0 0 0 .8 0z"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-100">{{ $drone->drone }}</div>
                                                        <div class="text-xs text-gray-400">ID: {{ $drone->id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($drone->activo)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-900/30 text-green-400 border border-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Activo
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-900/30 text-red-400 border border-red-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Inactivo
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                <div class="flex items-center">
                                                    <span class="text-lg font-semibold {{ $drone->misiones_count > 0 ? 'text-green-400' : 'text-gray-400' }}">
                                                        {{ $drone->misiones_count }}
                                                    </span>
                                                 
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($drone->hasLiveviewView())
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-900/30 text-blue-400 border border-blue-800">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                        </svg>
                                                        Disponible
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-900/30 text-gray-400 border border-gray-700">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                        </svg>
                                                        No disponible
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-300">
                                                @if($drone->share_url)
                                                    <div class="flex items-center space-x-2">
                                                        {{-- URL acortada con tooltip --}}
                                                        <span class="text-xs font-mono bg-gray-700 px-2 py-1 rounded border border-gray-600" 
                                                            title="{{ $drone->share_url }}"> {{-- Tooltip con URL completa --}}
                                                            {{ Str::limit($drone->share_url, 25, '...') }} {{-- URL limitada a 25 chars --}}
                                                        </span>
                                                        {{-- Botón para copiar URL --}}
                                                        <button type="button" 
                                                                onclick="copyUrl('{{ $drone->share_url }}', this)" {{-- Función para copiar --}}
                                                                class="inline-flex items-center p-1 bg-gray-600 border border-gray-500 rounded text-xs text-gray-300 hover:bg-gray-500 hover:text-white transition-colors duration-200"
                                                                title="Copiar URL">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @else
                                                    {{-- Estado cuando no hay URL --}}
                                                    <div class="flex items-center space-x-2">
                                                        <span class="text-red-400 text-xs">✗</span>
                                                        <span class="text-xs bg-gray-700 px-2 py-1 rounded">No configurada</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center space-x-2">
                                                    {{-- Botón Editar --}}
                                                    <button onclick="openEditModal({{ $drone->id }}, '{{ $drone->drone }}', '{{ $drone->share_url }}', {{ $drone->activo ? 'true' : 'false' }}, {{ $drone->dock_id ?? 'null' }})"
                                                    class="text-gray-400 hover:text-gray-300 transition-colors p-1 rounded hover:bg-gray-900/30"
                                                    title="Editar dron">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                        </svg>
                                                    </button>
                                                    <form action="{{ route('drones-flytbase.destroy', $drone) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-400 hover:text-red-300 transition-colors p-1 rounded hover:bg-red-900/30"
                                                                title="Eliminar drone"
                                                                onclick="return confirm('¿Está seguro de eliminar este drone? Esta acción no se puede deshacer.')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    @if($drone->hasLiveviewView() && $drone->activo)
                                                        <a href="{{ route('streaming.drone.liveview', ['droneName' => $drone->drone]) }}" 
                                                        class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 transition ease-in-out duration-150"
                                                        title="Ver Liveview">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                            </svg>
                                                           VER
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-400">
                                                <svg class="w-12 h-12 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                </svg>
                                                No se encontraron drones registrados
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <!-- Modal de Crear Drone -->
                                <div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
                                    <div class="bg-gray-800 rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-medium text-gray-100">Crear Nuevo Drone</h3>
                                            <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-300">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <form id="createForm" method="POST" action="{{ route('drones-flytbase.store') }}">
                                            @csrf
                                            
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-300 mb-2">Nombre del Drone *</label>
                                                    <input type="text" name="drone" required 
                                                        class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                                        placeholder="Ej: drone_001">
                                                </div>

                                                <!-- INICIO CAMBIO: Campo dock_id agregado -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-300 mb-2">Dock Asociado</label>
                                                    <select name="dock_id" 
                                                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                                        <option value="">Seleccione un dock</option>
                                                        @foreach($docks as $dock)
                                                            <option value="{{ $dock->id }}">{{ $dock->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-xs text-gray-400 mt-1">Seleccione el dock donde está asignado este drone (opcional)</p>
                                                </div>
                                                <!-- FIN CAMBIO -->

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-300 mb-2">URL de Compartir *</label>
                                                    <input type="url" name="share_url" required 
                                                        class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                                        placeholder="https://flytbase.com/guest/...">
                                                    <p class="text-xs text-gray-400 mt-1">URL de compartir de Flytbase para el liveview</p>
                                                </div>

                                                <div class="flex items-center">
                                                    <input type="checkbox" name="activo" id="createActivo" value="1" checked
                                                        class="rounded bg-gray-700 border-gray-600 text-blue-600 focus:ring-blue-500 focus:ring-offset-gray-800">
                                                    <label for="createActivo" class="ml-2 text-sm text-gray-300">Drone activo</label>
                                                </div>
                                            </div>

                                            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-700">
                                                <button type="button" onclick="closeCreateModal()" 
                                                        class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                                                    Cancelar
                                                </button>
                                                <button type="submit" 
                                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                                    Crear Drone
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
                                    <div class="bg-gray-800 rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-medium text-gray-100">Editar Drone</h3>
                                            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-300">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <form id="editForm" method="POST">
                                            @csrf
                                            @method('PUT')
                                            
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-300 mb-2">Nombre del Drone *</label>
                                                    <input type="text" name="drone" id="editDrone" required 
                                                        class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                                        placeholder="Ej: drone_001">
                                                </div>

                                                <!-- INICIO CAMBIO: Campo dock_id agregado -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-300 mb-2">Dock Asociado</label>
                                                    <select name="dock_id" id="editDockId"
                                                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                                        <option value="">Seleccione un dock</option>
                                                        @foreach($docks as $dock)
                                                            <option value="{{ $dock->id }}">{{ $dock->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-xs text-gray-400 mt-1">Seleccione el dock donde está asignado este drone (opcional)</p>
                                                </div>
                                                <!-- FIN CAMBIO -->

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-300 mb-2">URL de Compartir *</label>
                                                    <input type="url" name="share_url" id="editShareUrl" required 
                                                        class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                                        placeholder="https://flytbase.com/guest/...">
                                                    <p class="text-xs text-gray-400 mt-1">URL de compartir de Flytbase para el liveview</p>
                                                </div>

                                                <div class="flex items-center">
                                                    <input type="checkbox" name="activo" id="editActivo" value="1"
                                                        class="rounded bg-gray-700 border-gray-600 text-blue-600 focus:ring-blue-500 focus:ring-offset-gray-800">
                                                    <label for="editActivo" class="ml-2 text-sm text-gray-300">Drone activo</label>
                                                </div>
                                            </div>

                                            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-700">
                                                <button type="button" onclick="closeEditModal()" 
                                                        class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                                                    Cancelar
                                                </button>
                                                <button type="submit" 
                                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                                    Actualizar Drone
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .animate-slideDown {
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <script>
        function copyUrl(text, button) {
            // Intentar usar API moderna primero
            if (navigator.clipboard && window.isSecureContext) {
                copyToClipboardModern(text, button);
            } else {
                copyToClipboard(text, button);
            }
        }

        // Método moderno con Clipboard API
        async function copyToClipboardModern(text, button) {
            try {
                await navigator.clipboard.writeText(text);
                showCopySuccess(button); // Mostrar feedback visual
            } catch (err) {
                console.error('Error al copiar: ', err);
                copyToClipboard(text, button); // Fallback a método legacy
            }
        }

        // Método legacy para navegadores antiguos
        function copyToClipboard(text, button) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            
            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    showCopySuccess(button);
                }
            } catch (err) {
                console.error('Error al copiar: ', err);
                alert('No se pudo copiar automáticamente. La URL es:\n\n' + text);
            }
            
            document.body.removeChild(textArea);
        }

        // Función para mostrar feedback visual de copiado exitoso
        function showCopySuccess(button) {
            const originalHTML = button.innerHTML;
            const originalTitle = button.title;
            
            // Cambiar a estado de éxito
            button.innerHTML = `<svg class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
            button.classList.remove('bg-gray-600', 'text-gray-300');
            button.classList.add('bg-green-600', 'text-white');
            button.title = '¡Copiado!';
            
            // Restaurar después de 2 segundos
            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('bg-green-600', 'text-white');
                button.classList.add('bg-gray-600', 'text-gray-300');
                button.title = originalTitle;
            }, 2000);
        }
    </script>

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
        function openEditModal(id, drone, shareUrl, activo, dockId = null) {
            document.getElementById('editModal').classList.remove('hidden');
            
            // Actualizar el formulario
            document.getElementById('editForm').action = `/drones-flytbase/${id}`;
            document.getElementById('editDrone').value = drone;
            document.getElementById('editShareUrl').value = shareUrl;
            document.getElementById('editActivo').checked = activo;
            // INICIO CAMBIO: Actualizar dock_id
            document.getElementById('editDockId').value = dockId && dockId !== 'null' ? dockId : '';
            // FIN CAMBIO
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
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
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