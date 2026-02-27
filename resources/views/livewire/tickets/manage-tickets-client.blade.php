<div>
    {{-- ─── Flash messages ─── --}}
    @if (session()->has('message'))
        <div id="flash-success" class="mb-5 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="text-sm">{{ session('message') }}</span>
            </div>
            <button type="button" class="text-emerald-400/60 hover:text-emerald-300 ml-4 shrink-0" onclick="this.parentElement.style.display='none'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-5 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm">{{ session('error') }}</span>
            </div>
            <button type="button" class="text-red-400/60 hover:text-red-300 ml-4 shrink-0" onclick="this.parentElement.style.display='none'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <div class="bg-zinc-900 text-gray-100 p-6 rounded-lg shadow">

        {{-- ─── Header ─── --}}
        <div class="flex flex-wrap justify-between items-center mb-6 gap-3">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-br from-blue-600/20 to-blue-400/10 rounded-xl border border-blue-500/20">
                    <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Gestión de Tickets</h1>
                    <p class="text-gray-400 text-sm">Tickets de sistema</p>
                </div>
            </div>

            {{-- Filters + New Ticket --}}
            <div class="flex flex-wrap items-center gap-2">
                <select wire:model.live="statusFilter" class="bg-zinc-800 border border-zinc-700 rounded-xl text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                    <option value="">Todos los estados</option>
                    <option value="abierto">Abiertos</option>
                    <option value="en_proceso">En proceso de Revisión</option>
                    <option value="cerrado">Cerrados</option>
                    <option value="resuelto">Resueltos</option>
                </select>

                <select wire:model.live="categoryFilter" class="bg-zinc-800 border border-zinc-700 rounded-xl text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $key => $categoria)
                        <option value="{{ $key }}">{{ $categoria }}</option>
                    @endforeach
                </select>

                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('operador'))
                    <select wire:model.live="clientTypeFilter" class="bg-zinc-800 border border-zinc-700 rounded-xl text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                        <option value="">Todos los tickets</option>
                        <option value="interno">Internos</option>
                        <option value="cliente">De clientes</option>
                    </select>
                    <button wire:click="clearFilters"
                            class="p-2 bg-zinc-700 hover:bg-zinc-600 rounded-md text-gray-300 hover:text-white transition-colors"
                            title="Limpiar todos los filtros">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"/>
                        </svg>
                    </button>
                @endif

                <button wire:click="openModal" id="btn-crear-ticket"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold flex items-center gap-2 transition-all shadow-lg shadow-blue-600/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Crear Ticket
                </button>
            </div>
        </div>

        {{-- ─── Modal crear / editar ─── --}}
        @if ($showModal)
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50" wire:click="closeModalOnClickAway">
                <div class="bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl w-full max-w-2xl" wire:click.stop>
                    {{-- Modal header --}}
                    <div class="bg-zinc-800/50 border-b border-zinc-700/50 px-6 py-4 flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-white">
                            {{ $editMode ? 'Editar Ticket' : 'Crear Nuevo Ticket' }}
                        </h3>
                        <button type="button"
                                class="text-gray-400 hover:text-gray-200 bg-transparent rounded-lg text-sm p-1.5 inline-flex items-center"
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

                                {{-- Título --}}
                                <div class="md:col-span-2">
                                    <label for="titulo" class="block text-sm font-medium text-gray-300 mb-1.5">
                                        Título <span class="text-red-400">*</span>
                                    </label>
                                    <input type="text"
                                           id="titulo"
                                           wire:model="titulo"
                                           placeholder="Describí brevemente el problema o solicitud"
                                           class="w-full px-3 py-2.5 bg-zinc-800 border border-zinc-700 rounded-xl text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500/30 focus:border-blue-500 text-sm">
                                    @error('titulo') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Categoría --}}
                                <div>
                                    <label for="categoria" class="block text-sm font-medium text-gray-300 mb-1.5">
                                        Categoría <span class="text-red-400">*</span>
                                    </label>
                                    <select id="categoria"
                                            wire:model="categoria"
                                            class="w-full px-3 py-2.5 bg-zinc-800 border border-zinc-700 rounded-xl text-gray-200 focus:outline-none focus:ring-1 focus:ring-blue-500/30 focus:border-blue-500 text-sm">
                                        <option value="">Seleccionar categoría</option>
                                        @foreach($categorias as $key => $categoria)
                                            <option value="{{ $key }}">{{ $categoria }}</option>
                                        @endforeach
                                    </select>
                                    @error('categoria') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Prioridad --}}
                                <div>
                                    <label for="prioridad" class="block text-sm font-medium text-gray-300 mb-1.5">
                                        Prioridad <span class="text-red-400">*</span>
                                    </label>
                                    <select id="prioridad"
                                            wire:model="prioridad"
                                            class="w-full px-3 py-2.5 bg-zinc-800 border border-zinc-700 rounded-xl text-gray-200 focus:outline-none focus:ring-1 focus:ring-blue-500/30 focus:border-blue-500 text-sm">
                                        <option value="baja">🟢 Baja</option>
                                        <option value="media">🔵 Media</option>
                                        <option value="alta">🟠 Alta</option>
                                        <option value="urgente">🔴 Urgente</option>
                                    </select>
                                    @error('prioridad') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Descripción --}}
                                <div class="md:col-span-2">
                                    <label for="descripcion" class="block text-sm font-medium text-gray-300 mb-1.5">
                                        Descripción <span class="text-red-400">*</span>
                                    </label>
                                    <textarea id="descripcion"
                                              wire:model="descripcion"
                                              rows="4"
                                              placeholder="Describí con detalle: qué pasó, cuándo ocurrió, qué estabas haciendo..."
                                              class="w-full px-3 py-2.5 bg-zinc-800 border border-zinc-700 rounded-xl text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500/30 focus:border-blue-500 text-sm resize-none"></textarea>
                                    @error('descripcion') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                @if(!$editMode)
                                    {{--
                                        Para usuarios de la familia cliente (cliente/clientadmin/clientsupervisor):
                                        - emitido_por ya está forzado a CLIENTE en el componente.
                                        - Si tiene un único cliente asignado, cliente_id se pre-selecciona.
                                        - Solo mostramos el selector de cliente si tiene más de uno o si es admin/operador.
                                    --}}
                                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('operador'))
                                        {{-- Emitido por — solo admin y operador lo necesitan --}}
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Emitido por</label>
                                            <div class="flex space-x-4">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="radio"
                                                           class="form-radio text-blue-600 bg-zinc-700 border-zinc-600"
                                                           name="emitido_por"
                                                           value="COS"
                                                           wire:model="emitido_por">
                                                    <span class="ml-2 text-gray-300 text-sm">COS (interno)</span>
                                                </label>
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="radio"
                                                           class="form-radio text-blue-600 bg-zinc-700 border-zinc-600"
                                                           name="emitido_por"
                                                           value="CLIENTE"
                                                           wire:model="emitido_por">
                                                    <span class="ml-2 text-gray-300 text-sm">Cliente</span>
                                                </label>
                                            </div>
                                        </div>

                                        {{-- Cliente (solo si emitido por CLIENTE) --}}
                                        <div class="md:col-span-2" id="cliente-field" style="display: none;">
                                            <label for="cliente_id" class="block text-sm font-medium text-gray-300 mb-1.5">Cliente</label>
                                            <select id="cliente_id"
                                                    wire:model="cliente_id"
                                                    class="w-full px-3 py-2.5 bg-zinc-800 border border-zinc-700 rounded-xl text-gray-200 focus:outline-none focus:ring-1 focus:ring-blue-500/30 focus:border-blue-500 text-sm">
                                                <option value="">Seleccionar cliente</option>
                                                @foreach($clientes as $cliente)
                                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                                @endforeach
                                            </select>
                                            @error('cliente_id') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- Asignado a (solo si emitido por COS) --}}
                                        <div class="md:col-span-2" id="asignado-field">
                                            <label for="asignado_a" class="block text-sm font-medium text-gray-300 mb-1.5">Asignado a <span class="text-gray-500">(opcional)</span></label>
                                            <select id="asignado_a"
                                                    wire:model="asignado_a"
                                                    class="w-full px-3 py-2.5 bg-zinc-800 border border-zinc-700 rounded-xl text-gray-200 focus:outline-none focus:ring-1 focus:ring-blue-500/30 focus:border-blue-500 text-sm">
                                                <option value="">Sin asignar</option>
                                                @foreach($usuarios as $usuario)
                                                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('asignado_a') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    @else
                                        {{--
                                            Usuario de la familia cliente:
                                            Si pertenece a más de un cliente, mostrar selector.
                                            Si pertenece a uno solo, ya fue auto-seleccionado en el componente (oculto).
                                        --}}
                                        @php
                                            $userClientesCount = \App\Models\UserCliente::where('user_id', auth()->id())->count();
                                        @endphp
                                        @if($userClientesCount > 1)
                                            <div class="md:col-span-2">
                                                <label for="cliente_id_multi" class="block text-sm font-medium text-gray-300 mb-1.5">
                                                    ¿Para qué empresa es este ticket? <span class="text-red-400">*</span>
                                                </label>
                                                <select id="cliente_id_multi"
                                                        wire:model="cliente_id"
                                                        class="w-full px-3 py-2.5 bg-zinc-800 border border-zinc-700 rounded-xl text-gray-200 focus:outline-none focus:ring-1 focus:ring-blue-500/30 focus:border-blue-500 text-sm">
                                                    <option value="">Seleccionar empresa</option>
                                                    @foreach($clientes->filter(fn($c) => \App\Models\UserCliente::where('user_id', auth()->id())->where('cliente_id', $c->id)->exists()) as $cl)
                                                        <option value="{{ $cl->id }}">{{ $cl->nombre }}</option>
                                                    @endforeach
                                                </select>
                                                @error('cliente_id') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                        @endif
                                        {{-- emitido_por = CLIENTE está forzado en el componente PHP --}}
                                    @endif
                                @endif

                                @if($editMode && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('operador')))
                                    {{-- Estado (solo en edición y para admin/operador) --}}
                                    <div>
                                        <label for="estado" class="block text-sm font-medium text-gray-300 mb-1.5">Estado</label>
                                        <select id="estado"
                                                wire:model="estado"
                                                class="w-full px-3 py-2.5 bg-zinc-800 border border-zinc-700 rounded-xl text-gray-200 focus:outline-none focus:ring-1 focus:ring-blue-500/30 focus:border-blue-500 text-sm">
                                            @foreach($estados as $key => $estadoLabel)
                                                <option value="{{ $key }}">{{ $estadoLabel }}</option>
                                            @endforeach
                                        </select>
                                        @error('estado') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                            </div>

                            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-zinc-700/50">
                                <button type="button"
                                        class="px-5 py-2.5 bg-zinc-700 hover:bg-zinc-600 rounded-xl text-white text-sm font-medium transition-all"
                                        wire:click="closeModal">
                                    Cancelar
                                </button>
                                <button type="submit"
                                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 rounded-xl text-white text-sm font-semibold transition-all shadow-lg shadow-blue-600/20 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    {{ $editMode ? 'Actualizar ticket' : 'Enviar ticket' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        {{-- ─── Lista de tickets ─── --}}
        <div class="bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50 overflow-hidden shadow-xl">
            <div class="p-6">
                @if ($tickets->count())
                    <div class="overflow-x-auto">
                        <div class="max-h-[600px] overflow-y-auto [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="border-b border-zinc-700/50">
                                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Ticket</th>
                                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Categoría</th>
                                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Prioridad</th>
                                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Estado</th>
                                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Creado por</th>
                                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('operador'))
                                            <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Asignado a</th>
                                        @endif
                                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Fecha</th>
                                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-700/30">
                                    @foreach ($tickets as $ticket)
                                        <tr class="hover:bg-zinc-700/20 transition-colors duration-150">
                                            {{-- Título + descripción abreviada --}}
                                            <td class="px-4 py-4 max-w-xs">
                                                <div class="text-white font-medium leading-snug">{{ $ticket->titulo }}</div>
                                                <div class="text-gray-500 text-xs mt-0.5 line-clamp-1">{{ Str::limit($ticket->descripcion, 60) }}</div>
                                            </td>

                                            <td class="px-4 py-4 text-gray-300 text-sm">
                                                {{ $categorias[$ticket->categoria] ?? $ticket->categoria }}
                                            </td>

                                            <td class="px-4 py-4">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                                    @if($ticket->prioridad == 'baja') bg-zinc-500/10 text-gray-400 border border-zinc-500/20
                                                    @elseif($ticket->prioridad == 'media') bg-blue-500/10 text-blue-400 border border-blue-500/20
                                                    @elseif($ticket->prioridad == 'alta') bg-amber-500/10 text-amber-400 border border-amber-500/20
                                                    @elseif($ticket->prioridad == 'urgente') bg-red-500/10 text-red-400 border border-red-500/20
                                                    @endif">
                                                    {{ ucfirst($ticket->prioridad) }}
                                                </span>
                                            </td>

                                            <td class="px-4 py-4">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                                    @if($ticket->estado == 'abierto') bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                                    @elseif($ticket->estado == 'en_proceso') bg-blue-500/10 text-blue-400 border border-blue-500/20
                                                    @elseif($ticket->estado == 'resuelto') bg-purple-500/10 text-purple-400 border border-purple-500/20
                                                    @elseif($ticket->estado == 'cerrado') bg-zinc-500/10 text-gray-400 border border-zinc-500/20
                                                    @endif">
                                                    {{ $estados[$ticket->estado] ?? ucfirst(str_replace('_', ' ', $ticket->estado)) }}
                                                </span>
                                            </td>

                                            <td class="px-4 py-4 text-white text-sm">{{ $ticket->user->name }}</td>

                                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('operador'))
                                                <td class="px-4 py-4 text-gray-300 text-sm">
                                                    {{ $ticket->assignedTo->name ?? '—' }}
                                                </td>
                                            @endif

                                            <td class="px-4 py-4 text-gray-400 text-sm whitespace-nowrap">
                                                {{ $ticket->created_at->format('d/m/Y') }}
                                                @if($ticket->fecha_cierre)
                                                    <div class="text-xs text-gray-500">Cierre: {{ $ticket->fecha_cierre->format('d/m/Y') }}</div>
                                                @endif
                                            </td>

                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-1.5">
                                                    {{-- Ver detalles inline (todos los usuarios) --}}
                                                    <button wire:click="toggleDetails({{ $ticket->id }})"
                                                            class="p-1.5 rounded-lg transition-colors {{ $expandedTicketId === $ticket->id ? 'text-blue-400 bg-blue-500/10' : 'text-gray-400 hover:text-gray-200 hover:bg-zinc-700/50' }}"
                                                            title="{{ $expandedTicketId === $ticket->id ? 'Ocultar detalles' : 'Ver detalles' }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                    </button>

                                                    @if((auth()->user()->hasRole('admin') || auth()->user()->hasRole('operador')) && $ticket->estado !== 'cerrado')
                                                        <button wire:click="edit({{ $ticket->id }})"
                                                                class="p-1.5 text-blue-400 hover:text-blue-300 hover:bg-blue-500/10 rounded-lg transition-colors"
                                                                title="Editar">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                            </svg>
                                                        </button>
                                                    @endif

                                                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('operador'))
                                                        <div class="relative group">
                                                            <button class="p-1.5 text-gray-400 hover:text-gray-200 hover:bg-zinc-700/50 rounded-lg transition-colors" title="Más opciones">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                                </svg>
                                                            </button>
                                                            <div class="absolute right-0 mt-1 w-48 bg-zinc-900 border border-zinc-700/50 rounded-xl shadow-2xl opacity-0 invisible transition-all duration-200 group-hover:opacity-100 group-hover:visible z-10">
                                                                <div class="py-1">
                                                                    @if($ticket->estado !== 'cerrado' && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('operador')))
                                                                        <button wire:click="updateStatus({{ $ticket->id }}, 'abierto')" class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-zinc-700 rounded-t-xl">Marcar como Abierto</button>
                                                                        <button wire:click="updateStatus({{ $ticket->id }}, 'en_proceso')" class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-zinc-700">En Revisión</button>
                                                                        <button wire:click="updateStatus({{ $ticket->id }}, 'resuelto')" class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-zinc-700">Resuelto</button>
                                                                        <button wire:click="updateStatus({{ $ticket->id }}, 'cerrado')" class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-zinc-700">Cerrado</button>
                                                                        <hr class="border-zinc-700">
                                                                    @endif
                                                                    <button onclick="confirm('¿Eliminar este ticket?') || event.stopImmediatePropagation()"
                                                                            wire:click="delete({{ $ticket->id }})"
                                                                            class="block w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-zinc-700 rounded-b-xl">
                                                                        Eliminar
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>

                                        {{-- ─── Panel de detalles inline ─── --}}
                                        @if($expandedTicketId === $ticket->id)
                                            <tr>
                                                <td colspan="{{ (auth()->user()->hasRole('admin') || auth()->user()->hasRole('operador')) ? 8 : 7 }}" class="px-4 pb-4 pt-0">
                                                    <div class="bg-zinc-800/70 border border-zinc-700/40 rounded-xl p-4 text-sm space-y-3">
                                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                            <div>
                                                                <span class="text-gray-500 text-xs uppercase tracking-wide font-semibold block mb-0.5">Título</span>
                                                                <span class="text-white">{{ $ticket->titulo }}</span>
                                                            </div>
                                                            <div>
                                                                <span class="text-gray-500 text-xs uppercase tracking-wide font-semibold block mb-0.5">Categoría</span>
                                                                <span class="text-gray-200">{{ $categorias[$ticket->categoria] ?? $ticket->categoria }}</span>
                                                            </div>
                                                            <div>
                                                                <span class="text-gray-500 text-xs uppercase tracking-wide font-semibold block mb-0.5">Prioridad</span>
                                                                <span class="text-gray-200">{{ ucfirst($ticket->prioridad) }}</span>
                                                            </div>
                                                            <div>
                                                                <span class="text-gray-500 text-xs uppercase tracking-wide font-semibold block mb-0.5">Estado</span>
                                                                <span class="text-gray-200">{{ $estados[$ticket->estado] ?? $ticket->estado }}</span>
                                                            </div>
                                                            <div>
                                                                <span class="text-gray-500 text-xs uppercase tracking-wide font-semibold block mb-0.5">Creado por</span>
                                                                <span class="text-gray-200">{{ $ticket->user->name }}</span>
                                                            </div>
                                                            @if($ticket->cliente)
                                                                <div>
                                                                    <span class="text-gray-500 text-xs uppercase tracking-wide font-semibold block mb-0.5">Cliente</span>
                                                                    <span class="text-gray-200">{{ $ticket->cliente->nombre }}</span>
                                                                </div>
                                                            @endif
                                                            @if($ticket->assignedTo)
                                                                <div>
                                                                    <span class="text-gray-500 text-xs uppercase tracking-wide font-semibold block mb-0.5">Asignado a</span>
                                                                    <span class="text-gray-200">{{ $ticket->assignedTo->name }}</span>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <span class="text-gray-500 text-xs uppercase tracking-wide font-semibold block mb-0.5">Fecha de creación</span>
                                                                <span class="text-gray-200">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                                                            </div>
                                                            @if($ticket->fecha_cierre)
                                                                <div>
                                                                    <span class="text-gray-500 text-xs uppercase tracking-wide font-semibold block mb-0.5">Fecha de cierre</span>
                                                                    <span class="text-gray-200">{{ $ticket->fecha_cierre->format('d/m/Y H:i') }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <span class="text-gray-500 text-xs uppercase tracking-wide font-semibold block mb-1">Descripción completa</span>
                                                            <p class="text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $ticket->descripcion }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-6">
                        {{ $tickets->links() }}
                    </div>
                @else
                    {{-- Estado vacío con CTA prominente --}}
                    <div class="text-center py-14">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-blue-600/10 border border-blue-500/20 mb-6">
                            <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">No hay tickets todavía</h3>
                        <p class="text-gray-400 text-sm mb-6 max-w-xs mx-auto">
                            Si tenés un problema o solicitud, creá un ticket y nuestro equipo te responderá a la brevedad.
                        </p>
                        <button wire:click="openModal" id="btn-crear-ticket-empty"
                                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-sm font-semibold transition-all shadow-lg shadow-blue-600/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Crear mi primer ticket
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Script solo necesario para admin/operador donde "Emitido por" puede cambiar --}}
    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('operador'))
    <script>
    document.addEventListener('livewire:init', function() {
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

        document.addEventListener('change', function(e) {
            if (e.target.name === 'emitido_por') {
                toggleTicketFields();
            }
        });

        Livewire.on('modal-opened', function() {
            setTimeout(toggleTicketFields, 10);
        });

        toggleTicketFields();
    });
    </script>
    @endif
</div>
