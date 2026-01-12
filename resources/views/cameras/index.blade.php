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
                                   class="bg-zinc-700 hover:bg-zinc-600 text-gray-200 px-4 py-2 hover:border-bg-blue-700 rounded-lg transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Importar Cámaras
                            </button>
                        </div>
                    </div>

                    <!-- Tabla de Cámaras -->
                    <div class="bg-zinc-700 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-zinc-600">
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
                                <tbody class="bg-zinc-800 divide-y divide-gray-600">
                                    @forelse($cameras as $camera)
                                        <tr class="hover:bg-zinc-750 transition-colors">
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
                                                    <!-- Botón para vincular dispositivo -->
                                                    <button
                                                        onclick="openLinkDeviceModal({{ $camera->id }}, {{ json_encode($camera->camera_name) }})"
                                                        class="p-1.5 rounded-lg text-slate-100 hover:text-white hover:bg-slate-600/70 bg-slate-700/60 transition-colors"
                                                        title="Vincular dispositivo">
                                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor">
                                                            <path d="M8 0q-.264 0-.523.017l.064.998a7 7 0 0 1 .918 0l.064-.998A8 8 0 0 0 8 0M6.44.152q-.52.104-1.012.27l.321.948q.43-.147.884-.237L6.44.153zm4.132.271a8 8 0 0 0-1.011-.27l-.194.98q.453.09.884.237zm1.873.925a8 8 0 0 0-.906-.524l-.443.896q.413.205.793.459zM4.46.824q-.471.233-.905.524l.556.83a7 7 0 0 1 .793-.458zM2.725 1.985q-.394.346-.74.74l.752.66q.303-.345.648-.648zm11.29.74a8 8 0 0 0-.74-.74l-.66.752q.346.303.648.648zm1.161 1.735a8 8 0 0 0-.524-.905l-.83.556q.254.38.458.793l.896-.443zM1.348 3.555q-.292.433-.524.906l.896.443q.205-.413.459-.793zM.423 5.428a8 8 0 0 0-.27 1.011l.98.194q.09-.453.237-.884zM15.848 6.44a8 8 0 0 0-.27-1.012l-.948.321q.147.43.237.884zM.017 7.477a8 8 0 0 0 0 1.046l.998-.064a7 7 0 0 1 0-.918zM16 8a8 8 0 0 0-.017-.523l-.998.064a7 7 0 0 1 0 .918l.998.064A8 8 0 0 0 16 8M.152 9.56q.104.52.27 1.012l.948-.321a7 7 0 0 1-.237-.884l-.98.194zm15.425 1.012q.168-.493.27-1.011l-.98-.194q-.09.453-.237.884zM.824 11.54a8 8 0 0 0 .524.905l.83-.556a7 7 0 0 1-.458-.793zm13.828.905q.292-.434.524-.906l-.896-.443q-.205.413-.459.793zm-12.667.83q.346.394.74.74l.66-.752a7 7 0 0 1-.648-.648zm11.29.74q.394-.346.74-.74l-.752-.66q-.302.346-.648.648zm-1.735 1.161q.471-.233.905-.524l-.556-.83a7 7 0 0 1-.793.458zm-7.985-.524q.434.292.906.524l.443-.896a7 7 0 0 1-.793-.459zm1.873.925q.493.168 1.011.27l.194-.98a7 7 0 0 1-.884-.237zm4.132.271a8 8 0 0 0 1.012-.27l-.321-.948a7 7 0 0 1-.884.237l.194.98zm-2.083.135a8 8 0 0 0 1.046 0l-.064-.998a7 7 0 0 1-.918 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                                                        </svg>
                                                    </button>
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

    <!-- Modal Vincular Dispositivo -->
    <div id="link-device-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-slate-900 rounded-xl shadow-xl w-full max-w-3xl border border-slate-700">
            <div class="flex justify-between items-center px-4 py-3 border-b border-slate-700">
                <h3 class="text-lg font-semibold text-white">
                    Vincular dispositivo a cámara
                </h3>
                <button type="button"
                        onclick="closeLinkDeviceModal()"
                        class="text-gray-400 hover:text-gray-200 rounded-full p-1 hover:bg-slate-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="px-4 py-3 space-y-3">
                <p class="text-sm text-gray-300">
                    Cámara seleccionada:
                    <span id="link-device-camera-name" class="font-semibold text-white"></span>
                </p>
                <div id="link-device-error"
                     class="hidden text-sm text-red-400 bg-red-900/30 border border-red-700/60 rounded px-3 py-2">
                </div>

                <div class="flex items-center justify-between mb-2">
                    
                    <div class="flex items-center gap-2">
                        <input id="link-device-search"
                               type="text"
                               placeholder="Buscar por nombre, modelo o IP..."
                               class="bg-slate-800 border border-slate-600 rounded px-3 py-1 text-sm text-gray-100 focus:outline-none focus:border-purple-500">
                        <button type="button"
                                onclick="loadLinkDevices()"
                                class="px-3 py-1 rounded bg-slate-700 text-xs text-gray-200 hover:bg-slate-600 transition">
                            Buscar
                        </button>
                    </div>
                </div>

                <div class="border border-slate-700 rounded-lg max-h-80 overflow-y-auto">
                    <table class="min-w-full text-sm text-gray-200">
                        <thead class="bg-slate-800 text-xs uppercase text-gray-300">
                            <tr>
                                <th class="px-4 py-2 text-left w-10">Sel.</th>
                                <th class="px-4 py-2 text-left">Nombre</th>
                                <th class="px-4 py-2 text-left">Tipo</th>
                                <th class="px-4 py-2 text-left">Modelo</th>
                                <th class="px-4 py-2 text-left">IP</th>
                                <th class="px-4 py-2 text-left">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody id="link-device-table-body" class="divide-y divide-slate-700">
                            <!-- Filas cargadas por JS -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="flex justify-end gap-2 px-4 py-3 border-t border-slate-700">
                <button type="button"
                        onclick="closeLinkDeviceModal()"
                        class="px-4 py-2 rounded-lg bg-slate-800 text-sm text-gray-200 hover:bg-slate-700 transition">
                    Cancelar
                </button>
                <button type="button"
                        onclick="saveLinkedDevice()"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-sm text-white hover:bg-blue-500 transition">
                    Guardar
                </button>
            </div>
        </div>
    </div>

    <script>
        const LINK_DEVICES_ENDPOINT = '{{ route('api.dispositivos.camaras') }}';
        const LINK_DEVICE_SAVE_ENDPOINT_BASE = '{{ url('/cameras') }}';

        let currentCameraId = null;
        let selectedDispositivoId = null;

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

        function openLinkDeviceModal(cameraId, cameraName) {
            currentCameraId = cameraId;
            selectedDispositivoId = null;
            document.getElementById('link-device-camera-name').textContent = cameraName || '';
            const errorEl = document.getElementById('link-device-error');
            errorEl.classList.add('hidden');
            errorEl.textContent = '';

            document.getElementById('link-device-modal').classList.remove('hidden');

            // Limpiar búsqueda
            const searchInput = document.getElementById('link-device-search');
            if (searchInput) searchInput.value = '';

            // Cargar dispositivos
            loadLinkDevices();
        }

        function closeLinkDeviceModal() {
            document.getElementById('link-device-modal').classList.add('hidden');
        }

        async function loadLinkDevices() {
            const tbody = document.getElementById('link-device-table-body');
            const searchInput = document.getElementById('link-device-search');
            const search = searchInput ? searchInput.value.trim() : '';

            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-4 py-3 text-center text-gray-400">
                        Cargando dispositivos...
                    </td>
                </tr>
            `;

            try {
                const url = new URL(LINK_DEVICES_ENDPOINT, window.location.origin);
                if (search) {
                    url.searchParams.set('search', search);
                }

                const response = await fetch(url.toString());
                if (!response.ok) {
                    throw new Error('Error al cargar dispositivos');
                }

                const dispositivos = await response.json();

                if (!dispositivos.length) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="px-4 py-3 text-center text-gray-400">
                                No hay dispositivos disponibles de tipo camara_ip / camara_ptz.
                            </td>
                        </tr>
                    `;
                    return;
                }

                tbody.innerHTML = dispositivos.map(d => `
                    <tr class="hover:bg-slate-800 cursor-pointer" onclick="selectDispositivo(${d.id})">
                        <td class="px-4 py-2">
                            <input type="radio"
                                   name="dispositivo_id"
                                   value="${d.id}"
                                   ${selectedDispositivoId === d.id ? 'checked' : ''}
                                   onclick="event.stopPropagation(); selectDispositivo(${d.id});">
                        </td>
                        <td class="px-4 py-2">${d.nombre ?? ''}</td>
                        <td class="px-4 py-2 text-xs text-gray-300">${d.tipo}</td>
                        <td class="px-4 py-2 text-xs text-gray-300">${d.modelo ?? ''}</td>
                        <td class="px-4 py-2 text-xs text-gray-300">${d.direccion_ip ?? ''}</td>
                        <td class="px-4 py-2 text-xs text-gray-300">${d.observaciones ?? ''}</td>
                    </tr>
                `).join('');
            } catch (error) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-red-400">
                            ${error.message}
                        </td>
                    </tr>
                `;
            }
        }

        function selectDispositivo(id) {
            selectedDispositivoId = id;
            const radios = document.querySelectorAll('input[name="dispositivo_id"]');
            radios.forEach(r => {
                r.checked = parseInt(r.value) === id;
            });
        }

        async function saveLinkedDevice() {
            const errorEl = document.getElementById('link-device-error');
            errorEl.classList.add('hidden');
            errorEl.textContent = '';

            if (!currentCameraId || !selectedDispositivoId) {
                errorEl.textContent = 'Debe seleccionar un dispositivo para vincular.';
                errorEl.classList.remove('hidden');
                return;
            }

            try {
                const response = await fetch(`${LINK_DEVICE_SAVE_ENDPOINT_BASE}/${currentCameraId}/link-device`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        dispositivo_id: selectedDispositivoId,
                    }),
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    throw new Error(result.message || result.error || 'Error al vincular dispositivo');
                }

                closeLinkDeviceModal();
                // Recargar para reflejar cambios si se muestran en la tabla
                window.location.reload();
            } catch (error) {
                errorEl.textContent = error.message;
                errorEl.classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>