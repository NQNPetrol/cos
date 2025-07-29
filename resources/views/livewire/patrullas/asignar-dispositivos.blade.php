<div class="bg-gray-900 text-gray-100 p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">Listado de dispositivos</h2>
        <button wire:click="openModal"
                class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded text-white font-medium">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Asignar Dispositivos
        </button>
    </div>

    <!-- Tabla de dispositivos asignados -->
    <div class="overflow-x-auto mb-6">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-800 text-gray-300">
                <tr>
                    <th class="px-4 py-2 text-left">Dispositivo</th>
                    <th class="px-4 py-2 text-left">Tipo</th>
                    <th class="px-4 py-2 text-left">Modelo</th>
                    <th class="px-4 py-2 text-left">Fecha Asignación</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($asignaciones as $asignacion)
                    <tr class="border-b border-gray-700 hover:bg-gray-800">
                        <td class="px-4 py-2">
                            {{ $asignacion->dispositivo->nombre }}
                            <div class="text-xs text-gray-400">
                                {{ $asignacion->dispositivo->direccion_ip }}
                            </div>
                        </td>
                        <td class="px-4 py-2">{{ $asignacion->dispositivo->tipo }}</td>
                        <td class="px-4 py-2">{{ $asignacion->dispositivo->modelo }}</td>
                        <td class="px-4 py-2">{{ $asignacion->fecha_asignacion->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">
                            <button wire:click="eliminarAsignacion({{ $asignacion->dispositivo_id }})"
                                    onclick="return confirm('¿Desvincular este dispositivo?')"
                                    class="text-red-400 hover:text-red-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-400">
                            No hay dispositivos asignados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $asignaciones->links() }}
    </div>

    <!-- Modal para asignar dispositivos -->
    @if($showModal)
        <div x-data
             x-show="$wire.showModal"
             x-onkeydown.escape.window="$wire.closeModal()"
             x-on:close-modal.window="$wire.showModal() = false"
             wire:click.self="closeModal" 
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-900 rounded-lg p-6 w-full max-w-3xl max-h-[90vh] overflow-y-auto" @click.stop>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Asignar Dispositivos a {{ $patrulla->patente }}</h3>
                    <button wire:click="showModal = false" class="text-gray-400 hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Buscador -->
                <div class="mb-4 relative">
                <input type="text" wire:model.live.debounce.300ms="search" 
                       placeholder="Buscar por ID, tipo o cliente..."
                       class="w-full bg-gray-800 border border-gray-700 rounded px-3 py-2 text-gray-200 pl-10">
                <div class="absolute left-3 top-2.5 text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div wire:loading wire:target="search" class="absolute right-3 top-2.5">
                    <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
                <!-- Lista de dispositivos -->
                <form wire:submit.prevent="asignarDispositivos">
                    <div class="space-y-2 max-h-96 overflow-y-auto mb-4">
                        @forelse($dispositivosDisponibles as $dispositivo)
                            <label class="flex items-center p-2 hover:bg-gray-800 rounded cursor-pointer">
                                <input type="checkbox" 
                                       wire:model="selectedDispositivos"
                                       value="{{ $dispositivo->id }}"
                                       class="rounded bg-gray-800 border-gray-700 text-green-500">
                                <div class="ml-3 w-full">
                                    <span class="text-xs bg-green-600 px-2 py-1 rounded">{{ $dispositivo->id }}</span>

                                    <div class="text-green-400">
                                        @if($dispositivo->tipo)
                                        {{ $dispositivo->tipo }}
                                        @else
                                        Sin tipo
                                        @endif
                                    </div>

                                    <div class="font-medium">
                                        @if($dispositivo->cliente)
                                        Cliente: {{ $dispositivo->cliente->nombre }}
                                        @else
                                        Cliente: No asignado
                                        @endif
                                    </div>

                                    <div class="font-regular">
                                        @if($dispositivo->observaciones)
                                        {{ $dispositivo->observaciones }}
                                        @else
                                        Sin observaciones
                                        @endif
                                    </div>
                                    
                                </div>
                            </label>
                        @empty
                            <p class="text-gray-400 p-4 text-center">No se encontraron dispositivos disponibles</p>
                        @endforelse
                    </div>

                    <!-- Campos adicionales -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm mb-1">Fecha de Asignación</label>
                            <input type="date" wire:model="fechaAsignacion"
                                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Observaciones</label>
                            <input type="text" wire:model="observaciones"
                                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-4 pt-4 border-t border-gray-700">
                        <button type="button" wire:click="showModal = false"
                                class="px-4 py-2 text-gray-300 hover:text-gray-100">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="bg-green-600 hover:bg-green-700 px-6 py-2 rounded text-white font-medium"
                                @if(count($selectedDispositivos) === 0) disabled @endif>
                            Asignar Dispositivos
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>