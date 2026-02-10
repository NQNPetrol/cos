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
                        <div class="overflow-x-auto rounded-lg border border-zinc-700/50">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-zinc-700/50">
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tipo</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Vehículo</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Detalle</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Monto</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Vencimiento</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Factura</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-700/30">
                                    @forelse($pagosPendientes as $pago)
                                    @php
                                        $isVencido = $pago->fecha_vencimiento && $pago->fecha_vencimiento->isPast();
                                        $isTurno = in_array($pago->tipo, ['pago_service', 'pago_taller']);
                                    @endphp
                                    <tr class="hover:bg-zinc-800/50 transition-colors">
                                        <td class="px-4 py-3">
                                            @if($pago->tipo === 'pago_service')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-blue-400"></div>
                                                    Service
                                                </span>
                                            @elseif($pago->tipo === 'pago_taller')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-amber-400"></div>
                                                    Taller
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-zinc-600/30 text-gray-400 border border-zinc-600/30">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-gray-400"></div>
                                                    {{ ucfirst(str_replace(['pago_', '_'], ['', ' '], $pago->tipo)) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-medium text-gray-200">{{ $pago->rodado->patente ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $pago->rodado->marca ?? '' }} {{ $pago->rodado->modelo ?? '' }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm text-gray-300">
                                                @if($pago->servicioUsuario)
                                                    {{ $pago->servicioUsuario->nombre }}
                                                @elseif($pago->turnoRodado)
                                                    {{ $pago->turnoRodado->taller->nombre ?? 'Sin taller' }}
                                                    <div class="text-xs text-gray-500">Turno {{ $pago->turnoRodado->fecha_hora ? $pago->turnoRodado->fecha_hora->format('d/m/Y') : '' }}</div>
                                                @else
                                                    {{ $pago->observaciones ?? '-' }}
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-medium text-gray-200">
                                                @if($pago->moneda === 'USD') USD @endif${{ number_format($pago->monto, 2, ',', '.') }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $pago->moneda }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($pago->fecha_vencimiento)
                                                <div class="text-sm {{ $isVencido ? 'text-red-400 font-semibold' : 'text-gray-400' }}">
                                                    {{ $pago->fecha_vencimiento->format('d/m/Y') }}
                                                </div>
                                                @if($isVencido)
                                                    <span class="text-[10px] text-red-500 font-medium">VENCIDO</span>
                                                @else
                                                    <span class="text-[10px] text-gray-600">{{ now()->diffInDays($pago->fecha_vencimiento) }} días</span>
                                                @endif
                                            @else
                                                <span class="text-sm text-gray-600">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($pago->factura_path)
                                                <a href="{{ asset('storage/' . $pago->factura_path) }}" target="_blank"
                                                    class="inline-flex items-center gap-1 text-xs text-blue-400 hover:text-blue-300">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    Ver
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-600">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-end gap-1">
                                                {{-- Adjuntar comprobante de pago --}}
                                                <form action="{{ route('rodados.pagos.adjuntar-factura', $pago) }}" method="POST" enctype="multipart/form-data" class="inline" id="comprobante-form-{{ $pago->id }}">
                                                    @csrf
                                                    <label class="p-2 rounded-lg text-gray-400 hover:text-emerald-400 hover:bg-emerald-500/10 transition-all cursor-pointer inline-flex" title="Adjuntar comprobante de pago">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                                        <input type="file" name="comprobante_pago" class="hidden" accept=".pdf,.jpg,.jpeg,.png"
                                                            onchange="if(confirm('¿Adjuntar comprobante y marcar como pagado?')) this.form.submit();">
                                                    </label>
                                                </form>
                                                {{-- Eliminar --}}
                                                @if(!$pago->turno_rodado_id)
                                                <form action="{{ route('rodados.pagos.destroy', $pago) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este pago?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-red-400 hover:bg-red-500/10 transition-all" title="Eliminar">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-12 text-center">
                                            <div class="flex flex-col items-center gap-2 text-gray-500">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <p class="text-sm">No hay pagos pendientes</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagos Realizados -->
                    <div id="tab-realizados" class="tab-content hidden">
                        <div class="overflow-x-auto rounded-lg border border-zinc-700/50">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-zinc-700/50">
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tipo</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Vehículo</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Detalle</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Monto</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Fecha Pago</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Comprobante</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Factura</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-700/30">
                                    @forelse($pagosRealizados as $pago)
                                    <tr class="hover:bg-zinc-800/50 transition-colors">
                                        <td class="px-4 py-3">
                                            @if($pago->tipo === 'pago_service')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-blue-400"></div>
                                                    Service
                                                </span>
                                            @elseif($pago->tipo === 'pago_taller')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-amber-400"></div>
                                                    Taller
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                                                    {{ ucfirst(str_replace(['pago_', '_'], ['', ' '], $pago->tipo)) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-medium text-gray-200">{{ $pago->rodado->patente ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $pago->rodado->marca ?? '' }} {{ $pago->rodado->modelo ?? '' }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm text-gray-300">
                                                @if($pago->servicioUsuario)
                                                    {{ $pago->servicioUsuario->nombre }}
                                                @elseif($pago->turnoRodado)
                                                    {{ $pago->turnoRodado->taller->nombre ?? 'Sin taller' }}
                                                    <div class="text-xs text-gray-500">Turno {{ $pago->turnoRodado->fecha_hora ? $pago->turnoRodado->fecha_hora->format('d/m/Y') : '' }}</div>
                                                @else
                                                    {{ $pago->observaciones ?? '-' }}
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-medium text-emerald-400">
                                                @if($pago->moneda === 'USD') USD @endif${{ number_format($pago->monto, 2, ',', '.') }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $pago->moneda }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-400">
                                            {{ $pago->fecha_pago ? $pago->fecha_pago->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($pago->comprobante_pago_path)
                                                <a href="{{ asset('storage/' . $pago->comprobante_pago_path) }}" target="_blank"
                                                    class="inline-flex items-center gap-1 text-xs text-emerald-400 hover:text-emerald-300">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    Ver
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-600">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($pago->factura_path)
                                                <a href="{{ asset('storage/' . $pago->factura_path) }}" target="_blank"
                                                    class="inline-flex items-center gap-1 text-xs text-blue-400 hover:text-blue-300">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    Ver
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-600">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-12 text-center">
                                            <div class="flex flex-col items-center gap-2 text-gray-500">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                                                <p class="text-sm">No hay pagos realizados</p>
                                            </div>
                                        </td>
                                    </tr>
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
                                <option value="pago_patente">Patente</option>
                                <option value="pago_alquiler">Alquiler</option>
                                <option value="pago_seguro">Seguro</option>
                                <option value="pago_vtv">VTV</option>
                                <option value="pago_servicio_starlink">Servicio Starlink</option>
                                <option value="pagos_adicionales">Adicionales</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Monto *</label>
                            <input type="number" name="monto" step="0.01" required class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Fecha Vencimiento</label>
                            <input type="date" name="fecha_vencimiento" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
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
