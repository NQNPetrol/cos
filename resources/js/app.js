import { createApp } from 'vue';
import EventosBarras from './Components/EventosBarras.vue';
import ClientDashboard from './Components/Dashboard/ClientDashboard.vue';
import BarChart from './Components/Dashboard/BarChart.vue';
import HeatmapChart from './Components/Dashboard/HeatmapChart.vue';

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
    // Mapa de calor en dashboard de cliente (montaje directo del componente)
    const heatmapContainer = document.getElementById('heatmap-container');
    if (heatmapContainer) {
        console.log('#heatmap-container encontrado para HeatmapChart');

        const heatmapProps = {
            apiUrl: heatmapContainer.dataset.apiUrl || '',
            height: heatmapContainer.dataset.height || '450px',
            fechaDesde: '',
            fechaHasta: ''
        };

        const heatmapApp = createApp(HeatmapChart, heatmapProps);
        heatmapApp.mount('#heatmap-container');
    }
});
