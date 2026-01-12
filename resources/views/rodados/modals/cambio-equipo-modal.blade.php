<!-- Modal para crear/editar cambio de equipo -->
<div id="cambio-equipo-modal" class="hidden fixed inset-0 bg-zinc-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-3/4 shadow-lg rounded-md bg-zinc-800 border-zinc-700 max-h-[90vh] overflow-y-auto">
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

            <form id="cambio-equipo-form" method="POST" action="{{ route('rodados.cambios-equipos.store') }}">
                @csrf
                <input type="hidden" id="cambio-equipo-id" name="id">
                @method('POST')

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Vehículo *</label>
                            <select id="cambio-equipo-rodado" name="rodado_id" required
                                class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                                <option value="">Seleccione un vehículo</option>
                                @foreach($rodados as $rodado)
                                    <option value="{{ $rodado->id }}">{{ $rodado->patente ?? 'Sin patente' }} - Cliente: {{ $rodado->cliente->nombre ?? 'N/A' }} - Proveedor: {{ $rodado->proveedor->nombre ?? '-' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Taller *</label>
                            <select id="cambio-equipo-taller" name="taller_id" required
                                class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
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
                                class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                                <option value="cubiertas">Cubiertas</option>
                                <option value="antena_starlink">Antena Starlink</option>
                                <option value="camara_mobil">Cámara Móvil</option>
                                <option value="dvr">DVR</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Fecha y Hora Estimada *</label>
                            <input type="datetime-local" id="cambio-equipo-fecha" name="fecha_hora_estimada" required
                                class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Campo tipo cubierta (solo si tipo == cubiertas) -->
                    <div id="cambio-equipo-tipo-cubierta-field" style="display: none;">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Cubierta *</label>
                        <input type="text" id="cambio-equipo-tipo-cubierta" name="tipo_cubierta"
                            class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                    </div>

                    <!-- Campos para equipos que requieren dispositivos -->
                    <div id="cambio-equipo-dispositivos-fields" style="display: none;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Detalle del equipo a reemplazar</label>
                                <textarea id="cambio-equipo-detalle-equipo-reemplazar" name="detalle_equipo_viejo" rows="3"
                                    class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500"
                                    placeholder="Ingrese los detalles del equipo a reemplazar..."></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Nuevo equipo *</label>
                                <select id="cambio-equipo-dispositivo-nuevo" name="dispositivo_id" onchange="toggleDetalleEquipoNuevo()"
                                    class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                                    <option value="">Seleccione del inventario</option>
                                    <option value="manual">No está en inventario</option>
                                    <!-- Las opciones se cargarán dinámicamente según el tipo -->
                                </select>
                                <div id="cambio-equipo-detalle-nuevo-manual" style="display: none;" class="mt-2">
                                    <textarea name="detalle_equipo_nuevo" rows="2"
                                        class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500"
                                        placeholder="Detalles del equipo nuevo..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Costo mano de obra (opcional)</label>
                        <input type="number" id="cambio-equipo-pago" name="pago_mano_obra" step="0.01" min="0"
                            class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Motivo cambio</label>
                        <textarea id="cambio-equipo-motivo" name="motivo" rows="3"
                            class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500"
                            placeholder="Describe el motivo del cambio..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeCambioEquipoModal()"
                        class="px-4 py-2 bg-zinc-700 text-gray-300 rounded-md hover:bg-zinc-600 transition">
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
    // Dispositivos disponibles desde el servidor
    const dispositivosDisponibles = @json($dispositivos ?? []);
    
    // Mapeo de tipos de equipo a tipos de dispositivo
    const mapeoTipoDispositivo = {
        'camara_mobil': 'camara_ip',
        'antena_starlink': 'antena_starlink',
        'dvr': 'nvr_dvr'
    };
    
    function toggleCambioEquipoFields() {
        const tipo = document.getElementById('cambio-equipo-tipo').value;
        const tipoCubiertaField = document.getElementById('cambio-equipo-tipo-cubierta-field');
        const dispositivosFields = document.getElementById('cambio-equipo-dispositivos-fields');
        
        // Tipos que requieren dispositivos
        const tiposConDispositivos = ['antena_starlink', 'camara_mobil', 'dvr'];
        
        if (tipo === 'cubiertas') {
            tipoCubiertaField.style.display = 'block';
            document.getElementById('cambio-equipo-tipo-cubierta').required = true;
            dispositivosFields.style.display = 'none';
        } else if (tiposConDispositivos.includes(tipo)) {
            tipoCubiertaField.style.display = 'none';
            document.getElementById('cambio-equipo-tipo-cubierta').required = false;
            dispositivosFields.style.display = 'block';
            
            // Actualizar el dropdown de nuevo equipo según el tipo
            actualizarDropdownDispositivos(tipo);
        } else {
            tipoCubiertaField.style.display = 'none';
            dispositivosFields.style.display = 'none';
        }
    }
    
    function actualizarDropdownDispositivos(tipoEquipo) {
        const tipoDispositivo = mapeoTipoDispositivo[tipoEquipo];
        const selectNuevo = document.getElementById('cambio-equipo-dispositivo-nuevo');
        
        if (!selectNuevo || !tipoDispositivo) return;
        
        // Limpiar opciones existentes (mantener las primeras dos: vacío y manual)
        while (selectNuevo.options.length > 2) {
            selectNuevo.remove(2);
        }
        
        // Filtrar dispositivos según el tipo
        const dispositivosFiltrados = dispositivosDisponibles.filter(d => d.tipo === tipoDispositivo);
        
        // Agregar opciones filtradas
        dispositivosFiltrados.forEach(dispositivo => {
            const option = document.createElement('option');
            option.value = dispositivo.id;
            option.textContent = `${dispositivo.nombre}${dispositivo.numero_serie ? ' (' + dispositivo.numero_serie + ')' : ''}`;
            option.setAttribute('data-tipo', dispositivo.tipo);
            selectNuevo.appendChild(option);
        });
        
        // Resetear el valor seleccionado
        selectNuevo.value = '';
    }

    function toggleDetalleEquipoNuevo() {
        const select = document.getElementById('cambio-equipo-dispositivo-nuevo');
        const manualField = document.getElementById('cambio-equipo-detalle-nuevo-manual');
        if (select && select.value === 'manual') {
            manualField.style.display = 'block';
            manualField.querySelector('textarea').required = true;
        } else {
            manualField.style.display = 'none';
            if (manualField.querySelector('textarea')) {
                manualField.querySelector('textarea').required = false;
            }
        }
    }

    function closeCambioEquipoModal() {
        document.getElementById('cambio-equipo-modal').classList.add('hidden');
        document.getElementById('cambio-equipo-form').reset();
    }

    // Validaciones y procesamiento del formulario de cambio de equipo
    document.getElementById('cambio-equipo-form')?.addEventListener('submit', function(e) {
        const tipo = document.getElementById('cambio-equipo-tipo')?.value;
        const rodado = document.getElementById('cambio-equipo-rodado')?.value;
        const taller = document.getElementById('cambio-equipo-taller')?.value;
        const fechaHora = document.getElementById('cambio-equipo-fecha-hora')?.value;
        const costoManoObra = document.getElementById('cambio-equipo-pago')?.value;

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
            alert('Por favor, ingrese una fecha y hora estimada.');
            return false;
        }

        // Validar costo mano de obra solo si se ingresó un valor (opcional)
        if (costoManoObra && parseFloat(costoManoObra) < 0) {
            e.preventDefault();
            alert('El costo de mano de obra debe ser mayor o igual a 0.');
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

        // Validaciones específicas según tipo
        const tiposConDispositivos = ['antena_starlink', 'camara_mobil', 'dvr'];
        
        if (tiposConDispositivos.includes(tipo)) {
            const dispositivoNuevo = document.getElementById('cambio-equipo-dispositivo-nuevo');

            // Validar equipo nuevo
            if (!dispositivoNuevo || !dispositivoNuevo.value) {
                e.preventDefault();
                alert('Por favor, seleccione un nuevo equipo del inventario o indique que no está en inventario.');
                return false;
            }

            if (dispositivoNuevo.value === 'manual') {
                const detalleNuevoManual = this.querySelector('textarea[name="detalle_equipo_nuevo"]');
                if (!detalleNuevoManual || !detalleNuevoManual.value || detalleNuevoManual.value.trim() === '') {
                    e.preventDefault();
                    alert('Por favor, ingrese los detalles del nuevo equipo.');
                    detalleNuevoManual?.focus();
                    return false;
                }
            }
        } else if (tipo === 'cubiertas') {
            const tipoCubierta = document.getElementById('cambio-equipo-tipo-cubierta')?.value;
            if (!tipoCubierta || tipoCubierta.trim() === '') {
                e.preventDefault();
                alert('Por favor, ingrese el tipo de cubierta.');
                return false;
            }
        }

        const id = document.getElementById('cambio-equipo-id')?.value;
        if (id) {
            this.action = '{{ route("rodados.cambios-equipos.update", ":id") }}'.replace(':id', id);
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            this.appendChild(methodInput);
        }

        // Procesar campos de dispositivos antes de enviar
        if (tiposConDispositivos.includes(tipo)) {
            const dispositivoNuevo = document.getElementById('cambio-equipo-dispositivo-nuevo');
            if (dispositivoNuevo && dispositivoNuevo.value === 'manual') {
                // Si es manual, eliminar el valor del select para que no se envíe
                dispositivoNuevo.removeAttribute('name');
                // Crear un input hidden para indicar que es manual
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'dispositivo_id';
                hiddenInput.value = 'manual';
                this.appendChild(hiddenInput);
            }
        }
    });
</script>
