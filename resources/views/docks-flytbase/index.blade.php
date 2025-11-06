<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-5 text-gray-100 dark:text-gray-100">
                    <!-- Header -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-100">Administrar Docks Flytbase</h2>
                                <p class="text-sm text-gray-400 mt-1">Gestión de todos los docks disponibles en la plataforma</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button onclick="openCreateModal()"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Crear Dock
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Panel de Docks -->
                    <div class="bg-gray-800 rounded-lg shadow-sm border border-gray-700">
                        <div class="p-6">
                           
                            <!-- Tabla de Docks -->
                            <div class="overflow-x-auto rounded-lg border border-gray-700">
                                <table class="min-w-full divide-y divide-gray-700">
                                    <thead class="bg-gray-750">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Nombre</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Site</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Coordenadas</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Estado</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                                        @forelse($docks as $dock)
                                        <tr class="hover:bg-gray-750 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-300">#{{ $dock->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                                                            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-100">{{ $dock->nombre }}</div>
                                                        <div class="text-xs text-gray-400">{{ Str::limit($dock->descripcion, 50) }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ $dock->site->nombre ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                @if($dock->latitud && $dock->longitud)
                                                    {{ number_format($dock->latitud, 6) }}, {{ number_format($dock->longitud, 6) }}
                                                    @if($dock->altitude)
                                                        <br><span class="text-xs text-gray-400">Alt: {{ $dock->altitude }}m</span>
                                                    @endif
                                                @else
                                                    <span class="text-gray-400">No configuradas</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($dock->active)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-900/30 text-green-400 border border-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Activo
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-900/30 text-red-400 border border-red-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Inactivo
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center space-x-2">
                                                    <!-- Botón Editar -->
                                                    <button onclick="openEditModal({{ $dock->id }}, '{{ $dock->nombre }}', '{{ $dock->descripcion }}', {{ $dock->flytbase_site_id }}, {{ $dock->latitud ?? 'null' }}, {{ $dock->longitud ?? 'null' }}, {{ $dock->altitude ?? 'null' }}, {{ $dock->active ? 'true' : 'false' }})"
                                                    class="text-gray-400 hover:text-gray-300 transition-colors p-1 rounded hover:bg-gray-900/30"
                                                    title="Editar dock">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                        </svg>
                                                    </button>
                                                    <!-- Botón Eliminar -->
                                                    <form action="{{ route('docks-flytbase.destroy', $dock) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-400 hover:text-red-300 transition-colors p-1 rounded hover:bg-red-900/30"
                                                                title="Eliminar dock"
                                                                onclick="return confirm('¿Está seguro de eliminar este dock? Esta acción no se puede deshacer.')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-400">
                                                <svg class="w-12 h-12 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                                No se encontraron docks registrados
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Crear Dock -->
    <div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-100">Crear Nuevo Dock</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form id="createForm" method="POST" action="{{ route('docks-flytbase.store') }}">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Nombre del Dock *</label>
                        <input type="text" name="nombre" required 
                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                            placeholder="Ej: Dock Principal">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Descripción</label>
                        <textarea name="descripcion" rows="3"
                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                            placeholder="Descripción del dock..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Site *</label>
                        <select name="flytbase_site_id" required
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">Seleccione un site</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->id }}">{{ $site->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Latitud</label>
                            <input type="number" step="any" name="latitud" 
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                placeholder="0.000000">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Longitud</label>
                            <input type="number" step="any" name="longitud" 
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                placeholder="0.000000">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Altitud (m)</label>
                            <input type="text" 
                                name="altitude" 
                                id="createAltitude"
                                inputmode="decimal"
                                pattern="[+-]?[0-9]*[.]?[0-9]+"
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 no-spinner"
                                placeholder="0.00"
                                onkeypress="return validarDecimal(event)">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="active" id="createActive" value="1" checked
                            class="rounded bg-gray-700 border-gray-600 text-blue-600 focus:ring-blue-500 focus:ring-offset-gray-800">
                        <label for="createActive" class="ml-2 text-sm text-gray-300">Dock activo</label>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-700">
                    <button type="button" onclick="closeCreateModal()" 
                            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Crear Dock
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Editar Dock -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-100">Editar Dock</h3>
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
                        <label class="block text-sm font-medium text-gray-300 mb-2">Nombre del Dock *</label>
                        <input type="text" name="nombre" id="editNombre" required 
                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                            placeholder="Ej: Dock Principal">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Descripción</label>
                        <textarea name="descripcion" id="editDescripcion" rows="3"
                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                            placeholder="Descripción del dock..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Site *</label>
                        <select name="flytbase_site_id" id="editFlytbaseSiteId" required
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">Seleccione un site</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->id }}">{{ $site->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Latitud</label>
                            <input type="number" step="any" name="latitud" id="editLatitud"
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                placeholder="0.000000">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Longitud</label>
                            <input type="number" step="any" name="longitud" id="editLongitud"
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                placeholder="0.000000">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Altitud (m)</label>
                            <input type="text" 
                                name="altitude" 
                                id="editAltitude"
                                inputmode="decimal"
                                pattern="[+-]?[0-9]*[.]?[0-9]+"
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 no-spinner"
                                placeholder="0.00"
                                onkeypress="return validarDecimal(event)">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="active" id="editActive" value="1"
                            class="rounded bg-gray-700 border-gray-600 text-blue-600 focus:ring-blue-500 focus:ring-offset-gray-800">
                        <label for="editActive" class="ml-2 text-sm text-gray-300">Dock activo</label>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-700">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Actualizar Dock
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
            document.getElementById('createActive').checked = true;
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        // Funciones para el modal de editar
        function openEditModal(id, nombre, descripcion, siteId, latitud, longitud, altitude, active) {
            document.getElementById('editModal').classList.remove('hidden');
            
            // CORRECCIÓN: Usar la ruta correcta sin /update/
            document.getElementById('editForm').action = `/docks-flytbase/${id}`;
            document.getElementById('editNombre').value = nombre;
            document.getElementById('editDescripcion').value = descripcion || '';
            document.getElementById('editFlytbaseSiteId').value = siteId;
            document.getElementById('editLatitud').value = latitud && latitud !== 'null' ? latitud : '';
            document.getElementById('editLongitud').value = longitud && longitud !== 'null' ? longitud : '';
            document.getElementById('editAltitude').value = altitude && altitude !== 'null' && altitude !== '' ? altitude : '';
            document.getElementById('editActive').checked = active;
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
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
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