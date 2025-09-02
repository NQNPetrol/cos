<div>
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="bg-gray-900 text-gray-100 p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Gestion de Tickets</h2>
            <div class="flex items-center space-x-4">
                <select wire:model="statusFilter" class="form-select bg-gray-800 border-gray-700 text-gray-100">
                    <option value="">Todos los estados</option>
                    <option value="abierto">Abiertos</option>
                    <option value="en_proceso">En proceso</option>
                    <option value="cerrado">Cerrados</option>
                    <option value="resuelto">Resueltos</option>
                </select>
                <button wire:click="openModal" 
                        class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded text-white font-medium flex items-center">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Crear Ticket
                </button>
            </div>
        </div>
        <!-- Formulario para crear/editar tickets -->
        @if ($showModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
                <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl">
                    <div class="border-b border-gray-700 px-6 py-4">
                        <h3 class="text-xl font-semibold text-white">
                            {{ $editMode ? 'Editar Ticket' : 'Crear Nuevo Ticket' }}
                        </h3>
                    </div>
                    <div class="p-6">
                        <form wire:submit.prevent="{{ $editMode ? 'update' : 'store' }}">
                            <div class="mb-4">
                                <label for="titulo" class="block text-sm font-medium text-gray-300 mb-2">Título</label>
                                <input type="text" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500" id="titulo" wire:model="titulo">
                                @error('titulo') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4">
                                <label for="descripcion" class="block text-sm font-medium text-gray-300 mb-2">Descripción</label>
                                <textarea class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500" id="descripcion" rows="4" wire:model="descripcion"></textarea>
                                @error('descripcion') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-6">
                                <label for="prioridad" class="block text-sm font-medium text-gray-300 mb-2">Prioridad</label>
                                <select class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-green-500" id="prioridad" wire:model="prioridad">
                                    <option value="baja">Baja</option>
                                    <option value="media">Media</option>
                                    <option value="alta">Alta</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                                @error('prioridad') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button type="button" class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-md text-white" wire:click="closeModal">Cancelar</button>
                                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-500 rounded-md text-white">
                                    {{ $editMode ? 'Actualizar' : 'Crear' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Lista de tickets -->
        <div class="bg-gray-800 rounded-lg shadow">
            <div class="p-6">
                @if ($tickets->count())
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="bg-gray-700 text-gray-300">
                                    <th class="px-4 py-3 text-left">Título</th>
                                    <th class="px-4 py-3 text-left">Prioridad</th>
                                    <th class="px-4 py-3 text-left">Estado</th>
                                    <th class="px-4 py-3 text-left">Creado por</th>
                                    <th class="px-4 py-3 text-left">Fecha creación</th>
                                    <th class="px-4 py-3 text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach ($tickets as $ticket)
                                    <tr class="hover:bg-gray-750">
                                        <td class="px-4 py-3 text-white">{{ $ticket->titulo }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                                @if($ticket->prioridad == 'baja') bg-secondary
                                                @elseif($ticket->prioridad == 'media') bg-info
                                                @elseif($ticket->prioridad == 'alta') bg-warning
                                                @elseif($ticket->prioridad == 'urgente') bg-danger
                                                @endif">
                                                {{ ucfirst($ticket->prioridad) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                                @if($ticket->estado == 'abierto') bg-success
                                                @elseif($ticket->estado == 'en_proceso') bg-primary
                                                @elseif($ticket->estado == 'cerrado') bg-secondary
                                                @elseif($ticket->estado == 'resuelto') bg-info
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $ticket->estado)) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-white">{{ $ticket->user->name }}</td>
                                        <td class="px-4 py-3 text-gray-400">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex space-x-2">
                                                <button wire:click="edit({{ $ticket->id }})" class="p-2 bg-blue-600 hover:bg-blue-500 rounded-md text-white" title="Editar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                                
                                                <div class="relative">
                                                    <button class="p-2 bg-gray-600 hover:bg-gray-500 rounded-md text-white" title="Opciones">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                        </svg>
                                                    </button>
                                                    <div class="absolute right-0 mt-1 w-48 bg-gray-800 border border-gray-700 rounded-md shadow-lg opacity-0 invisible transition-all duration-300 group-hover:opacity-100 group-hover:visible">
                                                        <div class="py-1">
                                                            <button wire:click="updateStatus({{ $ticket->id }}, 'abierto')" class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-700">
                                                                Marcar como Abierto
                                                            </button>
                                                            <button wire:click="updateStatus({{ $ticket->id }}, 'en_proceso')" class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-700">
                                                                Marcar como en proceso
                                                            </button>
                                                            <button wire:click="updateStatus({{ $ticket->id }}, 'resulto')" class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-700">
                                                                Marcar como resuelto
                                                            </button>
                                                            <button wire:click="updateStatus({{ $ticket->id }}, 'cerrado')" class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-700">
                                                                Marcar como cerradp
                                                            </button>
                                                            <hr class="border-gray-700">
                                                            <button onclick="confirm('¿Estás seguro?') || event.stopImmediatePropagation()" 
                                                                    wire:click="delete({{ $ticket->id }})"
                                                                    class="block w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700">
                                                                Eliminar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $tickets->links() }}
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p>No hay tickets para mostrar.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>