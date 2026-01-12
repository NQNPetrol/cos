<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-zinc-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-5 text-gray-100 dark:text-gray-100">
                    <!-- Header -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-100">Cruce de Vehículos</h2>
                                <p class="text-sm text-gray-400 mt-1">Registros de cruce capturados por el sistema ANPR (deteccion de patentes)</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button id="importBtn"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Importar Registros
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <!-- Total Registros -->
                        <div class="bg-gradient-to-br from-gray-800 to-blue-800 rounded-lg p-4 text-white">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium opacity-90">Total Registros</p>
                                    <p class="text-2xl font-bold" id="totalRecords">{{ $totalRecords }}</p>
                                </div>
                                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                                </div>
                            </div>
                        </div>

                        <!-- Hoy -->
                        <div class="bg-gradient-to-br from-gray-800 to-blue-800 rounded-lg p-4 text-white">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium opacity-90">Hoy</p>
                                    <p class="text-2xl font-bold" id="todayRecords">{{ $todayRecords }}</p>
                                </div>
                                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                                </div>
                            </div>
                        </div>

                        <!-- Vehículos Únicos -->
                        <div class="bg-gradient-to-br from-gray-800 to-blue-800 rounded-lg p-4 text-white">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium opacity-90">Vehículos Únicos</p>
                                    <p class="text-2xl font-bold" id="uniquePlates">{{ $uniquePlates }}</p>
                                </div>
                                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                                </div>
                            </div>
                        </div>

                        <!-- Última Importación -->
                        <div class="bg-gradient-to-br from-gray-800 to-blue-800 rounded-lg p-4 text-white">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium opacity-90">Última Importación</p>
                                    <p class="text-sm font-medium opacity-90" id="lastImport">{{ $lastImportTime ?? 'Nunca' }}</p>
                                </div>
                                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="bg-zinc-800 rounded-lg p-6 mb-6 border border-zinc-700">
                        <form id="filterForm" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                            <!-- Filtro de Patente (Dropdown) -->
                            <div>
                                <label for="plate_no" class="block text-sm font-medium text-gray-300 mb-2">Patente</label>
                                <select id="plate_no" name="plate_no" 
                                       class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <option value="">Todas las patentes</option>
                                    @foreach($uniquePlatesList as $plate)
                                        <option value="{{ $plate }}" {{ request('plate_no') == $plate ? 'selected' : '' }}>
                                            {{ $plate }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro de Cámara (Dropdown) -->
                            <div>
                                <label for="camera_index_code" class="block text-sm font-medium text-gray-300 mb-2">Cámara</label>
                                <select id="camera_index_code" name="camera_index_code"
                                       class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <option value="">Todas las cámaras</option>
                                    @foreach($camerasList as $camera)
                                        <option value="{{ $camera }}" {{ request('camera_index_code') == $camera ? 'selected' : '' }}>
                                            @if($camera == '101')
                                                ANPR01-PIN
                                            @else
                                                {{ $camera }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                           
                            <!-- Botón Filtrar -->
                            <div class="flex items-end">
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    Filtrar
                                </button>
                            </div>

                            <!-- Botón Limpiar -->
                            <div class="flex items-end">
                                <button type="button" id="clearFilters"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-zinc-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-zinc-700 focus:outline-none focus:border-zinc-800 focus:ring ring-gray-300 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Limpiar
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Loading -->
                    <div id="loading" class="hidden bg-blue-900/30 border border-blue-800 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-blue-400 font-medium">Importando registros desde la API...</span>
                        </div>
                    </div>

                    <!-- Respuesta JSON -->
                    <div id="jsonResponse" class="hidden bg-zinc-800 rounded-lg p-4 mb-6 border border-zinc-700">
                        <h6 class="text-sm font-medium text-gray-300 mb-2">Respuesta de la API:</h6>
                        <pre id="responseContent" class="text-xs text-gray-200 bg-zinc-900 p-3 rounded border border-zinc-700 overflow-x-auto max-h-64"></pre>
                    </div>

                    <!-- Panel de Registros -->
                    <div class="bg-zinc-800 rounded-lg shadow-sm border border-zinc-700">
                        <div class="p-6">
                            <!-- Tabla de Registros -->
                            <div class="overflow-x-auto rounded-lg border border-zinc-700">
                                <table class="min-w-full divide-y divide-gray-700">
                                    <thead class="bg-zinc-750">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Patente</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Cámara</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Fecha/Hora</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Propietario</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Velocidad</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Dirección</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-zinc-800 divide-y divide-gray-700">
                                        @forelse($records as $record)
                                        <tr class="hover:bg-zinc-750 transition-colors duration-150"
                                            data-patente="{{ strtolower($record->plate_no ?? '') }}"
                                            data-camara="{{ strtolower($record->camera_index_code == '101' ? 'anpr01-pin' : $record->camera_index_code) }}"
                                            data-fecha="{{ $record->cross_time->format('Y-m-d') }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div>
                                                        <div class="text-sm font-medium text-blue-400">{{ $record->plate_no ?? 'Unknown' }}</div>
                                                        <div class="text-xs text-gray-400">Registro: {{ substr($record->cross_record_syscode, 0, 8) }}...</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                @if($record->camera_index_code == '101')
                                                    <div class="flex items-center">
                                                        <span class="text-gray-300 font-medium">ANPR01-PIN</span>
                                                    </div>
                                                @else
                                                    <div class="flex items-center">
                                                        <span class="text-gray-300">{{ $record->camera_index_code }}</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                <div>
                                                    <div class="font-medium">{{ $record->cross_time->format('d/m/Y H:i:s') }}</div>
                                                    <div class="text-xs text-gray-400">{{ $record->cross_time->diffForHumans() }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ $record->owner_name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ $record->vehicle_speed ?? 0 }} km/h
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($record->vehicle_direction_type == 1)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900/30 text-green-400 border border-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                                        </svg>
                                                        Entrada
                                                    </span>
                                                @elseif($record->vehicle_direction_type == 2)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900/30 text-blue-400 border border-blue-800">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                                        </svg>
                                                        Salida
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-700 text-gray-400 border border-zinc-600">
                                                        Desconocido
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                <a href="{{ route('anpr.view-image', $record->id) }}" 
                                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                                title="Ver imagen capturada">
                                                    <i class="bi bi-card-image"></i>
                                                    
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-400">
                                                <svg class="w-12 h-12 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                No se encontraron registros de cruce
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Paginación -->
                            <div class="flex justify-between items-center mt-4 pt-4 border-t border-zinc-700">
                                <div class="text-sm text-gray-400">
                                    Mostrando {{ $records->firstItem() ?? 0 }} - {{ $records->lastItem() ?? 0 }} de {{ $records->total() }} registros
                                </div>
                                <div class="text-gray-300">
                                    {{ $records->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const importBtn = document.getElementById('importBtn');
            const loading = document.getElementById('loading');
            const jsonResponse = document.getElementById('jsonResponse');
            const responseContent = document.getElementById('responseContent');
            const filterForm = document.getElementById('filterForm');
            const clearFiltersBtn = document.getElementById('clearFilters');

            // Elementos de filtro
            const filtroPatente = document.getElementById('plate_no');
            const filtroCamara = document.getElementById('camera_index_code');

            // Event listener solo para patente (frontend)
            filtroPatente.addEventListener('change', filtrarPorPatente);

            // Función de filtrado SOLO por patente (frontend)
            function filtrarPorPatente() {
                const filtroPatenteVal = filtroPatente.value.toLowerCase();
                
                const filas = document.querySelectorAll('tbody tr');

                filas.forEach(fila => {
                    // Verificar si es la fila de "no results"
                    if (fila.querySelector('td[colspan]')) {
                        return; // Saltar fila de "no results"
                    }

                    const patente = fila.getAttribute('data-patente') || '';
                    
                    // Aplicar filtro solo por patente
                    const coincidePatente = !filtroPatenteVal || patente.includes(filtroPatenteVal);

                    if (coincidePatente) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                });
            }

            // Event listener para cámara (backend - recarga página)
            filtroCamara.addEventListener('change', function() {
                // Crear formulario temporal para enviar al backend
                const formData = new FormData();
                if (filtroCamara.value) {
                    formData.append('camera_index_code', filtroCamara.value);
                }
                if (filtroPatente.value) {
                    formData.append('plate_no', filtroPatente.value);
                }
                
                const params = new URLSearchParams(formData);
                window.location.href = '{{ route("anpr.index") }}?' + params.toString();
            });

            // Limpiar filtros CORREGIDO para sistema híbrido
            clearFiltersBtn.addEventListener('click', function() {
                // Redirigir a la página sin parámetros (limpia todo)
                window.location.href = '{{ route("anpr.index") }}';
            });

            // Prevenir el envío del formulario normal
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                // No hacer nada, manejamos todo con los event listeners individuales
            });

            // Aplicar filtro de patente al cargar si hay valor en el dropdown
            if (filtroPatente.value) {
                filtrarPorPatente();
            }

            // Importar registros (mantener tu código actual)
            importBtn.addEventListener('click', function() {
                const originalHtml = importBtn.innerHTML;
                importBtn.disabled = true;
                importBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Importando...
                `;
                loading.classList.remove('hidden');
                jsonResponse.classList.add('hidden');

                fetch('{{ route("anpr.import") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    responseContent.textContent = JSON.stringify(data, null, 2);
                    jsonResponse.classList.remove('hidden');

                    if (data.success) {
                        showAlert('success', '¡Importación exitosa! ' + data.message);
                        
                        if (data.data) {
                            document.getElementById('totalRecords').textContent = data.data.total_records || '{{ $totalRecords }}';
                            document.getElementById('todayRecords').textContent = data.data.today_records || '{{ $todayRecords }}';
                            document.getElementById('uniquePlates').textContent = data.data.unique_plates || '{{ $uniquePlates }}';
                            document.getElementById('lastImport').textContent = 'Ahora mismo';
                        }
                        
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showAlert('error', 'Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'Error en la importación: ' + error.message);
                })
                .finally(() => {
                    importBtn.disabled = false;
                    importBtn.innerHTML = originalHtml;
                    loading.classList.add('hidden');
                });
            });

            function showAlert(type, message) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `fixed top-4 right-4 p-4 rounded-lg border z-50 animate-slideDown ${
                    type === 'success' 
                        ? 'bg-green-900/30 border-green-800 text-green-400' 
                        : 'bg-red-900/30 border-red-800 text-red-400'
                }`;
                alertDiv.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${type === 'success' 
                                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                            }
                        </svg>
                        <span>${message}</span>
                    </div>
                `;
                
                document.body.appendChild(alertDiv);
                
                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            }
        });
    </script>

</x-app-layout>