<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Default Title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-700">

    {{-- <!-- Header -->
    <header class="fixed top-0 left-0 w-full bg-slate-300 shadow z-20 flex justify-between items-center px-4 py-3">
        <!-- Botón hamburguesa -->
        <button id="toggleSidebar" class="text-gray-600 md:hidden">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <h1 class="text-xl font-bold mx-auto">Centro de Operaciones</h1>
    </header> --}}
    <!-- Menú horizontal superior -->
    {{-- <nav class="bg-slate-800 shadow fixed w-full z-50 top-0">
        <div class="max-w-7xl mx-auto px-4 flex justify-between h-16 items-center">
            <!-- Botón hamburguesa para sidebar -->
            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Links principales -->
            <div class="flex space-x-6">
                <a href="#" class="text-gray-300 hover:text-blue-600 font-medium">Inicio</a>

                <div class="relative group">
                    <button class="text-gray-300 hover:text-blue-600 font-medium focus:outline-none">
                        Herramientas
                    </button>
                    <div class="absolute left-0 mt-2 bg-white shadow rounded-md hidden group-hover:block z-10 w-40">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Ver Eventos</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Nuevo Evento</a>
                    </div>
                </div>

                <a href="#" class="text-gray-300 hover:text-blue-600 font-medium">Patrullas</a>
            </div>

            <!-- Perfil -->
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-500">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-600 hover:underline text-sm">Salir</button>
                </form>
            </div>
        </div>
    </nav> --}}

    <nav x-data="{ open: false }" class="bg-slate-800 border-b border-gray-300 fixed w-full top-0 z-50">
        <!-- Primary Navigation Menu -->
        <div class="px-4 sm:px-6">
            <div class="flex justify-between h-16">
                <div class="flex place-content-start">
                    <div class="sm:flex sm:items-center my-3 px-2 sm:ml-6">
                        <!-- Toggler -->
                        <button id="menu-toggle" class="p-2 rounded-md text-gray-300 hover:text-gray-800 focus:outline-none focus:bg-gray-200 sm:hidden">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path class="block" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                    <!-- Logo -->
                    <div class="shrink-0 flex">
                        <a href="/dashboard">
                            <x-application-mark class="h-14 w-auto" />
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex  lg:px-36">
                        <x-nav-link class="text-white active:bg-slate-100" href="/dashboard" :active="request()->routeIs('dashboard')">
                            Inicio
                        </x-nav-link>
                        <x-nav-link class="text-white" href="#" :active="request()->routeIs('dashboard')">
                            Dashboard
                        </x-nav-link>
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <!-- Notifications -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <!-- Notifications Button -->
                    <div class="relative mr-4">
                        <button id="notificationBtn" class="inline-block relative p-2 hover:bg-slate-700 rounded-full transition-colors duration-200">
                            <svg class="h-6 w-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span id="notificationBadge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold"></span>
                        </button>
                        
                        <!-- Notification Modal -->
                        <div id="notificationModal" class="hidden absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50 overflow-hidden">
                            <!-- Header con borde más destacado -->
                            <div class="p-4 border-b-2 border-gray-300 bg-gray-50">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-gray-800 font-bold text-lg">Notificaciones</h3>
                                    <div class="flex space-x-2">
                                        <button id="markAllReadBtn" class="text-sm text-blue-600 hover:text-blue-800 px-3 py-1 rounded transition-colors bg-blue-50 hover:bg-blue-100 font-medium">
                                            Marcar todas
                                        </button>
                                        <button id="closeModal" class="text-gray-500 hover:text-gray-700 transition-colors p-1 rounded hover:bg-gray-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contenido - Prevenir scroll horizontal -->
                            <div class="max-h-96 overflow-y-auto overflow-x-hidden">
                                <div id="notificationList" class="divide-y divide-gray-200">
                                    <!-- Loading state -->
                                    <div id="loadingState" class="p-4 text-center text-gray-500">
                                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
                                        <p class="mt-2">Cargando notificaciones...</p>
                                    </div>
                                    
                                    <!-- Empty state -->
                                    <div id="emptyState" class="hidden p-6 text-center text-gray-500">
                                        <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p>No tienes notificaciones</p>
                                    </div>
                                </div>
                                
                                <!-- Load More Button -->
                                <div id="loadMoreContainer" class="hidden p-4 text-center border-t border-gray-200 bg-gray-50">
                                    <button id="loadMoreBtn" class="text-blue-600 hover:text-blue-800 text-sm transition-colors font-medium">
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

                <!-- Hamburger -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>



        <!-- Responsive Navigation Menu -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
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

    <!-- Contenedor -->
    <div class="flex pt-10 h-screen mt-6">
        
        <!-- Sidebar -->
        <aside id="sidebar" class="bg-white w-64 h-full shadow-lg fixed hidden md:block z-10">
            <nav class="p-8 space-y-2">
                <button id="toggleClientes" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 rounded flex justify-between items-center">
                    <i class="bi bi-person-arms-up text-xl"></i>Clientes
                    <svg class="w-4 h-4 transition-transform" id="iconClientes" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Submenú -->
                <div id="submenuClientes" class="pl-6 space-y-1 hidden">
                    
                    <a href="{{ route('crear.cliente') }}" class="block text-gray-600 hover:text-gray-900 p-2">
                        <i class="bi bi-person-plus mr-2"></i>
                        Administrar Clientes
                    </a>

                    <a href="{{ route('empresas-asociadas.index') }}" class="block text-gray-600 hover:text-gray-900 p-2">
                        <i class="bi bi-people mr-2"></i>
                        Empresas Asociadas
                    </a>
                    
                    <a href="{{ route('contratos.index') }}" class="block text-gray-600 hover:text-gray-900 p-2">
                        <i class="bi bi-file-earmark-medical mr-2"></i>
                        Contratos
                    </a>
                </div>
                <!-- Botón para desplegar -->
                <button id="toggleEventos" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 rounded flex justify-between items-center">
                    <i class="bi bi-bell-fill text-xl"></i>Eventos
                    <svg class="w-4 h-4 transition-transform" id="iconEventos" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Submenú -->
                <div id="submenuEventos" class="pl-6 space-y-1 hidden">
                    <a href="{{ route('eventos.index') }}" class="block text-gray-700 hover:text-gray-900 p-2"><i class="bi bi-search mr-2"></i>Listado</a>
                    <a href="{{ route('eventos.create') }}" class="block text-gray-700 hover:text-gray-900 p-2"><i class="bi bi-plus mr-2"></i>Nuevo</a>
                    <a href="{{ route('seguimientos.index') }}" class="block text-gray-700 hover:text-gray-900 p-2"><i class="bi bi-search mr-2"></i>Administrar Seguimientos</a>
                </div>

                <!-- OBJETIVOS -->
                <button id="toggleObjetivos" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 rounded flex justify-between items-center">
                    <i class="bi bi-bullseye text-xl"></i>Objetivos
                    <svg class="w-4 h-4 transition-transform" id="iconObjetivos" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Submenú -->
                <div id="submenuObjetivos" class="pl-6 space-y-1 hidden">
                    
                    <a href="{{ route('objetivos.index') }}" class="block text-gray-600 hover:text-gray-900 p-2">
                        <i class="bi bi-plus-circle mr-2"></i>
                        Administrar Objetivos
                    </a>
                    
                </div>


                <button id="togglePatrullas" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 rounded flex justify-between items-center">
                    <i class="bi bi-taxi-front-fill text-xl"></i>Patrullas
                    <svg class="w-4 h-4 transition-transform" id="iconPatrullas" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                

                <!-- Submenú -->
                <div id="submenuPatrullas" class="pl-6 space-y-1 hidden">
                    <a href="{{ route('patrullas.index') }}" class="block text-gray-600 hover:text-gray-900 p-2">Administrar Patrullas</a>
                    <a href="{{ route('patrullas.location') }}" class="block text-gray-600 hover:text-gray-900 p-2">Administrar Patrullas</a>
                </div>

                <button id="toggleConfiguracion" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 rounded flex justify-between items-center">
                    <i class="bi bi-gear-fill text-xl"></i>Configuración
                    <svg class="w-4 h-4 transition-transform" id="iconConfiguracion" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Submenú -->
                <div id="submenuConfiguracion" class="pl-6 space-y-1 hidden">
                    <a href="{{ route('sistema.permisos') }}" class="block text-gray-600 hover:text-gray-900 p-2">Permisos</a>
                    <a href="{{ route('asignar.permisos') }}" class="block text-gray-600 hover:text-gray-900 p-2">Asignacion de Permisos</a>
                    <a href="{{ route('crear.roles') }}" class="block text-gray-600 hover:text-gray-900 p-2">Roles</a>
                    <a href="{{ route('notifications.admin') }}" class="block text-gray-600 hover:text-gray-900 p-2">Admin Notificaciones</a>
                </div>

                <!-- Tickets -->
                 <button id="toggleTickets" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 rounded flex justify-between items-center">
                    <i class="bi bi-bookmarks-fill text-xl"></i>Tickets
                    <svg class="w-4 h-4 transition-transform" id="iconTickets" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Submenú -->
                <div id="submenuTickets" class="pl-6 space-y-1 hidden">
                    <a href="{{ route('tickets.nuevo') }}" class="block text-gray-600 hover:text-gray-900 p-2">Gestionar Tickets</a>
                </div>

                <!-- Usuarios -->
                {{-- USUARIOS --}}
                <button id="toggleUsuarios" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 rounded flex justify-between items-center">
                    <i class="bi bi-person-fill text-xl"></i>Usuarios
                    <svg class="w-4 h-4 transition-transform" id="iconUsuarios" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Submenú -->
                <div id="submenuUsuarios" class="pl-6 space-y-1 hidden">
                    <a href="{{ route('usuarios.index') }}" class="block text-gray-600 hover:text-gray-900 p-2">
                        <i class="bi bi-people mr-2"></i>
                        Asignar roles a Usuarios
                    </a>
                    <a href="{{ route('user-cliente.index') }}" class="block text-gray-600 hover:text-gray-900 p-2">
                        <i class="bi bi-people mr-2"></i>
                        Asignar clientes a Usuarios
                    </a>
                </div>

                <!-- Personal -->
                <button id="togglePersonal" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 rounded flex justify-between items-center">
                    <i class="bi bi-people-fill text-xl"></i>Personal
                    <svg class="w-4 h-4 transition-transform" id="iconPersonal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Submenú -->
                 <div id="submenuPersonal" class="pl-6 space-y-1 hidden">
                    
                    <a href="{{ route('personal.index') }}" class="block text-gray-600 hover:text-gray-900 p-2">
                        <i class="bi bi-search mr-2"></i>
                        Listado
                    </a>
                 
                    <a href="{{ route('personal.create') }}" class="block text-gray-600 hover:text-gray-900 p-2">
                        <i class="bi bi-plus-circle mr-2"></i>
                        Nuevo
                    </a>
            
                </div>

                <!-- Inventario -->
                <button id="toggleInventario" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 rounded flex justify-between items-center">
                    <i class="bi bi-box-seam mr-2"></i>Inventario
                    <svg class="w-4 h-4 transition-transform" id="iconInventario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Submenú -->
                 <div id="submenuInventario" class="pl-6 space-y-1 hidden">
                    
                    <a href="{{ route('inventario.index') }}" class="block text-gray-600 hover:text-gray-900 p-2">
                        <i class="bi bi-search mr-2"></i>
                        Ver Inventario
                    </a>
                    <a href="{{ route('cameras.index') }}" class="block text-gray-600 hover:text-gray-900 p-2">
                        <i class="bi bi-search mr-2"></i>
                        Cámaras
                    </a>
            
                </div>
            </nav>
        </aside>

        <!-- Contenido -->
        <main class="flex-1 p-6 ml-0 md:ml-64">
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
                        <div class="flex-1 mr-3">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-gray-800 font-semibold text-sm">${notification.title}</h4>
                                <span class="text-xs font-medium px-2 py-1 rounded-full ${priorityColors[notification.priority]} bg-opacity-20">
                                    ${priorityText[notification.priority]}
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm mb-3">${notification.message}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400 text-xs">${notification.created_at_human}</span>
                                <div class="flex space-x-2">
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

        // CSS for animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeOut {
                from { opacity: 1; transform: translateX(0); }
                to { opacity: 0; transform: translateX(-100%); }
            }
            
            /* Animación de pulsación para botones */
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }
            
            @keyframes bounce {
                0%, 20%, 53%, 80%, 100% {
                    transform: translate3d(0, 0, 0);
                }
                40%, 43% {
                    transform: translate3d(0, -8px, 0);
                }
                70% {
                    transform: translate3d(0, -4px, 0);
                }
                90% {
                    transform: translate3d(0, -2px, 0);
                }
            }
            
            .notification-item {
                transition: all 0.2s ease;
            }
            
            .notification-item:hover {
                transform: translateX(2px);
            }
            
            /* Tooltip styles */
            .tooltip-text {
                min-width: 120px;
                text-align: center;
            }
            
            .mark-read-btn, .dismiss-btn {
                position: relative;
                transition: all 0.3s ease;
            }
            
            /* Animaciones para botones de iconos */
            .mark-read-btn:hover {
                animation: pulse 0.5s ease-in-out;
                color: #2563eb !important;
            }
            
            .dismiss-btn:hover {
                animation: bounce 0.5s ease-in-out;
                color: #dc2626 !important;
            }
            
            /* Efecto de elevación al hover */
            .mark-read-btn:hover svg,
            .dismiss-btn:hover svg {
                filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
            }
            
            /* Prevenir scroll horizontal en todo el modal */
            #notificationModal * {
                max-width: 100%;
                overflow-wrap: break-word;
            }
            
            /* Asegurar que el contenido no cause overflow */
            .notification-content {
                word-wrap: break-word;
                overflow-wrap: break-word;
            }
        `;

        style.textContent += `
        /* Animación Jetstream para el modal */
        #notificationModal {
            transition: opacity 300ms ease-in-out, transform 300ms ease-in-out;
        }
        
        #notificationModal.opacity-0 {
            opacity: 0;
        }
        
        #notificationModal.opacity-100 {
            opacity: 1;
        }
        
        #notificationModal.scale-95 {
            transform: scale(0.95);
        }
        
        #notificationModal.scale-100 {
            transform: scale(1);
        }
        
        /* Asegurar que el modal esté posicionado correctamente durante la animación */
        #notificationModal.transform {
            transform-origin: top right;
        }
    `;
        document.head.appendChild(style);

        // Initialize notification manager when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            window.notificationManager = new NotificationManager();
        });
    </script>

    <!-- JS: toggle sidebar -->
    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');

        // toggleBtn.addEventListener('click', () => {
        //     sidebar.classList.toggle('hidden');
        // });

        // Menú desplegable Clientes
        const toggleClientes = document.getElementById('toggleClientes');
        const submenuClientes = document.getElementById('submenuClientes');
        const iconClientes = document.getElementById('iconClientes');

        toggleClientes.addEventListener('click', () => {
            submenuClientes.classList.toggle('hidden');
            iconClientes.classList.toggle('rotate-180');
        });

        // Menú desplegable Eventos
        const toggleEventos = document.getElementById('toggleEventos');
        const submenuEventos = document.getElementById('submenuEventos');
        const iconEventos = document.getElementById('iconEventos');

        toggleEventos.addEventListener('click', () => {
            submenuEventos.classList.toggle('hidden');
            iconEventos.classList.toggle('rotate-180');
        });
        //objetivos
        const toggleObjetivos = document.getElementById('toggleObjetivos');
        const submenuObjetivos = document.getElementById('submenuObjetivos');
        const iconObjetivos = document.getElementById('iconObjetivos');

        toggleObjetivos.addEventListener('click', () => {
            submenuObjetivos.classList.toggle('hidden');
            iconObjetivos.classList.toggle('rotate-180');
        });
        //patrullas
        const togglePatrullas = document.getElementById('togglePatrullas');
        const submenuPatrullas = document.getElementById('submenuPatrullas');
        const iconPatrullas = document.getElementById('iconPatrullas');

        togglePatrullas.addEventListener('click', () => {
            submenuPatrullas.classList.toggle('hidden');
            iconPatrullas.classList.toggle('rotate-180');
        });
        //config
        const toggleConfiguracion = document.getElementById('toggleConfiguracion');
        const submenuConfiguracion = document.getElementById('submenuConfiguracion');
        const iconConfiguracion = document.getElementById('iconConfiguracion');

        toggleConfiguracion.addEventListener('click', () => {
            submenuConfiguracion.classList.toggle('hidden');
            iconConfiguracion.classList.toggle('rotate-180');
        });

        //tickets
        const toggleTickets = document.getElementById('toggleTickets');
        const submenuTickets = document.getElementById('submenuTickets');
        const iconTickets = document.getElementById('iconTickets');

        toggleTickets.addEventListener('click', () => {
            submenuTickets.classList.toggle('hidden');
            iconTickets.classList.toggle('rotate-180');
        });

        //usuarios
        const toggleUsuarios = document.getElementById('toggleUsuarios');
        const submenuUsuarios = document.getElementById('submenuUsuarios');
        const iconUsuarios = document.getElementById('iconUsuarios');

        toggleUsuarios.addEventListener('click', () => {
            submenuUsuarios.classList.toggle('hidden');
            iconUsuarios.classList.toggle('rotate-180');
        });
    
        //personal
        const togglePersonal = document.getElementById('togglePersonal');
        const submenuPersonal = document.getElementById('submenuPersonal');
        const iconPersonal = document.getElementById('iconPersonal');

        togglePersonal.addEventListener('click', () => {
            submenuPersonal.classList.toggle('hidden');
            iconPersonal.classList.toggle('rotate-180');
        });

        //Inventario
        const toggleInventario = document.getElementById('toggleInventario');
        const submenuInventario = document.getElementById('submenuInventario');
        const iconInventario = document.getElementById('iconInventario');

        toggleInventario.addEventListener('click', () => {
            submenuInventario.classList.toggle('hidden');
            iconInventario.classList.toggle('rotate-180');
        });

    </script>
    @stack('scripts')
</body>
</html>

