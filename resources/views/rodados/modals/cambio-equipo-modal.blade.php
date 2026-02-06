<!-- Modal para crear/editar cambio de equipo -->
<div id="cambio-equipo-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center modal-backdrop" style="padding-left: calc(var(--fb-sidebar-width, 240px) + 1rem); padding-top: calc(var(--fb-topbar-height, 60px) + 1.5rem); padding-right: 1rem; padding-bottom: 1rem;" onclick="if(event.target === this) closeCambioEquipoModal()">
    <div class="w-full max-w-2xl bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden modal-content flex flex-col" style="max-height: calc(100vh - var(--fb-topbar-height, 60px) - 4rem);" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800 shrink-0">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-amber-600/10 rounded-lg">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                    </svg>
                </div>
                <h3 id="cambio-equipo-modal-title" class="text-lg font-semibold text-gray-100">Nuevo Cambio de Equipo</h3>
            </div>
            <button onclick="closeCambioEquipoModal()"
                class="p-2 text-gray-500 hover:text-gray-300 hover:bg-zinc-800 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Body -->
        <div class="overflow-y-auto flex-1 px-6 py-5 modal-scroll">
            <form id="cambio-equipo-form" method="POST" action="{{ route('rodados.cambios-equipos.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="cambio-equipo-id" name="id">
                @method('POST')

                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Vehículo *</label>
                            <select id="cambio-equipo-rodado" name="rodado_id" required
                                class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                                <option value="">Seleccione un vehículo</option>
                                @foreach($rodados as $rodado)
                                    <option value="{{ $rodado->id }}">{{ $rodado->patente ?? 'Sin patente' }} - {{ $rodado->cliente->nombre ?? 'N/A' }}{{ $rodado->proveedor ? ' - Prov: '.$rodado->proveedor->nombre : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Taller *</label>
                            <select id="cambio-equipo-taller" name="taller_id" required
                                class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                                <option value="">Seleccione un taller</option>
                                @foreach($talleres as $taller)
                                    <option value="{{ $taller->id }}">{{ $taller->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Tipo de Equipo *</label>
                            <select id="cambio-equipo-tipo" name="tipo" required onchange="toggleCambioEquipoFields()"
                                class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                                <option value="cubiertas">Cubiertas</option>
                                <option value="antena_starlink">Antena Starlink</option>
                                <option value="camara_mobil">Cámara Móvil</option>
                                <option value="dvr">DVR</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Fecha y Hora Estimada *</label>
                            <input type="datetime-local" id="cambio-equipo-fecha" name="fecha_hora_estimada" required
                                class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                        </div>
                    </div>

                    <!-- Campo tipo cubierta -->
                    <div id="cambio-equipo-tipo-cubierta-field" style="display: none;">
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Tipo de Cubierta *</label>
                        <input type="text" id="cambio-equipo-tipo-cubierta" name="tipo_cubierta" placeholder="Ej: 255/70 R16"
                            class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                    </div>

                    <!-- Campos para equipos que requieren dispositivos -->
                    <div id="cambio-equipo-dispositivos-fields" style="display: none;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Equipo a reemplazar</label>
                                <textarea id="cambio-equipo-detalle-equipo-reemplazar" name="detalle_equipo_viejo" rows="3" placeholder="Detalles del equipo a reemplazar..."
                                    class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all resize-none"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Nuevo equipo *</label>
                                <select id="cambio-equipo-dispositivo-nuevo" name="dispositivo_id" onchange="toggleDetalleEquipoNuevo()"
                                    class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                                    <option value="">Seleccione del inventario</option>
                                    <option value="manual">No está en inventario</option>
                                </select>
                                <div id="cambio-equipo-detalle-nuevo-manual" style="display: none;" class="mt-2">
                                    <textarea name="detalle_equipo_nuevo" rows="2" placeholder="Detalles del equipo nuevo..."
                                        class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all resize-none"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Km y Costo -->
                    <div class="p-4 bg-zinc-800/50 rounded-xl border border-zinc-700/30">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Datos Adicionales</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1.5">Kilometraje actual</label>
                                <div class="relative">
                                    <input type="number" id="cambio-equipo-km" name="kilometraje_en_cambio" min="0" step="1" placeholder="Ej: 45000"
                                        class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 pr-12 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-500 font-medium">km</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1.5">Costo mano de obra</label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-500 font-medium">$</span>
                                    <input type="number" id="cambio-equipo-pago" name="pago_mano_obra" step="0.01" min="0" placeholder="0.00"
                                        class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 pl-8 pr-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Factura -->
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Factura mano de obra <span class="text-gray-600 normal-case">(opcional)</span></label>
                        <label class="flex items-center gap-3 w-full px-4 py-3 bg-zinc-800 border border-zinc-700 border-dashed rounded-xl cursor-pointer hover:border-blue-500/50 hover:bg-zinc-800/80 transition-all group">
                            <div class="p-2 bg-zinc-700 rounded-lg group-hover:bg-blue-600/10 transition-colors">
                                <svg class="w-5 h-5 text-gray-500 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-300 group-hover:text-gray-200" id="cambio-equipo-factura-label">Seleccionar archivo</p>
                                <p class="text-xs text-gray-600">PDF, JPG, PNG - Máx. 10MB</p>
                            </div>
                            <input type="file" id="cambio-equipo-factura" name="factura_mano_obra" accept=".pdf,.jpg,.jpeg,.png" class="hidden"
                                onchange="document.getElementById('cambio-equipo-factura-label').textContent = this.files[0] ? this.files[0].name : 'Seleccionar archivo'">
                        </label>
                    </div>

                    <!-- Motivo -->
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Motivo cambio <span class="text-gray-600 normal-case">(opcional)</span></label>
                        <textarea id="cambio-equipo-motivo" name="motivo" rows="2" placeholder="Describe el motivo del cambio..."
                            class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all resize-none"></textarea>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-zinc-800 bg-zinc-900/50 shrink-0">
            <button type="button" onclick="closeCambioEquipoModal()"
                class="px-4 py-2.5 text-sm font-medium text-gray-400 hover:text-gray-200 bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 rounded-xl transition-all">
                Cancelar
            </button>
            <button type="submit" form="cambio-equipo-form"
                class="px-5 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-lg shadow-amber-600/20 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Guardar
            </button>
        </div>
    </div>
</div>

<script>
    const dispositivosDisponibles = @json($dispositivos ?? []);
    const mapeoTipoDispositivo = { 'camara_mobil': 'camara_ip', 'antena_starlink': 'antena_starlink', 'dvr': 'nvr_dvr' };

    function toggleCambioEquipoFields() {
        const tipo = document.getElementById('cambio-equipo-tipo').value;
        const tipoCubiertaField = document.getElementById('cambio-equipo-tipo-cubierta-field');
        const dispositivosFields = document.getElementById('cambio-equipo-dispositivos-fields');
        const tiposConDispositivos = ['antena_starlink', 'camara_mobil', 'dvr'];

        if (tipo === 'cubiertas') {
            tipoCubiertaField.style.display = 'block';
            document.getElementById('cambio-equipo-tipo-cubierta').required = true;
            dispositivosFields.style.display = 'none';
        } else if (tiposConDispositivos.includes(tipo)) {
            tipoCubiertaField.style.display = 'none';
            document.getElementById('cambio-equipo-tipo-cubierta').required = false;
            dispositivosFields.style.display = 'block';
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
        while (selectNuevo.options.length > 2) selectNuevo.remove(2);
        dispositivosDisponibles.filter(d => d.tipo === tipoDispositivo).forEach(dispositivo => {
            const option = document.createElement('option');
            option.value = dispositivo.id;
            option.textContent = `${dispositivo.nombre}${dispositivo.numero_serie ? ' (' + dispositivo.numero_serie + ')' : ''}`;
            selectNuevo.appendChild(option);
        });
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
            if (manualField.querySelector('textarea')) manualField.querySelector('textarea').required = false;
        }
    }

    function closeCambioEquipoModal() {
        document.getElementById('cambio-equipo-modal').classList.add('hidden');
        document.getElementById('cambio-equipo-form').reset();
        document.getElementById('cambio-equipo-factura-label').textContent = 'Seleccionar archivo';
    }

    document.getElementById('cambio-equipo-form')?.addEventListener('submit', function(e) {
        const tipo = document.getElementById('cambio-equipo-tipo')?.value;
        const rodado = document.getElementById('cambio-equipo-rodado')?.value;
        const taller = document.getElementById('cambio-equipo-taller')?.value;
        const fechaHora = document.getElementById('cambio-equipo-fecha')?.value;
        const costoManoObra = document.getElementById('cambio-equipo-pago')?.value;

        if (!rodado) { e.preventDefault(); alert('Por favor, seleccione un vehículo.'); return false; }
        if (!taller) { e.preventDefault(); alert('Por favor, seleccione un taller.'); return false; }
        if (!fechaHora) { e.preventDefault(); alert('Por favor, ingrese una fecha y hora estimada.'); return false; }
        if (costoManoObra && parseFloat(costoManoObra) < 0) { e.preventDefault(); alert('El costo debe ser mayor o igual a 0.'); return false; }

        const fechaInput = new Date(fechaHora);
        if (fechaInput <= new Date()) {
            if (!confirm('La fecha es anterior a la actual. ¿Continuar?')) { e.preventDefault(); return false; }
        }

        const tiposConDispositivos = ['antena_starlink', 'camara_mobil', 'dvr'];
        if (tiposConDispositivos.includes(tipo)) {
            const dispositivoNuevo = document.getElementById('cambio-equipo-dispositivo-nuevo');
            if (!dispositivoNuevo || !dispositivoNuevo.value) { e.preventDefault(); alert('Seleccione un nuevo equipo.'); return false; }
            if (dispositivoNuevo.value === 'manual') {
                const detalleNuevoManual = this.querySelector('textarea[name="detalle_equipo_nuevo"]');
                if (!detalleNuevoManual || !detalleNuevoManual.value?.trim()) { e.preventDefault(); alert('Ingrese detalles del equipo nuevo.'); detalleNuevoManual?.focus(); return false; }
            }
        } else if (tipo === 'cubiertas') {
            const tipoCubierta = document.getElementById('cambio-equipo-tipo-cubierta')?.value;
            if (!tipoCubierta?.trim()) { e.preventDefault(); alert('Ingrese el tipo de cubierta.'); return false; }
        }

        const id = document.getElementById('cambio-equipo-id')?.value;
        if (id) {
            this.action = '{{ route("rodados.cambios-equipos.update", ":id") }}'.replace(':id', id);
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden'; methodInput.name = '_method'; methodInput.value = 'PUT';
            this.appendChild(methodInput);
        }

        if (tiposConDispositivos.includes(tipo)) {
            const dispositivoNuevo = document.getElementById('cambio-equipo-dispositivo-nuevo');
            if (dispositivoNuevo && dispositivoNuevo.value === 'manual') {
                dispositivoNuevo.removeAttribute('name');
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden'; hiddenInput.name = 'dispositivo_id'; hiddenInput.value = 'manual';
                this.appendChild(hiddenInput);
            }
        }
    });
</script>
