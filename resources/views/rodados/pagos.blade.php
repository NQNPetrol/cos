<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-zinc-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-100">Pagos de Servicios</h2>
                            <p class="text-gray-400 mt-1">Gestión de pagos realizados y por realizar</p>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="document.getElementById('modal-servicios').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-purple-600 rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0"/></svg>
                                Servicios
                            </button>
                            <button onclick="document.getElementById('modal-nuevo-pago').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Nuevo Pago
                            </button>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-800 border border-green-600 text-green-100 rounded-lg">{{ session('success') }}</div>
                    @endif

                    <!-- Tabs -->
                    <div class="flex space-x-1 mb-6 bg-zinc-800 rounded-lg p-1">
                        <button class="tab-btn active flex-1 px-4 py-2 text-sm font-medium rounded-md bg-blue-600 text-white" data-tab="pendientes">Pagos por Realizar ({{ $pagosPendientes->count() }})</button>
                        <button class="tab-btn flex-1 px-4 py-2 text-sm font-medium rounded-md text-gray-400 hover:text-gray-200" data-tab="realizados">Pagos Realizados ({{ $pagosRealizados->count() }})</button>
                    </div>

                    <!-- Pagos Pendientes -->
                    <div id="tab-pendientes" class="tab-content">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-zinc-700">
                                <thead class="bg-zinc-800">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Vehículo</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Servicio</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Monto</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Vencimiento</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Estado</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-700">
                                    @forelse($pagosPendientes as $pago)
                                    <tr class="hover:bg-zinc-800 transition-colors">
                                        <td class="px-4 py-3 text-sm text-gray-200">{{ $pago->rodado->patente ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-400">{{ $pago->servicioUsuario->nombre ?? $pago->tipo }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-200">${{ number_format($pago->monto, 2, ',', '.') }} {{ $pago->moneda }}</td>
                                        <td class="px-4 py-3 text-sm {{ $pago->fecha_vencimiento && $pago->fecha_vencimiento->isPast() ? 'text-red-400' : 'text-gray-400' }}">
                                            {{ $pago->fecha_vencimiento ? $pago->fecha_vencimiento->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $pago->estado === 'vencido' ? 'bg-red-900 text-red-300' : 'bg-yellow-900 text-yellow-300' }}">
                                                {{ ucfirst($pago->estado) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <div class="flex items-center gap-2">
                                                <form action="{{ route('rodados.pagos.adjuntar-factura', $pago) }}" method="POST" enctype="multipart/form-data" class="inline">
                                                    @csrf
                                                    <label class="cursor-pointer text-blue-400 hover:text-blue-300" title="Adjuntar comprobante">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                                        <input type="file" name="factura" class="hidden" onchange="this.form.submit()">
                                                    </label>
                                                </form>
                                                <form action="{{ route('rodados.pagos.destroy', $pago) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este pago?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-300"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No hay pagos pendientes.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagos Realizados -->
                    <div id="tab-realizados" class="tab-content hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-zinc-700">
                                <thead class="bg-zinc-800">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Vehículo</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Servicio</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Monto</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Fecha Pago</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Comprobante</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-700">
                                    @forelse($pagosRealizados as $pago)
                                    <tr class="hover:bg-zinc-800 transition-colors">
                                        <td class="px-4 py-3 text-sm text-gray-200">{{ $pago->rodado->patente ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-400">{{ $pago->servicioUsuario->nombre ?? $pago->tipo }}</td>
                                        <td class="px-4 py-3 text-sm text-green-400">${{ number_format($pago->monto, 2, ',', '.') }} {{ $pago->moneda }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-400">{{ $pago->fecha_pago ? $pago->fecha_pago->format('d/m/Y') : '-' }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($pago->comprobante_pago_path)
                                                <a href="{{ Storage::url($pago->comprobante_pago_path) }}" target="_blank" class="text-blue-400 hover:text-blue-300 inline-flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    Ver
                                                </a>
                                            @else
                                                <span class="text-gray-500">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">No hay pagos realizados.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Servicios CRUD -->
    <div id="modal-servicios" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-zinc-900 rounded-lg w-full max-w-lg border border-zinc-700 max-h-[80vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-100">Gestión de Servicios</h3>
                    <button onclick="document.getElementById('modal-servicios').classList.add('hidden')" class="text-gray-400 hover:text-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Create Service Form -->
                <form action="{{ route('rodados.servicios-usuario.store') }}" method="POST" class="mb-6 p-4 bg-zinc-800 rounded-lg">
                    @csrf
                    <h4 class="text-sm font-medium text-gray-300 mb-3">Nuevo Servicio</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="col-span-2">
                            <input type="text" name="nombre" placeholder="Nombre del servicio" required class="w-full bg-zinc-700 border-zinc-600 rounded-md text-gray-200 text-sm">
                        </div>
                        <div>
                            <select name="tipo_calculo" required class="w-full bg-zinc-700 border-zinc-600 rounded-md text-gray-200 text-sm">
                                <option value="fijo">Fijo</option>
                                <option value="variable">Variable (por cantidad)</option>
                            </select>
                        </div>
                        <div>
                            <input type="number" name="valor_unitario" step="0.01" placeholder="Valor" class="w-full bg-zinc-700 border-zinc-600 rounded-md text-gray-200 text-sm">
                        </div>
                        <div>
                            <select name="moneda" class="w-full bg-zinc-700 border-zinc-600 rounded-md text-gray-200 text-sm">
                                <option value="ARS">ARS</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="w-full px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition">Crear</button>
                        </div>
                    </div>
                </form>

                <!-- Existing Services List -->
                <div class="space-y-2">
                    @foreach($servicios as $servicio)
                    <div class="flex items-center justify-between p-3 bg-zinc-800 rounded-lg">
                        <div>
                            <div class="font-medium text-gray-200 text-sm">{{ $servicio->nombre }}</div>
                            <div class="text-xs text-gray-400">{{ ucfirst($servicio->tipo_calculo) }} - ${{ number_format($servicio->valor_unitario, 2) }} {{ $servicio->moneda }}</div>
                        </div>
                        <form action="{{ route('rodados.servicios-usuario.destroy', $servicio) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Pago -->
    <div id="modal-nuevo-pago" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-zinc-900 rounded-lg w-full max-w-md border border-zinc-700">
            <form method="POST" action="{{ route('rodados.pagos.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-100 mb-4">Registrar Pago</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Vehículo *</label>
                            <select name="rodado_id" required class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                                <option value="">Seleccionar...</option>
                                @foreach($rodados as $rodado)
                                    <option value="{{ $rodado->id }}">{{ $rodado->patente ?? $rodado->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Tipo</label>
                            <select name="tipo" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                                <option value="pago_service">Pago Service</option>
                                <option value="pago_taller">Pago Taller</option>
                                <option value="patente">Patente</option>
                                <option value="alquiler">Alquiler</option>
                                <option value="seguro">Seguro</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Monto *</label>
                            <input type="number" name="monto" step="0.01" required class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Fecha Pago</label>
                            <input type="date" name="fecha_pago" value="{{ date('Y-m-d') }}" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Moneda</label>
                            <select name="moneda" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                                <option value="ARS">ARS</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Comprobante</label>
                            <input type="file" name="comprobante_pago" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 p-4 bg-zinc-800 rounded-b-lg">
                    <button type="button" onclick="document.getElementById('modal-nuevo-pago').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-400 hover:text-gray-200 transition">Cancelar</button>
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
    </script>
    @endpush
</x-app-layout>
