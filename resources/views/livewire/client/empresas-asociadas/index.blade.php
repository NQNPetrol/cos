<div>
    <!-- Contenedor 1: Título -->
    <div class="bg-[#252728] rounded-lg p-6 mb-6 border border-transparent">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-100">Clientes</h2>
                
            </div>
            <button wire:click="abrirModalCrear" 
                    class="bg-[#1877f2] hover:bg-[#0866ff] text-white px-4 py-2 rounded-lg text-sm flex items-center space-x-2 transition-colors">
                <i class="bi bi-plus-circle"></i>
                <span>Nueva Empresa</span>
            </button>
        </div>
    </div>

    <!-- Contenedor 2: Filtros -->
    <div class="bg-[#252728] rounded-lg p-6 mb-6 border border-transparent">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-base font-semibold mb-1 text-gray-200">Buscar</label>
                <input type="text" wire:model.live="search"
                       placeholder="Nombre de empresa..."
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
                        <th class="px-4 py-2 text-left">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($empresas as $empresa)
                        <tr class="table-row-hover transition-colors">
                            <td class="px-4 py-2 font-medium text-gray-300">{{ $empresa->nombre }}</td>
                            <td class="px-4 py-2">
                                <div class="flex space-x-4">
                                    <button wire:click="abrirModalEditar({{ $empresa->id }})" 
                                            class="action-button relative"
                                            data-tooltip="Editar">
                                        <div class="w-9 h-9 rounded-full bg-amber-600 flex items-center justify-center transition-all hover:bg-amber-700">
                                            <i class="bi bi-pencil text-white" style="font-size: 16px;"></i>
                                        </div>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center px-4 py-8 text-gray-300">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <p>No se encontraron empresas asociadas</p>
                                    <p class="text-sm">Agrega una nueva empresa para comenzar</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $empresas->links() }}
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

    <!-- Modal Crear Empresa -->
    @if($mostrarModalCrear)
        <div class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-2 sm:p-4 z-50 overflow-y-auto">
            <div class="bg-[#252728] rounded-lg shadow-xl w-full max-w-lg max-h-[85vh] overflow-hidden flex flex-col my-auto">
                <!-- Header del Modal -->
                <div class="bg-[#1a1d1f] px-4 sm:px-6 py-3 sm:py-4 border-b border-zinc-700 flex-shrink-0">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-200">
                            <i class="bi bi-plus-circle mr-2"></i> Nueva Empresa Asociada
                        </h2>
                        <button wire:click="cerrarModalCrear" 
                                class="text-gray-400 hover:text-gray-200 transition-colors p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Contenido del Modal -->
                <div class="p-4 sm:p-6 overflow-y-auto flex-1">
                    <form wire:submit.prevent="crearEmpresa">
                        <div class="space-y-4">
                            <!-- Nombre -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-200 mb-2">Nombre de la Empresa *</label>
                                <input type="text" wire:model="nombre"
                                       class="w-full bg-transparent border border-zinc-500 rounded px-3 py-2 text-gray-200 text-sm"
                                       placeholder="Ej: Empresa ABC S.A.">
                                @error('nombre')
                                    <span class="text-red-400 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-zinc-700">
                            <button type="button" wire:click="cerrarModalCrear"
                                    class="bg-zinc-700 hover:bg-zinc-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="bg-[#1877f2] hover:bg-[#0866ff] text-white px-4 py-2 rounded-lg text-sm transition-colors flex items-center space-x-2">
                                <i class="bi bi-check-circle"></i>
                                <span>Crear Empresa</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Editar Empresa -->
    @if($mostrarModalEditar && $empresaEditar)
        <div class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-2 sm:p-4 z-50 overflow-y-auto">
            <div class="bg-[#252728] rounded-lg shadow-xl w-full max-w-lg max-h-[85vh] overflow-hidden flex flex-col my-auto">
                <!-- Header del Modal -->
                <div class="bg-[#1a1d1f] px-4 sm:px-6 py-3 sm:py-4 border-b border-zinc-700 flex-shrink-0">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-200">
                            <i class="bi bi-pencil mr-2"></i> Editar Empresa
                        </h2>
                        <button wire:click="cerrarModalEditar" 
                                class="text-gray-400 hover:text-gray-200 transition-colors p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Contenido del Modal -->
                <div class="p-4 sm:p-6 overflow-y-auto flex-1">
                    <form wire:submit.prevent="actualizarEmpresa">
                        <div class="space-y-4">
                            <!-- Nombre -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-200 mb-2">Nombre de la Empresa *</label>
                                <input type="text" wire:model="nombre"
                                       class="w-full bg-transparent border border-zinc-500 rounded px-3 py-2 text-gray-200 text-sm"
                                       placeholder="Ej: Empresa ABC S.A.">
                                @error('nombre')
                                    <span class="text-red-400 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-zinc-700">
                            <button type="button" wire:click="cerrarModalEditar"
                                    class="bg-zinc-700 hover:bg-zinc-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm transition-colors flex items-center space-x-2">
                                <i class="bi bi-check-circle"></i>
                                <span>Guardar Cambios</span>
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
