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
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Nueva Notificación
                            </a>
                        </div>
                    </div>

                    <!-- Filtros y Estadísticas -->
                    <div class="bg-gray-700 rounded-lg p-4 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="text-center p-3 bg-gray-600 rounded">
                                <div class="text-2xl font-bold text-blue-400">{{ $notifications->total() }}</div>
                                <div class="text-sm text-gray-300">Total</div>
                            </div>
                            <div class="text-center p-3 bg-gray-600 rounded">
                                <div class="text-2xl font-bold text-green-400">
                                    {{ $notifications->where('is_active', true)->count() }}
                                </div>
                                <div class="text-sm text-gray-300">Activas</div>
                            </div>
                            <div class="text-center p-3 bg-gray-600 rounded">
                                <div class="text-2xl font-bold text-yellow-400">
                                    {{ $notifications->where('type', 'global')->count() }}
                                </div>
                                <div class="text-sm text-gray-300">Globales</div>
                            </div>
                            <div class="text-center p-3 bg-gray-600 rounded">
                                <div class="text-2xl font-bold text-red-400">
                                    {{ $notifications->where('priority', 'ALTA')->count() }}
                                </div>
                                <div class="text-sm text-gray-300">Alta Prioridad</div>
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
                                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                                    {{ $notification->type === 'global' ? 'bg-blue-800 text-blue-200' : 
                                                       ($notification->type === 'user' ? 'bg-green-800 text-green-200' : 'bg-purple-800 text-purple-200') }}">
                                                    {{ $notification->type }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                                    {{ $notification->priority === 'ALTA' ? 'bg-red-800 text-red-200' : 
                                                       ($notification->priority === 'NORMAL' ? 'bg-yellow-800 text-yellow-200' : 'bg-green-800 text-green-200') }}">
                                                    {{ $notification->priority }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                                    {{ $notification->is_active ? 'bg-green-800 text-green-200' : 'bg-red-800 text-red-200' }}">
                                                    {{ $notification->is_active ? 'Activa' : 'Inactiva' }}
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
                                                        @method('POST')
                                                        <input type="hidden" name="activate" value="{{ $notification->is_active ? '0' : '1' }}">
                                                        <button type="submit"
                                                                class="p-2 rounded {{ $notification->is_active ? 'bg-yellow-600 hover:bg-yellow-500' : 'bg-green-600 hover:bg-green-500' }}"
                                                                title="{{ $notification->is_active ? 'Desactivar' : 'Activar' }}">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                      d="{{ $notification->is_active ? 'M6 18L18 6M6 6l12 12' : 'M5 13l4 4L19 7' }}"/>
                                                            </svg>
                                                        </button>
                                                    </form>

                                                    <!-- Botón para editar (abre modal) -->
                                                    <button onclick="openEditModal({{ $notification->id }})"
                                                            class="p-2 bg-blue-600 hover:bg-blue-500 rounded"
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
                                                                class="p-2 bg-red-600 hover:bg-red-500 rounded"
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
                            {{ $notifications->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Edición -->
    <div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden z-50">
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
                                <option value="BAJA">Baja</option>
                                <option value="NORMAL">Normal</option>
                                <option value="ALTA">Alta</option>
                            </select>
                        </div>

                        <!-- Estado -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" id="editIsActive" value="1"
                                       class="rounded border-gray-600 bg-gray-700 text-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-300">Notificación activa</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 pt-6">
                        <button type="button" onclick="closeEditModal()" 
                                class="bg-gray-600 hover:bg-gray-500 text-white px-6 py-2 rounded-lg transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-lg transition-colors">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript para las acciones -->
     <script>
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
                document.getElementById('editIsActive').checked = data.notification.is_active;
                
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