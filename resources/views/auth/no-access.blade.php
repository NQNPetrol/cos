<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sin Acceso - Centro de Operaciones</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-zinc-900 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="bg-zinc-800 rounded-2xl shadow-xl p-8 text-center border border-zinc-700">
            <!-- Icon -->
            <div class="mx-auto w-20 h-20 bg-amber-500/10 rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            
            <!-- Title -->
            <h1 class="text-2xl font-bold text-white mb-2">Acceso Pendiente</h1>
            
            <!-- Message -->
            <p class="text-zinc-400 mb-6">
                Tu cuenta ha sido creada pero aún no tienes permisos asignados para acceder al sistema.
            </p>
            
            <!-- Info Box -->
            <div class="bg-zinc-700/50 rounded-lg p-4 mb-6 text-left">
                <p class="text-sm text-zinc-300 mb-2">
                    <strong class="text-white">¿Qué hacer?</strong>
                </p>
                <ul class="text-sm text-zinc-400 space-y-1">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Contacta al administrador de tu organización</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Envía un correo a soporte técnico</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Espera a que se te asignen los permisos necesarios</span>
                    </li>
                </ul>
            </div>
            
            <!-- User Info -->
            <div class="bg-zinc-700/30 rounded-lg p-3 mb-6">
                <p class="text-xs text-zinc-500 mb-1">Conectado como:</p>
                <p class="text-sm text-zinc-300 font-medium">{{ auth()->user()->email ?? 'Usuario' }}</p>
            </div>
            
            <!-- Actions -->
            <div class="flex flex-col gap-3">
                <a href="mailto:soporte@cyhsur.com" 
                   class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Contactar Soporte
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full bg-zinc-700 hover:bg-zinc-600 text-zinc-300 font-medium py-2.5 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Footer -->
        <p class="text-center text-zinc-600 text-xs mt-6">
            Centro de Operaciones de Seguridad &copy; {{ date('Y') }}
        </p>
    </div>
</body>
</html>
