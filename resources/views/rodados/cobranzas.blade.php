<x-administrative-layout>
    <div class="py-6">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Toast Notifications -->
            @if(session('success'))
                <div id="toast-success" class="fixed z-[100] max-w-sm w-full toast-enter" style="top: calc(var(--fb-topbar-height, 60px) + 1rem); right: 1.5rem;">
                    <div class="bg-zinc-900 border border-green-500/30 rounded-2xl shadow-2xl shadow-green-900/20 p-4 flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-green-400">Operacion exitosa</p>
                            <p class="text-sm text-gray-300 mt-0.5">{{ session('success') }}</p>
                        </div>
                        <button onclick="dismissToast('toast-success')" class="flex-shrink-0 p-1 text-gray-500 hover:text-gray-300 rounded-lg hover:bg-zinc-800 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="mt-1 mx-4 h-0.5 bg-zinc-800 rounded-full overflow-hidden">
                        <div class="h-full bg-green-500 rounded-full toast-progress"></div>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div id="toast-error" class="fixed z-[100] max-w-sm w-full toast-enter" style="top: calc(var(--fb-topbar-height, 60px) + 1rem); right: 1.5rem;">
                    <div class="bg-zinc-900 border border-red-500/30 rounded-2xl shadow-2xl shadow-red-900/20 p-4 flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-red-400">Error</p>
                            <p class="text-sm text-gray-300 mt-0.5">{{ session('error') }}</p>
                        </div>
                        <button onclick="dismissToast('toast-error')" class="flex-shrink-0 p-1 text-gray-500 hover:text-gray-300 rounded-lg hover:bg-zinc-800 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="mt-1 mx-4 h-0.5 bg-zinc-800 rounded-full overflow-hidden">
                        <div class="h-full bg-red-500 rounded-full toast-progress"></div>
                    </div>
                </div>
            @endif

            @php
                $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
                $mesNombre = $meses[$mes - 1] ?? '';
            @endphp

            <!-- Header Principal -->
            <div class="mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-gradient-to-br from-emerald-600/20 to-emerald-400/10 rounded-xl border border-emerald-500/20">
                            <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-100 tracking-tight">Cobranzas</h1>
                            <p class="text-sm text-gray-400 mt-0.5">Gestion de cobros a clientes e ingresos vs egresos</p>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="flex flex-wrap gap-3">
                        <div class="flex items-center gap-2 px-4 py-2 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                            <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                            <span class="text-xs text-gray-400">Pendientes</span>
                            <span class="text-sm font-semibold text-gray-200">{{ $pendientes->count() }}</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                            <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                            <span class="text-xs text-gray-400">Cobradas</span>
                            <span class="text-sm font-semibold text-gray-200">{{ $cobradas->count() }}</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                            <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                            <span class="text-xs text-gray-400">Total</span>
                            <span class="text-sm font-semibold text-gray-200">{{ $cobranzas->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==================== PERIOD SELECTOR + FILTERS ==================== -->
            <form method="GET" id="filter-form" class="mb-5">
                <div class="flex flex-col sm:flex-row sm:items-end gap-3">
                    <!-- Period Navigation -->
                    <div class="flex items-center gap-2">
                        @php
                            $prevMes = $mes - 1;
                            $prevAnio = $anio;
                            if ($prevMes < 1) { $prevMes = 12; $prevAnio--; }
                            $nextMes = $mes + 1;
                            $nextAnio = $anio;
                            if ($nextMes > 12) { $nextMes = 1; $nextAnio++; }
                        @endphp
                        <a href="{{ route('rodados.cobranzas.index', ['mes' => $prevMes, 'anio' => $prevAnio, 'cliente_id' => request('cliente_id'), 'estado' => request('estado')]) }}"
                           class="p-2 bg-zinc-800 border border-zinc-700 rounded-xl text-gray-400 hover:text-gray-200 hover:bg-zinc-700 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                        <div class="flex items-center gap-2">
                            <select name="mes" class="bg-zinc-800 border border-zinc-700 rounded-xl text-gray-200 text-sm px-3 py-2 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30" onchange="document.getElementById('filter-form').submit()">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>{{ $meses[$m - 1] }}</option>
                                @endfor
                            </select>
                            <select name="anio" class="bg-zinc-800 border border-zinc-700 rounded-xl text-gray-200 text-sm px-3 py-2 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30" onchange="document.getElementById('filter-form').submit()">
                                @for($a = now()->year - 2; $a <= now()->year + 1; $a++)
                                    <option value="{{ $a }}" {{ $anio == $a ? 'selected' : '' }}>{{ $a }}</option>
                                @endfor
                            </select>
                        </div>
                        <a href="{{ route('rodados.cobranzas.index', ['mes' => $nextMes, 'anio' => $nextAnio, 'cliente_id' => request('cliente_id'), 'estado' => request('estado')]) }}"
                           class="p-2 bg-zinc-800 border border-zinc-700 rounded-xl text-gray-400 hover:text-gray-200 hover:bg-zinc-700 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <!-- Client filter -->
                    <div>
                        <select name="cliente_id" class="bg-zinc-800 border border-zinc-700 rounded-xl text-gray-200 text-sm px-3 py-2 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30" onchange="document.getElementById('filter-form').submit()">
                            <option value="">Todos los clientes</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}>{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Estado filter -->
                    <div>
                        <select name="estado" class="bg-zinc-800 border border-zinc-700 rounded-xl text-gray-200 text-sm px-3 py-2 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30" onchange="document.getElementById('filter-form').submit()">
                            <option value="">Todos los estados</option>
                            <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="cobrado" {{ request('estado') === 'cobrado' ? 'selected' : '' }}>Cobrado</option>
                            <option value="vencido" {{ request('estado') === 'vencido' ? 'selected' : '' }}>Vencido</option>
                        </select>
                    </div>

                    <!-- Spacer -->
                    <div class="flex-1"></div>

                    <!-- Nueva Cobranza Button -->
                    <button type="button" onclick="abrirNuevaCobranza()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-emerald-600/20 hover:shadow-emerald-500/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Nueva Cobranza
                    </button>
                </div>
            </form>

            <!-- ==================== RESUMEN DEL PERIODO ==================== -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Ingresos cobrados -->
                <div class="p-4 bg-zinc-800/60 rounded-xl border border-zinc-700/50">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
                        </div>
                        <span class="text-xs text-gray-400 uppercase tracking-wider font-medium">Ingresos</span>
                    </div>
                    <p class="text-xl font-bold text-emerald-400">${{ number_format($totalIngresos, 2, ',', '.') }}</p>
                    <p class="text-[10px] text-gray-500 mt-1">Cobrado en {{ $mesNombre }} {{ $anio }}</p>
                </div>

                <!-- Pendiente de cobro -->
                <div class="p-4 bg-zinc-800/60 rounded-xl border border-zinc-700/50">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 rounded-lg bg-amber-500/10 border border-amber-500/20 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-xs text-gray-400 uppercase tracking-wider font-medium">Pendiente</span>
                    </div>
                    <p class="text-xl font-bold text-amber-400">${{ number_format($totalPendiente, 2, ',', '.') }}</p>
                    <p class="text-[10px] text-gray-500 mt-1">Por cobrar en {{ $mesNombre }}</p>
                </div>

                <!-- Egresos -->
                <div class="p-4 bg-zinc-800/60 rounded-xl border border-zinc-700/50">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 rounded-lg bg-red-500/10 border border-red-500/20 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6L9 12.75l4.286-4.286a11.948 11.948 0 014.306 6.43l.776 2.898m0 0l3.182-5.511m-3.182 5.51l-5.511-3.181"/></svg>
                        </div>
                        <span class="text-xs text-gray-400 uppercase tracking-wider font-medium">Egresos</span>
                    </div>
                    <p class="text-xl font-bold text-red-400">${{ number_format($totalEgresos, 2, ',', '.') }}</p>
                    <p class="text-[10px] text-gray-500 mt-1">Pagado en {{ $mesNombre }} {{ $anio }}</p>
                </div>

                <!-- Balance -->
                <div class="p-4 bg-zinc-800/60 rounded-xl border {{ $diferencia >= 0 ? 'border-emerald-500/30' : 'border-red-500/30' }}">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 rounded-lg {{ $diferencia >= 0 ? 'bg-emerald-500/10 border-emerald-500/20' : 'bg-red-500/10 border-red-500/20' }} border flex items-center justify-center">
                            <svg class="w-4.5 h-4.5 {{ $diferencia >= 0 ? 'text-emerald-400' : 'text-red-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                        </div>
                        <span class="text-xs text-gray-400 uppercase tracking-wider font-medium">Balance</span>
                    </div>
                    <p class="text-xl font-bold {{ $diferencia >= 0 ? 'text-emerald-400' : 'text-red-400' }}">${{ number_format(abs($diferencia), 2, ',', '.') }}</p>
                    <p class="text-[10px] text-gray-500 mt-1">{{ $diferencia >= 0 ? 'Ganancia neta' : 'Deficit' }} del periodo</p>
                </div>
            </div>

            <!-- ==================== COMPARATIVA POR CLIENTE ==================== -->
            @if(count($comparativa) > 0)
            <div class="mb-6">
                <button onclick="document.getElementById('comparativa-panel').classList.toggle('hidden')" class="flex items-center gap-2 text-sm text-gray-400 hover:text-gray-200 transition-colors mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Detalle por cliente ({{ count($comparativa) }})
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="comparativa-panel" class="hidden">
                    <div class="bg-zinc-800/60 rounded-xl border border-zinc-700/50 overflow-hidden">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-zinc-700/50">
                                    <th class="px-4 py-3 text-left text-[11px] font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <th class="px-4 py-3 text-right text-[11px] font-medium text-gray-500 uppercase tracking-wider">Cobrado</th>
                                    <th class="px-4 py-3 text-right text-[11px] font-medium text-gray-500 uppercase tracking-wider">Pagado</th>
                                    <th class="px-4 py-3 text-right text-[11px] font-medium text-gray-500 uppercase tracking-wider">Diferencia</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-700/30">
                                @foreach($comparativa as $item)
                                <tr class="hover:bg-zinc-700/20 transition-colors">
                                    <td class="px-4 py-2.5 text-sm text-gray-200 font-medium">{{ $item['cliente'] }}</td>
                                    <td class="px-4 py-2.5 text-sm text-emerald-400 text-right">${{ number_format($item['cobrado'], 2, ',', '.') }}</td>
                                    <td class="px-4 py-2.5 text-sm text-red-400 text-right">${{ number_format($item['pagado'], 2, ',', '.') }}</td>
                                    <td class="px-4 py-2.5 text-sm text-right font-semibold {{ $item['diferencia'] >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                        {{ $item['diferencia'] >= 0 ? '+' : '-' }}${{ number_format(abs($item['diferencia']), 2, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- ==================== TABS ==================== -->
            <div class="flex items-center gap-2 mb-5">
                <button onclick="switchTab('pendientes')" id="tab-btn-pendientes"
                    class="cobranza-tab group relative px-5 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 bg-emerald-600 text-white shadow-lg shadow-emerald-600/20">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Pendientes
                        <span class="text-xs opacity-75 bg-white/20 px-1.5 py-0.5 rounded-md">{{ $pendientes->count() }}</span>
                    </span>
                </button>
                <button onclick="switchTab('cobradas')" id="tab-btn-cobradas"
                    class="cobranza-tab group relative px-5 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 bg-zinc-800 text-gray-400 hover:text-gray-200 hover:bg-zinc-700 border border-zinc-700/50">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Cobradas
                        <span class="text-xs opacity-60 bg-zinc-700 px-1.5 py-0.5 rounded-md">{{ $cobradas->count() }}</span>
                    </span>
                </button>
            </div>

            <!-- ==================== PENDIENTES TAB ==================== -->
            <div id="tab-pendientes" class="tab-panel">
                <div class="space-y-2">
                    @forelse($pendientes as $cobranza)
                    <div class="group p-4 bg-zinc-800/40 hover:bg-zinc-800/70 rounded-xl border border-zinc-700/40 hover:border-zinc-600/50 transition-all">
                        <div class="flex items-center gap-4">
                            <!-- Icon -->
                            <div class="flex-shrink-0 w-10 h-10 rounded-xl {{ $cobranza->fecha_vencimiento && $cobranza->fecha_vencimiento->isPast() ? 'bg-red-500/10 border-red-500/20' : 'bg-amber-500/10 border-amber-500/20' }} border flex items-center justify-center">
                                @if($cobranza->fecha_vencimiento && $cobranza->fecha_vencimiento->isPast())
                                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                                @else
                                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </div>

                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-sm font-semibold text-gray-100">{{ $cobranza->concepto }}</h3>
                                    <span class="text-[10px] px-2 py-0.5 rounded-lg bg-zinc-700/50 text-gray-400 border border-zinc-600/30">{{ $cobranza->cliente->nombre ?? 'N/A' }}</span>
                                    @if($cobranza->fecha_vencimiento && $cobranza->fecha_vencimiento->isPast())
                                        <span class="text-[10px] px-2 py-0.5 rounded-lg bg-red-500/10 text-red-400 border border-red-500/20">Vencido</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                                    <span>{{ $cobranza->cantidad }} x ${{ number_format($cobranza->valor_unitario, 2, ',', '.') }} {{ $cobranza->moneda }}</span>
                                    @if($cobranza->fecha_vencimiento)
                                        <span>Venc: {{ $cobranza->fecha_vencimiento->format('d/m/Y') }}</span>
                                    @endif
                                    @if($cobranza->servicioUsuario)
                                        <span class="text-blue-400">{{ $cobranza->servicioUsuario->nombre }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Monto -->
                            <div class="text-right flex-shrink-0">
                                <p class="text-sm font-bold text-gray-100">${{ number_format($cobranza->monto_total, 2, ',', '.') }}</p>
                                <p class="text-[10px] text-gray-500">{{ $cobranza->moneda }}</p>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-1 flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="abrirCobrar({{ $cobranza->id }})" class="p-2 text-emerald-400 hover:bg-emerald-500/10 rounded-lg transition-all" title="Marcar como cobrado">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </button>
                                <form action="{{ route('rodados.cobranzas.destroy', $cobranza) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta cobranza?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-400 hover:bg-red-500/10 rounded-lg transition-all" title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <svg class="w-12 h-12 text-gray-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-gray-500 text-sm">No hay cobranzas pendientes en {{ $mesNombre }} {{ $anio }}</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- ==================== COBRADAS TAB ==================== -->
            <div id="tab-cobradas" class="tab-panel hidden">
                <div class="space-y-2">
                    @forelse($cobradas as $cobranza)
                    <div class="group p-4 bg-zinc-800/40 hover:bg-zinc-800/70 rounded-xl border border-zinc-700/40 hover:border-zinc-600/50 transition-all">
                        <div class="flex items-center gap-4">
                            <!-- Icon -->
                            <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>

                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-sm font-semibold text-gray-100">{{ $cobranza->concepto }}</h3>
                                    <span class="text-[10px] px-2 py-0.5 rounded-lg bg-zinc-700/50 text-gray-400 border border-zinc-600/30">{{ $cobranza->cliente->nombre ?? 'N/A' }}</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Cobrado</span>
                                </div>
                                <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                                    <span>Pagado: {{ $cobranza->fecha_pago ? $cobranza->fecha_pago->format('d/m/Y') : '-' }}</span>
                                    @if($cobranza->servicioUsuario)
                                        <span class="text-blue-400">{{ $cobranza->servicioUsuario->nombre }}</span>
                                    @endif
                                    @if($cobranza->factura_path)
                                        <a href="{{ Storage::url($cobranza->factura_path) }}" target="_blank" class="text-blue-400 hover:text-blue-300 inline-flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            Factura
                                        </a>
                                    @endif
                                    @if($cobranza->comprobante_path)
                                        <a href="{{ Storage::url($cobranza->comprobante_path) }}" target="_blank" class="text-blue-400 hover:text-blue-300 inline-flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            Comprobante
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Monto -->
                            <div class="text-right flex-shrink-0">
                                <p class="text-sm font-bold text-emerald-400">${{ number_format($cobranza->monto_total, 2, ',', '.') }}</p>
                                <p class="text-[10px] text-gray-500">{{ $cobranza->moneda }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <svg class="w-12 h-12 text-gray-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-gray-500 text-sm">No hay cobranzas cobradas en {{ $mesNombre }} {{ $anio }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL: NUEVA COBRANZA ==================== -->
    <div id="modal-cobranza" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-zinc-900 rounded-2xl w-full max-w-lg border border-zinc-700/50 shadow-2xl flex flex-col max-h-[90vh]">
            <form method="POST" action="{{ route('rodados.cobranzas.store') }}" id="form-cobranza">
                @csrf
                <input type="hidden" name="mes" value="{{ $mes }}">
                <input type="hidden" name="anio" value="{{ $anio }}">

                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-600/10 rounded-lg">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-100">Nueva Cobranza</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Registrar un nuevo cobro a cliente</p>
                        </div>
                    </div>
                    <button type="button" onclick="cerrarModal()" class="p-2 text-gray-500 hover:text-gray-300 hover:bg-zinc-800 rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="px-6 py-5 overflow-y-auto flex-1 space-y-4 modal-scroll">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Cliente *</label>
                        <select name="cliente_id" required class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all">
                            <option value="">Seleccionar cliente...</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Servicio <span class="text-gray-600 normal-case">(opcional)</span></label>
                        <select name="servicio_usuario_id" id="servicio-select" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all" onchange="actualizarValor()">
                            <option value="">Personalizado</option>
                            @foreach($servicios as $servicio)
                                <option value="{{ $servicio->id }}" data-valor="{{ $servicio->valor_unitario }}" data-nombre="{{ $servicio->nombre }}">{{ $servicio->nombre }} (${{ number_format($servicio->valor_unitario, 2) }} {{ $servicio->moneda }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Concepto *</label>
                        <input type="text" name="concepto" id="input-concepto" required class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all" placeholder="Descripcion del cobro">
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Valor Unit. *</label>
                            <input type="number" name="valor_unitario" id="valor-unitario" step="0.01" required class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Cantidad *</label>
                            <input type="number" name="cantidad" id="cantidad" value="1" min="1" required class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Moneda</label>
                            <select name="moneda" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all">
                                <option value="ARS">ARS</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Fecha Emision *</label>
                            <input type="date" name="fecha_emision" value="{{ date('Y-m-d') }}" required class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Fecha Vencimiento</label>
                            <input type="date" name="fecha_vencimiento" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Observaciones</label>
                        <textarea name="observaciones" rows="2" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all" placeholder="Notas opcionales..."></textarea>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-zinc-800 shrink-0">
                    <button type="button" onclick="cerrarModal()" class="px-4 py-2 text-sm text-gray-400 hover:text-gray-200 hover:bg-zinc-800 rounded-xl transition-all">Cancelar</button>
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Guardar Cobranza
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== MODAL: MARCAR COMO COBRADO ==================== -->
    <div id="modal-cobrar" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-zinc-900 rounded-2xl w-full max-w-md border border-zinc-700/50 shadow-2xl">
            <form method="POST" id="form-cobrar" enctype="multipart/form-data">
                @csrf

                <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-600/10 rounded-lg">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-100">Registrar Cobro</h3>
                    </div>
                    <button type="button" onclick="document.getElementById('modal-cobrar').classList.add('hidden')" class="p-2 text-gray-500 hover:text-gray-300 hover:bg-zinc-800 rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Fecha de Pago *</label>
                        <input type="date" name="fecha_pago" value="{{ date('Y-m-d') }}" required class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Comprobante de pago</label>
                        <input type="file" name="comprobante" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-zinc-800 file:text-gray-300 hover:file:bg-zinc-700 file:cursor-pointer file:transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Factura</label>
                        <input type="file" name="factura" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-zinc-800 file:text-gray-300 hover:file:bg-zinc-700 file:cursor-pointer file:transition-all">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-zinc-800">
                    <button type="button" onclick="document.getElementById('modal-cobrar').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-400 hover:text-gray-200 hover:bg-zinc-800 rounded-xl transition-all">Cancelar</button>
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Confirmar Cobro
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // ==============================
        // TABS
        // ==============================
        function switchTab(tab) {
            document.querySelectorAll('.cobranza-tab').forEach(btn => {
                btn.classList.remove('bg-emerald-600', 'text-white', 'shadow-lg', 'shadow-emerald-600/20');
                btn.classList.add('bg-zinc-800', 'text-gray-400', 'border', 'border-zinc-700/50');
            });
            const active = document.getElementById('tab-btn-' + tab);
            active.classList.add('bg-emerald-600', 'text-white', 'shadow-lg', 'shadow-emerald-600/20');
            active.classList.remove('bg-zinc-800', 'text-gray-400', 'border', 'border-zinc-700/50');

            document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
            document.getElementById('tab-' + tab).classList.remove('hidden');
        }

        // ==============================
        // MODALS
        // ==============================
        function abrirNuevaCobranza() {
            document.getElementById('modal-cobranza').classList.remove('hidden');
        }
        function cerrarModal() {
            document.getElementById('modal-cobranza').classList.add('hidden');
        }
        function abrirCobrar(id) {
            const form = document.getElementById('form-cobrar');
            form.action = '/rodados/cobranzas/' + id + '/adjuntar';
            document.getElementById('modal-cobrar').classList.remove('hidden');
        }

        // ==============================
        // AUTO-FILL FROM SERVICE
        // ==============================
        function actualizarValor() {
            const select = document.getElementById('servicio-select');
            const option = select.options[select.selectedIndex];
            if (option.value) {
                document.getElementById('valor-unitario').value = option.dataset.valor;
                const concepto = document.getElementById('input-concepto');
                if (!concepto.value) {
                    concepto.value = option.dataset.nombre;
                }
            }
        }

        // ==============================
        // TOAST
        // ==============================
        function dismissToast(id) {
            const el = document.getElementById(id);
            if (el) { el.style.opacity = '0'; setTimeout(() => el.remove(), 300); }
        }
        document.querySelectorAll('[id^="toast-"]').forEach(toast => {
            setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 5000);
        });
    </script>
    @endpush

    @push('styles')
    <style>
        .toast-enter { animation: slideIn 0.3s ease-out; }
        .toast-progress { animation: progress 5s linear forwards; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes progress { from { width: 100%; } to { width: 0%; } }
        .modal-scroll::-webkit-scrollbar { width: 4px; }
        .modal-scroll::-webkit-scrollbar-track { background: transparent; }
        .modal-scroll::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 4px; }
    </style>
    @endpush
</x-administrative-layout>
