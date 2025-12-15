<div>
    <!-- Header con botón de nuevo pago -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-medium text-gray-200">Pagos de Servicios</h3>
        <button onclick="openCreatePagoModal()"
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo Pago
        </button>
    </div>

    <!-- Filtros -->
    <div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Vehículo</label>
            <select id="filtro-pago-vehiculo" class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2">
                <option value="">Todos</option>
                @foreach($rodados as $rodado)
                    <option value="{{ $rodado->id }}">{{ $rodado->marca }} {{ $rodado->modelo }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Pago</label>
            <select id="filtro-pago-tipo" class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2">
                <option value="">Todos</option>
                <option value="pago_patente">Pago Patente</option>
                <option value="pago_alquiler">Pago Alquiler</option>
                <option value="pago_proveedor">Pago Proveedor</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Año</label>
            <select id="filtro-pago-año" class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2">
                <option value="">Todos</option>
                @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Proveedor</label>
            <select id="filtro-pago-proveedor" class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2">
                <option value="">Todos</option>
                @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Tabla de pagos -->
    <div class="overflow-x-auto rounded-lg border border-gray-700">
        <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Vehículo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Mes/Año</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Monto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Fecha Pago</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Comprobante</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700">
                @forelse($pagos as $pago)
                <tr class="pago-row hover:bg-gray-750 transition-colors"
                    data-vehiculo="{{ $pago->rodado_id }}"
                    data-tipo="{{ $pago->tipo }}"
                    data-año="{{ $pago->año }}"
                    data-proveedor="{{ $pago->proveedor_id ?? '' }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($pago->tipo === 'pago_patente')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900/30 text-blue-400 border border-blue-800">
                                Patente
                            </span>
                        @elseif($pago->tipo === 'pago_alquiler')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900/30 text-green-400 border border-green-800">
                                Alquiler
                            </span>
                            @if($pago->monto_service)
                                <div class="text-xs text-gray-400 mt-1">+ Service: ${{ number_format($pago->monto_service, 2, ',', '.') }}</div>
                            @endif
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900/30 text-yellow-400 border border-yellow-800">
                                Proveedor
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                        {{ $pago->rodado->marca }} {{ $pago->rodado->modelo }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                        {{ \Carbon\Carbon::create()->month($pago->mes)->locale('es')->monthName }} / {{ $pago->año }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                        ${{ number_format($pago->monto, 2, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                        {{ $pago->fecha_pago->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($pago->comprobante_pago_path)
                            <a href="{{ asset('storage/' . $pago->comprobante_pago_path) }}" target="_blank"
                                class="text-blue-400 hover:text-blue-300">
                                Ver comprobante
                            </a>
                        @else
                            <span class="text-gray-500">Sin comprobante</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button onclick="openEditPagoModal({{ $pago->id }})"
                                class="text-blue-400 hover:text-blue-300 transition-colors p-1 rounded hover:bg-blue-900/30"
                                title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                            <form action="{{ route('rodados.pagos.destroy', ['pago' => $pago]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-400 hover:text-red-300 transition-colors p-1 rounded hover:bg-red-900/30"
                                        onclick="return confirm('¿Está seguro de eliminar este pago?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-400">
                        No hay pagos registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de pagos -->
@include('rodados.modals.pago-modal')

<script>
    function openCreatePagoModal() {
        document.getElementById('pago-form').reset();
        document.getElementById('pago-form').action = '{{ route("rodados.pagos.store") }}';
        document.getElementById('pago-id').value = '';
        document.getElementById('pago-modal-title').textContent = 'Nuevo Pago';
        togglePagoFields();
        document.getElementById('pago-modal').classList.remove('hidden');
    }

    function openEditPagoModal(id) {
        // Cargar datos del pago vía AJAX o pasar datos desde el servidor
        // Por ahora, solo abrir el modal
        document.getElementById('pago-id').value = id;
        document.getElementById('pago-form').action = '{{ route("rodados.pagos.update", ":id") }}'.replace(':id', id);
        document.getElementById('pago-modal-title').textContent = 'Editar Pago';
        document.getElementById('pago-modal').classList.remove('hidden');
        togglePagoFields();
    }
</script>

