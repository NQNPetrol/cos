<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">

                    <!-- Título más pequeño y sin icono -->
                    <div class="mb-6">
                        <h2 class="text-3xl font-bold text-gray-100 mb-2">
                            Administrar Usuarios
                        </h2>
                        <p class="text-sm text-gray-300">
                            Elimina, crea y gestiona usuarios de la app
                        </p>
                    </div>

                    <!-- Mensajes de estado mejorados -->
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

                    @if(session('info'))
                        <div class="mb-6 p-4 bg-blue-800 border border-blue-600 text-blue-100 rounded-lg flex items-center justify-between shadow-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ session('info') }}</span>
                            </div>
                            <button type="button" class="text-blue-300 hover:text-white" onclick="this.parentElement.style.display='none'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-800 border border-red-600 text-red-100 rounded-lg flex items-center justify-between shadow-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <ul class="list-disc list-inside">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="text-red-300 hover:text-white" onclick="this.parentElement.style.display='none'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    <!-- Header con botón crear -->
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex-1"></div>
                        <button type="button" 
                                onclick="abrirModalCrear()"
                                class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded text-white font-medium flex items-center">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Crear Usuario
                        </button>
                    </div>

                    <!-- Filtros de usuarios -->
                    <div class="mb-6 bg-gray-700 p-4 rounded-lg">
                        <div class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-2 md:space-y-0">
                            <div class="flex-1">
                                <label for="filtroNombre" class="block text-sm font-medium text-gray-200 mb-1">
                                    Buscar por nombre:
                                </label>
                                <input type="text" id="filtroNombre" 
                                       class="w-full px-3 py-2 bg-gray-600 border border-gray-500 text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm placeholder-gray-400"
                                       placeholder="Escriba el nombre del usuario...">
                            </div>
                            <div class="flex-1">
                                <label for="filtroEmail" class="block text-sm font-medium text-gray-200 mb-1">
                                    Buscar por email:
                                </label>
                                <input type="text" id="filtroEmail" 
                                       class="w-full px-3 py-2 bg-gray-600 border border-gray-500 text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm placeholder-gray-400"
                                       placeholder="Escriba el email del usuario...">
                            </div>
                            <div class="flex-shrink-0 mt-6 md:mt-6">
                                <button type="button" id="limpiarFiltros" 
                                        class="bg-gray-600 hover:bg-gray-500 text-gray-100 font-medium py-2 px-4 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-gray-400">
                                    Limpiar Filtros
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de usuarios -->
                    <div class="bg-gray-800 rounded-lg border border-gray-600">
                        <div class="px-6 py-4 border-b border-gray-600">
                            <h3 class="text-lg font-medium text-gray-100">Listado</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-600">
                                <thead class="bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Usuario
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Roles
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-600" id="tablaUsuarios">
                                    @foreach($usuarios as $usuario)
                                        <tr class="usuario-row hover:bg-gray-700" 
                                            data-nombre="{{ strtolower($usuario->name) }}" 
                                            data-email="{{ strtolower($usuario->email) }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8">
                                                        <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-white">
                                                                {{ $usuario->initials() }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-100">
                                                            {{ $usuario->name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ $usuario->email }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                @foreach($usuario->roles as $role)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900 text-blue-200 mr-1">
                                                        {{ $role->name }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <!-- Botón Editar -->
                                                    <button onclick="abrirModalEditar({{ $usuario }})"
                                                            class="p-2 bg-blue-600 hover:bg-blue-500 rounded-md text-white transition-colors"
                                                            title="Editar usuario">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </button>

                                                    <!-- Botón Eliminar -->
                                                    <button onclick="confirmarEliminacion({{ $usuario->id }}, '{{ $usuario->name }}')"
                                                            class="p-2 bg-red-600 hover:bg-red-500 rounded-md text-white transition-colors"
                                                            title="Eliminar usuario">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>

                                                    <!-- Botón Asignar Cliente -->
                                                    <a href="{{ route('user-cliente.index') }}?user_id={{ $usuario->id }}"
                                                       class="p-2 bg-green-600 hover:bg-green-500 rounded-md text-white transition-colors"
                                                       title="Asignar cliente">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                        </svg>
                                                    </a>

                                                    <!-- Botón Asignar Rol -->
                                                    <a href="{{ route('usuarios.admin-roles') }}?user_id={{ $usuario->id }}"
                                                       class="p-2 bg-purple-600 hover:bg-purple-500 rounded-md text-white transition-colors"
                                                       title="Asignar rol">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear Usuario -->
    <div id="modalCrear" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md">
            <div class="border-b border-gray-700 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-white">Crear Nuevo Usuario</h3>
                <button type="button" 
                        class="text-gray-400 hover:text-gray-200 bg-transparent rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        onclick="cerrarModalCrear()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span class="sr-only">Cerrar modal</span>
                </button>
            </div>

            <div class="p-6">
                <form action="{{ route('usuarios.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nombre</label>
                            <input type="text" name="name" id="name" required
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                            <input type="email" name="email" id="email" required
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Contraseña</label>
                            <input type="password" name="password" id="password" required
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" 
                                class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-md text-white transition-colors"
                                onclick="cerrarModalCrear()">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 hover:bg-green-500 rounded-md text-white transition-colors">
                            Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Usuario -->
    <div id="modalEditar" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md">
            <div class="border-b border-gray-700 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-white">Editar Usuario</h3>
                <button type="button" 
                        class="text-gray-400 hover:text-gray-200 bg-transparent rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        onclick="cerrarModalEditar()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span class="sr-only">Cerrar modal</span>
                </button>
            </div>

            <div class="p-6">
                <form id="formEditar" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label for="edit_name" class="block text-sm font-medium text-gray-300 mb-2">Nombre</label>
                            <input type="text" name="name" id="edit_name" required
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        
                        <div>
                            <label for="edit_email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                            <input type="email" name="email" id="edit_email" required
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" 
                                class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-md text-white transition-colors"
                                onclick="cerrarModalEditar()">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 hover:bg-green-500 rounded-md text-white transition-colors">
                            Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Confirmar Eliminación -->
    <div id="modalEliminar" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md">
            <div class="border-b border-gray-700 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-white">Confirmar Eliminación</h3>
                <button type="button" 
                        class="text-gray-400 hover:text-gray-200 bg-transparent rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        onclick="cerrarModalEliminar()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span class="sr-only">Cerrar modal</span>
                </button>
            </div>

            <div class="p-6">
                <p class="text-gray-200 mb-4">¿Estás seguro de que deseas eliminar al usuario <span id="nombreUsuario" class="font-semibold text-white"></span>? Esta acción no se puede deshacer.</p>
                
                <form id="formEliminar" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-md text-white transition-colors"
                                onclick="cerrarModalEliminar()">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-md text-white transition-colors">
                            Eliminar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Meta tag para CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // Funciones para los modales
        function abrirModalCrear() {
            document.getElementById('modalCrear').classList.remove('hidden');
        }

        function cerrarModalCrear() {
            document.getElementById('modalCrear').classList.add('hidden');
        }

        function abrirModalEditar(usuario) {
            document.getElementById('edit_name').value = usuario.name;
            document.getElementById('edit_email').value = usuario.email;
            document.getElementById('formEditar').action = `/usuarios/${usuario.id}`;
            document.getElementById('modalEditar').classList.remove('hidden');
        }

        function cerrarModalEditar() {
            document.getElementById('modalEditar').classList.add('hidden');
        }

        function confirmarEliminacion(userId, userName) {
            document.getElementById('nombreUsuario').textContent = userName;
            document.getElementById('formEliminar').action = `/usuarios/${userId}`;
            document.getElementById('modalEliminar').classList.remove('hidden');
        }

        function cerrarModalEliminar() {
            document.getElementById('modalEliminar').classList.add('hidden');
        }

        // Filtros de búsqueda
        document.getElementById('filtroNombre').addEventListener('input', filtrarUsuarios);
        document.getElementById('filtroEmail').addEventListener('input', filtrarUsuarios);
        document.getElementById('limpiarFiltros').addEventListener('click', limpiarFiltros);

        function filtrarUsuarios() {
            const filtroNombre = document.getElementById('filtroNombre').value.toLowerCase();
            const filtroEmail = document.getElementById('filtroEmail').value.toLowerCase();
            const filas = document.querySelectorAll('.usuario-row');

            filas.forEach(fila => {
                const nombre = fila.getAttribute('data-nombre');
                const email = fila.getAttribute('data-email');

                const coincideNombre = nombre.includes(filtroNombre);
                const coincideEmail = email.includes(filtroEmail);

                if (coincideNombre && coincideEmail) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        }

        function limpiarFiltros() {
            document.getElementById('filtroNombre').value = '';
            document.getElementById('filtroEmail').value = '';
            filtrarUsuarios();
        }

        // Cerrar modales al hacer clic fuera
        window.onclick = function(event) {
            const modals = ['modalCrear', 'modalEditar', 'modalEliminar'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (event.target === modal) {
                    if (modalId === 'modalCrear') cerrarModalCrear();
                    if (modalId === 'modalEditar') cerrarModalEditar();
                    if (modalId === 'modalEliminar') cerrarModalEliminar();
                }
            });
        }

        // Cerrar modales con ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                cerrarModalCrear();
                cerrarModalEditar();
                cerrarModalEliminar();
            }
        });
    </script>
</x-app-layout>