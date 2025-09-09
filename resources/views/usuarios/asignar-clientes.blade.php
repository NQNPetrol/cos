<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asignar Clientes a Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Mensajes de estado -->
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Formulario -->
                    <div class="mb-6">
                        <form id="asignacionForm" method="POST" action="{{ route('usuarios.store-asignacion-clientes') }}">
                            @csrf
                            
                            <!-- Selector de usuario -->
                            <div class="mb-6">
                                <label for="user_select" class="block text-sm font-medium text-gray-700 mb-2">
                                    Seleccionar Usuario:
                                </label>
                                <select id="user_select" name="user_id" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Seleccionar usuario --</option>
                                    @foreach($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}">
                                            {{ $usuario->name }} ({{ $usuario->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Lista de clientes -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Clientes Asignados:
                                </label>
                                <div class="border border-gray-300 rounded-lg p-4 max-h-96 overflow-y-auto">
                                    <div class="space-y-2">
                                        @foreach($clientes as $cliente)
                                            <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                                <input type="checkbox" 
                                                       name="clientes[]" 
                                                       value="{{ $cliente->id }}" 
                                                       class="cliente-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                <span class="ml-2 text-sm text-gray-900">
                                                    {{ $cliente->nombre }}
                                                    @if($cliente->esCOS())
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                                            COS
                                                        </span>
                                                    @endif
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="flex space-x-3">
                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Asignar Clientes
                                </button>
                                <a href="{{ route('usuarios.index') }}" 
                                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Volver
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Tabla de usuarios actuales -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Estado Actual de Asignaciones</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Usuario
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Clientes Asignados
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($usuarios as $usuario)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $usuario->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $usuario->email }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($usuario->clientes->isEmpty())
                                                    <span class="text-gray-400 italic">Sin clientes asignados</span>
                                                @else
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($usuario->clientes as $cliente)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                                         {{ $cliente->esCOS() ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                                {{ $cliente->nombre }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <button onclick="editarAsignacion({{ $usuario->id }})" 
                                                        class="text-blue-600 hover:text-blue-900">
                                                    Editar
                                                </button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userSelect = document.getElementById('user_select');
            const clienteCheckboxes = document.querySelectorAll('.cliente-checkbox');

            // Cuando se selecciona un usuario, cargar sus clientes asignados
            userSelect.addEventListener('change', function() {
                const userId = this.value;
                
                // Limpiar checkboxes
                clienteCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });

                
            });
        });

        // Función para editar desde la tabla
        function editarAsignacion(userId) {
            const userSelect = document.getElementById('user_select');
            userSelect.value = userId;
            userSelect.dispatchEvent(new Event('change'));
            
            // Scroll al formulario
            document.getElementById('asignacionForm').scrollIntoView({ behavior: 'smooth' });
        }
    </script>

    <!-- Meta tag para CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</x-app-layout>