
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-gray-100">
                <div class="text-gray-100">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="p-3 bg-gradient-to-br from-blue-600/20 to-blue-400/10 rounded-xl border border-blue-500/20">
                            <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">{{ $header }}</h1>
                            <p class="text-gray-400 text-sm">Seguimiento de eventos registrados</p>
                        </div>
                    </div>
                    
                    <!-- Filtros Livewire -->
                    <div class="mb-5 p-4 bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50 grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Filtro por estado -->
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Estado</label>
                                <select wire:model.live="estadoFilter" 
                                        class="w-full rounded-lg bg-zinc-900 border border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                                    <option value="">Todos</option>
                                    <option value="ABIERTO">Abierto</option>
                                    <option value="EN REVISION">En Revisión</option>
                                    <option value="CERRADO">Cerrado</option>
                                </select>
                            </div>

                            <!-- Tipo de evento -->

                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Tipo de Evento</label>
                                <input type="text" wire:model.live.debounce.500ms="tipoFilter"
                                       placeholder="Filtrar por tipo..."
                                       class="w-full rounded-lg bg-zinc-900 border border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                            </div>
                            
                            <!-- filtro de búsqueda -->
                             <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Buscar</label>
                                <input type="text" wire:model.live.debounce.500ms="search"
                                       placeholder="#ID, estado, tipo ..."
                                       class="w-full rounded-lg bg-zinc-900 border border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                            </div>

                            <!-- Botones -->
                            <div class="flex items-end space-x-2">
                                <button wire:click="clearFilters" 
                                        class="px-4 py-2 bg-zinc-700 hover:bg-zinc-600 text-gray-300 rounded-xl text-sm transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Limpiar Filtros
                                </button>
                            </div>
                    </div>
                    
                    <!-- Tabla de resultados -->
                    <div class="bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50 overflow-hidden shadow-xl">
                        <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider cursor-pointer" wire:click="sortBy('fecha')">
                                        Fecha
                                        @if($sortField === 'fecha_registro')
                                            @if($sortDirection === 'asc')
                                                ↑
                                            @else
                                                ↓
                                            @endif
                                        @endif
                                    </th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider cursor-pointer" wire:click="sortBy('id')">
                                        Evento
                                        @if($sortField === 'id')
                                            @if($sortDirection === 'asc')
                                                ↑
                                            @else
                                                ↓
                                            @endif
                                        @endif
                                    </th>
        
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider cursor-pointer" wire:click="sortBy('estado')">
                                        Estado
                                        @if($sortField === 'estado')
                                            @if($sortDirection === 'asc')
                                                ↑
                                            @else
                                                ↓
                                            @endif
                                        @endif
                                    </th>
                                    
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider cursor-pointer" wire:click="sortBy('tipo')">
                                        Tipo de Evento
                                        @if($sortField === 'tipo')
                                            @if($sortDirection === 'asc')
                                                ↑
                                            @else
                                                ↓
                                            @endif
                                        @endif
                                    </th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider cursor-pointer" wire:click="sortBy('tipo')">
                                        Observaciones
                                    </th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider cursor-pointer" wire:click="sortBy('tipo')">
                                        Usuario encargado
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-700/30">
                                @forelse ($seguimientos as $seguimiento)
                                <tr>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $seguimiento->fecha->format('d/m/Y') }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-300">
                                        Evento #{{ $seguimiento->evento->id ?? 'N/A' }}
                                        @if($seguimiento->evento)
                                            <div class="text-xs text-gray-500">{{ $seguimiento->evento->nombre }}</div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold 
                                            {{ $seguimiento->estado == 'CERRADO' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($seguimiento->estado == 'EN REVISION' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20') }}">
                                            {{ $seguimiento->estado }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $seguimiento->evento->tipo ?? 'N/A' }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $seguimiento->observaciones ?? 'N/A' }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-300">
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
                        <div class="px-5 py-3 border-t border-zinc-700/50">{{ $seguimientos->links() }}</div>
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

