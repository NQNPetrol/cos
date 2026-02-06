<div>
    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
        <div class="flex items-center gap-2">
            <button onclick="toggleFiltrosVehiculos()" id="btn-filtros-vehiculos"
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
                <input type="text" id="busqueda-vehiculo" placeholder="Buscar por patente, marca..."
                    class="pl-10 pr-4 py-2 bg-zinc-800 border border-zinc-700 rounded-xl text-sm text-gray-200 placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 w-64 transition-all"
                    oninput="buscarVehiculo(this.value)">
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="openKilometrajeModal()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-800 border border-zinc-700 rounded-xl text-sm font-medium text-gray-300 hover:bg-zinc-700 hover:border-zinc-600 hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                Registrar Km
            </button>
            <button onclick="openCreateVehiculoModal()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-xl text-sm font-medium text-white shadow-lg shadow-blue-600/20 hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Vehículo
            </button>
        </div>
    </div>

    <!-- Filtros Panel (collapsible) -->
    <div id="filtros-vehiculos-panel" class="hidden mb-5 p-4 bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50 animate-in">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Marca</label>
                <select id="filtro-marca" class="w-full rounded-lg bg-zinc-900 border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                    <option value="">Todas las marcas</option>
                    @foreach($rodados->pluck('marca')->unique()->sort() as $marca)
                        <option value="{{ $marca }}">{{ $marca }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Cliente</label>
                <select id="filtro-cliente" class="w-full rounded-lg bg-zinc-900 border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                    <option value="">Todos los clientes</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Tipo</label>
                <select id="filtro-tipo" class="w-full rounded-lg bg-zinc-900 border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                    <option value="">Todos los tipos</option>
                    @foreach($rodados->pluck('tipo_vehiculo')->unique()->sort() as $tipo)
                        <option value="{{ $tipo }}">{{ $tipo }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Propiedad</label>
                <select id="filtro-propiedad" class="w-full rounded-lg bg-zinc-900 border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                    <option value="">Todos</option>
                    <option value="propio">Propio</option>
                    @foreach($proveedores as $proveedor)
                        <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex justify-end mt-3">
            <button onclick="limpiarFiltrosVehiculos()" class="text-xs text-gray-500 hover:text-gray-300 transition">Limpiar filtros</button>
        </div>
    </div>

    <!-- Tabla de vehículos -->
    <div class="bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-zinc-700/50">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Vehículo</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tipo</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Cliente</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Kilometraje</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Proveedor</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Auxilios</th>
                        <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody id="vehiculos-table-body" class="divide-y divide-zinc-700/30">
                    @forelse($rodados as $rodado)
                    <tr class="vehiculo-row group hover:bg-zinc-700/30 transition-colors duration-150"
                        data-marca="{{ $rodado->marca }}"
                        data-tipo="{{ $rodado->tipo_vehiculo }}"
                        data-cliente="{{ $rodado->cliente_id }}"
                        data-proveedor="{{ $rodado->proveedor_id ?? 'propio' }}"
                        data-search="{{ strtolower(($rodado->patente ?? '') . ' ' . $rodado->marca . ' ' . $rodado->modelo . ' ' . ($rodado->cliente->nombre ?? '')) }}">

                        <!-- Vehículo -->
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-blue-600/20 to-indigo-600/20 border border-blue-500/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-100">{{ $rodado->marca }} {{ $rodado->modelo }}</div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        @if($rodado->patente)
                                            <span class="inline-flex items-center px-2 py-0.5 bg-zinc-700/80 rounded text-xs font-mono font-medium text-gray-300 tracking-wider">{{ $rodado->patente }}</span>
                                        @endif
                                        <span class="text-xs text-gray-500">{{ $rodado->año }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Tipo -->
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-zinc-700/60 text-gray-300 border border-zinc-600/30">
                                {{ $rodado->tipo_vehiculo }}
                            </span>
                        </td>

                        <!-- Cliente -->
                        <td class="px-5 py-4">
                            <span class="text-sm text-gray-300">{{ $rodado->cliente->nombre ?? 'N/A' }}</span>
                        </td>

                        <!-- Kilometraje -->
                        <td class="px-5 py-4">
                            @if($rodado->kilometrajeActual)
                                <div class="text-sm font-medium text-gray-200">{{ number_format($rodado->kilometrajeActual->kilometraje, 0, ',', '.') }} km</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $rodado->kilometrajeActual->fecha_registro->format('d/m/Y') }}</div>
                            @else
                                <span class="text-xs text-gray-600 italic">Sin registro</span>
                            @endif
                        </td>

                        <!-- Proveedor -->
                        <td class="px-5 py-4">
                            @if($rodado->proveedor)
                                <div class="flex items-center gap-1.5">
                                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                                    <span class="text-sm text-gray-300">{{ $rodado->proveedor->nombre }}</span>
                                </div>
                            @else
                                <div class="flex items-center gap-1.5">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                                    <span class="text-sm text-gray-400">Propio</span>
                                </div>
                            @endif
                        </td>

                        <!-- Auxilios -->
                        <td class="px-5 py-4">
                            @if($rodado->patente && isset($auxiliosMap[$rodado->patente]))
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $auxiliosMap[$rodado->patente] < 2 ? 'bg-red-500/10 text-red-400 border border-red-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/></svg>
                                    {{ $auxiliosMap[$rodado->patente] }}
                                </span>
                            @else
                                <span class="text-xs text-gray-600">--</span>
                            @endif
                        </td>

                        <!-- Acciones -->
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-1">
                                <button onclick="openEditVehiculoModal({{ $rodado->id }}, '{{ addslashes($rodado->marca) }}', '{{ addslashes($rodado->tipo_vehiculo) }}', '{{ addslashes($rodado->modelo) }}', {{ $rodado->año }}, {{ $rodado->cliente_id }}, {{ $rodado->proveedor_id ?? 'null' }}, {{ $rodado->es_propio ? 'true' : 'false' }}, '{{ $rodado->patente ?? '' }}')"
                                    class="p-2 rounded-lg text-gray-400 hover:text-blue-400 hover:bg-blue-500/10 transition-all duration-150"
                                    title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>
                                <form action="{{ route('rodados.destroy', $rodado) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 rounded-lg text-gray-400 hover:text-red-400 hover:bg-red-500/10 transition-all duration-150"
                                            title="Eliminar"
                                            onclick="return confirm('¿Está seguro de eliminar este vehículo? Esta acción no se puede deshacer.')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 rounded-full bg-zinc-700/30 flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                                    </svg>
                                </div>
                                <p class="text-gray-400 font-medium">No hay vehículos registrados</p>
                                <p class="text-gray-600 text-sm mt-1">Comienza agregando un nuevo vehículo</p>
                                <button onclick="openCreateVehiculoModal()" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-sm text-white transition">
                                    + Agregar Vehículo
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer con count -->
        @if($rodados->count() > 0)
        <div class="px-5 py-3 bg-zinc-900/30 border-t border-zinc-700/30 flex items-center justify-between">
            <span class="text-xs text-gray-500" id="vehiculos-count-text">Mostrando {{ $rodados->count() }} vehículos</span>
        </div>
        @endif
    </div>
</div>

<!-- Modales -->
@include('rodados.modals.vehiculo-modal')
@include('rodados.modals.kilometraje-modal')

<script>
    function toggleFiltrosVehiculos() {
        const panel = document.getElementById('filtros-vehiculos-panel');
        panel.classList.toggle('hidden');
    }

    function limpiarFiltrosVehiculos() {
        ['filtro-marca', 'filtro-tipo', 'filtro-cliente', 'filtro-propiedad'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });
        document.getElementById('busqueda-vehiculo').value = '';
        aplicarFiltros();
    }

    function buscarVehiculo(query) {
        const q = query.toLowerCase().trim();
        document.querySelectorAll('.vehiculo-row').forEach(row => {
            if (!q) { row.style.display = ''; return; }
            const searchData = row.dataset.search || '';
            row.style.display = searchData.includes(q) ? '' : 'none';
        });
        updateVehiculosCount();
    }

    document.addEventListener('DOMContentLoaded', function() {
        ['filtro-marca', 'filtro-tipo', 'filtro-cliente', 'filtro-propiedad'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', aplicarFiltros);
        });
    });

    function aplicarFiltros() {
        const marca = document.getElementById('filtro-marca').value.toLowerCase();
        const tipo = document.getElementById('filtro-tipo').value.toLowerCase();
        const cliente = document.getElementById('filtro-cliente').value;
        const propiedad = document.getElementById('filtro-propiedad').value;

        document.querySelectorAll('.vehiculo-row').forEach(fila => {
            const mostrar =
                (marca === '' || fila.dataset.marca.toLowerCase().includes(marca)) &&
                (tipo === '' || fila.dataset.tipo.toLowerCase().includes(tipo)) &&
                (cliente === '' || fila.dataset.cliente === cliente) &&
                (propiedad === '' || fila.dataset.proveedor === propiedad);
            fila.style.display = mostrar ? '' : 'none';
        });
        updateVehiculosCount();
    }

    function updateVehiculosCount() {
        const visible = document.querySelectorAll('.vehiculo-row:not([style*="display: none"])').length;
        const total = document.querySelectorAll('.vehiculo-row').length;
        const el = document.getElementById('vehiculos-count-text');
        if (el) el.textContent = visible === total ? `Mostrando ${total} vehículos` : `Mostrando ${visible} de ${total} vehículos`;
    }

    function openCreateVehiculoModal() {
        document.getElementById('vehiculo-form').reset();
        document.getElementById('vehiculo-form').action = '{{ route("rodados.store") }}';
        document.getElementById('vehiculo-modal-title').textContent = 'Nuevo Vehículo';
        document.getElementById('vehiculo-modal').classList.remove('hidden');
    }

    function openEditVehiculoModal(id, marca, tipo, modelo, año, clienteId, proveedorId, esPropio, patente) {
        document.getElementById('vehiculo-id').value = id;
        document.getElementById('vehiculo-marca').value = marca;
        document.getElementById('vehiculo-tipo').value = tipo;
        document.getElementById('vehiculo-modelo').value = modelo;
        document.getElementById('vehiculo-año').value = año;
        document.getElementById('vehiculo-cliente').value = clienteId;
        document.getElementById('vehiculo-proveedor').value = proveedorId || '';
        document.getElementById('vehiculo-patente').value = patente || '';

        const proveedorField = document.getElementById('vehiculo-proveedor').closest('div');
        if (proveedorField) proveedorField.style.display = 'block';

        document.getElementById('vehiculo-form').action = '{{ route("rodados.update", ":id") }}'.replace(':id', id);
        document.getElementById('vehiculo-modal-title').textContent = 'Editar Vehículo';
        document.getElementById('vehiculo-modal').classList.remove('hidden');
    }
</script>
