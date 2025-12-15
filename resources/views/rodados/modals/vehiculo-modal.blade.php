<!-- Modal para crear/editar vehículo -->
<div id="vehiculo-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-gray-800 border-gray-700">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 id="vehiculo-modal-title" class="text-lg font-medium text-gray-100">Nuevo Vehículo</h3>
                <button onclick="document.getElementById('vehiculo-modal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="vehiculo-form" method="POST" action="{{ route('rodados.store') }}">
                @csrf
                <input type="hidden" id="vehiculo-id" name="id">
                @method('POST')

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Marca *</label>
                            <input type="text" id="vehiculo-marca" name="marca" required
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Modelo *</label>
                            <input type="text" id="vehiculo-modelo" name="modelo" required
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Vehículo *</label>
                            <input type="text" id="vehiculo-tipo" name="tipo_vehiculo" required
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500"
                                placeholder="Ej: Camioneta, Auto, Moto">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Año *</label>
                            <input type="number" id="vehiculo-año" name="año" required min="1900" max="{{ date('Y') + 1 }}"
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Cliente *</label>
                        <select id="vehiculo-cliente" name="cliente_id" required
                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                            <option value="">Seleccione un cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" id="vehiculo-es-propio" name="es_propio" value="1" checked
                                onchange="toggleProveedorField()"
                                class="rounded bg-gray-700 border-gray-600 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-300">Vehículo propio de la empresa</span>
                        </label>
                    </div>

                    <div id="proveedor-field" style="display: none;">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Proveedor</label>
                        <select id="vehiculo-proveedor" name="proveedor_id"
                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                            <option value="">Seleccione un proveedor</option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Patente</label>
                        <input type="text" id="vehiculo-patente" name="patente"
                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500"
                            placeholder="Ej: ABC123">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="document.getElementById('vehiculo-modal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-700 text-gray-300 rounded-md hover:bg-gray-600 transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Actualizar método del formulario cuando se edita
    document.getElementById('vehiculo-form').addEventListener('submit', function(e) {
        const id = document.getElementById('vehiculo-id').value;
        if (id) {
            this.action = '{{ route("rodados.update", ":id") }}'.replace(':id', id);
            // Crear input hidden para método PUT
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            this.appendChild(methodInput);
        }
    });
</script>

