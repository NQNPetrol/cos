@extends($isClient ? 'layouts.cliente' : 'layouts.app')

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

    <!-- KPIs Section -->
    @include('operaciones.partials.kpis', ['kpis' => $kpisIniciales ?? null])

    <!-- Main Grid: Mapa y Eventos -->
    <div class="operaciones-main-grid">
        <!-- Mapa Container -->
        @include('operaciones.partials.map-container')

        <!-- Eventos List -->
        @include('operaciones.partials.eventos-list', ['eventos' => $eventosIniciales ?? null])
    </div>

    <!-- Quick Access Buttons -->
    @include('operaciones.partials.quick-access')
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

