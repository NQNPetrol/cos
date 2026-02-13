@extends('layouts.cliente')

@section('title', 'Supervisores')

@section('content')
<div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-br from-blue-600/20 to-blue-400/10 rounded-xl border border-blue-500/20">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-7 h-7 text-blue-400">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-100 tracking-tight">Supervisores</h1>
                    <p class="text-sm text-gray-400 mt-0.5">Gestión de supervisores y asignaciones del cliente</p>
                </div>
            </div>
            <!-- Quick Stats -->
            <div class="flex flex-wrap gap-3">
                <div class="flex items-center gap-2 px-4 py-2 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                    <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                    <span class="text-xs text-gray-400">Total</span>
                    <span class="text-sm font-semibold text-gray-200">{{ $supervisores->count() }}</span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                    <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                    <span class="text-xs text-gray-400">Con Personal</span>
                    <span class="text-sm font-semibold text-gray-200">{{ $conPersonal }}</span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                    <div class="w-2 h-2 rounded-full bg-purple-400"></div>
                    <span class="text-xs text-gray-400">Con Patrulla</span>
                    <span class="text-sm font-semibold text-gray-200">{{ $conPatrulla }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($supervisores as $supervisor)
        <div class="bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50 overflow-hidden shadow-xl hover:border-blue-500/30 transition-all duration-200" id="card-{{ $supervisor->id }}">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-blue-600/20 to-purple-600/20 p-4 border-b border-zinc-700/50">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-blue-600/20 border border-blue-500/30 flex items-center justify-center flex-shrink-0">
                        <span class="text-lg font-bold text-blue-400">{{ strtoupper(substr($supervisor->name, 0, 2)) }}</span>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-semibold text-gray-100 truncate">{{ $supervisor->name }}</h3>
                        <p class="text-xs text-gray-400 truncate">{{ $supervisor->email }}</p>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="p-4 space-y-3">
                @if(!$supervisor->personal)
                    <!-- No personal assigned -->
                    <div class="text-center py-3">
                        <p class="text-sm text-gray-500 mb-3">Sin registro de personal asignado</p>
                        <button onclick="abrirModalAsignarPersonal({{ $supervisor->id }}, '{{ addslashes($supervisor->name) }}')" class="w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-lg shadow-blue-600/20">
                            <i class="bi bi-person-plus mr-2"></i>Asignar Personal
                        </button>
                    </div>
                @else
                    <!-- Personal Data -->
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Nombre:</span>
                            <span class="text-gray-200 font-medium">{{ $supervisor->personal->nombre }} {{ $supervisor->personal->apellido }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">DNI:</span>
                            <span class="text-gray-200 font-medium">{{ $supervisor->personal->nro_doc ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Legajo:</span>
                            <span class="text-gray-200 font-medium">{{ $supervisor->personal->legajo ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Cargo:</span>
                            <span class="text-gray-200 font-medium">{{ $supervisor->personal->cargo ?? 'N/A' }}</span>
                        </div>
                        @if($supervisor->personal->telefono)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Teléfono:</span>
                            <span class="text-gray-200 font-medium">{{ $supervisor->personal->telefono }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Patrulla Section -->
                    @if($supervisor->personal->supervisorPatrulla)
                        <div class="mt-3 p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-xl">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs text-emerald-400 font-semibold uppercase tracking-wider">Patrulla Asignada</span>
                                <button onclick="abrirModalPatrulla({{ $supervisor->personal->id }}, true)" class="text-xs text-blue-400 hover:text-blue-300 transition-colors">
                                    <i class="bi bi-arrow-repeat mr-1"></i>Cambiar
                                </button>
                            </div>
                            <div class="text-gray-200 font-semibold">{{ $supervisor->personal->supervisorPatrulla->patrulla->patente }}</div>
                            <div class="text-xs text-gray-400">{{ $supervisor->personal->supervisorPatrulla->patrulla->marca }} {{ $supervisor->personal->supervisorPatrulla->patrulla->modelo }} {{ $supervisor->personal->supervisorPatrulla->patrulla->color ? '- ' . $supervisor->personal->supervisorPatrulla->patrulla->color : '' }}</div>
                        </div>
                    @else
                        <button onclick="abrirModalPatrulla({{ $supervisor->personal->id }}, false)" class="w-full mt-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-lg shadow-purple-600/20">
                            <i class="bi bi-car-front mr-2"></i>Asignar Patrulla
                        </button>
                    @endif

                    <!-- Empresas Section -->
                    <div class="mt-3">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Clientes Asignados</span>
                            <button onclick="abrirModalEmpresas({{ $supervisor->personal->id }})" class="text-xs text-blue-400 hover:text-blue-300 transition-colors">
                                <i class="bi bi-pencil-square mr-1"></i>Editar
                            </button>
                        </div>
                        @if($supervisor->personal->empresasAsociadas->count() > 0)
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($supervisor->personal->empresasAsociadas as $empresa)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                                    {{ $empresa->nombre }}
                                </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-500 italic">Sin clientes asignados</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="text-center py-12 bg-zinc-800/30 rounded-xl border border-zinc-700/30">
                <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="text-gray-400 text-lg font-medium">No hay supervisores registrados</p>
                <p class="text-gray-500 text-sm mt-1">Los supervisores con rol "clientsupervisor" aparecerán aquí.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal: Asignar Personal -->
<div id="modal-asignar-personal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center" style="padding-left: calc(var(--fb-sidebar-width, 240px) + 1rem); padding-top: calc(var(--fb-topbar-height, 60px) + 1.5rem);">
    <div class="w-full max-w-md bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-700/50 bg-zinc-800/50">
            <h3 class="text-lg font-semibold text-gray-100">Asignar Personal</h3>
            <p class="text-sm text-gray-400 mt-0.5">Supervisor: <span id="modal-personal-supervisor-name" class="text-blue-400"></span></p>
        </div>
        <div class="p-6">
            <input type="hidden" id="modal-personal-user-id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Buscar por DNI o Nombre</label>
                <input type="text" id="buscar-personal-input" placeholder="Ingrese DNI o nombre..." class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" oninput="buscarPersonal()">
            </div>
            <div id="resultados-personal" class="space-y-2 max-h-64 overflow-y-auto"></div>
        </div>
        <div class="px-6 py-4 border-t border-zinc-700/50 flex justify-end gap-3">
            <button onclick="cerrarModal('modal-asignar-personal')" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-gray-200 transition-colors">Cancelar</button>
        </div>
    </div>
</div>

<!-- Modal: Asignar/Cambiar Patrulla -->
<div id="modal-patrulla" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center" style="padding-left: calc(var(--fb-sidebar-width, 240px) + 1rem); padding-top: calc(var(--fb-topbar-height, 60px) + 1.5rem);">
    <div class="w-full max-w-md bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-700/50 bg-zinc-800/50">
            <h3 class="text-lg font-semibold text-gray-100" id="modal-patrulla-title">Asignar Patrulla</h3>
        </div>
        <div class="p-6">
            <input type="hidden" id="modal-patrulla-supervisor-id">
            <input type="hidden" id="modal-patrulla-is-cambio" value="0">
            <div id="lista-patrullas" class="space-y-2 max-h-72 overflow-y-auto">
                <div class="text-center py-4 text-gray-500 text-sm">Cargando patrullas...</div>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-zinc-700/50 flex justify-end gap-3">
            <button onclick="cerrarModal('modal-patrulla')" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-gray-200 transition-colors">Cancelar</button>
        </div>
    </div>
</div>

<!-- Modal: Asignar Empresas -->
<div id="modal-empresas" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center" style="padding-left: calc(var(--fb-sidebar-width, 240px) + 1rem); padding-top: calc(var(--fb-topbar-height, 60px) + 1.5rem);">
    <div class="w-full max-w-md bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-700/50 bg-zinc-800/50">
            <h3 class="text-lg font-semibold text-gray-100">Asignar Clientes</h3>
            <p class="text-sm text-gray-400 mt-0.5">Seleccione los clientes (empresas asociadas) para este supervisor</p>
        </div>
        <div class="p-6">
            <input type="hidden" id="modal-empresas-supervisor-id">
            <div class="space-y-2 max-h-72 overflow-y-auto" id="lista-empresas">
                @foreach($empresasAsociadas as $empresa)
                <label class="flex items-center gap-3 p-3 bg-zinc-800/50 border border-zinc-700/30 rounded-xl hover:border-indigo-500/30 transition-all cursor-pointer">
                    <input type="checkbox" value="{{ $empresa->id }}" class="empresa-checkbox w-4 h-4 rounded border-zinc-600 bg-zinc-700 text-blue-600 focus:ring-blue-500/30 focus:ring-offset-0">
                    <span class="text-sm text-gray-200">{{ $empresa->nombre }}</span>
                </label>
                @endforeach
            </div>
        </div>
        <div class="px-6 py-4 border-t border-zinc-700/50 flex justify-end gap-3">
            <button onclick="cerrarModal('modal-empresas')" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-gray-200 transition-colors">Cancelar</button>
            <button onclick="guardarEmpresas()" class="px-4 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-all shadow-lg shadow-blue-600/20">Guardar</button>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast-success" class="fixed z-[100] max-w-sm w-full hidden" style="top: calc(var(--fb-topbar-height, 60px) + 1rem); right: 1.5rem;">
    <div class="bg-zinc-900 border border-green-500/30 rounded-2xl shadow-2xl shadow-green-900/20 p-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-sm text-gray-200" id="toast-message">Operación exitosa</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function showToast(message) {
    const toast = document.getElementById('toast-success');
    document.getElementById('toast-message').textContent = message;
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 4000);
}

function cerrarModal(id) {
    document.getElementById(id).classList.add('hidden');
}

// Close modals on backdrop click
document.querySelectorAll('#modal-asignar-personal, #modal-patrulla, #modal-empresas').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) cerrarModal(this.id);
    });
});

// === ASIGNAR PERSONAL ===
function abrirModalAsignarPersonal(userId, userName) {
    document.getElementById('modal-personal-user-id').value = userId;
    document.getElementById('modal-personal-supervisor-name').textContent = userName;
    document.getElementById('buscar-personal-input').value = '';
    document.getElementById('resultados-personal').innerHTML = '';
    document.getElementById('modal-asignar-personal').classList.remove('hidden');
    document.getElementById('buscar-personal-input').focus();
}

let searchTimeout;
function buscarPersonal() {
    clearTimeout(searchTimeout);
    const dni = document.getElementById('buscar-personal-input').value;
    if (dni.length < 2) {
        document.getElementById('resultados-personal').innerHTML = '<p class="text-xs text-gray-500 text-center py-2">Ingrese al menos 2 caracteres</p>';
        return;
    }
    searchTimeout = setTimeout(() => {
        fetch(`{{ route('client.supervisores.personal-disponible') }}?dni=${encodeURIComponent(dni)}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        })
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById('resultados-personal');
            if (data.length === 0) {
                container.innerHTML = '<p class="text-xs text-gray-500 text-center py-2">No se encontraron resultados</p>';
                return;
            }
            container.innerHTML = data.map(p => `
                <button onclick="seleccionarPersonal(${p.id})" class="w-full text-left p-3 bg-zinc-800/50 border border-zinc-700/30 rounded-xl hover:border-blue-500/30 hover:bg-zinc-700/30 transition-all">
                    <div class="font-medium text-gray-200 text-sm">${p.nombre} ${p.apellido}</div>
                    <div class="text-xs text-gray-400 mt-1">DNI: ${p.nro_doc || 'N/A'} | Legajo: ${p.legajo || 'N/A'} | Cargo: ${p.cargo || 'N/A'}</div>
                </button>
            `).join('');
        });
    }, 300);
}

function seleccionarPersonal(personalId) {
    const userId = document.getElementById('modal-personal-user-id').value;
    fetch('{{ route("client.supervisores.asignar-personal") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ user_id: userId, personal_id: personalId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message);
            cerrarModal('modal-asignar-personal');
            location.reload();
        } else {
            alert(data.error || 'Error al asignar personal');
        }
    })
    .catch(() => alert('Error de conexión'));
}

// === ASIGNAR/CAMBIAR PATRULLA ===
function abrirModalPatrulla(supervisorId, esCambio) {
    document.getElementById('modal-patrulla-supervisor-id').value = supervisorId;
    document.getElementById('modal-patrulla-is-cambio').value = esCambio ? '1' : '0';
    document.getElementById('modal-patrulla-title').textContent = esCambio ? 'Cambiar Patrulla' : 'Asignar Patrulla';
    document.getElementById('lista-patrullas').innerHTML = '<div class="text-center py-4 text-gray-500 text-sm">Cargando...</div>';
    document.getElementById('modal-patrulla').classList.remove('hidden');

    fetch(`{{ route('client.supervisores.patrullas-disponibles') }}?supervisor_id=${supervisorId}`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
    })
    .then(r => r.json())
    .then(data => {
        const container = document.getElementById('lista-patrullas');
        if (data.length === 0) {
            container.innerHTML = '<p class="text-xs text-gray-500 text-center py-4">No hay patrullas disponibles</p>';
            return;
        }
        container.innerHTML = data.map(p => `
            <button onclick="asignarPatrulla(${p.id})" class="w-full text-left p-3 bg-zinc-800/50 border border-zinc-700/30 rounded-xl hover:border-purple-500/30 hover:bg-zinc-700/30 transition-all">
                <div class="font-semibold text-gray-200 text-sm">${p.patente}</div>
                <div class="text-xs text-gray-400 mt-1">${p.marca || ''} ${p.modelo || ''} ${p.color ? '- ' + p.color : ''}</div>
                <div class="text-xs text-gray-500 mt-0.5">Estado: ${p.estado || 'N/A'}</div>
            </button>
        `).join('');
    });
}

function asignarPatrulla(patrullaId) {
    const supervisorId = document.getElementById('modal-patrulla-supervisor-id').value;
    const esCambio = document.getElementById('modal-patrulla-is-cambio').value === '1';
    const url = esCambio ? '{{ route("client.supervisores.cambiar-patrulla") }}' : '{{ route("client.supervisores.asignar-patrulla") }}';
    const method = esCambio ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ supervisor_id: supervisorId, patrulla_id: patrullaId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message);
            cerrarModal('modal-patrulla');
            location.reload();
        } else {
            alert(data.error || 'Error al asignar patrulla');
        }
    })
    .catch(() => alert('Error de conexión'));
}

// === ASIGNAR EMPRESAS ===
function abrirModalEmpresas(supervisorId) {
    document.getElementById('modal-empresas-supervisor-id').value = supervisorId;
    // Reset all checkboxes
    document.querySelectorAll('.empresa-checkbox').forEach(cb => cb.checked = false);

    // Check current assignments
    @foreach($supervisores as $s)
        @if($s->personal)
        if (supervisorId == {{ $s->personal->id }}) {
            @foreach($s->personal->empresasAsociadas as $ea)
            document.querySelector('.empresa-checkbox[value="{{ $ea->id }}"]').checked = true;
            @endforeach
        }
        @endif
    @endforeach

    document.getElementById('modal-empresas').classList.remove('hidden');
}

function guardarEmpresas() {
    const supervisorId = document.getElementById('modal-empresas-supervisor-id').value;
    const empresaIds = Array.from(document.querySelectorAll('.empresa-checkbox:checked')).map(cb => parseInt(cb.value));

    fetch('{{ route("client.supervisores.asignar-empresas") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ supervisor_id: supervisorId, empresa_ids: empresaIds })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message);
            cerrarModal('modal-empresas');
            location.reload();
        } else {
            alert(data.error || 'Error al asignar clientes');
        }
    })
    .catch(() => alert('Error de conexión'));
}
</script>
@endpush
