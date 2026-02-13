<!DOCTYPE html>
<html lang="es" class="modern-ui">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administración - Centro de Operaciones')</title>
    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/js/modern-navigation.js',
        'resources/js/modern-search.js'
        ])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @livewireStyles
    @stack('styles')
</head>
<body class="modern-ui">
    <!-- Top Navigation Bar (Administrative) -->
    <x-modern.top-nav-administrative />

    <!-- Sidebar (Administrative) -->
    <x-modern.sidebar-administrative />

    <!-- Main Content Area -->
    <main class="modern-content">
        @hasSection('content')
            @yield('content')
        @elseif(isset($slot))
            {{ $slot }}
        @endif
    </main>

    @livewireScripts
    @stack('scripts')
</body>
</html>
