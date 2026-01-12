<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <a href="{{ route('usuarios.index') }}" 
                        class="flex items-center text-blue-400 hover:text-blue-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Volver a Usuarios
                        </a>

                    <!-- Título más pequeño y sin icono -->
                    <div class="mb">
                        <h2 class="text-3xl font-bold text-gray-100 mb-2">
                            Asignación de Clientes a Usuarios
                        </h2>
                        <p class="text-sm text-gray-300 mb-5">
                            Asignación y administración de clientes a usuarios del sistema
                        </p>
                    </div>

                    <!-- Mensajes de estado -->
                    @if(session('success'))
                        <div class="mb-4 bg-green-800 border border-green-600 text-green-200 px-4 py-3 rounded relative">
                            {{ session('success') }}
                            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
                                <span class="text-green-200">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-800 border border-red-600 text-red-200 px-4 py-3 rounded relative">
                            {{ session('error') }}
                            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
                                <span class="text-red-200">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="mb-4 bg-blue-800 border border-blue-600 text-blue-200 px-4 py-3 rounded relative">
                            {{ session('info') }}
                            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
                                <span class="text-blue-200">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 bg-red-800 border border-red-600 text-red-200 px-4 py-3 rounded relative">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
                                <span class="text-red-200">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Lista de clientes (Checklist) -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-3">
                            <label class="block text-sm font-medium text-gray-200">
                                Clientes Disponibles:
                            </label>
                            <button type="button" id="limpiarSeleccion" 
                                    class="text-sm text-blue-400 hover:text-blue-300 font-medium">
                                Limpiar Selección
                            </button>
                        </div>
                        <div class="border border-zinc-600 rounded-lg p-4 bg-zinc-700">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2" id="clientesContainer">
                                @foreach($clientes as $cliente)
                                    <label class="flex items-center p-2 hover:bg-zinc-600 rounded cursor-pointer transition-colors">
                                        <input type="checkbox" 
                                               name="clientes_seleccionados[]" 
                                               value="{{ $cliente->id }}" 
                                               class="cliente-checkbox w-4 h-4 text-blue-500 bg-zinc-600 border-zinc-500 rounded focus:ring-blue-400 focus:ring-2">
                                        <span class="ml-2 text-sm text-gray-100">
                                            {{ $cliente->nombre }}
                                            @if($cliente->esCOS())
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-800 text-blue-200 ml-1">
                                                    COS
                                                </span>
                                            @endif
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div id="clientesSeleccionados" class="mt-2 text-sm text-gray-300">
                            <span class="font-medium">Seleccionados:</span> 
                            <span id="contadorClientes" class="text-blue-400">0</span>
                        </div>
                    </div>

                    <!-- Filtros de usuarios -->
                    <div class="mb-6 bg-zinc-700 p-4 rounded-lg">
                        <div class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-2 md:space-y-0">
                            <div class="flex-1">
                                <label for="filtroNombre" class="block text-sm font-medium text-gray-200 mb-1">
                                    Buscar por nombre:
                                </label>
                                <input type="text" id="filtroNombre" 
                                       class="w-full px-3 py-2 bg-zinc-600 border border-zinc-500 text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm placeholder-gray-400"
                                       placeholder="Escriba el nombre del usuario...">
                            </div>
                            <div class="flex-1">
                                <label for="filtroEmail" class="block text-sm font-medium text-gray-200 mb-1">
                                    Buscar por email:
                                </label>
                                <input type="text" id="filtroEmail" 
                                       class="w-full px-3 py-2 bg-zinc-600 border border-zinc-500 text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm placeholder-gray-400"
                                       placeholder="Escriba el email del usuario...">
                            </div>
                            <div class="flex-shrink-0 mt-6 md:mt-6">
                                <button type="button" id="limpiarFiltros" 
                                        class="bg-zinc-600 hover:bg-zinc-500 text-gray-100 font-medium py-2 px-4 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-gray-400">
                                    Limpiar Filtros
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de usuarios -->
                    <div class="bg-zinc-800 rounded-lg border border-zinc-600">
                        <div class="px-6 py-4 border-b border-zinc-600">
                            <h3 class="text-lg font-medium text-gray-100">Usuarios del Sistema</h3>
                            <p class="text-sm text-gray-300 mt-1">
                                Presione "Asignar" para asignar los clientes seleccionados al usuario correspondiente.
                            </p>
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
                                            Clientes Actuales
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-zinc-800 divide-y divide-gray-600" id="tablaUsuarios">
                                    @foreach($usuarios as $usuario)
                                        <tr class="usuario-row hover:bg-zinc-700" 
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
                                                @if($usuario->clientes->isEmpty())
                                                    <span class="text-gray-500 italic">Sin clientes asignados</span>
                                                @else
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($usuario->clientes as $cliente)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                                                         {{ $cliente->esCOS() ? 'bg-blue-800 text-blue-200' : 'bg-zinc-600 text-gray-200' }}">
                                                                {{ $cliente->nombre }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <!-- Botón Asignar -->
                                                    <button type="button" 
                                                            class="asignar-btn bg-blue-600 hover:bg-blue-500 disabled:bg-zinc-600 disabled:cursor-not-allowed text-white font-medium py-2 px-3 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                                                            data-usuario-id="{{ $usuario->id }}"
                                                            data-usuario-nombre="{{ $usuario->name }}"
                                                            disabled>
                                                        Asignar
                                                    </button>
                                                    
                                                    <!-- Botón Desasignar (solo mostrar si tiene clientes) -->
                                                    @if(!$usuario->clientes->isEmpty())
                                                    <form action="{{ route('user-cliente.removeAll') }}" method="POST" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ $usuario->id }}">
                                                        <button type="submit" 
                                                                class="bg-red-600 hover:bg-red-500 text-white p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors"
                                                                onclick="return confirm('¿Está seguro de desasignar TODOS los clientes de {{ $usuario->name }}?')"
                                                                title="Desasignar todos los clientes">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    @endif
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

    <!-- Meta tag para CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const clienteCheckboxes = document.querySelectorAll('.cliente-checkbox');
            const asignarBtns = document.querySelectorAll('.asignar-btn');
            const limpiarSeleccionBtn = document.getElementById('limpiarSeleccion');
            const contadorClientes = document.getElementById('contadorClientes');
            const filtroNombre = document.getElementById('filtroNombre');
            const filtroEmail = document.getElementById('filtroEmail');
            const limpiarFiltrosBtn = document.getElementById('limpiarFiltros');
            const usuarioRows = document.querySelectorAll('.usuario-row');

            // Función para actualizar contador y estado de botones
            function actualizarEstadoBotones() {
                const clientesSeleccionados = document.querySelectorAll('.cliente-checkbox:checked');
                const count = clientesSeleccionados.length;
                
                contadorClientes.textContent = count;
                
                // Habilitar/deshabilitar botones de asignar
                asignarBtns.forEach(btn => {
                    btn.disabled = count === 0;
                });
            }

            // Event listeners para checkboxes de clientes
            clienteCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', actualizarEstadoBotones);
            });

            // Limpiar selección de clientes
            limpiarSeleccionBtn.addEventListener('click', function() {
                clienteCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                actualizarEstadoBotones();
            });

            // Función para filtrar usuarios
            function filtrarUsuarios() {
                const filtroNombreValue = filtroNombre.value.toLowerCase().trim();
                const filtroEmailValue = filtroEmail.value.toLowerCase().trim();

                usuarioRows.forEach(row => {
                    const nombre = row.getAttribute('data-nombre');
                    const email = row.getAttribute('data-email');
                    
                    const coincideNombre = filtroNombreValue === '' || nombre.includes(filtroNombreValue);
                    const coincideEmail = filtroEmailValue === '' || email.includes(filtroEmailValue);
                    
                    if (coincideNombre && coincideEmail) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Event listeners para filtros
            filtroNombre.addEventListener('input', filtrarUsuarios);
            filtroEmail.addEventListener('input', filtrarUsuarios);

            // Limpiar filtros
            limpiarFiltrosBtn.addEventListener('click', function() {
                filtroNombre.value = '';
                filtroEmail.value = '';
                filtrarUsuarios();
            });

            // Función para asignar clientes
            asignarBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const usuarioId = this.getAttribute('data-usuario-id');
                    const usuarioNombre = this.getAttribute('data-usuario-nombre');
                    const clientesSeleccionados = Array.from(document.querySelectorAll('.cliente-checkbox:checked'))
                        .map(checkbox => checkbox.value);

                    if (clientesSeleccionados.length === 0) {
                        alert('Por favor, seleccione al menos un cliente.');
                        return;
                    }

                    // Confirmar asignación
                    if (!confirm(`¿Está seguro de asignar ${clientesSeleccionados.length} cliente(s) a ${usuarioNombre}?`)) {
                        return;
                    }

                    // Guardar referencia al botón
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("user-cliente.store") }}';

                    // Mostrar estado de carga
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.appendChild(csrfToken);

                    const userIdInput = document.createElement('input');
                    userIdInput.type = 'hidden';
                    userIdInput.name = 'user_id';
                    userIdInput.value = usuarioId;
                    form.appendChild(userIdInput);

                    clientesSeleccionados.forEach(clienteId => {
                        const clienteInput = document.createElement('input');
                        clienteInput.type = 'hidden';
                        clienteInput.name = 'clientes[]';
                        clienteInput.value = clienteId;
                        form.appendChild(clienteInput);
                    });

                    document.body.appendChild(form);
                    form.submit();
                });
            });

            // Inicializar estado de botones
            actualizarEstadoBotones();
        });
    </script>
</x-app-layout>