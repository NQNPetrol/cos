@props(['isClient' => false])

<aside class="modern-sidebar" id="modernSidebar" x-data="{ currentLevel: 'main', currentDashboard: 'home' }" role="navigation" aria-label="Menú lateral">
    <!-- Sidebar Content Container -->
    <div class="modern-sidebar-menu" id="sidebarContent">
        <!-- Content will be dynamically loaded here -->
        <div id="sidebarLoading" class="modern-sidebar-item" style="justify-content: center;">
            <span>Cargando...</span>
        </div>
    </div>
</aside>

<!-- Sidebar Content Templates (Hidden) -->
<div id="sidebarTemplates" style="display: none;">
    @if($isClient)
        <!-- Client Sidebar Templates -->
        <template id="sidebar-home">
            <div class="modern-sidebar-section-title">Inicio</div>
            <a href="{{ route('client.dashboard') }}" class="modern-sidebar-item" data-route="{{ route('client.dashboard') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('client.operaciones.dashboard') }}" class="modern-sidebar-item" data-route="{{ route('client.operaciones.dashboard') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span>Dashboard Operacional</span>
            </a>
        </template>

        <template id="sidebar-eventos">
            <div class="modern-sidebar-section-title">Eventos</div>
            <a href="{{ route('client.eventos.index') }}" class="modern-sidebar-item" data-route="{{ route('client.eventos.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <span>Listado</span>
            </a>
            <a href="{{ route('client.eventos.create') }}" class="modern-sidebar-item" data-route="{{ route('client.eventos.create') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <span>Nuevo</span>
            </a>
            <a href="{{ route('client.seguimientos.index') }}" class="modern-sidebar-item" data-route="{{ route('client.seguimientos.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span>Seguimientos</span>
            </a>
        </template>

        <template id="sidebar-patrullas">
            <div class="modern-sidebar-section-title">Patrullas</div>
            <a href="{{ route('client.patrullas.index') }}" class="modern-sidebar-item" data-route="{{ route('client.patrullas.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span>Administrar Patrullas</span>
            </a>
            <a href="{{ route('client.patrullas.location') }}" class="modern-sidebar-item" data-route="{{ route('client.patrullas.location') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span>Ver en el Mapa</span>
            </a>
        </template>

        <template id="sidebar-drones">
            <div class="modern-sidebar-section-title">Drones</div>
            <a href="{{ route('client.alertas.index') }}" class="modern-sidebar-item" data-route="{{ route('client.alertas.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span>Desplegar Misión</span>
            </a>
            <a href="{{ route('client.misiones') }}" class="modern-sidebar-item" data-route="{{ route('client.misiones') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <span>Programar Misión</span>
            </a>
            <a href="{{ route('client.flight-logs') }}" class="modern-sidebar-item" data-route="{{ route('client.flight-logs') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span>Logs</span>
            </a>
        </template>

        <template id="sidebar-galeria">
            <div class="modern-sidebar-section-title">Galería</div>
            <a href="{{ route('client.gallery.index') }}" class="modern-sidebar-item" data-route="{{ route('client.gallery.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <span>Galería</span>
            </a>
        </template>

        <template id="sidebar-tickets">
            <a href="{{ route('client.tickets.nuevo') }}" class="modern-sidebar-item" data-route="{{ route('client.tickets.nuevo') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
                <span>Tickets</span>
            </a>
        </template>
    @else
        <!-- Admin Sidebar Templates -->
        <template id="sidebar-home">
            <div class="modern-sidebar-section-title">Inicio</div>
            <a href="{{ route('dashboard') }}" class="modern-sidebar-item" data-route="{{ route('dashboard') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <span>Dashboard Overview</span>
            </a>
            <a href="{{ route('main.dashboard') }}" class="modern-sidebar-item" data-route="{{ route('main.dashboard') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span>Main Dashboard</span>
            </a>
        </template>

        <!-- Administración Sidebar -->
        <template id="sidebar-administracion">
            <div class="modern-sidebar-section-title">Administración</div>
            <div class="modern-sidebar-item" data-level2="clientes" style="cursor: pointer;">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span>Clientes</span>
            </div>
            <div class="modern-sidebar-item" data-level2="personal" style="cursor: pointer;">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <span>Personal</span>
            </div>
            <a href="{{ route('rodados.index') }}" class="modern-sidebar-item" data-route="{{ route('rodados.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg
                        class="modern-sidebar-item-icon"
                        viewBox="0 0 229.98 229.98"
                        fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path d="M223.211,127.002c-0.717,0-1.451,0.102-2.185,0.304l-8.301,2.286l-8.618-20.995c-2.441-5.948-9.659-10.787-16.089-10.787
                            h-27.84V81.719c0-5.972-1.846-15.328-4.114-20.855l-1.391-3.388h1.054c4.819,0,8.74-3.921,8.74-8.74v-4.894
                            c0-4.128-2.847-7.125-6.769-7.125c-0.717,0-1.451,0.103-2.185,0.304l-8.3,2.286l-8.619-20.995
                            c-2.441-5.948-9.659-10.787-16.089-10.787H41.846c-6.429,0-13.646,4.839-16.089,10.787l-8.607,20.967l-8.195-2.257
                            c-0.733-0.202-1.469-0.305-2.185-0.305C2.847,36.717,0,39.713,0,43.842v4.894c0,4.819,3.921,8.74,8.74,8.74h0.939l-1.391,3.388
                            c-2.269,5.525-4.114,14.88-4.114,20.855v41.71c0,4.819,3.921,8.74,8.74,8.74h11.417c4.819,0,8.74-3.921,8.74-8.74v-10.416h56.384
                            l-6.794,16.55l-8.196-2.258c-0.733-0.202-1.468-0.304-2.185-0.304c-3.922,0-6.769,2.997-6.769,7.125v4.894
                            c0,4.819,3.921,8.74,8.74,8.74h0.939l-1.392,3.389c-2.268,5.525-4.114,14.88-4.114,20.855v41.71c0,4.819,3.921,8.74,8.74,8.74
                            h11.416c4.819,0,8.74-3.921,8.74-8.74v-10.416h98.212v10.416c0,4.819,3.921,8.74,8.74,8.74h11.415c4.819,0,8.74-3.921,8.74-8.74
                            v-41.71c0-5.975-1.846-15.33-4.114-20.855l-1.391-3.389h1.055c4.819,0,8.74-3.921,8.74-8.74v-4.894
                            C229.98,129.998,227.133,127.002,223.211,127.002z M143.357,81.011v11.886c0,1.923-1.573,3.496-3.496,3.496h-24.767
                            c-1.923,0-3.496-1.573-3.496-3.496V81.011c0-1.923,1.573-3.496,3.496-3.496h24.767C141.784,77.515,143.357,79.088,143.357,81.011z
                            M52.521,92.897c0,1.923-1.573,3.496-3.496,3.496H24.259c-1.923,0-3.496-1.573-3.496-3.496V81.011c0-1.923,1.573-3.496,3.496-3.496
                            h24.767c1.923,0,3.496,1.573,3.496,3.496V92.897z M27.755,59.197c-3.846,0-5.797-2.911-4.337-6.469l13.036-31.757
                            c1.461-3.558,5.802-6.469,9.647-6.469h72.149c3.846,0,8.188,2.911,9.647,6.469l13.038,31.757c1.46,3.558-0.491,6.469-4.337,6.469
                            H27.755z M88.929,143.013l13.037-31.757c1.46-3.558,5.802-6.469,9.647-6.469h72.149c3.846,0,8.188,2.911,9.648,6.469l13.036,31.757
                            c1.461,3.558-0.491,6.469-4.337,6.469H93.266C89.42,149.482,87.469,146.571,88.929,143.013z M118.033,183.182
                            c0,1.923-1.573,3.496-3.496,3.496H89.77c-1.923,0-3.496-1.573-3.496-3.496v-11.886c0-1.923,1.573-3.496,3.496-3.496h24.768
                            c1.923,0,3.496,1.573,3.496,3.496V183.182z M208.867,183.182c0,1.923-1.573,3.496-3.496,3.496h-24.766
                            c-1.923,0-3.496-1.573-3.496-3.496v-11.886c0-1.923,1.573-3.496,3.496-3.496h24.766c1.923,0,3.496,1.573,3.496,3.496V183.182z"/>
                    </svg>
                </div>
                <span>Rodados</span>
            </a>
        </template>

        <template id="sidebar-administracion-clientes">
            <div class="modern-sidebar-section-title">Clientes</div>
            <div class="modern-sidebar-back-button" data-back-to="administracion">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>
                <span>Atrás</span>
            </div>
            <a href="{{ route('crear.cliente') }}" class="modern-sidebar-item" data-route="{{ route('crear.cliente') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <span>Administrar Clientes</span>
            </a>
            <a href="{{ route('empresas-asociadas.index') }}" class="modern-sidebar-item" data-route="{{ route('empresas-asociadas.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span>Empresas Asociadas</span>
            </a>
            <a href="{{ route('contratos.index') }}" class="modern-sidebar-item" data-route="{{ route('contratos.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span>Contratos</span>
            </a>
        </template>

        <template id="sidebar-administracion-personal">
            <div class="modern-sidebar-section-title">Personal</div>
            <div class="modern-sidebar-back-button" data-back-to="administracion">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>
                <span>Atrás</span>
            </div>
            <a href="{{ route('personal.index') }}" class="modern-sidebar-item" data-route="{{ route('personal.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span>Listado</span>
            </a>
            <a href="{{ route('personal.create') }}" class="modern-sidebar-item" data-route="{{ route('personal.create') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <span>Nuevo</span>
            </a>
        </template>

        <!-- Operaciones Level 1 - Main Menu -->
        <template id="sidebar-operaciones-level1">
            <div class="modern-sidebar-section-title">Operaciones</div>
            <a href="{{ route('operaciones.dashboard') }}" class="modern-sidebar-item" data-route="{{ route('operaciones.dashboard') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <span>Dashboard</span>
            </a>
            <div class="modern-sidebar-item" data-level2="eventos" style="cursor: pointer;">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span>Eventos</span>
            </div>
            <div class="modern-sidebar-item" data-level2="objetivos" style="cursor: pointer;">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span>Objetivos</span>
            </div>
            <div class="modern-sidebar-item" data-level2="patrullas" style="cursor: pointer;">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <span>Patrullas</span>
            </div>
            <div class="modern-sidebar-item" data-level2="hikcentral" style="cursor: pointer;">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
                <span>Hikcentral</span>
            </div>
            <div class="modern-sidebar-item" data-level2="flytbase" style="cursor: pointer;">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </div>
                <span>Flytbase</span>
            </div>
        </template>

        <!-- Operaciones Level 2 - Eventos -->
        <template id="sidebar-operaciones-eventos">
            <div class="modern-sidebar-section-title">Eventos</div>
            <div class="modern-sidebar-back-button" data-back-to="operaciones-level1">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>
                <span>Atrás</span>
            </div>
            <a href="{{ route('eventos.index') }}" class="modern-sidebar-item" data-route="{{ route('eventos.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span>Listado</span>
            </a>
            <a href="{{ route('eventos.create') }}" class="modern-sidebar-item" data-route="{{ route('eventos.create') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <span>Nuevo</span>
            </a>
            <a href="{{ route('seguimientos.index') }}" class="modern-sidebar-item" data-route="{{ route('seguimientos.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <span>Administrar Seguimientos</span>
            </a>
        </template>

        <!-- Operaciones Level 2 - Objetivos -->
        <template id="sidebar-operaciones-objetivos">
            <div class="modern-sidebar-section-title">Objetivos</div>
            <div class="modern-sidebar-back-button" data-back-to="operaciones-level1">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>
                <span>Atrás</span>
            </div>
            <a href="{{ route('objetivos-aipem.index') }}" class="modern-sidebar-item" data-route="{{ route('objetivos-aipem.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span>Objetivos AIPEM</span>
            </a>
        </template>

        <!-- Operaciones Level 2 - Patrullas -->
        <template id="sidebar-operaciones-patrullas">
            <div class="modern-sidebar-section-title">Patrullas</div>
            <div class="modern-sidebar-back-button" data-back-to="operaciones-level1">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>
                <span>Atrás</span>
            </div>
            <a href="{{ route('patrullas.index') }}" class="modern-sidebar-item" data-route="{{ route('patrullas.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span>Listado Patrullas</span>
            </a>
        </template>

        <!-- Operaciones Level 2 - Hikcentral -->
        <template id="sidebar-operaciones-hikcentral">
            <div class="modern-sidebar-section-title">Hikcentral</div>
            <div class="modern-sidebar-back-button" data-back-to="operaciones-level1">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>
                <span>Atrás</span>
            </div>
            <a href="{{ route('cameras.index') }}" class="modern-sidebar-item" data-route="{{ route('cameras.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
                <span>Encoding Devices</span>
            </a>
            <a href="{{ route('patrullas.location') }}" class="modern-sidebar-item" data-route="{{ route('patrullas.location') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span>Real-Time Monitoring</span>
            </a>
            <a href="{{ route('anpr.index') }}" class="modern-sidebar-item" data-route="{{ route('anpr.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <span>ANPR</span>
            </a>
        </template>

        <!-- Operaciones Level 2 - Flytbase -->
        <template id="sidebar-operaciones-flytbase">
            <div class="modern-sidebar-section-title">Flytbase</div>
            <div class="modern-sidebar-back-button" data-back-to="operaciones-level1">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>
                <span>Atrás</span>
            </div>
            <a href="{{ route('pilotos.index') }}" class="modern-sidebar-item" data-route="{{ route('pilotos.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <span>Pilotos</span>
            </a>
            <a href="{{ route('misiones-flytbase.index') }}" class="modern-sidebar-item" data-route="{{ route('misiones-flytbase.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <span>Misiones</span>
            </a>
            <a href="{{ route('alertas.index') }}" class="modern-sidebar-item" data-route="{{ route('alertas.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span>Alertas / Desplegar Misión</span>
            </a>
            <a href="{{ route('flight-logs.index') }}" class="modern-sidebar-item" data-route="{{ route('flight-logs.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span>Flight Logs</span>
            </a>
            <a href="{{ route('sites.index') }}" class="modern-sidebar-item" data-route="{{ route('sites.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span>Sites</span>
            </a>
            <a href="{{ route('drones-flytbase.index') }}" class="modern-sidebar-item" data-route="{{ route('drones-flytbase.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </div>
                <span>Drones</span>
            </a>
            <a href="{{ route('docks-flytbase.index') }}" class="modern-sidebar-item" data-route="{{ route('docks-flytbase.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span>Docks</span>
            </a>
        </template>

        <!-- Sistema Sidebar -->
        <template id="sidebar-sistema">
            <div class="modern-sidebar-section-title">Sistema</div>
            <div class="modern-sidebar-item" data-level2="configuracion" style="cursor: pointer;">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span>Configuración</span>
            </div>
            <a href="{{ route('usuarios.index') }}" class="modern-sidebar-item" data-route="{{ route('usuarios.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <span>Usuarios</span>
            </a>
            <a href="{{ route('tickets.nuevo') }}" class="modern-sidebar-item" data-route="{{ route('tickets.nuevo') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
                <span>Tickets</span>
            </a>
            <a href="{{ route('inventario.index') }}" class="modern-sidebar-item" data-route="{{ route('inventario.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <span>Inventario</span>
            </a>
            <a href="{{ route('gallery.index') }}" class="modern-sidebar-item" data-route="{{ route('gallery.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <span>Galería</span>
            </a>
        </template>

        <template id="sidebar-sistema-configuracion">
            <div class="modern-sidebar-section-title">Configuración</div>
            <div class="modern-sidebar-back-button" data-back-to="sistema">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>
                <span>Atrás</span>
            </div>
            <a href="{{ route('sistema.permisos') }}" class="modern-sidebar-item" data-route="{{ route('sistema.permisos') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <span>Permisos</span>
            </a>
            <a href="{{ route('asignar.permisos') }}" class="modern-sidebar-item" data-route="{{ route('asignar.permisos') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <span>Asignación de Permisos</span>
            </a>
            <a href="{{ route('crear.roles') }}" class="modern-sidebar-item" data-route="{{ route('crear.roles') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span>Roles</span>
            </a>
            <a href="{{ route('notifications.admin') }}" class="modern-sidebar-item" data-route="{{ route('notifications.admin') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <span>Admin Notificaciones</span>
            </a>
            <a href="{{ route('activity-log.index') }}" class="modern-sidebar-item" data-route="{{ route('activity-log.index') }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span>Activity Log</span>
            </a>
        </template>
    @endif
</div>

