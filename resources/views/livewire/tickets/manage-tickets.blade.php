<div>
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-800 border border-green-600 text-green-100 rounded-lg flex items-center justify-between shadow-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
            <button type="button" class="text-green-300 hover:text-white" onclick="this.parentElement.style.display='none'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-800 border border-red-600 text-red-100 rounded-lg flex items-center justify-between shadow-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" class="text-red-300 hover:text-white" onclick="this.parentElement.style.display='none'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    <div class="bg-gray-900 text-gray-100 p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Gestion de Tickets</h2>
            <div class="flex items-center space-x-4">
                <!-- Filtros -->
                <select wire:model.live="statusFilter" class="form-select bg-gray-800 border-gray-700 text-gray-100">
                    <option value="">Todos los estados</option>
                    <option value="abierto">Abiertos</option>
                    <option value="en_proceso">En proceso de Revision</option>
                    <option value="cerrado">Cerrados</option>
                    <option value="resuelto">Resueltos</option>
                </select>

                <select wire:model.live="categoryFilter" class="form-select bg-gray-800 border-gray-700 text-gray-100 text-sm">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $key => $categoria)
                        <option value="{{ $key }}">{{ $categoria }}</option>
                    @endforeach
                </select>

                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('operador'))
                    <div class="flex items-center space-x-2">
                        <select wire:model.live="clientTypeFilter" class="form-select bg-gray-800 border-gray-700 text-gray-100 text-sm">
                            <option value="">Todos los tickets</option>
                            <option value="interno">Internos</option>
                            <option value="cliente">De clientes</option>
                        </select>
                        <!-- Botón para limpiar filtros -->
                        <button wire:click="clearFilters" 
                                class="p-2 bg-gray-700 hover:bg-gray-600 rounded-md text-gray-300 hover:text-white transition-colors"
                                title="Limpiar todos los filtros">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" class="text-red-400"/>
                            </svg>
                        </button>
                    </div>
                @endif

                <button wire:click="openModal" 
                        class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded text-white font-medium flex items-center">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Crear Ticket
                </button>
            </div>
        </div>
        <!-- Modal para crear/editar tickets -->
        @if ($showModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" wire:click="closeModalOnClickAway">
                <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl" wire:click.stop>
                    <div class="border-b border-gray-700 px-6 py-4 flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-white">
                            {{ $editMode ? 'Editar Ticket' : 'Crear Nuevo Ticket' }}
                        </h3>
                        <button type="button" 
                            class="text-gray-400 hover:text-gray-200 bg-transparent rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                            wire:click="closeModal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span class="sr-only">Cerrar modal</span>
                    </button>
                    </div>

                    <div class="p-6">
                        <form wire:submit.prevent="{{ $editMode ? 'update' : 'store' }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Título -->
                                 <div class="md:col-span-2">
                                    <label for="titulo" class="block text-sm font-medium text-gray-300 mb-2">Título</label>
                                    <input type="text" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500" id="titulo" wire:model="titulo">
                                    @error('titulo') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <!-- Descripción -->
                                <div class="md:col-span-2">
                                    <label for="descripcion" class="block text-sm font-medium text-gray-300 mb-2">Descripción</label>
                                    <textarea class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500" id="descripcion" rows="4" wire:model="descripcion"></textarea>
                                    @error('descripcion') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <!-- Categoría -->
                                 <div>
                                    <label for="categoria" class="block text-sm font-medium text-gray-300 mb-2">Categoría</label>
                                    <select class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-green-500" 
                                            id="categoria" 
                                            wire:model="categoria">
                                        <option value="">Seleccionar categoría</option>
                                        @foreach($categorias as $key => $categoria)
                                            <option value="{{ $key }}">{{ $categoria }}</option>
                                        @endforeach
                                    </select>
                                    @error('categoria') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <!-- Prioridad -->
                                <div>
                                    <label for="prioridad" class="block text-sm font-medium text-gray-300 mb-2">Prioridad</label>
                                    <select class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-green-500" id="prioridad" wire:model="prioridad">
                                        <option value="baja">Baja</option>
                                        <option value="media">Media</option>
                                        <option value="alta">Alta</option>
                                        <option value="urgente">Urgente</option>
                                    </select>
                                    @error('prioridad') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>

                                @if(!$editMode)
                                    <!-- Emitido por (solo en creación) -->
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-300 mb-2">Emitido por (de no ser del equipo del COS, seleccionar la opcion CLIENTE)</label>
                                        <div class="flex space-x-4">
                                            <label class="flex items-center">
                                                <input type="radio" 
                                                       class="form-radio text-green-600 bg-gray-700 border-gray-600" 
                                                       name="emitido_por" 
                                                       value="COS"
                                                       wire:model="emitido_por">
                                                <span class="ml-2 text-gray-300">COS</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" 
                                                       class="form-radio text-green-600 bg-gray-700 border-gray-600" 
                                                       name="emitido_por" 
                                                       value="CLIENTE"
                                                       wire:model="emitido_por">
                                                <span class="ml-2 text-gray-300">CLIENTE</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Cliente (solo si emitido por CLIENTE) -->
                                    <div class="md:col-span-2" id="cliente-field" style="display: none;">
                                        <label for="cliente_id" class="block text-sm font-medium text-gray-300 mb-2">Cliente</label>
                                        <select class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-green-500" 
                                                id="cliente_id" 
                                                wire:model="cliente_id">
                                            <option value="">Seleccionar cliente</option>
                                            @foreach($clientes as $cliente)
                                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                            @endforeach
                                        </select>
                                        @error('cliente_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                   

                                    <!-- Asignado a (solo si emitido por COS) -->
                                 
                                    <div class="md:col-span-2" id="asignado-field">
                                        <label for="asignado_a" class="block text-sm font-medium text-gray-300 mb-2">Asignado a (opcional)</label>
                                        <select class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-green-500" 
                                                id="asignado_a" 
                                                wire:model="asignado_a">
                                            <option value="">Sin asignar</option>
                                            @foreach($usuarios as $usuario)
                                                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('asignado_a') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                  
                                @endif

                                @if($editMode && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('operador')))
                                    <!-- Estado (solo en edición y para admin) -->
                                    <div>
                                        <label for="estado" class="block text-sm font-medium text-gray-300 mb-2">Estado</label>
                                        <select class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-green-500" 
                                                id="estado" 
                                                wire:model="estado">
                                            @foreach($estados as $key => $estadoLabel)
                                                <option value="{{ $key }}">{{ $estadoLabel }}</option>
                                            @endforeach
                                        </select>
                                        @error('estado') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                            </div>

                            <div class="flex justify-end space-x-3 mt-6">
                                <button type="button" 
                                        class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-md text-white" 
                                        wire:click="closeModal">
                                    Cancelar
                                </button>
                                <button type="submit" 
                                        class="px-4 py-2 bg-green-600 hover:bg-green-500 rounded-md text-white">
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
                        <div class="max-h-96 overflow-y-auto [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-700 text-gray-300">
                                        <th class="px-4 py-3 text-left">Título</th>
                                        <th class="px-4 py-3 text-left">Categoría</th>
                                        <th class="px-4 py-3 text-left">Prioridad</th>
                                        <th class="px-4 py-3 text-left">Estado</th>
                                        <th class="px-4 py-3 text-left">Cliente</th>
                                        <th class="px-4 py-3 text-left">Creado por</th>
                                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('operador'))
                                            <th class="px-4 py-3 text-left">Asignado a</th>
                                        @endif
                                        <th class="px-4 py-3 text-left">Fecha creación</th>
                                        <th class="px-4 py-3 text-left">Fecha cierre</th>
                                        <th class="px-4 py-3 text-left">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700">
                                    @foreach ($tickets as $ticket)
                                        <tr class="hover:bg-gray-750">
                                            <td class="px-4 py-3">
                                                <div>
                                                    <div class="text-white font-medium">{{ $ticket->titulo }}</div>
                                                    <div class="text-gray-400 text-sm">{{ Str::limit($ticket->descripcion, 50) }}</div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-gray-300">
                                                {{ $categorias[$ticket->categoria] ?? $ticket->categoria }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                                    @if($ticket->prioridad == 'baja') bg-gray-600 text-gray-200
                                                    @elseif($ticket->prioridad == 'media') bg-blue-600 text-blue-100
                                                    @elseif($ticket->prioridad == 'alta') bg-yellow-600 text-yellow-100
                                                    @elseif($ticket->prioridad == 'urgente') bg-red-600 text-red-100
                                                    @endif">
                                                    {{ ucfirst($ticket->prioridad) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 rounded-full text-xs font-medium 
                                                    @if($ticket->estado == 'abierto') bg-green-600 text-green-100
                                                    @elseif($ticket->estado == 'en_proceso') bg-blue-600 text-blue-100
                                                    @elseif($ticket->estado == 'resuelto') bg-purple-600 text-purple-100
                                                    @elseif($ticket->estado == 'cerrado') bg-gray-600 text-gray-100
                                                    @endif">
                                                    {{ $estados[$ticket->estado] ?? ucfirst(str_replace('_', ' ', $ticket->estado)) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($ticket->cliente_id)
                                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-indigo-600 text-indigo-100">
                                                        {{ $ticket->cliente->nombre }}
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-600 text-gray-100">
                                                        Interno
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-white">{{ $ticket->user->name }}</td>
                                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('operador'))
                                                <td class="px-4 py-3 text-gray-300">
                                                    {{ $ticket->assignedTo->name ?? 'Sin asignar' }}
                                                </td>
                                            @endif
                                            <td class="px-4 py-3 text-gray-400">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-4 py-3 text-gray-400">
                                                {{ $ticket->fecha_cierre ? $ticket->fecha_cierre->format('d/m/Y H:i') : '-' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex space-x-2">
                                                    @if((auth()->user()->hasRole('admin') || auth()->user()->hasRole('operador')) && $ticket->estado !== 'cerrado')
                                                        <button wire:click="edit({{ $ticket->id }})" 
                                                                class="p-2 bg-blue-600 hover:bg-blue-500 rounded-md text-white" 
                                                                title="Editar">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                    
                                                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('operador'))
                                                        <div class="relative group">
                                                            <button class="p-2 bg-gray-600 hover:bg-gray-500 rounded-md text-white" title="Opciones">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                                </svg>
                                                            </button>
                                                            <div class="absolute right-0 mt-1 w-48 bg-gray-800 border border-gray-700 rounded-md shadow-lg opacity-0 invisible transition-all duration-300 group-hover:opacity-100 group-hover:visible z-10">
                                                                <div class="py-1">
                                                                    @if($ticket->estado !== 'cerrado' && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('operador')))
                                                                        <button wire:click="updateStatus({{ $ticket->id }}, 'abierto')" 
                                                                                class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-700">
                                                                            Marcar como Abierto
                                                                        </button>
                                                                        <button wire:click="updateStatus({{ $ticket->id }}, 'en_proceso')" 
                                                                                class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-700">
                                                                            Marcar como en Revision
                                                                        </button>
                                                                        <button wire:click="updateStatus({{ $ticket->id }}, 'resuelto')" 
                                                                                class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-700">
                                                                            Marcar como Resuelto
                                                                        </button>
                                                                        <button wire:click="updateStatus({{ $ticket->id }}, 'cerrado')" 
                                                                                class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-700">
                                                                            Marcar como Cerrado
                                                                        </button>
                                                                        <hr class="border-gray-700">
                                                                    @endif
                                                                    <button onclick="confirm('¿Estás seguro?') || event.stopImmediatePropagation()" 
                                                                            wire:click="delete({{ $ticket->id }})"
                                                                            class="block w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700">
                                                                        Eliminar
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <!-- Botón para ver detalles (todos los usuarios) -->
                                                    <button onclick="alert('Detalles del ticket:\n\nTítulo: {{ $ticket->titulo }}\nDescripción: {{ $ticket->descripcion }}\nCategoría: {{ $categorias[$ticket->categoria] ?? $ticket->categoria }}\nPrioridad: {{ ucfirst($ticket->prioridad) }}\nEstado: {{ $estados[$ticket->estado] ?? $ticket->estado }}\nCreado: {{ $ticket->created_at->format('d/m/Y H:i') }}\nCreado por: {{ $ticket->user->name }}{{ $ticket->cliente ? '\nCliente: ' . $ticket->cliente->nombre : '' }}{{ $ticket->assignedTo ? '\nAsignado a: ' . $ticket->assignedTo->name : '' }}{{ $ticket->fecha_cierre ? '\nCerrado: ' . $ticket->fecha_cierre->format('d/m/Y H:i') : '' }}')"
                                                            class="p-2 bg-gray-600 hover:bg-gray-500 rounded-md text-white" 
                                                            title="Ver detalles">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
                        @if(!auth()->user()->hasRole('admin'))
                            <p class="text-sm mt-2">Solo puedes ver los tickets que has creado.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('livewire:init', function() {
        // Función para mostrar/ocultar campos según selección
        function toggleTicketFields() {
            const clienteField = document.getElementById('cliente-field');
            const asignadoField = document.getElementById('asignado-field');
            const emitidoPorCliente = document.querySelector('input[name="emitido_por"][value="CLIENTE"]');
            const emitidoPorCOS = document.querySelector('input[name="emitido_por"][value="COS"]');
            
            if (clienteField && asignadoField) {
                if (emitidoPorCliente && emitidoPorCliente.checked) {
                    clienteField.style.display = 'block';
                    asignadoField.style.display = 'none';
                } else if (emitidoPorCOS && emitidoPorCOS.checked) {
                    clienteField.style.display = 'none';
                    asignadoField.style.display = 'block';
                }
            }
        }

        // Escuchar cambios en los radio buttons
        document.addEventListener('change', function(e) {
            if (e.target.name === 'emitido_por') {
                toggleTicketFields();
            }
        });

        // Ejecutar al cargar y cuando Livewire actualice el DOM
        Livewire.on('modal-opened', function() {
            setTimeout(toggleTicketFields, 10);
        });

        // Ejecutar inicialmente
        toggleTicketFields();
    });
    </script>
</div>
