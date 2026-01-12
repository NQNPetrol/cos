<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-zinc-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-5 text-gray-100 dark:text-gray-100">
                <!-- Header -->
                <div class="mb-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-100">Sitios Flytbase</h2>
                            <p class="text-sm text-gray-400 mt-1">Gestión y administración de todos los sitios disponibles</p>
                        </div>
                        <button wire:click="openCreateModal" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Agregar Site
                        </button>
                    </div>
                </div>

                <!-- Panel de Sitios -->
                <div class="bg-zinc-800 rounded-lg shadow-sm border border-zinc-700">
                    <div class="p-6">
                        <!-- Filtros -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                            <!-- Buscar -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-300 mb-1">Buscar</label>
                                <input type="text" 
                                       wire:model.live="search" 
                                       id="search" 
                                       placeholder="Nombre, descripción..." 
                                       class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm">
                            </div>

                            <!-- Filtro Cliente -->
                            <div>
                                <label for="clienteFilter" class="block text-sm font-medium text-gray-300 mb-1">Cliente</label>
                                <select wire:model.live="clienteFilter" 
                                        id="clienteFilter" 
                                        class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm">
                                    <option value="">Todos los clientes</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro Dock-Drone -->
                            <div>
                                <label for="dockDroneFilter" class="block text-sm font-medium text-gray-300 mb-1">Dispositivos</label>
                                <select wire:model.live="dockDroneFilter" 
                                        id="dockDroneFilter" 
                                        class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm">
                                    <option value="">Todas las combinaciones</option>
                                    @foreach($dockDronePairs as $key => $pair)
                                        <option value="{{ $key }}">{{ $pair }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro Activo -->
                            <div>
                                <label for="activoFilter" class="block text-sm font-medium text-gray-300 mb-1">Estado</label>
                                <select wire:model.live="activoFilter" 
                                        id="activoFilter" 
                                        class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm">
                                    <option value="">Todos</option>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>

                            <!-- Botón Limpiar Filtros -->
                   
                            <div class="flex items-end">
                                <button wire:click="clearAllFilters"
                                        class="w-full px-3 py-2 bg-zinc-600 hover:bg-zinc-500 text-white text-sm font-medium rounded-md transition-colors duration-200 border border-zinc-500 flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                    Limpiar
                                </button>
                            </div>
                        </div>

                        <!-- Notificaciones -->
                        @if (session()->has('success'))
                            <div class="mb-4 p-3 bg-green-900/30 border border-green-800 text-green-400 rounded-lg text-sm">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Tabla de Sitios -->
                        <div class="overflow-x-auto rounded-lg border border-zinc-700">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-zinc-750">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Nombre</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Cliente</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Dispositivos</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Miembros</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Descripción</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-zinc-800 divide-y divide-gray-700">
                                    @forelse($sites as $site)
                                        <tr class="hover:bg-zinc-750 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-100">{{ $site->nombre }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-300">{{ $site->cliente->nombre ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-300">
                                                    {{ $this->getCantidadDispositivos($site->devices) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-300">
                                                    {{ $this->getCantidadMiembros($site->members) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($site->activo)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900/30 text-green-400 border border-green-700">
                                                        Activo
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900/30 text-red-400 border border-red-700">
                                                        Inactivo
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-300 max-w-xs truncate" title="{{ $site->descripcion }}">
                                                    {{ $site->descripcion ?? 'Sin descripción' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center space-x-2">
                                                    <button wire:click="openEditModal({{ $site->id }})" 
                                                            class="text-gray-400 hover:text-gray-300 transition-colors p-1 rounded hover:bg-zinc-900/30"
                                                            title="Editar">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </button>
                                                    <button wire:click="deleteSite({{ $site->id }})" 
                                                            wire:confirm="¿Estás seguro de que deseas eliminar este sitio?"
                                                            class="text-red-400 hover:text-red-300 transition-colors p-1 rounded hover:bg-red-900/30"
                                                            title="Eliminar">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-400">
                                                <svg class="w-12 h-12 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                                No se encontraron sitios
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        @if($sites->hasPages())
                            <div class="mt-4 px-4 py-3 border-t border-zinc-700">
                                {{ $sites->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="scrollbar-width: none; -ms-overflow-style: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-zinc-500 bg-opacity-75" wire:click="closeModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div class="inline-block w-full max-w-4xl my-8 overflow-hidden text-left align-middle transition-all transform bg-zinc-800 shadow-xl rounded-lg sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <div class="px-6 py-4 border-b border-zinc-700">
                        <h3 class="text-lg font-medium text-gray-100">
                            {{ $editing ? 'Editar Site' : 'Agregar Nuevo Site' }}
                        </h3>
                    </div>

                    <form wire:submit.prevent="saveSite">
                        <div class="px-6 py-4 max-h-96 overflow-y-auto" style="scrollbar-width: thin; scrollbar-color: #4B5563 #1F2937;">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Columna Izquierda -->
                                <div class="space-y-4">
                                    <!-- Nombre -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-1">Nombre *</label>
                                        <input type="text" 
                                               wire:model="nombre" 
                                               class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm">
                                        @error('nombre') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Descripción -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-1">Descripción</label>
                                        <textarea wire:model="descripcion" 
                                                  rows="3"
                                                  class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm"></textarea>
                                        @error('descripcion') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Cliente -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-1">Cliente *</label>
                                        <select wire:model="cliente_id" 
                                                class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm">
                                            <option value="">Seleccionar Cliente</option>
                                            @foreach($clientes as $cliente)
                                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                            @endforeach
                                        </select>
                                        @error('cliente_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Ubicación -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-1">Ubicación *</label>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <input type="number" step="any" 
                                                       wire:model="latitud" 
                                                       placeholder="Latitud"
                                                       class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm">
                                                @error('latitud') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <input type="number" step="any" 
                                                       wire:model="longitud" 
                                                       placeholder="Longitud"
                                                       class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm">
                                                @error('longitud') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Organización -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-1">Organización</label>
                                        <select wire:model="organization_id" 
                                                class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm">
                                            <option value="">Seleccionar Organización</option>
                                            @foreach($organizations as $org)
                                                <option value="{{ $org->id }}">{{ $org->nombre }}</option>
                                            @endforeach
                                        </select>
                                        @error('organization_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Estado (solo en edición) -->
                                    @if($editing)
                                    <div>
                                        <label class="flex items-center">
                                            <input type="checkbox" 
                                                   wire:model="activo" 
                                                   class="rounded bg-zinc-700 border-zinc-600 text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-300">Activo</span>
                                        </label>
                                    </div>
                                    @endif
                                </div>

                                <!-- Columna Derecha -->
                                <div class="space-y-4">
                                    <!-- Dispositivos -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-1">Dispositivos</label>
                                        <div class="grid grid-cols-2 gap-4">
                                            <!-- Docks -->
                                            <div>
                                                <label class="block text-xs font-medium text-gray-400 mb-2">Docks Disponibles</label>
                                                <div class="space-y-2 max-h-32 overflow-y-auto bg-zinc-700 rounded-md p-2" style="scrollbar-width: thin; scrollbar-color: #4B5563 #374151;">
                                                    @forelse($availableDocks as $dock)
                                                        <label class="flex items-center">
                                                            <input type="checkbox" 
                                                                   wire:model="selectedDocks" 
                                                                   value="{{ $dock->id }}"
                                                                   class="rounded bg-zinc-600 border-zinc-500 text-blue-600 focus:ring-blue-500">
                                                            <span class="ml-2 text-sm text-gray-300">{{ $dock->nombre }}</span>
                                                        </label>
                                                    @empty
                                                        <p class="text-xs text-gray-400 text-center py-2">No hay docks disponibles</p>
                                                    @endforelse
                                                </div>
                                                @error('selectedDocks') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                            </div>

                                            <!-- Drones -->
                                            <div>
                                                <label class="block text-xs font-medium text-gray-400 mb-2">Drones Disponibles</label>
                                                <div class="space-y-2 max-h-32 overflow-y-auto bg-zinc-700 rounded-md p-2" style="scrollbar-width: thin; scrollbar-color: #4B5563 #374151;">
                                                    @forelse($availableDrones as $drone)
                                                        <label class="flex items-center">
                                                            <input type="checkbox" 
                                                                   wire:model="selectedDrones" 
                                                                   value="{{ $drone->id }}"
                                                                   class="rounded bg-zinc-600 border-zinc-500 text-blue-600 focus:ring-blue-500">
                                                            <span class="ml-2 text-sm text-gray-300">{{ $drone->drone }}</span>
                                                        </label>
                                                    @empty
                                                        <p class="text-xs text-gray-400 text-center py-2">No hay drones disponibles</p>
                                                    @endforelse
                                                </div>
                                                @error('selectedDrones') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="mt-2 text-xs text-gray-400">
                                            Selecciona la misma cantidad de docks y drones. Se emparejarán en el orden de selección.
                                        </div>
                                        @if(count($selectedDocks) !== count($selectedDrones))
                                            <div class="mt-2 text-xs text-red-400">
                                                ⚠️ La cantidad de docks ({{ count($selectedDocks) }}) y drones ({{ count($selectedDrones) }}) seleccionados no coincide.
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Miembros -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-1">Miembros</label>
                                        <div class="max-h-32 overflow-y-auto bg-zinc-700 rounded-md p-2" style="scrollbar-width: thin; scrollbar-color: #4B5563 #374151;">
                                            <div class="space-y-2">
                                                @foreach($users as $user)
                                                    <label class="flex items-center">
                                                        <input type="checkbox" 
                                                               wire:model="selectedMembers" 
                                                               value="{{ $user->id }}"
                                                               class="rounded bg-zinc-600 border-zinc-500 text-blue-600 focus:ring-blue-500">
                                                        <span class="ml-2 text-sm text-gray-300">{{ $user->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-4 border-t border-zinc-700 bg-zinc-750 flex justify-end space-x-3">
                            <button type="button" 
                                    wire:click="closeModal" 
                                    class="px-4 py-2 text-sm font-medium text-gray-300 bg-zinc-700 hover:bg-zinc-600 rounded-md transition-colors duration-200">
                                Cancelar
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors duration-200">
                                {{ $editing ? 'Actualizar' : 'Crear' }} Site
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    <style>
    /* Ocultar scrollbars pero mantener funcionalidad */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-track {
        background: #1F2937;
        border-radius: 3px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #4B5563;
        border-radius: 3px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #6B7280;
    }
</style>
</div>