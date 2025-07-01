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
                <label class="block text-sm mb-1">Nombre *</label>
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
                                <a href="{{ route('clientes.edit', $cliente->id) }}" class="text-blue-400 hover:underline">
                                    Editar
                                </a>
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
