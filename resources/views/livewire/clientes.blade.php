<div class="max-w-5xl mx-auto p-6 bg-gray-900 text-gray-50 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6">Administrar Clientes</h2>

    @if ($successMessage)
        <div class="bg-emerald-600 text-white p-3 rounded mb-4">
            {{ $successMessage }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="bg-gray-800 rounded-lg p-4 mb-8">
        <h3 class="text-lg font-semibold mb-4">Crear Nuevo Cliente</h3>

        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm mb-1">Nombre</label>
                <input type="text" wire:model.defer="nombre" class="w-full bg-gray-900 border-gray-700 text-gray-200 rounded px-3 py-2">
                @error('nombre') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm mb-1">CUIT</label>
                <input type="text" wire:model.defer="cuit" class="w-full bg-gray-900 border-gray-700 text-gray-200 rounded px-3 py-2">
                @error('cuit') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm mb-1">Domicilio</label>
                <input type="text" wire:model.defer="domicilio" class="w-full bg-gray-900 border-gray-700 text-gray-200 rounded px-3 py-2">
                @error('domicilio') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm mb-1">Ciudad</label>
                <input type="text" wire:model.defer="ciudad" class="w-full bg-gray-900 border-gray-700 text-gray-200 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm mb-1">Provincia</label>
                <input type="text" wire:model.defer="provincia" class="w-full bg-gray-900 border-gray-700 text-gray-200 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm mb-1">Categoría</label>
                <input type="text" wire:model.defer="categoria" class="w-full bg-gray-900 border-gray-700 text-gray-200 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm mb-1">Convenio</label>
                <input type="text" wire:model.defer="convenio" class="w-full bg-gray-900 border-gray-700 text-gray-200 rounded px-3 py-2">
            </div>
        </div>

        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded">
            Guardar Cliente
        </button>
    </form>

    <div class="bg-gray-800 rounded-lg p-4">
        <h3 class="text-lg font-semibold mb-4">Listado de Clientes</h3>

        @if($clientes->count())
            <table class="min-w-full text-sm">
                <thead class="bg-gray-700 text-gray-300">
                    <tr>
                        <th class="px-4 py-2 text-left">Nombre</th>
                        <th class="px-4 py-2 text-left">CUIT</th>
                        <th class="px-4 py-2 text-left">Domicilio</th>
                        <th class="px-4 py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $cliente)
                        <tr class="border-b border-gray-700 hover:bg-gray-800">
                            <td class="px-4 py-2">{{ $cliente->nombre }}</td>
                            <td class="px-4 py-2">{{ $cliente->cuit }}</td>
                            <td class="px-4 py-2">{{ $cliente->domicilio }}</td>
                            <td class="px-4 py-2">
                                <div class="flex space-x-2">
                                    <a href="{{ route('clientes.edit', $cliente->id) }}" class="text-blue-400 hover:underline">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    </a>
                                    <button wire:click="delete({{ $cliente->id }})"
                                            onclick="return confirm('¿Está seguro de que desea eliminar esta empresa?')"
                                            class="text-red-400 hover:text-red-300"
                                            title="Eliminar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    <a href="{{ route('clientes.empresas-asociadas', $cliente->id) }}"
                                            class="text-green-600 hover:text-green-300"
                                            title="Empresas Asociadas">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                            </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $clientes->links() }}
            </div>
        @else
            <p class="text-gray-400">No hay clientes registrados.</p>
        @endif
    </div>
</div>
