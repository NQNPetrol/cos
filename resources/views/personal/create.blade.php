<x-app-layout>
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Crear Nuevo Personal</h2>

        @if ($errors->any())
            <div class="bg-red-600 text-white p-4 rounded mb-4">
                <strong>¡Revisa los errores!</strong>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('personal.store') }}" method="POST" class="bg-gray-900 text-gray-100 p-6 rounded-lg shadow">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm mb-1">Nombre <span class="text-red-500">*</span></label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}"
                        class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm mb-1">Apellido <span class="text-red-500">*</span></label>
                    <input type="text" name="apellido" value="{{ old('apellido') }}"
                        class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm mb-1">Documento</label>
                    <input type="text" name="documento" value="{{ old('documento') }}"
                        class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm mb-1">Fecha de Inicio</label>
                    <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}"
                        class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm mb-1">Puesto</label>
                    <input type="text" name="puesto" value="{{ old('puesto') }}"
                        class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm mb-1">Cargo</label>
                    <input type="text" name="puesto" value="{{ old('puesto') }}"
                        class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm mb-1">Categoría</label>
                    <select name="categoria_id" class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2">
                        <option value="">Sin categoría</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm mb-1">Estado <span class="text-red-500">*</span></label>
                    <select name="estado" class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2" required>
                        <option value="activo" {{ old('estado', 'activo') == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Espera</option>
                        <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded text-white">Guardar</button>
                <a href="{{ route('personal.index') }}" class="ml-3 text-gray-300 hover:underline">Cancelar</a>
            </div>
        </form>
    </div>
</x-app-layout>