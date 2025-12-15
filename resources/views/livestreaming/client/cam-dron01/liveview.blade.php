@extends('layouts.cliente')
@section('content')
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-5 text-gray-100 dark:text-gray-100">
                    <div class="space-y-8">
                        <!-- Header -->
                        <div class="flex justify-between items-center">
                            <div>
                                <a href="{{ route('client.alertas.index') }}" 
                                class="flex items-center text-blue-400 hover:text-blue-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                    Volver a Misiones
                                </a>
                                <h2 class="text-2xl font-semibold text-gray-100">Drone Streaming</h2>
                                <p class="text-sm text-gray-400 mt-1">
                                    Tramisión en vivo de la misión desplegada
                                </p>
                            </div>
                        </div>

        <!-- Contenedor del Liveview -->
        <div class="bg-gray-800 rounded-lg shadow-sm border border-gray-700">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-100 mb-2">
                            @if(isset($drone))
                                {{ $drone->drone }} - 
                            @endif
                            @if(isset($mision))
                                Misión: {{ $mision->nombre }}
                            @else
                                Cámara en Vivo
                            @endif
                        </h3>
                        <p class="text-sm text-gray-400">
                            Esperar a que el drone comience el vuelo para ver la imagen. Si no carga, refresque la página.
                        </p>
                    </div>

                    <div class="flex space-x-2">
                        <button onclick="expandToModal()" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5"/>
                            </svg>
                            Expandir
                        </button>
                    </div>
                </div>

                <!-- Contenedor del iframe -->
                <div class="bg-black rounded-lg overflow-hidden border border-gray-600">
                    <iframe 
                        src="{{ $flytbaseGuestUrl ?? $drone->share_url ?? 'https://console.flytbase.com/guest-sharing/your-stream-url' }}"
                        width="100%" 
                        height="600" 
                        frameborder="0" 
                        allowfullscreen
                        class="w-full"
                        id="liveviewFrame">
                    </iframe>
                </div>

                                <!-- Información y controles -->
                                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                                
                                   
                                </div>

                                <!-- Mensaje de información -->
                                <div class="mt-6 bg-blue-900/30 border border-blue-700 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div>
                                            <h4 class="text-sm font-medium text-blue-200">Información importante</h4>
                                            <p class="text-xs text-blue-300 mt-1">
                                                Esta transmisión utiliza el servicio de Guest Sharing de FlytBase. 
                                                La calidad y disponibilidad del stream dependen de la conexión del drone 
                                                y las condiciones de la red.
                                            </p>
                                            <p class="text-xs text-blue-300 mt-1">
                                                Si experimenta problemas de conexión, verifique que el enlace de Guest Sharing 
                                                esté activo en la consola de FlytBase o comuníquese a cos.support@cyhsur.com
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para vista expandida (cliente) -->
    <div id="expandedModal" class="fixed inset-0 bg-black bg-opacity-90 hidden" style="z-index: 10000;">
        <div class="flex flex-col h-full">
            <!-- Header del modal -->
            <div class="flex justify-between items-center p-4 bg-gray-900">
                <h3 class="text-lg font-medium text-white">
                    Stream en Vivo - {{ $drone->drone ?? 'Drone' }}
                </h3>
                <div class="flex space-x-2">
                    <button onclick="toggleFullscreenModal()" 
                            class="inline-flex items-center px-3 py-2 bg-gray-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-gray-700 transition">
                        <i class="bi bi-arrows-fullscreen"></i>
                    </button>
                    <button onclick="closeExpandedModal()" 
                            class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-blue-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Contenedor del stream en modal -->
            <div class="flex-1 bg-black p-4">
                <iframe 
                    src="{{ $flytbaseGuestUrl ?? $drone->share_url ?? 'https://console.flytbase.com/guest-sharing/your-stream-url' }}"
                    width="100%" 
                    height="100%" 
                    frameborder="0" 
                    allowfullscreen
                    id="modalFrame">
                </iframe>
            </div>
        </div>
    </div>

    <script>
        // Timer para la sesión de liveview (solo si existen los elementos)
        let startTime = Date.now();
        const sessionTimerEl = document.getElementById('sessionTimer');
        const sessionTimerCardEl = document.getElementById('sessionTimerCard');
        
        function updateTimer() {
            // Si no hay elementos donde mostrar, no hacer nada
            if (!sessionTimerEl && !sessionTimerCardEl) {
                return;
            }

            const currentTime = Date.now();
            const elapsedTime = currentTime - startTime;
            const seconds = Math.floor((elapsedTime / 1000) % 60);
            const minutes = Math.floor((elapsedTime / (1000 * 60)) % 60);
            const hours = Math.floor((elapsedTime / (1000 * 60 * 60)) % 24);
            
            const timerDisplay = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (sessionTimerEl) {
                sessionTimerEl.textContent = timerDisplay;
            }
            
            if (sessionTimerCardEl) {
                sessionTimerCardEl.textContent = timerDisplay;
            }
        }
        
        // Actualizar el timer cada segundo solo si hay al menos un elemento objetivo
        if (sessionTimerEl || sessionTimerCardEl) {
            setInterval(updateTimer, 1000);
        }
        
        // Función para refrescar el stream
        function refreshStream() {
            const iframe = document.getElementById('liveviewFrame');
            iframe.src = iframe.src;
        }
        
        // Funciones para el modal expandido (mismo comportamiento que admin)
        function expandToModal() {
            const modal = document.getElementById('expandedModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Ocultar sidebar y top bar mientras el modal está abierto (para clientes)
            const sidebar = document.getElementById('modernSidebar');
            const topNav = document.querySelector('.modern-top-nav');
            if (sidebar) {
                sidebar.dataset.originalDisplay = sidebar.style.display || '';
                sidebar.style.display = 'none';
            }
            if (topNav) {
                topNav.dataset.originalDisplay = topNav.style.display || '';
                topNav.style.display = 'none';
            }
        }
        
        function closeExpandedModal() {
            const modal = document.getElementById('expandedModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            
            // Salir de pantalla completa si está activa
            if (document.fullscreenElement) {
                document.exitFullscreen();
            }

            // Restaurar visibilidad del sidebar y top bar
            const sidebar = document.getElementById('modernSidebar');
            const topNav = document.querySelector('.modern-top-nav');
            if (sidebar) {
                sidebar.style.display = sidebar.dataset.originalDisplay || '';
            }
            if (topNav) {
                topNav.style.display = topNav.dataset.originalDisplay || '';
            }
        }
        
        // Función para pantalla completa del modal
        function toggleFullscreenModal() {
            const modal = document.getElementById('expandedModal');
            
            if (!document.fullscreenElement) {
                if (modal.requestFullscreen) {
                    modal.requestFullscreen();
                } else if (modal.webkitRequestFullscreen) {
                    modal.webkitRequestFullscreen();
                } else if (modal.msRequestFullscreen) {
                    modal.msRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
        }

        // Escuchar cambios en el estado de pantalla completa (opcional)
        document.addEventListener('fullscreenchange', handleFullscreenChange);
        document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
        document.addEventListener('msfullscreenchange', handleFullscreenChange);
        
        function handleFullscreenChange() {
            console.log('Estado de pantalla completa cambiado');
        }
        
        // Cerrar modal con tecla Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeExpandedModal();
            }
        });
        
        // Manejar errores del iframe
        document.getElementById('liveviewFrame').addEventListener('load', function() {
            console.log('Liveview cargado correctamente');
        });
        
        document.getElementById('liveviewFrame').addEventListener('error', function() {
            console.error('Error al cargar el liveview');
        });
    </script>
@endsection