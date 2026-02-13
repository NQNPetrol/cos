@extends('layouts.cliente')
@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-gray-100">
                <div class="text-gray-100">

                    <!-- Mensajes de sesión -->
                    @if(session('success'))
                        <div class="mb-5 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-sm">{{ session('success') }}</span>
                            </div>
                            <button type="button" class="text-emerald-400/60 hover:text-emerald-300" onclick="this.parentElement.style.display='none'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-5 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-sm">{{ session('error') }}</span>
                            </div>
                            <button type="button" class="text-red-400/60 hover:text-red-300" onclick="this.parentElement.style.display='none'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="mb-5 p-4 bg-blue-500/10 border border-blue-500/20 text-blue-400 rounded-xl flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-sm">{{ session('info') }}</span>
                            </div>
                            <button type="button" class="text-blue-400/60 hover:text-blue-300" onclick="this.parentElement.style.display='none'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="mb-5 p-4 bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-xl flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                <span class="text-sm">{{ session('warning') }}</span>
                            </div>
                            <button type="button" class="text-amber-400/60 hover:text-amber-300" onclick="this.parentElement.style.display='none'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-5 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div class="text-sm">
                                    <ul class="list-disc list-inside">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="text-red-400/60 hover:text-red-300" onclick="this.parentElement.style.display='none'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-gradient-to-br from-blue-600/20 to-blue-400/10 rounded-xl border border-blue-500/20">
                                <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">Listado de Eventos</h1>
                                <p class="text-gray-400 text-sm">Registro de eventos del sistema</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <!-- Toggle Paginación -->
                            <a href="{{ route('client.eventos.index', array_merge(request()->except('paginate', 'page'), $isPaginated ? [] : ['paginate' => 1])) }}" 
                               class="px-3.5 py-2 {{ $isPaginated ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-zinc-700 hover:bg-zinc-600' }} text-white rounded-xl text-sm transition-all flex items-center"
                               title="{{ $isPaginated ? 'Desactivar paginación (mostrar todos)' : 'Activar paginación (15 por página)' }}">
                                <i class="bi {{ $isPaginated ? 'bi-list-check' : 'bi-list' }} mr-2"></i>
                                {{ $isPaginated ? 'Paginado' : 'Sin paginar' }}
                            </a>
                            <a href="{{ route('client.eventos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2 transition-all shadow-lg shadow-blue-600/20">
                                <i class="bi bi-plus-circle mr-2"></i>Nuevo Evento
                            </a>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <form method="GET" action="{{ route('eventos.index') }}" class="mb-5 p-4 bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50">
                        <div class="flex flex-wrap items-end gap-4">
                            
                            <!-- Filtro por fecha desde -->
                            <div class="flex-1 min-w-[220px]">
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Desde</label>
                                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                                       class="w-full rounded-lg bg-zinc-900 border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                            </div>
                            
                            <!-- Filtro por fecha hasta -->
                            <div class="flex-1 min-w-[220px]">
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Hasta</label>
                                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                                       class="w-full rounded-lg bg-zinc-900 border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                            </div>
                            
                            <!-- Boton Filtrar -->
                            <div class="flex-1 min-w-[50px]">
                                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 text-sm transition-all">
                                    <i class="bi bi-funnel mr-2"></i>Filtrar
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Mostrar filtros activos -->
                    @if(request()->hasAny(['cliente_id', 'fecha_desde', 'fecha_hasta']))
                        <div class="mb-5 p-3 bg-blue-500/10 border border-blue-500/20 rounded-xl">
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
                    <div class="bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50 overflow-hidden shadow-xl">
                        <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">ID</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Categoría</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Empresa</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Ubicación</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Fecha</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Registrado por</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-700/30">
                                @forelse($eventos as $evento)
                                <tr class="group hover:bg-zinc-700/30 transition-colors duration-150">
                                    <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-300">{{ str_pad($evento->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm font-medium text-gray-300">
                                        <a href="#" class="text-blue-400 hover:text-blue-300">{{ $evento->categoria?->nombre ?? 'Sin categoría' }}</a>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $evento->empresaAsociada->nombre ?? 'N/A' }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-300">
                                        @if ($evento->ubicacion)
                                            <a href="{{ $evento->ubicacion }}" target="_blank" class="text-blue-400 hover:underline">
                                                Ver ubicación
                                            </a>
                                        @else
                                            <span class="text-gray-400">Sin ubicación</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ \Carbon\Carbon::parse($evento->fecha_hora)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $evento->creador->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('client.eventos.reporte.preview', $evento) }}"
                                                class="text-green-600 hover:text-green-900 dark:text-green-400"
                                                title="Generar Reporte">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>
                                            <button onclick="abrirModalNotas({{ $evento->id }})"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400"
                                                title="Agregar notas adicionales"
                                                data-notas="{{ e($evento->notas_adicionales ?? '') }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <!-- Botón para anular evento -->
                                            <form action="{{ route('client.eventos.anular', $evento) }}" method="POST" class="inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400"
                                                        title="Anular Evento"
                                                        onclick="return confirm('¿Estás seguro de que deseas anular este evento? Esta acción no se puede deshacer.')">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-4 text-center text-sm text-gray-500">
                                        No se encontraron eventos registrados
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                    <!-- Información de resultados y paginación -->
                    @if($eventos->count() > 0)
                        <div class="px-5 py-3 border-t border-zinc-700/50 flex items-center justify-between">
                            <div class="text-sm text-gray-400">
                                @if($isPaginated)
                                    Mostrando {{ $eventos->firstItem() }} a {{ $eventos->lastItem() }} 
                                    de {{ $eventos->total() }} resultados
                                @else
                                    Mostrando todos los {{ $eventos->total() }} resultados
                                @endif
                            </div>
                            @if($isPaginated && $eventos->hasPages())
                            <div>
                                {{ $eventos->links() }}
                            </div>
                            @endif
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal para Notas Adicionales -->
        <div id="modalNotas" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden">
            <div class="bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl w-full max-w-2xl">
                <div class="bg-zinc-800/50 border-b border-zinc-700/50 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-white">Agregar Notas Adicionales</h3>
                    <button type="button" 
                            class="text-gray-400 hover:text-gray-200 bg-transparent rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                            onclick="cerrarModalNotas()">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span class="sr-only">Cerrar modal</span>
                    </button>
                </div>

                <div class="p-6">
                    <form id="formNotas" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="notas_adicionales" class="block text-sm font-medium text-gray-300 mb-2">
                                    Notas Adicionales
                                </label>
                                <textarea name="notas_adicionales" id="notas_adicionales" rows="6" required
                                        class="w-full px-3 py-2 bg-zinc-700 border border-zinc-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                        placeholder="Ingrese las notas adicionales para este evento..."></textarea>
                                <p class="text-xs text-gray-400 mt-1">Máximo 1000 caracteres</p>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" 
                                    class="px-4 py-2 bg-zinc-600 hover:bg-zinc-500 rounded-md text-white transition-colors"
                                    onclick="cerrarModalNotas()">
                                Cancelar
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-md text-white transition-colors">
                                Guardar Notas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        let notasActuales = '';
        // Funciones para el modal de notas
        function abrirModalNotas(eventoId) {
            // Construir la URL manualmente usando la estructura correcta
            document.getElementById('formNotas').action = `/client/eventos/${eventoId}/notas-adicionales`;
            
            const boton = event.target.closest('button');
            const notas = boton.getAttribute('data-notas') || '';

            document.getElementById('notas_adicionales').value = notas;
            document.getElementById('modalNotas').classList.remove('hidden');
            document.getElementById('notas_adicionales').focus();
        }

        function cerrarModalNotas() {
            document.getElementById('modalNotas').classList.add('hidden');
            document.getElementById('notas_adicionales').value = '';
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            const modal = document.getElementById('modalNotas');
            if (event.target === modal) {
                cerrarModalNotas();
            }
        }

        // Cerrar modal con ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                cerrarModalNotas();
            }
        });

        // Validación de longitud de texto
        document.getElementById('notas_adicionales').addEventListener('input', function() {
            if (this.value.length > 1000) {
                this.value = this.value.substring(0, 1000);
            }
        });
    </script>
@endsection