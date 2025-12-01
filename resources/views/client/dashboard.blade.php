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
    <div class="border-b border-gray-600 pb-2 mt-8">
        <h2 class="text-lg font-semibold text-red-400 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Mapa de Calor de Eventos
        </h2>
    </div>

    <!-- Mapa de calor en pantalla completa -->
    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 shadow-xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-white">Concentración Geográfica de Eventos</h2>
                <p class="text-gray-400 text-sm mt-1">Mapa de calor que muestra zonas con mayor frecuencia de eventos</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-600/20 text-red-400">
                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                Intensidad
            </span>
        </div>
        
        <!-- Componente Vue del mapa de calor -->
        <div id="heatmap-container">
            <heatmap-chart 
                ref="heatmapChart"
                api-url="{{ route('client.dashboard.eventos-mapa-calor') }}"
                :fecha-desde="filtroFechaDesde"
                :fecha-hasta="filtroFechaHasta"
                height="450px"
                :radius="30"
                :blur="20"
                :max-intensity="1.2"
            />
        </div>
        
        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-800/50 p-4 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Zonas de Alta Frecuencia</p>
                        <p class="text-lg font-semibold text-white">Áreas en rojo</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-800/50 p-4 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-yellow-600/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Zonas de Media Frecuencia</p>
                        <p class="text-lg font-semibold text-white">Áreas en amarillo</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-800/50 p-4 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Zonas de Baja Frecuencia</p>
                        <p class="text-lg font-semibold text-white">Áreas en azul</p>
                    </div>
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
<!-- Prueba de diagnóstico -->
<div id="vue-test" style="display: none;">
    <div v-if="true" style="background: red; color: white; padding: 10px;">
        Vue está funcionando
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DIAGNÓSTICO VUE ===');
    
    // Verificar si Vue está disponible de diferentes formas
    console.log('window.Vue:', typeof window.Vue !== 'undefined' ? 'Disponible globalmente' : ' No global');
    
    // Verificar si el componente heatmap-chart está en DOM
    setTimeout(() => {
        const heatmapElement = document.querySelector('heatmap-chart');
        if (heatmapElement) {
            console.log('Componente heatmap-chart en DOM');
            console.log('Atributos:', {
                apiUrl: heatmapElement.getAttribute('api-url'),
                height: heatmapElement.getAttribute('height')
            });
        } else {
            console.error('Componente heatmap-chart NO en DOM');
        }
    }, 1000);
    
    // Verificar si Leaflet está disponible globalmente
    console.log('window.L (Leaflet):', typeof window.L !== 'undefined' ? 'si ' + window.L.version : 'No global');
    
    // Probar API del mapa
    const apiUrl = "{{ route('client.dashboard.eventos-mapa-calor') }}";
    console.log('URL API:', apiUrl);
    
    fetch(apiUrl)
        .then(response => {
            console.log('Estado API:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Datos API:', data);
            if (data.length === 0) {
                console.warn('API retornó array vacío - ¿Hay eventos con ubicación?');
            }
        })
        .catch(error => {
            console.error('Error API:', error);
        });
});
</script>

@push('scripts')
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
    // Diagnóstico del mapa
    console.log('=== DIAGNÓSTICO MAPA ===');
    console.log('window.L (Leaflet):', typeof window.L !== 'undefined' ? '✅ ' + window.L.version : '❌ No disponible');
    console.log('L.heatLayer:', typeof L !== 'undefined' && typeof L.heatLayer === 'function' ? '✅ Disponible' : '❌ No disponible');

    // Probar mapa directamente
    setTimeout(() => {
        const testContainer = document.getElementById('test-map');
        if (testContainer && typeof L !== 'undefined') {
            console.log('🧪 Probando Leaflet directamente...');
            const testMap = L.map('test-map').setView([-38.8827, -68.0447], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(testMap);
            L.marker([-38.8827, -68.0447])
                .addTo(testMap)
                .bindPopup('¡Leaflet funciona!')
                .openPopup();
            console.log('✅ Leaflet funciona correctamente');
        }
    }, 1000);
</script>
@endpush
@endsection
