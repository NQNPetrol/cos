<div class="operaciones-kpis" id="operaciones-kpis" data-kpis-loaded="{{ isset($kpis) ? '1' : '0' }}">
    <!-- KPI 1: Cantidad de Cámaras -->
    <div class="operaciones-kpi-card">
        <div class="operaciones-kpi-icon">
            <i class="bi bi-camera-video-fill"></i>
        </div>
        <div class="operaciones-kpi-value" id="kpi-camaras-total">
            {{ isset($kpis) ? ($kpis['camaras_total'] ?? 0) : '-' }}
        </div>
        <div class="operaciones-kpi-label">Cámaras Activas</div>
    </div>

    <!-- KPI 2: Vehículos vs Equipados -->
    <div class="operaciones-kpi-card">
        <div class="operaciones-kpi-icon">
            <i class="bi bi-taxi-front-fill"></i>
        </div>
        <div class="operaciones-kpi-value" id="kpi-vehiculos">
            <span id="kpi-vehiculos-equipados">{{ isset($kpis) ? ($kpis['vehiculos_equipados'] ?? 0) : '-' }}</span> / 
            <span id="kpi-vehiculos-total">{{ isset($kpis) ? ($kpis['vehiculos_total'] ?? 0) : '-' }}</span>
        </div>
        <div class="operaciones-kpi-label">Vehículos Equipados</div>
    </div>

    <!-- KPI 3: Trending de Eventos -->
    <div class="operaciones-kpi-card">
        <div class="operaciones-kpi-icon">
            <i class="bi bi-graph-up-arrow"></i>
        </div>
        <div class="operaciones-kpi-value" id="kpi-eventos-trending" style="color: {{ isset($kpis) && isset($kpis['eventos_trending']) && $kpis['eventos_trending']['tendencia'] === 'aumento' ? '#10b981' : '#ef4444' }}">
            <span id="kpi-trending-porcentaje">{{ isset($kpis) && isset($kpis['eventos_trending']) ? number_format($kpis['eventos_trending']['porcentaje_cambio'], 1) : '-' }}</span>%
            <span id="kpi-trending-icon" class="trending-icon {{ isset($kpis) && isset($kpis['eventos_trending']) && $kpis['eventos_trending']['tendencia'] === 'aumento' ? 'up' : 'down' }}">
                {{ isset($kpis) && isset($kpis['eventos_trending']) && $kpis['eventos_trending']['tendencia'] === 'aumento' ? '↑' : '↓' }}
            </span>
        </div>
        <div class="operaciones-kpi-label">Eventos (30 días)</div>
    </div>

    <!-- KPI 4: Eventos Abiertos -->
    <div class="operaciones-kpi-card">
        <div class="operaciones-kpi-icon">
            <i class="bi bi-bell-fill"></i>
        </div>
        <div class="operaciones-kpi-value" id="kpi-eventos-abiertos">{{ isset($kpis) ? ($kpis['eventos_abiertos'] ?? 0) : '-' }}</div>
        <div class="operaciones-kpi-label">Eventos Abiertos</div>
    </div>

    <!-- KPI 5: Tiempo Promedio de Cierre -->
    <div class="operaciones-kpi-card">
        <div class="operaciones-kpi-icon">
            <i class="bi bi-clock-fill"></i>
        </div>
        <div class="operaciones-kpi-value" id="kpi-tiempo-cierre">
            <span id="kpi-tiempo-dias">{{ isset($kpis) && isset($kpis['tiempo_promedio_cierre']) ? $kpis['tiempo_promedio_cierre']['dias'] : '-' }}</span>d 
            <span id="kpi-tiempo-horas">{{ isset($kpis) && isset($kpis['tiempo_promedio_cierre']) ? $kpis['tiempo_promedio_cierre']['horas'] : '-' }}</span>h
        </div>
        <div class="operaciones-kpi-label">Tiempo Promedio Cierre</div>
    </div>
</div>

