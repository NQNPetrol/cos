@php
    // Determine the active section based on the current URL
    $path = request()->path();
    $activeSection = 'home'; // default

    if (str_starts_with($path, 'rodados/pagos-servicios') || str_starts_with($path, 'rodados/cobranzas')) {
        $activeSection = 'finanzas';
    } elseif (str_starts_with($path, 'clientes') || str_starts_with($path, 'empresas-asociadas') || str_starts_with($path, 'rodados/proveedores-talleres') || str_starts_with($path, 'personal')) {
        $activeSection = 'personas';
    } elseif ($path === 'rodados' || str_starts_with($path, 'rodados/calendario') || str_starts_with($path, 'rodados/alertas-admin') || str_starts_with($path, 'rodados/turnos') || str_starts_with($path, 'rodados/cambios-equipo') || str_starts_with($path, 'rodados/kilometraje')) {
        $activeSection = 'vehiculos';
    } elseif (str_starts_with($path, 'rodados/admin-dashboard')) {
        $activeSection = 'home';
    }
@endphp

<aside class="modern-sidebar" id="modernSidebar" role="navigation" aria-label="Menú lateral administrativo">
    <div class="modern-sidebar-menu" id="sidebarContent">

        {{-- ===== HOME / DASHBOARD ===== --}}
        @if($activeSection === 'home')
            <div class="modern-sidebar-section-title">Dashboard</div>

            <a href="{{ route('rodados.admin-dashboard') }}" class="modern-sidebar-item {{ request()->routeIs('rodados.admin-dashboard') ? 'active' : '' }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span>Panel General</span>
            </a>
        @endif

        {{-- ===== VEHÍCULOS ===== --}}
        @if($activeSection === 'vehiculos')
            <div class="modern-sidebar-section-title">Vehículos</div>

            <a href="{{ route('rodados.index') }}" class="modern-sidebar-item {{ request()->routeIs('rodados.index') ? 'active' : '' }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                    </svg>
                </div>
                <span>Gestión de Vehículos</span>
            </a>

            <a href="{{ route('rodados.calendario.index') }}" class="modern-sidebar-item {{ request()->routeIs('rodados.calendario.*') ? 'active' : '' }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <span>Calendario</span>
            </a>

            <a href="{{ route('rodados.alertas-admin.index') }}" class="modern-sidebar-item {{ request()->routeIs('rodados.alertas-admin.*') ? 'active' : '' }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <span>Alertas</span>
            </a>
        @endif

        {{-- ===== FINANZAS ===== --}}
        @if($activeSection === 'finanzas')
            <div class="modern-sidebar-section-title">Pagos y Cobranzas</div>

            <a href="{{ route('rodados.pagos-servicios.index') }}" class="modern-sidebar-item {{ request()->routeIs('rodados.pagos-servicios.*') ? 'active' : '' }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span>Pagos y Servicios</span>
            </a>

            <a href="{{ route('rodados.cobranzas.index') }}" class="modern-sidebar-item {{ request()->routeIs('rodados.cobranzas.*') ? 'active' : '' }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                    </svg>
                </div>
                <span>Cobranzas</span>
            </a>
        @endif

        {{-- ===== PERSONAS ===== --}}
        @if($activeSection === 'personas')
            <div class="modern-sidebar-section-title">Clientes, Proveedores y Personal</div>

            <a href="{{ route('crear.cliente') }}" class="modern-sidebar-item {{ request()->routeIs('crear.cliente') || request()->routeIs('clientes.*') ? 'active' : '' }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span>Clientes</span>
            </a>

            <a href="{{ route('empresas-asociadas.index') }}" class="modern-sidebar-item {{ request()->routeIs('empresas-asociadas.*') ? 'active' : '' }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span>Empresas Asociadas</span>
            </a>

            <a href="{{ route('rodados.proveedores-talleres.index') }}" class="modern-sidebar-item {{ request()->routeIs('rodados.proveedores-talleres.*') ? 'active' : '' }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <span>Proveedores y Talleres</span>
            </a>

            <a href="{{ route('personal.index') }}" class="modern-sidebar-item {{ request()->routeIs('personal.*') ? 'active' : '' }}">
                <div class="modern-sidebar-item-icon-container">
                    <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <span>Personal</span>
            </a>
        @endif

    </div>
</aside>

<!-- Collapse tab: fixed, flush with sidebar right edge -->
<button id="adminSidebarToggle"
    onclick="toggleAdminSidebar()"
    title="Ocultar menú"
    style="position:fixed; left:239px; top:50%; transform:translateY(-50%); width:16px; height:44px; display:flex; align-items:center; justify-content:center; background:#18181b; border:1px solid #3f3f46; border-left:none; border-radius:0 6px 6px 0; color:#71717a; cursor:pointer; padding:0; z-index:1000; transition:color 0.15s, background 0.15s;">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" style="width:12px; height:12px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
    </svg>
</button>

<!-- Floating button to re-open sidebar when collapsed -->
<button id="adminSidebarOpen"
    onclick="toggleAdminSidebar()"
    title="Mostrar menú lateral"
    style="display:none; position:fixed; left:10px; top:72px; width:36px; height:36px; align-items:center; justify-content:center; background:#18181b; border:1px solid #3f3f46; border-radius:10px; color:#a1a1aa; cursor:pointer; padding:0; z-index:1000; box-shadow:0 4px 12px rgba(0,0,0,0.3);">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="width:18px; height:18px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
    </svg>
</button>

@push('styles')
<style>
    /* Collapse tab hover */
    #adminSidebarToggle:hover {
        color: #e4e4e7 !important;
        background: #27272a !important;
    }

    /* Floating open button hover state */
    #adminSidebarOpen:hover {
        background: #27272a !important;
        color: #f4f4f5 !important;
        transform: scale(1.05);
    }
</style>
@endpush

@push('scripts')
<script>
    function collapseAdminSidebar(animate) {
        const sidebar = document.getElementById('modernSidebar');
        const openBtn = document.getElementById('adminSidebarOpen');
        const collapseTab = document.getElementById('adminSidebarToggle');
        const content = document.querySelector('.modern-content');
        if (!sidebar || !openBtn || !content) return;

        const dur = animate ? '0.3s' : '0s';
        sidebar.style.transition = 'left ' + dur + ' cubic-bezier(0.4,0,0.2,1)';
        content.style.transition = 'margin-left ' + dur + ' cubic-bezier(0.4,0,0.2,1)';

        sidebar.style.left = '-260px';
        sidebar.style.pointerEvents = 'none';
        content.style.marginLeft = '0';
        openBtn.style.display = 'flex';
        if (collapseTab) collapseTab.style.display = 'none';
    }

    function expandAdminSidebar(animate) {
        const sidebar = document.getElementById('modernSidebar');
        const openBtn = document.getElementById('adminSidebarOpen');
        const collapseTab = document.getElementById('adminSidebarToggle');
        const content = document.querySelector('.modern-content');
        if (!sidebar || !openBtn || !content) return;

        const dur = animate ? '0.3s' : '0s';
        sidebar.style.transition = 'left ' + dur + ' cubic-bezier(0.4,0,0.2,1)';
        content.style.transition = 'margin-left ' + dur + ' cubic-bezier(0.4,0,0.2,1)';

        sidebar.style.left = '0';
        sidebar.style.pointerEvents = '';
        content.style.marginLeft = '';
        openBtn.style.display = 'none';
        if (collapseTab) collapseTab.style.display = 'flex';
    }

    function toggleAdminSidebar() {
        const sidebar = document.getElementById('modernSidebar');
        if (!sidebar) return;

        const isCurrentlyOpen = sidebar.style.left !== '-260px';
        if (isCurrentlyOpen) {
            collapseAdminSidebar(true);
            localStorage.setItem('adminSidebarCollapsed', '1');
        } else {
            expandAdminSidebar(true);
            localStorage.setItem('adminSidebarCollapsed', '0');
        }
    }

    // Restore state on page load
    (function() {
        if (localStorage.getItem('adminSidebarCollapsed') === '1') {
            collapseAdminSidebar(false);
        }
    })();
</script>
@endpush
