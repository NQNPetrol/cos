<x-app-layout>
    <div class="container mx-auto p-6">
        <h2 class="text-gray-200 text-2xl font-bold mb-4">Crear Nuevo Personal</h2>

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

        <form x-data="{mostrarPuesto: {{ old('cargo') == 'Personal de seguridad' ? 'true' : 'false' }}}"  action="{{ route('personal.store') }}" method="POST" class="bg-gray-900 text-gray-100 p-6 rounded-lg shadow">
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
                    <label class="block text-sm mb-1">Cargo<span class="text-red-500">*</span></label>
                    <select name="cargo" @change="mostrarPuesto = ($event.target.value == 'Personal de seguridad')" 
                            class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2" required>
                        <option value="Gerente" {{ old('cargo') == 'Gerente' ? 'selected' : '' }}>Gerente</option>
                        <option value="Coordinador" {{ old('cargo') == 'Coordinador' ? 'selected' : '' }}>Coordinador</option>
                        <option value="Supervisor" {{ old('cargo') == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                        <option value="Operador" {{ old('cargo') == 'Operador' ? 'selected' : '' }}>Operador</option>
                        <option value="Personal de seguridad" {{ old('cargo') == 'Personal de seguridad' ? 'selected' : '' }}>Personal de seguridad</option>
                    </select>
                </div>

                <!-- Campos condicionales -->
                <div x-show="mostrarPuesto">
                    <label class="block text-sm mb-1">Puesto <span class="text-red-500">*</span></label>
                    <select name="puesto" class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2" x-bind:required="mostrarPuesto">
                        <option value="">Seleccione un puesto</option>
                        <option value="Chofer de transporte" {{ old('puesto') == 'Chofer de transporte' ? 'selected' : '' }}>Chofer de transporte</option>
                        <option value="Chofer de patrulla" {{ old('puesto') == 'Chofer de patrulla' ? 'selected' : '' }}>Chofer de patrulla</option>
                        <option value="Guardia de puesto fijo" {{ old('puesto') == 'Guardia de puesto fijo' ? 'selected' : '' }}>Guardia de puesto fijo</option>
                    </select>
                </div>

                <div x-show="mostrarPuesto">
                    <label class="block text-sm mb-1">Convenio <span class="text-red-500">*</span></label>
                    <select name="convenio" class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2" x-bind:required="mostrarPuesto">
                        <option value="">Seleccione un convenio</option>
                        <option value="505" {{ old('convenio') == '505' ? 'selected' : '' }}>505</option>
                        <option value="507" {{ old('convenio') == '507' ? 'selected' : '' }}>507</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded text-white">Guardar</button>
                <a href="{{ route('personal.index') }}" class="ml-3 text-gray-300 hover:underline">Cancelar</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-app-layout>