<template>
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
                    <input type="date" id="fecha_desde" v-model="fechaDesde"
                           class="bg-gray-800 border border-gray-600 text-white text-sm rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-center gap-2">
                    <label for="fecha_hasta" class="text-sm text-gray-300">Hasta:</label>
                    <input type="date" id="fecha_hasta" v-model="fechaHasta"
                           class="bg-gray-800 border border-gray-600 text-white text-sm rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button @click="filtrar" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filtrar
                </button>
                <button @click="limpiarFiltros" 
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
                        <p class="text-3xl font-bold text-white mt-2">{{ totalEventos }}</p>
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
                        <p class="text-3xl font-bold text-white mt-2">{{ totalClientes }}</p>
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
                        <p class="text-3xl font-bold text-white mt-2">{{ eventosSinCliente }}</p>
                    </div>
                    <div class="w-14 h-14 bg-amber-600/20 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid de gráficos -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Gráfico de eventos por cliente -->
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 shadow-xl">
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
                
                <BarChart 
                    ref="chartClientes"
                    :api-url="urlEventosPorEmpresa"
                    :fecha-desde="filtroFechaDesde"
                    :fecha-hasta="filtroFechaHasta"
                    color-scheme="blue"
                />
            </div>

            <!-- Gráfico de eventos por categoría -->
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 shadow-xl">
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
                
                <BarChart 
                    ref="chartCategorias"
                    :api-url="urlEventosPorCategoria"
                    :fecha-desde="filtroFechaDesde"
                    :fecha-hasta="filtroFechaHasta"
                    color-scheme="violet"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import BarChart from './BarChart.vue';

const props = defineProps({
    totalEventos: {
        type: Number,
        default: 0
    },
    totalClientes: {
        type: Number,
        default: 0
    },
    eventosSinCliente: {
        type: Number,
        default: 0
    },
    urlEventosPorEmpresa: {
        type: String,
        required: true
    },
    urlEventosPorCategoria: {
        type: String,
        required: true
    }
});

const fechaDesde = ref('');
const fechaHasta = ref('');
const filtroFechaDesde = ref('');
const filtroFechaHasta = ref('');

const chartClientes = ref(null);
const chartCategorias = ref(null);

const filtrar = () => {
    filtroFechaDesde.value = fechaDesde.value;
    filtroFechaHasta.value = fechaHasta.value;
};

const limpiarFiltros = () => {
    fechaDesde.value = '';
    fechaHasta.value = '';
    filtroFechaDesde.value = '';
    filtroFechaHasta.value = '';
};
</script>





