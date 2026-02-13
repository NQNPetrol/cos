@extends('layouts.cliente')

@section('title', 'Dashboard Principal')

@section('content')
<div class="space-y-6">
    <!-- Header del Dashboard -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Dashboard</h1>
            <p class="text-gray-400 mt-1">Resumen de actividad y estadísticas</p>
        </div>
        
        <!-- Filtros de fecha -->
        <div class="flex flex-wrap gap-3 items-center">
            <!-- Filtro por Cliente (Empresa Asociada) -->
            <div class="flex items-center gap-2">
                <label for="empresa_asociada_filter" class="text-sm text-gray-300">Cliente:</label>
                <select id="empresa_asociada_filter" 
                        class="bg-zinc-800 border border-zinc-600 text-white text-sm rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 min-w-[150px]">
                    <option value="">Todos</option>
                    @foreach($empresasAsociadas as $empresa)
                        <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <label for="fecha_desde" class="text-sm text-gray-300">Desde:</label>
                <input type="date" id="fecha_desde" 
                       class="bg-zinc-800 border border-zinc-600 text-white text-sm rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex items-center gap-2">
                <label for="fecha_hasta" class="text-sm text-gray-300">Hasta:</label>
                <input type="date" id="fecha_hasta" 
                       class="bg-zinc-800 border border-zinc-600 text-white text-sm rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button id="btn_filtrar" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filtrar
            </button>
            <button id="btn_limpiar" 
                    class="bg-zinc-600 hover:bg-zinc-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                Limpiar
            </button>
            <button id="btn_generar_pdf" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Generar PDF
            </button>
        </div>
    </div>

    <!-- ==================== SECCIÓN EVENTOS ==================== -->
    <div class="border-b border-zinc-600 pb-2">
        <h2 class="text-lg font-semibold text-blue-400 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Estadísticas de Eventos
        </h2>
    </div>

    <!-- Tarjetas de resumen de eventos -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total de Eventos -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-zinc-700 shadow-xl">
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

        <!-- Clientes -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-zinc-700 shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wide">Clientes</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $empresasAsociadas->count() ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-emerald-600/20 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Eventos últimos 7 días -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-zinc-700 shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wide">Últimos 7 días</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $eventosUltimos7Dias ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-amber-600/20 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de gráficos de eventos - 2 columnas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gráfico stacked de eventos por categoría y cliente -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-zinc-700 shadow-xl relative">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-white">Eventos por Categoría y Cliente</h2>
                    <p class="text-gray-400 text-sm mt-1">Distribución de eventos por categoría segmentada por cliente</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-600/20 text-blue-400">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                    Eventos registrados
                </span>
            </div>
            
            <div class="relative" style="height: 400px;">
                <canvas id="chartEventosStacked"></canvas>
            </div>
            
            <div id="loading-stacked" class="hidden absolute inset-0 bg-zinc-800/80 rounded-2xl flex items-center justify-center">
                <div class="flex flex-col items-center gap-3">
                    <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-500 border-t-transparent"></div>
                    <span class="text-gray-300 text-sm">Cargando datos...</span>
                </div>
            </div>
        </div>

        <!-- Gráfico combinado de eventos por mes -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-zinc-700 shadow-xl relative">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-white">Tendencia Mensual de Eventos</h2>
                    <p class="text-gray-400 text-sm mt-1">Evolución de eventos por mes con promedio</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-violet-600/20 text-violet-400">
                    <span class="w-2 h-2 bg-violet-500 rounded-full mr-2"></span>
                    Tendencia
                </span>
            </div>
            
            <div class="relative" style="height: 400px;">
                <canvas id="chartEventosMensual"></canvas>
            </div>
            
            <div id="loading-mensual" class="hidden absolute inset-0 bg-zinc-800/80 rounded-2xl flex items-center justify-center">
                <div class="flex flex-col items-center gap-3">
                    <div class="animate-spin rounded-full h-10 w-10 border-4 border-violet-500 border-t-transparent"></div>
                    <span class="text-gray-300 text-sm">Cargando datos...</span>
                </div>
            </div>
        </div>
    </div>

    <!--MAPA DE CALOR -->

    <!-- Contenedor del mapa de calor y panel lateral -->
    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-zinc-700 shadow-xl">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Columna principal: mapa (ancho reducido) -->
            <div class="flex-1">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-white">Concentración Geográfica de Eventos</h2>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        
                        <!-- Leyenda de colores -->
                        <div class="flex flex-col items-end gap-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-400 font-medium">Baja</span>
                                <div class="w-40 h-4 rounded-md overflow-hidden border border-zinc-600 shadow-inner" 
                                     style="background: linear-gradient(to right, #2563eb 0%, #06b6d4 30%, #84cc16 60%, #eab308 80%, #ef4444 100%);">
                                </div>
                                <span class="text-xs text-gray-400 font-medium">Alta</span>
                            </div>
                            <p class="text-xs text-gray-500 italic">Escala de intensidad de eventos</p>
                        </div>
                    </div>
                </div>

                <!-- Mapa de calor con Leaflet (altura fija) -->
                <div
                    id="heatmap-container"
                    class="w-full"
                    style="height: 600px; border-radius: 12px; overflow: hidden; background: #1f2937;"
                    data-api-url="{{ route('client.dashboard.eventos-mapa-calor') }}"
                >
                    <div id="heatmap-loading" class="flex items-center justify-center h-full">
                        <div class="flex flex-col items-center gap-3">
                            <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-500 border-t-transparent"></div>
                            <span class="text-gray-300 text-sm">Cargando mapa de calor...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel lateral: filtros y menú desplegable -->
            <div id="heatmap-filters-panel" class="w-full lg:w-80 space-y-4">
                <!-- Filtro por cliente -->
                <div class="bg-zinc-900/60 border border-zinc-700 rounded-xl p-4">
                    <h3 class="text-sm font-semibold text-gray-200 mb-2">Filtrar por cliente</h3>

                    <div class="space-y-2">
                        <!-- Checkbox: seleccionar todos -->
                        <label class="flex items-center gap-2 text-xs text-gray-200 cursor-pointer">
                            <input
                                type="checkbox"
                                id="heatmap_cliente_todos"
                                class="h-4 w-4 rounded border-zinc-500 bg-zinc-800 text-blue-500 focus:ring-blue-500"
                            >
                            <span>Seleccionar todos los clientes</span>
                        </label>

                        <!-- Lista de clientes -->
                        <div class="max-h-40 overflow-y-auto space-y-1 mt-1 custom-scrollbar">
                            @foreach($empresasAsociadas as $empresa)
                                <label class="flex items-center gap-2 text-xs text-gray-200 cursor-pointer hover:bg-zinc-800/60 px-2 py-1 rounded">
                                    <input
                                        type="checkbox"
                                        class="heatmap-cliente-item h-4 w-4 rounded border-zinc-500 bg-zinc-800 text-blue-500 focus:ring-blue-500"
                                        value="{{ $empresa->id }}"
                                    >
                                    <span>{{ $empresa->nombre }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Filtros por categoría y tipo de evento -->
                <div class="bg-zinc-900/60 border border-zinc-700 rounded-xl p-4 space-y-3">
                    <!-- Categorías como checkboxes -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-200 mb-2">Filtrar por Categoria</h3>
                        
                        <div class="space-y-2">
                            <!-- Checkbox: seleccionar todas las categorías -->
                            <label class="flex items-center gap-2 text-xs text-gray-200 cursor-pointer">
                                <input
                                    type="checkbox"
                                    id="heatmap_categoria_todos"
                                    class="h-4 w-4 rounded border-zinc-500 bg-zinc-800 text-blue-500 focus:ring-blue-500"
                                >
                                <span>Seleccionar todas las categorías</span>
                            </label>

                            <!-- Lista de categorías -->
                            <div class="max-h-40 overflow-y-auto space-y-1 mt-1 custom-scrollbar" id="heatmap_categorias_lista">
                                @if(isset($categoriasTiposMapa))
                                    @foreach($categoriasTiposMapa as $cat)
                                        <label class="flex items-center gap-2 text-xs text-gray-200 cursor-pointer hover:bg-zinc-800/60 px-2 py-1 rounded">
                                            <input
                                                type="checkbox"
                                                class="heatmap-categoria-item h-4 w-4 rounded border-zinc-500 bg-zinc-800 text-blue-500 focus:ring-blue-500"
                                                value="{{ $cat['id'] }}"
                                                data-tipos="{{ json_encode($cat['tipos'] ?? []) }}"
                                            >
                                            <span>{{ $cat['nombre'] }}</span>
                                        </label>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tipos de evento (select multiple, se actualiza dinámicamente) -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-200 mb-2">Tipos de evento</h3>
                        
                        <div class="space-y-2">
                            <!-- Checkbox: seleccionar todos los tipos -->
                            <label class="flex items-center gap-2 text-xs text-gray-200 cursor-pointer">
                                <input
                                    type="checkbox"
                                    id="heatmap_tipo_todos"
                                    class="h-4 w-4 rounded border-zinc-500 bg-zinc-800 text-blue-500 focus:ring-blue-500"
                                    disabled
                                >
                                <span>Seleccionar todos los tipos</span>
                            </label>

                            <!-- Lista de tipos (se llena dinámicamente) -->
                            <div id="heatmap_tipos_lista" class="max-h-40 overflow-y-auto space-y-1 mt-1 custom-scrollbar">
                                <!-- Se llena dinámicamente con JavaScript -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 pt-1">
                        <button id="heatmap_aplicar_filtros"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg px-3 py-2 transition-colors">
                            Aplicar filtros del mapa
                        </button>
                        <button id="heatmap_limpiar_filtros"
                                class="bg-zinc-700 hover:bg-zinc-600 text-gray-100 text-xs font-medium rounded-lg px-3 py-2 transition-colors">
                            Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== MAPA DE INTENSIDAD DE RECORRIDOS ==================== -->
    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-zinc-700 shadow-xl mt-6">
        <!-- Header con título y filtros -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <div>
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    Mapa de calor de frecuencia de recorridos
                </h2>
                <p class="text-gray-400 text-sm mt-1">Intensidad basada en la frecuencia de realización mensual</p>
            </div>
            <div class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs text-gray-400 mb-1 uppercase tracking-wider">Cliente</label>
                    <select id="recmap_empresa" class="bg-zinc-800 border border-zinc-600 text-white text-sm rounded-lg px-3 py-2 min-w-[180px] focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccionar cliente...</option>
                        @foreach($empresasConRecorridos as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1 uppercase tracking-wider">Patrulla</label>
                    <select id="recmap_patrulla" class="bg-zinc-800 border border-zinc-600 text-white text-sm rounded-lg px-3 py-2 min-w-[150px] focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todas</option>
                        @foreach($patrullas as $pat)
                            <option value="{{ $pat->id }}">{{ $pat->patente }}</option>
                        @endforeach
                    </select>
                </div>
                <button id="btn_cargar_recmap" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all">
                    <i class="bi bi-arrow-repeat mr-1"></i> Cargar Mapa
                </button>
            </div>
        </div>

        <!-- Mapa -->
        <div class="relative rounded-xl overflow-hidden border border-zinc-600" style="height: 520px;">
            <div id="recorridos-map" style="height: 100%; width: 100%; background: #1a1a2e;"></div>
            <div id="recmap-loading" class="absolute inset-0 bg-gray-900/80 flex items-center justify-center hidden z-[1000]">
                <div class="flex items-center gap-3 text-gray-300">
                    <svg class="animate-spin h-6 w-6" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    Cargando recorridos...
                </div>
            </div>
            <div id="recmap-empty" class="absolute inset-0 flex items-center justify-center z-[999] pointer-events-none">
                <div class="text-center text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    <p class="text-sm">Seleccione un cliente para ver los recorridos en el mapa</p>
                </div>
            </div>
        </div>

        <!-- Leyenda -->
        <div id="recmap-legend" class="mt-4 hidden">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-xs text-gray-400 font-medium">Baja</span>
                <div class="w-40 h-4 rounded-md overflow-hidden border border-zinc-600 shadow-inner"
                     style="background: linear-gradient(to right, #2563eb 0%, #06b6d4 30%, #84cc16 60%, #eab308 80%, #ef4444 100%);">
                </div>
                <span class="text-xs text-gray-400 font-medium">Alta</span>
                <span class="text-xs text-gray-500 italic ml-2">Escala de intensidad de recorridos</span>
            </div>
        </div>

        <!-- Indicadores colapsables -->
        <div class="mt-4 border-t border-zinc-700 pt-3">
            <button id="toggle-indicadores-rec" type="button" class="flex items-center gap-2 text-sm text-gray-400 hover:text-white transition-colors w-full">
                <svg id="indicadores-chevron" class="w-4 h-4 transform -rotate-90 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
                <span class="font-medium">Indicadores del Mes</span>
                <span id="indicadores-count" class="text-xs bg-zinc-700 text-gray-400 px-2 py-0.5 rounded-full hidden">0</span>
            </button>
            <div id="indicadores-recorridos-panel" class="hidden mt-3">
                <div id="indicadores-recorridos-container" class="space-y-2">
                    <div class="text-center py-4 text-gray-500 text-sm">
                        <svg class="animate-spin h-5 w-5 mx-auto mb-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Cargando indicadores...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== GRÁFICOS DE ANÁLISIS DE RECORRIDOS ==================== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Tendencia día/hora -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-zinc-700 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-white">Tendencia de Recorridos</h2>
                    <p class="text-gray-400 text-sm mt-1">Distribución por día de semana y hora</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-600/20 text-purple-400">
                    <span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span>Heatmap
                </span>
            </div>
            <div class="relative" style="height: 320px;"><canvas id="chartTendenciaRecorridos"></canvas></div>
        </div>

        <!-- Top 5 más vs menos realizados -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-zinc-700 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-white">Top Recorridos del Mes</h2>
                    <p class="text-gray-400 text-sm mt-1">5 más realizados vs 5 menos realizados</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-600/20 text-blue-400">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>Ranking
                </span>
            </div>
            <div class="relative" style="height: 320px;"><canvas id="chartTopRecorridos"></canvas></div>
        </div>
    </div>

    <!-- Link al Dashboard de Patrullas -->
    <div class="mt-6">
        <a href="{{ route('client.dashboard-patrullas') }}" class="inline-flex items-center gap-3 px-5 py-3 bg-gradient-to-r from-cyan-600/20 to-cyan-400/10 border border-cyan-500/30 rounded-xl text-cyan-400 hover:from-cyan-600/30 hover:to-cyan-400/20 hover:border-cyan-500/50 transition-all group">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            <span class="font-medium">Dashboard Patrullas</span>
            <span class="text-xs text-gray-500">Estadísticas y documentación de patrullas</span>
            <svg class="w-4 h-4 ml-auto opacity-50 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</div>
@push('scripts')
<!-- Leaflet CSS y JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
 src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

<style>
    /* Scrollbar personalizada para los contenedores de filtros */
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #4b5563 #1f2937;
    }
    
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #1f2937;
        border-radius: 3px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #4b5563;
        border-radius: 3px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: #6b7280;
    }
    
    /* Estilos para cuando el contenedor de tipos está vacío/deshabilitado */
    #heatmap_tipos_lista:empty {
        min-height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        font-style: italic;
        font-size: 11px;
    }
    
    /* Estilos para el popup personalizado del mapa */
    .custom-popup .leaflet-popup-content-wrapper {
        background: transparent;
        padding: 0;
        border-radius: 8px;
    }
    
    .custom-popup .leaflet-popup-content {
        margin: 0;
        padding: 0;
    }
    
    .custom-popup .leaflet-popup-tip {
        background: #1f2937;
    }
    
    /* Scrollbar personalizado para el listado de eventos en el popup */
    .popup-eventos-scroll {
        scrollbar-width: thin;
        scrollbar-color: #1f2937 #1f2937;
    }
    
    .popup-eventos-scroll::-webkit-scrollbar {
        width: 6px;
    }
    
    .popup-eventos-scroll::-webkit-scrollbar-track {
        background: #1f2937;
        border-radius: 3px;
    }
    
    .popup-eventos-scroll::-webkit-scrollbar-thumb {
        background-color: #374151;
        border-radius: 3px;
    }
    
    .popup-eventos-scroll::-webkit-scrollbar-thumb:hover {
        background-color: #4b5563;
    }
    
    /* Estilos para el botón de restablecer vista del mapa - mismo estilo que controles de zoom */
    .leaflet-control-reset-view {
        background-color: #fff;
        border-bottom: 1px solid #ccc;
        width: 30px;
        height: 30px;
        line-height: 30px;
        display: block;
        text-align: center;
        text-decoration: none;
        color: black;
        box-sizing: border-box;
    }
    
    .leaflet-control-reset-view:hover {
        background-color: #f4f4f4;
    }
    
    .leaflet-control-reset-view svg {
        width: 18px;
        height: 18px;
        display: inline-block;
        vertical-align: middle;
        stroke: currentColor;
    }
    
    /* Estilos para el botón de cambio de estilo del mapa - mismo estilo que controles de zoom */
    .leaflet-control-map-style {
        background-color: #fff;
        border-bottom: 1px solid #ccc;
        width: 30px;
        height: 30px;
        line-height: 30px;
        display: block;
        text-align: center;
        text-decoration: none;
        color: black;
        box-sizing: border-box;
    }
    
    .leaflet-control-map-style:hover {
        background-color: #f4f4f4;
    }
    
    .leaflet-control-map-style svg {
        width: 18px;
        height: 18px;
        display: inline-block;
        vertical-align: middle;
        stroke: currentColor;
    }
</style>

<script>
// Funciones globales para el mapa de calor
function copyCoords(coords, buttonElement) {
    navigator.clipboard.writeText(coords).then(function() {
        // Mostrar feedback visual
        if (buttonElement) {
            const originalText = buttonElement.textContent;
            buttonElement.textContent = 'Copiado!';
            buttonElement.style.background = '#10b981';
            setTimeout(() => {
                buttonElement.textContent = originalText;
                buttonElement.style.background = '#3b82f6';
            }, 2000);
        }
    }).catch(function(err) {
        console.error('Error al copiar:', err);
        // Fallback para navegadores que no soportan clipboard API
        const textarea = document.createElement('textarea');
        textarea.value = coords;
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            if (buttonElement) {
                const originalText = buttonElement.textContent;
                buttonElement.textContent = 'Copiado!';
                buttonElement.style.background = '#10b981';
                setTimeout(() => {
                    buttonElement.textContent = originalText;
                    buttonElement.style.background = '#3b82f6';
                }, 2000);
            } else {
                alert('Coordenadas copiadas: ' + coords);
            }
        } catch (e) {
            alert('Error al copiar coordenadas');
        }
        document.body.removeChild(textarea);
    });
}

function verEvento(eventoId) {
    // Redirigir a la página de reporte del evento
    window.location.href = `/client/eventos/${eventoId}/reporte`;
}
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos iniciales - Eventos
    const initialDataStacked = @json($chartDataStacked ?? ['clientes' => [], 'datasets' => []]);
    const initialDataMensual = @json($chartDataMensual ?? ['labels' => [], 'data' => [], 'promedio' => 0]);
    
    // (Datos de patrullas y documentos movidos a Dashboard Patrullas)
    
    // URLs de API
    const urlStacked = "{{ route('client.dashboard.eventos-stacked') }}";
    const urlMensual = "{{ route('client.dashboard.eventos-mensual') }}";
    
    // Paleta de colores profesional para empresa de seguridad
    // Tonos: Azules oscuros, grises, verdes, cyans - sin fucsia ni rosa
    const colorsClientes = {
        bg: ['rgba(30, 58, 138, 0.85)',   // Azul navy oscuro
             'rgba(20, 83, 45, 0.85)',     // Verde oscuro
             'rgba(22, 78, 99, 0.85)',     // Cyan oscuro
             'rgba(55, 65, 81, 0.85)',     // Gris oscuro
             'rgba(30, 64, 175, 0.85)',    // Azul royal
             'rgba(4, 120, 87, 0.85)',     // Verde esmeralda
             'rgba(17, 94, 89, 0.85)',     // Teal oscuro
             'rgba(71, 85, 105, 0.85)',    // Gris azulado
             'rgba(37, 99, 235, 0.85)',    // Azul medio
             'rgba(5, 150, 105, 0.85)'],   // Verde medio
        border: ['rgba(30, 58, 138, 1)', 'rgba(20, 83, 45, 1)', 'rgba(22, 78, 99, 1)',
                 'rgba(55, 65, 81, 1)', 'rgba(30, 64, 175, 1)', 'rgba(4, 120, 87, 1)',
                 'rgba(17, 94, 89, 1)', 'rgba(71, 85, 105, 1)', 'rgba(37, 99, 235, 1)',
                 'rgba(5, 150, 105, 1)']
    };
    
    const colorsCategorias = {
        bg: ['rgba(30, 58, 138, 0.85)',   // Azul navy
             'rgba(20, 83, 45, 0.85)',     // Verde bosque
             'rgba(55, 65, 81, 0.85)',     // Gris acero
             'rgba(22, 78, 99, 0.85)',     // Cyan oscuro
             'rgba(30, 64, 175, 0.85)',    // Azul royal
             'rgba(4, 120, 87, 0.85)',     // Verde esmeralda
             'rgba(71, 85, 105, 0.85)',    // Gris slate
             'rgba(17, 94, 89, 0.85)',     // Teal
             'rgba(37, 99, 235, 0.85)',    // Azul
             'rgba(5, 150, 105, 0.85)'],   // Verde
        border: ['rgba(30, 58, 138, 1)', 'rgba(20, 83, 45, 1)', 'rgba(55, 65, 81, 1)',
                 'rgba(22, 78, 99, 1)', 'rgba(30, 64, 175, 1)', 'rgba(4, 120, 87, 1)',
                 'rgba(71, 85, 105, 1)', 'rgba(17, 94, 89, 1)', 'rgba(37, 99, 235, 1)',
                 'rgba(5, 150, 105, 1)']
    };

    // (Colores de patrullas y documentos movidos a Dashboard Patrullas)

    // Opciones comunes para gráficos de barras
    const getBarChartOptions = () => ({
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
                        return `${context.raw} (${pct}%)`;
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

    // Opciones para gráficos de dona/pie
    const getDoughnutOptions = () => ({
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: { color: '#9ca3af', padding: 20, font: { size: 12 } }
            },
            tooltip: {
                backgroundColor: 'rgba(17, 24, 39, 0.95)',
                titleColor: '#fff',
                bodyColor: '#9ca3af',
                padding: 12,
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const pct = total > 0 ? ((context.raw / total) * 100).toFixed(1) : 0;
                        return `${context.label}: ${context.raw} (${pct}%)`;
                    }
                }
            }
        },
        animation: { duration: 1000, easing: 'easeOutQuart' }
    });

    // ========== GRÁFICO STACKED DE EVENTOS ==========
    
    // Opciones para gráfico stacked
    const getStackedBarChartOptions = () => ({
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: { 
                    color: '#9ca3af', 
                    padding: 15, 
                    font: { size: 11 },
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            },
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
                        const label = context.dataset.label || '';
                        const value = context.raw || 0;
                        return `${label}: ${value}`;
                    }
                }
            }
        },
        scales: {
            x: {
                stacked: true,
                grid: { color: 'rgba(75, 85, 99, 0.2)', drawBorder: false },
                ticks: { color: '#9ca3af', font: { size: 11 }, maxRotation: 45, minRotation: 45 }
            },
            y: {
                stacked: true,
                beginAtZero: true,
                grid: { color: 'rgba(75, 85, 99, 0.2)', drawBorder: false },
                ticks: { color: '#9ca3af', font: { size: 11 }, stepSize: 1 }
            }
        },
        animation: { duration: 1000, easing: 'easeOutQuart' }
    });

    // Gráfico stacked de eventos por categoría y cliente
    const ctxStacked = document.getElementById('chartEventosStacked').getContext('2d');
    const chartStacked = new Chart(ctxStacked, {
        type: 'bar',
        data: {
            labels: initialDataStacked.clientes || [],
            datasets: initialDataStacked.datasets || []
        },
        options: getStackedBarChartOptions()
    });

    // ========== GRÁFICO MENSUAL COMBINADO (Barras + Línea) ==========
    
    // Opciones para gráfico combinado mensual
    const getMensualChartOptions = (promedio) => ({
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: { 
                    color: '#9ca3af', 
                    padding: 15, 
                    font: { size: 11 },
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            },
            tooltip: {
                backgroundColor: 'rgba(17, 24, 39, 0.95)',
                titleColor: '#fff',
                bodyColor: '#9ca3af',
                borderColor: 'rgba(75, 85, 99, 0.3)',
                borderWidth: 1,
                padding: 12,
                cornerRadius: 8,
                filter: function(tooltipItem) {
                    // Ocultar tooltip para el último punto vacío
                    return tooltipItem.dataIndex < tooltipItem.chart.data.labels.length - 1;
                },
                callbacks: {
                    label: function(context) {
                        if (context.raw === null) return null;
                        if (context.datasetIndex === 0) {
                            return `Eventos: ${context.raw}`;
                        } else if (context.datasetIndex === 1) {
                            return `Tendencia: ${context.raw}`;
                        } else {
                            return `Promedio: ${Math.round(context.raw)}`;
                        }
                    }
                }
            },
        },
        scales: {
            x: {
                grid: { color: 'rgba(75, 85, 99, 0.2)', drawBorder: false },
                ticks: { 
                    color: '#9ca3af', 
                    font: { size: 11 }, 
                    maxRotation: 45, 
                    minRotation: 45,
                    callback: function(value, index) {
                        // No mostrar label para el último punto vacío
                        if (index === this.chart.data.labels.length - 1) {
                            return '';
                        }
                        return this.chart.data.labels[index];
                    }
                }
            },
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(75, 85, 99, 0.2)', drawBorder: false },
                ticks: { color: '#9ca3af', font: { size: 11 }, stepSize: 1 }
            }
        },
        animation: { duration: 1000, easing: 'easeOutQuart' }
    });

    // Plugin personalizado para etiquetas en puntos y línea de promedio
    let promedioActual = initialDataMensual.promedio || 0;
    const labelPlugin = {
        id: 'labelPlugin',
        afterDatasetsDraw: (chart) => {
            const ctx = chart.ctx;
            const meta = chart.getDatasetMeta(1); // Dataset de tendencia
            const promedio = promedioActual;
            
            // Dibujar etiquetas en puntos de la línea de tendencia
            meta.data.forEach((point, index) => {
                const value = chart.data.datasets[1].data[index];
                const x = point.x;
                const y = point.y;
                
                ctx.save();
                ctx.fillStyle = '#e5e7eb';
                ctx.font = 'bold 11px Arial';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'bottom';
                ctx.fillText(value, x, y - 10);
                ctx.restore();
            });
            
            // Dibujar etiqueta en línea de promedio (después del último punto)
            if (chart.data.labels.length > 0) {
                const lastIndex = chart.data.labels.length - 1;
                const lastPoint = chart.scales.x.getPixelForValue(lastIndex);
                const promedioY = chart.scales.y.getPixelForValue(promedio);
                const promedioTexto = 'Promedio: ' + Math.round(promedio);
                
                // Calcular espacio adicional (aproximadamente el ancho de una barra)
                const spacing = chart.scales.x.width / (chart.data.labels.length - 1);
                const labelX = lastPoint + spacing * 0.3; // Espacio después de la última barra
                
                ctx.save();
                
                // Texto en gris claro sin recuadro, posicionado ligeramente arriba de la línea
                ctx.fillStyle = 'rgba(209, 213, 219, 1)';
                ctx.font = 'bold 16px Arial';
                ctx.textAlign = 'right';
                ctx.textBaseline = 'bottom';
                ctx.fillText(promedioTexto, labelX, promedioY - 12);
                ctx.restore();
            }
        }
    };

    // Preparar datos con espacio adicional para la línea de promedio
    const labelsConEspacio = [...(initialDataMensual.labels || []), ''];
    const dataBarras = [...(initialDataMensual.data || []), null];
    const dataTendencia = [...(initialDataMensual.data || []), null];
    const dataPromedio = Array(labelsConEspacio.length).fill(initialDataMensual.promedio || 0);

    // Gráfico mensual combinado
    const ctxMensual = document.getElementById('chartEventosMensual').getContext('2d');
    const chartMensual = new Chart(ctxMensual, {
        type: 'bar',
        data: {
            labels: labelsConEspacio,
            datasets: [
                {
                    type: 'bar',
                    label: 'Eventos',
                    data: dataBarras,
                    backgroundColor: 'rgba(37, 99, 235, 0.85)',
                    borderColor: 'rgba(37, 99, 235, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false
                },
                {
                    type: 'line',
                    label: 'Tendencia',
                    data: dataTendencia,
                    borderColor: 'rgba(30, 58, 138, 1)',
                    backgroundColor: 'rgba(30, 58, 138, 0.1)',
                    borderWidth: 3,
                    fill: false,
                    tension: 0.4,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: 'rgba(30, 58, 138, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointStyle: 'circle',
                    spanGaps: false
                },
                {
                    type: 'line',
                    label: 'Promedio',
                    data: dataPromedio,
                    borderColor: 'rgba(209, 213, 219, 0.8)',
                    backgroundColor: 'rgba(209, 213, 219, 0.1)',
                    borderWidth: 3,
                    borderDash: [8, 4],
                    fill: false,
                    pointRadius: 0,
                    pointHoverRadius: 0,
                    spanGaps: true
                }
            ]
        },
        options: getMensualChartOptions(initialDataMensual.promedio),
        plugins: [labelPlugin]
    });

    // (Gráficos de patrullas y documentos movidos a Dashboard Patrullas)

    // ========== FILTROS DE EVENTOS ==========
    
    async function updateCharts() {
        const fechaDesde = document.getElementById('fecha_desde').value;
        const fechaHasta = document.getElementById('fecha_hasta').value;
        const empresaAsociada = document.getElementById('empresa_asociada_filter').value;
        
        document.getElementById('loading-stacked').classList.remove('hidden');
        document.getElementById('loading-mensual').classList.remove('hidden');

        try {
            const params = new URLSearchParams();
            if (fechaDesde) params.append('fecha_desde', fechaDesde);
            if (fechaHasta) params.append('fecha_hasta', fechaHasta);
            if (empresaAsociada) params.append('empresa_asociada_id', empresaAsociada);
            const queryString = params.toString() ? `?${params.toString()}` : '';

            const [resStacked, resMensual] = await Promise.all([
                fetch(urlStacked + queryString),
                fetch(urlMensual + queryString)
            ]);

            const dataStacked = await resStacked.json();
            const dataMensual = await resMensual.json();

            // Actualizar gráfico stacked
            chartStacked.data.labels = dataStacked.clientes || [];
            chartStacked.data.datasets = dataStacked.datasets || [];
            chartStacked.update('active');

            // Actualizar gráfico mensual con espacio adicional para la línea de promedio
            const labelsConEspacio = [...(dataMensual.labels || []), ''];
            const dataBarras = [...(dataMensual.data || []), null];
            const dataTendencia = [...(dataMensual.data || []), null];
            const dataPromedio = Array(labelsConEspacio.length).fill(dataMensual.promedio || 0);
            
            chartMensual.data.labels = labelsConEspacio;
            chartMensual.data.datasets[0].data = dataBarras;
            chartMensual.data.datasets[1].data = dataTendencia;
            chartMensual.data.datasets[2].data = dataPromedio;
            // Actualizar el promedio en el plugin personalizado
            promedioActual = dataMensual.promedio || 0;
            chartMensual.update('active');

            // Actualizar también el mapa de calor si existe
            if (typeof window.heatmapReload === 'function') {
                window.heatmapReload();
            }

        } catch (error) {
            console.error('Error al cargar datos:', error);
        } finally {
            document.getElementById('loading-stacked').classList.add('hidden');
            document.getElementById('loading-mensual').classList.add('hidden');
        }
    }

    // Event listeners
    document.getElementById('btn_filtrar').addEventListener('click', updateCharts);
    document.getElementById('btn_limpiar').addEventListener('click', function() {
        document.getElementById('fecha_desde').value = '';
        document.getElementById('fecha_hasta').value = '';
        document.getElementById('empresa_asociada_filter').value = '';
        updateCharts();
    });
    
    // Event listener para generar PDF
    const btnGenerarPdf = document.getElementById('btn_generar_pdf');
    if (btnGenerarPdf) {
        btnGenerarPdf.addEventListener('click', function() {
            const fechaDesde = document.getElementById('fecha_desde').value;
            const fechaHasta = document.getElementById('fecha_hasta').value;
            const empresaAsociada = document.getElementById('empresa_asociada_filter').value;
            
            const params = new URLSearchParams();
            if (fechaDesde) params.append('fecha_desde', fechaDesde);
            if (fechaHasta) params.append('fecha_hasta', fechaHasta);
            if (empresaAsociada) params.append('empresa_asociada_id', empresaAsociada);
            
            const url = '{{ route("client.dashboard.pdf") }}' + (params.toString() ? '?' + params.toString() : '');
            window.open(url, '_blank');
        });
    }
});

// ========== INTERACCIÓN FILTROS MAPA DE CALOR ==========
document.addEventListener('DOMContentLoaded', function () {
    const btnAplicar   = document.getElementById('heatmap_aplicar_filtros');
    const btnLimpiar   = document.getElementById('heatmap_limpiar_filtros');
    const chkTodos     = document.getElementById('heatmap_cliente_todos');
    const chkClientes  = document.querySelectorAll('.heatmap-cliente-item');
    const chkCategoriaTodos = document.getElementById('heatmap_categoria_todos');
    const chkCategorias = document.querySelectorAll('.heatmap-categoria-item');
    const chkTipoTodos = document.getElementById('heatmap_tipo_todos');

    function setButtonDefault() {
        if (!btnAplicar) return;
        btnAplicar.textContent = 'Aplicar filtros del mapa';
        btnAplicar.classList.remove('bg-emerald-600', 'hover:bg-emerald-700');
        if (!btnAplicar.classList.contains('bg-blue-600')) {
            btnAplicar.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }
    }

    // Manejar "Seleccionar todos los clientes"
    if (chkTodos) {
        chkTodos.addEventListener('change', function () {
            const checked = chkTodos.checked;
            chkClientes.forEach(c => { c.checked = checked; });
            setButtonDefault();
        });
    }

    // Manejar "Seleccionar todas las categorías"
    // NOTA: Los listeners de categorías y tipos están manejados en el bloque del mapa de calor
    // (líneas ~1454 y ~1478) para evitar duplicación. Este bloque solo maneja clientes.
    // if (chkCategoriaTodos) {
    //     chkCategoriaTodos.addEventListener('change', function () {
    //         const checked = chkCategoriaTodos.checked;
    //         chkCategorias.forEach(c => { 
    //             c.checked = checked; 
    //         });
    //         setButtonDefault();
    //     });
    // }

    // Manejar "Seleccionar todos los tipos"
    // NOTA: Los listeners de tipos están manejados en el bloque del mapa de calor
    // if (chkTipoTodos) {
    //     chkTipoTodos.addEventListener('change', function () {
    //         const checked = chkTipoTodos.checked;
    //         const chkTipos = document.querySelectorAll('.heatmap-tipo-item');
    //         chkTipos.forEach(c => { 
    //             c.checked = checked; 
    //         });
    //         setButtonDefault();
    //     });
    // }

    // Si se cambia cualquier checkbox de cliente, volver a estado "Aplicar filtros"
    chkClientes.forEach(c => {
        c.addEventListener('change', function () {
            if (chkTodos) {
                const allChecked = Array.from(chkClientes).every(cb => cb.checked);
                chkTodos.checked = allChecked;
            }
            setButtonDefault();
        });
    });



    // Botón aplicar: dispara recarga del mapa
    if (btnAplicar) {
        btnAplicar.addEventListener('click', function (e) {
            e.preventDefault();
            if (window.heatmapReload) {
                window.heatmapLastActionWasApply = true;
                window.heatmapReload();
            }
        });
    }

    // Botón limpiar: borra selección y recarga mapa sin filtros
    if (btnLimpiar) {
        btnLimpiar.addEventListener('click', function (e) {
            e.preventDefault();
            // Limpiar clientes
            if (chkTodos) chkTodos.checked = false;
            chkClientes.forEach(c => { c.checked = false; });
            
            // Limpiar categorías
            if (chkCategoriaTodos) chkCategoriaTodos.checked = false;
            chkCategorias.forEach(c => { c.checked = false; });
            
            // Limpiar tipos
            if (chkTipoTodos) chkTipoTodos.checked = false;
            const chkTipos = document.querySelectorAll('.heatmap-tipo-item');
            chkTipos.forEach(c => { c.checked = false; });
            
            // Vaciar lista de tipos
            const tiposLista = document.getElementById('heatmap_tipos_lista');
            if (tiposLista) {
                tiposLista.innerHTML = '';
            }
            
            setButtonDefault();
            if (window.heatmapReload) {
                window.heatmapReload();
            }
        });
    }

    // Estado inicial del botón
    setButtonDefault();
});
    // ========== MAPA DE CALOR ==========

    (function () {
        const apiUrl = "{{ route('client.dashboard.eventos-mapa-calor') }}";
        const apiUrlEventosUbicacion = "{{ route('client.dashboard.eventos-por-ubicacion') }}";
        const categoriasTiposMapa = @json($categoriasTiposMapa ?? []);

        let mapInstance = null;
        let heatLayer = null;
        let markersLayer = null;
        let baseLayers = {};
        let currentBaseLayer = null;
        let labelsLayer = null;

        const container = document.getElementById('heatmap-container');
        const loading = document.getElementById('heatmap-loading');

        const btnAplicar = document.getElementById('heatmap_aplicar_filtros');
        const btnLimpiar = document.getElementById('heatmap_limpiar_filtros');

        const chkClienteTodos = document.getElementById('heatmap_cliente_todos');
        const chkClientes = document.querySelectorAll('.heatmap-cliente-item');

        // Categorias y tipos
        const chkCategoriaTodos = document.getElementById('heatmap_categoria_todos');
        const chkCategorias = document.querySelectorAll('.heatmap-categoria-item');

        const selectTipos = document.getElementById('heatmap_tipos');

        const detalleToggle = document.getElementById('heatmap_detalle_toggle');
        const detallePanel = document.getElementById('heatmap_detalle_panel');
        const detalleIcon = document.getElementById('heatmap_detalle_icon');

        let tiposPorCategoria = {};

        // Verificar que Leaflet esté cargado
        if (typeof L === 'undefined') {
            console.error('Leaflet no está disponible');
            if (loading) {
                loading.innerHTML = '<div class="text-center text-red-400"><p>Error: No se pudo cargar la biblioteca de mapas</p></div>';
            }
            return;
        }

        // Inicializar tipos por categoría
        function inicializarTiposPorCategoria() {
            tiposPorCategoria = {};
            categoriasTiposMapa.forEach(cat => {
                // Usar tanto string como número como clave para evitar problemas de tipo
                const id = String(cat.id);
                tiposPorCategoria[id] = cat.tipos || [];
                tiposPorCategoria[cat.id] = cat.tipos || []; // También como número
            });
            console.log('Tipos por categoría inicializados:', tiposPorCategoria);
        }

        // Mapa inicial
        function initMap() {
            if (mapInstance) return;

            mapInstance = L.map('heatmap-container', {
                center: [-38.9516, -68.0591], // Centro en Neuquén por defecto
                zoom: 10,
                zoomControl: true
            });

            // Crear capas base
            // Capa satelital (Esri World Imagery)
            baseLayers.satelital = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '&copy; Esri, Maxar, Earthstar Geographics',
                maxZoom: 19
            });

            // Capa física/normal (OpenStreetMap)
            baseLayers.fisico = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            });

            // Capa de etiquetas con rutas y nombres (solo para satelital)
            labelsLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}{r}.png', {
                attribution: '',
                subdomains: 'abcd',
                maxZoom: 19,
                pane: 'shadowPane'
            });

            // Agregar capa satelital por defecto con etiquetas
            baseLayers.satelital.addTo(mapInstance);
            labelsLayer.addTo(mapInstance);
            currentBaseLayer = 'satelital';

            markersLayer = L.layerGroup().addTo(mapInstance);

            // Guardar posición inicial del mapa
            const initialCenter = [-38.9516, -68.0591];
            const initialZoom = 10;

            // Crear control personalizado para restablecer vista
            const ResetViewControl = L.Control.extend({
                onAdd: function(map) {
                    const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
                    const button = L.DomUtil.create('a', 'leaflet-control-reset-view', container);
                    button.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
                    button.href = '#';
                    button.title = 'Restablecer vista inicial y refrescar mapa';
                    
                    L.DomEvent.disableClickPropagation(button);
                    L.DomEvent.on(button, 'click', function(e) {
                        L.DomEvent.stopPropagation(e);
                        L.DomEvent.preventDefault(e);
                        // Restablecer vista inicial
                        mapInstance.setView(initialCenter, initialZoom, {
                            animate: true,
                            duration: 0.5
                        });
                        // Recargar el mapa para refrescar los datos
                        if (window.heatmapReload) {
                            window.heatmapReload();
                        }
                    });

                    return container;
                }
            });

            // Agregar control al mapa
            new ResetViewControl({ position: 'topleft' }).addTo(mapInstance);

            // Crear control para cambiar estilo del mapa
            const MapStyleControl = L.Control.extend({
                onAdd: function(map) {
                    const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
                    const button = L.DomUtil.create('a', 'leaflet-control-map-style', container);
                    button.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z"></path></svg>';
                    button.href = '#';
                    button.title = 'Cambiar estilo del mapa';
                    
                    L.DomEvent.disableClickPropagation(button);
                    L.DomEvent.on(button, 'click', function(e) {
                        L.DomEvent.stopPropagation(e);
                        L.DomEvent.preventDefault(e);
                        
                        // Cambiar entre capas
                        if (currentBaseLayer === 'satelital') {
                            // Cambiar a físico
                            mapInstance.removeLayer(baseLayers.satelital);
                            mapInstance.removeLayer(labelsLayer);
                            baseLayers.fisico.addTo(mapInstance);
                            currentBaseLayer = 'fisico';
                            button.title = 'Cambiar estilo del mapa';
                        } else {
                            // Cambiar a satelital
                            mapInstance.removeLayer(baseLayers.fisico);
                            baseLayers.satelital.addTo(mapInstance);
                            labelsLayer.addTo(mapInstance);
                            currentBaseLayer = 'satelital';
                            button.title = 'Cambiar estilo del mapa';
                        }
                    });

                    return container;
                }
            });

            // Agregar control de cambio de estilo al mapa (debajo del botón de reset)
            new MapStyleControl({ position: 'topleft' }).addTo(mapInstance);
        }

        function buildQueryParams() {
            const params = new URLSearchParams();

            // Filtro por clientes (checkboxes)
            if (chkClientes && chkClientes.length > 0) {
                const selectedClientes = Array.from(chkClientes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value)
                    .filter(Boolean);

                if (selectedClientes.length > 0) {
                    params.append('empresa_asociada_id', selectedClientes.join(','));
                    console.log('Filtro clientes:', selectedClientes);
                }
            }

            // Filtro por categorías (checkboxes)
            if (chkCategorias && chkCategorias.length > 0) {
                const selectedCategorias = Array.from(chkCategorias)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value)
                    .filter(Boolean);

                if (selectedCategorias.length > 0) {
                    params.append('categorias', selectedCategorias.join(','));
                    console.log('Filtro categorías:', selectedCategorias);
                } else {
                    console.log('Filtro categorías: NINGUNA seleccionada');
                }
            }

            // Filtro por tipos de evento (checkboxes)
            const chkTipos = document.querySelectorAll('.heatmap-tipo-item');
            if (chkTipos && chkTipos.length > 0) {
                const selectedTipos = Array.from(chkTipos)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value)
                    .filter(Boolean);

                if (selectedTipos.length > 0) {
                    params.append('tipos', selectedTipos.join(','));
                    console.log('Filtro tipos:', selectedTipos);
                } else {
                    console.log('Filtro tipos: NINGUNO seleccionado');
                }
            }
            
            console.log('Query params:', params.toString());
            return params.toString() ? `?${params.toString()}` : '';
        }

        // Actualiza las opciones de tipos según las categorías seleccionadas
        function actualizarTiposPorCategorias() {
            console.log('actualizarTiposPorCategorias() llamada');

            const tiposLista = document.getElementById('heatmap_tipos_lista');
            const chkTipoTodos = document.getElementById('heatmap_tipo_todos');
            
            if (!tiposLista) {
                console.error('No se encontró heatmap_tipos_lista');
                return;
            }

            const selectedCategorias = Array.from(chkCategorias)
                .filter(cb => cb.checked)
                .map(cb => cb.value)
                .filter(Boolean);
            
            console.log('Categorías seleccionadas:', selectedCategorias);
            console.log('Tipos por categoría disponibles:', tiposPorCategoria);

            // Limpiamos lista actual
            tiposLista.innerHTML = '';

            if (selectedCategorias.length === 0) {
                // Habilitar/deshabilitar checkbox "todos"
                if (chkTipoTodos) {
                    chkTipoTodos.disabled = true;
                    chkTipoTodos.checked = false;
                }
                
                // Mostrar mensaje cuando no hay categorías seleccionadas
                tiposLista.innerHTML = '<div class="text-center py-3 text-gray-500 text-xs italic">Selecciona categorías primero</div>';
                return;
            }

            // Recopilar todos los tipos únicos de las categorías seleccionadas
            const tiposSet = new Set();
            selectedCategorias.forEach(categoriaId => {
                console.log(`Buscando tipos para categoría ID: ${categoriaId} (tipo: ${typeof categoriaId})`);
                // Buscar tanto como string como número para asegurar compatibilidad
                let tipos = tiposPorCategoria[categoriaId] || tiposPorCategoria[String(categoriaId)] || tiposPorCategoria[Number(categoriaId)] || [];
                
                // Si aún no encontramos, buscar en categoriasTiposMapa directamente
                if (!tipos || tipos.length === 0) {
                    const categoria = categoriasTiposMapa.find(cat => String(cat.id) === String(categoriaId));
                    tipos = categoria ? (categoria.tipos || []) : [];
                    console.log(`Tipos encontrados directamente en categoriasTiposMapa para ${categoriaId}:`, tipos);
                } else {
                    console.log(`Tipos encontrados en tiposPorCategoria para ${categoriaId}:`, tipos);
                }
                
                if (Array.isArray(tipos)) {
                    tipos.forEach(tipo => {
                        if (tipo) tiposSet.add(String(tipo));
                    });
                }
            });

            console.log(`Total de tipos únicos encontrados: ${tiposSet.size}`, Array.from(tiposSet));

            if (tiposSet.size === 0) {
                // Habilitar/deshabilitar checkbox "todos"
                if (chkTipoTodos) {
                    chkTipoTodos.disabled = true;
                    chkTipoTodos.checked = false;
                }
                
                // Mostrar mensaje cuando no hay tipos disponibles
                tiposLista.innerHTML = '<div class="text-center py-3 text-gray-500 text-xs italic">No hay tipos disponibles para las categorías seleccionadas</div>';
                return;
            }

            console.log(`Creando ${tiposSet.size} checkboxes de tipos`);
            // Ordenar y agregar checkboxes
            Array.from(tiposSet).sort().forEach(tipo => {
                console.log(`Creando checkbox para tipo: ${tipo}`);
                const label = document.createElement('label');
                label.className = 'flex items-center gap-2 text-xs text-gray-200 cursor-pointer hover:bg-zinc-800/60 px-2 py-1 rounded';
                
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.className = 'heatmap-tipo-item h-4 w-4 rounded border-zinc-500 bg-zinc-800 text-blue-500 focus:ring-blue-500';
                checkbox.value = tipo;
                
                const span = document.createElement('span');
                span.textContent = tipo;
                
                label.appendChild(checkbox);
                label.appendChild(span);
                tiposLista.appendChild(label);
                
                // Event listener para cada checkbox de tipo
                checkbox.addEventListener('change', function() {
                    // Actualizar checkbox "todos"
                    const allTipos = document.querySelectorAll('.heatmap-tipo-item');
                    const allChecked = Array.from(allTipos).every(cb => cb.checked);
                    
                    if (chkTipoTodos) {
                        chkTipoTodos.checked = allChecked;
                    }
                    
                    setButtonDefault();
                });
            });

            // Habilitar checkbox "todos"
            if (chkTipoTodos) {
                chkTipoTodos.disabled = false;
                chkTipoTodos.checked = false;
            }
        }

        async function loadHeatmap() {
            initMap();

            if (loading) {
                loading.classList.remove('hidden');
                loading.style.display = 'flex';
            }

            const queryString = buildQueryParams();
            console.log('Solicitando datos a:', apiUrl + queryString);


            try {
                const response = await fetch(apiUrl + queryString);
                console.log('Respuesta recibida, status:', response.status);
                const data = await response.json();

                console.log('Datos recibidos:', {
                    totalPuntos: data?.length || 0,
                    esArray: Array.isArray(data),
                    tieneError: data?.error,
                    primerosPuntos: data?.slice(0, 3)
                });

                if (loading) {
                    loading.style.display = 'none';
                }

                // Limpiar capas anteriores
                if (heatLayer) {
                    heatLayer.remove();
                    heatLayer = null;
                }
                if (markersLayer) {
                    markersLayer.clearLayers();
                }
                
                // Eliminar cualquier placeholder existente
                const existingPlaceholder = container.querySelector('.absolute.inset-0');
                if (existingPlaceholder) {
                    existingPlaceholder.remove();
                }

                if (data && data.length > 0) {
                    // Calcular intensidad máxima para normalizar
                    const maxCount = Math.max(...data.map(p => p.count || 1));

                    // Preparar datos para el heatmap [lat, lng, intensity]
                    const heatData = data.map(point => [
                        point.lat,
                        point.lng,
                        (point.count || 1) / maxCount // Normalizar intensidad
                    ]);

                    // Crear capa de calor con colores profesionales de seguridad
                    heatLayer = L.heatLayer(heatData, {
                        radius: 35,
                        blur: 25,
                        maxZoom: 17,
                        max: 1.0,
                        minOpacity: 0.6,
                        gradient: {
                            0.0: '#1e40af',  // Azul royal
                            0.3: '#0891b2',  // Cyan
                            0.5: '#059669',  // Verde esmeralda
                            0.7: '#ca8a04',  // Dorado/ámbar
                            1.0: '#b91c1c'   // Rojo (zonas críticas)
                        }
                    }).addTo(mapInstance);

                    // Ajustar vista a los puntos
                    const bounds = L.latLngBounds(heatData.map(p => [p[0], p[1]]));
                    mapInstance.fitBounds(bounds, { padding: [40, 40] });

                    // Agregar marcadores para cada ubicación con eventos
                    data.forEach(point => {
                        if (point.lat && point.lng) {
                            const count = point.count || 1;
                            const radius = Math.min(6 + count * 2, 18); // Radio según cantidad

                            // Colores profesionales según cantidad de eventos
                            let fillColor = '#1e3a8a'; // Azul navy por defecto
                            if (count >= 5) fillColor = '#7f1d1d'; // Rojo oscuro para muchos (alerta)
                            else if (count >= 3) fillColor = '#115e59'; // Teal oscuro para varios
                            else if (count >= 2) fillColor = '#166534'; // Verde oscuro para pocos

                            const marker = L.circleMarker([point.lat, point.lng], {
                                radius: radius,
                                fillColor: fillColor,
                                color: '#e5e7eb',
                                weight: 2,
                                opacity: 1,
                                fillOpacity: 0.9
                            });

                            // Crear popup con contenido dinámico
                            const popupContent = document.createElement('div');
                            popupContent.style.cssText = 'min-width: 300px; max-width: 400px;';
                            popupContent.innerHTML = `
                                <div style="padding: 16px; background: #1f2937; border-radius: 8px;">
                                    <div style="text-align: center; margin-bottom: 16px;">
                                        <div style="font-size: 28px; font-weight: bold; color: #e5e7eb;">${count}</div>
                                        <div style="color: #9ca3af; font-size: 13px; margin-top: 4px; font-weight: 500; letter-spacing: 0.5px;">EVENTOS</div>
                                    </div>
                                    <div style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 16px;">
                                        <code style="background: #111827; padding: 4px 8px; border-radius: 4px; color: #60a5fa; font-size: 12px;" id="coords-${point.lat}-${point.lng}">${point.lat.toFixed(4)}, ${point.lng.toFixed(4)}</code>
                                        <button onclick="copyCoords('${point.lat.toFixed(4)}, ${point.lng.toFixed(4)}', this)" title="Copiar" style="background: #3b82f6; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px;">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div id="eventos-list-${point.lat}-${point.lng}">
                                        <div style="text-align: center; padding: 20px; color: #6b7280;">
                                            <div class="animate-spin rounded-full h-6 w-6 border-2 border-blue-500 border-t-transparent mx-auto mb-2"></div>
                                            <div style="font-size: 12px;">Cargando eventos...</div>
                                        </div>
                                    </div>
                                </div>
                            `;

                            marker.bindPopup(popupContent, {
                                maxWidth: 400,
                                className: 'custom-popup'
                            });

                            // Función para cargar eventos de la ubicación
                            async function loadEventosForMarker(lat, lng) {
                                const eventosListDiv = document.getElementById(`eventos-list-${lat}-${lng}`);
                                if (!eventosListDiv) return;

                                try {
                                    // Construir parámetros de filtros actuales
                                    const params = new URLSearchParams();
                                    params.append('lat', lat);
                                    params.append('lng', lng);

                                    // Agregar filtros activos
                                    const selectedClientes = Array.from(chkClientes)
                                        .filter(cb => cb.checked)
                                        .map(cb => cb.value)
                                        .filter(Boolean);
                                    if (selectedClientes.length > 0) {
                                        params.append('empresa_asociada_id', selectedClientes.join(','));
                                    }

                                    const selectedCategorias = Array.from(chkCategorias)
                                        .filter(cb => cb.checked)
                                        .map(cb => cb.value)
                                        .filter(Boolean);
                                    if (selectedCategorias.length > 0) {
                                        params.append('categorias', selectedCategorias.join(','));
                                    }

                                    const chkTipos = document.querySelectorAll('.heatmap-tipo-item');
                                    const selectedTipos = Array.from(chkTipos)
                                        .filter(cb => cb.checked)
                                        .map(cb => cb.value)
                                        .filter(Boolean);
                                    if (selectedTipos.length > 0) {
                                        params.append('tipos', selectedTipos.join(','));
                                    }

                                    const response = await fetch(apiUrlEventosUbicacion + '?' + params.toString());
                                    const data = await response.json();

                                    if (data.error) {
                                        eventosListDiv.innerHTML = `<div style="color: #ef4444; font-size: 12px; text-align: center;">Error al cargar eventos</div>`;
                                        return;
                                    }

                                    if (!data.eventos || data.eventos.length === 0) {
                                        eventosListDiv.innerHTML = `<div style="color: #6b7280; font-size: 12px; text-align: center;">No hay eventos en esta ubicación</div>`;
                                        return;
                                    }

                                    // Construir HTML del listado de eventos
                                    let eventosHTML = `
                                        <div style="font-size: 11px; color: #6b7280; margin-bottom: 8px; font-weight: 500;">LISTADO DE EVENTOS</div>
                                        <div class="popup-eventos-scroll" style="max-height: 200px; overflow-y: auto;">
                                    `;

                                    data.eventos.forEach((evento, index) => {
                                        eventosHTML += `
                                            <div style="background: #111827; border: 1px solid #374151; border-radius: 6px; padding: 10px; margin-bottom: 8px;">
                                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 6px;">
                                                    <div style="flex: 1;">
                                                        <div style="font-size: 12px; font-weight: 600; color: #e5e7eb; margin-bottom: 4px;">${evento.fecha_hora_formatted}</div>
                                                        <div style="font-size: 11px; color: #9ca3af;">
                                                            <span style="color: #60a5fa;">${evento.categoria}</span>
                                                            ${evento.tipo ? ` • ${evento.tipo}` : ''}
                                                        </div>
                                                        <div style="font-size: 11px; color: #6b7280; margin-top: 4px;">
                                                            Cliente: <span style="color: #9ca3af;">${evento.cliente}</span>
                                                        </div>
                                                    </div>
                                                    <button onclick="verEvento(${evento.id})" style="background: #3b82f6; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 11px; font-weight: 500; margin-left: 8px; white-space: nowrap;">Ver evento</button>
                                                </div>
                                            </div>
                                        `;
                                    });

                                    eventosHTML += `</div>`;
                                    eventosListDiv.innerHTML = eventosHTML;

                                } catch (error) {
                                    console.error('Error cargando eventos:', error);
                                    eventosListDiv.innerHTML = `<div style="color: #ef4444; font-size: 12px; text-align: center;">Error al cargar eventos</div>`;
                                }
                            }

                            // Click en marcador: hacer zoom y cargar eventos
                            marker.on('click', async function(e) {
                                // Hacer zoom al punto
                                mapInstance.setView([point.lat, point.lng], Math.max(mapInstance.getZoom(), 15), {
                                    animate: true,
                                    duration: 0.5
                                });

                                // Abrir popup
                                marker.openPopup();

                                // Cargar eventos si aún no se han cargado
                                const eventosListDiv = document.getElementById(`eventos-list-${point.lat}-${point.lng}`);
                                if (eventosListDiv && eventosListDiv.innerHTML.includes('Cargando eventos...')) {
                                    await loadEventosForMarker(point.lat, point.lng);
                                }
                            });

                            // Cargar eventos al hacer hover (opcional, para precargar)
                            marker.on('mouseover', function() {
                                const eventosListDiv = document.getElementById(`eventos-list-${point.lat}-${point.lng}`);
                                if (eventosListDiv && eventosListDiv.innerHTML.includes('Cargando eventos...')) {
                                    loadEventosForMarker(point.lat, point.lng);
                                }
                            });

                            marker.addTo(markersLayer);
                        }
                    });
                }
            } catch (error) {
                console.error('Error cargando datos del mapa:', error);
                if (loading) {
                    loading.innerHTML = '<div class="text-center text-red-400"><p>Error al cargar datos del mapa</p></div>';
                }
            }
        }

        // Inicializar eventos
        function inicializarEventos() {
            // Inicializar tipos por categoría
            inicializarTiposPorCategoria();

            // Manejar "Seleccionar todos los clientes"
            if (chkClienteTodos) {
                chkClienteTodos.addEventListener('change', function () {
                    const checked = chkClienteTodos.checked;
                    chkClientes.forEach(c => { c.checked = checked; });
                    setButtonDefault();
                });
            }

            // Manejar "Seleccionar todas las categorías"
            if (chkCategoriaTodos) {
                chkCategoriaTodos.addEventListener('change', function () {
                    const checked = chkCategoriaTodos.checked;
                    chkCategorias.forEach(c => { 
                        c.checked = checked; 
                    });
                    // Actualizar tipos cuando cambian las categorías
                    actualizarTiposPorCategorias();
                    setButtonDefault();
                });
            }

            // Si se cambia cualquier checkbox de cliente, volver a estado "Aplicar filtros"
            chkClientes.forEach(c => {
                c.addEventListener('change', function () {
                    if (chkClienteTodos) {
                        const allChecked = Array.from(chkClientes).every(cb => cb.checked);
                        chkClienteTodos.checked = allChecked;
                    }
                    setButtonDefault();
                });
            });

            // Si se cambia cualquier checkbox de categoría, actualizar tipos y botón
            chkCategorias.forEach(c => {
                c.addEventListener('change', function () {
                    if (chkCategoriaTodos) {
                        const allChecked = Array.from(chkCategorias).every(cb => cb.checked);
                        chkCategoriaTodos.checked = allChecked;
                    }
                    // Actualizar tipos cuando cambian las categorías
                    actualizarTiposPorCategorias();
                    setButtonDefault();
                });
            });

            // Manejar "Seleccionar todos los tipos" - delegación de eventos para elementos dinámicos
            const chkTipoTodos = document.getElementById('heatmap_tipo_todos');
            if (chkTipoTodos) {
                chkTipoTodos.addEventListener('change', function () {
                    const checked = chkTipoTodos.checked;
                    const chkTipos = document.querySelectorAll('.heatmap-tipo-item');
                    chkTipos.forEach(c => { 
                        c.checked = checked; 
                    });
                    setButtonDefault();
                });
            }

            // Delegación de eventos para checkboxes de tipos dinámicos
            document.addEventListener('change', function(e) {
                if (e.target && e.target.classList.contains('heatmap-tipo-item')) {
                    // Actualizar checkbox "todos" cuando cambian los tipos individuales
                    const chkTipoTodos = document.getElementById('heatmap_tipo_todos');
                    if (chkTipoTodos && !chkTipoTodos.disabled) {
                        const allTipos = document.querySelectorAll('.heatmap-tipo-item');
                        const allChecked = Array.from(allTipos).every(cb => cb.checked);
                        chkTipoTodos.checked = allChecked;
                    }
                    setButtonDefault();
                }
            });

            // Botón aplicar
            if (btnAplicar) {
                btnAplicar.addEventListener('click', function (e) {
                    e.preventDefault();
                    window.heatmapLastActionWasApply = true;
                    loadHeatmap();
                });
            }

            // Botón limpiar
            if (btnLimpiar) {
                btnLimpiar.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    // Limpiar clientes
                    if (chkClienteTodos) chkClienteTodos.checked = false;
                    chkClientes.forEach(c => { c.checked = false; });
                    
                    // Limpiar categorías
                    if (chkCategoriaTodos) chkCategoriaTodos.checked = false;
                    chkCategorias.forEach(c => { c.checked = false; });
                    
                    // Limpiar tipos
                    const chkTipoTodos = document.getElementById('heatmap_tipo_todos');
                    if (chkTipoTodos) {
                        chkTipoTodos.checked = false;
                        chkTipoTodos.disabled = true;
                    }
                    
                    // Vaciar lista de tipos
                    const tiposLista = document.getElementById('heatmap_tipos_lista');
                    if (tiposLista) {
                        tiposLista.innerHTML = '<div class="text-center py-3 text-gray-500 text-xs italic">Selecciona categorías primero</div>';
                    }
                    
                    // También limpiar cualquier checkbox de tipo existente
                    const chkTipos = document.querySelectorAll('.heatmap-tipo-item');
                    chkTipos.forEach(c => { c.checked = false; });
                    
                    setButtonDefault();
                    loadHeatmap();
                });
            }

            // Toggle del menú desplegable de detalle
            // if (detalleToggle && detallePanel && detalleIcon) {
            //     detalleToggle.addEventListener('click', () => {
            //         const isHidden = detallePanel.classList.contains('hidden');
            //         detallePanel.classList.toggle('hidden');
            //         detalleIcon.classList.toggle('rotate-180', isHidden);
            //     });
            // }
        }

        function setButtonDefault() {
        if (!btnAplicar) return;
        btnAplicar.textContent = 'Aplicar filtros del mapa';
        btnAplicar.classList.remove('bg-emerald-600', 'hover:bg-emerald-700');
        if (!btnAplicar.classList.contains('bg-blue-600')) {
            btnAplicar.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }
    }

    // Inicializar eventos y cargar mapa
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            inicializarEventos();
            // Llamar a actualizarTiposPorCategorias después de inicializar para mostrar tipos si hay categorías preseleccionadas
            setTimeout(() => {
                actualizarTiposPorCategorias();
            }, 100);
            loadHeatmap();
        });
    } else {
        inicializarEventos();
        // Llamar a actualizarTiposPorCategorias después de inicializar para mostrar tipos si hay categorías preseleccionadas
        setTimeout(() => {
            actualizarTiposPorCategorias();
        }, 100);
        loadHeatmap();
    }

    window.heatmapReload = loadHeatmap;
    window.actualizarTiposPorCategorias = actualizarTiposPorCategorias;
    })();
</script>

<style>
    .recorrido-popup .leaflet-popup-content-wrapper {
        background: #1f2937; color: #e5e7eb; border-radius: 12px;
        border: 1px solid #374151; box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    }
    .recorrido-popup .leaflet-popup-tip { background: #1f2937; }
    .recorrido-popup .leaflet-popup-content { margin: 0; padding: 0; }
    .recmap-tooltip {
        background: rgba(31,41,55,0.92); color: #e5e7eb; border: 1px solid #374151;
        border-radius: 8px; padding: 6px 12px; font-size: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.4); backdrop-filter: blur(4px);
    }
    .recmap-tooltip .leaflet-tooltip-arrow { display: none; }
    #recorridos-map .leaflet-control-map-style a {
        width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;
        background: #fff; color: #333;
    }
    #recorridos-map .leaflet-control-map-style a svg { width: 16px; height: 16px; }
</style>

<script>
// ========== MAPA DE INTENSIDAD DE RECORRIDOS + GRÁFICOS ==========
(function() {
    const urlRecorridosMap = "{{ route('client.dashboard-patrullas.recorridos-map') }}";
    const urlTendencia = "{{ route('client.dashboard-patrullas.recorridos-tendencia') }}";
    const urlTopRecorridos = "{{ route('client.dashboard-patrullas.top-recorridos') }}";
    const urlIndicadores = "{{ route('client.dashboard-patrullas.indicadores') }}";

    let recMap = null;
    let recRouteLayers = [];
    let recBaseLayers = {};
    let recLabelsLayer = null;
    let recCurrentBase = 'satelital';

    // Same gradient as the events heatmap: blue -> cyan -> green -> yellow -> red
    function getHeatColor(intensity) {
        const stops = [
            { pos: 0.0, r: 37,  g: 99,  b: 235 }, // #2563eb
            { pos: 0.3, r: 6,   g: 182, b: 212 }, // #06b6d4
            { pos: 0.6, r: 132, g: 204, b: 22  }, // #84cc16
            { pos: 0.8, r: 234, g: 179, b: 8   }, // #eab308
            { pos: 1.0, r: 239, g: 68,  b: 68  }, // #ef4444
        ];
        const t = Math.max(0, Math.min(1, intensity));
        let lower = stops[0], upper = stops[stops.length - 1];
        for (let i = 0; i < stops.length - 1; i++) {
            if (t >= stops[i].pos && t <= stops[i + 1].pos) {
                lower = stops[i]; upper = stops[i + 1]; break;
            }
        }
        const range = upper.pos - lower.pos || 1;
        const f = (t - lower.pos) / range;
        const r = Math.round(lower.r + f * (upper.r - lower.r));
        const g = Math.round(lower.g + f * (upper.g - lower.g));
        const b = Math.round(lower.b + f * (upper.b - lower.b));
        return `rgb(${r},${g},${b})`;
    }

    function initRecMap() {
        if (recMap) return;
        recMap = L.map('recorridos-map', { center: [-38.95, -68.05], zoom: 12, zoomControl: true });

        // Grayscale base layer (CartoDB Positron)
        recBaseLayers.grayscale = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://carto.com/">CartoDB</a>',
            subdomains: 'abcd', maxZoom: 19
        });

        // Satellite base layer (Esri)
        recBaseLayers.satelital = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: '&copy; Esri, Maxar, Earthstar Geographics', maxZoom: 19
        });

        // Labels overlay for satellite mode
        recLabelsLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}{r}.png', {
            attribution: '', subdomains: 'abcd', maxZoom: 19, pane: 'shadowPane'
        });

        // Default: satellite with labels
        recBaseLayers.satelital.addTo(recMap);
        recLabelsLayer.addTo(recMap);
        recCurrentBase = 'satelital';

        // Style toggle control
        const MapStyleCtrl = L.Control.extend({
            onAdd: function() {
                const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-map-style');
                const btn = L.DomUtil.create('a', '', container);
                btn.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z"></path></svg>';
                btn.href = '#';
                btn.title = 'Cambiar a vista de mapa';
                L.DomEvent.disableClickPropagation(container);
                L.DomEvent.on(btn, 'click', function(e) {
                    L.DomEvent.stopPropagation(e);
                    L.DomEvent.preventDefault(e);
                    if (recCurrentBase === 'satelital') {
                        recMap.removeLayer(recBaseLayers.satelital);
                        recMap.removeLayer(recLabelsLayer);
                        recBaseLayers.grayscale.addTo(recMap);
                        recCurrentBase = 'grayscale';
                        btn.title = 'Cambiar a vista satelital';
                    } else {
                        recMap.removeLayer(recBaseLayers.grayscale);
                        recBaseLayers.satelital.addTo(recMap);
                        recLabelsLayer.addTo(recMap);
                        recCurrentBase = 'satelital';
                        btn.title = 'Cambiar a vista de mapa';
                    }
                });
                return container;
            }
        });
        new MapStyleCtrl({ position: 'topleft' }).addTo(recMap);
    }

    function clearRecRoutes() {
        recRouteLayers.forEach(l => recMap.removeLayer(l));
        recRouteLayers = [];
    }

    function loadRecorridosMap() {
        const empresaId = document.getElementById('recmap_empresa').value;
        if (!empresaId) return;

        if (!recMap) initRecMap();

        document.getElementById('recmap-loading').classList.remove('hidden');
        document.getElementById('recmap-empty').classList.add('hidden');

        const params = new URLSearchParams({ empresa_asociada_id: empresaId });
        const fechaDesde = document.getElementById('fecha_desde')?.value;
        const fechaHasta = document.getElementById('fecha_hasta')?.value;
        const patrullaId = document.getElementById('recmap_patrulla')?.value;
        if (fechaDesde) params.append('fecha_desde', fechaDesde);
        if (fechaHasta) params.append('fecha_hasta', fechaHasta);
        if (patrullaId) params.append('patrulla_id', patrullaId);

        fetch(`${urlRecorridosMap}?${params.toString()}`)
            .then(r => r.json())
            .then(data => {
                clearRecRoutes();
                const bounds = [];

                data.recorridos.forEach((rec, idx) => {
                    // Normalize waypoints: handle nested {points:[...]} or flat array
                    let waypoints = rec.waypoints;
                    if (waypoints && !Array.isArray(waypoints) && waypoints.points) {
                        waypoints = waypoints.points;
                    }
                    if (!waypoints || !Array.isArray(waypoints) || waypoints.length < 2) return;

                    const latlngs = waypoints.map(wp => [
                        parseFloat(wp.lat || wp.latitude),
                        parseFloat(wp.lng || wp.lon || wp.longitude)
                    ]);
                    bounds.push(...latlngs);

                    const color = getHeatColor(rec.intensidad);
                    const t = Math.max(0.15, rec.intensidad); // min intensity for visibility

                    // ── Heat corridor: multiple concentric glow layers ──
                    // Widths and opacities scale with intensity for a radiant aura
                    const glowLayers = [];
                    const auraSpecs = [
                        { weight: 44, opacity: 0.03 + t * 0.03 },
                        { weight: 34, opacity: 0.04 + t * 0.05 },
                        { weight: 26, opacity: 0.06 + t * 0.07 },
                        { weight: 18, opacity: 0.08 + t * 0.10 },
                        { weight: 12, opacity: 0.12 + t * 0.13 },
                    ];
                    auraSpecs.forEach(spec => {
                        const gl = L.polyline(latlngs, {
                            color: color, weight: spec.weight, opacity: spec.opacity,
                            smoothFactor: 1.5, lineCap: 'round', lineJoin: 'round', interactive: false
                        }).addTo(recMap);
                        glowLayers.push(gl);
                        recRouteLayers.push(gl);
                    });

                    // Core line (interactive)
                    const polyline = L.polyline(latlngs, {
                        color: color, weight: 4, opacity: 0.95,
                        smoothFactor: 1.5, lineCap: 'round', lineJoin: 'round'
                    }).addTo(recMap);

                    // Bright inner stroke for definition
                    const innerGlow = L.polyline(latlngs, {
                        color: '#ffffff', weight: 1.5, opacity: 0.15 + t * 0.2,
                        smoothFactor: 1.5, lineCap: 'round', lineJoin: 'round', interactive: false
                    }).addTo(recMap);

                    // Hover tooltip: invite to click
                    polyline.bindTooltip(`<b>${rec.nombre}</b><br><span style="opacity:0.7">Click para ver detalles</span>`, {
                        className: 'recmap-tooltip', sticky: true, direction: 'top', offset: [0, -10]
                    });

                    // Highlight on hover: expand the aura
                    polyline.on('mouseover', function() {
                        this.setStyle({ weight: 6, opacity: 1 });
                        innerGlow.setStyle({ opacity: 0.4 });
                        glowLayers.forEach((gl, i) => {
                            gl.setStyle({ weight: auraSpecs[i].weight + 8, opacity: auraSpecs[i].opacity + 0.06 });
                        });
                    });
                    polyline.on('mouseout', function() {
                        this.setStyle({ weight: 4, opacity: 0.95 });
                        innerGlow.setStyle({ opacity: 0.15 + t * 0.2 });
                        glowLayers.forEach((gl, i) => {
                            gl.setStyle({ weight: auraSpecs[i].weight, opacity: auraSpecs[i].opacity });
                        });
                    });

                    // Popup on click with full details
                    let patBreakdown = '';
                    if (rec.frecuencia_patrulla && Object.keys(rec.frecuencia_patrulla).length > 0) {
                        patBreakdown = '<div class="mt-2 border-t border-gray-600 pt-2"><span class="text-[10px] uppercase tracking-wider text-gray-500">Por patrulla:</span>';
                        for (const [pat, cnt] of Object.entries(rec.frecuencia_patrulla)) {
                            patBreakdown += `<div class="flex justify-between text-xs mt-0.5"><span class="text-gray-400">${pat}</span><span class="text-white font-medium">${cnt}</span></div>`;
                        }
                        patBreakdown += '</div>';
                    }

                    polyline.bindPopup(`
                        <div class="p-3 min-w-[220px]">
                            <div class="font-bold text-sm text-white mb-1">${rec.nombre}</div>
                            ${rec.descripcion ? `<div class="text-xs text-gray-400 mb-2">${rec.descripcion}</div>` : ''}
                            <div class="space-y-1 text-xs">
                                <div class="flex justify-between"><span class="text-gray-400">Frecuencia</span><span class="font-bold" style="color:${color}">${rec.frecuencia} veces</span></div>
                                ${rec.longitud_km ? `<div class="flex justify-between"><span class="text-gray-400">Longitud</span><span class="text-white">${rec.longitud_km} km</span></div>` : ''}
                                ${rec.velocidadmax ? `<div class="flex justify-between"><span class="text-gray-400">Vel. Máx</span><span class="text-white">${rec.velocidadmax} km/h</span></div>` : ''}
                                ${rec.duracion_promedio ? `<div class="flex justify-between"><span class="text-gray-400">Dur. Prom.</span><span class="text-white">${rec.duracion_promedio} min</span></div>` : ''}
                            </div>
                            ${patBreakdown}
                        </div>`, { className: 'recorrido-popup', maxWidth: 300 });

                    recRouteLayers.push(polyline, innerGlow);
                });

                if (bounds.length > 0) {
                    recMap.fitBounds(bounds, { padding: [40, 40] });
                } else {
                    document.getElementById('recmap-empty').classList.remove('hidden');
                }
                renderRecLegend(data.legend, data.maxFreq);
                document.getElementById('recmap-loading').classList.add('hidden');
            })
            .catch(err => {
                console.error('Error loading recorridos map:', err);
                document.getElementById('recmap-loading').classList.add('hidden');
            });
    }

    function renderRecLegend(legend, maxFreq) {
        const wrapper = document.getElementById('recmap-legend');
        // Show the gradient legend bar whenever there's data
        if (maxFreq > 0) {
            wrapper.classList.remove('hidden');
        } else {
            wrapper.classList.add('hidden');
        }
    }

    // Init map lazily when container is visible
    document.addEventListener('DOMContentLoaded', function() {
        initRecMap();

        document.getElementById('btn_cargar_recmap').addEventListener('click', loadRecorridosMap);
        document.getElementById('recmap_empresa').addEventListener('change', function() {
            if (this.value) loadRecorridosMap();
        });

        // React to main dashboard date filters
        const fdEl = document.getElementById('fecha_desde');
        const fhEl = document.getElementById('fecha_hasta');
        if (fdEl) fdEl.addEventListener('change', function() { loadTendenciaChart(); loadTopChart(); if (document.getElementById('recmap_empresa').value) loadRecorridosMap(); });
        if (fhEl) fhEl.addEventListener('change', function() { loadTendenciaChart(); loadTopChart(); if (document.getElementById('recmap_empresa').value) loadRecorridosMap(); });

        // Load charts on page load
        loadTendenciaChart();
        loadTopChart();
        loadIndicadoresRecorridos();
    });

    // ========== TENDENCIA DÍA/HORA ==========
    let chartTendencia = null;

    function loadTendenciaChart() {
        const params = new URLSearchParams();
        const fd = document.getElementById('fecha_desde')?.value;
        const fh = document.getElementById('fecha_hasta')?.value;
        if (fd) params.append('fecha_desde', fd);
        if (fh) params.append('fecha_hasta', fh);

        fetch(`${urlTendencia}?${params.toString()}`)
            .then(r => r.json())
            .then(data => {
                const days = data.days;
                const matrix = data.matrix;
                const hours = Array.from({length: 24}, (_, i) => `${String(i).padStart(2,'0')}:00`);

                const dayColors = [
                    'rgba(59,130,246,0.9)','rgba(99,102,241,0.9)','rgba(168,85,247,0.9)',
                    'rgba(236,72,153,0.9)','rgba(249,115,22,0.9)','rgba(234,179,8,0.9)','rgba(34,197,94,0.9)',
                ];

                const datasets = days.map((day, di) => ({
                    label: day,
                    data: matrix[di],
                    borderColor: dayColors[di],
                    backgroundColor: dayColors[di].replace('0.9', '0.15'),
                    borderWidth: 2, tension: 0.4, fill: false,
                    pointRadius: 3, pointHoverRadius: 6,
                }));

                if (chartTendencia) chartTendencia.destroy();
                chartTendencia = new Chart(document.getElementById('chartTendenciaRecorridos').getContext('2d'), {
                    type: 'line',
                    data: { labels: hours, datasets },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: { display: true, position: 'bottom', labels: { color: '#9ca3af', padding: 12, font: { size: 11 }, usePointStyle: true, pointStyle: 'circle' } },
                            tooltip: { backgroundColor: 'rgba(17,24,39,0.95)', titleColor: '#fff', bodyColor: '#9ca3af', padding: 12, cornerRadius: 8, mode: 'index', intersect: false }
                        },
                        scales: {
                            x: { grid: { color: 'rgba(75,85,99,0.2)', drawBorder: false }, ticks: { color: '#6b7280', font: { size: 10 }, maxRotation: 45 } },
                            y: { beginAtZero: true, grid: { color: 'rgba(75,85,99,0.2)', drawBorder: false }, ticks: { color: '#6b7280', font: { size: 10 }, stepSize: 1 } }
                        },
                        interaction: { mode: 'index', intersect: false },
                        animation: { duration: 800 }
                    }
                });
            });
    }

    // ========== TOP RECORRIDOS ==========
    let chartTop = null;

    function loadTopChart() {
        const params = new URLSearchParams();
        const fd = document.getElementById('fecha_desde')?.value;
        const fh = document.getElementById('fecha_hasta')?.value;
        if (fd) params.append('fecha_desde', fd);
        if (fh) params.append('fecha_hasta', fh);

        fetch(`${urlTopRecorridos}?${params.toString()}`)
            .then(r => r.json())
            .then(data => {
                const top5 = data.top5 || [];
                const bottom5 = data.bottom5 || [];
                const labels = [], values = [], colors = [];

                top5.forEach(item => {
                    labels.push(item.nombre + ' (' + item.empresa + ')');
                    values.push(item.freq);
                    colors.push('rgba(34,197,94,0.85)');
                });

                if (bottom5.length > 0) {
                    labels.push('── Menos realizados ──'); values.push(null); colors.push('transparent');
                    bottom5.forEach(item => {
                        labels.push(item.nombre + ' (' + item.empresa + ')');
                        values.push(item.freq);
                        colors.push('rgba(239,68,68,0.85)');
                    });
                }

                if (chartTop) chartTop.destroy();

                if (top5.length === 0) {
                    const canvas = document.getElementById('chartTopRecorridos');
                    const ctx = canvas.getContext('2d');
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.font = '14px sans-serif';
                    ctx.fillStyle = '#6b7280';
                    ctx.textAlign = 'center';
                    ctx.fillText('No hay datos de recorridos para el periodo', canvas.width / 2, canvas.height / 2);
                    return;
                }

                chartTop = new Chart(document.getElementById('chartTopRecorridos').getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels, datasets: [{
                            data: values, backgroundColor: colors,
                            borderColor: colors.map(c => c.replace('0.85', '1')),
                            borderWidth: 1, borderRadius: 6, borderSkipped: false,
                        }]
                    },
                    options: {
                        indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(17,24,39,0.95)', titleColor: '#fff', bodyColor: '#9ca3af',
                                padding: 12, cornerRadius: 8,
                                filter: ctx => ctx.raw !== null,
                                callbacks: { label: ctx => `${ctx.raw} recorridos realizados` }
                            }
                        },
                        scales: {
                            x: { beginAtZero: true, grid: { color: 'rgba(75,85,99,0.2)', drawBorder: false }, ticks: { color: '#6b7280', font: { size: 10 }, stepSize: 1 } },
                            y: {
                                grid: { display: false },
                                ticks: {
                                    color: function(ctx) {
                                        const label = labels[ctx.index] || '';
                                        if (label.includes('──')) return '#6b7280';
                                        return colors[ctx.index] === 'rgba(239,68,68,0.85)' ? '#f87171' : '#9ca3af';
                                    },
                                    font: function(ctx) {
                                        const label = labels[ctx.index] || '';
                                        return { size: label.includes('──') ? 9 : 10, style: label.includes('──') ? 'italic' : 'normal' };
                                    },
                                    crossAlign: 'far'
                                }
                            }
                        },
                        animation: { duration: 800 }
                    }
                });
            });
    }

    // ========== INDICADORES (collapsible inside map card) ==========
    // Toggle visibility
    document.getElementById('toggle-indicadores-rec')?.addEventListener('click', function() {
        const panel = document.getElementById('indicadores-recorridos-panel');
        const chevron = document.getElementById('indicadores-chevron');
        const isHidden = panel.classList.toggle('hidden');
        if (isHidden) {
            chevron.classList.add('-rotate-90');
        } else {
            chevron.classList.remove('-rotate-90');
        }
    });

    function loadIndicadoresRecorridos() {
        fetch(urlIndicadores)
            .then(r => r.json())
            .then(data => {
                const container = document.getElementById('indicadores-recorridos-container');
                const countBadge = document.getElementById('indicadores-count');
                const indicadores = data.indicadores || [];

                if (indicadores.length === 0) {
                    container.innerHTML = '<div class="text-center py-4 text-gray-500 text-sm"><p>No hay indicadores de recorridos disponibles para este mes</p></div>';
                    countBadge.classList.add('hidden');
                    return;
                }

                // Show count badge
                countBadge.textContent = indicadores.length;
                countBadge.classList.remove('hidden');

                const iconMap = {
                    trophy: '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>',
                    alert: '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
                    car: '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>',
                    speed: '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
                };
                const dotColor = {
                    success: 'text-emerald-400',
                    warning: 'text-amber-400',
                    info: 'text-blue-400',
                    danger: 'text-red-400',
                };

                container.innerHTML = indicadores.map(ind => {
                    const dc = dotColor[ind.tipo] || dotColor.info;
                    const icon = iconMap[ind.icon] || iconMap.alert;
                    return `<div class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-zinc-700/40 transition-colors">
                        <span class="${dc}">${icon}</span>
                        <span class="text-sm text-gray-300 leading-snug">${ind.texto}</span>
                    </div>`;
                }).join('');
            })
            .catch(() => {
                document.getElementById('indicadores-recorridos-container').innerHTML = '<div class="text-center py-4 text-gray-500 text-sm">Error al cargar indicadores</div>';
            });
    }
})();
</script>

@endpush
@endsection
