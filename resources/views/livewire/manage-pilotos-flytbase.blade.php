<div>
    <!-- Notificaciones -->
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-900/30 border border-green-800 text-green-400 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-900/30 border border-red-800 text-red-400 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Listado de Pilotos -->
    <div class="bg-zinc-800 rounded-lg border border-zinc-700">
        <div class="flex justify-between items-center p-6 border-b border-zinc-700">
            <h3 class="text-lg font-semibold text-gray-100">Pilotos</h3>
            <button wire:click="openModal"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo
            </button>
        </div>

        @if($pilotosPaginados->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-zinc-750">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Token</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Clientes Asignados</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-zinc-800 divide-y divide-gray-700">
                        @foreach($pilotosPaginados as $piloto)
                        <tr class="hover:bg-zinc-750 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-300">#{{ $piloto->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $piloto->nombre }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                @if($piloto->user)
                                    {{ $piloto->user->name }}
                                @else
                                    <span class="text-gray-500">No asignado</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                @if($piloto->token)
                                    <span class="font-mono text-xs bg-zinc-700 px-2 py-1 rounded" title="{{ $piloto->token }}">
                                        {{ Str::limit($piloto->token, 20) }}
                                    </span>
                                @else
                                    <span class="text-gray-500">No configurado</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $piloto->clientes->count() > 0 ? 'bg-green-900/30 text-green-400' : 'bg-zinc-700 text-gray-400' }}">
                                    {{ $piloto->clientes->count() }} cliente(s)
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="editarPiloto({{ $piloto->id }})"
                                            class="text-gray-400 hover:text-gray-300 transition-colors p-1 rounded hover:bg-zinc-900/30"
                                            title="Editar piloto">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>
                                    <button wire:click="eliminarPiloto({{ $piloto->id }})"
                                            wire:confirm="¿Estás seguro de que deseas eliminar este piloto?"
                                            class="text-red-400 hover:text-red-300 transition-colors p-1 rounded hover:bg-red-900/30"
                                            title="Eliminar piloto">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($pilotosPaginados->hasPages())
            <div class="px-6 py-4 border-t border-zinc-700">
                {{ $pilotosPaginados->links() }}
            </div>
            @endif
        @else
            <div class="p-8 text-center">
                <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h4 class="text-lg font-medium text-gray-400 mb-2">No hay pilotos registrados</h4>
                <p class="text-gray-500">Comienza agregando un nuevo piloto usando el botón superior.</p>
            </div>
        @endif
    </div>

    <!-- Modal para Crear/Editar Piloto -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-zinc-800 rounded-lg p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-100">
                    {{ $editing ? 'Editar Piloto' : 'Crear Nuevo Piloto' }}
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form wire:submit.prevent="{{ $editing ? 'actualizarPiloto' : 'crearPiloto' }}">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Nombre del Piloto *</label>
                        <input type="text" 
                               wire:model="nombre" 
                               required 
                               class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                               placeholder="Ej: Juan Pérez">
                        @error('nombre') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Token</label>
                        <textarea wire:model="token" 
                                  rows="3"
                                  class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                  placeholder="Token de autenticación del piloto..."></textarea>
                        <p class="text-xs text-gray-400 mt-1">Token de autenticación para el piloto (opcional)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Usuario Asociado</label>
                        <select wire:model="user_id"
                                class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">Seleccione un usuario</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Asociar este piloto a un usuario del sistema (opcional)</p>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-zinc-700">
                    <button type="button" 
                            wire:click="closeModal"
                            class="px-4 py-2 bg-zinc-600 text-white rounded-md hover:bg-zinc-700 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        {{ $editing ? 'Actualizar' : 'Crear' }} Piloto
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>