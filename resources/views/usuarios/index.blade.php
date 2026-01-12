<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">

                    <!-- Header con título -->
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <a href="{{ route('usuarios.index') }}" 
                        class="flex items-center text-blue-400 hover:text-blue-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Volver a Usuarios
                        </a>
                            <h2 class="text-3xl font-bold text-gray-100 mb-2">
                                Administración de Roles
                            </h2>
                            <p class="text-sm text-gray-300">
                                Asigna y gestiona roles de usuarios del sistema
                            </p>
                        </div>
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

                    <!-- Filtros de usuarios -->
                    <div class="mb-6 bg-zinc-700 p-4 rounded-lg">
                        <div class="flex flex-col md:flex-row md:items-end md:space-x-4 space-y-2 md:space-y-0">
                            <div class="flex-1">
                                <label for="filtroNombre" class="block text-sm font-medium text-gray-200 mb-1">
                                    Buscar por nombre:
                                </label>
                                <input type="text" id="filtroNombre" 
                                       class="w-full px-3 py-2 bg-zinc-600 border border-zinc-500 text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm placeholder-gray-400"
                                       placeholder="Escriba el nombre del usuario...">
                            </div>
                            <div class="flex-1">
                                <label for="filtroEmail" class="block text-sm font-medium text-gray-200 mb-1">
                                    Buscar por email:
                                </label>
                                <input type="text" id="filtroEmail" 
                                       class="w-full px-3 py-2 bg-zinc-600 border border-zinc-500 text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm placeholder-gray-400"
                                       placeholder="Escriba el email del usuario...">
                            </div>
                            <div class="flex-1">
                                <label for="filtroRol" class="block text-sm font-medium text-gray-200 mb-1">
                                    Filtrar por rol:
                                </label>
                                <select id="filtroRol" 
                                        class="w-full px-3 py-2 bg-zinc-600 border border-zinc-500 text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                                    <option value="">Todos los roles</option>
                                    @foreach($roles as $rol)
                                        <option value="{{ strtolower($rol->name) }}">{{ $rol->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-shrink-0">
                                <button type="button" id="limpiarFiltros" 
                                        class="bg-zinc-600 hover:bg-zinc-500 text-gray-100 font-medium py-2 px-4 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 h-[42px]">
                                    Limpiar Filtros
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de usuarios -->
                    <div class="bg-zinc-800 rounded-lg border border-zinc-600">
                        <div class="px-6 py-4 border-b border-zinc-600">
                            <h3 class="text-lg font-medium text-gray-100">Listado de Usuarios</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-600">
                                <thead class="bg-zinc-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Usuario
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Rol Actual
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Cambiar Rol
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-zinc-800 divide-y divide-gray-600" id="tablaUsuarios">
                                    @foreach($usuarios as $usuario)
                                        <tr class="usuario-row hover:bg-zinc-700" 
                                            data-nombre="{{ strtolower($usuario->name) }}" 
                                            data-email="{{ strtolower($usuario->email) }}"
                                            data-rol="{{ strtolower($usuario->getRoleNames()->implode(', ') ?: 'sin rol') }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8">
                                                        <div class="h-8 w-8 rounded-full bg-zinc-600 flex items-center justify-center">
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
                                                @if($usuario->getRoleNames()->isNotEmpty())
                                                    @foreach($usuario->getRoleNames() as $rolName)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900 text-blue-200 mr-1">
                                                            {{ $rolName }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="text-gray-400 text-xs">Sin rol asignado</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <form action="{{ route('usuarios.roles', $usuario) }}" method="POST" class="flex items-center space-x-2">
                                                    @csrf
                                                    <select name="rol" 
                                                            class="px-3 py-2 bg-zinc-700 border border-zinc-600 text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                                                        <option value="">Seleccionar rol...</option>
                                                        @foreach($roles as $rol)
                                                            <option value="{{ $rol->name }}" {{ $usuario->hasRole($rol->name) ? 'selected' : '' }}>
                                                                {{ $rol->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" 
                                                            class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-green-500">
                                                        Asignar
                                                    </button>
                                                </form>
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

    <!-- Meta tag para CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // Filtros de búsqueda
        document.getElementById('filtroNombre').addEventListener('input', filtrarUsuarios);
        document.getElementById('filtroEmail').addEventListener('input', filtrarUsuarios);
        document.getElementById('filtroRol').addEventListener('change', filtrarUsuarios);
        document.getElementById('limpiarFiltros').addEventListener('click', limpiarFiltros);

        function filtrarUsuarios() {
            const filtroNombre = document.getElementById('filtroNombre').value.toLowerCase();
            const filtroEmail = document.getElementById('filtroEmail').value.toLowerCase();
            const filtroRol = document.getElementById('filtroRol').value.toLowerCase();
            const filas = document.querySelectorAll('.usuario-row');

            filas.forEach(fila => {
                const nombre = fila.getAttribute('data-nombre');
                const email = fila.getAttribute('data-email');
                const rol = fila.getAttribute('data-rol');

                const coincideNombre = nombre.includes(filtroNombre);
                const coincideEmail = email.includes(filtroEmail);
                const coincideRol = filtroRol === '' || rol.includes(filtroRol);

                if (coincideNombre && coincideEmail && coincideRol) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        }

        function limpiarFiltros() {
            document.getElementById('filtroNombre').value = '';
            document.getElementById('filtroEmail').value = '';
            document.getElementById('filtroRol').value = '';
            filtrarUsuarios();
        }

        // Cerrar mensajes de estado al hacer clic en la X
        document.querySelectorAll('[onclick*="parentElement.style.display"]').forEach(button => {
            button.addEventListener('click', function() {
                this.parentElement.style.display = 'none';
            });
        });

        // Confirmación antes de asignar roles
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const select = this.querySelector('select[name="rol"]');
                if (select.value === '') {
                    e.preventDefault();
                    alert('Por favor, selecciona un rol antes de asignar.');
                    return false;
                }
                
                const usuarioNombre = this.closest('tr').querySelector('.text-gray-100').textContent.trim();
                const rolSeleccionado = select.options[select.selectedIndex].text;
                
                // Mensaje en una sola línea
                const mensaje = `¿Estás seguro de asignar el rol "${rolSeleccionado}" al usuario "${usuarioNombre}"?`;
                
                if (!confirm(mensaje)) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
</x-app-layout>