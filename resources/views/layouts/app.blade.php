<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <button class="inline-block relative">
                        <svg class="h-6 w-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="animate-ping absolute top-1 right-0.5 block h-1 w-1 rounded-full ring-2 ring-green-200 bg-green-600"></span>
                    </button>
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
                        Nuevo Cliente
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
                    <a href="{{ route('eventos.index') }}" class="block text-gray-700 hover:text-gray-900 p-2"><i class="bi bi-search mr-2"></i>Ver Eventos</a>
                    <a href="{{ route('eventos.create') }}" class="block text-gray-700 hover:text-gray-900 p-2"><i class="bi bi-plus mr-2"></i>Nuevo Evento</a>
                    <a href="{{ route('seguimientos.index') }}" class="block text-gray-700 hover:text-gray-900 p-2"><i class="bi bi-search mr-2"></i>Ver Seguimientos</a>
                    <a href="{{ route('seguimientos.create') }}" class="block text-gray-700 hover:text-gray-900 p-2"><i class="bi bi-node-plus mr-2"></i>Nuevo Seguimiento</a>
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
                        Nuevo Objetivo
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
                    <a href="{{ route('usuarios.index') }}" class="block text-gray-600 hover:text-gray-900 p-2">Usuarios</a>
                    <a href="#" class="block text-gray-600 hover:text-gray-900 p-2">Nuevo Evento</a>
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
                        <i class="bi bi-plus-circle mr-2"></i>
                        Ver Personal
                    </a>
                 
                    <a href="{{ route('personal.create') }}" class="block text-gray-600 hover:text-gray-900 p-2">
                        <i class="bi bi-plus-circle mr-2"></i>
                        Nuevo Personal
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
            
                </div>
            </nav>
        </aside>

        <!-- Contenido -->
        <main class="flex-1 p-6 ml-0 md:ml-64">
            {{ $slot }}
        </main>
    </div>

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

