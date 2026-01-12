<!-- Modal para crear/editar turno -->
<div id="turno-modal" class="hidden fixed bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="top: 60px; left: 240px; right: 0; bottom: 0;" onclick="if(event.target === this) closeTurnoModal();">
    <div class="relative w-full max-w-3xl bg-zinc-800 border border-zinc-700 rounded-xl shadow-2xl overflow-hidden flex flex-col" style="max-height: calc(100vh - 60px - 2rem);" onclick="event.stopPropagation();">
        <!-- Header del modal -->
        <div class="flex justify-between items-center p-6 border-b border-zinc-700 bg-zinc-900/50">
            <h3 id="turno-modal-title" class="text-xl font-semibold text-gray-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Nuevo Turno
            </h3>
            <button onclick="closeTurnoModal()"
                class="p-2 text-gray-400 hover:text-gray-200 hover:bg-zinc-700 rounded-lg transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Contenido del modal -->
        <div class="overflow-y-auto flex-1 p-6">
            <form id="turno-form" method="POST" action="{{ route('rodados.turnos.store') }}">
                @csrf
                <input type="hidden" id="turno-id" name="id">
                @method('POST')

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Vehículo *</label>
                            <select id="turno-rodado" name="rodado_id" required
                                class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                                <option value="">Seleccione un vehículo</option>
                                @foreach($rodados as $rodado)
                                    <option value="{{ $rodado->id }}">{{ $rodado->patente ?? 'Sin patente' }} - Cliente: {{ $rodado->cliente->nombre ?? 'N/A' }} - Proveedor: {{ $rodado->proveedor->nombre ?? '-' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Taller *</label>
                            <select id="turno-taller" name="taller_id" required
                                class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
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
                                class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                                <option value="turno_service">Turno Service</option>
                                <option value="turno_taller">Turno al Taller</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Fecha y Hora *</label>
                            <input type="datetime-local" id="turno-fecha-hora" name="fecha_hora" required
                                class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                        </div>
                    </div>
                    <!-- Input hidden para tipo cuando se establece automáticamente -->
                    <input type="hidden" id="turno-tipo-hidden" name="tipo" value="">

                    <!-- Campos para turno_taller (guardado como turno_mecanico) -->
                    <div id="turno-taller-fields" style="display: none;">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Motivo del turno *</label>
                            <textarea id="turno-descripcion-taller" name="descripcion" rows="3"
                                class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500"
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
                                <table class="min-w-full divide-y divide-gray-700 border border-zinc-600 rounded-lg">
                                    <thead class="bg-zinc-700">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">Item</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">Cantidad</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">Descripción</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="partes-afectadas-body" class="bg-zinc-800 divide-y divide-gray-700">
                                        <!-- Las filas se agregarán dinámicamente -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Encargado de Dejar</label>
                                <input type="text" id="turno-encargado-dejar-taller" name="encargado_dejar"
                                    class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Encargado de Retirar</label>
                                <input type="text" id="turno-encargado-retirar-taller" name="encargado_retirar"
                                    class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Campos para turno_service -->
                    <div id="turno-service-fields">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Encargado de Dejar</label>
                                <input type="text" id="turno-encargado-dejar-service" name="encargado_dejar"
                                    class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Encargado de Retirar</label>
                                <input type="text" id="turno-encargado-retirar-service" name="encargado_retirar"
                                    class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Anotaciones</label>
                            <textarea id="turno-descripcion-service" name="descripcion" rows="3"
                                class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500"
                                placeholder="Anotaciones sobre el servicio..."></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer del modal con acciones -->
        <div class="flex justify-end gap-3 p-6 border-t border-zinc-700 bg-zinc-900/50">
            <button type="button" onclick="closeTurnoModal()"
                class="px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg font-medium transition-all duration-200">
                Cancelar
            </button>
            <button type="submit" form="turno-form"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Guardar
            </button>
        </div>
    </div>
</div>

<style>
    /* Ajustes responsive para el modal */
    @media (max-width: 1024px) {
        #turno-modal {
            left: 0 !important;
        }
    }
    
    @media (max-width: 768px) {
        #turno-modal {
            top: 0 !important;
            left: 0 !important;
        }
    }
    
    /* Asegurar que el modal use las variables CSS si están disponibles */
    #turno-modal {
        top: var(--fb-topbar-height, 60px);
        left: var(--fb-sidebar-width, 240px);
    }
    
    #turno-modal > div {
        max-height: calc(100vh - var(--fb-topbar-height, 60px) - 2rem);
    }
</style>

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
            // Configurar campo de taller
            if (descripcionTaller) {
                descripcionTaller.required = true;
                descripcionTaller.name = 'descripcion';
                descripcionTaller.disabled = false;
            }
            // Deshabilitar y ocultar campo de service
            if (descripcionService) {
                descripcionService.required = false;
                descripcionService.name = '';
                descripcionService.disabled = true;
                descripcionService.value = ''; // Limpiar valor cuando está oculto
            }
        } else {
            tallerFields.style.display = 'none';
            serviceFields.style.display = 'block';
            // Configurar campo de service
            if (descripcionService) {
                descripcionService.required = false;
                descripcionService.name = 'descripcion';
                descripcionService.disabled = false;
            }
            // Deshabilitar y ocultar campo de taller
            if (descripcionTaller) {
                descripcionTaller.required = false;
                descripcionTaller.name = '';
                descripcionTaller.disabled = true;
                descripcionTaller.value = ''; // Limpiar valor cuando está oculto
            }
        }
    }

    function agregarFilaParte() {
        filaParteCounter++;
        const tbody = document.getElementById('partes-afectadas-body');
        const tr = document.createElement('tr');
        tr.setAttribute('data-parte-index', filaParteCounter);
        
        tr.innerHTML = `
            <td class="px-4 py-2">
                <input type="text" name="partes_afectadas[${filaParteCounter}][item]" required
                    class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-2 py-1 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500"
                    placeholder="Ej: Filtro de aceite">
            </td>
            <td class="px-4 py-2">
                <input type="number" name="partes_afectadas[${filaParteCounter}][cantidad]" required min="1"
                    class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-2 py-1 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500"
                    placeholder="1">
            </td>
            <td class="px-4 py-2">
                <input type="text" name="partes_afectadas[${filaParteCounter}][descripcion]" required
                    class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-2 py-1 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500"
                    placeholder="Descripción del item">
            </td>
            <td class="px-4 py-2">
                <button type="button" onclick="eliminarFilaParte(this)" 
                    class="text-red-400 hover:text-red-300 transition-colors"
                    title="Eliminar item">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        
        // Hacer scroll al nuevo item agregado
        tr.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function eliminarFilaParte(button) {
        const tr = button.closest('tr');
        if (tr) {
            // Confirmar eliminación
            if (confirm('¿Está seguro de eliminar este item?')) {
                tr.remove();
            }
        }
    }

    function closeTurnoModal() {
        document.getElementById('turno-modal').classList.add('hidden');
        document.getElementById('turno-form').reset();
        // Limpiar tabla de partes afectadas
        document.getElementById('partes-afectadas-body').innerHTML = '';
        filaParteCounter = 0;
        // Resetear campos required
        const descripcionTaller = document.getElementById('turno-descripcion-taller');
        if (descripcionTaller) {
            descripcionTaller.required = false;
        }
        // Resetear ID del turno
        document.getElementById('turno-id').value = '';
        // Resetear acción del formulario
        document.getElementById('turno-form').action = '{{ route("rodados.turnos.store") }}';
        // Mostrar campo de tipo
        const tipoFieldContainer = document.getElementById('turno-tipo-field-container');
        if (tipoFieldContainer) {
            tipoFieldContainer.style.display = 'block';
        }
        // Resetear tipo hidden
        const tipoHidden = document.getElementById('turno-tipo-hidden');
        if (tipoHidden) {
            tipoHidden.value = '';
        }
    }
    
    // Inicializar campos cuando se carga la página
    document.addEventListener('DOMContentLoaded', function() {
        toggleTurnoFields();
    });

    // Cerrar modal con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('turno-modal');
            if (modal && !modal.classList.contains('hidden')) {
                closeTurnoModal();
            }
        }
    });


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
            const tallerFields = document.getElementById('turno-taller-fields');
            if (tallerFields && tallerFields.style.display !== 'none') {
                const descripcionTaller = document.getElementById('turno-descripcion-taller');
                const motivo = descripcionTaller?.value;
                
                console.log('Validando turno mecánico - Campo descripcion:', motivo);
                console.log('Campo visible?', descripcionTaller?.offsetParent !== null);
                
                // Validar que el motivo no esté vacío (sin validar mínimo de caracteres)
                if (!motivo || motivo.trim() === '') {
                    e.preventDefault();
                    alert('Por favor, ingrese el motivo del turno.');
                    descripcionTaller?.focus();
                    return false;
                }
                
                // Asegurar que el campo tenga el atributo name correcto y esté habilitado
                if (descripcionTaller) {
                    descripcionTaller.name = 'descripcion';
                    descripcionTaller.disabled = false;
                    console.log('Campo descripcion configurado:', descripcionTaller.name, 'Valor:', descripcionTaller.value);
                }
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

        // Asegurar que el campo tipo se envíe correctamente
        const tipoField = document.getElementById('turno-tipo-hidden');
        if (tipoField && tipoField.value) {
            // Asegurar que el tipo se envíe en el formulario
            if (!document.querySelector('input[name="tipo"][type="hidden"]')) {
                const tipoInput = document.createElement('input');
                tipoInput.type = 'hidden';
                tipoInput.name = 'tipo';
                tipoInput.value = tipoField.value === 'turno_mecanico' ? 'turno_taller' : tipoField.value;
                this.appendChild(tipoInput);
            }
        }

        // Asegurar que el campo descripcion esté habilitado y tenga el name correcto antes de enviar
        const descripcionTaller = document.getElementById('turno-descripcion-taller');
        const descripcionService = document.getElementById('turno-descripcion-service');
        
        if (descripcionTaller && descripcionTaller.offsetParent !== null) {
            // Campo visible, asegurar que esté habilitado y tenga el name correcto
            descripcionTaller.disabled = false;
            descripcionTaller.name = 'descripcion';
            if (descripcionService) {
                descripcionService.disabled = true;
                descripcionService.name = '';
            }
            console.log('Enviando descripcion de taller:', descripcionTaller.value);
        } else if (descripcionService && descripcionService.offsetParent !== null) {
            // Campo service visible
            descripcionService.disabled = false;
            descripcionService.name = 'descripcion';
            if (descripcionTaller) {
                descripcionTaller.disabled = true;
                descripcionTaller.name = '';
            }
            console.log('Enviando descripcion de service:', descripcionService.value);
        }

        const id = document.getElementById('turno-id')?.value;
        if (id) {
            const updateBaseUrl = '{{ url("/rodados/turnos") }}';
            this.action = updateBaseUrl + '/' + id;
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            this.appendChild(methodInput);
        }
        
        // Log final antes de enviar
        const formData = new FormData(this);
        console.log('Datos del formulario a enviar:');
        for (let [key, value] of formData.entries()) {
            if (key === 'descripcion') {
                console.log('  ' + key + ':', value);
            }
        }
    });
</script>
