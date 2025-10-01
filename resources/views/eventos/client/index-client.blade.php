@extends('layouts.cliente')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100 dark:text-gray-100">

                    <!-- Mensajes de sesión -->
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-800 border border-green-600 text-green-100 rounded-lg flex items-center justify-between shadow-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button type="button" class="text-green-300 hover:text-white" onclick="this.parentElement.style.display='none'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-800 border border-red-600 text-red-100 rounded-lg flex items-center justify-between shadow-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ session('error') }}</span>
                            </div>
                            <button type="button" class="text-red-300 hover:text-white" onclick="this.parentElement.style.display='none'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="mb-6 p-4 bg-blue-800 border border-blue-600 text-blue-100 rounded-lg flex items-center justify-between shadow-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ session('info') }}</span>
                            </div>
                            <button type="button" class="text-blue-300 hover:text-white" onclick="this.parentElement.style.display='none'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="mb-6 p-4 bg-yellow-800 border border-yellow-600 text-yellow-100 rounded-lg flex items-center justify-between shadow-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <span>{{ session('warning') }}</span>
                            </div>
                            <button type="button" class="text-yellow-300 hover:text-white" onclick="this.parentElement.style.display='none'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-800 border border-red-600 text-red-100 rounded-lg flex items-center justify-between shadow-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <ul class="list-disc list-inside">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="text-red-300 hover:text-white" onclick="this.parentElement.style.display='none'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Listado de Eventos</h2>
                        <a href="{{ route('client.eventos.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            <i class="bi bi-plus-circle mr-2"></i>Nuevo Evento
                        </a>
                    </div>

                    <!-- Filtros -->
                    <form method="GET" action="{{ route('eventos.index') }}" class="mb-6">
                        <div class="flex flex-wrap items-end gap-4">
                            
                            <!-- Filtro por fecha desde -->
                            <div class="flex-1 min-w-[220px]">
                                <label class="block text-sm font-medium text-gray-300 mb-1">Desde</label>
                                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                                       class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2">
                            </div>
                            
                            <!-- Filtro por fecha hasta -->
                            <div class="flex-1 min-w-[220px]">
                                <label class="block text-sm font-medium text-gray-300 mb-1">Hasta</label>
                                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                                       class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2">
                            </div>
                            
                            <!-- Boton Filtrar -->
                            <div class="flex-1 min-w-[50px]">
                                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    <i class="bi bi-funnel mr-2"></i>Filtrar
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Mostrar filtros activos -->
                    @if(request()->hasAny(['cliente_id', 'fecha_desde', 'fecha_hasta']))
                        <div class="mb-4 p-3 bg-blue-900 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-blue-100">
                                    <span class="font-medium">Filtros activos:</span>
                                    @if(request('cliente_id'))
                                        @php
                                            $clienteFiltro = $clientes->firstWhere('id', request('cliente_id'));
                                        @endphp
                                        <span class="ml-2 px-2 py-1 bg-blue-800 rounded">Cliente: {{ $clienteFiltro->nombre ?? 'ID'.request('cliente_id') }}</span>
                                    @endif
                                    @if(request('fecha_desde'))
                                        <span class="ml-2 px-2 py-1 bg-blue-800 rounded">Desde: {{ request('fecha_desde') }}</span>
                                    @endif
                                    @if(request('fecha_hasta'))
                                        <span class="ml-2 px-2 py-1 bg-blue-800 rounded">Hasta: {{ request('fecha_hasta') }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('eventos.index') }}" class="text-blue-300 hover:text-blue-100 text-sm">
                                    <i class="bi bi-x-circle mr-1"></i>Limpiar filtros
                                </a>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Tabla de resultados -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full dark:divide-gray-700">
                            <thead class="bg-gray-800 text-gray-300 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Categoría</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Empresa</th>
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
                                        {{ $evento->empresaAsociada->nombre ?? 'N/A' }}
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
                                            <a href="{{ route('eventos.reporte.preview', $evento) }}"
                                                class="text-green-600 hover:text-green-900 dark:text-green-400"
                                                title="Generar Reporte">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>
                                            <a href="{{ route('client.eventos.edit', $evento) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400"
                                                title="Editar">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('client.eventos.destroy', $evento) }}" method="POST" class="inline">
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
                    
                    <!-- Información de resultados y paginación -->
                    @if($eventos->count() > 0)
                        <div class="mt-4 flex items-center justify-between">
                            <div class="text-sm text-gray-400">
                                Mostrando {{ $eventos->firstItem() }} a {{ $eventos->lastItem() }} 
                                de {{ $eventos->total() }} resultados
                            </div>
                            <div>
                                {{ $eventos->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection