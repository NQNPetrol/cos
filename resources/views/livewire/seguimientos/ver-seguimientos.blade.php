
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100 dark:text-gray-100">
                    <h2 class="text-2xl font-semibold mb-6">{{ $header }}</h2>
                    
                    <!-- Filtros Livewire -->
                    <div class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Filtro por estado -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Estado</label>
                                <select wire:model.live="estadoFilter" 
                                        class="w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                    <option value="">Todos</option>
                                    <option value="ABIERTO">Abierto</option>
                                    <option value="EN REVISION">En Revisión</option>
                                    <option value="CERRADO">Cerrado</option>
                                </select>
                            </div>

                            <!-- Tipo de evento -->

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Tipo de Evento</label>
                                <input type="text" wire:model.live.debounce.500ms="tipoFilter"
                                       placeholder="Filtrar por tipo..."
                                       class="w-full rounded-md bg-gray-700 border-gray-600 text-white">
                            </div>
                            
                            <!-- filtro de búsqueda -->
                             <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Buscar</label>
                                <input type="text" wire:model.live.debounce.500ms="search"
                                       placeholder="#ID, estado, tipo ..."
                                       class="w-full rounded-md bg-gray-700 border-gray-600 text-white">
                            </div>

                            <!-- Botones -->
                            <div class="flex items-end space-x-2">
                                <button wire:click="clearFilters" 
                                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded-md transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Limpiar Filtros
                                </button>
                                <a href="{{ route('seguimientos.create') }}" 
                                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors duration-200">
                                   <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Nuevo Seguimiento
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tabla de resultados -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="g-gray-800 text-gray-300 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('id')">
                                        Evento
                                        @if($sortField === 'id')
                                            @if($sortDirection === 'asc')
                                                ↑
                                            @else
                                                ↓
                                            @endif
                                        @endif
                                    </th>
        
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('estado')">
                                        Estado
                                        @if($sortField === 'estado')
                                            @if($sortDirection === 'asc')
                                                ↑
                                            @else
                                                ↓
                                            @endif
                                        @endif
                                    </th>
                                    
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('tipo')">
                                        Tipo de Evento
                                        @if($sortField === 'tipo')
                                            @if($sortDirection === 'asc')
                                                ↑
                                            @else
                                                ↓
                                            @endif
                                        @endif
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('fecha')">
                                        Fecha
                                        @if($sortField === 'fecha_registro')
                                            @if($sortDirection === 'asc')
                                                ↑
                                            @else
                                                ↓
                                            @endif
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="dark:bg-gray-800">
                                @forelse ($seguimientos as $seguimiento)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 dark:text-gray-300">
                                        Evento #{{ $seguimiento->evento->id ?? 'N/A' }}
                                        @if($seguimiento->evento)
                                            <div class="text-xs text-gray-500">{{ $seguimiento->evento->nombre }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $seguimiento->estado == 'CERRADO' ? 'bg-green-100 text-green-800' : 
                                               ($seguimiento->estado == 'EN REVISION' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ $seguimiento->estado }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 dark:text-gray-300">
                                        {{ $seguimiento->evento->tipo ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 dark:text-gray-300">
                                        {{ $seguimiento->fecha->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="#" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="#" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="#" class="text-red-600 hover:text-red-900 dark:text-red-400">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No se encontraron seguimientos con los filtros actuales
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación -->
                    <div class="mt-4">
                        {{ $seguimientos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

