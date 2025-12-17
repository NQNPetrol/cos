<div class="operaciones-map-container" id="operaciones-map-container">
    <div id="operaciones-map"></div>

    <!-- Controles del Mapa -->
    <div class="map-controls">
        <!-- Botón Expandir Mapa -->
        <button class="dashboard-control-btn" id="expand-map-btn" title="Expandir">
            <i class="bi bi-arrows-fullscreen"></i>
        </button>
    </div>

    <!-- Filtros del Mapa -->
    <div class="map-filter-controls">
        <!-- Filtro de Tipo de Mapa -->
        <div class="map-type-control">
            <button class="map-filter-tab" id="map-type-satellite" data-type="satellite">Satelital</button>
            <button class="map-filter-tab" id="map-type-roadmap" data-type="roadmap">Detallado</button>
        </div>

        <!-- Filtro de Estado de Evento -->
        <div class="map-event-status-control">
            <button class="map-filter-tab" id="filter-estado-abierto" data-estado="ABIERTO">Abiertos</button>
            <button class="map-filter-tab" id="filter-estado-revision" data-estado="EN REVISION">En Revisión</button>
            <button class="map-filter-tab" id="filter-estado-cerrado" data-estado="CERRADO">Cerrados</button>
        </div>

        <!-- Filtro de Cliente (solo admin) -->
        @if(!$isClient)
        <div class="map-cliente-control">
            <select id="filter-cliente" class="map-cliente-select">
                <option value="">Todos los clientes</option>
                @foreach(\App\Models\Cliente::orderBy('nombre')->get() as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                @endforeach
            </select>
        </div>
        @endif
    </div>
</div>


