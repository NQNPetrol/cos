<x-app-layout>
    <div class="max-w-7xl py-8 sm:px-6 lg:px-8 bg-gray-800 rounded-lg shadow">
        <h2 class="text-2xl font-semibold text-gray-50 mb-6">Administración de Usuarios y Roles</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-gray-800 shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-50 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-50 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-50 uppercase">Rol Actual</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-50 uppercase">Cambiar Rol</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-600 divide-y divide-gray-700 text-sm text-gray-200">
                    @foreach($usuarios as $usuario)
                        <tr>
                            <td class="px-6 py-4">{{ $usuario->name }}</td>
                            <td class="px-6 py-4">{{ $usuario->email }}</td>
                            <td class="px-6 py-4">
                                {{ $usuario->getRoleNames()->implode(', ') ?: 'Sin Rol' }}
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('usuarios.roles', $usuario) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    <select name="rol" class="border-gray-300 text-gray-700 rounded px-2 py-1 shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                                        @foreach($roles as $rol)
                                            <option value="{{ $rol->name }}" {{ $usuario->hasRole($rol->name) ? 'selected' : '' }}>
                                                {{ $rol->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
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
</x-app-layout>
