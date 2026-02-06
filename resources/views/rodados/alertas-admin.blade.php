<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-zinc-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-100">Alertas</h2>
                            <p class="text-gray-400 mt-1">Crea alertas personalizables para el dashboard y notificaciones</p>
                        </div>
                        <button onclick="document.getElementById('modal-alerta').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Nueva Alerta
                        </button>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-800 border border-green-600 text-green-100 rounded-lg">{{ session('success') }}</div>
                    @endif

                    <!-- Alertas List -->
                    <div class="space-y-4">
                        @forelse($alertas as $alerta)
                        <div class="bg-zinc-800 p-5 rounded-lg border border-zinc-700 {{ !$alerta->activa ? 'opacity-50' : '' }} hover:border-zinc-500 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <h3 class="text-lg font-semibold text-gray-200">{{ $alerta->titulo }}</h3>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                            @if($alerta->tipo === 'vencimiento_pago') bg-red-900/50 text-red-300
                                            @elseif($alerta->tipo === 'recordatorio_turno') bg-blue-900/50 text-blue-300
                                            @elseif($alerta->tipo === 'agendar_turno_km') bg-orange-900/50 text-orange-300
                                            @else bg-purple-900/50 text-purple-300
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $alerta->tipo)) }}
                                        </span>
                                        @if($alerta->recurrente)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-cyan-900/50 text-cyan-300">
                                            Recurrente: {{ $alerta->frecuencia_recurrencia }}
                                        </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-400 mt-1">{{ $alerta->descripcion ?? 'Sin descripción' }}</p>
                                    <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                        @if($alerta->rodado)
                                            <span>Vehículo: {{ $alerta->rodado->patente ?? 'N/A' }}</span>
                                        @endif
                                        @if($alerta->cliente)
                                            <span>Cliente: {{ $alerta->cliente->nombre }}</span>
                                        @endif
                                        @if($alerta->km_intervalo)
                                            <span>Cada {{ number_format($alerta->km_intervalo) }} km</span>
                                        @endif
                                        @if($alerta->fecha_alerta)
                                            <span>Fecha: {{ $alerta->fecha_alerta->format('d/m/Y') }}</span>
                                        @endif
                                        <span>Acciones: {{ implode(', ', $alerta->getAcciones()) }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 ml-4">
                                    <form action="{{ route('rodados.alertas-admin.toggle', $alerta) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 text-xs rounded-md {{ $alerta->activa ? 'bg-green-900 text-green-300' : 'bg-zinc-700 text-gray-400' }} hover:opacity-80 transition" title="{{ $alerta->activa ? 'Desactivar' : 'Activar' }}">
                                            {{ $alerta->activa ? 'Activa' : 'Inactiva' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('rodados.alertas-admin.destroy', $alerta) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta alerta?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <p class="mt-2 text-gray-500">No hay alertas creadas. Crea tu primera alerta.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nueva Alerta -->
    <div id="modal-alerta" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-zinc-900 rounded-lg w-full max-w-lg border border-zinc-700 max-h-[85vh] overflow-y-auto">
            <form method="POST" action="{{ route('rodados.alertas-admin.store') }}">
                @csrf
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-100 mb-4">Nueva Alerta</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Título *</label>
                            <input type="text" name="titulo" required class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Descripción</label>
                            <textarea name="descripcion" rows="2" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Tipo *</label>
                            <select name="tipo" id="tipo-alerta" required class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm" onchange="toggleAlertaFields()">
                                <option value="vencimiento_pago">Vencimiento de Pago</option>
                                <option value="recordatorio_turno">Recordatorio de Turno</option>
                                <option value="agendar_turno_km">Agendar Turno por KM</option>
                                <option value="personalizada">Personalizada</option>
                            </select>
                        </div>
                        <div id="km-field" class="hidden">
                            <label class="block text-sm font-medium text-gray-400 mb-1">Intervalo KM</label>
                            <input type="number" name="km_intervalo" min="1" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm" placeholder="Ej: 10000">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Vehículo (opcional)</label>
                            <select name="rodado_id" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                                <option value="">Ninguno</option>
                                @foreach($rodados as $rodado)
                                    <option value="{{ $rodado->id }}">{{ $rodado->patente ?? $rodado->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Cliente (opcional)</label>
                            <select name="cliente_id" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                                <option value="">Ninguno</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Fecha de Alerta</label>
                            <input type="date" name="fecha_alerta" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="recurrente" value="1" class="rounded bg-zinc-700 border-zinc-600 text-blue-600" onchange="document.getElementById('frecuencia-field').classList.toggle('hidden')">
                                <span class="text-sm text-gray-400">Recurrente</span>
                            </label>
                        </div>
                        <div id="frecuencia-field" class="hidden">
                            <label class="block text-sm font-medium text-gray-400 mb-1">Frecuencia</label>
                            <select name="frecuencia_recurrencia" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                                <option value="diaria">Diaria</option>
                                <option value="semanal">Semanal</option>
                                <option value="mensual" selected>Mensual</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Acciones *</label>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="acciones[]" value="dashboard" checked class="rounded bg-zinc-700 border-zinc-600 text-blue-600">
                                    <span class="text-sm text-gray-300">Mostrar en Dashboard</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="acciones[]" value="notificacion" class="rounded bg-zinc-700 border-zinc-600 text-blue-600">
                                    <span class="text-sm text-gray-300">Enviar Notificación del Sistema</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="acciones[]" value="correo" class="rounded bg-zinc-700 border-zinc-600 text-blue-600">
                                    <span class="text-sm text-gray-300">Enviar Correo Electrónico</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 p-4 bg-zinc-800 rounded-b-lg">
                    <button type="button" onclick="document.getElementById('modal-alerta').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-400 hover:text-gray-200 transition">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition">Crear Alerta</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleAlertaFields() {
            const tipo = document.getElementById('tipo-alerta').value;
            document.getElementById('km-field').classList.toggle('hidden', tipo !== 'agendar_turno_km');
        }
    </script>
    @endpush
</x-app-layout>
