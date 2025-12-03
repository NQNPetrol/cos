@extends('layouts.cliente')

@section('title', 'Dashboard - Área Cliente')

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

    <!-- ==================== SECCIÓN EVENTOS ==================== -->
    <div class="border-b border-gray-600 pb-2">
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

        <!-- Clientes -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 shadow-xl">
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

        <!-- Eventos sin cliente -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wide">Sin Cliente Asignado</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $eventosSinEmpresa ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-amber-600/20 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de gráficos de eventos - 2 columnas en pantallas lg -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gráfico de eventos por cliente -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 shadow-xl relative">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-white">Eventos por Cliente</h2>
                    <p class="text-gray-400 text-sm mt-1">Distribución de eventos de seguridad por cliente</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-600/20 text-blue-400">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                    Eventos registrados
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

    <!--MAPA DE CALOR -->

    <!-- Mapa de calor en pantalla completa -->
    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 shadow-xl">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div>
                <h2 class="text-xl font-bold text-white">Concentración Geográfica de Eventos</h2>
                <p class="text-gray-400 text-sm mt-1">Mapa de calor que muestra zonas con mayor frecuencia de eventos</p>
            </div>
            <div class="flex flex-col items-end gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-600/20 text-red-400">
                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                    Intensidad
                </span>
                <!-- Leyenda de colores -->
                <div class="flex flex-col items-end gap-1">
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-400 font-medium">Baja</span>
                        <div class="w-48 h-4 rounded-md overflow-hidden border border-gray-600 shadow-inner" 
                             style="background: linear-gradient(to right, #2563eb 0%, #06b6d4 30%, #84cc16 60%, #eab308 80%, #ef4444 100%);">
                        </div>
                        <span class="text-xs text-gray-400 font-medium">Alta</span>
                    </div>
                    <p class="text-xs text-gray-500 italic">Escala de intensidad de eventos</p>
                </div>
            </div>
        </div>
        
        <!-- Mapa de calor con Leaflet -->
        <div id="heatmap-container" style="height: 450px; border-radius: 12px; overflow: hidden; background: #1f2937;">
            <div id="heatmap-loading" class="flex items-center justify-center h-full">
                <div class="flex flex-col items-center gap-3">
                    <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-500 border-t-transparent"></div>
                    <span class="text-gray-300 text-sm">Cargando mapa de calor...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== SECCIÓN PATRULLAS ==================== -->
    <div class="border-b border-gray-600 pb-2 mt-8">
        <h2 class="text-lg font-semibold text-cyan-400 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            Estadísticas de Patrullas
        </h2>
    </div>

    <!-- Tarjetas de resumen de patrullas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Patrullas -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 shadow-xl">
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

        <!-- Patrullas con GPS -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 shadow-xl">
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

        <!-- Patrullas sin GPS -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 shadow-xl">
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

    <!-- Grid de gráficos de patrullas - 2 columnas en pantallas lg -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gráfico de patrullas por estado -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 shadow-xl relative">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-white">Patrullas por Estado</h2>
                    <p class="text-gray-400 text-sm mt-1">Distribución según estado operativo</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-cyan-600/20 text-cyan-400">
                    <span class="w-2 h-2 bg-cyan-500 rounded-full mr-2"></span>
                    Por estado
                </span>
            </div>
            
            <div class="relative" style="height: 300px;">
                <canvas id="chartPatrullasEstado"></canvas>
            </div>
        </div>

        <!-- Gráfico de patrullas por GPS -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 shadow-xl relative">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-white">Cobertura GPS</h2>
                    <p class="text-gray-400 text-sm mt-1">Patrullas con y sin seguimiento GPS</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-600/20 text-green-400">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                    GPS
                </span>
            </div>
            
            <div class="relative" style="height: 300px;">
                <canvas id="chartPatrullasGPS"></canvas>
            </div>
        </div>
    </div>

    <!-- ==================== SECCIÓN DOCUMENTOS DE PATRULLAS ==================== -->
    <div class="border-b border-gray-600 pb-2 mt-8">
        <h2 class="text-lg font-semibold text-orange-400 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Documentación de Patrullas
        </h2>
    </div>

    <!-- Tarjetas de resumen de documentos -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Documentos -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 shadow-xl">
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

        <!-- Documentos Vencidos -->
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

        <!-- Por vencer en 7 días -->
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

        <!-- Documentos Vigentes -->
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

    <!-- Grid: Gráfico + Tabla de documentos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gráfico de estado de documentos -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 shadow-xl relative">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-white">Estado de Documentos</h2>
                    <p class="text-gray-400 text-sm mt-1">Distribución por estado de vencimiento</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-600/20 text-orange-400">
                    <span class="w-2 h-2 bg-orange-500 rounded-full mr-2"></span>
                    Vencimientos
                </span>
            </div>
            
            <div class="relative" style="height: 300px;">
                <canvas id="chartDocumentos"></canvas>
            </div>
        </div>

        <!-- Tabla de documentos próximos a vencer -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-bold text-white">Alertas de Vencimiento</h2>
                    <p class="text-gray-400 text-sm mt-1">Documentos próximos a vencer o vencidos</p>
                </div>
            </div>
            
            <div class="overflow-x-auto max-h-72 overflow-y-auto">
                @if(isset($documentosAlerta) && count($documentosAlerta) > 0)
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-800/50 sticky top-0">
                        <tr>
                            <th scope="col" class="px-4 py-3">Documento</th>
                            <th scope="col" class="px-4 py-3">Patrulla</th>
                            <th scope="col" class="px-4 py-3 text-center">Vencimiento</th>
                            <th scope="col" class="px-4 py-3 text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($documentosAlerta as $doc)
                        <tr class="hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-3 font-medium text-white">{{ $doc['nombre'] }}</td>
                            <td class="px-4 py-3 text-gray-300">{{ $doc['patrulla'] }}</td>
                            <td class="px-4 py-3 text-center text-gray-300">{{ $doc['fecha_vto'] }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($doc['estado'] === 'vencido')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-600/20 text-red-400">
                                        Vencido ({{ abs($doc['dias_restantes']) }} días)
                                    </span>
                                @elseif($doc['estado'] === 'critico')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-600/20 text-amber-400">
                                        {{ $doc['dias_restantes'] }} días
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-600/20 text-yellow-400">
                                        {{ $doc['dias_restantes'] }} días
                                    </span>
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
<!-- Leaflet CSS y JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos iniciales - Eventos
    const initialDataClientes = @json($chartData ?? []);
    const initialDataCategorias = @json($chartDataCategorias ?? []);
    
    // Datos iniciales - Patrullas
    const initialDataPatrullasEstado = @json($chartDataPatrullasEstado ?? []);
    const initialDataPatrullasGPS = @json($chartDataPatrullasGPS ?? []);
    
    // Datos iniciales - Documentos
    const initialDataDocumentos = @json($chartDataDocumentos ?? []);
    
    // URLs de API
    const urlClientes = "{{ route('client.dashboard.eventos-por-empresa') }}";
    const urlCategorias = "{{ route('client.dashboard.eventos-por-categoria') }}";
    
    // Paletas de colores
    const colorsClientes = {
        bg: ['rgba(59, 130, 246, 0.8)', 'rgba(16, 185, 129, 0.8)', 'rgba(245, 158, 11, 0.8)',
             'rgba(239, 68, 68, 0.8)', 'rgba(139, 92, 246, 0.8)', 'rgba(236, 72, 153, 0.8)',
             'rgba(6, 182, 212, 0.8)', 'rgba(249, 115, 22, 0.8)', 'rgba(34, 197, 94, 0.8)',
             'rgba(168, 85, 247, 0.8)'],
        border: ['rgba(59, 130, 246, 1)', 'rgba(16, 185, 129, 1)', 'rgba(245, 158, 11, 1)',
                 'rgba(239, 68, 68, 1)', 'rgba(139, 92, 246, 1)', 'rgba(236, 72, 153, 1)',
                 'rgba(6, 182, 212, 1)', 'rgba(249, 115, 22, 1)', 'rgba(34, 197, 94, 1)',
                 'rgba(168, 85, 247, 1)']
    };
    
    const colorsCategorias = {
        bg: ['rgba(139, 92, 246, 0.8)', 'rgba(236, 72, 153, 0.8)', 'rgba(168, 85, 247, 0.8)',
             'rgba(244, 114, 182, 0.8)', 'rgba(192, 132, 252, 0.8)', 'rgba(232, 121, 249, 0.8)',
             'rgba(167, 139, 250, 0.8)', 'rgba(251, 146, 60, 0.8)', 'rgba(74, 222, 128, 0.8)',
             'rgba(56, 189, 248, 0.8)'],
        border: ['rgba(139, 92, 246, 1)', 'rgba(236, 72, 153, 1)', 'rgba(168, 85, 247, 1)',
                 'rgba(244, 114, 182, 1)', 'rgba(192, 132, 252, 1)', 'rgba(232, 121, 249, 1)',
                 'rgba(167, 139, 250, 1)', 'rgba(251, 146, 60, 1)', 'rgba(74, 222, 128, 1)',
                 'rgba(56, 189, 248, 1)']
    };

    const colorsPatrullas = {
        bg: ['rgba(6, 182, 212, 0.8)', 'rgba(34, 197, 94, 0.8)', 'rgba(245, 158, 11, 0.8)',
             'rgba(239, 68, 68, 0.8)', 'rgba(99, 102, 241, 0.8)'],
        border: ['rgba(6, 182, 212, 1)', 'rgba(34, 197, 94, 1)', 'rgba(245, 158, 11, 1)',
                 'rgba(239, 68, 68, 1)', 'rgba(99, 102, 241, 1)']
    };

    const colorsGPS = {
        bg: ['rgba(34, 197, 94, 0.8)', 'rgba(239, 68, 68, 0.8)'],
        border: ['rgba(34, 197, 94, 1)', 'rgba(239, 68, 68, 1)']
    };

    // Colores para documentos (rojo=vencido, ámbar=7días, amarillo=30días, verde=vigente)
    const colorsDocumentos = {
        bg: ['rgba(239, 68, 68, 0.8)', 'rgba(245, 158, 11, 0.8)', 'rgba(234, 179, 8, 0.8)', 'rgba(34, 197, 94, 0.8)'],
        border: ['rgba(239, 68, 68, 1)', 'rgba(245, 158, 11, 1)', 'rgba(234, 179, 8, 1)', 'rgba(34, 197, 94, 1)']
    };

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

    // ========== GRÁFICOS DE EVENTOS ==========
    
    // Gráfico de eventos por cliente
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
        options: getBarChartOptions()
    });

    // Gráfico de eventos por categoría
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
        options: getBarChartOptions()
    });

    // ========== GRÁFICOS DE PATRULLAS ==========
    
    // Gráfico de patrullas por estado
    const ctxPatrullasEstado = document.getElementById('chartPatrullasEstado').getContext('2d');
    new Chart(ctxPatrullasEstado, {
        type: 'doughnut',
        data: {
            labels: initialDataPatrullasEstado.map(i => i.nombre),
            datasets: [{
                data: initialDataPatrullasEstado.map(i => i.total),
                backgroundColor: initialDataPatrullasEstado.map((_, i) => colorsPatrullas.bg[i % colorsPatrullas.bg.length]),
                borderColor: initialDataPatrullasEstado.map((_, i) => colorsPatrullas.border[i % colorsPatrullas.border.length]),
                borderWidth: 2
            }]
        },
        options: getDoughnutOptions()
    });

    // Gráfico de patrullas GPS
    const ctxPatrullasGPS = document.getElementById('chartPatrullasGPS').getContext('2d');
    new Chart(ctxPatrullasGPS, {
        type: 'doughnut',
        data: {
            labels: initialDataPatrullasGPS.map(i => i.nombre),
            datasets: [{
                data: initialDataPatrullasGPS.map(i => i.total),
                backgroundColor: colorsGPS.bg,
                borderColor: colorsGPS.border,
                borderWidth: 2
            }]
        },
        options: getDoughnutOptions()
    });

    // ========== GRÁFICO DE DOCUMENTOS ==========
    
    const ctxDocumentos = document.getElementById('chartDocumentos').getContext('2d');
    new Chart(ctxDocumentos, {
        type: 'doughnut',
        data: {
            labels: initialDataDocumentos.map(i => i.nombre),
            datasets: [{
                data: initialDataDocumentos.map(i => i.total),
                backgroundColor: initialDataDocumentos.map((_, i) => colorsDocumentos.bg[i % colorsDocumentos.bg.length]),
                borderColor: initialDataDocumentos.map((_, i) => colorsDocumentos.border[i % colorsDocumentos.border.length]),
                borderWidth: 2
            }]
        },
        options: getDoughnutOptions()
    });

    // ========== FILTROS DE EVENTOS ==========
    
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
    // ========== MAPA DE CALOR ==========
    
    function initHeatmap() {
        const container = document.getElementById('heatmap-container');
        const loading = document.getElementById('heatmap-loading');
        const apiUrl = "{{ route('client.dashboard.eventos-mapa-calor') }}";
        
        // Verificar que Leaflet esté cargado
        if (typeof L === 'undefined') {
            console.error('Leaflet no está disponible');
            if (loading) {
                loading.innerHTML = '<div class="text-center text-red-400"><p>Error: No se pudo cargar la biblioteca de mapas</p></div>';
            }
            return;
        }
        
        // Obtener datos de la API
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                // Ocultar loading
                if (loading) loading.style.display = 'none';
                
                // Crear el mapa
                const map = L.map('heatmap-container', {
                    center: [-38.9516, -68.0591], // Centro en Neuquén por defecto
                    zoom: 10,
                    zoomControl: true
                });
                
                // Agregar capa de tiles (estilo Voyager con colores)
                L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                    subdomains: 'abcd',
                    maxZoom: 19
                }).addTo(map);
                
                if (data && data.length > 0) {
                    // Calcular intensidad máxima para normalizar
                    const maxCount = Math.max(...data.map(p => p.count || 1));
                    
                    // Preparar datos para el heatmap [lat, lng, intensity]
                    const heatData = data.map(point => [
                        point.lat,
                        point.lng,
                        (point.count || 1) / maxCount // Normalizar intensidad
                    ]);
                    
                    // Crear capa de calor con colores más intensos
                    const heat = L.heatLayer(heatData, {
                        radius: 30,
                        blur: 20,
                        maxZoom: 17,
                        max: 1.0,
                        minOpacity: 0.4,
                        gradient: {
                            0.0: '#3b82f6',  // Azul brillante
                            0.25: '#06b6d4', // Cyan
                            0.5: '#22c55e',  // Verde
                            0.75: '#f59e0b', // Naranja
                            1.0: '#dc2626'   // Rojo intenso
                        }
                    }).addTo(map);
                    
                    // Ajustar vista a los puntos
                    const bounds = L.latLngBounds(heatData.map(p => [p[0], p[1]]));
                    map.fitBounds(bounds, { padding: [50, 50] });
                    
                    // Agregar marcadores para cada ubicación con eventos
                    data.forEach(point => {
                        if (point.lat && point.lng) {
                            const count = point.count || 1;
                            const radius = Math.min(6 + count * 2, 18); // Radio según cantidad
                            
                            // Color según cantidad de eventos
                            let fillColor = '#3b82f6'; // Azul por defecto
                            if (count >= 5) fillColor = '#dc2626'; // Rojo para muchos
                            else if (count >= 3) fillColor = '#f59e0b'; // Naranja para varios
                            else if (count >= 2) fillColor = '#22c55e'; // Verde para pocos
                            
                            const marker = L.circleMarker([point.lat, point.lng], {
                                radius: radius,
                                fillColor: fillColor,
                                color: '#ffffff',
                                weight: 2,
                                opacity: 1,
                                fillOpacity: 0.85
                            }).addTo(map);
                            
                            marker.bindPopup(`
                                <div style="padding: 10px; min-width: 140px; text-align: center;">
                                    <div style="font-size: 24px; font-weight: bold; color: ${fillColor};">${count}</div>
                                    <div style="color: #374151; font-size: 13px;">evento${count > 1 ? 's' : ''} registrado${count > 1 ? 's' : ''}</div>
                                    <div style="color: #9ca3af; font-size: 11px; margin-top: 4px;">en esta ubicación</div>
                                </div>
                            `);
                        }
                    });
                    
                    console.log(`Mapa de calor cargado con ${data.length} eventos`);
                } else {
                    // Mostrar mensaje si no hay datos
                    const infoDiv = document.createElement('div');
                    infoDiv.className = 'absolute inset-0 flex items-center justify-center bg-gray-800/80 z-[1000]';
                    infoDiv.innerHTML = `
                        <div class="text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            <p>No hay eventos con ubicación geográfica</p>
                        </div>
                    `;
                    container.style.position = 'relative';
                    container.appendChild(infoDiv);
                }
            })
            .catch(error => {
                console.error('Error cargando datos del mapa:', error);
                if (loading) {
                    loading.innerHTML = '<div class="text-center text-red-400"><p>Error al cargar datos del mapa</p></div>';
                }
            });
    }
    
    // Inicializar mapa cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHeatmap);
    } else {
        initHeatmap();
    }
</script>
@endpush
@endsection
