<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Centro de Operaciones')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        /* Animaciones personalizadas */
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(-100%); }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% { transform: translate3d(0,0,0); }
            40%, 43% { transform: translate3d(0,-8px,0); }
            70% { transform: translate3d(0,-4px,0); }
            90% { transform: translate3d(0,-2px,0); }
        }

        .sidebar-scroll {
            height: calc(100vh - 4rem); /* Altura total menos el header */
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Scrollbar completamente invisible para Webkit (Chrome, Safari, Edge) */
        .sidebar-scroll::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: transparent;
            border: none;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: transparent;
        }

        /* Scrollbar invisible para Firefox */
        .sidebar-scroll {
            scrollbar-width: none;
            scrollbar-color: transparent transparent;
        }

        /* Scrollbar invisible para Internet Explorer y Edge Legacy */
        .sidebar-scroll {
            -ms-overflow-style: none;
        }

        /* Asegurar que el contenido del sidebar tenga suficiente espacio */
        .sidebar-content {
            min-height: min-content;
            padding-bottom: 2rem; /* Espacio extra al final */
        }

        /* Prevenir que los submenús se corten */
        .submenu-container {
            max-height: none !important;
            overflow: visible !important;
        }

        /* Mejorar el scroll suave */
        .sidebar-scroll {
            scroll-behavior: smooth;
        }

        .animate-slideDown { animation: slideDown 0.3s ease-out; }
        .animate-slideIn { animation: slideIn 0.3s ease-out; }
        .animate-fadeIn { animation: fadeIn 0.3s ease-out; }
        .animate-pulse-custom { animation: pulse 0.5s ease-in-out; }
        .animate-bounce-custom { animation: bounce 0.5s ease-in-out; }
        
        /* Efectos de hover mejorados */
        .nav-item {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .nav-item:hover::before,
        .nav-item.active::before {
            transform: scaleY(1);
        }
        
        .nav-item:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(29, 78, 216, 0.05));
            transform: translateX(8px);
        }
        
        /* Mejoras del modal de notificaciones */
        .notification-modal {
            backdrop-filter: blur(8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow-x: hidden !important;
        }
        
        .notification-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 4px solid transparent;
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 100%;
        }
        
        .notification-item:hover {
            transform: translateX(4px);
            border-left-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }
        
        /* Prevenir scroll horizontal */
        .notification-content * {
            max-width: 100% !important;
            overflow-wrap: break-word !important;
            word-wrap: break-word !important;
        }
        
        /* Sidebar mejorado */
        .sidebar {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.08);
        }
        
        /* Header mejorado */
        .header-gradient {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        }
        
        /* Tooltips */
        .mark-read-btn:hover svg,
        .dismiss-btn:hover svg {
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }
        
        .mark-read-btn:hover {
            animation: pulse 0.5s ease-in-out;
            color: #2563eb !important;
        }
        
        .dismiss-btn:hover {
            animation: bounce 0.5s ease-in-out;
            color: #dc2626 !important;
        }
    </style>
</head>
<body class="bg-gray-700">

    <!-- Navegación superior mejorada -->
    <nav x-data="{ open: false }" class="header-gradient shadow-xl fixed w-full top-0 z-50 border-b border-blue-700/20">
        <div class="px-4 sm:px-6">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-4">
                    <!-- Botón hamburguesa mejorado -->
                    <button id="menu-toggle" class="p-2 rounded-xl text-white/80 hover:text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 sm:hidden transition-all duration-300">
                        <svg class="h-6 w-6 transition-transform duration-300" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path class="block" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Texto del sistema -->
                    <div class="flex items-center space-x-4">
                        <div class="hidden sm:block">
                            <h1 class="text-xl font-bold text-white">CENTRO DE OPERACIONES</h1>
                            <p class="text-sm text-white/70">DE SEGURIDAD</p>
                        </div>
                    </div>

                    <!-- Enlaces de navegación -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex  lg:px-36">
                        <x-nav-link class="text-white active:bg-slate-100" href="/dashboard" :active="request()->routeIs('dashboard')">
                            Inicio
                        </x-nav-link>
                        <x-nav-link class="text-white" href="#" :active="request()->routeIs('dashboard')">
                            Dashboard
                        </x-nav-link>
                    </div>
                </div>

                <!-- Acciones del usuario -->
                <div class="hidden sm:flex sm:items-center sm:space-x-4">
                    <!-- Botón de notificaciones mejorado -->
                    <div class="relative">
                        <button id="notificationBtn" class="relative p-3 hover:bg-white/10 rounded-xl transition-all duration-300 group">
                            <svg class="h-6 w-6 text-white/80 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span id="notificationBadge" class="hidden absolute -top-1 -right-1 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold animate-pulse shadow-lg"></span>
                        </button>
                        
                        <!-- Modal de notificaciones mejorado -->
                        <div id="notificationModal" class="hidden absolute right-0 mt-3 w-96 notification-modal bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 z-50 overflow-hidden">
                            <div class="p-6 border-b border-gray-200/50 bg-gradient-to-r from-blue-50 to-indigo-50">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-gray-800 font-bold text-lg flex items-center space-x-2">
                                        <i class="bi bi-bell-fill text-blue-600"></i>
                                        <span>Notificaciones</span>
                                    </h3>
                                    <div class="flex space-x-2">
                                        <button id="markAllReadBtn" class="text-sm text-blue-600 hover:text-blue-800 px-4 py-2 rounded-xl transition-all bg-blue-50 hover:bg-blue-100 font-medium">
                                            Marcar todas
                                        </button>
                                        <button id="closeModal" class="text-gray-500 hover:text-gray-700 transition-colors p-2 rounded-xl hover:bg-gray-100">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="max-h-96 overflow-y-auto overflow-x-hidden">
                                <div id="notificationList" class="divide-y divide-gray-100 notification-content">
                                    <!-- Loading state -->
                                    <div id="loadingState" class="p-6 text-center text-gray-500">
                                        <div class="animate-spin rounded-full h-10 w-10 border-3 border-blue-500 border-t-transparent mx-auto"></div>
                                        <p class="mt-3 text-sm">Cargando notificaciones...</p>
                                    </div>
                                    
                                    <!-- Empty state -->
                                    <div id="emptyState" class="hidden p-8 text-center text-gray-500">
                                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                            <i class="bi bi-bell-slash text-2xl text-gray-400"></i>
                                        </div>
                                        <p class="font-medium">No tienes notificaciones</p>
                                        <p class="text-sm text-gray-400 mt-1">Te notificaremos cuando haya algo nuevo</p>
                                    </div>
                                </div>
                                
                                <div id="loadMoreContainer" class="hidden p-4 text-center border-t border-gray-100 bg-gray-50/50">
                                    <button id="loadMoreBtn" class="text-blue-600 hover:text-blue-800 text-sm transition-colors font-medium px-4 py-2 rounded-xl hover:bg-blue-50">
                                        Cargar más notificaciones
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Teams Dropdown -->
                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                        <div class="ml-3 relative">
                            <x-dropdown align="right" width="60">
                                <x-slot name="trigger">
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                            {{-- {{ Auth::user()->currentTeam->name }} --}}

                                            <svg class="ml-2 -mr-0.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                            </svg>
                                        </button>
                                    </span>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="w-60">
                                        <!-- Team Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Manage Team') }}
                                        </div>

                                        <!-- Team Settings -->
                                        {{-- <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                            {{ __('Team Settings') }}
                                        </x-dropdown-link> --}}

                                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                            <x-dropdown-link href="{{ route('teams.create') }}">
                                                {{ __('Create New Team') }}
                                            </x-dropdown-link>
                                        @endcan

                                        <!-- Team Switcher -->
                                        @if (Auth::user()->allTeams()->count() > 1)
                                            <div class="border-t border-gray-200"></div>

                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                {{ __('Switch Teams') }}
                                            </div>

                                            @foreach (Auth::user()->allTeams() as $team)
                                                <x-switchable-team :team="$team" />
                                            @endforeach
                                        @endif
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif

                    <!-- Settings Dropdown -->
                    <div class="ml-3 relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                    </button>
                                @else
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                            @ 
                                            {{ Auth::user()->name }}

                                            <svg class="ml-2 -mr-0.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                    </span>
                                @endif
                            </x-slot>

                            <x-slot name="content">
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Manage Account') }}
                                </div>

                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                        {{ __('API Tokens') }}
                                    </x-dropdown-link>
                                @endif

                                <div class="border-t border-gray-200"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf

                                    <x-dropdown-link href="{{ route('logout') }}"
                                            @click.prevent="$root.submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>

                <!-- Botón hamburguesa móvil -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-xl text-white/80 hover:text-white hover:bg-white/10 transition-all duration-300">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Menú responsive -->
        <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden bg-white/10">
            <div class="pt-2 pb-3 space-y-2 px-4">
                <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div class="shrink-0 mr-3">
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        </div>
                    @endif

                    <div>
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <!-- Account Management -->
                    <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                            {{ __('API Tokens') }}
                        </x-responsive-nav-link>
                    @endif

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf

                        <x-responsive-nav-link href="{{ route('logout') }}"
                                    @click.prevent="$root.submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenedor principal -->
    <div class="flex pt-16 min-h-screen">
        <aside id="sidebar" class="sidebar w-72 h-full shadow-2xl fixed hidden md:block z-40 animate-slideIn">
            <div class="sidebar-scroll"> <!-- Contenedor con scroll -->
                <div class="sidebar-content p-6"> <!-- Contenido del sidebar -->
                    <!-- Logo solamente (sin texto) -->
                    <div class="mb-6 text-center sticky top-0 bg-white z-10 pb-4"> <!-- Sticky header -->
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('cyh.png') }}" 
                                alt="Logo Centro de Operaciones de Seguridad" 
                                class="h-28 w-auto mb-4 transition-transform duration-300 hover:scale-105"
                                onerror="this.style.display='none'; document.getElementById('fallbackLogo').style.display='flex';">
                                                    
                            <div id="fallbackLogo" class="h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 hidden">
                                <svg class="h-10 w-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <nav class="space-y-2">
                        <!-- Clientes -->
                        <div class="submenu-container">
                            <button id="toggleClientes" class="nav-item w-full text-left px-4 py-3 text-gray-700 hover:text-blue-700 rounded-xl flex justify-between items-center font-medium transition-all duration-300">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-person-arms-up text-white text-sm"></i>
                                    </div>
                                    <span>Clientes</span>
                                </div>
                                <svg class="w-5 h-5 transition-transform duration-300" id="iconClientes" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div id="submenuClientes" class="ml-4 mt-2 space-y-1 hidden animate-slideDown">
                                <a href="{{ route('crear.cliente') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-person-plus text-lg"></i>
                                    <span>Administrar Clientes</span>
                                </a>
                                <a href="{{ route('empresas-asociadas.index') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-people text-lg"></i>
                                    <span>Empresas Asociadas</span>
                                </a>
                                <a href="{{ route('contratos.index') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-file-earmark-medical text-lg"></i>
                                    <span>Contratos</span>
                                </a>
                            </div>
                        </div>

                        <!-- Eventos -->
                        <div class="submenu-container">
                            <button id="toggleEventos" class="nav-item w-full text-left px-4 py-3 text-gray-700 hover:text-blue-700 rounded-xl flex justify-between items-center font-medium transition-all duration-300">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-bell-fill text-white text-sm"></i>
                                    </div>
                                    <span>Eventos</span>
                                </div>
                                <svg class="w-5 h-5 transition-transform duration-300" id="iconEventos" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div id="submenuEventos" class="ml-4 mt-2 space-y-1 hidden animate-slideDown">
                                <a href="{{ route('eventos.index') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-search text-lg"></i>
                                    <span>Listado</span>
                                </a>
                                <a href="{{ route('eventos.create') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-plus text-lg"></i>
                                    <span>Nuevo</span>
                                </a>
                                <a href="{{ route('seguimientos.index') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-search text-lg"></i>
                                    <span>Administrar Seguimientos</span>
                                </a>
                            </div>
                        </div>

                        <!-- Patrullas -->
                        <div class="submenu-container">
                            <button id="togglePatrullas" class="nav-item w-full text-left px-4 py-3 text-gray-700 hover:text-blue-700 rounded-xl flex justify-between items-center font-medium transition-all duration-300">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-taxi-front-fill text-white text-sm"></i>
                                    </div>
                                    <span>Patrullas</span>
                                </div>
                                <svg class="w-5 h-5 transition-transform duration-300" id="iconPatrullas" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div id="submenuPatrullas" class="ml-4 mt-2 space-y-1 hidden animate-slideDown">
                                <a href="{{ route('patrullas.index') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-gear text-lg"></i>
                                    <span>Administrar Patrullas</span>
                                </a>
                                <a href="{{ route('patrullas.location') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-geo-alt text-lg"></i>
                                    <span>Ver en el Mapa</span>
                                </a>
                            </div>
                        </div>

                        <!-- Configuración -->
                        <div class="submenu-container">
                            <button id="toggleConfiguracion" class="nav-item w-full text-left px-4 py-3 text-gray-700 hover:text-blue-700 rounded-xl flex justify-between items-center font-medium transition-all duration-300">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-gear-fill text-white text-sm"></i>
                                    </div>
                                    <span>Configuración</span>
                                </div>
                                <svg class="w-5 h-5 transition-transform duration-300" id="iconConfiguracion" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div id="submenuConfiguracion" class="ml-4 mt-2 space-y-1 hidden animate-slideDown">
                                <a href="{{ route('sistema.permisos') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-shield-check text-lg"></i>
                                    <span>Permisos</span>
                                </a>
                                <a href="{{ route('asignar.permisos') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-person-gear text-lg"></i>
                                    <span>Asignación de Permisos</span>
                                </a>
                                <a href="{{ route('crear.roles') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-tags text-lg"></i>
                                    <span>Roles</span>
                                </a>
                                <a href="{{ route('notifications.admin') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-bell-slash text-lg"></i>
                                    <span>Admin Notificaciones</span>
                                </a>
                            </div>
                        </div>

                        <!-- Personal -->
                        <div class="submenu-container">
                            <button id="togglePersonal" class="nav-item w-full text-left px-4 py-3 text-gray-700 hover:text-blue-700 rounded-xl flex justify-between items-center font-medium transition-all duration-300">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-people-fill text-white text-sm"></i>
                                    </div>
                                    <span>Personal</span>
                                </div>
                                <svg class="w-5 h-5 transition-transform duration-300" id="iconPersonal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div id="submenuPersonal" class="ml-4 mt-2 space-y-1 hidden animate-slideDown">
                                <a href="{{ route('personal.index') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-search text-lg"></i>
                                    <span>Listado</span>
                                </a>
                                <a href="{{ route('personal.create') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-plus-circle text-lg"></i>
                                    <span>Nuevo</span>
                                </a>
                            </div>
                        </div>

                        <!-- Inventario -->
                        <div class="submenu-container">
                            <button id="toggleInventario" class="nav-item w-full text-left px-4 py-3 text-gray-700 hover:text-blue-700 rounded-xl flex justify-between items-center font-medium transition-all duration-300">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-box-seam text-white text-sm"></i>
                                    </div>
                                    <span>Inventario</span>
                                </div>
                                <svg class="w-5 h-5 transition-transform duration-300" id="iconInventario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div id="submenuInventario" class="ml-4 mt-2 space-y-1 hidden animate-slideDown">
                                <a href="{{ route('inventario.index') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-search text-lg"></i>
                                    <span>Ver Inventario</span>
                                </a>
                                <a href="{{ route('cameras.index') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-camera text-lg"></i>
                                    <span>Cámaras</span>
                                </a>
                            </div>
                        </div>

                        <!-- Alertas (hikvision - flytbase) -->
                        <div class="submenu-container">
                            <button id="toggleAlertas" class="nav-item w-full text-left px-4 py-3 text-gray-700 hover:text-blue-700 rounded-xl flex justify-between items-center font-medium transition-all duration-300">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-rocket-takeoff text-white text-sm"></i>
                                    </div>
                                    <span>Flytbase</span>
                                </div>
                                <svg class="w-5 h-5 transition-transform duration-300" id="iconAlertas" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div id="submenuAlertas" class="ml-4 mt-2 space-y-1 hidden animate-slideDown">
                                <a href="{{ route('pilotos.index') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-person-badge-fill"></i>
                                    <span>Pilotos</span>
                                </a>
                                <a href="{{ route('misiones-flytbase.index') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-calendar-check"></i>
                                    <span>Misiones</span>
                                </a>
                                <a href="{{ route('alertas.index') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-bell"></i>
                                    <span>Alertas</span>
                                </a>
                                <a href="{{ route('drones-flytbase.index') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-bezier"></i>
                                    <span>Drones</span>
                                </a>
                                <a href="{{ route('gallery.index') }}" class="flex items-center space-x-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 p-3 rounded-lg transition-all duration-200">
                                    <i class="bi bi-grid-1x2"></i>
                                    <span>Galeria</span>
                                </a>
                            </div>
                        </div>

                        <!-- Tickets - Sin submenu, va directo -->
                        <div class="submenu-container">
                            <a href="{{ route('tickets.nuevo') }}" class="nav-item w-full text-left px-4 py-3 text-gray-700 hover:text-blue-700 rounded-xl flex items-center font-medium transition-all duration-300">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-bookmarks-fill text-white text-sm"></i>
                                    </div>
                                    <span>Tickets</span>
                                </div>
                            </a>
                        </div>

                        <div class="submenu-container">
                            <a href="{{ route('objetivos.index') }}" class="nav-item w-full text-left px-4 py-3 text-gray-700 hover:text-blue-700 rounded-xl flex items-center font-medium transition-all duration-300">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-bullseye text-white text-sm"></i>
                                    </div>
                                    <span>Objetivos</span>
                                </div>
                            </a>
                        </div>

                        <!-- Usuarios - Sin submenu, va directo -->
                        <div class="submenu-container">
                            <a href="{{ route('usuarios.index') }}" class="nav-item w-full text-left px-4 py-3 text-gray-700 hover:text-blue-700 rounded-xl flex items-center font-medium transition-all duration-300">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-person-fill text-white text-sm"></i>
                                    </div>
                                    <span>Usuarios</span>
                                </div>
                            </a>
                        </div>
                    </nav>
                </div>
            </div>
        </aside>

        <!-- Contenido principal -->
        <main class="flex-1 p-6 ml-0 md:ml-72">
            {{ $slot }}
        </main>
    </div>

    <!-- Notifications JavaScript -->
    <script>
        class NotificationManager {
            constructor() {
                this.currentPage = 1;
                this.hasMorePages = false;
                this.isLoading = false;
                this.modal = document.getElementById('notificationModal');
                this.button = document.getElementById('notificationBtn');
                this.badge = document.getElementById('notificationBadge');
                this.list = document.getElementById('notificationList');
                
                this.init();
            }

            init() {
                this.setupEventListeners();
                this.updateUnreadCount();
                this.setupCSRF();
            }

            setupCSRF() {
                this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            }

            setupEventListeners() {
                // Toggle modal
                this.button.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.toggleModal();
                });

                // Close modal
                document.getElementById('closeModal').addEventListener('click', () => {
                    this.closeModal();
                });

                // Close modal when clicking outside
                document.addEventListener('click', (e) => {
                    if (!this.modal.contains(e.target) && !this.button.contains(e.target)) {
                        this.closeModal();
                    }
                });

                // Mark all as read
                document.getElementById('markAllReadBtn').addEventListener('click', () => {
                    this.markAllAsRead();
                });

                // Load more notifications
                document.getElementById('loadMoreBtn').addEventListener('click', () => {
                    this.loadMoreNotifications();
                });
            }

            async toggleModal() {
                if (this.modal.classList.contains('hidden')) {
                    await this.openModal();
                } else {
                    this.closeModal();
                }
            }

            async openModal() {
                // Mostrar el modal pero inicialmente invisible
                this.modal.classList.remove('hidden');
                
                // Forzar reflow para que la animación funcione
                void this.modal.offsetWidth;
                
                // Aplicar animación de entrada (solo esto es nuevo)
                this.modal.classList.remove('opacity-0', 'scale-95');
                this.modal.classList.add('opacity-100', 'scale-100');
                
                if (this.currentPage === 1) {
                    await this.loadNotifications();
                }
            }

            closeModal() {
                // Animación de salida (solo esto es nuevo)
                this.modal.classList.remove('opacity-100', 'scale-100');
                this.modal.classList.add('opacity-0', 'scale-95');
                
                // Ocultar después de que termine la animación
                setTimeout(() => {
                    if (this.modal.classList.contains('opacity-0')) {
                        this.modal.classList.add('hidden');
                    }
                }, 300);
            }

            async loadNotifications(page = 1) {
                if (this.isLoading) return;
                
                this.isLoading = true;
                this.showLoadingState();

                try {
                    console.log('Solicitando notificaciones, página:', page);
                    const response = await fetch(`/notificaciones?page=${page}`);
                    
                    console.log('Respuesta HTTP:', response.status, response.statusText);
                    
                    if (!response.ok) {
                        throw new Error(`Error HTTP! estado: ${response.status}`);
                    }
                    
                    const responseText = await response.text();
                    console.log('Respuesta textual:', responseText);
                    
                    let data;
                    try {
                        data = JSON.parse(responseText);
                        console.log('Datos parseados:', data);
                    } catch (parseError) {
                        console.error('Error parseando JSON:', parseError);
                        throw new Error('Respuesta JSON inválida del servidor');
                    }

                    // Verificar estructura de datos
                    if (!data || typeof data !== 'object') {
                        console.error('Estructura de datos inválida:', data);
                        throw new Error('Respuesta del servidor inválida');
                    }

                    if (page === 1 && this.list) {
                        this.list.innerHTML = '';
                    }

                    if (data.data && Array.isArray(data.data)) {
                        console.log('Notificaciones recibidas:', data.data.length);
                        this.renderNotifications(data.data);
                        this.currentPage = data.current_page || 1;
                        this.hasMorePages = data.has_more || false;
                        this.updateLoadMoreButton();
                    } else {
                        console.error('Estructura de datos incorrecta, falta propiedad data:', data);
                        throw new Error('Estructura de datos inválida');
                    }

                    this.hideLoadingState();

                } catch (error) {
                    console.error('Error completo en loadNotifications:', error);
                    this.showErrorState();
                } finally {
                    this.isLoading = false;
                }
            }

            async loadMoreNotifications() {
                if (this.hasMorePages && !this.isLoading) {
                    await this.loadNotifications(this.currentPage + 1);
                }
            }

            renderNotifications(notifications) {
                if (notifications.length === 0 && this.currentPage === 1) {
                    this.showEmptyState();
                    return;
                }

                notifications.forEach(notification => {
                    const element = this.createNotificationElement(notification);
                    this.list.appendChild(element);
                });
            }

            createNotificationElement(notification) {
                const div = document.createElement('div');
                div.className = `notification-item p-4 hover:bg-gray-50 transition-colors duration-200 ${
                    notification.is_read ? 'opacity-75 bg-gray-50' : 'bg-white'
                }`;
                div.dataset.id = notification.id;

                const priorityColors = {
                    'ALTA': 'text-red-500',
                    'NORMAL': 'text-blue-500',
                    'BAJA': 'text-green-500'
                };
                const priorityText = {
                    'ALTA': 'PRIORIDAD: ALTA',
                    'NORMAL': 'PRIORIDAD: NORMAL', 
                    'BAJA': 'PRIORIDAD: BAJA'
                };

                div.innerHTML = `
                    <div class="flex justify-between items-start">
                        <div class="flex-1 mr-3" style="max-width: 100%; overflow-wrap: break-word;">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-gray-800 font-semibold text-sm" style="word-wrap: break-word;">${notification.title}</h4>
                                <span class="text-xs font-medium px-2 py-1 rounded-full ${priorityColors[notification.priority]} bg-opacity-20" style="flex-shrink: 0;">
                                    ${priorityText[notification.priority]}
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm mb-3" style="word-wrap: break-word; overflow-wrap: break-word;">${notification.message}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400 text-xs">${notification.created_at_human}</span>
                                <div class="flex space-x-2" style="flex-shrink: 0;">
                                    ${!notification.is_read ? `
                                        <button class="mark-read-btn group p-1 text-blue-600 hover:text-blue-800 transition-colors" 
                                                data-id="${notification.id}"
                                                title="Marcar como leída">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                                                <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                                                <path fill-rule="evenodd" d="M1.38 8.28a.87.87 0 0 1 0-.566 7.003 7.003 0 0 1 13.238.006.87.87 0 0 1 0 .566A7.003 7.003 0 0 1 1.379 8.28ZM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    ` : ''}
                                    <button class="dismiss-btn group p-1 text-red-600 hover:text-red-800 transition-colors" 
                                            data-id="${notification.id}"
                                            title="Descartar">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                                            <path d="M2 3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3Z" />
                                            <path fill-rule="evenodd" d="M13 6H3v6a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V6ZM5.72 7.47a.75.75 0 0 1 1.06 0L8 8.69l1.22-1.22a.75.75 0 1 1 1.06 1.06L9.06 9.75l1.22 1.22a.75.75 0 1 1-1.06 1.06L8 10.81l-1.22 1.22a.75.75 0 0 1-1.06-1.06l1.22-1.22-1.22-1.22a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Add event listeners to buttons
                const markReadBtn = div.querySelector('.mark-read-btn');
                const dismissBtn = div.querySelector('.dismiss-btn');

                if (markReadBtn) {
                    markReadBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        this.markAsRead(notification.id, div);
                    });
                }

                dismissBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.dismissNotification(notification.id, div);
                });

                return div;
            }

            async markAsRead(notificationId, element) {
                try {
                    const response = await fetch(`/notificaciones/${notificationId}/leer`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        }
                    });

                    if (response.ok) {
                        element.classList.add('opacity-75');
                        element.classList.remove('border-l-4', 'border-blue-500');
                        const markReadBtn = element.querySelector('.mark-read-btn');
                        if (markReadBtn) markReadBtn.remove();
                        this.updateUnreadCount();
                    }
                } catch (error) {
                    console.error('Error marking notification as read:', error);
                }
            }

            async dismissNotification(notificationId, element) {
                try {
                    const response = await fetch(`/notificaciones/${notificationId}/descartar`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken
                        }
                    });

                    if (response.ok) {
                        element.style.animation = 'fadeOut 0.3s ease-out';
                        setTimeout(() => {
                            element.remove();
                            this.updateUnreadCount();
                        }, 300);
                    }
                } catch (error) {
                    console.error('Error dismissing notification:', error);
                }
            }

            async markAllAsRead() {
                try {
                    const response = await fetch('/notificaciones/leer-todas', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken
                        }
                    });

                    if (response.ok) {
                        // Update UI
                        const notifications = this.list.querySelectorAll('.notification-item');
                        notifications.forEach(item => {
                            item.classList.add('opacity-75');
                            item.classList.remove('border-l-4', 'border-blue-500');
                            const markReadBtn = item.querySelector('.mark-read-btn');
                            if (markReadBtn) markReadBtn.remove();
                        });
                        this.updateUnreadCount();
                    }
                } catch (error) {
                    console.error('Error marking all notifications as read:', error);
                }
            }

            async updateUnreadCount() {
                try {
                    const response = await fetch('/notificaciones/contador');
                    const data = await response.json();
                    
                    if (data.count > 0) {
                        this.badge.textContent = data.count > 99 ? '99+' : data.count;
                        this.badge.classList.remove('hidden');
                    } else {
                        this.badge.classList.add('hidden');
                    }
                } catch (error) {
                    console.error('Error updating unread count:', error);
                }
            }

            updateLoadMoreButton() {
                const container = document.getElementById('loadMoreContainer');
                if (this.hasMorePages) {
                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                }
            }

            showLoadingState() {
                document.getElementById('loadingState').classList.remove('hidden');
                document.getElementById('emptyState').classList.add('hidden');
            }

            hideLoadingState() {
                document.getElementById('loadingState').classList.add('hidden');
            }

            showEmptyState() {
                document.getElementById('emptyState').classList.remove('hidden');
                document.getElementById('loadingState').classList.add('hidden');
            }

            showErrorState() {
                // Primero ocultar el estado de carga
                this.hideLoadingState();
                
                // Luego mostrar error dentro del listado
                if (this.list) {
                    this.list.innerHTML = `
                        <div class="p-6 text-center text-red-400">
                            <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <p>Error al cargar las notificaciones</p>
                            <button onclick="window.notificationManager.loadNotifications(1)" class="mt-2 text-blue-400 hover:text-blue-300 text-sm">
                                Intentar nuevamente
                            </button>
                        </div>
                    `;
                }
            }
        }

        // Inicializar notification manager cuando DOM esté cargado
        document.addEventListener('DOMContentLoaded', () => {
            window.notificationManager = new NotificationManager();
        });
    </script>

    <!-- JS: toggle sidebar -->
    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');

        // Menú desplegable Clientes
        const toggleClientes = document.getElementById('toggleClientes');
        const submenuClientes = document.getElementById('submenuClientes');
        const iconClientes = document.getElementById('iconClientes');

        if (toggleClientes && submenuClientes && iconClientes) {
            toggleClientes.addEventListener('click', () => {
                submenuClientes.classList.toggle('hidden');
                iconClientes.classList.toggle('rotate-180');
            });
        }

        // Menú desplegable Eventos
        const toggleEventos = document.getElementById('toggleEventos');
        const submenuEventos = document.getElementById('submenuEventos');
        const iconEventos = document.getElementById('iconEventos');

        if (toggleEventos && submenuEventos && iconEventos) {
            toggleEventos.addEventListener('click', () => {
                submenuEventos.classList.toggle('hidden');
                iconEventos.classList.toggle('rotate-180');
            });
        }

        //patrullas
        const togglePatrullas = document.getElementById('togglePatrullas');
        const submenuPatrullas = document.getElementById('submenuPatrullas');
        const iconPatrullas = document.getElementById('iconPatrullas');

        if (togglePatrullas && submenuPatrullas && iconPatrullas) {
            togglePatrullas.addEventListener('click', () => {
                submenuPatrullas.classList.toggle('hidden');
                iconPatrullas.classList.toggle('rotate-180');
            });
        }

        //config
        const toggleConfiguracion = document.getElementById('toggleConfiguracion');
        const submenuConfiguracion = document.getElementById('submenuConfiguracion');
        const iconConfiguracion = document.getElementById('iconConfiguracion');

        if (toggleConfiguracion && submenuConfiguracion && iconConfiguracion) {
            toggleConfiguracion.addEventListener('click', () => {
                submenuConfiguracion.classList.toggle('hidden');
                iconConfiguracion.classList.toggle('rotate-180');
            });
        }

        //personal
        const togglePersonal = document.getElementById('togglePersonal');
        const submenuPersonal = document.getElementById('submenuPersonal');
        const iconPersonal = document.getElementById('iconPersonal');

        if (togglePersonal && submenuPersonal && iconPersonal) {
            togglePersonal.addEventListener('click', () => {
                submenuPersonal.classList.toggle('hidden');
                iconPersonal.classList.toggle('rotate-180');
            });
        }

        //Inventario
        const toggleInventario = document.getElementById('toggleInventario');
        const submenuInventario = document.getElementById('submenuInventario');
        const iconInventario = document.getElementById('iconInventario');

        if (toggleInventario && submenuInventario && iconInventario) {
            toggleInventario.addEventListener('click', () => {
                submenuInventario.classList.toggle('hidden');
                iconInventario.classList.toggle('rotate-180');
            });
        }

        //Alertas
        const toggleAlertas = document.getElementById('toggleAlertas');
        const submenuAlertas = document.getElementById('submenuAlertas');
        const iconAlertas = document.getElementById('iconAlertas');

        if (toggleAlertas && submenuAlertas && iconAlertas) {
            toggleAlertas.addEventListener('click', () => {
                submenuAlertas.classList.toggle('hidden');
                iconAlertas.classList.toggle('rotate-180');
            });
        }

        //Misiones
        const toggleMisiones = document.getElementById('toggleMisiones');
        const submenuMisiones = document.getElementById('submenuMisiones');
        const iconMisiones = document.getElementById('iconMisiones');

        if (toggleMisiones && submenuMisiones && iconMisiones) {
            toggleMisiones.addEventListener('click', () => {
                submenuMisiones.classList.toggle('hidden');
                iconMisiones.classList.toggle('rotate-180');
            });
        }
    </script>

    <script>
        // Mejorar la experiencia de scroll en el sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar-scroll');
            
            if (sidebar) {
                // Prevenir que los submenús se abran durante el scroll
                sidebar.addEventListener('scroll', function() {
                    // Opcional: puedes agregar lógica aquí si necesitas
                    // hacer algo específico durante el scroll
                });
                
                // Asegurar que el sidebar siempre muestre el scrollbar en hover
                sidebar.addEventListener('mouseenter', function() {
                    this.style.overflowY = 'auto';
                });
                
                sidebar.addEventListener('mouseleave', function() {
                    // Mantener el scrollbar visible pero más discreto
                    setTimeout(() => {
                        this.style.overflowY = 'auto';
                    }, 1000);
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>