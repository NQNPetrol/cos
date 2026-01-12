@extends('layouts.app')

@section('title', 'Dashboard Operacional')

@push('styles')
<style>
    /* Estilos específicos del dashboard se agregarán en modern-ui.css */
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
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initOperacionesMap" async defer></script>
@vite(['resources/js/operaciones-dashboard.js'])
<script>
    // Variable global para el callback de Google Maps
    window.initOperacionesMap = function() {
        if (window.operacionesDashboard) {
            window.operacionesDashboard.setup();
        }
    };
</script>
@endpush

