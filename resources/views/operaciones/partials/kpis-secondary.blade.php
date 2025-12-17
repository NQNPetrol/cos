<div class="operaciones-kpis-secondary" id="operaciones-kpis-secondary">
    <!-- KPI 6: Vuelos Total vs Incompletos -->
    <div class="operaciones-kpi-card">
        <div class="operaciones-kpi-icon">
            <i class="bi bi-airplane-fill"></i>
        </div>
        <div class="operaciones-kpi-value" id="kpi-vuelos">
            <span id="kpi-vuelos-total">{{ isset($kpis) ? ($kpis['vuelos_total'] ?? 0) : '-' }}</span> / 
            <span id="kpi-vuelos-incompletos">{{ isset($kpis) ? ($kpis['vuelos_incompletos'] ?? 0) : '-' }}</span>
        </div>
        <div class="operaciones-kpi-label">Vuelos / Incompletos</div>
    </div>

    <!-- KPI 7: Triggers de Misiones -->
    <div class="operaciones-kpi-card">
        <div class="operaciones-kpi-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-send-check-fill" viewBox="0 0 16 16">
                <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 1.59 2.498C8 14 8 13 8 12.5a4.5 4.5 0 0 1 5.026-4.47zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471z"/>
                <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-1.993-1.679a.5.5 0 0 0-.686.172l-1.17 1.95-.547-.547a.5.5 0 0 0-.708.708l.774.773a.75.75 0 0 0 1.174-.144l1.335-2.226a.5.5 0 0 0-.172-.686"/>
            </svg>
        </div>
        <div class="operaciones-kpi-value" id="kpi-triggers-total">{{ isset($kpis) && isset($kpis['triggers_misiones']) ? $kpis['triggers_misiones']['total'] : '-' }}</div>
        <div class="operaciones-kpi-label">Triggers Enviados</div>
        <div class="operaciones-kpi-detail" id="kpi-triggers-detail" style="font-size: 11px; color: var(--fb-text-secondary); margin-top: 4px;">
            @if(isset($kpis) && isset($kpis['triggers_misiones']['por_dron']))
                @foreach(array_slice($kpis['triggers_misiones']['por_dron'], 0, 3) as $trigger)
                    {{ $trigger['drone_name'] }}: {{ $trigger['count'] }}{{ !$loop->last ? ', ' : '' }}
                @endforeach
            @endif
        </div>
    </div>
</div>

