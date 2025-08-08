<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-gray-900 text-gray-50 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6">Crear Contrato</h2>

        @if ($errors->any())
            <div class="bg-red-600 text-white p-4 mb-4 rounded">
                <strong>¡Error!</strong> Corrige los campos indicados.<br>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('contratos.store') }}" class="space-y-4">
            @csrf

            <!-- Cliente -->
            <div>
                <label class="block mb-1">Cliente <span class="text-red-500">*</span></label>
                <select name="cliente_id" id="cliente_id" required class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2">
                    <option value="">Seleccione un cliente</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ old('id_cliente') == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1">Empresa Asociada al cliente <span class="text-red-500">*</span></label>
                <select name="empresa_asociada_id" id="empresa_asociada_id" required class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2">
                    <option value="">Seleccione una empresa asociada</option>
                    @foreach ($empresasFiltradas as $empresa)
                        <option value="{{ $empresa->id }}" {{ old('empresa_asociada_id') == $empresa->id ? 'selected' : '' }}>
                            {{ $empresa->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Nombre Proyecto -->
            <div>
                <label class="block mb-1">Nombre del Proyecto <span class="text-red-500">*</span></label>
                <input type="text" name="nombre_proyecto" value="{{ old('nombre_proyecto') }}" required
                       class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2">
            </div>

            <!-- Localidad y Provincia -->
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1">Localidad</label>
                    <input type="text" name="localidad" value="{{ old('localidad') }}" 
                           class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2">
                </div>
                <div>
                    <label class="block mb-1">Provincia</label>
                    <input type="text" name="provincia" value="{{ old('provincia') }}" 
                           class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2">
                </div>
            </div>

            <!-- Observaciones -->
            <div>
                <label class="block mb-1">Observaciones</label>
                <textarea name="observaciones" rows="3"
                          class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2">{{ old('observaciones') }}</textarea>
            </div>

            <!-- Fechas -->
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" 
                           class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2">
                </div>

            </div>

            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded">
                Guardar Contrato
            </button>
            <a href="{{ route('contratos.index') }}" 
                class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2 text-sm ml-4 hover:underline">
                Cancelar
            </a>
        </form>
    </div>
</x-app-layout>
