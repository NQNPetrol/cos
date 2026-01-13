@extends('layouts.cliente')

@section('title', 'Dashboard Operacional')

@push('styles')
<style>
    /* Estilos específicos del dashboard se manejan en modern-ui.css */
</style>
@endpush

@section('content')
<div class="operaciones-dashboard">
    <!-- Botones de Control -->
    @include('operaciones.partials.controls')

    <!-- Main Grid: 6 columnas -->
    <div class="operaciones-main-grid">
        <!-- Fila 1: KPIs (5 columnas) + Botones (1 columna) -->
        @include('operaciones.partials.kpis', ['kpis' => $kpisIniciales ?? null])
        
        <!-- Botones de Acceso Rápido (1 columna, alineados hacia abajo) -->
        <div class="quick-access-container">
            @include('operaciones.partials.quick-access-sidebar')
        </div>
        
        <!-- Fila 2: Mapa (5 columnas) + Eventos (1 columna) -->
        @include('operaciones.partials.map-container')
        
        @include('operaciones.partials.eventos-list', ['eventos' => $eventosIniciales ?? null])
    </div>

    <!-- KPIs Secundarios: Vuelos y Triggers -->
    @include('operaciones.partials.kpis-secondary', ['kpis' => $kpisIniciales ?? null])
</div>
@endsection

@push('scripts')
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&loading=async&callback=initOperacionesMap"
    async
    defer>
</script>

<script>
    // Callback global para Google Maps
    window.initOperacionesMap = function () {
        console.log('[Google Maps] callback ejecutado');
        
        // Si ya existe una instancia, usar setup directamente
        if (window.operacionesDashboard) {
            console.log('[Google Maps] Usando instancia existente de dashboard');
            window.operacionesDashboard.setup();
        } 
        // Si no existe, crear nueva instancia (fallback)
        else if (window.operacionesDashboardInstance) {
            console.log('[Google Maps] Usando instancia alternativa');
            window.operacionesDashboardInstance.setup();
        }
        // Si el módulo aún no se ha cargado, esperar un momento
        else {
            console.warn('[Google Maps] Dashboard no inicializado aún, reintentando...');
            setTimeout(() => {
                if (window.operacionesDashboard) {
                    window.operacionesDashboard.setup();
                }
            }, 500);
        }
    };
</script>
@endpush


