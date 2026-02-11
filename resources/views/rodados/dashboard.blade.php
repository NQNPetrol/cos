<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-zinc-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-100">Dashboard Administración</h2>
                            <p class="text-gray-400 mt-1">Resumen general de operaciones, pagos y alertas</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="document.getElementById('filtros-panel').classList.toggle('hidden')" class="inline-flex items-center px-3 py-2 bg-zinc-700 border border-zinc-600 rounded-md font-medium text-xs text-gray-300 uppercase tracking-widest hover:bg-zinc-600 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                Filtros
                            </button>
                        </div>
                    </div>

                    <!-- Filtros Panel -->
                    <div id="filtros-panel" class="hidden mb-6 p-4 bg-zinc-800 rounded-lg border border-zinc-700">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Cliente</label>
                                <select class="w-full bg-zinc-700 border-zinc-600 rounded-md text-gray-200 text-sm" id="filter-cliente">
                                    <option value="">Todos los clientes</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Proveedor</label>
                                <select class="w-full bg-zinc-700 border-zinc-600 rounded-md text-gray-200 text-sm" id="filter-proveedor">
                                    <option value="">Todos los proveedores</option>
                                    @foreach($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Fecha Desde</label>
                                <input type="date" class="w-full bg-zinc-700 border-zinc-600 rounded-md text-gray-200 text-sm" id="filter-fecha-desde">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Fecha Hasta</label>
                                <input type="date" class="w-full bg-zinc-700 border-zinc-600 rounded-md text-gray-200 text-sm" id="filter-fecha-hasta">
                            </div>
                        </div>
                    </div>

                    <!-- KPI Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-8">
                        <div class="bg-gradient-to-br from-blue-900/50 to-blue-800/30 p-4 rounded-lg border border-blue-700/50">
                            <div class="text-3xl font-bold text-blue-400">{{ $totalVehiculos }}</div>
                            <div class="text-xs text-gray-400 mt-1">Vehículos</div>
                        </div>
                        <div class="bg-gradient-to-br from-yellow-900/50 to-yellow-800/30 p-4 rounded-lg border border-yellow-700/50">
                            <div class="text-3xl font-bold text-yellow-400">{{ $turnosPendientes }}</div>
                            <div class="text-xs text-gray-400 mt-1">Turnos Pendientes</div>
                        </div>
                        <div class="bg-gradient-to-br from-red-900/50 to-red-800/30 p-4 rounded-lg border border-red-700/50">
                            <div class="text-3xl font-bold text-red-400">{{ $pagosVencidos }}</div>
                            <div class="text-xs text-gray-400 mt-1">Pagos Vencidos</div>
                        </div>
                        <div class="bg-gradient-to-br from-orange-900/50 to-orange-800/30 p-4 rounded-lg border border-orange-700/50">
                            <div class="text-3xl font-bold text-orange-400">{{ $pagosPendientes }}</div>
                            <div class="text-xs text-gray-400 mt-1">Pagos Pendientes</div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-900/50 to-purple-800/30 p-4 rounded-lg border border-purple-700/50">
                            <div class="text-3xl font-bold text-purple-400">{{ $cobrosPendientes }}</div>
                            <div class="text-xs text-gray-400 mt-1">Cobros Pendientes</div>
                        </div>
                        <div class="bg-gradient-to-br from-green-900/50 to-green-800/30 p-4 rounded-lg border border-green-700/50">
                            <div class="text-3xl font-bold text-green-400">${{ number_format($totalPagadoMes, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-400 mt-1">Pagado este mes</div>
                        </div>
                        <div class="bg-gradient-to-br from-emerald-900/50 to-emerald-800/30 p-4 rounded-lg border border-emerald-700/50">
                            <div class="text-3xl font-bold text-emerald-400">${{ number_format($totalCobradoMes, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-400 mt-1">Cobrado este mes</div>
                        </div>
                    </div>

                    <!-- Alertas Activas -->
                    @if($alertas->count() > 0)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-200 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            Alertas Activas
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($alertas as $alerta)
                            <div class="bg-zinc-800 p-4 rounded-lg border border-zinc-700 hover:border-yellow-600/50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-200">{{ $alerta->titulo }}</h4>
                                        <p class="text-sm text-gray-400 mt-1">{{ $alerta->descripcion ?? 'Sin descripción' }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($alerta->tipo === 'vencimiento_pago') bg-red-900 text-red-300
                                        @elseif($alerta->tipo === 'recordatorio_turno') bg-blue-900 text-blue-300
                                        @elseif($alerta->tipo === 'agendar_turno_km') bg-orange-900 text-orange-300
                                        @else bg-purple-900 text-purple-300
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $alerta->tipo)) }}
                                    </span>
                                </div>
                                @if($alerta->rodado)
                                <div class="mt-2 text-xs text-gray-500">Vehículo: {{ $alerta->rodado->patente ?? 'N/A' }}</div>
                                @endif
                                @if($alerta->cliente)
                                <div class="mt-1 text-xs text-gray-500">Cliente: {{ $alerta->cliente->nombre }}</div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Charts Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Pagos Mensuales Chart -->
                        <div class="bg-zinc-800 p-6 rounded-lg border border-zinc-700">
                            <h3 class="text-lg font-semibold text-gray-200 mb-4">Pagos Mensuales</h3>
                            <div id="pagos-chart">
                                <canvas id="pagosChartCanvas" height="250"></canvas>
                            </div>
                        </div>

                        <!-- Cobros vs Pagos Chart -->
                        <div class="bg-zinc-800 p-6 rounded-lg border border-zinc-700">
                            <h3 class="text-lg font-semibold text-gray-200 mb-4">Cobros vs Pagos</h3>
                            <div id="cobros-chart">
                                <canvas id="cobrosChartCanvas" height="250"></canvas>
                            </div>
                        </div>

                        <!-- Turnos por Estado -->
                        <div class="bg-zinc-800 p-6 rounded-lg border border-zinc-700">
                            <h3 class="text-lg font-semibold text-gray-200 mb-4">Turnos por Estado</h3>
                            <div id="turnos-chart">
                                <canvas id="turnosChartCanvas" height="250"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Real-time Information -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Próximos Turnos -->
                        <div class="bg-zinc-800 p-6 rounded-lg border border-zinc-700">
                            <h3 class="text-lg font-semibold text-gray-200 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Próximos Turnos (7 días)
                            </h3>
                            @if($proximosTurnos->count() > 0)
                                <div class="space-y-3">
                                    @foreach($proximosTurnos as $turno)
                                    <div class="flex items-center justify-between p-3 bg-zinc-900 rounded-lg">
                                        <div>
                                            <div class="font-medium text-gray-200">{{ $turno->rodado->patente ?? 'Sin patente' }}</div>
                                            <div class="text-sm text-gray-400">{{ $turno->taller->nombre ?? 'N/A' }} - {{ ucfirst($turno->tipo) }}</div>
                                        </div>
                                        <div class="text-sm text-blue-400">{{ $turno->fecha_hora->format('d/m H:i') }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No hay turnos próximos.</p>
                            @endif
                        </div>

                        <!-- Pagos Próximos a Vencer -->
                        <div class="bg-zinc-800 p-6 rounded-lg border border-zinc-700">
                            <h3 class="text-lg font-semibold text-gray-200 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Pagos Próximos a Vencer (7 días)
                            </h3>
                            @if($pagosProximosVencer->count() > 0)
                                <div class="space-y-3">
                                    @foreach($pagosProximosVencer as $pago)
                                    <div class="flex items-center justify-between p-3 bg-zinc-900 rounded-lg">
                                        <div>
                                            <div class="font-medium text-gray-200">{{ $pago->rodado?->patente ?? 'Sin patente' }}</div>
                                            <div class="text-sm text-gray-400">${{ number_format($pago->monto, 2, ',', '.') }} {{ $pago->moneda ?? 'ARS' }}</div>
                                        </div>
                                        <div class="text-sm text-red-400">{{ $pago->fecha_vencimiento->format('d/m/Y') }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No hay pagos próximos a vencer.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pagos Mensuales Chart
            fetch('{{ route("rodados.admin-dashboard.pagos-mensuales") }}')
                .then(r => r.json())
                .then(data => {
                    new Chart(document.getElementById('pagosChartCanvas'), {
                        type: 'bar',
                        data: {
                            labels: data.map(d => d.mes),
                            datasets: [
                                { label: 'Pagados', data: data.map(d => d.pagados), backgroundColor: 'rgba(34, 197, 94, 0.7)', borderColor: 'rgb(34, 197, 94)', borderWidth: 1 },
                                { label: 'Pendientes', data: data.map(d => d.pendientes), backgroundColor: 'rgba(239, 68, 68, 0.7)', borderColor: 'rgb(239, 68, 68)', borderWidth: 1 }
                            ]
                        },
                        options: { responsive: true, plugins: { legend: { labels: { color: '#9ca3af' } } }, scales: { x: { ticks: { color: '#9ca3af' }, grid: { color: '#374151' } }, y: { ticks: { color: '#9ca3af' }, grid: { color: '#374151' } } } }
                    });
                });

            // Cobros vs Pagos Chart
            fetch('{{ route("rodados.admin-dashboard.cobros-vs-pagos") }}')
                .then(r => r.json())
                .then(data => {
                    new Chart(document.getElementById('cobrosChartCanvas'), {
                        type: 'line',
                        data: {
                            labels: data.map(d => d.mes),
                            datasets: [
                                { label: 'Cobrado', data: data.map(d => d.cobrado), borderColor: 'rgb(34, 197, 94)', backgroundColor: 'rgba(34, 197, 94, 0.1)', fill: true, tension: 0.3 },
                                { label: 'Pagado', data: data.map(d => d.pagado), borderColor: 'rgb(239, 68, 68)', backgroundColor: 'rgba(239, 68, 68, 0.1)', fill: true, tension: 0.3 }
                            ]
                        },
                        options: { responsive: true, plugins: { legend: { labels: { color: '#9ca3af' } } }, scales: { x: { ticks: { color: '#9ca3af' }, grid: { color: '#374151' } }, y: { ticks: { color: '#9ca3af' }, grid: { color: '#374151' } } } }
                    });
                });

            // Turnos por Estado Chart
            fetch('{{ route("rodados.admin-dashboard.turnos-por-estado") }}')
                .then(r => r.json())
                .then(data => {
                    new Chart(document.getElementById('turnosChartCanvas'), {
                        type: 'doughnut',
                        data: {
                            labels: data.map(d => d.estado),
                            datasets: [{
                                data: data.map(d => d.total),
                                backgroundColor: ['rgba(234, 179, 8, 0.7)', 'rgba(59, 130, 246, 0.7)', 'rgba(34, 197, 94, 0.7)', 'rgba(107, 114, 128, 0.7)'],
                                borderColor: ['rgb(234, 179, 8)', 'rgb(59, 130, 246)', 'rgb(34, 197, 94)', 'rgb(107, 114, 128)'],
                                borderWidth: 1
                            }]
                        },
                        options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { color: '#9ca3af' } } } }
                    });
                });
        });
    </script>
    @endpush
</x-app-layout>
