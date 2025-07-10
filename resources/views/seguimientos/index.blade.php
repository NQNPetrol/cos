<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl font-semibold mb-6">Listado de Seguimientos</h2>
                    
                    <!-- Filtros -->
                    <form method="GET" action="{{ route('seguimientos.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Filtro por estado -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Estado</label>
                                <select name="estado" class="w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                    <option value="">Todos</option>
                                    <option value="ABIERTO" {{ request('estado') == 'ABIERTO' ? 'selected' : '' }}>Abierto</option>
                                    <option value="EN REVISION" {{ request('estado') == 'EN REVISION' ? 'selected' : '' }}>En Revisión</option>
                                    <option value="CERRADO" {{ request('estado') == 'CERRADO' ? 'selected' : '' }}>Cerrado</option>
                                </select>
                            </div>
                            
                            <!-- Filtro por evento -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Evento</label>
                                <select name="evento_id" class="w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                    <option value="">Todos</option>
                                    @foreach($eventos as $evento)
                                        <option value="{{ $evento->id }}" {{ request('evento_id') == $evento->id ? 'selected' : '' }}>
                                            {{ $evento->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Búsqueda -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Buscar</label>
                                <input type="text" name="busqueda" value="{{ request('busqueda') }}" 
                                       placeholder="Título o descripción..."
                                       class="w-full rounded-md bg-gray-700 border-gray-600 text-white">
                            </div>
                            
                            <!-- Botones -->
                            <div class="flex items-end space-x-2">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                                    Filtrar
                                </button>
                                <a href="{{ route('seguimientos.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md">
                                    Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Tabla de resultados -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Título</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Evento</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($seguimientos as $seguimiento)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $seguimiento->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $seguimiento->titulo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $seguimiento->evento->nombre ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $seguimiento->estado == 'CERRADO' ? 'bg-green-100 text-green-800' : 
                                               ($seguimiento->estado == 'EN REVISION' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ $seguimiento->estado }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $seguimiento->fecha->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">Ver</a>
                                        <a href="#" class="ml-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Editar</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación -->
                    <div class="mt-4">
                        {{ $seguimientos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>