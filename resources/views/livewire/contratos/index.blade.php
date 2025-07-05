<div class="bg-gray-900 text-gray-50 p-6 rounded-lg shadow">

    <h2 class="text-2xl font-bold mb-6">Listado de Contratos</h2>

    <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0 mb-6">
        <div class="flex-1">
            <label class="block text-sm mb-1">Filtrar ppor Cliente</label>
            <select wire:change="filtrarCliente($event.target.value)"
                    class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                <option value="">Todos</option>
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex-1">
            <label class="block text-sm mb-1">Filtrar por Nombre del Proyecto</label>
            <input type="text" wire:model.debounce.500ms="searchNombre"
                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200"
                   placeholder="Buscar por nombre">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-800 text-gray-300">
                <tr>
                    <th class="px-4 py-2 cursor-pointer" wire:click="sortBy('nombre_proyecto')">
                        Nombre del Proyecto
                        @if ($sortField == 'nombre_proyecto')
                            @if ($sortDirection == 'asc')
                                ▲
                            @else
                                ▼
                            @endif
                        @endif
                    </th>
                    <th class="px-4 py-2">Cliente</th>
                    <th class="px-4 py-2 cursor-pointer" wire:click="sortBy('fecha_inicio')">
                        Fecha Inicio
                        @if ($sortField == 'fecha_inicio')
                            @if ($sortDirection == 'asc')
                                ▲
                            @else
                                ▼
                            @endif
                        @endif
                    </th>
                    
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($contratos as $contrato)
                    <tr class="border-b border-gray-700 hover:bg-gray-800">
                        <td class="px-4 py-2 text-center">{{ $contrato->nombre_proyecto }}</td>
                        <td class="px-4 py-2 text-center">{{ $contrato->cliente?->nombre ?? '-' }}</td>
                        <td class="px-4 py-2 text-center">{{ $contrato->fecha_inicio }}</td>
                        
                        <td class="px-4 py-2 text-center">
                            <a href="{{ route('contratos.edit', $contrato->id) }}" class="text-blue-400 hover:underline">
                                Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center text-gray-400">No hay contratos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $contratos->links() }}
    </div>
</div>
