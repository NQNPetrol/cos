<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Listado de Eventos</h2>
                        <a href="{{ route('eventos.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            <i class="bi bi-plus-circle mr-2"></i>Nuevo Evento
                        </a>
                    </div>

                    <!-- Filtros -->
                    <form method="GET" action="{{ route('eventos.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Filtro por cliente -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Cliente</label>
                                <input type="text" name="cliente" value="{{ request('cliente') }}" 
                                       placeholder="Buscar por cliente..."
                                       class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2">
                            </div>
                            
                            <!-- Filtro por fecha desde -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Desde</label>
                                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                                       class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2">
                            </div>
                            
                            <!-- Filtro por fecha hasta -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Hasta</label>
                                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                                       class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2">
                            </div>
                            
                            <!-- Botones -->
                            <div class="flex items-end space-x-2 md:col-span-3">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    <i class="bi bi-funnel mr-2"></i>Filtrar
                                </button>
                                <a href="{{ route('eventos.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                    <i class="bi bi-arrow-counterclockwise mr-2"></i>Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Tabla de resultados -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full dark:divide-gray-700">
                            <thead class="bg-gray-800 text-gray-300 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Categoría</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ubicación</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Registrado por</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="dark:bg-gray-800 ">
                                @forelse($eventos as $evento)
                                <tr class="hover:bg-gray-600 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 dark:text-gray-300">{{ str_pad($evento->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-300 dark:text-gray-300">
                                        <a href="#" class="text-blue-400 hover:text-blue-300">{{ $evento->categoria?->nombre ?? 'Sin categoría' }}</a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 dark:text-gray-300">
                                        {{ $evento->cliente->nombre ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 dark:text-gray-300">
                                        @if ($evento->ubicacion)
                                            <a href="{{ $evento->ubicacion }}" target="_blank" class="text-blue-400 hover:underline">
                                                Ver ubicación
                                            </a>
                                        @else
                                            <span class="text-gray-400">Sin ubicación</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 dark:text-gray-300">
                                        {{ $evento->creador->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('eventos.edit', $evento) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400"
                                                title="Editar">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('eventos.destroy', $evento) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    onclick="return confirm('¿Estás seguro de eliminar este evento?')"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400"
                                                    title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No se encontraron eventos registrados
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación -->
                    <div class="mt-4">
                        {{ $eventos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>