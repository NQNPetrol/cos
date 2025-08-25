<div class="bg-gray-900 text-gray-100 p-6 rounded-lg shadow">

    <h2 class="text-2xl font-bold mb-6">Listado de Personal</h2>


    <div class="flex flex-col md:flex-row gap-4 mb-6 items-end">
        <!-- Buscar -->
        <div class="flex-1 min-w-[150px]">
            <label class="block text-sm mb-1">Buscar</label>
            <input type="text"
                wire:model.live="search"
                placeholder="Nombre o apellido"
                class="w-full bg-gray-800 border border-gray-700 rounded px-3 py-2 text-gray-200 text-sm">
        </div>

        <!-- Filtro por Cliente -->
        <div class="flex-1 min-w-[180px]">
            <label class="block text-sm mb-1">Cliente</label>
            <select wire:model="cliente_id" class="w-full bg-gray-800 border border-gray-700 rounded px-3 py-2 text-gray-200 text-sm">
                <option value="">Todos los clientes</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Filtro por Convenio -->
        <div class="flex-1 min-w-[150px]">
            <label class="block text-sm mb-1">Convenio</label>
            <select wire:model="convenio" class="w-full bg-gray-800 border border-gray-700 rounded px-3 py-2 text-gray-200 text-sm">
                <option value="">Todos</option>
                @foreach($convenios as $convenioItem)
                    <option value="{{ $convenioItem }}">{{ $convenioItem }}</option>
                @endforeach
            </select>
        </div>

   
        <div class="flex space-x-2">
            <button wire:click="aplicarFiltros" 
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded transition-colors text-sm whitespace-nowrap">
                Aplicar
            </button>
            <button wire:click="limpiarFiltros" 
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded transition-colors text-sm whitespace-nowrap">
                Limpiar
            </button>
        </div>
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
