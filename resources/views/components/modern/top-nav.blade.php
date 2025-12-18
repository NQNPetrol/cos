@props(['isClient' => false])

<nav class="modern-top-nav">
    <!-- Left Section: Logo and Search -->
    <div class="modern-top-nav-left">
        <!-- Logo -->
        <a href="{{ $isClient ? route('client.dashboard') : route('dashboard') }}" class="modern-top-nav-logo-container">
            @if($isClient)
                @php
                    $user = auth()->user();
                    $logoUrl = $user->logo_cliente ?? null;
                    $clienteNombre = $user->nombre_cliente ?? 'Cliente';
                @endphp
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Logo {{ $clienteNombre }}" class="modern-top-nav-logo" onerror="this.onerror=null; this.src='{{ asset('cyh.png') }}';">
                @else
                    <img src="{{ asset('cyh.png') }}" alt="Logo" class="modern-top-nav-logo">
                @endif
            @else
                <img src="{{ asset('cyh.png') }}" alt="Logo" class="modern-top-nav-logo">
            @endif
        </a>
        
        <!-- Search Bar -->
        <x-modern.search-bar />
    </div>

    <!-- Center Section: Dashboard Icons -->
    <div class="modern-top-nav-center" role="navigation" aria-label="Navegación principal">
        @if($isClient)
            <!-- Client Dashboard Icons -->
            <button class="modern-top-nav-button" data-dashboard="home" data-route="{{ route('client.dashboard') }}" title="Inicio">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </button>
            <button class="modern-top-nav-button" data-dashboard="eventos" data-route="{{ route('client.eventos.index') }}" title="Eventos">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </button>
            <button class="modern-top-nav-button" data-dashboard="patrullas" data-route="{{ route('client.patrullas.index') }}" title="Patrullas">
                <i class="bi bi-taxi-front-fill"></i>
            </button>
            <button class="modern-top-nav-button" data-dashboard="drones" data-route="{{ route('client.alertas.index') }}" title="Drones">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </button>
            <button class="modern-top-nav-button" data-dashboard="galeria" data-route="{{ route('client.gallery.index') }}" title="Galería">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </button>
            <button class="modern-top-nav-button" data-dashboard="tickets" data-route="{{ route('client.tickets.nuevo') }}" title="Tickets">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
            </button>
        @else
            <!-- Admin Dashboard Icons -->
            <button class="modern-top-nav-button" data-dashboard="home" data-route="{{ route('dashboard') }}" title="Inicio" aria-label="Ir al inicio">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </button>
            <button class="modern-top-nav-button" data-dashboard="administracion" data-route="{{ route('crear.cliente') }}" title="Administración">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </button>
            <button class="modern-top-nav-button" data-dashboard="operaciones" data-route="{{ route('operaciones.dashboard') }}" title="Operaciones">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </button>
            <button class="modern-top-nav-button" data-dashboard="sistema" data-route="{{ route('sistema.permisos') }}" title="Sistema">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </button>
        @endif
    </div>

    <!-- Right Section: Shortcuts, Notifications, User -->
    <div class="modern-top-nav-right">
        <!-- Shortcuts Menu Button -->
        <button class="modern-top-nav-button" id="shortcutsMenuBtn" title="Accesos Rápidos">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Notifications Button -->
        <button class="modern-top-nav-button" id="notificationsBtn" title="Notificaciones">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span id="notificationBadge" class="modern-notification-badge hidden"></span>
        </button>

        <!-- User Profile Button -->
        <button class="modern-top-nav-button" id="userMenuBtn" title="Perfil">
            @if(Laravel\Jetstream\Jetstream::managesProfilePhotos() && Auth::user()->profile_photo_path)
                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="modern-top-nav-avatar">
            @else
                <div class="modern-top-nav-avatar" style="background-color: var(--fb-bg-tertiary); display: flex; align-items: center; justify-content: center; color: var(--fb-text-primary); font-weight: bold; aspect-ratio: 1 / 1;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
        </button>
    </div>

    <!-- Dropdown Menus -->
    <x-modern.shortcuts-menu :is-client="$isClient" />
    <x-modern.notifications-menu />
    <x-modern.user-menu :is-client="$isClient" />
</nav>

