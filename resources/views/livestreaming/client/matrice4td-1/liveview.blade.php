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
                                <div class="mb-4">
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
                                    <div class="bg-gray-750 rounded-lg p-4 border border-gray-600">
                                        <h4 class="text-sm font-medium text-gray-200 mb-2">Estado de la Transmisión</h4>
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                            <span class="text-xs text-gray-300">Transmisión Activa</span>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gray-750 rounded-lg p-4 border border-gray-600">
                                        <h4 class="text-sm font-medium text-gray-200 mb-2">Duración de Sesión</h4>
                                        <p class="text-xs text-gray-300" id="sessionTimerCard">00:00:00</p>
                                    </div>
                                    
                                    <div class="bg-gray-750 rounded-lg p-4 border border-gray-600">
                                        <h4 class="text-sm font-medium text-gray-200 mb-2">Acciones</h4>
                                        <button onclick="refreshStream()" 
                                                class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded transition-colors">
                                            Refrescar Stream
                                        </button>
                                    </div>
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

    <script>
        // Timer para la sesión de liveview
        let startTime = Date.now();
        
        function updateTimer() {
            const currentTime = Date.now();
            const elapsedTime = currentTime - startTime;
            const seconds = Math.floor((elapsedTime / 1000) % 60);
            const minutes = Math.floor((elapsedTime / (1000 * 60)) % 60);
            const hours = Math.floor((elapsedTime / (1000 * 60 * 60)) % 24);
            
            const timerDisplay = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            document.getElementById('sessionTimer').textContent = timerDisplay;
            document.getElementById('sessionTimerCard').textContent = timerDisplay;
        }
        
        // Actualizar el timer cada segundo
        setInterval(updateTimer, 1000);
        
        // Función para refrescar el stream
        function refreshStream() {
            const iframe = document.getElementById('liveviewFrame');
            iframe.src = iframe.src;
        }
        
        // Manejar errores del iframe
        document.getElementById('liveviewFrame').addEventListener('load', function() {
            console.log('Liveview cargado correctamente');
        });
        
        document.getElementById('liveviewFrame').addEventListener('error', function() {
            console.error('Error al cargar el liveview');
        });
    </script>
@endsection