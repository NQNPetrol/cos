<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">

                    <!-- Título más pequeño y sin icono -->
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-100 mb-2">
                                Administrar Usuarios
                            </h2>
                            <p class="text-sm text-gray-300">
                                Elimina, crea y gestiona usuarios de la app
                            </p>
                        </div>
                        <button type="button" 
                                onclick="abrirModalCrear()"
                                class="bg-gray-600 hover:bg-gray-500 px-3 py-2 rounded text-white font-medium">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                                Crear Usuario
                        </button>
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
                    <div class="flex justify-between items-center">
                        <div class="flex items-center"></div>
                    </div>

                    <!-- Filtros de usuarios -->
                    <div class="mb-6 bg-gray-700 p-4 rounded-lg">
                        <div class="flex flex-col md:flex-row md:items-end md:space-x-4 space-y-2 md:space-y-0">
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
                            <div class="flex-shrink-0">
                                <button type="button" id="limpiarFiltros" 
                                        class="bg-gray-600 hover:bg-gray-500 text-gray-100 font-medium py-2 px-4 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 h-[42px]">
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
                                            Clientes Asignados
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
                                                        <div class="h-8 w-8 rounded-full bg-gray-600 flex items-center justify-center">
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
                                             <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                @if($usuario->clientes->count() > 0)
                                                    @foreach($usuario->clientes as $cliente)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-200 mr-1 mb-1">
                                                            {{ $cliente->nombre }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="text-gray-400 text-xs">Sin clientes asignados</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-3">
                                                    <!-- Botón Editar -->
                                                    <button onclick="abrirModalEditar({{ $usuario }})"
                                                            class="text-gray-300 hover:text-gray-200 transition-colors"
                                                            title="Editar usuario">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </button>

                                                    <!-- Botón Eliminar -->
                                                    <button onclick="confirmarEliminacion({{ $usuario->id }}, '{{ $usuario->name }}')"
                                                            class="text-red-500 hover:text-red-400 transition-colors"
                                                            title="Eliminar usuario">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>

                                                    <!-- Botón Asignar Cliente -->
                                                    <a href="{{ route('user-cliente.index') }}?user_id={{ $usuario->id }}"
                                                       class="text-blue-400 hover:text-blue-300 transition-colors"
                                                       title="Asignar cliente">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                        </svg>
                                                    </a>

                                                    <!-- Botón Asignar Rol -->
                                                    <a href="{{ route('usuarios.admin-roles') }}?user_id={{ $usuario->id }}"
                                                       class="text-lime-400 hover:text-lime-300 transition-colors"
                                                       title="Asignar rol">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M8.5 4.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0ZM10 13c.552 0 1.01-.452.9-.994a5.002 5.002 0 0 0-9.802 0c-.109.542.35.994.902.994h8ZM12.5 3.5a.75.75 0 0 1 .75.75v1h1a.75.75 0 0 1 0 1.5h-1v1a.75.75 0 0 1-1.5 0v-1h-1a.75.75 0 0 1 0-1.5h1v-1a.75.75 0 0 1 .75-.75Z"/>
                                                        </svg>
                                                    </a>

                                                    <!-- Botón Resetear Contraseña -->
                                                    <button onclick="abrirModalResetPassword({{ $usuario->id }}, '{{ $usuario->name }}')"
                                                            class="text-yellow-400 hover:text-yellow-300 transition-colors"
                                                            title="Resetear contraseña">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                        </svg>
                                                    </button>
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
                        
                        <div class="relative">
                            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Contraseña</label>
                            <div class="relative">
                                <input type="password" name="password" id="password" required
                                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 pr-10">
                                <button type="button" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-200 mt-2"
                                        onclick="togglePasswordVisibility('password', 'passwordEyeIcon', 'passwordEyeTooltip')"
                                        id="passwordEyeButton">
                                    <svg id="passwordEyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <div id="passwordEyeTooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Mostrar contraseña
                                </div>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Confirmar Contraseña</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 pr-10">
                                <button type="button" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-200 mt-2"
                                        onclick="togglePasswordVisibility('password_confirmation', 'passwordConfirmationEyeIcon', 'passwordConfirmationEyeTooltip')"
                                        id="passwordConfirmationEyeButton">
                                    <svg id="passwordConfirmationEyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <div id="passwordConfirmationEyeTooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Mostrar contraseña
                                </div>
                            </div>
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

    <!-- Modal Resetear Contraseña -->
    <div id="modalResetPassword" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md">
            <div class="border-b border-gray-700 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-white">Resetear Contraseña</h3>
                <button type="button" 
                        class="text-gray-400 hover:text-gray-200 bg-transparent rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        onclick="cerrarModalResetPassword()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span class="sr-only">Cerrar modal</span>
                </button>
            </div>

            <div class="p-6">
                <p class="text-gray-200 mb-4">¿Estás seguro de que deseas resetear la contraseña de <span id="nombreUsuarioReset" class="font-semibold text-white"></span>?</p>
                
                <form id="formResetPassword" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div class="relative">
                            <label for="new_password" class="block text-sm font-medium text-gray-300 mb-2">Nueva Contraseña</label>
                            <div class="relative">
                                <input type="password" name="new_password" id="new_password" required
                                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 pr-10">
                                <button type="button" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-200 mt-2"
                                        onclick="togglePasswordVisibility('new_password', 'newPasswordEyeIcon', 'newPasswordEyeTooltip')"
                                        id="newPasswordEyeButton">
                                    <svg id="newPasswordEyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <div id="newPasswordEyeTooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Mostrar contraseña
                                </div>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Confirmar Nueva Contraseña</label>
                            <div class="relative">
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 pr-10">
                                <button type="button" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-200 mt-2"
                                        onclick="togglePasswordVisibility('new_password_confirmation', 'newPasswordConfirmationEyeIcon', 'newPasswordConfirmationEyeTooltip')"
                                        id="newPasswordConfirmationEyeButton">
                                    <svg id="newPasswordConfirmationEyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <div id="newPasswordConfirmationEyeTooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Mostrar contraseña
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" 
                                class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-md text-white transition-colors"
                                onclick="cerrarModalResetPassword()">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-yellow-600 hover:bg-yellow-500 rounded-md text-white transition-colors">
                            Resetear Contraseña
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

        function abrirModalResetPassword(userId, userName) {
            document.getElementById('nombreUsuarioReset').textContent = userName;
            document.getElementById('formResetPassword').action = `/usuarios/${userId}/reset-password`;
            document.getElementById('modalResetPassword').classList.remove('hidden');
        }

        function cerrarModalResetPassword() {
            document.getElementById('modalResetPassword').classList.add('hidden');
        }

        function confirmarEliminacion(userId, userName) {
            document.getElementById('nombreUsuario').textContent = userName;
            document.getElementById('formEliminar').action = `/usuarios/${userId}`;
            document.getElementById('modalEliminar').classList.remove('hidden');
        }

        function cerrarModalEliminar() {
            document.getElementById('modalEliminar').classList.add('hidden');
        }

        // Función para mostrar/ocultar contraseña
        function togglePasswordVisibility(inputId, eyeIconId, tooltipId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(eyeIconId);
            const tooltip = document.getElementById(tooltipId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
                tooltip.textContent = 'Ocultar contraseña';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
                tooltip.textContent = 'Mostrar contraseña';
            }
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