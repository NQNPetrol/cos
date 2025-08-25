<x-app-layout>
    <div class="container mx-auto p-6">
        <h2 class="text-gray-200 text-2xl font-bold mb-4">Editar Personal</h2>

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

        <form x-data="{mostrarPuesto: {{ $personal->cargo == 'personal-seguridad' ? 'true' : 'false' }}}"  
              action="{{ route('personal.update', $personal->id) }}" method="POST" 
              class="bg-gray-900 text-gray-100 p-6 rounded-lg shadow">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm mb-1">Nombre <span class="text-red-500">*</span></label>
                    <input type="text" name="nombre" value="{{ old('nombre', $personal->nombre) }}"
                        class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm mb-1">Apellido <span class="text-red-500">*</span></label>
                    <input type="text" name="apellido" value="{{ old('apellido', $personal->apellido) }}"
                        class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm mb-1">Documento</label>
                    <input type="text" name="nro_doc" value="{{ old('nro_doc', $personal->nro_doc) }}"
                        class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm mb-1">Fecha de Inicio</label>
                    <input type="date" name="fecha_ing" value="{{ old('fecha_ing', $personal->fecha_ing) }}"
                        class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm mb-1">Cliente <span class="text-red-500">*</span></label>
                    <select name="cliente_id" class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2" required>
                        <option value="">Seleccione un cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ old('cliente_id', $personal->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm mb-1">Cargo<span class="text-red-500">*</span></label>
                    <select name="cargo" @change="mostrarPuesto = ($event.target.value == 'personal-seguridad')" 
                            class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2" required>
                        <option value="gerente" {{ old('cargo', $personal->cargo) == 'Gerente' ? 'selected' : '' }}>Gerente</option>
                        <option value="coordinador" {{ old('cargo', $personal->cargo) == 'Coordinador' ? 'selected' : '' }}>Coordinador</option>
                        <option value="supervisor" {{ old('cargo', $personal->cargo) == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                        <option value="operador" {{ old('cargo', $personal->cargo) == 'Operador' ? 'selected' : '' }}>Operador</option>
                        <option value="personal-seguridad" {{ old('cargo', $personal->cargo) == 'Personal de seguridad' ? 'selected' : '' }}>Personal de seguridad</option>
                    </select>
                </div>

                <!-- Campos condicionales -->
                <div x-show="mostrarPuesto">
                    <label class="block text-sm mb-1">Puesto <span class="text-red-500">*</span></label>
                    <select name="puesto" class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2" x-bind:required="mostrarPuesto">
                        <option value="">Seleccione un puesto</option>
                        <option value="Chofer de transporte" {{ old('puesto', $personal->puesto) == 'Chofer de transporte' ? 'selected' : '' }}>Chofer de transporte</option>
                        <option value="Chofer de patrulla" {{ old('puesto', $personal->puesto) == 'Chofer de patrulla' ? 'selected' : '' }}>Chofer de patrulla</option>
                        <option value="Guardia de puesto fijo" {{ old('puesto', $personal->puesto) == 'Guardia de puesto fijo' ? 'selected' : '' }}>Guardia de puesto fijo</option>
                    </select>
                </div>

                <div x-show="mostrarPuesto">
                    <label class="block text-sm mb-1">Convenio <span class="text-red-500">*</span></label>
                    <select name="convenio" class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2" x-bind:required="mostrarPuesto">
                        <option value="">Seleccione un convenio</option>
                        <option value="505" {{ old('convenio', $personal->convenio) == '505' ? 'selected' : '' }}>505</option>
                        <option value="507" {{ old('convenio', $personal->convenio) == '507' ? 'selected' : '' }}>507</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white">Actualizar</button>
                <a href="{{ route('personal.index') }}" class="ml-3 text-gray-300 hover:underline">Cancelar</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-app-layout>