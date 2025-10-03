<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-5 text-gray-100 dark:text-gray-100">
                    <!-- Mensajes de sesión -->
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-800 border border-green-600 text-green-100 rounded-lg flex items-center justify-between shadow-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button type="button" class="text-green-300 hover:text-white" onclick="this.parentElement.style.display='none'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-800 border border-red-600 text-red-100 rounded-lg flex items-center justify-between shadow-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ session('error') }}</span>
                            </div>
                            <button type="button" class="text-red-300 hover:text-white" onclick="this.parentElement.style.display='none'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    <div class="space-y-6">
                        <!-- Header -->
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

                        <!-- Panel de Configuración de Alertas -->
                        <div class="bg-gray-800 rounded-lg shadow-sm border border-gray-700">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-100 mb-4">Configurar y Enviar Alerta</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    <!-- Tipo de Alerta -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Alerta</label>
                                        <select id="tipoAlerta" class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2">
                                            <option value="trigger_mision">🚀 Trigger Misión</option>
                                            <option value="alerta_tecnica">⚠️ Alerta Técnica</option>
                                            <option value="alerta_hardware">🔧 Alerta Hardware</option>
                                        </select>
                                    </div>

                                    <!-- Token -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-2">Flytbase Token (sin Bearer)</label>
                                        <input type="text" id="flytbaseToken" 
                                            placeholder="Ingresa el token actual"
                                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 placeholder-gray-400">
                                        <p class="text-xs text-gray-400 mt-1">El token expira cada 15 minutos</p>
                                    </div>
                                </div>

                                <!-- Botón Enviar -->
                                <div class="flex justify-end">
                                    <button id="triggerAlarmBtn"
                                            class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-medium text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:border-cyan-800 focus:ring ring-cyan-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-exclamation mr-2" viewBox="0 0 16 16">
                                            <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855a.75.75 0 0 0-.124 1.329l4.995 3.178 1.531 2.406a.5.5 0 0 0 .844-.536L6.637 10.07l7.494-7.494-1.895 4.738a.5.5 0 1 0 .928.372zm-2.54 1.183L5.93 9.363 1.591 6.602z"/>
                                            <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1.5a.5.5 0 0 1-1 0V11a.5.5 0 0 1 1 0m0 3a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"/>
                                        </svg>
                                        Enviar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Historial de Logs -->
                        <div class="bg-gray-800 rounded-lg shadow-sm border border-gray-700">
                            <div class="p-6">
                                <!-- Header con título y botones de filtro -->
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                                    <h3 class="text-lg font-medium text-gray-100">Historial de Alertas</h3>
                                    <div class="flex space-x-2">
                                        <button type="submit" form="filterForm" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                            Filtrar
                                        </button>
                                        <a href="{{ route('alertas.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">
                                            Limpiar
                                        </a>
                                    </div>
                                </div>

                                <!-- Filtros -->
                                <form method="GET" action="{{ route('alertas.index') }}" id="filterForm" class="mb-6">
                                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                        <!-- Tipo -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-1">Tipo</label>
                                            <select name="tipo" class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 text-sm">
                                                <option value="">Todos los tipos</option>
                                                <option value="trigger_mision" {{ request('tipo') == 'trigger_mision' ? 'selected' : '' }}>Trigger Misión</option>
                                                <option value="alerta_tecnica" {{ request('tipo') == 'alerta_tecnica' ? 'selected' : '' }}>Alerta Técnica</option>
                                                <option value="alerta_hardware" {{ request('tipo') == 'alerta_hardware' ? 'selected' : '' }}>Alerta Hardware</option>
                                            </select>
                                        </div>

                                        <!-- Usuario -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-1">Usuario</label>
                                            <select name="usuario" class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 text-sm">
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
                                            <label class="block text-sm font-medium text-gray-300 mb-1">Estado</label>
                                            <select name="exitoso" class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 text-sm">
                                                <option value="">Todos</option>
                                                <option value="1" {{ request('exitoso') === '1' ? 'selected' : '' }}>Exitosas</option>
                                                <option value="0" {{ request('exitoso') === '0' ? 'selected' : '' }}>Con Error</option>
                                            </select>
                                        </div>

                                        <!-- Fecha Desde -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-1">Desde</label>
                                            <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 text-sm">
                                        </div>

                                        <!-- Fecha Hasta -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-1">Hasta</label>
                                            <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 text-sm">
                                        </div>
                                    </div>
                                </form>


                                <!-- Tabla de Logs -->
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-700">
                                        <thead class="bg-gray-700">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tipo</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Usuario</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Fecha/Hora</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Estado</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Código</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                                            @forelse($logs as $log)
                                            <tr class="hover:bg-gray-750 transition-colors">
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-300">#{{ $log->id }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-300">
                                                    @switch($log->tipo_alerta)
                                                        @case('trigger_mision')
                                                            🚀 Trigger Misión
                                                            @break
                                                        @case('alerta_tecnica')
                                                            ⚠️ Alerta Técnica
                                                            @break
                                                        @case('alerta_hardware')
                                                            🔧 Alerta Hardware
                                                            @break
                                                        @default
                                                            {{ $log->tipo_alerta }}
                                                    @endswitch
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-300">{{ $log->user->name }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-300">
                                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    @if($log->exito)
                                                        <span class="px-2 py-1 text-xs font-medium text-green-400 ">
                                                            Exitosa
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-1 text-xs font-medium text-red-400">
                                                            ¡Error!
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-300">
                                                    {{ $log->codigo_respuesta ?: 'N/A' }}
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-400">
                                                    No se encontraron registros de alertas
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Paginación -->
                                @if($logs->hasPages())
                                    <div class="mt-4">
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
            const token = document.getElementById('flytbaseToken').value;
            
            if (!token) {
                showAlert('error', 'Por favor ingresa el token de Flytbase');
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

            // Hacer la petición AJAX
            fetch('{{ route("alertas.trigger-alarm") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    token: token
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    // Recargar la página después de 2 segundos para mostrar el nuevo log
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                showAlert('error', 'Error al enviar la alarma: ' + error.message);
            })
            .finally(() => {
                // Restaurar botón después de 3 segundos
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 3000);
            });
        });

        function showAlert(type, message) {
            // Crear elemento de alerta
            const alertDiv = document.createElement('div');
            alertDiv.className = `mb-6 p-4 ${
                type === 'success' 
                    ? 'bg-green-800 border-green-600 text-green-100' 
                    : 'bg-red-800 border-red-600 text-red-100'
            } border rounded-lg flex items-center justify-between shadow-lg`;
            
            alertDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${
                            type === 'success' 
                                ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                                : 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                        }"/>
                    </svg>
                    <span>${message}</span>
                </div>
                <button type="button" class="text-${type === 'success' ? 'green' : 'red'}-300 hover:text-white" onclick="this.parentElement.remove()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
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
    </script>
</x-app-layout>