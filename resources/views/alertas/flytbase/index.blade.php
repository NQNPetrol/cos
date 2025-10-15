<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-5 text-gray-100 dark:text-gray-100">
                    <!-- Header -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <h2 class="text-2xl font-semibold text-gray-100">Administrar Alertas Flytbase</h2>
                            <a href="https://console.flytbase.com" target="_blank" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 transition ease-in-out duration-150">
                                Flytbase Console
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="space-y-8 mt-4">
                        <!-- Panel de Configuración de Alertas -->
                        <div class="bg-gray-800 rounded-lg shadow-sm border border-gray-700">
                            <div class="p-6">
                                <div class="space-y-6">
                                    <!-- Tipo de Alerta -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-3">Tipo de Alerta</label>
                                        <select id="tipoAlerta" class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-4 py-3 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition-colors">
                                            <option value="" selected disabled>Seleccione un tipo de alerta...</option>
                                            <option value="trigger_mision">🚀 Trigger Misión</option>
                                            <option value="alerta_tecnica">⚠️ Alerta Técnica</option>
                                            <option value="alerta_hardware">🔧 Alerta Hardware</option>
                                        </select>
                                        <p class="text-xs text-gray-400 mt-2">Selecciona el tipo de alerta a enviar</p>
                                    </div>

                                    <div id="misionContainer" class="hidden transition-all duration-300">
                                        <label class="block text-sm font-medium text-gray-300 mb-3">Misión a realizar</label>
                                        <select id="misionSelect" name="mision_id" class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-4 py-3 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition-colors">
                                            <option value="">Seleccione una misión</option>
                                            @foreach($misiones as $mision)
                                                <option value="{{ $mision->id }}">
                                                    {{ $mision->nombre }} - {{ $mision->cliente->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="text-xs text-gray-400 mt-2">Seleccione la misión que desea desplegar</p>
                                    </div>

                                    <!-- Información de configuración -->
                                    <div class="bg-gray-750 rounded-lg p-4 border border-gray-600">
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-blue-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div class="flex-1">
                                                <h4 class="text-sm font-medium text-gray-200">Tener en cuenta</h4>
                                                <p class="text-xs text-gray-400 mt-1">Una vez mandada un alerta de tipo "Trigger Mision", esta debe ser revisada por un operador quien se encargará de aprobar el despliegue del drone a la mision seleccionada.</p>
                                                <p class="text-xs text-gray-400 mt-1">En caso de que salte un error, comunicarse con el soporte del COS (cos.support@cyhsur.com)</p>
                                                
                                                <!-- Mensaje dinámico para trigger misión exitoso -->
                                                <div id="triggerSuccessMessage" class="hidden mt-4 p-3 bg-green-900/30 border border-green-700 rounded-lg">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex-1">
                                                            <p class="text-xs text-green-300 font-medium mb-2">
                                                                Misión desplegada exitosamente. El dron está en camino.
                                                            </p>
                                                            <div class="flex items-center space-x-3">
                                                                <a id="liveviewButton" href="#" 
                                                                    class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 transition ease-in-out duration-150">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                                    </svg>
                                                                    Ver Streaming del Drone
                                                                </a>
                                                                <button type="button" onclick="hideTriggerSuccessMessage()" 
                                                                        class="text-green-300 hover:text-white transition-colors duration-200">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botón Enviar -->
                                <div class="flex justify-end mt-8 pt-6 border-t border-gray-700">
                                    <button id="triggerAlarmBtn"
                                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 border border-transparent rounded-lg font-medium text-sm text-white uppercase tracking-widest hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-105">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-send-exclamation mr-3" viewBox="0 0 16 16">
                                            <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855a.75.75 0 0 0-.124 1.329l4.995 3.178 1.531 2.406a.5.5 0 0 0 .844-.536L6.637 10.07l7.494-7.494-1.895 4.738a.5.5 0 1 0 .928.372zm-2.54 1.183L5.93 9.363 1.591 6.602z"/>
                                            <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1.5a.5.5 0 0 1-1 0V11a.5.5 0 0 1 1 0m0 3a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"/>
                                        </svg>
                                        Enviar Alerta
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- El resto del código de Historial de Logs se mantiene igual -->
                        <!-- Historial de Logs -->
                        <div class="bg-gray-800 rounded-lg shadow-sm border border-gray-700 mt-8">
                            <div class="p-6">
                                <!-- Header con título y botones de filtro -->
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-100">Historial de Alertas</h3>
                                        <p class="text-sm text-gray-400 mt-1">Registro de todas las alertas enviadas al sistema</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button type="submit" form="filterForm" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                                            </svg>
                                            Filtrar
                                        </button>
                                        <a href="{{ route('alertas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Limpiar
                                        </a>
                                    </div>
                                </div>

                                <!-- Filtros -->
                                <form method="GET" action="{{ route('alertas.index') }}" id="filterForm" class="mb-8">
                                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                        <!-- Tipo -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Tipo</label>
                                            <select name="tipo" class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                                <option value="">Todos los tipos</option>
                                                <option value="trigger_mision" {{ request('tipo') == 'trigger_mision' ? 'selected' : '' }}>Trigger Misión</option>
                                                <option value="alerta_tecnica" {{ request('tipo') == 'alerta_tecnica' ? 'selected' : '' }}>Alerta Técnica</option>
                                                <option value="alerta_hardware" {{ request('tipo') == 'alerta_hardware' ? 'selected' : '' }}>Alerta Hardware</option>
                                            </select>
                                        </div>

                                        <!-- Usuario -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Usuario</label>
                                            <select name="usuario" class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                                <option value="">Todos los usuarios</option>
                                                @foreach($usuarios as $usuario)
                                                    <option value="{{ $usuario->id }}" {{ request('usuario') == $usuario->id ? 'selected' : '' }}>
                                                        {{ $usuario->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Estado -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Estado</label>
                                            <select name="exitoso" class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                                <option value="">Todos</option>
                                                <option value="1" {{ request('exitoso') === '1' ? 'selected' : '' }}>Exitosas</option>
                                                <option value="0" {{ request('exitoso') === '0' ? 'selected' : '' }}>Con Error</option>
                                            </select>
                                        </div>

                                        <!-- Fecha Desde -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Desde</label>
                                            <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        </div>

                                        <!-- Fecha Hasta -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Hasta</label>
                                            <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        </div>
                                    </div>
                                </form>

                                <!-- Tabla de Logs -->
                                <div class="overflow-x-auto rounded-lg border border-gray-700">
                                    <table class="min-w-full divide-y divide-gray-700">
                                        <thead class="bg-gray-750">
                                            <tr>
                                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tipo</th>
                                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Usuario</th>
                                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Fecha/Hora</th>
                                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Estado</th>
                                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Código</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                                            @forelse($logs as $log)
                                            <tr class="hover:bg-gray-750 transition-colors duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-300">#{{ $log->id }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                    @switch($log->tipo_alerta)
                                                        @case('trigger_mision')
                                                            <span class="inline-flex items-center">
                                                                🚀 Trigger Misión
                                                            </span>
                                                            @break
                                                        @case('alerta_tecnica')
                                                            <span class="inline-flex items-center">
                                                                ⚠️ Alerta Técnica
                                                            </span>
                                                            @break
                                                        @case('alerta_hardware')
                                                            <span class="inline-flex items-center">
                                                                🔧 Alerta Hardware
                                                            </span>
                                                            @break
                                                        @default
                                                            {{ $log->tipo_alerta }}
                                                    @endswitch
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $log->user->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($log->exito)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-900/30 text-green-400 border border-green-800">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Exitosa
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-900/30 text-red-400 border border-red-800">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Error
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-300">
                                                    {{ $log->codigo_respuesta ?: 'N/A' }}
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-400">
                                                    <svg class="w-12 h-12 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    No se encontraron registros de alertas
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Paginación -->
                                @if($logs->hasPages())
                                    <div class="mt-6">
                                        {{ $logs->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para manejar el trigger -->
    <script>
        document.getElementById('triggerAlarmBtn').addEventListener('click', function() {
            const button = this;
            const originalText = button.innerHTML;
            const tipoAlerta = document.getElementById('tipoAlerta').value;
            const misionId = document.getElementById('misionSelect').value;
            
            // Validar que si es trigger_mision, tenga misión seleccionada
            if (tipoAlerta === 'trigger_mision' && !misionId) {
                showAlert('error', 'Debe seleccionar una misión para continuar.');
                return;
            }
            
            // Mostrar loading
            button.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Enviando...
            `;
            button.disabled = true;

            // Preparar datos para enviar
            const requestData = {
                tipo_alerta: tipoAlerta
            };

            if (tipoAlerta === 'trigger_mision') {
                requestData.mision_id = misionId;
            }

            // Hacer la petición AJAX
            fetch('{{ route("alertas.trigger-alarm") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta recibida del servidor:', data); // ← AGREGAR ESTO
                
                if (data.success) {
                    console.log('Tipo de alerta:', tipoAlerta); // ← AGREGAR ESTO
                    console.log('Has liveview:', data.has_liveview); // ← AGREGAR ESTO
                    
                    // Si es trigger_mision exitoso, mostrar el mensaje en la tarjeta
                    if (tipoAlerta === 'trigger_mision' && data.has_liveview) {
                        console.log('Condición cumplida - mostrando mensaje'); // ← AGREGAR ESTO
                        showTriggerSuccessMessage(data.mision_id, data.mision_nombre);
                    } else {
                        console.log('Condición NO cumplida - mostrando alerta temporal'); // ← AGREGAR ESTO
                        // Para otros tipos de alerta, mostrar alerta temporal
                        showTemporaryAlert('success', data.message);
                    }
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                showTemporaryAlert('error', 'Error al enviar la alarma: ' + error.message);
            })
            .finally(() => {
                // Restaurar botón después de 3 segundos
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 3000);
            });
        });

        function showTriggerSuccessMessage(misionId, misionNombre) {
            const successMessage = document.getElementById('triggerSuccessMessage');
            const liveviewButton = document.getElementById('liveviewButton');
            
            console.log('Elemento successMessage:', successMessage); 
            console.log('Elemento liveviewButton:', liveviewButton); 
            
            // Actualizar el enlace del botón
            liveviewButton.href = "{{ route('alertas.liveview') }}?mision_id=" + misionId;
            
            // Mostrar el mensaje
            console.log('Clases antes:', successMessage.className); 
            successMessage.classList.remove('hidden');
            successMessage.classList.add('block');
            console.log('Clases después:', successMessage.className);
            
            console.log('Mensaje de éxito mostrado para misión:', misionNombre);
        }


        function hideTriggerSuccessMessage() {
            const successMessage = document.getElementById('triggerSuccessMessage');
            successMessage.classList.remove('block');
            successMessage.classList.add('hidden');
        }

        function showTemporaryAlert(type, message) {
            // Crear elemento de alerta temporal
            const alertDiv = document.createElement('div');
            alertDiv.className = `mb-6 p-4 ${
                type === 'success' 
                    ? 'bg-green-800 border-green-600 text-green-100' 
                    : 'bg-red-800 border-red-600 text-red-100'
            } border rounded-lg flex items-center justify-between shadow-lg`;
            
            alertDiv.innerHTML = `
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${
                                type === 'success' 
                                    ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                                    : 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                            }"/>
                        </svg>
                        <span class="text-sm font-medium">${message}</span>
                    </div>
                    <button type="button" class="text-${type === 'success' ? 'green' : 'red'}-300 hover:text-white" onclick="this.parentElement.parentElement.remove()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            
            // Insertar después del header
            const header = document.querySelector('h2');
            header.parentNode.insertBefore(alertDiv, header.nextSibling);
            
            // Auto-remover después de 5 segundos
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }

        // Manejar cambio en el tipo de alerta
        document.getElementById('tipoAlerta').addEventListener('change', function() {
            const misionContainer = document.getElementById('misionContainer');
            const misionSelect = document.getElementById('misionSelect');
            
            if (this.value === 'trigger_mision') {
                misionContainer.classList.remove('hidden');
                misionContainer.classList.add('block');
                misionSelect.required = true;
            } else {
                misionContainer.classList.remove('block');
                misionContainer.classList.add('hidden');
                misionSelect.required = false;
                misionSelect.value = '';
            }
        });

        // Ocultar mensaje de éxito al cargar la página (para asegurar estado inicial)
        document.addEventListener('DOMContentLoaded', function() {
            hideTriggerSuccessMessage();
        });
    </script>
</x-app-layout>