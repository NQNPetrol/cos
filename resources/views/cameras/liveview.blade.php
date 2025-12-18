<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-slate-900 overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <div class="p-6 text-gray-100">
                    <!-- Header con título y botones -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-3xl font-bold text-white mb-2">Vista en vivo de la cámara</h2>
                                <p class="text-sm text-gray-300">{{ $camera->camera_name }} - ID: {{ $camera->camera_index_code }}</p>
                            </div>
                            <div class="flex space-x-3">
                                <a href="https://central.cyhsur.com/" 
                                   target="_blank"
                                   class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    Ir a HikCentral
                                </a>
                                <a href="{{ route('cameras.index') }}" 
                                   class="bg-zinc-700 hover:bg-zinc-600 text-gray-200 px-4 py-2 rounded-lg transition-colors flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                    Volver a Cámaras
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Contenedor del video -->
                    <div class="bg-zinc-800 rounded-lg p-4 mb-6">
                        <div class="flex justify-center items-center min-h-96">
                            @if($camera->stream && $camera->stream->url && $camera->status == 1)
                                <!-- Video en vivo -->
                                <div class="w-full max-w-4xl">
                                    <h3 class="text-lg font-semibold text-white mb-3 text-center">Liveview</h3>
                                    <div class="bg-black rounded-lg overflow-hidden">
                                        <iframe 
                                            src="{{ $camera->stream->url }}"
                                            class="w-full h-96"
                                            frameborder="0"
                                            allowfullscreen
                                            allow="autoplay; encrypted-media"
                                            title="Live View - {{ $camera->camera_name }}"
                                        ></iframe>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <p class="text-sm text-green-400">
                                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Conectado - Stream activo
                                        </p>
                                    </div>
                                </div>
                            @elseif($camera->status != 1)
                                <!-- Cámara inactiva -->
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <h3 class="text-xl font-semibold text-red-400 mb-2">Cámara Inactiva</h3>
                                    <p class="text-gray-300 mb-4">Esta cámara está actualmente fuera de línea o deshabilitada.</p>
                                    <div class="text-sm text-gray-400">
                                        <p>Estado: <span class="text-red-400">INACTIVA</span></p>
                                        <p>ID: {{ $camera->camera_index_code }}</p>
                                    </div>
                                </div>
                            @else
                                <!-- Sin URL de stream -->
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 text-yellow-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <h3 class="text-xl font-semibold text-yellow-400 mb-2">Stream No Disponible</h3>
                                    <p class="text-gray-300 mb-4">No hay URL de streaming configurada para esta cámara.</p>
                                    <div class="text-sm text-gray-400">
                                        <p>Estado: <span class="text-green-400">ACTIVA</span> pero sin stream</p>
                                        <p>ID: {{ $camera->camera_index_code }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Información de la cámara -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-zinc-700 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-white mb-3">Información de la Cámara</h3>
                            <div class="space-y-2">
                                <p><span class="text-gray-400">Nombre:</span> {{ $camera->camera_name }}</p>
                                <p><span class="text-gray-400">ID:</span> {{ $camera->camera_index_code }}</p>
                                <p><span class="text-gray-400">Estado:</span> 
                                    <span class="px-2 py-1 text-xs rounded {{ $camera->status == 1 ? 'bg-green-900/30 text-green-300' : 'bg-red-900/30 text-red-300' }}">
                                        {{ $camera->status == 1 ? 'ACTIVA' : 'INACTIVA' }}
                                    </span>
                                </p>
                                <p><span class="text-gray-400">Capacidades:</span> {{ $camera->capability_set ?? 'N/A' }}</p>
                                @if($camera->encodingDevice)
                                    <p><span class="text-gray-400">Dispositivo:</span> {{ $camera->encodingDevice->name }}</p>
                                    <p><span class="text-gray-400">IP:</span> {{ $camera->encodingDevice->ip }}:{{ $camera->encodingDevice->port }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="bg-zinc-700 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-white mb-3">Información de Streaming</h3>
                            <div class="space-y-2">
                                @if($camera->stream && $camera->stream->url)
                                    <p><span class="text-gray-400">URL:</span> 
                                        <span class="text-sm text-blue-300 break-all">{{ Str::limit($camera->stream->url, 50) }}</span>
                                    </p>
                                    <p><span class="text-gray-400">Protocolo:</span> {{ $camera->stream->protocol ?? 'RTSP' }}</p>
                                    <p><span class="text-gray-400">Estado Stream:</span> 
                                        <span class="px-2 py-1 text-xs rounded {{ $camera->stream->is_active ? 'bg-green-900/30 text-green-300' : 'bg-red-900/30 text-red-300' }}">
                                            {{ $camera->stream->is_active ? 'ACTIVO' : 'INACTIVO' }}
                                        </span>
                                    </p>
                                    <p><span class="text-gray-400">Actualizado:</span> {{ $camera->stream->last_updated->diffForHumans() ?? 'Nunca' }}</p>
                                @else
                                    <p class="text-yellow-300">No hay información de streaming disponible</p>
                                    <p class="text-sm text-gray-400">Use el botón "Importar URLs" para obtener las URLs de streaming</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Acciones adicionales -->
                    <div class="mt-6 flex justify-center space-x-4">
                        <button onclick="refreshStream()" 
                               class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Actualizar Stream
                        </button>
                        
                        <a href="/api/cameras/import-streams" 
                           class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Importar URLs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function refreshStream() {
            const iframe = document.querySelector('iframe');
            if (iframe) {
                iframe.src = iframe.src;
            }
            alert('Stream actualizado');
        }

        // Auto-refresh cada 30 segundos si hay stream
        @if($camera->stream && $camera->stream->url && $camera->status == 1)
            setInterval(refreshStream, 30000);
        @endif
    </script>
</x-app-layout>