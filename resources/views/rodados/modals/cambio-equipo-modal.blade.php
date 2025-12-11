<!-- Modal para crear/editar cambio de equipo -->
<div id="cambio-equipo-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-gray-800 border-gray-700">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 id="cambio-equipo-modal-title" class="text-lg font-medium text-gray-100">Nuevo Cambio de Equipo</h3>
                <button onclick="closeCambioEquipoModal()"
                    class="text-gray-400 hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="cambio-equipo-form" method="POST" action="{{ route('rodados.cambios-equipos.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="cambio-equipo-id" name="id">
                @method('POST')

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Vehículo *</label>
                            <select id="cambio-equipo-rodado" name="rodado_id" required
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                                <option value="">Seleccione un vehículo</option>
                                @foreach($rodados as $rodado)
                                    <option value="{{ $rodado->id }}">
                                        {{ $rodado->marca }} {{ $rodado->modelo }}
                                        @if($rodado->kilometrajeActual)
                                            ({{ number_format($rodado->kilometrajeActual->kilometraje, 0, ',', '.') }} km)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Taller *</label>
                            <select id="cambio-equipo-taller" name="taller_id" required
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
                            <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Equipo *</label>
                            <select id="cambio-equipo-tipo" name="tipo" required onchange="toggleCambioEquipoFields()"
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                                <option value="cubiertas">Cubiertas</option>
                                <option value="antena_starlink">Antena Starlink</option>
                                <option value="camara_mobil">Cámara Móvil</option>
                                <option value="dvr">DVR</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Fecha y Hora Estimada *</label>
                            <input type="datetime-local" id="cambio-equipo-fecha" name="fecha_hora_estimada" required
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                        </div>
                    </div>

                    <div id="cambio-equipo-tipo-cubierta-field">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Cubierta</label>
                        <input type="text" id="cambio-equipo-tipo-cubierta" name="tipo_cubierta"
                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500"
                            placeholder="Ej: Michelin, Bridgestone, etc.">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Pago Mano de Obra *</label>
                            <input type="number" id="cambio-equipo-pago" name="pago_mano_obra" step="0.01" min="0" required
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Kilometraje en el Cambio *</label>
                            <input type="number" id="cambio-equipo-kilometraje" name="kilometraje_en_cambio" min="0" required
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Factura (PDF o JPG)</label>
                            <input type="file" id="cambio-equipo-factura" name="factura" accept=".pdf,.jpg,.jpeg"
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Comprobante de Pago (PDF o JPG)</label>
                            <input type="file" id="cambio-equipo-comprobante" name="comprobante_pago" accept=".pdf,.jpg,.jpeg"
                                class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeCambioEquipoModal()"
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
    function toggleCambioEquipoFields() {
        const tipo = document.getElementById('cambio-equipo-tipo').value;
        const tipoCubiertaField = document.getElementById('cambio-equipo-tipo-cubierta-field');
        
        if (tipo === 'cubiertas') {
            tipoCubiertaField.style.display = 'block';
            document.getElementById('cambio-equipo-tipo-cubierta').required = false;
        } else {
            tipoCubiertaField.style.display = 'none';
            document.getElementById('cambio-equipo-tipo-cubierta').value = '';
        }
    }

    function closeCambioEquipoModal() {
        document.getElementById('cambio-equipo-modal').classList.add('hidden');
        document.getElementById('cambio-equipo-form').reset();
    }

    // Actualizar método del formulario cuando se edita
    document.getElementById('cambio-equipo-form')?.addEventListener('submit', function(e) {
        const id = document.getElementById('cambio-equipo-id').value;
        if (id) {
            this.action = '{{ route("rodados.cambios-equipos.update", ":id") }}'.replace(':id', id);
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            this.appendChild(methodInput);
        }
    });
</script>

