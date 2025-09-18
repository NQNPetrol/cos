<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-600 text-white p-4 rounded-lg mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-600 text-white p-4 rounded-lg mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Header -->
            <div class="bg-slate-900 overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <div class="p-6 text-gray-100">
                    <!-- Header -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-3xl font-bold text-white mb-2">Administrar Cámaras</h2>
                                <p class="text-sm text-gray-300">Gestión de cámaras del sistema HikCentral</p>
                            </div>
                            <button onclick="importCameras()" 
                                   class="bg-gray-700 hover:bg-gray-600 text-gray-200 px-4 py-2 hover:border-bg-blue-700 rounded-lg transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Importar Cámaras
                            </button>
                        </div>
                    </div>

                    <!-- Tabla de Cámaras -->
                    <div class="bg-gray-700 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-600">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Nombre
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Tipo Dispositivo
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Estado
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Capacidades
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-600">
                                    @forelse($cameras as $camera)
                                        <tr class="hover:bg-gray-750 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-white">{{ $camera->camera_name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-300">{{ $camera->camera_index_code }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-300">{{ $camera->dev_resource_type }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-medium rounded {{ $camera->status == 1 ? 'bg-green-900/30 text-green-300 border border-green-600/50' : 'bg-red-900/30 text-red-300 border border-red-600/50' }}">
                                                    {{ $camera->status == 1 ? 'ACTIVA' : 'INACTIVA' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-300">{{ $camera->capability_set ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <!-- Botón para ver detalles -->
                                                    <button onclick="alert('Detalles de la camara:\n\nNombre: {{ $camera->camera_name }}')"
                                                            class="p-1.5 rounded-lg text-blue-400 hover:text-blue-200 hover:bg-blue-200/30 transition-colors" 
                                                            title="Ver detalles">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                    </button>

                                                    <!-- Botón para ver liveview -->
                                                    @if($camera->status == 1)
                                                        <a href="{{ route('cameras.stream', $camera->camera_index_code) }}" 
                                                        target="_blank"
                                                        class="p-1.5 rounded-lg text-green-400 hover:text-green-200 hover:bg-green-200/30 transition-colors"
                                                        title="Ver LiveView">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-gray-400">
                                                No hay cámaras disponibles. Presiona "Importar Cámaras" para cargar desde HikCentral.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Paginación -->
                    @if($cameras->hasPages())
                        <div class="mt-6">
                            {{ $cameras->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        async function importCameras() {
            if (!confirm('¿Está seguro de importar las cámaras desde HikCentral?')) {
                return;
            }

            try {
                const response = await fetch('/api/cameras/import', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    alert('Éxito: ' + result.message);
                    location.reload();
                } else {
                    throw new Error(result.error || 'Error en la importación');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }
    </script>
</x-app-layout>