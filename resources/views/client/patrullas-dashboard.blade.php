@extends('layouts.cliente')

@section('title', 'Dashboard Patrullas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                <div class="p-2.5 bg-gradient-to-br from-cyan-600/20 to-cyan-400/10 rounded-xl border border-cyan-500/20">
                    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                Dashboard Patrullas
            </h1>
            <p class="text-gray-400 mt-1">Estadísticas de patrullas y documentación</p>
        </div>
    </div>

    <!-- ==================== SECCIÓN PATRULLAS ==================== -->
    <div class="border-b border-zinc-600 pb-2 mt-8">
        <h2 class="text-lg font-semibold text-cyan-400 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            Estadísticas de Patrullas
        </h2>
    </div>

    <!-- Tarjetas de resumen de patrullas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-zinc-700 shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wide">Total Patrullas</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $totalPatrullas ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-cyan-600/20 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-zinc-700 shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wide">Con GPS</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $patrullasConGPS ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-green-600/20 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-zinc-700 shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wide">Sin GPS</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $patrullasSinGPS ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-red-600/20 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de gráficos de patrullas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-zinc-700 shadow-xl relative">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-white">Patrullas por Estado</h2>
                    <p class="text-gray-400 text-sm mt-1">Distribución según estado operativo</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-cyan-600/20 text-cyan-400">
                    <span class="w-2 h-2 bg-cyan-500 rounded-full mr-2"></span>Por estado
                </span>
            </div>
            <div class="relative" style="height: 300px;"><canvas id="chartPatrullasEstado"></canvas></div>
        </div>
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-zinc-700 shadow-xl relative">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-white">Cobertura GPS</h2>
                    <p class="text-gray-400 text-sm mt-1">Patrullas con y sin seguimiento GPS</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-600/20 text-green-400">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>GPS
                </span>
            </div>
            <div class="relative" style="height: 300px;"><canvas id="chartPatrullasGPS"></canvas></div>
        </div>
    </div>

    <!-- ==================== SECCIÓN DOCUMENTOS ==================== -->
    <div class="border-b border-zinc-600 pb-2 mt-8">
        <h2 class="text-lg font-semibold text-orange-400 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Documentación de Patrullas
        </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-zinc-700 shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wide">Total Documentos</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $totalDocumentos ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-orange-600/20 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-red-900/50 shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wide">Vencidos</p>
                    <p class="text-3xl font-bold text-red-500 mt-2">{{ $documentosVencidos ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-red-600/20 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-amber-900/50 shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wide">Vence en 7 días</p>
                    <p class="text-3xl font-bold text-amber-500 mt-2">{{ $documentosPorVencer7Dias ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-amber-600/20 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-green-900/50 shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wide">Vigentes</p>
                    <p class="text-3xl font-bold text-green-500 mt-2">{{ $documentosVigentes ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-green-600/20 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-zinc-700 shadow-xl relative">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-white">Estado de Documentos</h2>
                    <p class="text-gray-400 text-sm mt-1">Distribución por estado de vencimiento</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-600/20 text-orange-400">
                    <span class="w-2 h-2 bg-orange-500 rounded-full mr-2"></span>Vencimientos
                </span>
            </div>
            <div class="relative" style="height: 300px;"><canvas id="chartDocumentos"></canvas></div>
        </div>
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-zinc-700 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-bold text-white">Alertas de Vencimiento</h2>
                    <p class="text-gray-400 text-sm mt-1">Documentos próximos a vencer o vencidos</p>
                </div>
            </div>
            <div class="overflow-x-auto max-h-72 overflow-y-auto">
                @if(isset($documentosAlerta) && count($documentosAlerta) > 0)
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-400 uppercase bg-zinc-800/50 sticky top-0">
                        <tr>
                            <th class="px-4 py-3">Documento</th>
                            <th class="px-4 py-3">Patrulla</th>
                            <th class="px-4 py-3 text-center">Vencimiento</th>
                            <th class="px-4 py-3 text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($documentosAlerta as $doc)
                        <tr class="hover:bg-zinc-700/50 transition-colors">
                            <td class="px-4 py-3 font-medium text-white">{{ $doc['nombre'] }}</td>
                            <td class="px-4 py-3 text-gray-300">{{ $doc['patrulla'] }}</td>
                            <td class="px-4 py-3 text-center text-gray-300">{{ $doc['fecha_vto'] }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($doc['estado'] === 'vencido')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-600/20 text-red-400">Vencido ({{ abs($doc['dias_restantes']) }} días)</span>
                                @elseif($doc['estado'] === 'critico')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-600/20 text-amber-400">{{ $doc['dias_restantes'] }} días</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-600/20 text-yellow-400">{{ $doc['dias_restantes'] }} días</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-8 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p>No hay documentos próximos a vencer</p>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    // ========== GRÁFICOS DE PATRULLAS (Chart.js) ==========
    const initialDataPatrullasEstado = @json($chartDataPatrullasEstado ?? []);
    const initialDataPatrullasGPS = @json($chartDataPatrullasGPS ?? []);
    const initialDataDocumentos = @json($chartDataDocumentos ?? []);

    const colorsPatrullas = {
        bg: ['rgba(30,58,138,0.85)','rgba(20,83,45,0.85)','rgba(55,65,81,0.85)','rgba(22,78,99,0.85)','rgba(71,85,105,0.85)'],
        border: ['rgba(30,58,138,1)','rgba(20,83,45,1)','rgba(55,65,81,1)','rgba(22,78,99,1)','rgba(71,85,105,1)']
    };
    const colorsGPS = {
        bg: ['rgba(20,83,45,0.85)','rgba(55,65,81,0.85)'],
        border: ['rgba(20,83,45,1)','rgba(55,65,81,1)']
    };
    const colorsDocumentos = {
        bg: ['rgba(127,29,29,0.85)','rgba(146,64,14,0.85)','rgba(113,63,18,0.85)','rgba(20,83,45,0.85)'],
        border: ['rgba(127,29,29,1)','rgba(146,64,14,1)','rgba(113,63,18,1)','rgba(20,83,45,1)']
    };

    const getDoughnutOptions = () => ({
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { display: true, position: 'bottom', labels: { color: '#9ca3af', padding: 20, font: { size: 12 } } },
            tooltip: {
                backgroundColor: 'rgba(17,24,39,0.95)', titleColor: '#fff', bodyColor: '#9ca3af',
                padding: 12, cornerRadius: 8,
                callbacks: {
                    label: ctx => {
                        const total = ctx.dataset.data.reduce((a,b) => a+b, 0);
                        const pct = total > 0 ? ((ctx.raw / total) * 100).toFixed(1) : 0;
                        return `${ctx.label}: ${ctx.raw} (${pct}%)`;
                    }
                }
            }
        },
        animation: { duration: 1000, easing: 'easeOutQuart' }
    });

    if (initialDataPatrullasEstado.length > 0) {
        new Chart(document.getElementById('chartPatrullasEstado').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: initialDataPatrullasEstado.map(i => i.nombre),
                datasets: [{ data: initialDataPatrullasEstado.map(i => i.total), backgroundColor: initialDataPatrullasEstado.map((_,i) => colorsPatrullas.bg[i % colorsPatrullas.bg.length]), borderColor: initialDataPatrullasEstado.map((_,i) => colorsPatrullas.border[i % colorsPatrullas.border.length]), borderWidth: 2 }]
            },
            options: getDoughnutOptions()
        });
    }

    if (initialDataPatrullasGPS.length > 0) {
        new Chart(document.getElementById('chartPatrullasGPS').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: initialDataPatrullasGPS.map(i => i.nombre),
                datasets: [{ data: initialDataPatrullasGPS.map(i => i.total), backgroundColor: colorsGPS.bg, borderColor: colorsGPS.border, borderWidth: 2 }]
            },
            options: getDoughnutOptions()
        });
    }

    if (initialDataDocumentos.length > 0) {
        new Chart(document.getElementById('chartDocumentos').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: initialDataDocumentos.map(i => i.nombre),
                datasets: [{ data: initialDataDocumentos.map(i => i.total), backgroundColor: initialDataDocumentos.map((_,i) => colorsDocumentos.bg[i % colorsDocumentos.bg.length]), borderColor: initialDataDocumentos.map((_,i) => colorsDocumentos.border[i % colorsDocumentos.border.length]), borderWidth: 2 }]
            },
            options: getDoughnutOptions()
        });
    }

});
</script>
@endpush
@endsection
