<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- Notificaciones -->
                @if (session()->has('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session()->has('warning'))
                    <div class="mb-6 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
                        {{ session('warning') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Header -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">Asignar Pilotos a Clientes</h2>
                    <p class="text-gray-600 mt-2">Administra los pilotos encargados de volar misiones de clientes del sistema.</p>
                </div>

                <!-- Formulario de Asignación -->
                <div class="bg-gray-50 rounded-lg p-6 mb-8 border border-gray-200">

                    
                    <form wire:submit.prevent="asignarPiloto" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Selección de Piloto -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Piloto</label>
                            <select wire:model="pilotoSeleccionado" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Selección de Cliente -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cliente</label>
                            <select wire:model="clienteSeleccionado" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Seleccionar Cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">
                                        {{ $cliente->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('clienteSeleccionado')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botón -->
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors duration-200">
                                Asignar Piloto
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Lista de Asignaciones -->
                <div class="bg-white rounded-lg border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 p-6 border-b border-gray-200">
                        Asignaciones Actuales
                    </h3>

                    @if(count($asignaciones) > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($pilotos as $piloto)
                                @if(isset($asignaciones[$piloto->id]) && count($asignaciones[$piloto->id]) > 0)
                                    <div class="p-6">
                                        <div class="flex items-center justify-between mb-4">
                                            <div>
                                                <h4 class="text-lg font-medium text-gray-800">
                                                    {{ $piloto->nombre }}
                                                    @if($piloto->user)
                                                        <span class="text-gray-500 text-sm">({{ $piloto->user->name }})</span>
                                                    @endif
                                                </h4>
                                                <p class="text-gray-600 text-sm">Token: {{ Str::limit($piloto->token, 20) }}</p>
                                            </div>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                {{ count($asignaciones[$piloto->id]) }} cliente(s)
                                            </span>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                            @foreach($asignaciones[$piloto->id] as $clienteId)
                                                @php
                                                    $cliente = $clientes->firstWhere('id', $clienteId);
                                                @endphp
                                                @if($cliente)
                                                    <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3 border border-gray-200">
                                                        <span class="text-gray-700 font-medium">{{ $cliente->nombre }}</span>
                                                        <button type="button" 
                                                                wire:click="eliminarAsignacion({{ $piloto->id }}, {{ $clienteId }})"
                                                                wire:confirm="¿Estás seguro de que deseas eliminar esta asignación?"
                                                                class="text-red-600 hover:text-red-800 transition-colors duration-200">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    @else
                        <div class="p-8 text-center">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <h4 class="text-lg font-medium text-gray-600 mb-2">No hay asignaciones</h4>
                            <p class="text-gray-500">Comienza asignando pilotos a clientes usando el formulario superior.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>