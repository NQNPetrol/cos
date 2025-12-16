<div>
    <!-- Header con botones -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-medium text-gray-200">Listado de Vehículos</h3>
        <div class="flex space-x-2">
            <button onclick="openKilometrajeModal()"
                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-green-700 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                Registrar Kilometraje
            </button>
            <button onclick="openCreateVehiculoModal()"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Vehículo
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Marca</label>
            <select id="filtro-marca" class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                <option value="">Todas</option>
                @foreach($rodados->pluck('marca')->unique() as $marca)
                    <option value="{{ $marca }}">{{ $marca }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Cliente</label>
            <select id="filtro-cliente" class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                <option value="">Todos</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Tipo</label>
            <select id="filtro-tipo" class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                <option value="">Todos</option>
                @foreach($rodados->pluck('tipo_vehiculo')->unique() as $tipo)
                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Propiedad</label>
            <select id="filtro-propiedad" class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                <option value="">Todos</option>
                <option value="propio">Propio</option>
                @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Tabla de vehículos -->
    <div class="overflow-x-auto rounded-lg border border-gray-700">
        <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Marca/Modelo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Año</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Kilometraje</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Proveedor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700" id="vehiculos-table-body">
                @forelse($rodados as $rodado)
                <tr class="vehiculo-row hover:bg-gray-750 transition-colors" 
                    data-marca="{{ $rodado->marca }}"
                    data-tipo="{{ $rodado->tipo_vehiculo }}"
                    data-cliente="{{ $rodado->cliente_id }}"
                    data-proveedor="{{ $rodado->proveedor_id ?? 'propio' }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-100">{{ $rodado->marca }} {{ $rodado->modelo }}</div>
                        @if($rodado->patente)
                            <div class="text-sm text-gray-400">Patente: {{ $rodado->patente }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                        {{ $rodado->tipo_vehiculo }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                        {{ $rodado->año }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                        {{ $rodado->cliente->nombre }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                        @if($rodado->kilometrajeActual)
                            <span class="font-medium">{{ number_format($rodado->kilometrajeActual->kilometraje, 0, ',', '.') }} km</span>
                            <div class="text-xs text-gray-400">{{ $rodado->kilometrajeActual->fecha_registro->format('d/m/Y') }}</div>
                        @else
                            <span class="text-gray-500">Sin registro</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                        @if($rodado->proveedor)
                            {{ $rodado->proveedor->nombre }}
                        @else
                            Vehículo Propio
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button onclick="openEditVehiculoModal({{ $rodado->id }}, '{{ addslashes($rodado->marca) }}', '{{ addslashes($rodado->tipo_vehiculo) }}', '{{ addslashes($rodado->modelo) }}', {{ $rodado->año }}, {{ $rodado->cliente_id }}, {{ $rodado->proveedor_id ?? 'null' }}, {{ $rodado->es_propio ? 'true' : 'false' }}, '{{ $rodado->patente ?? '' }}')"
                                class="text-blue-400 hover:text-blue-300 transition-colors p-1 rounded hover:bg-blue-900/30"
                                title="Editar vehículo">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                            <form action="{{ route('rodados.destroy', $rodado) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-400 hover:text-red-300 transition-colors p-1 rounded hover:bg-red-900/30"
                                        title="Eliminar vehículo"
                                        onclick="return confirm('¿Está seguro de eliminar este vehículo?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-400">
                        No hay vehículos registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modales -->
@include('rodados.modals.vehiculo-modal')
@include('rodados.modals.kilometraje-modal')

<script>
    // Filtros
    document.addEventListener('DOMContentLoaded', function() {
        const filtros = ['filtro-marca', 'filtro-tipo', 'filtro-cliente', 'filtro-propiedad'];
        
        filtros.forEach(filtroId => {
            const filtro = document.getElementById(filtroId);
            if (filtro) {
                filtro.addEventListener('change', aplicarFiltros);
            }
        });
    });

    function aplicarFiltros() {
        const marca = document.getElementById('filtro-marca').value.toLowerCase();
        const tipo = document.getElementById('filtro-tipo').value.toLowerCase();
        const cliente = document.getElementById('filtro-cliente').value;
        const propiedad = document.getElementById('filtro-propiedad').value;

        const filas = document.querySelectorAll('.vehiculo-row');
        
        filas.forEach(fila => {
            const filaMarca = fila.dataset.marca.toLowerCase();
            const filaTipo = fila.dataset.tipo.toLowerCase();
            const filaCliente = fila.dataset.cliente;
            const filaProveedor = fila.dataset.proveedor;

            const mostrar = 
                (marca === '' || filaMarca.includes(marca)) &&
                (tipo === '' || filaTipo.includes(tipo)) &&
                (cliente === '' || filaCliente === cliente) &&
                (propiedad === '' || filaProveedor === propiedad);

            fila.style.display = mostrar ? '' : 'none';
        });
    }

    function openCreateVehiculoModal() {
        // Resetear formulario
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
        
        // Asegurar que el campo proveedor esté visible (siempre visible ahora)
        const proveedorField = document.getElementById('vehiculo-proveedor').closest('div');
        if (proveedorField) {
            proveedorField.style.display = 'block';
        }
        
        document.getElementById('vehiculo-form').action = '{{ route("rodados.update", ":id") }}'.replace(':id', id);
        document.getElementById('vehiculo-modal-title').textContent = 'Editar Vehículo';
        document.getElementById('vehiculo-modal').classList.remove('hidden');
    }
</script>

