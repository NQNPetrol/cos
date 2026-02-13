<!-- Modal para crear/editar turno -->
<div id="turno-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center modal-backdrop" style="padding-left: calc(var(--fb-sidebar-width, 240px) + 1rem); padding-top: calc(var(--fb-topbar-height, 60px) + 1.5rem); padding-right: 1rem; padding-bottom: 1rem;" onclick="if(event.target === this) closeTurnoModal();">
    <div class="w-full max-w-2xl bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden modal-content flex flex-col" style="max-height: calc(100vh - var(--fb-topbar-height, 60px) - 4rem);" onclick="event.stopPropagation();">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800 shrink-0">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-600/10 rounded-lg">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 id="turno-modal-title" class="text-lg font-semibold text-gray-100">Nuevo Turno</h3>
            </div>
            <button onclick="closeTurnoModal()"
                class="p-2 text-gray-500 hover:text-gray-300 hover:bg-zinc-800 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Body -->
        <div class="overflow-y-auto flex-1 px-6 py-5 modal-scroll">
            <form id="turno-form" method="POST" action="{{ route('rodados.turnos.store') }}">
                @csrf
                <input type="hidden" id="turno-id" name="id">
                @method('POST')

                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Vehículo *</label>
                            <select id="turno-rodado" name="rodado_id" required
                                class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                                <option value="">Seleccione un vehículo</option>
                                @foreach($rodados as $rodado)
                                    <option value="{{ $rodado->id }}">{{ $rodado->patente ?? 'Sin patente' }} - {{ $rodado->cliente->nombre ?? 'N/A' }}{{ $rodado->proveedor ? ' - Prov: '.$rodado->proveedor->nombre : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Taller *</label>
                            <select id="turno-taller" name="taller_id" required
                                class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                                <option value="">Seleccione un taller</option>
                                @foreach($talleres as $taller)
                                    <option value="{{ $taller->id }}">{{ $taller->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div id="turno-tipo-field-container">
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Tipo de Turno *</label>
                            <select id="turno-tipo" name="tipo" required onchange="toggleTurnoFields()"
                                class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                                <option value="turno_service">Turno Service</option>
                                <option value="turno_taller">Turno al Taller</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Fecha y Hora *</label>
                            <input type="datetime-local" id="turno-fecha-hora" name="fecha_hora" required
                                class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                        </div>
                    </div>
                    <input type="hidden" id="turno-tipo-hidden" name="tipo" value="">

                    <!-- Campos para turno_taller (guardado como turno_mecanico) -->
                    <div id="turno-taller-fields" style="display: none;">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Motivo del turno *</label>
                            <textarea id="turno-descripcion-taller" name="descripcion" rows="3" placeholder="Describe el motivo del turno..."
                                class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all resize-none"></textarea>
                        </div>

                        <!-- Tabla dinámica de partes afectadas -->
                        <div class="mt-5">
                            <div class="flex justify-between items-center mb-3">
                                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Partes afectadas</label>
                                <button type="button" onclick="agregarFilaParte()"
                                    class="text-xs px-3 py-1.5 bg-blue-600/10 text-blue-400 border border-blue-600/20 rounded-lg hover:bg-blue-600/20 transition-all flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Agregar
                                </button>
                            </div>
                            <div class="overflow-x-auto rounded-xl border border-zinc-700/50">
                                <table class="min-w-full divide-y divide-zinc-800">
                                    <thead class="bg-zinc-800/50">
                                        <tr>
                                            <th class="px-3 py-2.5 text-left text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Item</th>
                                            <th class="px-3 py-2.5 text-left text-[10px] font-semibold text-gray-500 uppercase tracking-wider w-20">Cant.</th>
                                            <th class="px-3 py-2.5 text-left text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Descripción</th>
                                            <th class="px-3 py-2.5 text-center text-[10px] font-semibold text-gray-500 uppercase tracking-wider w-12"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="partes-afectadas-body" class="divide-y divide-zinc-800/50">
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5">
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Encargado de Dejar</label>
                                <input type="text" id="turno-encargado-dejar-taller" name="encargado_dejar" placeholder="Nombre del encargado"
                                    class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Encargado de Retirar</label>
                                <input type="text" id="turno-encargado-retirar-taller" name="encargado_retirar" placeholder="Nombre del encargado"
                                    class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Campos para turno_service -->
                    <div id="turno-service-fields">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Encargado de Dejar</label>
                                <input type="text" id="turno-encargado-dejar-service" name="encargado_dejar" placeholder="Nombre del encargado"
                                    class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Encargado de Retirar</label>
                                <input type="text" id="turno-encargado-retirar-service" name="encargado_retirar" placeholder="Nombre del encargado"
                                    class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Anotaciones <span class="text-gray-600 normal-case">(opcional)</span></label>
                            <textarea id="turno-descripcion-service" name="descripcion" rows="2" placeholder="Notas sobre el servicio..."
                                class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all resize-none"></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-zinc-800 bg-zinc-900/50 shrink-0">
            <button type="button" onclick="closeTurnoModal()"
                class="px-4 py-2.5 text-sm font-medium text-gray-400 hover:text-gray-200 bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 rounded-xl transition-all">
                Cancelar
            </button>
            <button type="submit" form="turno-form"
                class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-600/20 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Guardar
            </button>
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
        const descripcionTaller = document.getElementById('turno-descripcion-taller');
        const descripcionService = document.getElementById('turno-descripcion-service');

        if (tipo === 'turno_taller' || tipo === 'turno_mecanico') {
            tallerFields.style.display = 'block';
            serviceFields.style.display = 'none';
            if (descripcionTaller) { descripcionTaller.required = true; descripcionTaller.name = 'descripcion'; descripcionTaller.disabled = false; }
            if (descripcionService) { descripcionService.required = false; descripcionService.name = ''; descripcionService.disabled = true; descripcionService.value = ''; }
        } else {
            tallerFields.style.display = 'none';
            serviceFields.style.display = 'block';
            if (descripcionService) { descripcionService.required = false; descripcionService.name = 'descripcion'; descripcionService.disabled = false; }
            if (descripcionTaller) { descripcionTaller.required = false; descripcionTaller.name = ''; descripcionTaller.disabled = true; descripcionTaller.value = ''; }
        }
    }

    function agregarFilaParte() {
        filaParteCounter++;
        const tbody = document.getElementById('partes-afectadas-body');
        const tr = document.createElement('tr');
        tr.setAttribute('data-parte-index', filaParteCounter);
        tr.className = 'group';
        tr.innerHTML = `
            <td class="px-3 py-2">
                <input type="text" name="partes_afectadas[${filaParteCounter}][item]" required placeholder="Ej: Filtro"
                    class="w-full rounded-lg bg-zinc-800 border border-zinc-700 text-gray-200 px-2.5 py-1.5 text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
            </td>
            <td class="px-3 py-2">
                <input type="number" name="partes_afectadas[${filaParteCounter}][cantidad]" required min="1" placeholder="1"
                    class="w-full rounded-lg bg-zinc-800 border border-zinc-700 text-gray-200 px-2.5 py-1.5 text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
            </td>
            <td class="px-3 py-2">
                <input type="text" name="partes_afectadas[${filaParteCounter}][descripcion]" required placeholder="Descripción"
                    class="w-full rounded-lg bg-zinc-800 border border-zinc-700 text-gray-200 px-2.5 py-1.5 text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
            </td>
            <td class="px-3 py-2 text-center">
                <button type="button" onclick="eliminarFilaParte(this)"
                    class="p-1 text-gray-500 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all" title="Eliminar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </td>`;
        tbody.appendChild(tr);
        tr.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function eliminarFilaParte(button) {
        const tr = button.closest('tr');
        if (tr && confirm('¿Eliminar este item?')) tr.remove();
    }

    function closeTurnoModal() {
        document.getElementById('turno-modal').classList.add('hidden');
        document.getElementById('turno-form').reset();
        document.getElementById('partes-afectadas-body').innerHTML = '';
        filaParteCounter = 0;
        const descripcionTaller = document.getElementById('turno-descripcion-taller');
        if (descripcionTaller) descripcionTaller.required = false;
        document.getElementById('turno-id').value = '';
        document.getElementById('turno-form').action = '{{ route("rodados.turnos.store") }}';
        const tipoFieldContainer = document.getElementById('turno-tipo-field-container');
        if (tipoFieldContainer) tipoFieldContainer.style.display = 'block';
        const tipoHidden = document.getElementById('turno-tipo-hidden');
        if (tipoHidden) tipoHidden.value = '';
    }

    document.addEventListener('DOMContentLoaded', function() { toggleTurnoFields(); });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('turno-modal');
            if (modal && !modal.classList.contains('hidden')) closeTurnoModal();
        }
    });

    document.getElementById('turno-form')?.addEventListener('submit', function(e) {
        const tipoSelect = document.getElementById('turno-tipo');
        const tipoHidden = document.getElementById('turno-tipo-hidden');
        const tipo = tipoHidden && tipoHidden.value ? tipoHidden.value : (tipoSelect ? tipoSelect.value : '');
        const rodado = document.getElementById('turno-rodado')?.value;
        const taller = document.getElementById('turno-taller')?.value;
        const fechaHora = document.getElementById('turno-fecha-hora')?.value;

        if (tipoHidden && tipoHidden.value) {
            if (tipoSelect) tipoSelect.value = tipoHidden.value === 'turno_mecanico' ? 'turno_taller' : tipoHidden.value;
        }

        if (!rodado) { e.preventDefault(); alert('Por favor, seleccione un vehículo.'); return false; }
        if (!taller) { e.preventDefault(); alert('Por favor, seleccione un taller.'); return false; }
        if (!fechaHora) { e.preventDefault(); alert('Por favor, ingrese una fecha y hora.'); return false; }

        const fechaInput = new Date(fechaHora);
        if (fechaInput <= new Date()) {
            if (!confirm('La fecha seleccionada es anterior a la actual. ¿Continuar?')) { e.preventDefault(); return false; }
        }

        if (tipo === 'turno_taller' || tipo === 'turno_mecanico') {
            const tallerFields = document.getElementById('turno-taller-fields');
            if (tallerFields && tallerFields.style.display !== 'none') {
                const descripcionTaller = document.getElementById('turno-descripcion-taller');
                const motivo = descripcionTaller?.value;
                if (!motivo || motivo.trim() === '') { e.preventDefault(); alert('Por favor, ingrese el motivo del turno.'); descripcionTaller?.focus(); return false; }
                if (descripcionTaller) { descripcionTaller.name = 'descripcion'; descripcionTaller.disabled = false; }
            }
            const partesRows = document.querySelectorAll('#partes-afectadas-body tr');
            if (partesRows.length === 0) {
                if (!confirm('No se han agregado partes afectadas. ¿Continuar?')) { e.preventDefault(); return false; }
            } else {
                let filaIncompleta = false;
                partesRows.forEach(row => {
                    const item = row.querySelector('input[name*="[item]"]')?.value;
                    const cantidad = row.querySelector('input[name*="[cantidad]"]')?.value;
                    const descripcion = row.querySelector('input[name*="[descripcion]"]')?.value;
                    if ((item && item.trim()) || (cantidad && cantidad.trim()) || (descripcion && descripcion.trim())) {
                        if (!item || !cantidad || !descripcion) filaIncompleta = true;
                    }
                });
                if (filaIncompleta) { e.preventDefault(); alert('Complete todos los campos de partes afectadas o elimine filas incompletas.'); return false; }
            }
        }

        const tipoField = document.getElementById('turno-tipo-hidden');
        if (tipoField && tipoField.value) {
            if (!document.querySelector('input[name="tipo"][type="hidden"]')) {
                const tipoInput = document.createElement('input');
                tipoInput.type = 'hidden'; tipoInput.name = 'tipo';
                tipoInput.value = tipoField.value === 'turno_mecanico' ? 'turno_taller' : tipoField.value;
                this.appendChild(tipoInput);
            }
        }

        const descripcionTaller = document.getElementById('turno-descripcion-taller');
        const descripcionService = document.getElementById('turno-descripcion-service');
        if (descripcionTaller && descripcionTaller.offsetParent !== null) {
            descripcionTaller.disabled = false; descripcionTaller.name = 'descripcion';
            if (descripcionService) { descripcionService.disabled = true; descripcionService.name = ''; }
        } else if (descripcionService && descripcionService.offsetParent !== null) {
            descripcionService.disabled = false; descripcionService.name = 'descripcion';
            if (descripcionTaller) { descripcionTaller.disabled = true; descripcionTaller.name = ''; }
        }

        const id = document.getElementById('turno-id')?.value;
        if (id) {
            this.action = '{{ url("/rodados/turnos") }}' + '/' + id;
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden'; methodInput.name = '_method'; methodInput.value = 'PUT';
            this.appendChild(methodInput);
        }
    });
</script>
