<!-- Modal para crear/editar pago -->
<div id="pago-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-gray-800 border-gray-700">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 id="pago-modal-title" class="text-lg font-medium text-gray-100">Nuevo Pago</h3>
                <button onclick="closePagoModal()"
                    class="text-gray-400 hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="pago-form" method="POST" action="{{ route('rodados.pagos.store') }}">
                @csrf
                <input type="hidden" id="pago-id" name="id">
                @method('POST')

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Vehículo *</label>
                        <select id="pago-rodado" name="rodado_id" required onchange="togglePagoFields()"
                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                            <option value="">Seleccione un vehículo</option>
                            @foreach($rodados as $rodado)
                                <option value="{{ $rodado->id }}" data-es-propio="{{ $rodado->es_propio ? '1' : '0' }}">
                                    {{ $rodado->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Pago *</label>
                        <select id="pago-tipo" name="tipo" required onchange="togglePagoFields()"
                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                            <option value="pago_patente">Pago patente</option>
                            <option value="pago_alquiler">Pago alquiler</option>
                            <option value="pago_a_proveedor">Pago a proveedor</option>
                            <option value="pago_seguro">Pago seguro</option>
                            <option value="pago_servicio_starlink">Pago servicio Starlink</option>
                            <option value="pago_vtv">Pago VTV</option>
                            <option value="pagos_adicionales">Pagos adicionales</option>
                        </select>
                    </div>

                    <div id="pago-proveedor-field" style="display: none;">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Proveedor *</label>
                        <select id="pago-proveedor" name="proveedor_id"
                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                            <option value="">Seleccione un proveedor</option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Fecha de Pago *</label>
                        <input type="date" id="pago-fecha" name="fecha_pago" required
                            value="{{ date('Y-m-d') }}"
                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Moneda *</label>
                            <select id="pago-moneda" name="moneda" required
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                                <option value="ARS">Pesos argentinos ($)</option>
                                <option value="USD">Dólares (USD$)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Monto *</label>
                            <input type="number" id="pago-monto" name="monto" step="0.01" min="0" required
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500"
                                placeholder="0.00">
                        </div>
                    </div>

                    <div id="pago-monto-service-field" style="display: none;">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Monto Service (incluido en alquiler)</label>
                        <input type="number" id="pago-monto-service" name="monto_service" step="0.01" min="0"
                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closePagoModal()"
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
    function togglePagoFields() {
        const tipo = document.getElementById('pago-tipo').value;
        const rodadoSelect = document.getElementById('pago-rodado');
        const rodadoOption = rodadoSelect.options[rodadoSelect.selectedIndex];
        const esPropio = rodadoOption ? rodadoOption.dataset.esPropio === '1' : false;
        
        const proveedorField = document.getElementById('pago-proveedor-field');
        const montoServiceField = document.getElementById('pago-monto-service-field');
        const proveedorSelect = document.getElementById('pago-proveedor');

        // Mostrar proveedor solo para alquiler
        if (tipo === 'pago_alquiler') {
            proveedorField.style.display = 'block';
            proveedorSelect.required = true;
            montoServiceField.style.display = 'block';
        } else {
            proveedorField.style.display = 'none';
            proveedorSelect.required = false;
            proveedorSelect.value = '';
            montoServiceField.style.display = 'none';
            document.getElementById('pago-monto-service').value = '';
        }

        // Ajustar opciones según tipo de vehículo
        if (esPropio && tipo === 'pago_alquiler') {
            // Si es propio, no debería permitir pago_alquiler
            alert('Este vehículo es propio. No se puede registrar pago de alquiler.');
            document.getElementById('pago-tipo').value = 'pago_patente';
            togglePagoFields();
        }
    }

    document.getElementById('pago-rodado')?.addEventListener('change', togglePagoFields);
    document.getElementById('pago-tipo')?.addEventListener('change', togglePagoFields);

    function closePagoModal() {
        document.getElementById('pago-modal').classList.add('hidden');
        document.getElementById('pago-form').reset();
    }

    // Validaciones del formulario de pago
    document.getElementById('pago-form')?.addEventListener('submit', function(e) {
        const rodado = document.getElementById('pago-rodado')?.value;
        const tipo = document.getElementById('pago-tipo')?.value;
        const fechaPago = document.getElementById('pago-fecha')?.value;
        const moneda = document.getElementById('pago-moneda')?.value;
        const monto = document.getElementById('pago-monto')?.value;

        // Validaciones básicas
        if (!rodado) {
            e.preventDefault();
            alert('Por favor, seleccione un vehículo.');
            return false;
        }

        if (!tipo) {
            e.preventDefault();
            alert('Por favor, seleccione un tipo de pago.');
            return false;
        }

        if (!fechaPago) {
            e.preventDefault();
            alert('Por favor, ingrese la fecha de pago.');
            return false;
        }

        if (!moneda) {
            e.preventDefault();
            alert('Por favor, seleccione una moneda.');
            return false;
        }

        if (!monto || parseFloat(monto) <= 0) {
            e.preventDefault();
            alert('Por favor, ingrese un monto válido (mayor a 0).');
            return false;
        }

        // Validar formato de monto
        if (isNaN(parseFloat(monto))) {
            e.preventDefault();
            alert('Por favor, ingrese un monto numérico válido.');
            return false;
        }

        // Validar que el monto no exceda un límite razonable (opcional)
        if (parseFloat(monto) > 100000000) {
            if (!confirm('El monto ingresado es muy alto. ¿Está seguro que es correcto?')) {
                e.preventDefault();
                return false;
            }
        }

        const id = document.getElementById('pago-id')?.value;
        if (id) {
            this.action = '{{ route("rodados.pagos.update", ":id") }}'.replace(':id', id);
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            this.appendChild(methodInput);
        }
    });
</script>
