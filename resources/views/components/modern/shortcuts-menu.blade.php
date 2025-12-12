@props(['isClient' => false])

<div id="shortcutsMenu" class="modern-dropdown hidden" style="width: 360px;">
    <div style="padding: 12px 16px; border-bottom: 1px solid var(--fb-border);">
        <h3 style="font-weight: 600; font-size: 16px; color: var(--fb-text-primary);">Accesos Rápidos</h3>
    </div>
    
    <div style="max-height: calc(100vh - 200px); overflow-y: auto;">
        @if($isClient)
            <!-- Client Shortcuts -->
            <div class="modern-sidebar-item" style="height: 60px; cursor: pointer;" data-shortcut="evento-nuevo" data-route="{{ route('client.eventos.create') }}" data-navigation='{"topBar":"eventos","level1":"eventos"}'>
                <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <div style="flex: 1;">
                    <div style="font-weight: 500; font-size: 15px;">Documentar un evento nuevo</div>
                    <div style="font-size: 13px; color: var(--fb-text-secondary);">Crear y registrar un nuevo evento</div>
                </div>
            </div>
            
            <div class="modern-sidebar-item" style="height: 60px; cursor: pointer;" data-shortcut="desplegar-mision" data-route="{{ route('client.alertas.index') }}" data-navigation='{"topBar":"drones","level1":"drones"}'>
                <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <div style="flex: 1;">
                    <div style="font-weight: 500; font-size: 15px;">Desplegar misión</div>
                    <div style="font-size: 13px; color: var(--fb-text-secondary);">Iniciar una misión de drone</div>
                </div>
            </div>
            
            <div class="modern-sidebar-item" style="height: 60px; cursor: pointer;" data-shortcut="ticket-nuevo" data-route="{{ route('client.tickets.nuevo') }}" data-navigation='{"topBar":"tickets"}'>
                <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
                <div style="flex: 1;">
                    <div style="font-weight: 500; font-size: 15px;">Crear nuevo ticket</div>
                    <div style="font-size: 13px; color: var(--fb-text-secondary);">Abrir un nuevo ticket de soporte</div>
                </div>
            </div>
        @else
            <!-- Admin Shortcuts -->
            <div class="modern-sidebar-item" style="height: 60px; cursor: pointer;" data-shortcut="evento-nuevo" data-route="{{ route('eventos.create') }}" data-navigation='{"topBar":"operaciones","level1":"eventos","level2":"eventos-nuevo"}'>
                <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <div style="flex: 1;">
                    <div style="font-weight: 500; font-size: 15px;">Documentar un evento nuevo</div>
                    <div style="font-size: 13px; color: var(--fb-text-secondary);">Operaciones → Eventos → Nuevo</div>
                </div>
            </div>
            
            <div class="modern-sidebar-item" style="height: 60px; cursor: pointer;" data-shortcut="desplegar-mision" data-route="{{ route('alertas.index') }}" data-navigation='{"topBar":"operaciones","level1":"flytbase","level2":"alertas"}'>
                <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <div style="flex: 1;">
                    <div style="font-weight: 500; font-size: 15px;">Desplegar misión</div>
                    <div style="font-size: 13px; color: var(--fb-text-secondary);">Operaciones → Flytbase → Alertas</div>
                </div>
            </div>
            
            <div class="modern-sidebar-item" style="height: 60px; cursor: pointer;" data-shortcut="ticket-nuevo" data-route="{{ route('tickets.nuevo') }}" data-navigation='{"topBar":"sistema"}'>
                <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
                <div style="flex: 1;">
                    <div style="font-weight: 500; font-size: 15px;">Crear nuevo ticket</div>
                    <div style="font-size: 13px; color: var(--fb-text-secondary);">Sistema → Tickets</div>
                </div>
            </div>
            
            <div class="modern-sidebar-item" style="height: 60px; cursor: pointer;" data-shortcut="seguimiento-nuevo" data-route="{{ route('seguimientos.index') }}" data-navigation='{"topBar":"operaciones","level1":"eventos","level2":"seguimientos"}'>
                <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <div style="flex: 1;">
                    <div style="font-weight: 500; font-size: 15px;">Crear nuevo seguimiento</div>
                    <div style="font-size: 13px; color: var(--fb-text-secondary);">Operaciones → Eventos → Seguimientos</div>
                </div>
            </div>
            
            <div class="modern-sidebar-item" style="height: 60px; cursor: pointer;" data-shortcut="patrullas-mapa" data-route="{{ route('patrullas.location') }}" data-navigation='{"topBar":"operaciones","level1":"hikcentral","level2":"monitoring"}'>
                <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <div style="flex: 1;">
                    <div style="font-weight: 500; font-size: 15px;">Ver patrullas en mapa</div>
                    <div style="font-size: 13px; color: var(--fb-text-secondary);">Operaciones → Hikcentral → Monitoring</div>
                </div>
            </div>
        @endif
    </div>
</div>

