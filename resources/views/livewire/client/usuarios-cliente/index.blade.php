<div>
    <!-- Contenedor 1: Título -->
    <div class="bg-[#252728] rounded-lg p-6 mb-6 border border-transparent">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-100">Administrar Usuarios</h2>
                @if($clientePrincipal)
                    <p class="text-sm text-gray-400 mt-1">Cliente: {{ $clientePrincipal->nombre }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Contenedor 2: Filtros -->
    <div class="bg-[#252728] rounded-lg p-6 mb-6 border border-transparent">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-base font-semibold mb-1 text-gray-200">Buscar</label>
                <input type="text" wire:model.live="search"
                       placeholder="Nombre o email..."
                       class="w-full bg-transparent border-zinc-300 rounded px-3 py-2 text-gray-200">
            </div>
        </div>
    </div>

    <!-- Contenedor 3: Listado/Tabla -->
    <div class="bg-[#252728] rounded-lg p-6 border border-transparent">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-[#1a1d1f] text-gray-300">
                    <tr>
                        <th class="px-4 py-2 text-left">NOMBRE</th>
                        <th class="px-4 py-2 text-left">EMAIL</th>
                        <th class="px-4 py-2 text-left">ROL ACTUAL</th>
                        <th class="px-4 py-2 text-left">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr class="table-row-hover transition-colors">
                            <td class="px-4 py-2 font-medium text-gray-300">{{ $usuario->name }}</td>
                            <td class="px-4 py-2 text-gray-300">{{ $usuario->email }}</td>
                            <td class="px-4 py-2">
                                @foreach($usuario->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($role->name === 'clientadmin') bg-purple-600 text-white
                                        @elseif($role->name === 'clientsupervisor') bg-blue-600 text-white
                                        @elseif($role->name === 'cliente') bg-green-600 text-white
                                        @else bg-gray-600 text-white
                                        @endif">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-4 py-2">
                                <div class="flex space-x-4">
                                    @if($usuario->id !== auth()->id() && !$usuario->hasRole(['clientadmin', 'admin', 'operador', 'supervisor']))
                                        <button wire:click="abrirModalAsignar({{ $usuario->id }})" 
                                                class="action-button relative"
                                                data-tooltip="Asignar Rol">
                                            <div class="w-9 h-9 rounded-full bg-[#1877f2] flex items-center justify-center transition-all hover:bg-[#0866ff]">
                                                <i class="bi bi-person-gear text-white" style="font-size: 16px;"></i>
                                            </div>
                                        </button>
                                    @else
                                        <span class="text-gray-500 text-xs italic">Sin acciones</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center px-4 py-8 text-gray-300">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <p>No se encontraron usuarios</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $usuarios->links() }}
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

    @if (session()->has('error'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-transition
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed top-4 right-4 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif

    <!-- Modal Asignar Rol -->
    @if($mostrarModalAsignar && $usuarioSeleccionado)
        <div class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-2 sm:p-4 z-50 overflow-y-auto">
            <div class="bg-[#252728] rounded-lg shadow-xl w-full max-w-md max-h-[85vh] overflow-hidden flex flex-col my-auto">
                <!-- Header del Modal -->
                <div class="bg-[#1a1d1f] px-4 sm:px-6 py-3 sm:py-4 border-b border-zinc-700 flex-shrink-0">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-200">
                            <i class="bi bi-person-gear mr-2"></i> Asignar Rol
                        </h2>
                        <button wire:click="cerrarModalAsignar" 
                                class="text-gray-400 hover:text-gray-200 transition-colors p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-gray-400 mt-1">{{ $usuarioSeleccionado->name }}</p>
                </div>

                <!-- Contenido del Modal -->
                <div class="p-4 sm:p-6 overflow-y-auto flex-1">
                    <form wire:submit.prevent="asignarRol">
                        <div class="space-y-4">
                            <div class="mb-4">
                                <p class="text-gray-300 text-sm mb-4">
                                    Selecciona el rol que deseas asignar a este usuario. 
                                    Solo puedes asignar el rol de <strong>Supervisor de Cliente</strong> o el rol básico de <strong>Cliente</strong>.
                                </p>
                            </div>

                            <!-- Selector de Rol -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-200 mb-3">Rol a Asignar</label>
                                
                                <div class="space-y-3">
                                    <label class="flex items-start p-3 rounded-lg border border-zinc-600 cursor-pointer hover:bg-zinc-700/50 transition-colors {{ $rolSeleccionado === 'cliente' ? 'border-green-500 bg-green-500/10' : '' }}">
                                        <input type="radio" wire:model="rolSeleccionado" value="cliente" 
                                               class="mt-1 h-4 w-4 text-green-600 focus:ring-green-500 border-zinc-500">
                                        <div class="ml-3">
                                            <span class="block text-sm font-medium text-gray-200">Cliente</span>
                                            <span class="block text-xs text-gray-400">Acceso básico al sistema. Puede ver eventos, reportes y patrullas.</span>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-start p-3 rounded-lg border border-zinc-600 cursor-pointer hover:bg-zinc-700/50 transition-colors {{ $rolSeleccionado === 'clientsupervisor' ? 'border-blue-500 bg-blue-500/10' : '' }}">
                                        <input type="radio" wire:model="rolSeleccionado" value="clientsupervisor" 
                                               class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-zinc-500">
                                        <div class="ml-3">
                                            <span class="block text-sm font-medium text-gray-200">Supervisor de Cliente</span>
                                            <span class="block text-xs text-gray-400">Acceso extendido. Puede crear y editar patrullas, generar PDFs de estadísticas.</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-zinc-700">
                            <button type="button" wire:click="cerrarModalAsignar"
                                    class="bg-zinc-700 hover:bg-zinc-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="bg-[#1877f2] hover:bg-[#0866ff] text-white px-4 py-2 rounded-lg text-sm transition-colors flex items-center space-x-2">
                                <i class="bi bi-check-circle"></i>
                                <span>Asignar Rol</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Estilos -->
    <style>
        .action-button {
            position: relative;
            display: inline-block;
        }

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

        .table-row-hover {
            border-bottom: 0.1px solid #e5e7eb !important;
        }

        .table-row-hover:hover {
            background-color: #1f2937 !important;
        }

        .table-row-hover:hover td {
            color: #e5e7eb !important;
        }
    </style>
</div>
