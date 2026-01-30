<div>
    <!-- Contenedor 1: Título -->
    <div class="bg-[#252728] rounded-lg p-4 mb-4 border border-transparent">
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-100">Flotas Vehiculares</h2>
            @if(auth()->user()->hasAnyRole(['clientadmin', 'clientsupervisor']))
                <button wire:click="abrirModalCrear" 
                        class="bg-[#1877f2] hover:bg-[#0866ff] text-white px-3 py-1.5 rounded-lg text-xs flex items-center space-x-1 transition-colors">
                    <i class="bi bi-plus-circle"></i>
                    <span>Crear Patrulla</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Contenedor 2: Filtros -->
    <div class="bg-[#252728] rounded-lg p-4 mb-4 border border-transparent">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-200">Buscar</label>
                <input type="text" wire:model.live="search"
                       placeholder="Patente, marca, modelo..."
                       class="w-full bg-transparent border-zinc-300 rounded px-2 py-1.5 text-sm text-gray-200">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-200">Estado</label>
                <select wire:model.live="estadoFilter" 
                        class="w-full bg-transparent border-zinc-300 rounded px-2 py-1.5 text-sm text-gray-200">
                    <option value="">Todos</option>
                    <option value="operativa">Operativa</option>
                    <option value="disponible">Disponible</option>
                    <option value="en mantenimiento">En Mantenimiento</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Contenedor 3: Listado/Tabla -->
    <div class="bg-[#252728] rounded-lg p-4 border border-transparent">
        <table class="w-full text-xs">
            <thead class="bg-[#1a1d1f] text-gray-300">
                <tr>
                    <th class="px-2 py-2 text-left font-medium">DOMINIO</th>
                    <th class="px-2 py-2 text-left font-medium">MARCA</th>
                    <th class="px-2 py-2 text-left font-medium">MODELO</th>
                    <th class="px-2 py-2 text-left font-medium">AÑO</th>
                    <th class="px-2 py-2 text-left font-medium">ESTADO</th>
                    <th class="px-2 py-2 text-left font-medium">OBJETIVO</th>
                    <th class="px-2 py-2 text-left font-medium">OBSERVACIÓN</th>
                    <th class="px-2 py-2 text-center font-medium">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($patrullas as $patrulla)
                    <tr class="table-row-hover transition-colors">
                        <td class="px-2 py-1.5 font-medium text-gray-300">{{ $patrulla->patente }}</td>
                        <td class="px-2 py-1.5 text-gray-300">{{ $patrulla->marca }}</td>
                        <td class="px-2 py-1.5 text-gray-300">{{ $patrulla->modelo }}</td>   
                        <td class="px-2 py-1.5 text-gray-300">{{ $patrulla->año ?? 'N/A'}}</td>
                        <td class="px-2 py-1.5">
                            @if ($editingEstadoId === $patrulla->id)
                                <div class="flex items-center space-x-1">
                                    <select wire:model="nuevoEstado" 
                                            class="bg-zinc-800 border border-zinc-600 rounded px-1 py-0.5 text-xs text-gray-200">
                                        <option value="operativa">Operativa</option>
                                        <option value="disponible">Disponible</option>
                                        <option value="en mantenimiento">En mant.</option>
                                    </select>
                                    <button wire:click="guardarEstado({{ $patrulla->id }})"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-1.5 py-0.5 rounded text-[10px]">
                                        OK
                                    </button>
                                    <button wire:click="cancelarEdicion"
                                            class="bg-zinc-600 hover:bg-zinc-700 text-white px-1.5 py-0.5 rounded text-[10px]">
                                        X
                                    </button>
                                </div>
                            @else
                                <div class="flex items-center gap-1">
                                    <span class="font-medium text-xs {{ 
                                        $patrulla->estado === 'operativa' ? 'text-green-500' : 
                                        ($patrulla->estado === 'disponible' ? 'text-blue-500' : 
                                        'text-orange-500')
                                    }}">
                                        {{ ucfirst($patrulla->estado) }}
                                    </span>
                                    <button wire:click="iniciarEdicionEstado({{ $patrulla->id }}, '{{ $patrulla->estado }}')"
                                            class="text-gray-500 hover:text-gray-300 transition-colors"
                                            title="Editar estado">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </td>
                        <td class="px-2 py-1.5">
                            @if ($editingObjetivoId === $patrulla->id)
                                <div class="flex items-center space-x-1">
                                    <input type="text" 
                                           wire:model="nuevoObjetivo"
                                           class="bg-zinc-800 border border-zinc-600 rounded px-1 py-0.5 text-xs text-gray-200 w-20"
                                           placeholder="Objetivo">
                                    <button wire:click="guardarObjetivo({{ $patrulla->id }})"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-1.5 py-0.5 rounded text-[10px]">
                                        OK
                                    </button>
                                    <button wire:click="cancelarEdicionObjetivo"
                                            class="bg-zinc-600 hover:bg-zinc-700 text-white px-1.5 py-0.5 rounded text-[10px]">
                                        X
                                    </button>
                                </div>
                            @else
                                <div class="flex items-center gap-1">
                                    <span class="max-w-[80px] truncate text-gray-300" title="{{ $patrulla->ultimoRegistroFlota->objetivo_servicio ?? 'N/A' }}">
                                        {{ $patrulla->ultimoRegistroFlota->objetivo_servicio ?? 'N/A' }}
                                    </span>
                                    <button wire:click="iniciarEdicionObjetivo({{ $patrulla->id }}, '{{ $patrulla->ultimoRegistroFlota->objetivo_servicio ?? '' }}')"
                                            class="text-gray-500 hover:text-gray-300 transition-colors flex-shrink-0"
                                            title="Editar objetivo">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </td>
                        <td class="px-2 py-1.5">
                            @if ($editingObservacionId === $patrulla->id)
                                <div class="flex items-center space-x-1">
                                    <input type="text" 
                                           wire:model="nuevaObservacion"
                                           class="bg-zinc-800 border border-zinc-600 rounded px-1 py-0.5 text-xs text-gray-200 w-20"
                                           placeholder="Obs.">
                                    <button wire:click="guardarObservacion({{ $patrulla->id }})"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-1.5 py-0.5 rounded text-[10px]">
                                        OK
                                    </button>
                                    <button wire:click="cancelarEdicionObservacion"
                                            class="bg-zinc-600 hover:bg-zinc-700 text-white px-1.5 py-0.5 rounded text-[10px]">
                                        X
                                    </button>
                                </div>
                            @else
                                <div class="flex items-center gap-1">
                                    <span class="max-w-[80px] truncate text-gray-300" title="{{ $patrulla->ultimoRegistroFlota->observacion ?? 'N/A' }}">
                                        {{ $patrulla->ultimoRegistroFlota->observacion ?? 'N/A' }}
                                    </span>
                                    <button wire:click="iniciarEdicionObservacion({{ $patrulla->id }}, '{{ $patrulla->ultimoRegistroFlota->observacion ?? '' }}')"
                                            class="text-gray-500 hover:text-gray-300 transition-colors flex-shrink-0"
                                            title="Editar observación">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </td>
                        <td class="px-2 py-1.5">
                            <div class="flex items-center justify-center space-x-1">
                                @if(auth()->user()->hasAnyRole(['clientadmin', 'clientsupervisor']))
                                    <button wire:click="abrirModalEditar({{ $patrulla->id }})" 
                                            class="action-button relative"
                                            data-tooltip="Editar">
                                        <div class="w-7 h-7 rounded-full bg-amber-600 flex items-center justify-center transition-all hover:bg-amber-700">
                                            <i class="bi bi-pencil text-white" style="font-size: 12px;"></i>
                                        </div>
                                    </button>
                                @endif
                                <button wire:click="abrirModal({{ $patrulla->id }})" 
                                        class="action-button relative"
                                        data-tooltip="Documentación">
                                    <div class="w-7 h-7 rounded-full bg-[#1877f2] flex items-center justify-center transition-all hover:bg-[#0866ff]">
                                        <i class="bi bi-file-earmark-text text-white" style="font-size: 12px;"></i>
                                    </div>
                                </button>
                                <a href="{{ route('client.patrullas.location') }}" 
                                   class="action-button relative"
                                   data-tooltip="Ubicación">
                                    <div class="w-7 h-7 rounded-full bg-[#1877f2] flex items-center justify-center transition-all hover:bg-[#0866ff]">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="white" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M3.1 11.2a.5.5 0 0 1 .4-.2H6a.5.5 0 0 1 0 1H3.75L1.5 15h13l-2.25-3H10a.5.5 0 0 1 0-1h2.5a.5.5 0 0 1 .4.2l3 4a.5.5 0 0 1-.4.8H.5a.5.5 0 0 1-.4-.8z"/>
                                            <path fill-rule="evenodd" d="M8 1a3 3 0 1 0 0 6 3 3 0 0 0 0-6M4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999z"/>
                                        </svg>
                                    </div>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center px-2 py-6 text-gray-300">
                            <div class="flex flex-col items-center">
                                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm">No se encontraron patrullas</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $patrullas->links() }}
        </div>
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
            <div class="bg-[#252728] rounded-lg shadow-xl w-full max-w-4xl max-h-[80vh] overflow-hidden flex flex-col">
                <!-- Header del Modal -->
                <div class="bg-[#1a1d1f] px-4 py-3 border-b border-zinc-700 flex-shrink-0">
                    <div class="flex justify-between items-center">
                        <h2 class="text-base font-bold text-gray-200">
                            PATRULLA {{ $patrullaSeleccionada->patente }}
                        </h2>
                        <button wire:click="cerrarModal" 
                                class="text-gray-400 hover:text-gray-200 transition-colors p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ $patrullaSeleccionada->marca }} {{ $patrullaSeleccionada->modelo }} - {{ $patrullaSeleccionada->año }}
                    </p>
                </div>

                <!-- Contenido del Modal -->
                <div class="p-4 overflow-y-auto flex-1 custom-scrollbar">
                    <div class="mb-4">
                        @livewire('flotas-vehiculares.sistema-patrulla-listado', ['patrullaId' => $patrullaSeleccionada->id])
                    </div>

                    <!-- Documental -->
                    <div>
                        @livewire('flotas-vehiculares.documentacion-patrulla-listado', ['patrullaId' => $patrullaSeleccionada->id])
                    </div>
                </div>
            </div>
        </div>

        <style>
            .custom-scrollbar::-webkit-scrollbar {
                width: 10px;
            }
            
            .custom-scrollbar::-webkit-scrollbar-track {
                background: #252728;
                border-radius: 4px;
            }
            
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #3a3b3c;
                border-radius: 4px;
            }
            
            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #4a4b4c;
            }
            
            /* Para Firefox */
            .custom-scrollbar {
                scrollbar-width: thin;
                scrollbar-color: #3a3b3c #252728;
            }
        </style>
    @endif

    <!-- Modal Crear Patrulla -->
    @if($mostrarModalCrear)
        <div class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-4 z-50">
            <div class="bg-[#252728] rounded-lg shadow-xl w-full max-w-md max-h-[80vh] overflow-hidden flex flex-col">
                <!-- Header del Modal -->
                <div class="bg-[#1a1d1f] px-4 py-3 border-b border-zinc-700 flex-shrink-0">
                    <div class="flex justify-between items-center">
                        <h2 class="text-sm font-bold text-gray-200">
                            <i class="bi bi-plus-circle mr-2"></i> Crear Nueva Patrulla
                        </h2>
                        <button wire:click="cerrarModalCrear" 
                                class="text-gray-400 hover:text-gray-200 transition-colors p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Contenido del Modal -->
                <div class="p-4 overflow-y-auto flex-1 custom-scrollbar">
                    <form wire:submit.prevent="crearPatrulla">
                        <div class="space-y-3">
                            <!-- Patente -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-200 mb-1">Patente/Dominio *</label>
                                <input type="text" wire:model="patente"
                                       class="w-full bg-transparent border border-zinc-500 rounded px-2 py-1.5 text-gray-200 text-xs uppercase"
                                       placeholder="Ej: ABC123">
                                @error('patente')<span class="text-red-400 text-[10px]">{{ $message }}</span>@enderror
                            </div>

                            <!-- Marca y Modelo -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-200 mb-1">Marca *</label>
                                    <input type="text" wire:model="marca"
                                           class="w-full bg-transparent border border-zinc-500 rounded px-2 py-1.5 text-gray-200 text-xs"
                                           placeholder="Ej: Toyota">
                                    @error('marca')<span class="text-red-400 text-[10px]">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-200 mb-1">Modelo *</label>
                                    <input type="text" wire:model="modelo"
                                           class="w-full bg-transparent border border-zinc-500 rounded px-2 py-1.5 text-gray-200 text-xs"
                                           placeholder="Ej: Hilux">
                                    @error('modelo')<span class="text-red-400 text-[10px]">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <!-- Color y Año -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-200 mb-1">Color</label>
                                    <input type="text" wire:model="color"
                                           class="w-full bg-transparent border border-zinc-500 rounded px-2 py-1.5 text-gray-200 text-xs"
                                           placeholder="Ej: Blanco">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-200 mb-1">Año</label>
                                    <input type="number" wire:model="año"
                                           class="w-full bg-transparent border border-zinc-500 rounded px-2 py-1.5 text-gray-200 text-xs"
                                           placeholder="2023" min="1900" max="2100">
                                </div>
                            </div>

                            <!-- Estado -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-200 mb-1">Estado *</label>
                                <select wire:model="estado"
                                        class="w-full bg-transparent border border-zinc-500 rounded px-2 py-1.5 text-gray-200 text-xs modal-select">
                                    <option value="disponible">Disponible</option>
                                    <option value="operativa">Operativa</option>
                                    <option value="en mantenimiento">En Mantenimiento</option>
                                </select>
                            </div>

                            <!-- Observaciones -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-200 mb-1">Observaciones</label>
                                <textarea wire:model="observaciones"
                                          class="w-full bg-transparent border border-zinc-500 rounded px-2 py-1.5 text-gray-200 text-xs"
                                          rows="2"
                                          placeholder="Observaciones..."></textarea>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end space-x-2 mt-4 pt-3 border-t border-zinc-700">
                            <button type="button" wire:click="cerrarModalCrear"
                                    class="bg-zinc-700 hover:bg-zinc-600 text-white px-3 py-1.5 rounded text-xs transition-colors">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="bg-[#1877f2] hover:bg-[#0866ff] text-white px-3 py-1.5 rounded text-xs transition-colors flex items-center space-x-1">
                                <i class="bi bi-check-circle"></i>
                                <span>Crear</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Editar Patrulla -->
    @if($mostrarModalEditar && $patrullaEditar)
        <div class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-4 z-50">
            <div class="bg-[#252728] rounded-lg shadow-xl w-full max-w-md max-h-[80vh] overflow-hidden flex flex-col">
                <!-- Header del Modal -->
                <div class="bg-[#1a1d1f] px-4 py-3 border-b border-zinc-700 flex-shrink-0">
                    <div class="flex justify-between items-center">
                        <h2 class="text-sm font-bold text-gray-200">
                            <i class="bi bi-pencil mr-2"></i> Editar Patrulla
                        </h2>
                        <button wire:click="cerrarModalEditar" 
                                class="text-gray-400 hover:text-gray-200 transition-colors p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $patrullaEditar->patente }}</p>
                </div>

                <!-- Contenido del Modal -->
                <div class="p-4 overflow-y-auto flex-1 custom-scrollbar">
                    <form wire:submit.prevent="actualizarPatrulla">
                        <div class="space-y-3">
                            <!-- Patente -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-200 mb-1">Patente/Dominio *</label>
                                <input type="text" wire:model="patente"
                                       class="w-full bg-transparent border border-zinc-500 rounded px-2 py-1.5 text-gray-200 text-xs uppercase"
                                       placeholder="Ej: ABC123">
                                @error('patente')<span class="text-red-400 text-[10px]">{{ $message }}</span>@enderror
                            </div>

                            <!-- Marca y Modelo -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-200 mb-1">Marca *</label>
                                    <input type="text" wire:model="marca"
                                           class="w-full bg-transparent border border-zinc-500 rounded px-2 py-1.5 text-gray-200 text-xs"
                                           placeholder="Ej: Toyota">
                                    @error('marca')<span class="text-red-400 text-[10px]">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-200 mb-1">Modelo *</label>
                                    <input type="text" wire:model="modelo"
                                           class="w-full bg-transparent border border-zinc-500 rounded px-2 py-1.5 text-gray-200 text-xs"
                                           placeholder="Ej: Hilux">
                                    @error('modelo')<span class="text-red-400 text-[10px]">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <!-- Color y Año -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-200 mb-1">Color</label>
                                    <input type="text" wire:model="color"
                                           class="w-full bg-transparent border border-zinc-500 rounded px-2 py-1.5 text-gray-200 text-xs"
                                           placeholder="Ej: Blanco">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-200 mb-1">Año</label>
                                    <input type="number" wire:model="año"
                                           class="w-full bg-transparent border border-zinc-500 rounded px-2 py-1.5 text-gray-200 text-xs"
                                           placeholder="2023" min="1900" max="2100">
                                </div>
                            </div>

                            <!-- Estado -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-200 mb-1">Estado *</label>
                                <select wire:model="estado"
                                        class="w-full bg-transparent border border-zinc-500 rounded px-2 py-1.5 text-gray-200 text-xs modal-select">
                                    <option value="disponible">Disponible</option>
                                    <option value="operativa">Operativa</option>
                                    <option value="en mantenimiento">En Mantenimiento</option>
                                </select>
                            </div>

                            <!-- Observaciones -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-200 mb-1">Observaciones</label>
                                <textarea wire:model="observaciones"
                                          class="w-full bg-transparent border border-zinc-500 rounded px-2 py-1.5 text-gray-200 text-xs"
                                          rows="2"
                                          placeholder="Observaciones..."></textarea>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end space-x-2 mt-4 pt-3 border-t border-zinc-700">
                            <button type="button" wire:click="cerrarModalEditar"
                                    class="bg-zinc-700 hover:bg-zinc-600 text-white px-3 py-1.5 rounded text-xs transition-colors">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="bg-amber-600 hover:bg-amber-700 text-white px-3 py-1.5 rounded text-xs transition-colors flex items-center space-x-1">
                                <i class="bi bi-check-circle"></i>
                                <span>Guardar</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Estilos para botones de acción y scrollbar -->
    <style>
        .action-button {
            position: relative;
            display: inline-block;
        }

        /* Tooltip para botones de acción */
        .action-button[data-tooltip]::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-bottom: 8px;
            padding: 6px 10px;
            background-color: #d1d5db;
            color: #1c1e21;
            font-size: 13px;
            font-weight: 400;
            white-space: nowrap;
            border-radius: 8px;
            pointer-events: none;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.1s ease 0.05s, visibility 0.1s ease 0.05s;
            z-index: 10000;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .action-button[data-tooltip]:hover::after {
            opacity: 1;
            visibility: visible;
        }

        /* Flecha del tooltip (apunta hacia abajo) */
        .action-button[data-tooltip]::before {
            content: '';
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-bottom: 2px;
            border: 5px solid transparent;
            border-top-color: #d1d5db;
            pointer-events: none;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.1s ease 0.05s, visibility 0.1s ease 0.05s;
            z-index: 10001;
        }

        .action-button[data-tooltip]:hover::before {
            opacity: 1;
            visibility: visible;
        }

        /* Scrollbar personalizado para la tabla */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #252728;
            border-radius: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #3a3b3c;
            border-radius: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #4a4b4c;
        }

        /* Para Firefox */
        .overflow-x-auto {
            scrollbar-width: thin;
            scrollbar-color: #3a3b3c #252728;
        }

        /* Línea más fina y hover para filas de tabla */
        .table-row-hover {
            border-bottom: 0.1px solid #e5e7eb !important;
        }

        .table-row-hover:hover {
            background-color: #1f2937 !important;
        }

        .table-row-hover:hover td {
            color: #e5e7eb !important;
        }

        /* Estilos para filas de tablas en el modal */
        .table-row-hover-modal {
            border-bottom: 0.1px solid #e5e7eb !important;
        }

        .table-row-hover-modal:hover {
            background-color: #1f2937 !important;
        }

        .table-row-hover-modal:hover td {
            color: #e5e7eb !important;
        }

        /* Estilos para opciones de dropdowns en el modal */
        .modal-select option {
            background-color:rgb(195, 201, 211);
            color:rgb(22, 27, 35);
        }
    </style>
</div>