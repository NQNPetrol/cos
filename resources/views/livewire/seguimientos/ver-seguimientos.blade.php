
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
                                <button wire:click="openModal"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors duration-200">
                                   <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Nuevo
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tabla de resultados -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="g-gray-800 text-gray-300 dark:bg-gray-700">
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
                                    <!-- Acciones -->
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="dark:bg-gray-800">
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button wire:click="edit({{ $seguimiento->id }})" 
                                                    class="text-blue-400 hover:text-blue-300"
                                                    title="Editar">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button wire:click="delete({{ $seguimiento->id }})"
                                                    onclick="return confirm('¿Está seguro que desea eliminar este elemento?')"
                                                    class="text-red-400 hover:text-red-300"
                                                    title="Eliminar">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
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


                    <!-- Modal de Nuevo seguimiento/editar -->
                    @if($showModal)
                        <div wire:click.self="closeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                            <div class="bg-gray-900 rounded-lg p-6 w-full max-w-4xl max-h-[90vh] overflow-y-auto" @click.stop>
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-bold text-gray-100">
                                        @if($editingId)
                                            Editar Seguimiento del Evento #{{ $evento_id }}
                                        @else
                                            Nuevo Seguimiento
                                        @endif
                                    </h3>
                                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                @if($editingId)
                                <!-- Formulario de edicion -->
                                <form wire:submit.prevent="save">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="col-span-full">
                                            <h4 class="text-lg font-semibold text-gray-200 mb-3 border-b border-gray-700 pb-2">
                                                Información del Seguimiento
                                            </h4>
                                        </div>


                                        <div>
                                            <label class="block text-sm mb-1 text-gray-300">Fecha <span class="text-red-500">*</span></label>
                                            <input type="date" wire:model="fecha"
                                                class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                                            @error('fecha') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm mb-1 text-gray-300">Estado <span class="text-red-500">*</span></label>
                                            <select wire:model="estado" class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                                                <option value="ABIERTO">Abierto</option>
                                                <option value="EN REVISION">En Revisión</option>
                                                <option value="CERRADO">Cerrado</option>
                                            </select>
                                            @error('estado') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="col-span-full">
                                            <label class="block text-sm mb-1 text-gray-300">Observaciones</label>
                                            <textarea wire:model="observaciones" rows="2"
                                                    class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200"
                                                    placeholder="Observaciones adicionales..."></textarea>
                                        </div>
                                    </div>

                                    <!-- Botones -->
                                    <div class="flex justify-end space-x-4 mt-6 pt-4 border-t border-gray-700">
                                        <button type="button" wire:click="closeModal"
                                                class="px-4 py-2 text-gray-300 hover:text-gray-100">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 px-6 py-2 rounded text-white font-medium">
                                            {{ $editingId ? 'Actualizar' : 'Guardar' }}
                                        </button>
                                    </div>
                                </form>
                                @else
                                <!-- Formulario para nuevo seguimiento -->
                                <form wire:submit.prevent="saveNew">
                                    <div class="space-y-6">
                                        <!-- Campo Evento -->
                                        <div>
                                            <label for="evento_id" class="block text-sm font-medium text-gray-300 mb-2">
                                                Evento <span class="text-red-500">*</span>
                                            </label>

                                            @if($eventosDisponibles->isEmpty())
                                                <div class="bg-gray-700 border border-gray-600 rounded-md p-3 text-yellow-400">
                                                    No hay eventos disponibles para seguimiento. Todos los eventos están cerrados o no hay eventos creados.
                                                </div>
                                            @else
                                            <select wire:model="evento_id" id="evento_id" 
                                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md shadow-sm 
                                                        focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-white">
                                                <option value="">Seleccione un evento</option>
                                                @foreach($eventosDisponibles as $evento)
                                                    <option value="{{ $evento->id }}">Evento #{{ $evento->id }} - {{ $evento->tipo }} - {{ $evento->cliente->nombre ?? 'Sin cliente' }}</option>
                                                @endforeach
                                            </select>
                                            @error('evento_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                            @endif
                                        </div>

                                        <!-- Campo Estado -->
                                        <div>
                                            <label for="estado" class="block text-sm font-medium text-gray-300 mb-2">
                                                Estado <span class="text-red-500">*</span>
                                            </label>
                                            <select wire:model="estado" id="estado" 
                                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md shadow-sm 
                                                        focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-white">
                                                <option value="ABIERTO">ABIERTO</option>
                                                <option value="EN REVISION">EN REVISIÓN</option>
                                                <option value="CERRADO">CERRADO</option>
                                            </select>
                                            @error('estado') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Campo Registra (automático) -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                                Registra
                                            </label>
                                            <div class="px-3 py-2 bg-gray-700 rounded-md text-gray-300">
                                                {{ auth()->user()->name }}
                                            </div>
                                        </div>

                                        <!-- Campo Fecha Registro (automático) -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                                Fecha de Registro
                                            </label>
                                            <div class="px-3 py-2 bg-gray-700 rounded-md text-gray-300">
                                                {{ now()->format('d/m/Y') }}
                                            </div>
                                        </div>

                                        <!-- Campo observaciones -->
                                        <div>
                                            <label for="observaciones" class="block text-sm font-medium text-gray-300 mb-2">
                                                Detalles y observaciones <span class="text-red-500">*</span>
                                            </label>
                                            <textarea wire:model="observaciones" id="observaciones" rows="5"
                                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md shadow-sm 
                                                            focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-white"
                                                    placeholder="Describa los detalles del seguimiento..."></textarea>
                                            @error('observaciones') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Botones -->
                                    <div class="flex justify-end space-x-4 mt-6 pt-4 border-t border-gray-700">
                                        <button type="button" wire:click="closeModal"
                                                class="px-4 py-2 border border-gray-600 rounded-md text-gray-300 bg-gray-700 hover:bg-gray-600">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                            Guardar Seguimiento
                                        </button>
                                    </div>
                                </form>
                                @endif
                            </div>
                        </div>
                        @endif
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
