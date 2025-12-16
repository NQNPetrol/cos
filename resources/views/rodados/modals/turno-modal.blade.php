<!-- Modal para crear/editar turno -->
<div id="turno-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-3/4 shadow-lg rounded-md bg-gray-800 border-gray-700 max-h-[90vh] overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 id="turno-modal-title" class="text-lg font-medium text-gray-100">Nuevo Turno</h3>
                <button onclick="closeTurnoModal()"
                    class="text-gray-400 hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="turno-form" method="POST" action="{{ route('rodados.turnos.store') }}">
                @csrf
                <input type="hidden" id="turno-id" name="id">
                @method('POST')

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Vehículo *</label>
                            <select id="turno-rodado" name="rodado_id" required
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                                <option value="">Seleccione un vehículo</option>
                                @foreach($rodados as $rodado)
                                    <option value="{{ $rodado->id }}">{{ $rodado->patente ?? 'Sin patente' }} - Cliente: {{ $rodado->cliente->nombre ?? 'N/A' }} - Proveedor: {{ $rodado->proveedor->nombre ?? '-' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Taller *</label>
                            <select id="turno-taller" name="taller_id" required
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                                <option value="">Seleccione un taller</option>
                                @foreach($talleres as $taller)
                                    <option value="{{ $taller->id }}">{{ $taller->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div id="turno-tipo-field-container">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Turno *</label>
                            <select id="turno-tipo" name="tipo" required onchange="toggleTurnoFields()"
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                                <option value="turno_service">Turno Service</option>
                                <option value="turno_taller">Turno al Taller</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Fecha y Hora *</label>
                            <input type="datetime-local" id="turno-fecha-hora" name="fecha_hora" required
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                        </div>
                    </div>
                    <!-- Input hidden para tipo cuando se establece automáticamente -->
                    <input type="hidden" id="turno-tipo-hidden" name="tipo" value="">

                    <!-- Campos para turno_taller (guardado como turno_mecanico) -->
                    <div id="turno-taller-fields" style="display: none;">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Motivo del turno *</label>
                            <textarea id="turno-descripcion-taller" name="descripcion" rows="3" required
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500"
                                placeholder="Describe el motivo del turno..."></textarea>
                        </div>

                        <!-- Tabla dinámica de partes afectadas -->
                        <div class="mt-4">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-300">Partes afectadas del vehículo</label>
                                <button type="button" onclick="agregarFilaParte()" 
                                    class="text-sm px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                    + Agregar fila
                                </button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-700 border border-gray-600 rounded-lg">
                                    <thead class="bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">Item</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">Cantidad</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">Descripción</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="partes-afectadas-body" class="bg-gray-800 divide-y divide-gray-700">
                                        <!-- Las filas se agregarán dinámicamente -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Encargado de Dejar</label>
                                <input type="text" id="turno-encargado-dejar-taller" name="encargado_dejar"
                                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Encargado de Retirar</label>
                                <input type="text" id="turno-encargado-retirar-taller" name="encargado_retirar"
                                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Campos para turno_service -->
                    <div id="turno-service-fields">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Encargado de Dejar</label>
                                <input type="text" id="turno-encargado-dejar-service" name="encargado_dejar"
                                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Encargado de Retirar</label>
                                <input type="text" id="turno-encargado-retirar-service" name="encargado_retirar"
                                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Anotaciones</label>
                            <textarea id="turno-descripcion-service" name="descripcion" rows="3"
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500"
                                placeholder="Anotaciones sobre el servicio..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeTurnoModal()"
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
    let filaParteCounter = 0;

    function toggleTurnoFields() {
        const tipoSelect = document.getElementById('turno-tipo');
        const tipoHidden = document.getElementById('turno-tipo-hidden');
        const tipo = tipoSelect ? tipoSelect.value : (tipoHidden ? tipoHidden.value : '');
        const tallerFields = document.getElementById('turno-taller-fields');
        const serviceFields = document.getElementById('turno-service-fields');

        if (tipo === 'turno_taller' || tipo === 'turno_mecanico') {
            tallerFields.style.display = 'block';
            serviceFields.style.display = 'none';
        } else {
            tallerFields.style.display = 'none';
            serviceFields.style.display = 'block';
        }
    }

    function agregarFilaParte() {
        filaParteCounter++;
        const tbody = document.getElementById('partes-afectadas-body');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="px-4 py-2">
                <input type="text" name="partes_afectadas[${filaParteCounter}][item]" required
                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-2 py-1 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
            </td>
            <td class="px-4 py-2">
                <input type="number" name="partes_afectadas[${filaParteCounter}][cantidad]" required min="1"
                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-2 py-1 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
            </td>
            <td class="px-4 py-2">
                <input type="text" name="partes_afectadas[${filaParteCounter}][descripcion]" required
                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-2 py-1 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
            </td>
            <td class="px-4 py-2">
                <button type="button" onclick="eliminarFilaParte(this)" 
                    class="text-red-400 hover:text-red-300 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    function eliminarFilaParte(button) {
        button.closest('tr').remove();
    }

    function closeTurnoModal() {
        document.getElementById('turno-modal').classList.add('hidden');
        document.getElementById('turno-form').reset();
        // Limpiar tabla de partes afectadas
        document.getElementById('partes-afectadas-body').innerHTML = '';
        filaParteCounter = 0;
    }

    // Validaciones del formulario de turnos
    document.getElementById('turno-form')?.addEventListener('submit', function(e) {
        const tipoSelect = document.getElementById('turno-tipo');
        const tipoHidden = document.getElementById('turno-tipo-hidden');
        const tipo = tipoHidden && tipoHidden.value ? tipoHidden.value : (tipoSelect ? tipoSelect.value : '');
        const rodado = document.getElementById('turno-rodado')?.value;
        const taller = document.getElementById('turno-taller')?.value;
        const fechaHora = document.getElementById('turno-fecha-hora')?.value;
        
        // Si hay tipo hidden, usar ese valor y establecerlo en el select para el envío
        if (tipoHidden && tipoHidden.value) {
            if (tipoSelect) {
                tipoSelect.value = tipoHidden.value === 'turno_mecanico' ? 'turno_taller' : tipoHidden.value;
            }
        }

        // Validaciones básicas
        if (!rodado) {
            e.preventDefault();
            alert('Por favor, seleccione un vehículo.');
            return false;
        }

        if (!taller) {
            e.preventDefault();
            alert('Por favor, seleccione un taller.');
            return false;
        }

        if (!fechaHora) {
            e.preventDefault();
            alert('Por favor, ingrese una fecha y hora.');
            return false;
        }

        // Validar fecha futura
        const fechaInput = new Date(fechaHora);
        const fechaActual = new Date();
        if (fechaInput <= fechaActual) {
            if (!confirm('La fecha seleccionada es anterior o igual a la fecha actual. ¿Desea continuar de todas formas?')) {
                e.preventDefault();
                return false;
            }
        }

        // Validaciones específicas para turno_mecanico
        if (tipo === 'turno_taller' || tipo === 'turno_mecanico') {
            const motivo = document.getElementById('turno-descripcion-taller')?.value;
            // Validar que el motivo no esté vacío (sin validar mínimo de caracteres)
            if (!motivo || motivo.trim() === '') {
                e.preventDefault();
                alert('Por favor, ingrese el motivo del turno.');
                document.getElementById('turno-descripcion-taller')?.focus();
                return false;
            }

            // Validar que haya al menos una parte afectada
            const partesRows = document.querySelectorAll('#partes-afectadas-body tr');
            if (partesRows.length === 0) {
                if (!confirm('No se han agregado partes afectadas. ¿Desea continuar de todas formas?')) {
                    e.preventDefault();
                    return false;
                }
            } else {
                // Validar que todas las filas tengan datos completos
                let filaIncompleta = false;
                partesRows.forEach(row => {
                    const item = row.querySelector('input[name*="[item]"]')?.value;
                    const cantidad = row.querySelector('input[name*="[cantidad]"]')?.value;
                    const descripcion = row.querySelector('input[name*="[descripcion]"]')?.value;
                    
                    if ((item && item.trim()) || (cantidad && cantidad.trim()) || (descripcion && descripcion.trim())) {
                        if (!item || !cantidad || !descripcion) {
                            filaIncompleta = true;
                        }
                    }
                });
                
                if (filaIncompleta) {
                    e.preventDefault();
                    alert('Por favor, complete todos los campos de las partes afectadas o elimine las filas incompletas.');
                    return false;
                }
            }
        }

        const id = document.getElementById('turno-id')?.value;
        if (id) {
            this.action = '{{ route("rodados.turnos.update", ":id") }}'.replace(':id', id);
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            this.appendChild(methodInput);
        }
    });
</script>
