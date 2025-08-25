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
                    <th class="px-4 py-2">Cliente</th>
                    <th class="px-4 py-2">Cargo</th>
                    <th class="px-4 py-2">Puesto</th>
                    <th class="px-4 py-2">Convenio</th>
                    <th class="px-4 py-2">Fecha Ingreso</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($personal as $p)
                    <tr class="border-b border-gray-700 hover:bg-gray-800">
                        <td class="px-4 py-2 text-center">{{ $p->nombre ?? '-' }}</td>
                        <td class="px-4 py-2 text-center">{{ $p->apellido ?? '-' }}</td>
                        <td class="px-4 py-2 text-center">{{ $p->nro_doc ?? '-' }}</td>
                        <td class="px-4 py-2 text-center">
                            {{ $p->cliente->nombre ?? 'Sin cliente' }}
                        </td>
                        <td class="px-4 py-2 text-center">{{ $p->cargo ?? '-' }}</td>
                        <td class="px-4 py-2 text-center">{{ $p->puesto ?? 'Sin definir' }}</td>
                        <td class="px-4 py-2 text-center">{{ $p->convenio ?? '-' }}</td>
                        <td class="px-4 py-2 text-center">{{ $p->fecha_ing ?? 'Sin definir' }}</td>
                        <td class="px-4 py-2 text-center">
                            <button wire:click="edit({{ $p->id }})" 
                                    class="text-blue-400 hover:text-blue-300"
                                    title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </td>
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
