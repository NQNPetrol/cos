<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-100">Gestión de Misiones Flytbase</h2>
                            <p class="text-gray-400 mt-1">Administra las misiones disponibles para los drones</p>
                        </div>
                        <div class="flex space-x-3">
                            <button onclick="openCreateModal()"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Nueva Misión
                            </button>
                            
                        </div>
                    </div>

                    <!-- Mensajes -->
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-800 border border-green-600 text-green-100 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-800 border border-red-600 text-red-100 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Tabla de Misiones -->
                    <div class="overflow-x-auto rounded-lg border border-gray-700">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-gray-750">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">URL</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-700">
                                @forelse($misiones as $mision)
                                <tr class="hover:bg-gray-750 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-100">{{ $mision->nombre }}</div>
                                        @if($mision->descripcion)
                                            <div class="text-sm text-gray-400 mt-1">{{ Str::limit($mision->descripcion, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $mision->cliente->nombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        <div class="truncate max-w-xs" title="{{ $mision->url }}">
                                            {{ $mision->url }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('misiones-flytbase.toggle-status', $mision) }}" method="POST" class="inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border transition-colors {{ $mision->activo ? 'bg-green-900/30 text-green-400 border-green-800 hover:bg-green-800/40' : 'bg-red-900/30 text-red-400 border-red-800 hover:bg-red-800/40' }}">
                                                {{ $mision->activo ? 'Activa' : 'Inactiva' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button onclick="openEditModal({{ $mision->id }}, '{{ $mision->nombre }}', '{{ $mision->descripcion }}', {{ $mision->cliente_id }}, '{{ $mision->url }}', {{ $mision->activo ? 'true' : 'false' }})"
                                                    class="text-blue-400 hover:text-blue-300 transition-colors">
                                                Editar
                                            </button>
                                            <form action="{{ route('misiones-flytbase.destroy', $mision) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-400 hover:text-red-300 transition-colors"
                                                        onclick="return confirm('¿Está seguro de eliminar esta misión?')">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-400">
                                        No se encontraron misiones registradas.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($misiones->hasPages())
                        <div class="mt-4">
                            {{ $misiones->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Crear Misión -->
    <div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-100">Crear Nueva Misión</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form id="createForm" method="POST" action="{{ route('misiones-flytbase.store') }}">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Nombre *</label>
                        <input type="text" name="nombre" required 
                               class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Descripción</label>
                        <textarea name="descripcion" rows="3"
                                  class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Cliente *</label>
                        <select name="cliente_id" required 
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">Seleccione un cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">URL del Webhook *</label>
                        <input type="url" name="url" required 
                               class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                               placeholder="https://api.flytbase.com/rest/alarms/trigger">
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="activo" id="createActivo" value="1" checked
                               class="rounded bg-gray-700 border-gray-600 text-blue-600 focus:ring-blue-500 focus:ring-offset-gray-800">
                        <label for="createActivo" class="ml-2 text-sm text-gray-300">Misión activa</label>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-700">
                    <button type="button" onclick="closeCreateModal()" 
                            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Crear Misión
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Editar Misión -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-100">Editar Misión</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Nombre *</label>
                        <input type="text" name="nombre" id="editNombre" required 
                               class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Descripción</label>
                        <textarea name="descripcion" id="editDescripcion" rows="3"
                                  class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Cliente *</label>
                        <select name="cliente_id" id="editClienteId" required 
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">Seleccione un cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">URL del Webhook *</label>
                        <input type="url" name="url" id="editUrl" required 
                               class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                               placeholder="https://api.flytbase.com/rest/alarms/trigger">
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="activo" id="editActivo" value="1"
                               class="rounded bg-gray-700 border-gray-600 text-blue-600 focus:ring-blue-500 focus:ring-offset-gray-800">
                        <label for="editActivo" class="ml-2 text-sm text-gray-300">Misión activa</label>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-700">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Actualizar Misión
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        // Funciones para el modal de crear
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
            document.getElementById('createForm').reset();
            document.getElementById('createActivo').checked = true;
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        // Funciones para el modal de editar
        function openEditModal(id, nombre, descripcion, clienteId, url, activo) {
            document.getElementById('editModal').classList.remove('hidden');
            
            // Actualizar el formulario
            document.getElementById('editForm').action = `/misiones-flytbase/${id}`;
            document.getElementById('editNombre').value = nombre;
            document.getElementById('editDescripcion').value = descripcion || '';
            document.getElementById('editClienteId').value = clienteId;
            document.getElementById('editUrl').value = url;
            document.getElementById('editActivo').checked = activo;
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Cerrar modales al hacer click fuera
        document.addEventListener('click', function(event) {
            const createModal = document.getElementById('createModal');
            const editModal = document.getElementById('editModal');
            
            if (event.target === createModal) {
                closeCreateModal();
            }
            if (event.target === editModal) {
                closeEditModal();
            }
        });

        // Manejar envío de formularios
        document.getElementById('createForm').addEventListener('submit', function(e) {
            e.preventDefault();
            submitForm(this);
        });

        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            submitForm(this);
        });

        function submitForm(form) {
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Mostrar loading
            submitButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Guardando...
            `;
            submitButton.disabled = true;

            fetch(form.action, {
                method: form.method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    return response.json().then(data => {
                        if (data.success || response.ok) {
                            window.location.reload();
                        } else {
                            throw new Error(data.message || 'Error al guardar');
                        }
                    });
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        }

        // Cerrar con ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeCreateModal();
                closeEditModal();
            }
        });
    </script>
</x-app-layout>