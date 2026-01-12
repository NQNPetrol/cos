<div class="max-w-5xl mx-auto p-6 bg-zinc-900 text-gray-50 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6">Administrar Clientes</h2>

    @if ($successMessage)
        <div class="bg-blue-600 text-white p-3 rounded mb-4">
            {{ $successMessage }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="bg-zinc-800 rounded-lg p-4 mb-8">
        <h3 class="text-lg font-semibold mb-4">Crear Nuevo Cliente</h3>

        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm mb-1">Nombre</label>
                <input type="text" wire:model.defer="nombre" class="w-full bg-zinc-900 border-zinc-700 text-gray-200 rounded px-3 py-2">
                @error('nombre') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm mb-1">CUIT</label>
                <input type="text" wire:model.defer="cuit" class="w-full bg-zinc-900 border-zinc-700 text-gray-200 rounded px-3 py-2">
                @error('cuit') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm mb-1">Domicilio</label>
                <input type="text" wire:model.defer="domicilio" class="w-full bg-zinc-900 border-zinc-700 text-gray-200 rounded px-3 py-2">
                @error('domicilio') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm mb-1">Ciudad</label>
                <input type="text" wire:model.defer="ciudad" class="w-full bg-zinc-900 border-zinc-700 text-gray-200 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm mb-1">Provincia</label>
                <input type="text" wire:model.defer="provincia" class="w-full bg-zinc-900 border-zinc-700 text-gray-200 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm mb-1">Categoría</label>
                <input type="text" wire:model.defer="categoria" class="w-full bg-zinc-900 border-zinc-700 text-gray-200 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm mb-1">Convenio</label>
                <input type="text" wire:model.defer="convenio" class="w-full bg-zinc-900 border-zinc-700 text-gray-200 rounded px-3 py-2">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm mb-2 font-medium text-gray-300">Logo (PNG)</label>
                
                <!-- Input file con diseño consistente -->
                <div class="relative">
                    <input type="file" wire:model.defer="logo" accept=".png" 
                           class="w-full bg-zinc-900 border border-zinc-700 text-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-zinc-700 file:text-gray-300 hover:file:bg-zinc-600 cursor-pointer">
                </div>
                
                @error('logo') 
                    <span class="text-red-400 text-sm mt-2 block">{{ $message }}</span> 
                @enderror
                
                @if ($logo)
                    <div class="mt-4 p-3 bg-zinc-900 rounded-lg border border-zinc-700">
                        <p class="text-sm text-gray-400 mb-2">Vista previa:</p>
                        <img src="{{ $logo->temporaryUrl() }}" class="h-24 object-contain bg-white rounded-lg p-2">
                    </div>
                @endif
                
                <p class="text-xs text-gray-400 mt-2">Formatos aceptados: PNG. Tamaño máximo: 2MB</p>
            </div>
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Guardar Cliente
        </button>
    </form>

    <div class="bg-zinc-800 rounded-lg p-4">
        <h3 class="text-lg font-semibold mb-4">Listado de Clientes</h3>

        @if($clientes->count())
            <table class="min-w-full text-sm">
                <thead class="bg-zinc-700 text-gray-300">
                    <tr>
                        <th class="px-4 py-2 text-left">Nombre</th>
                        <th class="px-4 py-2 text-left">CUIT</th>
                        <th class="px-4 py-2 text-left">Domicilio</th>
                        <th class="px-4 py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $cliente)
                        <tr class="border-b border-zinc-700 hover:bg-zinc-800">
                            <td class="px-4 py-2">{{ $cliente->nombre }}</td>
                            <td class="px-4 py-2">{{ $cliente->cuit }}</td>
                            <td class="px-4 py-2">{{ $cliente->domicilio }}</td>
                            <td class="px-4 py-2">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('clientes.edit', $cliente->id) }}" class="text-blue-400 hover:text-blue-300 p-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    </a>
                                    <button wire:click="delete({{ $cliente->id }})"
                                            onclick="return confirm('¿Está seguro de que desea eliminar este cliente?')"
                                            class="text-red-400 hover:text-red-300 p-1"
                                            title="Eliminar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    <a href="{{ route('cliente-empresas-asociadas.index', $cliente->id) }}"
                                       class="flex items-center text-green-400 hover:text-green-300 p-1"
                                       title="Ver empresas asociadas">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span class="text-xs ml-1">Empresas ({{ $cliente->empresas_asociadas_count }})
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
