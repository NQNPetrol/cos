<div class="bg-gray-900 text-gray-100 p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Flotas Vehiculares</h2>
    </div>

    <!-- Filtros -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm mb-1">Buscar</label>
            <input type="text" wire:model.live="search"
                   placeholder="Patente, marca, modelo..."
                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
        </div>
        
        <div>
            <label class="block text-sm mb-1">Estado</label>
            <select wire:model.live="estadoFilter" 
                    class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                <option value="">Todos</option>
                <option value="operativa">Operativa</option>
                <option value="mantenimiento">En mantenimiento</option>
                <option value="baja">Dada de baja</option>
            </select>
        </div>
    </div>

    <!-- Tabla -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-800 text-gray-300">
                <tr>
                    <th class="px-4 py-2 text-left">DOMINIO</th>
                    <th class="px-4 py-2 text-left">MARCA</th>
                    <th class="px-4 py-2 text-left">MODELO</th>
                    <th class="px-4 py-2 text-left">AÑO</th>
                    <th class="px-4 py-2 text-left">ESTADO</th>
                    <th class="px-4 py-2 text-left">OBJETIVO/SERVICIO</th>
                    <th class="px-4 py-2 text-left">OBSERVACION</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($patrullas as $patrulla)
                    <tr class="border-b border-gray-700 hover:bg-gray-800">
                        <td class="px-4 py-2 font-medium">{{ $patrulla->patente }}</td>
                        <td class="px-4 py-2">{{ $patrulla->marca }}</td>
                        <td class="px-4 py-2">{{ $patrulla->modelo }}</td>   
                        <td class="px-4 py-2">{{ $patrulla->año ?? 'N/A'}}</td>
                        <td class="px-4 py-2">
                            @if ($editingEstadoId === $patrulla->id)
                                <!-- Modo edición -->
                                <div class="flex items-center space-x-2">
                                    <select wire:model="nuevoEstado" 
                                            class="bg-gray-800 border border-gray-600 rounded px-2 py-1 text-sm text-gray-200">
                                        <option value="operativa">Operativa</option>
                                        <option value="disponible">Disponible</option>
                                        <option value="en mantenimiento">En mantenimiento</option>
                                    </select>
                                    <button wire:click="guardarEstado({{ $patrulla->id }})"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs">
                                        Guardar
                                    </button>
                                    <button wire:click="cancelarEdicion"
                                            class="bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 rounded text-xs">
                                        Cancelar
                                    </button>
                                </div>
                            @else
                                <!-- Modo visualización -->
                                <div class="flex items-center gap-1">
                                    <span class="{{ 
                                        $patrulla->estado === 'operativa' ? 'text-green-400' : 
                                        ($patrulla->estado === 'disponible' ? 'text-blue-400' : 
                                        'text-yellow-400')
                                    }}">
                                        {{ ucfirst($patrulla->estado) }}
                                    </span>
                                    <button wire:click="iniciarEdicionEstado({{ $patrulla->id }}, '{{ $patrulla->estado }}')"
                                            class="text-gray-400 hover:text-gray-300 transition-colors"
                                            title="Editar estado">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if ($editingObjetivoId === $patrulla->id)
                                <div class="flex items-center space-x-2">
                                    <input type="text" 
                                           wire:model="nuevoObjetivo"
                                           class="bg-gray-800 border border-gray-600 rounded px-2 py-1 text-sm text-gray-200 w-32"
                                           placeholder="Objetivo/Servicio">
                                    <button wire:click="guardarObjetivo({{ $patrulla->id }})"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs">
                                        Guardar
                                    </button>
                                    <button wire:click="cancelarEdicionObjetivo"
                                            class="bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 rounded text-xs">
                                        Cancelar
                                    </button>
                                </div>
                            @else
                                <div class="flex items-center gap-1">
                                    <span class="max-w-xs truncate">
                                        {{ $patrulla->ultimoRegistroFlota->objetivo_servicio ?? 'N/A' }}
                                    </span>
                                    <button wire:click="iniciarEdicionObjetivo({{ $patrulla->id }}, '{{ $patrulla->ultimoRegistroFlota->objetivo_servicio ?? '' }}')"
                                            class="text-gray-400 hover:text-gray-300 transition-colors flex-shrink-0"
                                            title="Editar objetivo/servicio">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if ($editingObservacionId === $patrulla->id)
                                <div class="flex items-center space-x-2">
                                    <input type="text" 
                                           wire:model="nuevaObservacion"
                                           class="bg-gray-800 border border-gray-600 rounded px-2 py-1 text-sm text-gray-200 w-32"
                                           placeholder="Observación">
                                    <button wire:click="guardarObservacion({{ $patrulla->id }})"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs">
                                        Guardar
                                    </button>
                                    <button wire:click="cancelarEdicionObservacion"
                                            class="bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 rounded text-xs">
                                        Cancelar
                                    </button>
                                </div>
                            @else
                                <div class="flex items-center gap-1">
                                    <span class="max-w-xs truncate">
                                        {{ $patrulla->ultimoRegistroFlota->observacion ?? 'N/A' }}
                                    </span>
                                    <button wire:click="iniciarEdicionObservacion({{ $patrulla->id }}, '{{ $patrulla->ultimoRegistroFlota->observacion ?? '' }}')"
                                            class="text-gray-400 hover:text-gray-300 transition-colors flex-shrink-0"
                                            title="Editar observación">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </td>

                       
                        <td class="px-4 py-2">
                            <div class="flex space-x-3">
                                <a href="{{ route('client.patrullas.location') }}" 
                                   class="text-orange-400 hover:text-orange-300"
                                   title="Ubicacion">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pin-map" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M3.1 11.2a.5.5 0 0 1 .4-.2H6a.5.5 0 0 1 0 1H3.75L1.5 15h13l-2.25-3H10a.5.5 0 0 1 0-1h2.5a.5.5 0 0 1 .4.2l3 4a.5.5 0 0 1-.4.8H.5a.5.5 0 0 1-.4-.8z"/>
                                        <path fill-rule="evenodd" d="M8 1a3 3 0 1 0 0 6 3 3 0 0 0 0-6M4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999z"/>
                                    </svg>
                                </a>
                                <button wire:click="abrirModal({{ $patrulla->id }})" 
                                        class="text-gray-400 hover:text-gray-300 transition-colors"
                                        title="Todos los datos">
                                    <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="18"
                                    height="18"
                                    viewBox="0 0 18 18"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    >
                                        <path d="M20 6l-11 0" />
                                        <path d="M20 12l-7 0" />
                                        <path d="M20 18l-11 0" />
                                        <path d="M4 8l4 4l-4 4" />
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <p>No se encontraron patrullas</p>
                                <p class="text-sm">Intenta ajustar los filtros o agrega una nueva patrulla</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $patrullas->links() }}
    </div>

    <!-- Mensajes de éxito/error -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-transition
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif
        <!-- Mensajes de éxito/error -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-transition
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    <!-- Modal -->
    @if($mostrarModal && $patrullaSeleccionada)
        <div class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-4 z-50">
            <div class="bg-gray-700 rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] overflow-hidden">
                <!-- Header del Modal -->
                <div class="bg-gray-900 px-6 py-4 border-b border-gray-700">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold text-gray-100">
                            PATRULLA {{ $patrullaSeleccionada->patente }}
                        </h2>
                        <button wire:click="cerrarModal" 
                                class="text-gray-400 hover:text-gray-300 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-gray-400 mt-1">
                        {{ $patrullaSeleccionada->marca }} {{ $patrullaSeleccionada->modelo }} - {{ $patrullaSeleccionada->año }}
                    </p>
                </div>

                <!-- Contenido del Modal -->
                <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                    <div class="mt-6 bg-gray-900 rounded-lg p-4">
                        <!-- - Sistemas -->
                        <div class="bg-gray-900 rounded-lg p-4">
                            @livewire('flotas-vehiculares.sistema-patrulla-listado', ['patrullaId' => $patrullaSeleccionada->id])
                        </div>
                    </div>

                    <!-- Documental -->
                    <div class="mt-6 bg-gray-900 rounded-lg p-4">
                            <div class="bg-gray-900 rounded-lg p-4">
                                @livewire('flotas-vehiculares.documentacion-patrulla-listado', ['patrullaId' => $patrullaSeleccionada->id])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>