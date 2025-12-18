<div class="bg-zinc-900 text-gray-50 p-6 rounded-lg shadow">

    <h2 class="text-2xl font-bold mb-6">Listado de Contratos</h2>

    <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0 mb-6">
        <div class="flex-1">
            <label class="block text-sm mb-1">Filtrar por Cliente</label>
            <select wire:model.live="searchCliente"
                    class="w-full bg-zinc-800 border-zinc-700 rounded px-3 py-2 text-gray-200">
                <option value="">Todos</option>
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex-1">
            <label class="block text-sm mb-1">Buscar</label>
            <input type="text" wire:model.live="searchNombre"
                   class="w-full bg-zinc-800 border-zinc-700 rounded px-3 py-2 text-gray-200"
                   placeholder="Buscar por nombre, empresa asociada...">
        </div>

        <div class="flex items-end">
            <button wire:click="clearFilters" 
                    class="bg-zinc-600 hover:bg-zinc-700 px-4 py-2 rounded text-white">
                Limpiar Filtros
            </button>
        </div>
    
    </div>

    <div class="overflow-x-auto"> 
        <table class="min-w-full text-sm">
            <thead class="bg-zinc-800 text-gray-300">
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
                    <th class="px-4 py-2">Empresa Asociada al Cliente</th>
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
                    <th class="px-4 py-2">Observaciones</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($contratos as $contrato)
                    <tr class="border-b border-zinc-700 hover:bg-zinc-800">
                        <td class="px-4 py-2 text-center">{{ $contrato->nombre_proyecto }}</td>
                        <td class="px-4 py-2 text-center">{{ $contrato->cliente?->nombre ?? '-' }}</td>
                        <td class="px-4 py-2 text-center">{{ $contrato->empresaAsociada?->nombre ?? '-' }}</td>
                        <td class="px-4 py-2 text-center">{{ $contrato->fecha_inicio }}</td>
                        <td class="px-4 py-2 text-center">{{ $contrato->observaciones ?? '-' }}</td>
                        
                        <td class="px-4 py-2 text-center">
                            <a href="{{ route('contratos.edit', $contrato->id) }}" class="text-blue-400 hover:underline" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button wire:click="delete({{ $contrato->id }})"
                                    onclick="return confirm('¿Está seguro de que desea eliminar este contrato?')"
                                    class="text-red-400 hover:text-red-300"
                                    title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
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
