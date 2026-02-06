<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-zinc-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-100">Cobranzas</h2>
                            <p class="text-gray-400 mt-1">Gestión de cobros a clientes</p>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="document.getElementById('toggle-comparativa').classList.toggle('hidden')" class="inline-flex items-center px-4 py-2 bg-purple-600 rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                Cobrado vs Pagado
                            </button>
                            <button onclick="document.getElementById('modal-cobranza').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Nueva Cobranza
                            </button>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-800 border border-green-600 text-green-100 rounded-lg">{{ session('success') }}</div>
                    @endif

                    <!-- Comparativa toggle -->
                    <div id="toggle-comparativa" class="hidden mb-6">
                        <div class="bg-zinc-800 p-4 rounded-lg border border-zinc-700">
                            <h3 class="text-lg font-semibold text-gray-200 mb-3">Cobrado vs Pagado por Cliente</h3>
                            @if(count($comparativa) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-zinc-700">
                                    <thead class="bg-zinc-700">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-400 uppercase">Cliente</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-400 uppercase">Cobrado</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-400 uppercase">Pagado</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-400 uppercase">Diferencia</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-zinc-700">
                                        @foreach($comparativa as $item)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-200">{{ $item['cliente'] }}</td>
                                            <td class="px-4 py-2 text-sm text-green-400">${{ number_format($item['cobrado'], 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 text-sm text-red-400">${{ number_format($item['pagado'], 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 text-sm {{ $item['diferencia'] >= 0 ? 'text-green-400' : 'text-red-400' }}">${{ number_format($item['diferencia'], 2, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="text-gray-500 text-sm">Seleccione un cliente o active la comparativa con parámetros de filtro.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Filtros -->
                    <form method="GET" class="mb-6 flex gap-4 flex-wrap items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Cliente</label>
                            <select name="cliente_id" class="bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                                <option value="">Todos</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}>{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Estado</label>
                            <select name="estado" class="bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                                <option value="">Todos</option>
                                <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="cobrado" {{ request('estado') === 'cobrado' ? 'selected' : '' }}>Cobrado</option>
                                <option value="vencido" {{ request('estado') === 'vencido' ? 'selected' : '' }}>Vencido</option>
                            </select>
                        </div>
                        <input type="hidden" name="comparativa" value="1">
                        <button type="submit" class="px-4 py-2 bg-zinc-700 text-gray-200 text-sm rounded-md hover:bg-zinc-600 transition">Filtrar</button>
                    </form>

                    <!-- Tabs -->
                    <div class="flex space-x-1 mb-6 bg-zinc-800 rounded-lg p-1">
                        <button class="tab-btn active flex-1 px-4 py-2 text-sm font-medium rounded-md bg-blue-600 text-white" data-tab="pendientes">Pendientes ({{ $pendientes->count() }})</button>
                        <button class="tab-btn flex-1 px-4 py-2 text-sm font-medium rounded-md text-gray-400 hover:text-gray-200" data-tab="cobradas">Cobradas ({{ $cobradas->count() }})</button>
                    </div>

                    <!-- Pendientes Tab -->
                    <div id="tab-pendientes" class="tab-content">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-zinc-700">
                                <thead class="bg-zinc-800">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Cliente</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Concepto</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Cantidad</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Monto Total</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Vencimiento</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-700">
                                    @forelse($pendientes as $cobranza)
                                    <tr class="hover:bg-zinc-800 transition-colors">
                                        <td class="px-4 py-3 text-sm text-gray-200">{{ $cobranza->cliente->nombre ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-400">{{ $cobranza->concepto }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-400">{{ $cobranza->cantidad }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-200">${{ number_format($cobranza->monto_total, 2, ',', '.') }} {{ $cobranza->moneda }}</td>
                                        <td class="px-4 py-3 text-sm {{ $cobranza->fecha_vencimiento && $cobranza->fecha_vencimiento->isPast() ? 'text-red-400' : 'text-gray-400' }}">
                                            {{ $cobranza->fecha_vencimiento ? $cobranza->fecha_vencimiento->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <div class="flex items-center gap-2">
                                                <form action="{{ route('rodados.cobranzas.adjuntar', $cobranza) }}" method="POST" enctype="multipart/form-data" class="inline" id="adjuntar-form-{{ $cobranza->id }}">
                                                    @csrf
                                                    <input type="hidden" name="fecha_pago" value="{{ date('Y-m-d') }}">
                                                    <label class="cursor-pointer text-green-400 hover:text-green-300" title="Marcar como cobrado y adjuntar">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        <input type="file" name="comprobante" class="hidden" onchange="this.form.submit()">
                                                    </label>
                                                </form>
                                                <form action="{{ route('rodados.cobranzas.destroy', $cobranza) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-300"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No hay cobranzas pendientes.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Cobradas Tab -->
                    <div id="tab-cobradas" class="tab-content hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-zinc-700">
                                <thead class="bg-zinc-800">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Cliente</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Concepto</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Monto Total</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Fecha Pago</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Factura</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Comprobante</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-700">
                                    @forelse($cobradas as $cobranza)
                                    <tr class="hover:bg-zinc-800 transition-colors">
                                        <td class="px-4 py-3 text-sm text-gray-200">{{ $cobranza->cliente->nombre ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-400">{{ $cobranza->concepto }}</td>
                                        <td class="px-4 py-3 text-sm text-green-400">${{ number_format($cobranza->monto_total, 2, ',', '.') }} {{ $cobranza->moneda }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-400">{{ $cobranza->fecha_pago ? $cobranza->fecha_pago->format('d/m/Y') : '-' }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($cobranza->factura_path)
                                                <a href="{{ Storage::url($cobranza->factura_path) }}" target="_blank" class="text-blue-400 hover:text-blue-300">Ver</a>
                                            @else - @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($cobranza->comprobante_path)
                                                <a href="{{ Storage::url($cobranza->comprobante_path) }}" target="_blank" class="text-blue-400 hover:text-blue-300">Ver</a>
                                            @else - @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No hay cobranzas realizadas.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nueva Cobranza -->
    <div id="modal-cobranza" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-zinc-900 rounded-lg w-full max-w-md border border-zinc-700">
            <form method="POST" action="{{ route('rodados.cobranzas.store') }}">
                @csrf
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-100 mb-4">Nueva Cobranza</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Cliente *</label>
                            <select name="cliente_id" required class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                                <option value="">Seleccionar...</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Servicio</label>
                            <select name="servicio_usuario_id" id="servicio-select" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm" onchange="actualizarValor()">
                                <option value="">Personalizado</option>
                                @foreach($servicios as $servicio)
                                    <option value="{{ $servicio->id }}" data-tipo="{{ $servicio->tipo_calculo }}" data-valor="{{ $servicio->valor_unitario }}">{{ $servicio->nombre }} (${{ number_format($servicio->valor_unitario, 2) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Concepto *</label>
                            <input type="text" name="concepto" required class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Valor Unitario *</label>
                                <input type="number" name="valor_unitario" id="valor-unitario" step="0.01" required class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Cantidad *</label>
                                <input type="number" name="cantidad" id="cantidad" value="1" min="1" required class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Moneda</label>
                                <select name="moneda" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                                    <option value="ARS">ARS</option>
                                    <option value="USD">USD</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Fecha Emisión *</label>
                                <input type="date" name="fecha_emision" value="{{ date('Y-m-d') }}" required class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Fecha Vencimiento</label>
                            <input type="date" name="fecha_vencimiento" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Observaciones</label>
                            <textarea name="observaciones" rows="2" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm"></textarea>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 p-4 bg-zinc-800 rounded-b-lg">
                    <button type="button" onclick="document.getElementById('modal-cobranza').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-400 hover:text-gray-200 transition">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(b => { b.classList.remove('active', 'bg-blue-600', 'text-white'); b.classList.add('text-gray-400'); });
                this.classList.add('active', 'bg-blue-600', 'text-white'); this.classList.remove('text-gray-400');
                document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
                document.getElementById('tab-' + this.dataset.tab).classList.remove('hidden');
            });
        });

        function actualizarValor() {
            const select = document.getElementById('servicio-select');
            const option = select.options[select.selectedIndex];
            if (option.value) {
                document.getElementById('valor-unitario').value = option.dataset.valor;
            }
        }
    </script>
    @endpush
</x-app-layout>
