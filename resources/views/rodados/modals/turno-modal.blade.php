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

            <form id="turno-form" method="POST" action="{{ route('rodados.turnos.store') }}" enctype="multipart/form-data">
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
                                    <option value="{{ $rodado->id }}">{{ $rodado->marca }} {{ $rodado->modelo }}</option>
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
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Turno *</label>
                            <select id="turno-tipo" name="tipo" required onchange="toggleTurnoFields()"
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                                <option value="turno_service">Turno Service</option>
                                <option value="turno_mecanico">Turno Mecánico</option>
                                <option value="cambio_equipo">Cambio de Equipo</option>
                                <option value="turno_taller">Turno al Taller</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Fecha y Hora *</label>
                            <input type="datetime-local" id="turno-fecha-hora" name="fecha_hora" required
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Campos para turno_mecanico -->
                    <div id="turno-mecanico-fields" style="display: none;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Reparación</label>
                                <input type="text" id="turno-tipo-reparacion" name="tipo_reparacion"
                                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="flex items-center mt-6">
                                    <input type="checkbox" id="turno-cubre-servicio" name="cubre_servicio" value="1"
                                        class="rounded bg-gray-700 border-gray-600 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-300">Cubre Servicio (Empresa paga)</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Descripción</label>
                            <textarea id="turno-descripcion" name="descripcion" rows="3"
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500"></textarea>
                        </div>
                    </div>

                    <!-- Campos para cambio_equipo -->
                    <div id="turno-cambio-equipo-fields" style="display: none;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Equipo</label>
                                <select id="turno-tipo-equipo" name="tipo_equipo"
                                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                                    <option value="">Seleccione</option>
                                    <option value="cubiertas">Cubiertas</option>
                                    <option value="antena_starlink">Antena Starlink</option>
                                    <option value="camara_mobil">Cámara Móvil</option>
                                    <option value="dvr">DVR</option>
                                </select>
                            </div>
                            <div id="turno-tipo-cubierta-field" style="display: none;">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Cubierta</label>
                                <input type="text" id="turno-tipo-cubierta" name="tipo_cubierta"
                                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Pago Mano de Obra</label>
                                <input type="number" id="turno-pago-mano-obra" name="pago_mano_obra" step="0.01" min="0"
                                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Campos comunes para service y otros -->
                    <div id="turno-encargados-fields">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Encargado de Dejar</label>
                                <input type="text" id="turno-encargado-dejar" name="encargado_dejar"
                                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Encargado de Retirar</label>
                                <input type="text" id="turno-encargado-retirar" name="encargado_retirar"
                                    class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Factura y Comprobante -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Fecha Factura</label>
                            <input type="date" id="turno-fecha-factura" name="fecha_factura"
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Días de Vencimiento</label>
                            <input type="number" id="turno-dias-vencimiento" name="dias_vencimiento" min="0"
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Factura (PDF o JPG)</label>
                            <input type="file" id="turno-factura" name="factura" accept=".pdf,.jpg,.jpeg"
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Comprobante de Pago (PDF o JPG)</label>
                            <input type="file" id="turno-comprobante" name="comprobante_pago" accept=".pdf,.jpg,.jpeg"
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Estado</label>
                        <select id="turno-estado" name="estado"
                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                            <option value="pendiente">Pendiente</option>
                            <option value="completado">Completado</option>
                        </select>
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
    function toggleTurnoFields() {
        const tipo = document.getElementById('turno-tipo').value;
        const mecanicoFields = document.getElementById('turno-mecanico-fields');
        const cambioEquipoFields = document.getElementById('turno-cambio-equipo-fields');
        const encargadosFields = document.getElementById('turno-encargados-fields');
        const tipoCubiertaField = document.getElementById('turno-tipo-cubierta-field');

        // Ocultar todos
        mecanicoFields.style.display = 'none';
        cambioEquipoFields.style.display = 'none';
        encargadosFields.style.display = 'block';

        if (tipo === 'turno_mecanico') {
            mecanicoFields.style.display = 'block';
        } else if (tipo === 'cambio_equipo') {
            cambioEquipoFields.style.display = 'block';
            encargadosFields.style.display = 'none';
        }

        // Mostrar campo tipo cubierta solo si es cambio de equipo y tipo es cubiertas
        const tipoEquipo = document.getElementById('turno-tipo-equipo').value;
        if (tipo === 'cambio_equipo' && tipoEquipo === 'cubiertas') {
            tipoCubiertaField.style.display = 'block';
        } else {
            tipoCubiertaField.style.display = 'none';
        }
    }

    document.getElementById('turno-tipo-equipo')?.addEventListener('change', function() {
        const tipoCubiertaField = document.getElementById('turno-tipo-cubierta-field');
        if (this.value === 'cubiertas') {
            tipoCubiertaField.style.display = 'block';
        } else {
            tipoCubiertaField.style.display = 'none';
        }
    });

    function closeTurnoModal() {
        document.getElementById('turno-modal').classList.add('hidden');
        document.getElementById('turno-form').reset();
    }

    // Actualizar método del formulario cuando se edita
    document.getElementById('turno-form')?.addEventListener('submit', function(e) {
        const id = document.getElementById('turno-id').value;
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

