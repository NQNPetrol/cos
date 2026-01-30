import { createApp } from 'vue';
import EventosBarras from './components/EventosBarras.vue';
import ClientDashboard from './components/Dashboard/ClientDashboard.vue';
import BarChart from './components/Dashboard/BarChart.vue';
import HeatmapChart from './components/Dashboard/HeatmapChart.vue';
import OperacionesDashboard from './operaciones-dashboard.js';

console.log('app.js cargando...');
console.log('Leaflet disponible?', typeof L !== 'undefined' ? 'Sí' : 'No');

// NOTA: Alpine.js es incluido automáticamente por Livewire 3
// No importar manualmente para evitar conflictos con @livewireScripts

// Esperar a que el DOM esté listo para Vue
document.addEventListener('DOMContentLoaded', () => {
    // App principal Vue
    const appElement = document.getElementById('app');
    if (appElement) {
        const app = createApp({});
        app.component('eventos-barras', EventosBarras);
        app.component('client-dashboard', ClientDashboard);
        app.component('bar-chart', BarChart);
        app.mount('#app');
    }

    // App para el dashboard del cliente
    const dashboardElement = document.getElementById('client-dashboard-app');
    if (dashboardElement) {
        console.log('#client-dashboard-app encontrado');
        // Obtener props del elemento
        const props = {
            totalEventos: parseInt(dashboardElement.dataset.totalEventos || '0'),
            totalClientes: parseInt(dashboardElement.dataset.totalClientes || '0'),
            eventosSinCliente: parseInt(dashboardElement.dataset.eventosSinCliente || '0'),
            urlEventosPorEmpresa: dashboardElement.dataset.urlEventosPorEmpresa || '',
            urlEventosPorCategoria: dashboardElement.dataset.urlEventosPorCategoria || '',
            urlEventosMapaCalor: dashboardElement.dataset.urlEventosMapaCalor || ''
        };

        console.log('Props para dashboard:', props);
        
        const dashboardApp = createApp(ClientDashboard, props);
        dashboardApp.component('bar-chart', BarChart);
        dashboardApp.component('heatmap-chart', HeatmapChart);
        dashboardApp.mount('#client-dashboard-app');
        console.log('Dashboard montado');
    }

    // Inicializar dashboard operacional si existe el elemento del mapa
    const operacionesMapElement = document.getElementById('operaciones-map');
    if (operacionesMapElement) {
        console.log('[app.js] Detectado dashboard operacional, inicializando...');
        // El módulo se inicializará cuando Google Maps esté listo vía callback
        // Solo pre-cargamos el módulo aquí
        OperacionesDashboard.init().catch(err => {
            console.error('[app.js] Error inicializando dashboard operacional:', err);
        });
    }
   
});
