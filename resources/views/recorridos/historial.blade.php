@extends('layouts.cliente')

@section('title', 'Historial de Recorridos')

@section('content')
<div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-br from-purple-600/20 to-purple-400/10 rounded-xl border border-purple-500/20">
                    <svg fill="currentColor" viewBox="0 0 237.569 237.569" class="w-7 h-7 text-purple-400">
                        <path d="M234.362,24.818c-2.009-1.403-4.576-1.736-6.879-0.89l-71.607,26.306L84.272,23.927c-1.671-0.614-3.504-0.614-5.173,0 L4.914,51.183C1.962,52.268,0,55.079,0,58.223v148.379c0,2.451,1.198,4.748,3.207,6.15c1.276,0.891,2.778,1.35,4.293,1.35 c0.871,0,1.747-0.152,2.586-0.46l71.599-26.306l71.604,26.306c1.669,0.613,3.502,0.613,5.173,0l74.193-27.256 c2.952-1.084,4.914-3.895,4.914-7.04V30.968C237.569,28.516,236.372,26.22,234.362,24.818z M222.569,174.112l-66.693,24.5 l-71.604-26.306c-0.835-0.307-1.711-0.46-2.586-0.46c-0.876,0-1.752,0.153-2.587,0.46L15,195.857V63.458l66.686-24.5l71.604,26.306 c1.669,0.613,3.502,0.613,5.173,0l64.107-23.551V174.112z"/>
                        <path d="M157.018,114.759c0-25.51-20.752-46.264-46.26-46.264c-25.51,0-46.264,20.754-46.264,46.264 c0,25.509,20.754,46.262,46.264,46.262c10.053,0,19.359-3.233,26.955-8.701l22.563,22.559c1.464,1.464,3.383,2.196,5.303,2.196 c1.919,0,3.839-0.732,5.304-2.197c2.929-2.929,2.928-7.678-0.001-10.606l-22.562-22.559 C153.785,134.116,157.018,124.811,157.018,114.759z M79.494,114.759c0-17.239,14.025-31.264,31.264-31.264 c17.237,0,31.26,14.025,31.26,31.264c0,17.238-14.023,31.262-31.26,31.262C93.519,146.02,79.494,131.996,79.494,114.759z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-100 tracking-tight">Historial de Recorridos</h1>
                    <p class="text-sm text-gray-400 mt-0.5">Registro de recorridos realizados</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3 items-center">
                <!-- Stats -->
                <div class="flex items-center gap-2 px-4 py-2 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                    <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                    <span class="text-xs text-gray-400">Total</span>
                    <span class="text-sm font-semibold text-gray-200">{{ $totalRecorridos }}</span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                    <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                    <span class="text-xs text-gray-400">Prom. Duración</span>
                    <span class="text-sm font-semibold text-gray-200">{{ round($promedioDuracion) }} min</span>
                </div>
                @if($velocidadExcedida > 0)
                <div class="flex items-center gap-2 px-4 py-2 bg-red-500/10 rounded-xl border border-red-500/20">
                    <div class="w-2 h-2 rounded-full bg-red-400"></div>
                    <span class="text-xs text-red-300">Vel. Excedida</span>
                    <span class="text-sm font-semibold text-red-200">{{ $velocidadExcedida }}</span>
                </div>
                @endif
                @if($tienePersonal && $tienePatrulla)
                <button onclick="abrirModalRegistrar()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-blue-600/20">
                    <i class="bi bi-plus-lg mr-2"></i>Registrar Recorrido
                </button>
                @endif
            </div>
        </div>
    </div>

    @if(!$tienePersonal)
    <div class="mb-6 p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl">
        <div class="flex items-center gap-3">
            <i class="bi bi-exclamation-triangle-fill text-amber-400 text-lg"></i>
            <p class="text-sm text-amber-200">Debe tener un registro de personal asignado para registrar recorridos.</p>
        </div>
    </div>
    @elseif(!$tienePatrulla)
    <div class="mb-6 p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl">
        <div class="flex items-center gap-3">
            <i class="bi bi-exclamation-triangle-fill text-amber-400 text-lg"></i>
            <p class="text-sm text-amber-200">Debe tener una patrulla asignada para registrar recorridos.</p>
        </div>
    </div>
    @endif

    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
        <div class="flex items-center gap-2">
            <button onclick="toggleFiltrosHistorial()" id="btn-filtros-historial"
                class="inline-flex items-center gap-2 px-3.5 py-2 bg-zinc-800 border border-zinc-700 rounded-xl text-sm text-gray-300 hover:bg-zinc-700 hover:border-zinc-600 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filtros
            </button>
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="busqueda-historial" placeholder="Buscar registro..." oninput="aplicarFiltrosHistorial()"
                    class="pl-9 pr-3 py-2 bg-zinc-800 border border-zinc-700 rounded-xl text-sm text-gray-200 placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all w-56">
            </div>
            <span class="text-xs text-gray-500" id="historial-count">{{ $registros->count() }} registros</span>
        </div>
    </div>

    <!-- Filtros Panel (collapsible) -->
    <div id="filtros-historial-panel" class="hidden mb-5 p-4 bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Recorrido</label>
                <select id="filtro-hist-recorrido" class="w-full rounded-lg bg-zinc-900 border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30" onchange="aplicarFiltrosHistorial()">
                    <option value="">Todos los recorridos</option>
                    @foreach($recorridos as $rec)
                        <option value="{{ $rec->nombre }}">{{ $rec->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Estado</label>
                <select id="filtro-hist-estado" class="w-full rounded-lg bg-zinc-900 border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30" onchange="aplicarFiltrosHistorial()">
                    <option value="">Todos</option>
                    <option value="normal">Normal</option>
                    <option value="excedida">Vel. Excedida</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Desde</label>
                <input type="date" id="filtro-hist-desde" class="w-full rounded-lg bg-zinc-900 border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30" onchange="aplicarFiltrosHistorial()">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Hasta</label>
                <input type="date" id="filtro-hist-hasta" class="w-full rounded-lg bg-zinc-900 border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30" onchange="aplicarFiltrosHistorial()">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Ordenar por</label>
                <select id="filtro-hist-orden" class="w-full rounded-lg bg-zinc-900 border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30" onchange="aplicarFiltrosHistorial()">
                    <option value="reciente">Más reciente</option>
                    <option value="antiguo">Más antiguo</option>
                    <option value="velocidad">Mayor velocidad</option>
                    <option value="duracion">Mayor duración</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end mt-3">
            <button onclick="limpiarFiltrosHistorial()" class="text-xs text-gray-500 hover:text-gray-300 transition">Limpiar filtros</button>
        </div>
    </div>

    <!-- Tabla Historial -->
    <div class="bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-700/50">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Fecha/Hora</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Recorrido</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Patrulla</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Velocidad</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Duración Est.</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Fin Est.</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Estado</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-700/30">
                    @forelse($registros as $reg)
                    <tr class="hover:bg-zinc-700/30 transition-colors duration-150 historial-row"
                        data-recorrido="{{ $reg->recorrido->nombre ?? '' }}"
                        data-empresa="{{ $reg->recorrido->empresaAsociada->nombre ?? '' }}"
                        data-patrulla="{{ $reg->patrulla->patente ?? '' }}"
                        data-estado="{{ $reg->velocidad_excedida ? 'excedida' : 'normal' }}"
                        data-velocidad="{{ $reg->velocidad }}"
                        data-duracion="{{ $reg->duracion_est ?? 0 }}"
                        data-fecha="{{ $reg->fecha_hora_inicio ? $reg->fecha_hora_inicio->format('Y-m-d') : '' }}"
                        data-timestamp="{{ $reg->fecha_hora_inicio ? $reg->fecha_hora_inicio->timestamp : 0 }}">
                        <td class="px-5 py-4 text-sm text-gray-200">
                            {{ $reg->fecha_hora_inicio ? $reg->fecha_hora_inicio->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-sm font-medium text-gray-200">{{ $reg->recorrido->nombre ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $reg->recorrido->empresaAsociada->nombre ?? '' }}</div>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-300">{{ $reg->patrulla->patente ?? 'N/A' }}</td>
                        <td class="px-5 py-4 text-sm text-gray-300">{{ $reg->velocidad }} km/h</td>
                        <td class="px-5 py-4 text-sm text-gray-300">{{ $reg->duracion_est ? $reg->duracion_est . ' min' : '-' }}</td>
                        <td class="px-5 py-4 text-sm text-gray-300">
                            {{ $reg->fechahora_fin_est ? $reg->fechahora_fin_est->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="px-5 py-4">
                            @if($reg->velocidad_excedida)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Vel. Excedida
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div> Normal
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @can('editar.recorridos-cliente')
                                <button onclick="editarRegistro({{ $reg->id }}, '{{ $reg->fecha_hora_inicio ? $reg->fecha_hora_inicio->format('Y-m-d\TH:i') : '' }}', {{ $reg->velocidad }}, {{ $reg->recorrido_id }})" class="p-2 text-gray-400 hover:text-amber-400 transition-colors" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @endcan
                                @can('eliminar.historial-recorridos-cliente')
                                <button onclick="confirmarEliminarRegistro({{ $reg->id }})" class="p-2 text-gray-400 hover:text-red-400 transition-colors" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="bi bi-clock-history text-4xl mb-3 block"></i>
                                <p class="font-medium">No hay recorridos registrados</p>
                                <p class="text-sm mt-1">Registre un recorrido para comenzar el historial</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Registrar/Editar Recorrido Realizado -->
<div id="modal-registrar" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center" style="padding-left: calc(var(--fb-sidebar-width, 240px) + 1rem); padding-top: calc(var(--fb-topbar-height, 60px) + 1.5rem);">
    <div class="w-full max-w-md bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-700/50 bg-zinc-800/50">
            <h3 class="text-lg font-semibold text-gray-100" id="modal-registrar-title">Registrar Recorrido Realizado</h3>
        </div>
        <div class="p-6 space-y-4">
            <input type="hidden" id="registro-id" value="">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Recorrido *</label>
                <select id="reg-recorrido" required class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" onchange="actualizarDatosRecorrido()">
                    <option value="">Seleccionar recorrido...</option>
                    @foreach($recorridos as $rec)
                    <option value="{{ $rec->id }}" data-longitud="{{ $rec->longitud_mts }}" data-velmax="{{ $rec->velocidadmax_permitida }}">
                        {{ $rec->nombre }} - {{ $rec->empresaAsociada->nombre ?? '' }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Fecha y Hora de Inicio *</label>
                <input type="datetime-local" id="reg-fecha" required class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" onchange="calcularEstimados()">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Velocidad Promedio (km/h) *</label>
                <input type="number" id="reg-velocidad" required min="1" max="300" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" placeholder="Ej: 40" oninput="calcularEstimados()">
            </div>
            <!-- Calculated fields -->
            <div class="grid grid-cols-2 gap-4">
                <div class="p-3 bg-zinc-800/50 rounded-xl border border-zinc-700/30">
                    <div class="text-xs text-gray-400">Duración Estimada</div>
                    <div class="text-sm font-semibold text-gray-200 mt-1" id="calc-duracion">-</div>
                </div>
                <div class="p-3 bg-zinc-800/50 rounded-xl border border-zinc-700/30">
                    <div class="text-xs text-gray-400">Hora Fin Estimada</div>
                    <div class="text-sm font-semibold text-gray-200 mt-1" id="calc-fin">-</div>
                </div>
            </div>
            <!-- Speed warning -->
            <div id="alerta-velocidad" class="hidden p-3 bg-red-500/10 border border-red-500/20 rounded-xl">
                <div class="flex items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill text-red-400"></i>
                    <span class="text-sm text-red-300">La velocidad ingresada supera el máximo permitido para este recorrido</span>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-zinc-700/50 flex justify-end gap-3">
            <button onclick="cerrarModal('modal-registrar')" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-gray-200 transition-colors">Cancelar</button>
            <button onclick="guardarRegistro()" class="px-4 py-2.5 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-all shadow-lg shadow-blue-600/20">Guardar</button>
        </div>
    </div>
</div>

<!-- Modal: Confirmar Eliminación -->
<div id="modal-eliminar-registro" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center" style="padding-left: calc(var(--fb-sidebar-width, 240px));">
    <div class="w-full max-w-sm bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-6 text-center">
            <div class="w-14 h-14 rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-exclamation-triangle text-red-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-100 mb-2">Eliminar Registro</h3>
            <p class="text-sm text-gray-400">¿Está seguro de eliminar este registro del historial?</p>
            <input type="hidden" id="eliminar-registro-id">
        </div>
        <div class="px-6 py-4 border-t border-zinc-700/50 flex justify-center gap-3">
            <button onclick="cerrarModal('modal-eliminar-registro')" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-gray-200 transition-colors">Cancelar</button>
            <button onclick="eliminarRegistro()" class="px-4 py-2.5 text-sm font-medium bg-red-600 hover:bg-red-700 text-white rounded-xl transition-all">Eliminar</button>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast-success" class="fixed z-[100] max-w-sm w-full hidden" style="top: calc(var(--fb-topbar-height, 60px) + 1rem); right: 1.5rem;">
    <div class="bg-zinc-900 border border-green-500/30 rounded-2xl shadow-2xl shadow-green-900/20 p-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="text-sm text-gray-200" id="toast-message">Operación exitosa</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function showToast(msg) {
    const t = document.getElementById('toast-success');
    document.getElementById('toast-message').textContent = msg;
    t.classList.remove('hidden');
    setTimeout(() => t.classList.add('hidden'), 4000);
}

function cerrarModal(id) { document.getElementById(id).classList.add('hidden'); }

document.querySelectorAll('#modal-registrar, #modal-eliminar-registro').forEach(m => {
    m.addEventListener('click', function(e) { if (e.target === this) cerrarModal(this.id); });
});

function abrirModalRegistrar() {
    document.getElementById('registro-id').value = '';
    document.getElementById('reg-recorrido').value = '';
    document.getElementById('reg-fecha').value = '';
    document.getElementById('reg-velocidad').value = '';
    document.getElementById('calc-duracion').textContent = '-';
    document.getElementById('calc-fin').textContent = '-';
    document.getElementById('alerta-velocidad').classList.add('hidden');
    document.getElementById('modal-registrar-title').textContent = 'Registrar Recorrido Realizado';
    document.getElementById('modal-registrar').classList.remove('hidden');
}

function editarRegistro(id, fecha, velocidad, recorridoId) {
    document.getElementById('registro-id').value = id;
    document.getElementById('reg-recorrido').value = recorridoId;
    document.getElementById('reg-fecha').value = fecha;
    document.getElementById('reg-velocidad').value = velocidad;
    document.getElementById('modal-registrar-title').textContent = 'Editar Registro';
    document.getElementById('modal-registrar').classList.remove('hidden');
    calcularEstimados();
}

function actualizarDatosRecorrido() { calcularEstimados(); }

function calcularEstimados() {
    const select = document.getElementById('reg-recorrido');
    const option = select.options[select.selectedIndex];
    const longitud = parseFloat(option?.dataset?.longitud || 0);
    const velMax = parseFloat(option?.dataset?.velmax || 0);
    const velocidad = parseFloat(document.getElementById('reg-velocidad').value || 0);
    const fecha = document.getElementById('reg-fecha').value;

    if (longitud && velocidad) {
        const distKm = longitud / 1000;
        const durHoras = distKm / velocidad;
        const durMin = Math.round(durHoras * 60);
        document.getElementById('calc-duracion').textContent = durMin + ' min';

        if (fecha) {
            const inicio = new Date(fecha);
            const fin = new Date(inicio.getTime() + (durMin * 60000));
            document.getElementById('calc-fin').textContent = fin.toLocaleString('es-AR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        }
    } else {
        document.getElementById('calc-duracion').textContent = '-';
        document.getElementById('calc-fin').textContent = '-';
    }

    // Speed warning
    if (velMax && velocidad > velMax) {
        document.getElementById('alerta-velocidad').classList.remove('hidden');
    } else {
        document.getElementById('alerta-velocidad').classList.add('hidden');
    }
}

function guardarRegistro() {
    const id = document.getElementById('registro-id').value;
    const data = {
        recorrido_id: document.getElementById('reg-recorrido').value,
        fecha_hora_inicio: document.getElementById('reg-fecha').value,
        velocidad: parseInt(document.getElementById('reg-velocidad').value),
    };

    const url = id ? `{{ url('client/recorridos/historial') }}/${id}` : '{{ route("client.recorridos.historial.store") }}';
    const method = id ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(result => {
        if (result.success) {
            showToast(result.message);
            cerrarModal('modal-registrar');
            location.reload();
        } else {
            alert(result.error || 'Error al guardar el registro');
        }
    })
    .catch(() => alert('Error de conexión'));
}

// ===== Filtros dinámicos =====
function toggleFiltrosHistorial() {
    const panel = document.getElementById('filtros-historial-panel');
    panel.classList.toggle('hidden');
    const btn = document.getElementById('btn-filtros-historial');
    if (!panel.classList.contains('hidden')) {
        btn.classList.add('bg-zinc-700', 'border-zinc-600');
        btn.classList.remove('bg-zinc-800', 'border-zinc-700');
    } else {
        btn.classList.remove('bg-zinc-700', 'border-zinc-600');
        btn.classList.add('bg-zinc-800', 'border-zinc-700');
    }
}

function limpiarFiltrosHistorial() {
    document.getElementById('filtro-hist-recorrido').value = '';
    document.getElementById('filtro-hist-estado').value = '';
    document.getElementById('filtro-hist-desde').value = '';
    document.getElementById('filtro-hist-hasta').value = '';
    document.getElementById('filtro-hist-orden').value = 'reciente';
    document.getElementById('busqueda-historial').value = '';
    aplicarFiltrosHistorial();
}

function aplicarFiltrosHistorial() {
    const busqueda = document.getElementById('busqueda-historial').value.toLowerCase().trim();
    const recorrido = document.getElementById('filtro-hist-recorrido').value;
    const estado = document.getElementById('filtro-hist-estado').value;
    const desde = document.getElementById('filtro-hist-desde').value;
    const hasta = document.getElementById('filtro-hist-hasta').value;
    const orden = document.getElementById('filtro-hist-orden').value;

    const filas = Array.from(document.querySelectorAll('.historial-row'));
    let visibles = 0;

    filas.forEach(fila => {
        const matchBusqueda = busqueda === '' ||
            fila.dataset.recorrido.toLowerCase().includes(busqueda) ||
            fila.dataset.empresa.toLowerCase().includes(busqueda) ||
            fila.dataset.patrulla.toLowerCase().includes(busqueda);
        const matchRecorrido = recorrido === '' || fila.dataset.recorrido === recorrido;
        const matchEstado = estado === '' || fila.dataset.estado === estado;
        const matchDesde = desde === '' || fila.dataset.fecha >= desde;
        const matchHasta = hasta === '' || fila.dataset.fecha <= hasta;

        const mostrar = matchBusqueda && matchRecorrido && matchEstado && matchDesde && matchHasta;
        fila.style.display = mostrar ? '' : 'none';
        if (mostrar) visibles++;
    });

    // Ordenar
    const tbody = filas[0]?.parentElement;
    if (tbody && filas.length > 0) {
        const sorted = filas.slice().sort((a, b) => {
            if (orden === 'antiguo') return parseFloat(a.dataset.timestamp) - parseFloat(b.dataset.timestamp);
            if (orden === 'velocidad') return parseFloat(b.dataset.velocidad) - parseFloat(a.dataset.velocidad);
            if (orden === 'duracion') return parseFloat(b.dataset.duracion) - parseFloat(a.dataset.duracion);
            return parseFloat(b.dataset.timestamp) - parseFloat(a.dataset.timestamp); // reciente
        });
        sorted.forEach(row => tbody.appendChild(row));
    }

    const el = document.getElementById('historial-count');
    if (el) el.textContent = visibles + ' registro' + (visibles !== 1 ? 's' : '');
}

function confirmarEliminarRegistro(id) {
    document.getElementById('eliminar-registro-id').value = id;
    document.getElementById('modal-eliminar-registro').classList.remove('hidden');
}

function eliminarRegistro() {
    const id = document.getElementById('eliminar-registro-id').value;
    fetch(`{{ url('client/recorridos/historial') }}/${id}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message);
            cerrarModal('modal-eliminar-registro');
            location.reload();
        } else {
            alert(data.error || 'Error al eliminar');
        }
    });
}
</script>
@endpush
