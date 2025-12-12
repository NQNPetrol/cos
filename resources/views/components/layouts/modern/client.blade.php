<!DOCTYPE html>
<html lang="es" class="modern-ui">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Centro de Operaciones')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/css/modern-ui.css', 'resources/js/app.js', 'resources/js/modern-navigation.js', 'resources/js/modern-search.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @livewireStyles
</head>
<body class="modern-ui">
    <!-- Top Navigation Bar -->
    <x-modern.top-nav :is-client="true" />

    <!-- Sidebar -->
    <x-modern.sidebar :is-client="true" />

    <!-- Main Content Area -->
    <main class="modern-content">
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot }}
        @endif
    </main>

    @livewireScripts
    @stack('scripts')
</body>
</html>

