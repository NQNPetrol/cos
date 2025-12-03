import { createApp } from 'vue';
import EventosBarras from './Components/EventosBarras.vue';
import ClientDashboard from './Components/Dashboard/ClientDashboard.vue';
import BarChart from './Components/Dashboard/BarChart.vue';

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
        // Obtener props del elemento
        const props = {
            totalEventos: parseInt(dashboardElement.dataset.totalEventos || '0'),
            totalClientes: parseInt(dashboardElement.dataset.totalClientes || '0'),
            eventosSinCliente: parseInt(dashboardElement.dataset.eventosSinCliente || '0'),
            urlEventosPorEmpresa: dashboardElement.dataset.urlEventosPorEmpresa || '',
            urlEventosPorCategoria: dashboardElement.dataset.urlEventosPorCategoria || ''
        };
        
        const dashboardApp = createApp(ClientDashboard, props);
        dashboardApp.component('bar-chart', BarChart);
        dashboardApp.mount('#client-dashboard-app');
    }
});
