<div class="bg-gray-900 text-gray-100 p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">Dispositivos asignados a {{ $patrulla->patente }}</h2>
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
                <div class="mb-4">
                    <input type="text" wire:model.debounce.300ms="search" 
                           placeholder="Buscar dispositivos..."
                           class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
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
                                <div class="ml-3">
                                    <div class="font-medium">{{ $dispositivo->tipo }} - {{ $dispositivo->modelo }}</div>
                                    <div class="text-sm text-gray-400">
                                        {{ $dispositivo->nombre }} | {{ $dispositivo->direccion_ip }}
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