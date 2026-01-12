
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-zinc-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100 dark:text-gray-100">
                    <h2 class="text-2xl font-semibold mb-6">{{ $header }}</h2>
                    
                    <!-- Filtros Livewire -->
                    <div class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Filtro por estado -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Estado</label>
                                <select wire:model.live="estadoFilter" 
                                        class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white">
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
                                       class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white">
                            </div>
                            
                            <!-- filtro de búsqueda -->
                             <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Buscar</label>
                                <input type="text" wire:model.live.debounce.500ms="search"
                                       placeholder="#ID, estado, tipo ..."
                                       class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white">
                            </div>

                            <!-- Botones -->
                            <div class="flex items-end space-x-2">
                                <button wire:click="clearFilters" 
                                        class="px-4 py-2 bg-zinc-700 hover:bg-zinc-600 text-gray-300 rounded-md transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Limpiar Filtros
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tabla de resultados -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="g-gray-800 text-gray-300 dark:bg-zinc-700">
                                <tr>
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('tipo')">
                                        Observaciones
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('tipo')">
                                        Usuario encargado
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="dark:bg-zinc-800">
                                @forelse ($seguimientos as $seguimiento)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 dark:text-gray-300">
                                        {{ $seguimiento->fecha->format('d/m/Y') }}
                                    </td>
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
                                        {{ $seguimiento->observaciones ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 dark:text-gray-300">
                                        {{ $seguimiento->user->name ?? 'N/A' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center px-4 py-8 text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <p>No se encontraron seguimientos</p>
                                            <p class="text-sm">Intenta ajustar los filtros o crea un nuevo seguimiento</p>
                                        </div>
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
        <!-- Mensajes de éxito/error -->
        @if (session()->has('success'))
            <div class="fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
                {{ session('success') }}
            </div>
        @endif
    </div>

