import { createApp } from 'vue';
import Alpine from 'alpinejs';
import EventosBarras from './Components/EventosBarras.vue';
import ClientDashboard from './Components/Dashboard/ClientDashboard.vue';
import BarChart from './Components/Dashboard/BarChart.vue';

// Inicializar Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Esperar a que el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    // App principal
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
