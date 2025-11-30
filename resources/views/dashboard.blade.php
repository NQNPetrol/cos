<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-50 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Header con filtros -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-white">Panel de Control</h1>
                    <p class="text-gray-400 mt-1">Resumen general de eventos por cliente</p>
                </div>
                
                <!-- Filtros de fecha -->
                <div class="flex flex-wrap gap-3 items-center">
                    <div class="flex items-center gap-2">
                        <label for="fecha_desde" class="text-sm text-gray-300">Desde:</label>
                        <input type="date" id="fecha_desde" 
                               class="bg-gray-800 border border-gray-600 text-white text-sm rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-center gap-2">
                        <label for="fecha_hasta" class="text-sm text-gray-300">Hasta:</label>
                        <input type="date" id="fecha_hasta" 
                               class="bg-gray-800 border border-gray-600 text-white text-sm rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <button id="btn_filtrar" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filtrar
                    </button>
                    <button id="btn_limpiar" 
                            class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        Limpiar
                    </button>
                </div>
            </div>

            <!-- Tarjetas de resumen -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total de Eventos -->
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm font-medium uppercase tracking-wide">Total Eventos</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $totalEventos ?? 0 }}</p>
                        </div>
                        <div class="w-14 h-14 bg-blue-600/20 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Clientes -->
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm font-medium uppercase tracking-wide">Clientes</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $totalClientes ?? 0 }}</p>
                        </div>
                        <div class="w-14 h-14 bg-emerald-600/20 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Eventos sin cliente -->
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm font-medium uppercase tracking-wide">Sin Cliente Asignado</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $eventosSinCliente ?? 0 }}</p>
                        </div>
                        <div class="w-14 h-14 bg-amber-600/20 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid de gráficos - 2 columnas en pantallas lg -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Gráfico de eventos por cliente -->
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 shadow-xl relative">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-white">Eventos por Cliente</h2>
                            <p class="text-gray-400 text-sm mt-1">Distribución de eventos por cada cliente</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-600/20 text-blue-400">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                            Por cliente
                        </span>
                    </div>
                    
                    <div class="relative" style="height: 350px;">
                        <canvas id="chartEventosPorCliente"></canvas>
                    </div>
                    
                    <div id="loading-clientes" class="hidden absolute inset-0 bg-gray-800/80 rounded-2xl flex items-center justify-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-500 border-t-transparent"></div>
                            <span class="text-gray-300 text-sm">Cargando datos...</span>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de eventos por categoría -->
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 shadow-xl relative">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-white">Eventos por Categoría</h2>
                            <p class="text-gray-400 text-sm mt-1">Distribución de eventos según su tipo</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-violet-600/20 text-violet-400">
                            <span class="w-2 h-2 bg-violet-500 rounded-full mr-2"></span>
                            Por categoría
                        </span>
                    </div>
                    
                    <div class="relative" style="height: 350px;">
                        <canvas id="chartEventosPorCategoria"></canvas>
                    </div>
                    
                    <div id="loading-categorias" class="hidden absolute inset-0 bg-gray-800/80 rounded-2xl flex items-center justify-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="animate-spin rounded-full h-10 w-10 border-4 border-violet-500 border-t-transparent"></div>
                            <span class="text-gray-300 text-sm">Cargando datos...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Datos iniciales
        const initialDataClientes = @json($chartDataClientes ?? []);
        const initialDataCategorias = @json($chartDataCategorias ?? []);
        
        // URLs de API
        const urlClientes = "{{ route('admin.dashboard.eventos-por-cliente') }}";
        const urlCategorias = "{{ route('admin.dashboard.eventos-por-categoria') }}";
        
        // Paleta de colores para clientes
        const colorsClientes = {
            bg: [
                'rgba(59, 130, 246, 0.8)', 'rgba(16, 185, 129, 0.8)', 'rgba(245, 158, 11, 0.8)',
                'rgba(239, 68, 68, 0.8)', 'rgba(139, 92, 246, 0.8)', 'rgba(236, 72, 153, 0.8)',
                'rgba(6, 182, 212, 0.8)', 'rgba(249, 115, 22, 0.8)', 'rgba(34, 197, 94, 0.8)',
                'rgba(168, 85, 247, 0.8)', 'rgba(14, 165, 233, 0.8)', 'rgba(234, 179, 8, 0.8)'
            ],
            border: [
                'rgba(59, 130, 246, 1)', 'rgba(16, 185, 129, 1)', 'rgba(245, 158, 11, 1)',
                'rgba(239, 68, 68, 1)', 'rgba(139, 92, 246, 1)', 'rgba(236, 72, 153, 1)',
                'rgba(6, 182, 212, 1)', 'rgba(249, 115, 22, 1)', 'rgba(34, 197, 94, 1)',
                'rgba(168, 85, 247, 1)', 'rgba(14, 165, 233, 1)', 'rgba(234, 179, 8, 1)'
            ]
        };
        
        // Paleta de colores para categorías
        const colorsCategorias = {
            bg: [
                'rgba(139, 92, 246, 0.8)', 'rgba(236, 72, 153, 0.8)', 'rgba(168, 85, 247, 0.8)',
                'rgba(244, 114, 182, 0.8)', 'rgba(192, 132, 252, 0.8)', 'rgba(232, 121, 249, 0.8)',
                'rgba(167, 139, 250, 0.8)', 'rgba(251, 146, 60, 0.8)', 'rgba(74, 222, 128, 0.8)',
                'rgba(56, 189, 248, 0.8)', 'rgba(248, 113, 113, 0.8)', 'rgba(52, 211, 153, 0.8)'
            ],
            border: [
                'rgba(139, 92, 246, 1)', 'rgba(236, 72, 153, 1)', 'rgba(168, 85, 247, 1)',
                'rgba(244, 114, 182, 1)', 'rgba(192, 132, 252, 1)', 'rgba(232, 121, 249, 1)',
                'rgba(167, 139, 250, 1)', 'rgba(251, 146, 60, 1)', 'rgba(74, 222, 128, 1)',
                'rgba(56, 189, 248, 1)', 'rgba(248, 113, 113, 1)', 'rgba(52, 211, 153, 1)'
            ]
        };

        // Opciones comunes del gráfico
        const getChartOptions = () => ({
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    titleColor: '#fff',
                    bodyColor: '#9ca3af',
                    borderColor: 'rgba(75, 85, 99, 0.3)',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = total > 0 ? ((context.raw / total) * 100).toFixed(1) : 0;
                            return `${context.raw} eventos (${pct}%)`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(75, 85, 99, 0.2)', drawBorder: false },
                    ticks: { color: '#9ca3af', font: { size: 11 }, maxRotation: 45, minRotation: 45 }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(75, 85, 99, 0.2)', drawBorder: false },
                    ticks: { color: '#9ca3af', font: { size: 11 }, stepSize: 1 }
                }
            },
            animation: { duration: 1000, easing: 'easeOutQuart' }
        });

        // Crear gráfico de clientes
        const ctxClientes = document.getElementById('chartEventosPorCliente').getContext('2d');
        const chartClientes = new Chart(ctxClientes, {
            type: 'bar',
            data: {
                labels: initialDataClientes.map(i => i.nombre),
                datasets: [{
                    label: 'Eventos',
                    data: initialDataClientes.map(i => i.total),
                    backgroundColor: initialDataClientes.map((_, i) => colorsClientes.bg[i % colorsClientes.bg.length]),
                    borderColor: initialDataClientes.map((_, i) => colorsClientes.border[i % colorsClientes.border.length]),
                    borderWidth: 2, borderRadius: 8, borderSkipped: false
                }]
            },
            options: getChartOptions()
        });

        // Crear gráfico de categorías
        const ctxCategorias = document.getElementById('chartEventosPorCategoria').getContext('2d');
        const chartCategorias = new Chart(ctxCategorias, {
            type: 'bar',
            data: {
                labels: initialDataCategorias.map(i => i.nombre),
                datasets: [{
                    label: 'Eventos',
                    data: initialDataCategorias.map(i => i.total),
                    backgroundColor: initialDataCategorias.map((_, i) => colorsCategorias.bg[i % colorsCategorias.bg.length]),
                    borderColor: initialDataCategorias.map((_, i) => colorsCategorias.border[i % colorsCategorias.border.length]),
                    borderWidth: 2, borderRadius: 8, borderSkipped: false
                }]
            },
            options: getChartOptions()
        });

        // Función para actualizar gráficos
        async function updateCharts() {
            const fechaDesde = document.getElementById('fecha_desde').value;
            const fechaHasta = document.getElementById('fecha_hasta').value;
            
            document.getElementById('loading-clientes').classList.remove('hidden');
            document.getElementById('loading-categorias').classList.remove('hidden');

            try {
                const params = new URLSearchParams();
                if (fechaDesde) params.append('fecha_desde', fechaDesde);
                if (fechaHasta) params.append('fecha_hasta', fechaHasta);
                const queryString = params.toString() ? `?${params.toString()}` : '';

                const [resClientes, resCategorias] = await Promise.all([
                    fetch(urlClientes + queryString),
                    fetch(urlCategorias + queryString)
                ]);

                const dataClientes = await resClientes.json();
                const dataCategorias = await resCategorias.json();

                // Actualizar gráfico de clientes
                chartClientes.data.labels = dataClientes.map(i => i.nombre);
                chartClientes.data.datasets[0].data = dataClientes.map(i => i.total);
                chartClientes.data.datasets[0].backgroundColor = dataClientes.map((_, i) => colorsClientes.bg[i % colorsClientes.bg.length]);
                chartClientes.data.datasets[0].borderColor = dataClientes.map((_, i) => colorsClientes.border[i % colorsClientes.border.length]);
                chartClientes.update('active');

                // Actualizar gráfico de categorías
                chartCategorias.data.labels = dataCategorias.map(i => i.nombre);
                chartCategorias.data.datasets[0].data = dataCategorias.map(i => i.total);
                chartCategorias.data.datasets[0].backgroundColor = dataCategorias.map((_, i) => colorsCategorias.bg[i % colorsCategorias.bg.length]);
                chartCategorias.data.datasets[0].borderColor = dataCategorias.map((_, i) => colorsCategorias.border[i % colorsCategorias.border.length]);
                chartCategorias.update('active');

            } catch (error) {
                console.error('Error al cargar datos:', error);
            } finally {
                document.getElementById('loading-clientes').classList.add('hidden');
                document.getElementById('loading-categorias').classList.add('hidden');
            }
        }

        // Event listeners
        document.getElementById('btn_filtrar').addEventListener('click', updateCharts);
        document.getElementById('btn_limpiar').addEventListener('click', function() {
            document.getElementById('fecha_desde').value = '';
            document.getElementById('fecha_hasta').value = '';
            updateCharts();
        });
    });
    </script>
    @endpush
</x-app-layout>
