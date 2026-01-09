<div>
    <!-- Header con botones de acción -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-medium text-gray-200">Servicios y Mantenimientos</h3>
        <div class="flex space-x-2">
            <button onclick="openCreateTurnoModal('turno_service')"
                class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Turno Service
            </button>
            <button onclick="openCreateTurnoModal('turno_mecanico')"
                class="inline-flex items-center px-3 py-2 bg-yellow-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-yellow-700 transition">
                Turno Taller
            </button>
            <button onclick="openCreateCambioEquipoModal()"
                class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                Cambio Equipo
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Vehículo</label>
            <select id="filtro-servicio-vehiculo" class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2">
                <option value="">Todos</option>
                @foreach($rodados as $rodado)
                    <option value="{{ $rodado->id }}">{{ $rodado->patente ?? 'Sin patente' }} - Cliente: {{ $rodado->cliente->nombre ?? 'N/A' }} - Proveedor: {{ $rodado->proveedor->nombre ?? '-' }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Tipo</label>
            <select id="filtro-servicio-tipo" class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2">
                <option value="">Todos</option>
                <option value="turno_service">Turno Service</option>
                <option value="turno_mecanico">Turno Mecánico</option>
                <option value="cambio_equipo">Cambio Equipo</option>
                <option value="turno_taller">Turno Taller</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Taller</label>
            <select id="filtro-servicio-taller" class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2">
                <option value="">Todos</option>
                @foreach($talleres as $taller)
                    <option value="{{ $taller->id }}">{{ $taller->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Estado</label>
            <select id="filtro-servicio-estado" class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2">
                <option value="">Todos</option>
                <option value="pendiente">Pendiente</option>
                <option value="atendido">Atendido</option>
                <option value="completado">Completado</option>
                <option value="cancelado">Cancelado</option>
            </select>
        </div>
    </div>

    <!-- Tabla de servicios -->
    <div class="overflow-x-auto rounded-lg border border-zinc-700">
        <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-zinc-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Vehículo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Fecha/Hora</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Taller</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Vencimiento Pago</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-zinc-800 divide-y divide-gray-700">
                @forelse($todosLosServicios ?? $turnos as $servicio)
                @php
                    $turno = $servicio['tipo_servicio'] === 'turno' ? $servicio['model'] : null;
                    $cambio = $servicio['tipo_servicio'] === 'cambio_equipo' ? $servicio['model'] : null;
                    $item = $turno ?? $cambio;
                @endphp
                <tr class="servicio-row hover:bg-zinc-750 transition-colors"
                    data-vehiculo="{{ $servicio['rodado']->id }}"
                    data-tipo="{{ $servicio['tipo'] }}"
                    data-taller="{{ $servicio['taller']->id ?? '' }}"
                    data-estado="{{ $servicio['estado'] }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($servicio['tipo'] === 'turno_service')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900/30 text-blue-400 border border-blue-800">
                                Service
                            </span>
                        @elseif($servicio['tipo'] === 'turno_mecanico')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900/30 text-yellow-400 border border-yellow-800">
                                Mecánico
                            </span>
                            @if($servicio['cubre_servicio'])
                                <div class="text-xs text-green-400 mt-1">Cubre Empresa</div>
                            @else
                                <div class="text-xs text-red-400 mt-1">Cubre Cliente</div>
                            @endif
                        @elseif($servicio['tipo'] === 'cambio_equipo')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900/30 text-green-400 border border-green-800">
                                Cambio Equipo
                            </span>
                            @if($cambio)
                                <div class="text-xs text-gray-400 mt-1">{{ ucfirst(str_replace('_', ' ', $cambio->tipo)) }}</div>
                            @endif
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-700 text-gray-300 border border-zinc-600">
                                Taller
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                        {{ $servicio['rodado']->patente ?? 'Sin patente' }} - {{ $servicio['rodado']->marca }} {{ $servicio['rodado']->modelo }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                        {{ $servicio['fecha_hora']->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                        {{ $servicio['taller']->nombre }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($servicio['estado'] === 'completado' || $servicio['estado'] === 'atendido')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900/30 text-green-400 border border-green-800">
                                {{ $servicio['estado'] === 'completado' ? 'Completado' : 'Atendido' }}
                            </span>
                        @elseif($servicio['estado'] === 'cancelado')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900/30 text-red-400 border border-red-800">
                                Cancelado
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900/30 text-yellow-400 border border-yellow-800">
                                Pendiente
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($servicio['fecha_vencimiento_pago'])
                            @php
                                $diasRestantes = now()->diffInDays($servicio['fecha_vencimiento_pago'], false);
                            @endphp
                            <div class="text-gray-300">{{ $servicio['fecha_vencimiento_pago']->format('d/m/Y') }}</div>
                            @if($diasRestantes < 0)
                                <div class="text-xs text-red-400">Vencido</div>
                            @elseif($diasRestantes <= 7)
                                <div class="text-xs text-yellow-400">Vence en {{ $diasRestantes }} días</div>
                            @endif
                        @else
                            <span class="text-gray-500">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2 flex-wrap">
                            @if($servicio['tipo_servicio'] === 'turno')
                                <button onclick="openEditTurnoModal({{ $item->id }})"
                                    class="text-blue-400 hover:text-blue-300 transition-colors p-1 rounded hover:bg-blue-900/30"
                                    title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>
                                <button onclick="openAdjuntarFacturaModal('turno', {{ $item->id }})"
                                    class="text-green-400 hover:text-green-300 transition-colors p-1 rounded hover:bg-green-900/30"
                                    title="Adjuntar factura">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </button>
                                @if($servicio['tipo'] === 'turno_mecanico')
                                    <button onclick="openRevisarCoberturaModal({{ $item->id }})"
                                        class="text-purple-400 hover:text-purple-300 transition-colors p-1 rounded hover:bg-purple-900/30"
                                        title="Revisar cobertura">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                @endif
                                <form action="{{ route('rodados.turnos.destroy', ['turno' => $item]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-400 hover:text-red-300 transition-colors p-1 rounded hover:bg-red-900/30"
                                            onclick="return confirm('¿Está seguro de eliminar este turno?')"
                                            title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <button onclick="openEditCambioEquipoModal({{ $item->id }})"
                                    class="text-blue-400 hover:text-blue-300 transition-colors p-1 rounded hover:bg-blue-900/30"
                                    title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>
                                <button onclick="openAdjuntarFacturaModal('cambio_equipo', {{ $item->id }})"
                                    class="text-green-400 hover:text-green-300 transition-colors p-1 rounded hover:bg-green-900/30"
                                    title="Adjuntar factura">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </button>
                                <form action="{{ route('rodados.cambios-equipos.destroy', ['cambio' => $item]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-400 hover:text-red-300 transition-colors p-1 rounded hover:bg-red-900/30"
                                            onclick="return confirm('¿Está seguro de eliminar este cambio de equipo?')"
                                            title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-400">
                        No hay servicios registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modales -->
@include('rodados.modals.turno-modal')
@include('rodados.modals.cambio-equipo-modal')
@include('rodados.partials.modal-adjuntar-factura')
@include('rodados.partials.modal-revisar-cobertura')

<script>
    function openCreateTurnoModal(tipo) {
        document.getElementById('turno-form').reset();
        document.getElementById('turno-form').action = '{{ route("rodados.turnos.store") }}';
        document.getElementById('turno-id').value = '';
        
        const tipoFieldContainer = document.getElementById('turno-tipo-field-container');
        const tipoSelect = document.getElementById('turno-tipo');
        const tipoHidden = document.getElementById('turno-tipo-hidden');
        
        // Ocultar el campo "Tipo de Turno" cuando se abre desde botones específicos
        if (tipo === 'turno_service' || tipo === 'turno_mecanico') {
            if (tipoFieldContainer) {
                tipoFieldContainer.style.display = 'none';
            }
            if (tipoHidden) {
                tipoHidden.value = tipo === 'turno_mecanico' ? 'turno_mecanico' : 'turno_service';
            }
            if (tipoSelect) {
                tipoSelect.value = tipo === 'turno_mecanico' ? 'turno_taller' : tipo;
            }
        } else {
            // Si se abre de otra manera, mostrar el campo
            if (tipoFieldContainer) {
                tipoFieldContainer.style.display = 'block';
            }
            if (tipoHidden) {
                tipoHidden.value = '';
            }
            if (tipoSelect) {
                tipoSelect.value = tipo;
            }
        }
        
        document.getElementById('turno-modal-title').textContent = 'Nuevo ' + (tipo === 'turno_service' ? 'Turno Service' : tipo === 'turno_mecanico' ? 'Turno Mecánico' : tipo === 'cambio_equipo' ? 'Cambio de Equipo' : 'Turno al Taller');
        toggleTurnoFields();
        document.getElementById('turno-modal').classList.remove('hidden');
    }

    function openCreateCambioEquipoModal() {
        document.getElementById('cambio-equipo-form').reset();
        document.getElementById('cambio-equipo-form').action = '{{ route("rodados.cambios-equipos.store") }}';
        document.getElementById('cambio-equipo-id').value = '';
        document.getElementById('cambio-equipo-modal-title').textContent = 'Nuevo Cambio de Equipo';
        toggleCambioEquipoFields();
        document.getElementById('cambio-equipo-modal').classList.remove('hidden');
    }

    async function openEditTurnoModal(id) {
        try {
            // Cargar datos del turno vía AJAX
            // Construir la URL manualmente para evitar problemas con Blade
            const baseUrl = '{{ url("/rodados/turnos") }}';
            const url = baseUrl + '/' + id;
            console.log('Cargando turno desde:', url);
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Error al cargar los datos del turno');
            }

            const turno = await response.json();
            
            // Logging para depurar
            console.log('Turno cargado:', turno);
            console.log('Partes afectadas:', turno.partes_afectadas);
            console.log('Tipo de partes_afectadas:', typeof turno.partes_afectadas);
            console.log('Es array?', Array.isArray(turno.partes_afectadas));
            console.log('Descripción del turno:', turno.descripcion);

            // Establecer ID y acción del formulario
            document.getElementById('turno-id').value = turno.id;
            const updateBaseUrl = '{{ url("/rodados/turnos") }}';
            document.getElementById('turno-form').action = updateBaseUrl + '/' + id;
            document.getElementById('turno-modal-title').textContent = 'Editar Turno';

            // Prellenar campos básicos (estos siempre están visibles)
            document.getElementById('turno-rodado').value = turno.rodado_id || '';
            document.getElementById('turno-taller').value = turno.taller_id || '';
            document.getElementById('turno-fecha-hora').value = turno.fecha_hora || '';

            // Manejar tipo de turno
            const tipoFieldContainer = document.getElementById('turno-tipo-field-container');
            const tipoSelect = document.getElementById('turno-tipo');
            const tipoHidden = document.getElementById('turno-tipo-hidden');
            
            // Mostrar el campo de tipo
            if (tipoFieldContainer) {
                tipoFieldContainer.style.display = 'block';
            }
            
            // Establecer el tipo correcto
            let tipoDisplay = turno.tipo;
            if (turno.tipo === 'turno_mecanico') {
                tipoDisplay = 'turno_taller'; // Mostrar como "turno_taller" en el select
            }
            
            if (tipoSelect) {
                tipoSelect.value = tipoDisplay;
            }
            if (tipoHidden) {
                tipoHidden.value = turno.tipo;
            }

            // Toggle campos según el tipo
            toggleTurnoFields();

            // Prellenar campos según el tipo de turno
            // Usar setTimeout para asegurar que los campos estén visibles después del toggle
            setTimeout(() => {
                if (turno.tipo === 'turno_service') {
                    // Campos de turno service
                    const encargadoDejarService = document.getElementById('turno-encargado-dejar-service');
                    const encargadoRetirarService = document.getElementById('turno-encargado-retirar-service');
                    const descripcionService = document.getElementById('turno-descripcion-service');
                    
                    if (encargadoDejarService) encargadoDejarService.value = turno.encargado_dejar || '';
                    if (encargadoRetirarService) encargadoRetirarService.value = turno.encargado_retirar || '';
                    if (descripcionService) descripcionService.value = turno.descripcion || '';
                } else if (turno.tipo === 'turno_mecanico') {
                    // Campos de turno mecánico/taller
                    const descripcionTaller = document.getElementById('turno-descripcion-taller');
                    const encargadoDejarTaller = document.getElementById('turno-encargado-dejar-taller');
                    const encargadoRetirarTaller = document.getElementById('turno-encargado-retirar-taller');
                    
                    console.log('=== PRELLENANDO CAMPOS DE TURNO MECÁNICO ===');
                    console.log('Descripción recibida del servidor:', turno.descripcion);
                    console.log('Tipo de descripción:', typeof turno.descripcion);
                    console.log('Descripción es null?', turno.descripcion === null);
                    console.log('Descripción es undefined?', turno.descripcion === undefined);
                    console.log('Descripción es string vacío?', turno.descripcion === '');
                    
                    if (descripcionTaller) {
                        const descripcionValue = (turno.descripcion !== null && turno.descripcion !== undefined) ? String(turno.descripcion) : '';
                        descripcionTaller.value = descripcionValue;
                        // Asegurar que el campo tenga el name correcto y esté habilitado
                        descripcionTaller.name = 'descripcion';
                        descripcionTaller.disabled = false;
                        descripcionTaller.required = true;
                        console.log('✓ Campo descripción encontrado');
                        console.log('  Valor establecido:', descripcionValue);
                        console.log('  Valor actual del campo:', descripcionTaller.value);
                        console.log('  Campo visible?', descripcionTaller.offsetParent !== null);
                        console.log('  Campo name:', descripcionTaller.name);
                        console.log('  Campo disabled:', descripcionTaller.disabled);
                        console.log('  Campo required:', descripcionTaller.required);
                        
                        // Forzar el valor nuevamente después de un pequeño delay adicional
                        setTimeout(() => {
                            if (descripcionTaller.value !== descripcionValue) {
                                console.log('⚠ Valor no se estableció correctamente, reintentando...');
                                descripcionTaller.value = descripcionValue;
                                descripcionTaller.name = 'descripcion';
                                descripcionTaller.disabled = false;
                                console.log('  Valor después del reintento:', descripcionTaller.value);
                                console.log('  Name después del reintento:', descripcionTaller.name);
                            }
                        }, 100);
                    } else {
                        console.error('✗ ERROR: No se encontró el campo turno-descripcion-taller');
                    }
                    
                    if (encargadoDejarTaller) {
                        encargadoDejarTaller.value = turno.encargado_dejar || '';
                        console.log('✓ Encargado dejar prellenado:', encargadoDejarTaller.value);
                    } else {
                        console.error('✗ ERROR: No se encontró el campo turno-encargado-dejar-taller');
                    }
                    
                    if (encargadoRetirarTaller) {
                        encargadoRetirarTaller.value = turno.encargado_retirar || '';
                        console.log('✓ Encargado retirar prellenado:', encargadoRetirarTaller.value);
                    } else {
                        console.error('✗ ERROR: No se encontró el campo turno-encargado-retirar-taller');
                    }
                    
                    // Cargar partes afectadas si existen (solo para turno mecánico)
                    let partesAfectadas = turno.partes_afectadas || [];
                    console.log('Partes afectadas antes de procesar:', partesAfectadas);
                    
                    // Si es un objeto (no array), convertirlo a array
                    if (partesAfectadas && typeof partesAfectadas === 'object' && !Array.isArray(partesAfectadas)) {
                        console.log('Convirtiendo objeto a array');
                        partesAfectadas = Object.values(partesAfectadas);
                    }
                    
                    // Asegurar que sea un array
                    if (!Array.isArray(partesAfectadas)) {
                        console.log('No es array, convirtiendo a array vacío');
                        partesAfectadas = [];
                    }
                    
                    console.log('Partes afectadas después de procesar:', partesAfectadas);
                    console.log('Cantidad de items:', partesAfectadas.length);
                    
                    const tbody = document.getElementById('partes-afectadas-body');
                    tbody.innerHTML = ''; // Limpiar tabla
                    
                    // Función helper para escapar HTML
                    const escapeHtml = (text) => {
                        if (text === null || text === undefined) return '';
                        const map = {
                            '&': '&amp;',
                            '<': '&lt;',
                            '>': '&gt;',
                            '"': '&quot;',
                            "'": '&#039;'
                        };
                        return String(text).replace(/[&<>"']/g, m => map[m]);
                    };
                    
                    // Resetear contador antes de cargar items existentes
                    filaParteCounter = 0;
                    
                    if (partesAfectadas.length > 0) {
                        console.log('Cargando', partesAfectadas.length, 'items en la tabla');
                        partesAfectadas.forEach((parte) => {
                            console.log('Procesando item:', parte);
                            filaParteCounter++;
                            const tr = document.createElement('tr');
                            tr.setAttribute('data-parte-index', filaParteCounter);
                            
                            tr.innerHTML = `
                                <td class="px-4 py-2">
                                    <input type="text" name="partes_afectadas[${filaParteCounter}][item]" required
                                        value="${escapeHtml(parte.item || '')}"
                                        class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-2 py-1 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" name="partes_afectadas[${filaParteCounter}][cantidad]" required min="1"
                                        value="${escapeHtml(parte.cantidad || '')}"
                                        class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-2 py-1 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-2">
                                    <input type="text" name="partes_afectadas[${filaParteCounter}][descripcion]" required
                                        value="${escapeHtml(parte.descripcion || '')}"
                                        class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-2 py-1 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
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
                        });
                        console.log('Items cargados en la tabla. Total filas:', tbody.children.length);
                    } else {
                        console.log('No hay items para cargar');
                    }
                    
                    console.log('=== FIN PRELLENADO ===');
                }

                // Abrir el modal después de prellenar
                document.getElementById('turno-modal').classList.remove('hidden');
            }, 100);

        } catch (error) {
            console.error('Error al cargar el turno:', error);
            alert('Error al cargar los datos del turno. Por favor, intenta nuevamente.');
        }
    }

    function openEditCambioEquipoModal(id) {
        document.getElementById('cambio-equipo-id').value = id;
        const updateBaseUrl = '{{ url("/rodados/cambios-equipos") }}';
        document.getElementById('cambio-equipo-form').action = updateBaseUrl + '/' + id;
        document.getElementById('cambio-equipo-modal-title').textContent = 'Editar Cambio de Equipo';
        document.getElementById('cambio-equipo-modal').classList.remove('hidden');
        toggleCambioEquipoFields();
    }
</script>

