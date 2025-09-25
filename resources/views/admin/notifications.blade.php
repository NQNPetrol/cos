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
                                <h2 class="text-3xl font-bold text-white mb-2">Administrar Notificaciones</h2>
                                <p class="text-sm text-gray-300">Gestión de notificaciones globales del sistema</p>
                            </div>
                            <a href="{{ route('admin.nueva-notif') }}" 
                               class="bg-gray-700 hover:bg-gray-600 text-gray-200 px-4 py-2 hover:border-bg-blue-700 rounded-lg transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Nueva Notificación
                            </a>
                        </div>
                    </div>

                    <!-- Filtros y Estadísticas -->
                    <div class="bg-gray-700 rounded-lg p-4 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                            <!-- Filtro por tipo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Tipo</label>
                                <select name="filter_type" id="filterType" class="w-full bg-gray-600 border border-gray-500 text-white rounded-lg px-3 py-2 filter-select">
                                    <option value="">Todos los tipos</option>
                                    <option value="global" {{ request('filter_type') == 'global' ? 'selected' : '' }}>GLOBAL</option>
                                    <option value="user" {{ request('filter_type') == 'user' ? 'selected' : '' }}>DE USUARIO</option>
                                    <option value="client" {{ request('filter_type') == 'client' ? 'selected' : '' }}>DE CLIENTE</option>
                                </select>
                            </div>

                            <!-- Filtro por prioridad -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Prioridad</label>
                                <select name="filter_priority" id="filterPriority" class="w-full bg-gray-600 border border-gray-500 text-white rounded-lg px-3 py-2 filter-select">
                                    <option value="">Todas las prioridades</option>
                                    <option value="BAJA" {{ request('filter_priority') == 'BAJA' ? 'selected' : '' }}>BAJA</option>
                                    <option value="NORMAL" {{ request('filter_priority') == 'NORMAL' ? 'selected' : '' }}>MEDIA</option>
                                    <option value="ALTA" {{ request('filter_priority') == 'ALTA' ? 'selected' : '' }}>ALTA</option>
                                </select>
                            </div>

                            <!-- Filtro por fecha desde -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Desde</label>
                                <input type="date" name="filter_date_from" id="filterDateFrom" 
                                    value="{{ request('filter_date_from') }}"
                                    class="w-full bg-gray-600 border border-gray-500 text-white rounded-lg px-3 py-2 filter-select">
                            </div>

                            <!-- Filtro por fecha hasta -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Hasta</label>
                                <input type="date" name="filter_date_to" id="filterDateTo" 
                                    value="{{ request('filter_date_to') }}"
                                    class="w-full bg-gray-600 border border-gray-500 text-white rounded-lg px-3 py-2 filter-select">
                            </div>

                            <!-- Botón limpiar - OCUPA EL ESPACIO DEL FILTRO ELIMINADO -->
                            <div class="md:col-span-1">
                                <button id="clearFilters" 
                                        class="w-full bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center mt-2 md:mt-0">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Limpiar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Notificaciones -->
                    <div class="bg-gray-700 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-600">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Título
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Tipo
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Prioridad
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Estado
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Creada
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-600">
                                    @forelse($notifications as $notification)
                                        <tr class="hover:bg-gray-750 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-white">{{ $notification->title }}</div>
                                                <div class="text-sm text-gray-300 truncate max-w-xs">{{ $notification->message }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $typeLabels = [
                                                        'global' => 'GLOBAL',
                                                        'user' => 'DE USUARIO', 
                                                        'client' => 'DE CLIENTE'
                                                    ];
                                                    $typeColors = [
                                                        'global' => 'bg-blue-900/30 text-blue-300 border border-blue-600/50',
                                                        'user' => 'bg-green-900/30 text-green-300 border border-green-600/50',
                                                        'client' => 'bg-purple-900/30 text-purple-300 border border-purple-600/50'
                                                    ];
                                                @endphp
                                                <span class="px-2 py-1 text-xs font-medium rounded {{ $typeColors[$notification->type] }}">
                                                    {{ $typeLabels[$notification->type] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $priorityLabels = [
                                                        'BAJA' => 'BAJA',
                                                        'NORMAL' => 'MEDIA',
                                                        'ALTA' => 'ALTA'
                                                    ];
                                                    $priorityColors = [
                                                        'BAJA' => 'bg-green-900/30 text-green-300 border border-green-600/50',
                                                        'NORMAL' => 'bg-yellow-900/30 text-yellow-300 border border-yellow-600/50',
                                                        'ALTA' => 'bg-red-900/30 text-red-300 border border-red-600/50'
                                                    ];
                                                @endphp
                                                <span class="px-2 py-1 text-xs font-medium rounded {{ $priorityColors[$notification->priority] }}">
                                                    {{ $priorityLabels[$notification->priority] ?? $notification->priority }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-medium rounded {{ $notification->is_active ? 'bg-green-900/30 text-green-300 border border-green-600/50' : 'bg-red-900/30 text-red-300 border border-red-600/50' }}">
                                                    {{ $notification->is_active ? 'ACTIVA' : 'INACTIVA' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ $notification->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <!-- Formulario para activar/desactivar -->
                                                    <form action="{{ route('notifications.toggle', $notification->id) }}" method="POST" 
                                                        onsubmit="return confirm('¿{{ $notification->is_active ? 'Desactivar' : 'Activar' }} esta notificación?')">
                                                        @csrf
                                                        @method('POST') <!-- Cambiar a POST ya que el método toggle usa POST -->
                                                        <input type="hidden" name="activate" value="{{ $notification->is_active ? '0' : '1' }}">
                                                        <button type="submit"
                                                                class="p-1.5 rounded-lg {{ $notification->is_active ? 'text-yellow-100 hover:text-yellow-200 hover:bg-yellow-200/30' : 'text-green-400 hover:text-green-200 hover:bg-green-200/30' }} transition-colors"
                                                                title="{{ $notification->is_active ? 'Desactivar' : 'Activar' }}">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                    d="{{ $notification->is_active ? 'M6 18L18 6M6 6l12 12' : 'M5 13l4 4L19 7' }}"/>
                                                            </svg>
                                                        </button>
                                                    </form>

                                                    <!-- Botón para editar (abre modal) -->
                                                    <button onclick="openEditModal({{ $notification->id }})"
                                                            class="p-1.5 rounded-lg text-blue-400 hover:text-blue-200 hover:bg-blue-200/30 transition-colors"
                                                            title="Editar">
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </button>

                                                    <!-- Formulario para eliminar -->
                                                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" 
                                                          onsubmit="return confirm('¿Está seguro de eliminar esta notificación?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="p-1.5 rounded-lg text-red-400 hover:text-red-300 hover:bg-red-300/30 transition-colors"
                                                                title="Eliminar">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-gray-400">
                                                No hay notificaciones creadas
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Paginación -->
                    @if($notifications->hasPages())
                        <div class="mt-6">
                            {{ $notifications->appends(request()->except('page'))->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Edición -->
    <div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-90 flex items-center justify-center hidden z-50">
        <div class="bg-gray-800 rounded-lg w-11/12 md:w-3/4 lg:w-1/2 max-h-90vh overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-white">Editar Notificación</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <!-- Tipo de Notificación -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Notificación</label>
                            <select name="type" id="editType" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                                <option value="global">Notificación Global (Todos los usuarios)</option>
                                <option value="user">Notificación Específica para Usuario</option>
                                <option value="client">Notificación para Cliente</option>
                            </select>
                        </div>

                        <!-- Campos condicionales -->
                        <div id="editUserField" class="hidden">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Seleccionar Usuario</label>
                            <select name="user_id" id="editUserId" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2">
                                <option value="">Seleccione un usuario</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="editClientField" class="hidden">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Seleccionar Cliente</label>
                            <select name="client_id" id=editClientId class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2">
                                <option value="">Seleccione un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Título -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Título</label>
                            <input type="text" name="title" id="editTitle" required 
                                   class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                                   placeholder="Ingrese el título de la notificación">
                        </div>

                        <!-- Mensaje -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Mensaje</label>
                            <textarea name="message" id="editMessage" rows="4" required
                                      class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                                      placeholder="Escriba el mensaje de la notificación"></textarea>
                        </div>

                        <!-- Prioridad -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Prioridad</label>
                            <select name="priority" id="editPriority" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2">
                                <option value="BAJA">BAJA</option>
                                <option value="NORMAL">MEDIA</option>
                                <option value="ALTA">ALTA</option>
                            </select>
                        </div>

                        <!-- Estado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Estado Actual</label>
                            <div id="editStatusDisplay" class="px-3 py-2 bg-gray-700 rounded-lg border border-gray-600">
                                <span id="statusText" class="text-sm font-medium"></span>
                            </div>
                            <p class="mt-1 text-xs text-gray-400">
                                Use el botón de activar/desactivar en la tabla para cambiar el estado
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 pt-6">
                        <button type="button" onclick="closeEditModal()" 
                                class="bg-gray-600 hover:bg-gray-500 text-white px-6 py-2 rounded-lg transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-2 rounded-lg transition-colors">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript para las acciones -->
     <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterSelects = document.querySelectorAll('.filter-select');
            const clearButton = document.getElementById('clearFilters');
            let filterTimeout;

            // Función para aplicar filtros automáticamente
            function applyFilters() {
                clearTimeout(filterTimeout);
                
                filterTimeout = setTimeout(() => {
                    const filters = {
                        filter_type: document.getElementById('filterType').value,
                        filter_priority: document.getElementById('filterPriority').value,
                        filter_date_from: document.getElementById('filterDateFrom').value,
                        filter_date_to: document.getElementById('filterDateTo').value
                    };

                    // Construir URL con parámetros de filtro
                    const url = new URL(window.location.href);
                    Object.keys(filters).forEach(key => {
                        if (filters[key]) {
                            url.searchParams.set(key, filters[key]);
                        } else {
                            url.searchParams.delete(key);
                        }
                    });

                    // Recargar la página con los nuevos filtros
                    window.location.href = url.toString();
                }, 800); // Debounce de 800ms para evitar recargas excesivas
            }

            // Limpiar todos los filtros
            clearButton.addEventListener('click', function() {
                // Resetear todos los selects e inputs
                document.getElementById('filterType').value = '';
                document.getElementById('filterPriority').value = '';
                document.getElementById('filterDateFrom').value = '';
                document.getElementById('filterDateTo').value = '';
                
                // Recargar sin filtros
                window.location.href = "{{ route('notifications.admin') }}";
            });

            // Event listeners para cambios en los filtros
            filterSelects.forEach(select => {
                select.addEventListener('change', applyFilters);
            });

            // Event listeners para inputs de fecha
            document.getElementById('filterDateFrom').addEventListener('change', applyFilters);
            document.getElementById('filterDateTo').addEventListener('change', applyFilters);
        });
        
        async function openEditModal(notificationId) {
            try {
                // Cargar datos de la notificación via AJAX
                const response = await fetch(`/admin/notificaciones/${notificationId}/editar-datos`);
                if (!response.ok) throw new Error('Error al cargar datos');
                
                const data = await response.json();
                
                const form = document.getElementById('editForm');
                form.action = `/admin/notificaciones/${notificationId}`;
                
                // Llenar campos del formulario
                document.getElementById('editTitle').value = data.notification.title;
                document.getElementById('editMessage').value = data.notification.message;
                document.getElementById('editType').value = data.notification.type;
                document.getElementById('editPriority').value = data.notification.priority;
                
                const statusText = document.getElementById('statusText');
                const statusDisplay = document.getElementById('editStatusDisplay');
                
                if (data.notification.is_active) {
                    statusText.textContent = 'ACTIVA';
                    statusText.className = 'text-sm font-medium text-green-400';
                    statusDisplay.className = 'px-3 py-2 bg-green-900/20 rounded-lg border border-green-600/50';
                } else {
                    statusText.textContent = 'INACTIVA';
                    statusText.className = 'text-sm font-medium text-red-400';
                    statusDisplay.className = 'px-3 py-2 bg-red-900/20 rounded-lg border border-red-600/50';
                }

                // Llenar select de usuario si existe
                if (data.notification.user_id) {
                    document.getElementById('editUserId').value = data.notification.user_id;
                }
                
                // Llenar select de cliente si existe
                if (data.notification.client_id) {
                    document.getElementById('editClientId').value = data.notification.client_id;
                }
                
                // Mostrar campos condicionales según el tipo
                toggleEditFields();
                
                // Mostrar el modal
                document.getElementById('editModal').classList.remove('hidden');
                
            } catch (error) {
                console.error('Error:', error);
                alert('Error al cargar los datos de la notificación');
            }
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function toggleEditFields() {
            const type = document.getElementById('editType').value;
            const userField = document.getElementById('editUserField');
            const clientField = document.getElementById('editClientField');
            
            userField.classList.toggle('hidden', type !== 'user');
            clientField.classList.toggle('hidden', type !== 'client');
        }

        // Manejar envío del formulario para cerrar modal y recargar
        document.getElementById('editForm').addEventListener('submit', function(e) {
            // El formulario se enviará normalmente, el modal se cerrará con JavaScript
            setTimeout(() => {
                closeEditModal();
            }, 100);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const editTypeSelect = document.getElementById('editType');
            editTypeSelect.addEventListener('change', toggleEditFields);
        });
    </script>
</x-app-layout>