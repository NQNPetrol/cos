<div class="bg-gray-900 text-gray-100 p-6 rounded-lg shadow">

    <h2 class="text-2xl font-bold mb-6">Listado de Personal</h2>

    <div class="mb-4">
        <label class="block text-sm mb-1">Buscar por Nombre o Apellido</label>
        <input type="text"
               wire:model.debounce.500ms="search"
               placeholder="Ingrese nombre o apellido"
               class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-800 text-gray-300">
                <tr>
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Apellido</th>
                    <th class="px-4 py-2">Documento</th>
                    <th class="px-4 py-2">Puesto</th>
                    <th class="px-4 py-2">Estado</th>
                    <th class="px-4 py-2">Fecha Inicio</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($personal as $p)
                    <tr class="border-b border-gray-700 hover:bg-gray-800">
                        <td class="px-4 py-2 text-center">{{ $p->nombre }}</td>
                        <td class="px-4 py-2 text-center">{{ $p->apellido }}</td>
                        <td class="px-4 py-2 text-center">{{ $p->documento }}</td>
                        <td class="px-4 py-2 text-center">{{ $p->puesto }}</td>
                        <td class="px-4 py-2 text-center">{{ ucfirst($p->estado) }}</td>
                        <td class="px-4 py-2 text-center">{{ $p->fecha_inicio }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center px-4 py-2 text-gray-400">No hay registros.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $personal->links() }}
    </div>
</div>
