/**
 * Modern Search System
 * Handles search functionality with deep navigation support
 */

// Search index with route metadata
const searchIndex = [
    // Admin Routes - Home
    {
        route: 'dashboard',
        label: 'Dashboard Overview',
        path: '/dashboard',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
        category: 'Inicio',
        navigation: { topBar: 'home' }
    },
    {
        route: 'main.dashboard',
        label: 'Main Dashboard',
        path: '/main-dashboard',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />',
        category: 'Inicio',
        navigation: { topBar: 'home' }
    },
    
    // Admin Routes - Administración
    {
        route: 'crear.cliente',
        label: 'Administrar Clientes',
        path: '/clientes/create',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />',
        category: 'Administración > Clientes',
        navigation: { topBar: 'administracion', level1: 'clientes' }
    },
    {
        route: 'empresas-asociadas.index',
        label: 'Empresas Asociadas',
        path: '/empresas-asociadas',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />',
        category: 'Administración > Clientes',
        navigation: { topBar: 'administracion', level1: 'clientes' }
    },
    {
        route: 'contratos.index',
        label: 'Contratos',
        path: '/contratos',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />',
        category: 'Administración > Clientes',
        navigation: { topBar: 'administracion', level1: 'clientes' }
    },
    {
        route: 'personal.index',
        label: 'Listado Personal',
        path: '/personal',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />',
        category: 'Administración > Personal',
        navigation: { topBar: 'administracion', level1: 'personal' }
    },
    {
        route: 'personal.create',
        label: 'Nuevo Personal',
        path: '/personal/create',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />',
        category: 'Administración > Personal',
        navigation: { topBar: 'administracion', level1: 'personal' }
    },
    
    // Admin Routes - Operaciones > Eventos
    {
        route: 'eventos.index',
        label: 'Listado Eventos',
        path: '/eventos',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />',
        category: 'Operaciones > Eventos',
        navigation: { topBar: 'operaciones', level1: 'eventos' }
    },
    {
        route: 'eventos.create',
        label: 'Nuevo Evento',
        path: '/eventos/create',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />',
        category: 'Operaciones > Eventos',
        navigation: { topBar: 'operaciones', level1: 'eventos' }
    },
    {
        route: 'seguimientos.index',
        label: 'Administrar Seguimientos',
        path: '/seguimientos',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />',
        category: 'Operaciones > Eventos',
        navigation: { topBar: 'operaciones', level1: 'eventos' }
    },
    
    // Admin Routes - Operaciones > Objetivos
    {
        route: 'objetivos-aipem.index',
        label: 'Objetivos AIPEM',
        path: '/objetivos-aipem',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />',
        category: 'Operaciones > Objetivos',
        navigation: { topBar: 'operaciones', level1: 'objetivos' }
    },
    
    // Admin Routes - Operaciones > Patrullas
    {
        route: 'patrullas.index',
        label: 'Listado Patrullas',
        path: '/patrullas',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />',
        category: 'Operaciones > Patrullas',
        navigation: { topBar: 'operaciones', level1: 'patrullas' }
    },
    
    // Admin Routes - Operaciones > Hikcentral
    {
        route: 'cameras.index',
        label: 'Encoding Devices',
        path: '/cameras',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />',
        category: 'Operaciones > Hikcentral',
        navigation: { topBar: 'operaciones', level1: 'hikcentral' }
    },
    {
        route: 'patrullas.location',
        label: 'Real-Time Monitoring',
        path: '/patrullas/location',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />',
        category: 'Operaciones > Hikcentral',
        navigation: { topBar: 'operaciones', level1: 'hikcentral' }
    },
    {
        route: 'anpr.index',
        label: 'ANPR',
        path: '/anpr',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />',
        category: 'Operaciones > Hikcentral',
        navigation: { topBar: 'operaciones', level1: 'hikcentral' }
    },
    
    // Admin Routes - Operaciones > Flytbase
    {
        route: 'pilotos.index',
        label: 'Pilotos',
        path: '/pilotos',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />',
        category: 'Operaciones > Flytbase',
        navigation: { topBar: 'operaciones', level1: 'flytbase' }
    },
    {
        route: 'misiones-flytbase.index',
        label: 'Misiones',
        path: '/misiones-flytbase',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />',
        category: 'Operaciones > Flytbase',
        navigation: { topBar: 'operaciones', level1: 'flytbase' }
    },
    {
        route: 'alertas.index',
        label: 'Desplegar Misión',
        path: '/alertas',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />',
        category: 'Operaciones > Flytbase',
        navigation: { topBar: 'operaciones', level1: 'flytbase' }
    },
    {
        route: 'flight-logs.index',
        label: 'Flight Logs',
        path: '/flight-logs',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />',
        category: 'Operaciones > Flytbase',
        navigation: { topBar: 'operaciones', level1: 'flytbase' }
    },
    {
        route: 'sites.index',
        label: 'Sites',
        path: '/sites',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />',
        category: 'Operaciones > Flytbase',
        navigation: { topBar: 'operaciones', level1: 'flytbase' }
    },
    {
        route: 'drones-flytbase.index',
        label: 'Drones',
        path: '/drones-flytbase',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />',
        category: 'Operaciones > Flytbase',
        navigation: { topBar: 'operaciones', level1: 'flytbase' }
    },
    {
        route: 'docks-flytbase.index',
        label: 'Docks',
        path: '/docks-flytbase',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />',
        category: 'Operaciones > Flytbase',
        navigation: { topBar: 'operaciones', level1: 'flytbase' }
    },
    
    // Admin Routes - Sistema
    {
        route: 'sistema.permisos',
        label: 'Permisos',
        path: '/sistema/permisos',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />',
        category: 'Sistema > Configuración',
        navigation: { topBar: 'sistema', level1: 'configuracion' }
    },
    {
        route: 'asignar.permisos',
        label: 'Asignación de Permisos',
        path: '/asignar-permisos',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />',
        category: 'Sistema > Configuración',
        navigation: { topBar: 'sistema', level1: 'configuracion' }
    },
    {
        route: 'crear.roles',
        label: 'Roles',
        path: '/crear-roles',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />',
        category: 'Sistema > Configuración',
        navigation: { topBar: 'sistema', level1: 'configuracion' }
    },
    {
        route: 'notifications.admin',
        label: 'Admin Notificaciones',
        path: '/notifications/admin',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />',
        category: 'Sistema > Configuración',
        navigation: { topBar: 'sistema', level1: 'configuracion' }
    },
    {
        route: 'usuarios.index',
        label: 'Usuarios',
        path: '/usuarios',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />',
        category: 'Sistema',
        navigation: { topBar: 'sistema' }
    },
    {
        route: 'tickets.nuevo',
        label: 'Tickets',
        path: '/tickets/nuevo',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />',
        category: 'Sistema',
        navigation: { topBar: 'sistema' }
    },
    {
        route: 'inventario.index',
        label: 'Inventario',
        path: '/inventario',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />',
        category: 'Sistema',
        navigation: { topBar: 'sistema' }
    },
    {
        route: 'gallery.index',
        label: 'Galería',
        path: '/gallery',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />',
        category: 'Sistema',
        navigation: { topBar: 'sistema' }
    },
    
    // Client Routes
    {
        route: 'client.dashboard',
        label: 'Dashboard',
        path: '/client/dashboard',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
        category: 'Inicio',
        navigation: { topBar: 'home' }
    },
    {
        route: 'client.eventos.index',
        label: 'Listado Eventos',
        path: '/client/eventos',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />',
        category: 'Eventos',
        navigation: { topBar: 'eventos' }
    },
    {
        route: 'client.eventos.create',
        label: 'Nuevo Evento',
        path: '/client/eventos/nuevo',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />',
        category: 'Eventos',
        navigation: { topBar: 'eventos' }
    },
    {
        route: 'client.seguimientos.index',
        label: 'Seguimientos',
        path: '/client/seguimientos',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />',
        category: 'Eventos',
        navigation: { topBar: 'eventos' }
    },
    {
        route: 'client.patrullas.index',
        label: 'Administrar Patrullas',
        path: '/client/patrullas',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />',
        category: 'Patrullas',
        navigation: { topBar: 'patrullas' }
    },
    {
        route: 'client.patrullas.location',
        label: 'Ver en el Mapa',
        path: '/client/patrullas/location',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />',
        category: 'Patrullas',
        navigation: { topBar: 'patrullas' }
    },
    {
        route: 'client.alertas.index',
        label: 'Desplegar Misión',
        path: '/client/alertas',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />',
        category: 'Drones',
        navigation: { topBar: 'drones' }
    },
    {
        route: 'client.misiones',
        label: 'Programar Misión',
        path: '/client/misiones',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />',
        category: 'Drones',
        navigation: { topBar: 'drones' }
    },
    {
        route: 'client.flight-logs',
        label: 'Logs',
        path: '/client/flight-logs',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />',
        category: 'Drones',
        navigation: { topBar: 'drones' }
    },
    {
        route: 'client.gallery.index',
        label: 'Galería',
        path: '/client/gallery',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />',
        category: 'Galería',
        navigation: { topBar: 'galeria' }
    },
    {
        route: 'client.tickets.nuevo',
        label: 'Tickets',
        path: '/client/tickets/nuevo',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />',
        category: 'Tickets',
        navigation: { topBar: 'tickets' }
    }
];

// Make search index available globally
window.searchIndex = searchIndex;

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Search functionality
class ModernSearch {
    constructor() {
        this.results = [];
        this.selectedIndex = -1;
        this.searchInput = null;
        this.resultsContainer = null;
        
        this.init();
    }

    init() {
        // Wait for Alpine.js to be ready
        if (typeof Alpine !== 'undefined') {
            document.addEventListener('alpine:init', () => this.setupAlpine());
        } else {
            // Fallback if Alpine.js is not available
            this.setupVanilla();
        }
    }

    setupAlpine() {
        // Alpine.js integration is handled in the search-bar component
        // This is a fallback for vanilla JS
    }

    setupVanilla() {
        const searchBar = document.querySelector('.modern-search-bar');
        if (!searchBar) return;

        this.searchInput = searchBar.querySelector('input');
        this.resultsContainer = searchBar.querySelector('.modern-dropdown');

        if (this.searchInput) {
            this.searchInput.addEventListener('input', debounce((e) => {
                this.search(e.target.value);
            }, 300));

            this.searchInput.addEventListener('keydown', (e) => {
                this.handleKeyboard(e);
            });
        }
    }

    search(query) {
        if (!query || query.length < 2) {
            this.results = [];
            this.updateResults();
            return;
        }

        const lowerQuery = query.toLowerCase();
        this.results = window.searchIndex
            .filter(item => 
                item.label.toLowerCase().includes(lowerQuery) ||
                item.category.toLowerCase().includes(lowerQuery) ||
                item.route.toLowerCase().includes(lowerQuery)
            )
            .slice(0, 10);

        this.selectedIndex = -1;
        this.updateResults();
    }

    updateResults() {
        // Results are handled by Alpine.js in the component
        // This is a fallback
    }

    handleKeyboard(e) {
        if (!this.resultsContainer) return;

        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                this.selectedIndex = Math.min(this.selectedIndex + 1, this.results.length - 1);
                this.highlightResult();
                break;
            case 'ArrowUp':
                e.preventDefault();
                this.selectedIndex = Math.max(this.selectedIndex - 1, -1);
                this.highlightResult();
                break;
            case 'Enter':
                e.preventDefault();
                if (this.selectedIndex >= 0 && this.selectedIndex < this.results.length) {
                    this.selectResult(this.results[this.selectedIndex]);
                } else if (this.results.length > 0) {
                    this.selectResult(this.results[0]);
                }
                break;
            case 'Escape':
                if (this.resultsContainer) {
                    this.resultsContainer.classList.add('hidden');
                }
                break;
        }
    }

    highlightResult() {
        // Highlight logic handled by Alpine.js
    }

    selectResult(result) {
        if (window.modernNavigation) {
            window.modernNavigation.navigateToRoute(result);
        } else {
            window.location.href = result.path;
        }
    }
}

// Initialize search
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.modernSearch = new ModernSearch();
    });
} else {
    window.modernSearch = new ModernSearch();
}

