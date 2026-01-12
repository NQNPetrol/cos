<div class="bg-zinc-900 rounded-lg">
    <div>
        <!-- Notificaciones -->
        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-green-900/30 border border-green-800 text-green-400 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('warning'))
            <div class="mb-6 p-4 bg-yellow-900/30 border border-yellow-800 text-yellow-400 rounded-lg">
                {{ session('warning') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-900/30 border border-red-800 text-red-400 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Contenedor del Formulario de Asignación -->
        <div class="bg-zinc-800 rounded-lg p-6 mb-6 border border-zinc-600">
            <!-- Formulario de Asignación -->
            <div class="rounded-lg">
                <form wire:submit.prevent="asignarPiloto" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Selección de Piloto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Piloto</label>
                        <select wire:model="pilotoSeleccionado" 
                                class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">Seleccionar Piloto</option>
                            @foreach($pilotos as $piloto)
                                <option value="{{ $piloto->id }}">
                                    {{ $piloto->nombre }} 
                                    @if($piloto->user)
                                        ({{ $piloto->user->name }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('pilotoSeleccionado')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                

                    <!-- Selección de Cliente -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Cliente</label>
                        <select wire:model="clienteSeleccionado" 
                                class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">Seleccionar Cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">
                                    {{ $cliente->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('clienteSeleccionado')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botón -->
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors duration-200">
                            Asignar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Contenedor de la Carta de Asignaciones Actuales -->
         <div class="bg-zinc-800 rounded-lg border border-zinc-600">
            <!-- Lista de Asignaciones -->
            <div class="rounded-lg">
                <div class="flex items-center justify-between p-6 border-b border-zinc-600">
                    <h3 class="text-lg font-semibold text-gray-100">
                        Asignaciones Actuales
                    </h3>
                    
                    <!-- Controles de Paginación -->
                    @if($totalPaginas > 1)
                    <div class="flex items-center space-x-2">
                        <button wire:click="paginaAnterior" 
                                wire:loading.attr="disabled"
                                class="p-2 rounded-md bg-zinc-700 hover:bg-zinc-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                {{ $paginaActual === 1 ? 'disabled' : '' }}>
                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        
                        <span class="text-sm text-gray-400 mx-2">
                            {{ $paginaActual }} de {{ $totalPaginas }}
                        </span>
                        
                        <button wire:click="paginaSiguiente"
                                wire:loading.attr="disabled"
                                class="p-2 rounded-md bg-zinc-700 hover:bg-zinc-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                {{ $paginaActual === $totalPaginas ? 'disabled' : '' }}>
                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                    @endif
                </div>

                @if(count($asignaciones) > 0)
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            @foreach($pilotosPaginados as $piloto)
                                @if(isset($asignaciones[$piloto->id]) && count($asignaciones[$piloto->id]) > 0)
                                    <div class="bg-zinc-750 rounded-lg border border-zinc-600 p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <div>
                                                <h4 class="text-base font-medium text-gray-100">
                                                    {{ $piloto->nombre }}
                                                    @if($piloto->user)
                                                        <span class="text-gray-400 text-xs">({{ $piloto->user->name }})</span>
                                                    @endif
                                                </h4>
                                                <p class="text-gray-400 text-xs mt-1">Token: {{ Str::limit($piloto->token, 20) }}</p>
                                            </div>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-900/30 text-blue-400 border border-blue-800">
                                                {{ count($asignaciones[$piloto->id]) }} cliente(s)
                                            </span>
                                        </div>

                                        <div class="space-y-2">
                                            @foreach($asignaciones[$piloto->id] as $clienteId)
                                                @php
                                                    $cliente = $clientes->firstWhere('id', $clienteId);
                                                @endphp
                                                @if($cliente)
                                                    <div class="flex items-center justify-between bg-zinc-700 rounded-lg p-2 border border-zinc-600">
                                                        <span class="text-sm text-gray-300 font-medium">{{ $cliente->nombre }}</span>
                                                        <button type="button" 
                                                                wire:click="eliminarAsignacion({{ $piloto->id }}, {{ $clienteId }})"
                                                                wire:confirm="¿Estás seguro de que deseas eliminar esta asignación?"
                                                                class="text-red-400 hover:text-red-300 transition-colors duration-200 p-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="p-8 text-center">
                        <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <h4 class="text-base font-medium text-gray-400 mb-2">No hay asignaciones</h4>
                        <p class="text-gray-500 text-sm">Comienza asignando pilotos a clientes usando el formulario superior.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>