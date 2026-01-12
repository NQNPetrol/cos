
<div class="bg-zinc-900 text-gray-100 p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">
                Listado de Empresas
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
                   class="w-full bg-zinc-800 border-zinc-700 rounded px-3 py-2 text-gray-200">
        </div>

        <div class="flex items-end">
            <button wire:click="clearFilters" 
                    class="bg-zinc-600 hover:bg-zinc-700 px-4 py-2 rounded text-white">
                Limpiar Filtros
            </button>
        </div>
    </div>
   

    <!-- Tabla contenido -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-zinc-800 text-gray-300">
                <tr>
                    <th class="px-4 py-2 text-left">Nombre</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($empresas as $empresa)
                    <tr class="border-b border-zinc-700 hover:bg-zinc-800">
                        <td class="px-4 py-2">
                            <div class="text-sm">{{ $empresa->nombre ?? 'N/A' }}</div>
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
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                                </svg>
                                <p>No se encontraron empresas asociadas a clientes</p>
                                <p class="text-sm">Intenta ajustar los filtros o crea una nueva.</p>
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
            <div class="bg-zinc-900 rounded-lg p-6 w-full max-w-2xl" @click.stop>
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
                                   class="w-full bg-zinc-800 border-zinc-700 rounded px-3 py-2 text-gray-200">
                            @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    <div class="flex justify-end space-x-4 mt-6 pt-4 border-t border-zinc-700">
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
