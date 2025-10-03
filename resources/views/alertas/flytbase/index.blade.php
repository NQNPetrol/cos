<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">

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

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Alertas de Flytbase</h2>
                    </div>

                    <!-- Tabla de alertas -->
                    <div class="bg-gray-800 rounded-lg overflow-hidden mb-6">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Misión
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Acción
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-700">
                                <tr class="hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-100">
                                                    Desplegar misión Perímetro Rodial
                                                </div>
                                                <div class="text-sm text-gray-400">
                                                    Misión de vigilancia del perímetro designado
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-800 text-yellow-100">
                                            Lista para desplegar
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button 
                                            id="triggerAlarmBtn"
                                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-normal text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-exclamation" viewBox="0 0 16 16">
                                                <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855a.75.75 0 0 0-.124 1.329l4.995 3.178 1.531 2.406a.5.5 0 0 0 .844-.536L6.637 10.07l7.494-7.494-1.895 4.738a.5.5 0 1 0 .928.372zm-2.54 1.183L5.93 9.363 1.591 6.602z"/>
                                                <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1.5a.5.5 0 0 1-1 0V11a.5.5 0 0 1 1 0m0 3a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"/>
                                            </svg>
                                            _Trigger
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar mensaje de éxito
                    showAlert('success', data.message);
                    
                    // Cambiar temporalmente el botón a éxito
                    button.innerHTML = `
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        ¡Enviado!
                    `;
                    button.classList.remove('bg-red-600', 'hover:bg-red-700');
                    button.classList.add('bg-green-600', 'hover:bg-green-700');
                    
                    // Restaurar después de 3 segundos
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.classList.remove('bg-green-600', 'hover:bg-green-700');
                        button.classList.add('bg-red-600', 'hover:bg-red-700');
                        button.disabled = false;
                    }, 3000);
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                // Mostrar mensaje de error
                showAlert('error', 'Error al enviar la alarma: ' + error.message);
                
                // Restaurar botón
                button.innerHTML = originalText;
                button.disabled = false;
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
            
            // Insertar después del título
            const title = document.querySelector('h2');
            title.parentNode.insertBefore(alertDiv, title.nextSibling);
            
            // Auto-remover después de 5 segundos
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }
    </script>
</x-app-layout>