<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">
            Empresas Asociadas de {{ $cliente->nombre }}
        </h2>
        <div class="flex space-x-2">
            <input wire:model.live.debounce.300ms="search" 
                   placeholder="Buscar empresas..." 
                   class="px-3 py-2 border rounded">
            <a href="{{ route('empresas-asociadas.create', $cliente->id) }}" 
               class="px-4 py-2 bg-blue-500 text-white rounded">
                Nueva Empresa
            </a>
        </div>
    </div>

    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr>
                <th class="px-6 py-3">Nombre</th>
                <th class="px-6 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($empresas as $empresa)
            <tr>
                <td class="px-6 py-4">{{ $empresa->nombre }}</td>
                <td class="px-6 py-4 text-right">
                    <button wire:click="eliminarEmpresa({{ $empresa->id }})" 
                            onclick="return confirm('¿Eliminar esta empresa asociada?')"
                            class="text-red-500 hover:text-red-700">
                        Eliminar
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="px-6 py-4 text-center">
                    No se encontraron empresas asociadas
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $empresas->links() }}
</div>
