<div class="bg-gray-900 text-gray-100 p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">
            @if($clienteEspecifico)
                Empresas Asociadas a {{ $clienteEspecifico->nombre }}
                <a href="{{ route('crear.cliente') }}" 
                   class="text-blue-400 text-sm ml-2 hover:underline">
                   Volver al listado
                </a>
            @else
                Empresas Asociadas
            @endif
        </h2>
        <button wire:click="openModal"
                class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded text-white font-medium">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva Empresa
        </button>
    </div>
    
    <!-- Filtros -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm mb-1">Buscar</label>
            <input type="text"
                   wire:model.live="search"
                   placeholder="Nombre, Cliente..."
                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
        </div>
        <!-- Filtro cliente -->
        @if(!$clienteEspecifico)
        <div>
            <label class="block text-sm mb-1">Filtrar por Cliente</label>
            <select wire:model.live="clienteFilter" class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                <option value="">Todos los clientes</option>
                @foreach($clientesDisponibles as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="flex items-end">
            <button wire:click="clearFilters" 
                    class="bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded text-white">
                Limpiar Filtros
            </button>
        </div>
    </div>
   

    <!-- Tabla contenido -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-800 text-gray-300">
                <tr>
                    <th class="px-4 py-2 text-left">Nombre</th>
                    <th class="px-4 py-2 text-left">Cliente</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($empresas as $empresa)
                    <tr class="border-b border-gray-700 hover:bg-gray-800">
                        <td class="px-4 py-2">
                            <div class="text-sm">{{ $empresa->nombre ?? 'N/A' }}</div>
                        </td>
                        <td class="px-4 py-2">
                            @if($empresa->clientes->count() > 0)
                                @foreach($empresa->clientes as $cliente)
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm bg-blue-600 text-white px-2 py-1 rounded text-xs">
                                            {{ $cliente->nombre }}
                                        </span>
                                        <button wire:click="desasociarDeCliente({{ $empresa->id }}, {{ $cliente->id }})"
                                                onclick="return confirm('¿Está seguro de que desea desasociar esta empresa del cliente {{ $cliente->nombre }}?')"
                                                class="text-red-400 hover:text-red-300 ml-2"
                                                title="Desasociar de cliente">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <span class="text-gray-400 text-sm">Sin asociar</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex space-x-2">
                                <button wire:click="editarEmpresa({{ $empresa->id }} )"
                                        class="text-blue-400 hover:text-blue-300"
                                        title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button wire:click="eliminarEmpresa({{ $empresa->id }})"
                                        onclick="return confirm('¿Está seguro de que desea eliminar esta empresa?')"
                                        class="text-red-400 hover:text-red-300"
                                        title="Eliminar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center px-4 py-8 text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <p>No se encontraron empresas asociadas a clientes</p>
                                <p class="text-sm">Intenta ajustar los filtros o agrega un nuevo dispositivo</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $empresas->links() }}

    <!-- Modal -->
     @if($showModal)
        <div wire:click.self="closeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-900 rounded-lg p-6 w-full max-w-2xl" @click.stop>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-100">
                        {{ $editingId ? 'Editar Empresa' : 'Nueva Empresa' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-6">
                        <div>
                            <label class="block text-sm mb-1 text-gray-300">Nombre <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="nombre" 
                                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                            @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    <div class="flex justify-end space-x-4 mt-6 pt-4 border-t border-gray-700">
                        <button type="button" wire:click="closeModal"
                                class="px-4 py-2 text-gray-300 hover:text-gray-100">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="bg-green-600 hover:bg-green-700 px-6 py-2 rounded text-white font-medium">
                            {{ $editingId ? 'Actualizar' : 'Guardar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
