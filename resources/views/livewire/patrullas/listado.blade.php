<div class="bg-gray-900 text-gray-100 p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Listado de Patrullas</h2>
        <div class="flex space-x-3">
            <!-- Botón Importar Patrullas (Solo para admin) -->
            @if(auth()->user()->hasRole('admin'))
                <button onclick="importarPatrullas()" 
                        class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white font-medium">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                    </svg>
                    Importar Patrullas
                </button>
            @endif
            
            <button wire:click="openModal"
            class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded text-white font-medium">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Patrulla
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm mb-1">Buscar</label>
            <input type="text" wire:model.live="search"
                   placeholder="Patente, marca, modelo..."
                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
        </div>
        
        <div>
            <label class="block text-sm mb-1">Estado</label>
            <select wire:model.live="estadoFilter" 
                    class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                <option value="">Todos</option>
                <option value="operativa">Operativa</option>
                <option value="mantenimiento">En mantenimiento</option>
                <option value="baja">Dada de baja</option>
            </select>
        </div>
    </div>

    <!-- Tabla -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-800 text-gray-300">
                <tr>
                    <th class="px-4 py-2 text-left">Patente</th>
                    <th class="px-4 py-2 text-left">Marca</th>
                    <th class="px-4 py-2 text-left">Modelo</th>
                    <th class="px-4 py-2 text-left">Color</th>
                    <th class="px-4 py-2 text-left">Estado</th>
                    <th class="px-4 py-2 text-left">Observaciones</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($patrullas as $patrulla)
                    <tr class="border-b border-gray-700 hover:bg-gray-800">
                        <td class="px-4 py-2 font-medium">{{ $patrulla->patente }}</td>
                        <td class="px-4 py-2">{{ $patrulla->marca }}</td>
                        <td class="px-4 py-2">{{ $patrulla->modelo }}</td>
                        <td class="px-4 py-2">{{ $patrulla->color }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ 
                                $patrulla->estado == 'operativa' ? 'bg-green-100 text-green-800' : 
                                ($patrulla->estado == 'mantenimiento' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') 
                            }}">
                                {{ ucfirst($patrulla->estado) }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ $patrulla->observaciones }}</td>
                        <td class="px-4 py-2">
                            <div class="flex space-x-3">
                                <a href="{{ route('patrullas.dispositivos', $patrulla->id) }}" 
                                   class="text-emerald-400 hover:text-emerald-300"
                                   title="Dispositivos">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('patrullas.location') }}" 
                                   class="text-orange-400 hover:text-orange-300"
                                   title="Ubicacion">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pin-map" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M3.1 11.2a.5.5 0 0 1 .4-.2H6a.5.5 0 0 1 0 1H3.75L1.5 15h13l-2.25-3H10a.5.5 0 0 1 0-1h2.5a.5.5 0 0 1 .4.2l3 4a.5.5 0 0 1-.4.8H.5a.5.5 0 0 1-.4-.8z"/>
                                        <path fill-rule="evenodd" d="M8 1a3 3 0 1 0 0 6 3 3 0 0 0 0-6M4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999z"/>
                                    </svg>
                                </a>
                                <button wire:click="edit({{ $patrulla->id }})" 
                                        class="text-blue-400 hover:text-blue-300"
                                        title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $patrulla->id }})"
                                        onclick="return confirm('¿Eliminar esta patrulla?')"
                                        class="text-red-400 hover:text-red-300"
                                        title="Eliminar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center px-4 py-8 text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <p>No se encontraron patrullas</p>
                                <p class="text-sm">Intenta ajustar los filtros o agrega una nueva patrulla</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $patrullas->links() }}
    </div>

    <!-- Modal -->
     @if($showModal)
        <div wire:click.self="closeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-900 rounded-lg p-6 w-full max-w-2xl" @click.stop>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-100">
                        {{ $editingId ? 'Editar Patrulla' : 'Nueva Patrulla' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm mb-1 text-gray-300">Patente <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="patente" 
                                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                            @error('patente') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm mb-1 text-gray-300">Marca</label>
                            <input type="text" wire:model="marca" 
                                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                            @error('marca') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm mb-1 text-gray-300">Modelo</label>
                            <input type="text" wire:model="modelo" 
                                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                            @error('modelo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm mb-1 text-gray-300">Color</label>
                            <input type="text" wire:model="color" 
                                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                            @error('color') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
    
                        
                        <div>
                            <label class="block text-sm mb-1 text-gray-300">Estado</label>
                            <select wire:model="estado" 
                                    class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                                <option value="operativa">Operativa</option>
                                <option value="mantenimiento">En mantenimiento</option>
                                <option value="baja">Dada de baja</option>
                            </select>
                            @error('estado') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm mb-1 text-gray-300">Observaciones</label>
                            <input type="text" wire:model="observaciones" 
                                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                            @error('observaciones') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 mt-6 pt-4 border-t border-gray-700">
                        <button type="button" wire:click="closeModal"
                                class="px-4 py-2 text-gray-300 hover:text-gray-100">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="bg-green-600 hover:bg-green-700 px-6 py-2 rounded text-white font-medium">
                            {{ $editingId ? 'Actualizar' : 'Guardar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Mensajes de éxito/error -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-transition
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif
        <!-- Mensajes de éxito/error -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-transition
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    <!-- Script para importar patrullas -->
    <script>
        function importarPatrullas() {
            if (!confirm('¿Estás seguro de que deseas importar las patrullas? Esta acción actualizará los datos de producción.')) {
                return;
            }

            // Mostrar indicador de carga
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Importando...';
            button.disabled = true;

            // URL de la API - ajustada para la ruta API
            const apiUrl = '/api/mobile-vehicles/import';

            // Hacer la petición POST a la API
            fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Mostrar mensaje de éxito
                    showMessage('Patrullas importadas exitosamente', 'success');
                    
                    // Recargar la página después de 2 segundos para ver los cambios
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    throw new Error(data.message || 'Error en la importación');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Error al importar patrullas: ' + error.message, 'error');
            })
            .finally(() => {
                // Restaurar el botón
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }

        function showMessage(message, type) {
            // Crear elemento de mensaje
            const messageDiv = document.createElement('div');
            messageDiv.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'
            }`;
            messageDiv.textContent = message;
            messageDiv.setAttribute('x-data', '{ show: true }');
            messageDiv.setAttribute('x-show', 'show');
            messageDiv.setAttribute('x-transition', '');
            messageDiv.setAttribute('x-init', 'setTimeout(() => show = false, 5000)');

            document.body.appendChild(messageDiv);

            // Auto-remover después de 5 segundos
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.parentNode.removeChild(messageDiv);
                }
            }, 5000);
        }
    </script>
</div>