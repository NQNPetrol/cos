@extends('layouts.cliente')
@section('content')
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
                                    <!-- Selección de Misión -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-3">Seleccionar Misión</label>
                                        <select id="misionSelect" name="mision_id" class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-4 py-3 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition-colors">
                                            <option value="">Seleccione una misión disponible</option>
                                            @foreach($misiones as $mision)
                                                <option value="{{ $mision->id }}">
                                                    {{ $mision->nombre }} 
                                                    @if($mision->cliente)
                                                        - {{ $mision->cliente->nombre }}
                                                    @endif
                                                    @if($mision->drone)
                                                        ({{ $mision->drone->drone }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @if($misiones->isEmpty())
                                            <p class="text-xs text-yellow-400 mt-2">
                                                No hay misiones disponibles para su cliente. Contacte al administrador.
                                            </p>
                                        @else
                                            <p class="text-xs text-gray-400 mt-2">Seleccione la misión que desea desplegar</p>
                                        @endif
                                    </div>

                                    <!-- Información de configuración -->
                                    <div class="bg-gray-750 rounded-lg p-4 border border-gray-600">
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-blue-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div class="flex-1">
                                                <h4 class="text-sm font-medium text-gray-200">Tener en cuenta</h4>
                                                <p class="text-xs text-gray-400 mt-1">Condiciones climáticas entre otros factores inesperados pueden impedir que el drone despliegue.</p>
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
                                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 border border-transparent rounded-lg font-medium text-sm text-white uppercase tracking-widest hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-105"
                                            {{ $misiones->isEmpty() ? 'disabled' : '' }}>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-send-exclamation mr-3" viewBox="0 0 16 16">
                                            <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855a.75.75 0 0 0-.124 1.329l4.995 3.178 1.531 2.406a.5.5 0 0 0 .844-.536L6.637 10.07l7.494-7.494-1.895 4.738a.5.5 0 1 0 .928.372zm-2.54 1.183L5.93 9.363 1.591 6.602z"/>
                                            <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1.5a.5.5 0 0 1-1 0V11a.5.5 0 0 1 1 0m0 3a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"/>
                                        </svg>
                                        Enviar Drone
                                    </button>
                                </div>
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
            const misionId = document.getElementById('misionSelect').value;
            
            // Validar que tenga misión seleccionada
            if (!misionId) {
                showTemporaryAlert('error', 'Debe seleccionar una misión para continuar.');
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

            // Preparar datos para enviar - SIEMPRE será trigger_mision
            const requestData = {
                tipo_alerta: 'trigger_mision',
                mision_id: misionId
            };

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
                console.log('Respuesta recibida del servidor:', data);
                
                if (data.success) {
                    console.log('Has liveview:', data.has_liveview);
                    
                    // Si tiene liveview, mostrar el mensaje especial
                    if (data.has_liveview) {
                        console.log('Condición cumplida - mostrando mensaje');
                        showTriggerSuccessMessage(data.mision_id, data.mision_nombre);
                    } else {
                        console.log('Condición NO cumplida - mostrando alerta temporal');
                        // Para misiones sin liveview, mostrar alerta temporal
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

        // Ocultar mensaje de éxito al cargar la página (para asegurar estado inicial)
        document.addEventListener('DOMContentLoaded', function() {
            hideTriggerSuccessMessage();
        });
    </script>
@endsection