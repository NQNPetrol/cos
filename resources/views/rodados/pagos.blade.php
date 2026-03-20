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

            <!-- Header Principal -->
            <div class="mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-gradient-to-br from-blue-600/20 to-blue-400/10 rounded-xl border border-blue-500/20">
                            <svg class="w-7 h-7 text-blue-400" fill="currentColor" viewBox="0 0 209 256"><path d="M120.523,2l-1.877,23.625H96.625v78.681h-2.458c-14.746,0-28.129,8.628-34.214,22.06L2.125,254h78.75l5.07-16.892 C122.588,225.945,132.216,191,132.216,191h51.034v-18.12l10.529,0.837L206.875,8.861L120.523,2z M175.375,166.606 c-7.757,2.097-13.697,8.53-15.077,16.519h-44.767c-2.957,15.124-14.146,28.884-29.224,35.043c-0.487,0.199-0.992,0.293-1.488,0.293 c-1.552,0-3.023-0.924-3.647-2.449c-0.822-2.013,0.143-4.311,2.156-5.134c14.782-6.038,25.106-20.689,25.106-35.628 c0-0.007,0.002-3.938,0.002-3.938h40.032c5.948,0,11.048-4.654,11.154-10.601c0.108-6.056-4.765-10.996-10.797-11.006l-70.906-0.049 c-2.174,0-3.938-1.763-3.938-3.938s1.763-3.938,3.938-3.938h26.58V49.236c7.999-1.632,14.288-7.837,16.1-15.736h38.987 c1.791,7.83,7.972,13.941,15.789,15.668V166.606z M187.86,148.775c-1.608,0.301-3.149,0.796-4.61,1.446V25.625h-53.998 c6.856-1.808,12.395-7.039,14.546-13.876l38.864,3.088c1.166,7.947,6.842,14.528,14.498,16.868L187.86,148.775z M167.696,107.404 c0.028-15.077-12.011-27.764-27.692-27.793c-15.077-0.028-27.766,12.614-27.793,27.692c-0.028,15.077,12.011,27.764,27.692,27.793 C154.98,135.124,167.668,122.482,167.696,107.404z M123.666,109.737l-4.825-0.009l0.01-5.428l4.222,0.008 c0.004-2.413,1.216-5.426,1.823-7.837l6.028,1.821c-0.605,1.205-1.213,3.616-1.22,7.235c-0.005,3.015,1.197,4.827,3.007,4.831 s3.019-1.804,4.836-6.022c2.423-6.027,4.841-9.038,9.666-9.028c4.221,0.611,7.832,4.236,9.031,9.063l4.825,0.009l-0.01,5.428 l-4.222-0.008c-0.005,2.413-1.22,7.235-1.826,9.043l-6.028-1.821c1.21-1.807,2.422-5.423,2.428-8.439 c0.006-3.619-1.196-5.43-3.609-5.434c-1.809-0.004-3.018,1.201-4.835,5.419c-1.819,5.424-4.842,9.641-9.667,9.632 C128.475,118.189,124.861,115.166,123.666,109.737z"/></svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-100 tracking-tight">Pagos de Servicios</h1>
                            <p class="text-sm text-gray-400 mt-0.5">Gestion de pagos realizados y por realizar</p>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="flex flex-wrap gap-3">
                        <div class="flex items-center gap-2 px-4 py-2 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                            <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                            <span class="text-xs text-gray-400">Pendientes</span>
                            <span class="text-sm font-semibold text-gray-200">{{ $pagosPendientes->count() }}</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                            <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                            <span class="text-xs text-gray-400">Realizados</span>
                            <span class="text-sm font-semibold text-gray-200">{{ $pagosRealizados->count() }}</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                            <div class="w-2 h-2 rounded-full bg-purple-400"></div>
                            <span class="text-xs text-gray-400">Servicios</span>
                            <span class="text-sm font-semibold text-gray-200">{{ $servicios->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="flex items-center gap-2 mb-6">
                <button onclick="switchPagosTab('pendientes')" id="pagos-tab-pendientes"
                    class="pagos-tab-button group relative px-5 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 bg-blue-600 text-white shadow-lg shadow-blue-600/20">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 1024 1024"><path d="M146.1 786.12c0-30.21 24.59-54.8 54.8-54.8h200.87v-73.14H310.81c-30.21 0-54.8-24.59-54.8-54.8 0-30.23 24.59-54.82 54.8-54.82h127.98v-73.14H200.76c-30.21 0-54.8-24.59-54.8-54.8s24.59-54.8 54.8-54.8h512.4c70.57 0 128-57.43 128-128s-57.43-128-128-128H310.87c-70.57 0-128 57.43-128 128 0 19.9 4.96 38.52 13.1 55.34-68.29 2.58-123.16 58.55-123.16 127.46s54.85 124.87 123.14 127.46c-8.13 16.81-13.08 35.42-13.08 55.31s4.95 38.5 13.08 55.3c-68.21 2.67-123 58.59-123 127.45 0 70.55 57.39 127.95 127.95 127.95h274.57v-73.14H200.9c-30.21-0.02-54.8-24.61-54.8-54.83z m164.77-603.17h402.29c30.25 0 54.86 24.61 54.86 54.86s-24.61 54.86-54.86 54.86H310.87c-30.25 0-54.86-24.61-54.86-54.86s24.61-54.86 54.86-54.86z"/><path d="M694.54 402.54c-141.4 0-256.03 114.63-256.03 256.03 0 141.4 114.63 256.03 256.03 256.03 141.4 0 256.03-114.63 256.03-256.03 0-141.41-114.63-256.03-256.03-256.03z m0 438.92c-100.84 0-182.89-82.04-182.89-182.89s82.04-182.89 182.89-182.89c100.85 0 182.89 82.04 182.89 182.89s-82.05 182.89-182.89 182.89z"/><path d="M721.97 552.38h-54.85V670l93.89 93.07 38.61-38.96-77.65-76.97z"/></svg>
                        Pagos por Realizar
                        <span class="text-xs opacity-75 bg-white/20 px-1.5 py-0.5 rounded-md">{{ $pagosPendientes->count() }}</span>
                    </span>
                </button>
                <button onclick="switchPagosTab('realizados')" id="pagos-tab-realizados"
                    class="pagos-tab-button group relative px-5 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 bg-zinc-800 text-gray-400 hover:text-gray-200 hover:bg-zinc-700 border border-zinc-700/50">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 1024 1024"><path d="M146.1 786.12c0-30.21 24.59-54.8 54.8-54.8h274.02v-73.14H310.81c-30.21 0-54.8-24.59-54.8-54.8 0-30.23 24.59-54.82 54.8-54.82h164.55v-73.14h-274.6c-30.21 0-54.8-24.59-54.8-54.8s24.59-54.8 54.8-54.8h512.4c70.57 0 128-57.43 128-128s-57.43-128-128-128H310.87c-70.57 0-128 57.43-128 128 0 19.9 4.96 38.52 13.1 55.34-68.29 2.58-123.16 58.55-123.16 127.46s54.85 124.87 123.14 127.46c-8.13 16.81-13.08 35.42-13.08 55.31s4.95 38.5 13.08 55.3c-68.21 2.67-123 58.59-123 127.45 0 70.55 57.39 127.95 127.95 127.95h347.71v-73.14H200.9c-30.21-0.02-54.8-24.61-54.8-54.83z m164.77-603.17h402.29c30.25 0 54.86 24.61 54.86 54.86s-24.61 54.86-54.86 54.86H310.87c-30.25 0-54.86-24.61-54.86-54.86s24.61-54.86 54.86-54.86z"/><path d="M731.28 402.29L511.86 512v96.26c0 120.31 67.97 230.3 175.58 284.1l43.84 21.92 43.84-21.92c107.61-53.81 175.59-163.79 175.59-284.1V512L731.28 402.29z m146.29 205.97c0 93.21-51.79 177-135.15 218.69l-11.13 5.56-11.13-5.56C636.78 785.26 585 701.47 585 608.26v-51.05l146.29-73.14 146.29 73.14v51.05z"/><path d="M669.33 624.18l-36.32 41.11 99.84 88.26 122.64-141.1-41.39-36-86.36 99.35z"/></svg>
                        Pagos Realizados
                        <span class="text-xs opacity-60 bg-zinc-700 px-1.5 py-0.5 rounded-md">{{ $pagosRealizados->count() }}</span>
                    </span>
                </button>
            </div>

            <!-- ==================== PAGOS PENDIENTES TAB ==================== -->
            <div id="pagos-tab-content-pendientes" class="pagos-tab-content">
                <!-- Action Bar -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" id="busqueda-pendiente" placeholder="Buscar pago pendiente..."
                                class="pl-10 pr-4 py-2 bg-zinc-800 border border-zinc-700 rounded-xl text-sm text-gray-200 placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 w-72 transition-all"
                                oninput="buscarPago(this.value, 'pendiente')">
                        </div>
                        <button id="btn-batch-comprobante" onclick="abrirModalBatch()"
                            class="hidden items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 rounded-xl text-sm font-medium text-white shadow-lg shadow-emerald-600/20 hover:-translate-y-0.5 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                            Adjuntar comprobante (<span id="batch-count">0</span>)
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="document.getElementById('modal-servicios').classList.remove('hidden')"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-800 hover:bg-zinc-700 border border-zinc-700/50 rounded-xl text-sm font-medium text-gray-300 hover:text-white transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0"/></svg>
                            Servicios
                        </button>
                        <button onclick="resetModalPago(); document.getElementById('modal-nuevo-pago').classList.remove('hidden')"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-xl text-sm font-medium text-white shadow-lg shadow-blue-600/20 hover:-translate-y-0.5 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Nuevo Pago
                        </button>
                    </div>
                </div>

                <!-- Tabla Pendientes -->
                <div class="bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50 overflow-hidden shadow-xl">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-zinc-700/50">
                                    <th class="pl-4 pr-1 py-3.5 w-10">
                                        <input type="checkbox" id="select-all-pagos" onchange="toggleSelectAllPagos(this)"
                                            class="rounded border-zinc-600 bg-zinc-700 text-blue-500 focus:ring-blue-500 cursor-pointer">
                                    </th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tipo</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Vehiculo</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Detalle</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Monto</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Vencimiento</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Factura</th>
                                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-700/30">
                                @forelse($pagosPendientes as $pago)
                                @php
                                    $isVencido = $pago->fecha_vencimiento && $pago->fecha_vencimiento->isPast();
                                    $isTurno = in_array($pago->tipo, ['pago_service', 'pago_taller']);
                                @endphp
                                <tr class="pago-pendiente-row group hover:bg-zinc-700/30 transition-colors duration-150"
                                    data-search="{{ strtolower(($pago->rodado?->patente ?? '') . ' ' . ($pago->rodado?->marca ?? '') . ' ' . ($pago->rodado?->modelo ?? '') . ' ' . ($pago->servicioUsuario?->nombre ?? '') . ' ' . ($pago->observaciones ?? '') . ' ' . str_replace(['pago_', '_'], ['', ' '], $pago->tipo)) }}">
                                    <td class="pl-4 pr-1 py-4">
                                        <input type="checkbox" class="pago-checkbox rounded border-zinc-600 bg-zinc-700 text-blue-500 focus:ring-blue-500 cursor-pointer"
                                            value="{{ $pago->id }}" onchange="actualizarBatchCount()">
                                    </td>
                                    <td class="px-5 py-4">
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
                                        @elseif($pago->tipo === 'pago_patente')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-violet-500/10 text-violet-400 border border-violet-500/20">
                                                <div class="w-1.5 h-1.5 rounded-full bg-violet-400"></div>
                                                Patente
                                            </span>
                                        @elseif($pago->tipo === 'pago_seguro')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                                                <div class="w-1.5 h-1.5 rounded-full bg-cyan-400"></div>
                                                Seguro
                                            </span>
                                        @elseif($pago->tipo === 'pago_vtv')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-teal-500/10 text-teal-400 border border-teal-500/20">
                                                <div class="w-1.5 h-1.5 rounded-full bg-teal-400"></div>
                                                VTV
                                            </span>
                                        @elseif($pago->tipo === 'pago_proveedor' || $pago->tipo === 'pago_a_proveedor')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                                                Proveedor
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-zinc-600/30 text-gray-400 border border-zinc-600/30">
                                                <div class="w-1.5 h-1.5 rounded-full bg-gray-400"></div>
                                                {{ ucfirst(str_replace(['pago_', '_'], ['', ' '], $pago->tipo)) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-gradient-to-br from-zinc-600/20 to-zinc-500/10 border border-zinc-600/20 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-100">{{ $pago->rodado?->patente ?? 'Sin vehiculo' }}</div>
                                                <div class="text-xs text-gray-500">{{ $pago->rodado?->marca ?? '' }} {{ $pago->rodado?->modelo ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="text-sm text-gray-300">
                                            @if($pago->servicioUsuario)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-medium bg-purple-500/10 text-purple-400 border border-purple-500/20">
                                                    {{ $pago->servicioUsuario->nombre }}
                                                </span>
                                            @elseif($pago->turnoRodado)
                                                {{ $pago->turnoRodado->taller->nombre ?? 'Sin taller' }}
                                                <div class="text-xs text-gray-500 mt-0.5">Turno {{ $pago->turnoRodado->fecha_hora ? $pago->turnoRodado->fecha_hora->format('d/m/Y') : '' }}</div>
                                            @else
                                                <span class="text-gray-400">{{ $pago->observaciones ?? '-' }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="text-sm font-semibold text-gray-100">
                                            @if($pago->moneda === 'USD') <span class="text-xs text-gray-500">USD</span> @endif${{ number_format($pago->monto, 2, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $pago->moneda }}</div>
                                    </td>
                                    <td class="px-5 py-4">
                                        @if($pago->fecha_vencimiento)
                                            <div class="text-sm {{ $isVencido ? 'text-red-400 font-semibold' : 'text-gray-300' }}">
                                                {{ $pago->fecha_vencimiento->format('d/m/Y') }}
                                            </div>
                                            @if($isVencido)
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-500/10 text-red-400 border border-red-500/20 mt-0.5">VENCIDO</span>
                                            @else
                                                <span class="text-xs text-gray-500">{{ (int) now()->diffInDays($pago->fecha_vencimiento) }} dias</span>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-600 italic">Sin fecha</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @if($pago->factura_path)
                                            <a href="{{ asset('storage/' . $pago->factura_path) }}" target="_blank"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20 hover:bg-blue-500/20 transition-all">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                Ver
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-600">--</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-1">
                                            <form action="{{ route('rodados.pagos.adjuntar-factura', $pago) }}" method="POST" enctype="multipart/form-data" class="inline" id="comprobante-form-{{ $pago->id }}">
                                                @csrf
                                                <label class="p-2 rounded-lg text-gray-400 hover:text-emerald-400 hover:bg-emerald-500/10 transition-all duration-150 cursor-pointer inline-flex" title="Adjuntar comprobante de pago">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                                    <input type="file" name="comprobante_pago" class="hidden" accept=".pdf,.jpg,.jpeg,.png"
                                                        onchange="if(confirm('Adjuntar comprobante y marcar como pagado?')) this.form.submit();">
                                                </label>
                                            </form>
                                            @if(!$pago->turno_rodado_id)
                                            <form action="{{ route('rodados.pagos.destroy', $pago) }}" method="POST" class="inline" onsubmit="return confirm('Eliminar este pago?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-red-400 hover:bg-red-500/10 transition-all duration-150" title="Eliminar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 rounded-full bg-zinc-700/30 flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <p class="text-gray-400 font-medium">No hay pagos pendientes</p>
                                            <p class="text-gray-600 text-sm mt-1">Todos los pagos estan al dia</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($pagosPendientes->count() > 0)
                    <div class="px-5 py-3 bg-zinc-900/30 border-t border-zinc-700/30 flex items-center justify-between">
                        <span class="text-xs text-gray-500" id="pendientes-count-text">Mostrando {{ $pagosPendientes->count() }} pagos pendientes</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- ==================== PAGOS REALIZADOS TAB ==================== -->
            <div id="pagos-tab-content-realizados" class="pagos-tab-content hidden">
                <!-- Action Bar -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                    <div class="relative">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" id="busqueda-realizado" placeholder="Buscar pago realizado..."
                            class="pl-10 pr-4 py-2 bg-zinc-800 border border-zinc-700 rounded-xl text-sm text-gray-200 placeholder-gray-500 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 w-72 transition-all"
                            oninput="buscarPago(this.value, 'realizado')">
                    </div>
                </div>

                <!-- Tabla Realizados -->
                <div class="bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50 overflow-hidden shadow-xl">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-zinc-700/50">
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tipo</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Vehiculo</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Detalle</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Monto</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Fecha Pago</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Comprobante</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Factura</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-700/30">
                                @forelse($pagosRealizados as $pago)
                                <tr class="pago-realizado-row group hover:bg-zinc-700/30 transition-colors duration-150"
                                    data-search="{{ strtolower(($pago->rodado?->patente ?? '') . ' ' . ($pago->rodado?->marca ?? '') . ' ' . ($pago->rodado?->modelo ?? '') . ' ' . ($pago->servicioUsuario?->nombre ?? '') . ' ' . ($pago->observaciones ?? '') . ' ' . str_replace(['pago_', '_'], ['', ' '], $pago->tipo)) }}">
                                    <td class="px-5 py-4">
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
                                        @elseif($pago->tipo === 'pago_patente')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-violet-500/10 text-violet-400 border border-violet-500/20">
                                                <div class="w-1.5 h-1.5 rounded-full bg-violet-400"></div>
                                                Patente
                                            </span>
                                        @elseif($pago->tipo === 'pago_seguro')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                                                <div class="w-1.5 h-1.5 rounded-full bg-cyan-400"></div>
                                                Seguro
                                            </span>
                                        @elseif($pago->tipo === 'pago_vtv')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-teal-500/10 text-teal-400 border border-teal-500/20">
                                                <div class="w-1.5 h-1.5 rounded-full bg-teal-400"></div>
                                                VTV
                                            </span>
                                        @elseif($pago->tipo === 'pago_proveedor' || $pago->tipo === 'pago_a_proveedor')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                                                Proveedor
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                                                {{ ucfirst(str_replace(['pago_', '_'], ['', ' '], $pago->tipo)) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-600/20 to-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-100">{{ $pago->rodado?->patente ?? 'Sin vehiculo' }}</div>
                                                <div class="text-xs text-gray-500">{{ $pago->rodado?->marca ?? '' }} {{ $pago->rodado?->modelo ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="text-sm text-gray-300">
                                            @if($pago->servicioUsuario)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-medium bg-purple-500/10 text-purple-400 border border-purple-500/20">
                                                    {{ $pago->servicioUsuario->nombre }}
                                                </span>
                                            @elseif($pago->turnoRodado)
                                                {{ $pago->turnoRodado->taller->nombre ?? 'Sin taller' }}
                                                <div class="text-xs text-gray-500 mt-0.5">Turno {{ $pago->turnoRodado->fecha_hora ? $pago->turnoRodado->fecha_hora->format('d/m/Y') : '' }}</div>
                                            @else
                                                <span class="text-gray-400">{{ $pago->observaciones ?? '-' }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="text-sm font-semibold text-emerald-400">
                                            @if($pago->moneda === 'USD') <span class="text-xs text-gray-500">USD</span> @endif${{ number_format($pago->monto, 2, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $pago->moneda }}</div>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-300">
                                        {{ $pago->fecha_pago ? $pago->fecha_pago->format('d/m/Y') : '--' }}
                                    </td>
                                    <td class="px-5 py-4">
                                        @if($pago->comprobante_pago_path)
                                            <a href="{{ asset('storage/' . $pago->comprobante_pago_path) }}" target="_blank"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20 transition-all">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                Ver
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-600">--</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @if($pago->factura_path)
                                            <a href="{{ asset('storage/' . $pago->factura_path) }}" target="_blank"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20 hover:bg-blue-500/20 transition-all">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                Ver
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-600">--</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 rounded-full bg-zinc-700/30 flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                                            </div>
                                            <p class="text-gray-400 font-medium">No hay pagos realizados</p>
                                            <p class="text-gray-600 text-sm mt-1">Los pagos completados apareceran aqui</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($pagosRealizados->count() > 0)
                    <div class="px-5 py-3 bg-zinc-900/30 border-t border-zinc-700/30 flex items-center justify-between">
                        <span class="text-xs text-gray-500" id="realizados-count-text">Mostrando {{ $pagosRealizados->count() }} pagos realizados</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Servicios CRUD -->
    <div id="modal-servicios" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center modal-backdrop" style="padding-left: calc(var(--fb-sidebar-width, 240px) + 1rem); padding-top: calc(var(--fb-topbar-height, 60px) + 1.5rem); padding-right: 1rem; padding-bottom: 1rem;" onclick="if(event.target === this) document.getElementById('modal-servicios').classList.add('hidden')">
        <div class="w-full max-w-lg bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden modal-content" style="max-height: calc(100vh - var(--fb-topbar-height, 60px) - 4rem);" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-purple-600/10 rounded-lg">
                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-100">Gestion de Servicios</h3>
                </div>
                <button type="button" onclick="document.getElementById('modal-servicios').classList.add('hidden')" class="p-2 text-gray-500 hover:text-gray-300 hover:bg-zinc-800 rounded-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="px-6 py-5 overflow-y-auto modal-scroll" style="max-height: calc(100vh - var(--fb-topbar-height, 60px) - 12rem);">
                <!-- Create Service Form -->
                <form action="{{ route('rodados.servicios-usuario.store') }}" method="POST" class="mb-6 p-4 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                    @csrf
                    <h4 class="text-xs font-medium text-gray-400 mb-3 uppercase tracking-wider">Nuevo Servicio</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="col-span-2">
                            <input type="text" name="nombre" placeholder="Nombre del servicio" required class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-purple-500 focus:ring-1 focus:ring-purple-500/30 transition-all">
                        </div>
                        <div>
                            <select name="tipo_calculo" required class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500/30 transition-all">
                                <option value="fijo">Fijo</option>
                                <option value="variable">Variable (por cantidad)</option>
                            </select>
                        </div>
                        <div>
                            <input type="number" name="valor_unitario" step="0.01" placeholder="Valor" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-purple-500 focus:ring-1 focus:ring-purple-500/30 transition-all">
                        </div>
                        <div>
                            <select name="moneda" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500/30 transition-all">
                                <option value="ARS">ARS</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="w-full px-3.5 py-2.5 bg-purple-600 text-white text-sm font-medium rounded-xl hover:bg-purple-700 shadow-lg shadow-purple-600/20 hover:-translate-y-0.5 transition-all duration-200">Crear</button>
                        </div>
                    </div>
                </form>

                <!-- Existing Services List -->
                <div class="space-y-2">
                    @foreach($servicios as $servicio)
                    <div class="flex items-center justify-between p-3.5 bg-zinc-800/80 rounded-xl border border-zinc-700/50 group hover:border-zinc-600/50 transition-all">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-gradient-to-br from-purple-600/20 to-purple-400/10 border border-purple-500/20 flex items-center justify-center">
                                <div class="w-2 h-2 rounded-full bg-purple-400"></div>
                            </div>
                            <div>
                                <div class="font-medium text-gray-200 text-sm">{{ $servicio->nombre }}</div>
                                <div class="text-xs text-gray-500">{{ ucfirst($servicio->tipo_calculo) }} - ${{ number_format($servicio->valor_unitario, 2) }} {{ $servicio->moneda }}</div>
                            </div>
                        </div>
                        <form action="{{ route('rodados.servicios-usuario.destroy', $servicio) }}" method="POST" onsubmit="return confirm('Eliminar?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-red-400 hover:bg-red-500/10 transition-all duration-150">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Pago -->
    <div id="modal-nuevo-pago" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center modal-backdrop" style="padding-left: calc(var(--fb-sidebar-width, 240px) + 1rem); padding-top: calc(var(--fb-topbar-height, 60px) + 1.5rem); padding-right: 1rem; padding-bottom: 1rem;" onclick="if(event.target === this) cerrarModalPago()">
        <div class="w-full max-w-md bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden flex flex-col modal-content" style="max-height: calc(100vh - var(--fb-topbar-height, 60px) - 4rem);" onclick="event.stopPropagation()">
            <form id="form-nuevo-pago" method="POST" action="{{ route('rodados.pagos.store') }}" enctype="multipart/form-data" class="flex flex-col overflow-hidden">
                @csrf
                <input type="hidden" name="tipo_pago" id="tipo-pago" value="">
                <input type="hidden" name="rodado_id" id="rodado-id" value="">
                <input type="hidden" name="servicio_usuario_id" id="servicio-usuario-id" value="">
                <input type="hidden" name="tipo" id="tipo-servicio" value="pagos_adicionales">
                <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-600/10 rounded-lg">
                            <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 209 256"><path d="M120.523,2l-1.877,23.625H96.625v78.681h-2.458c-14.746,0-28.129,8.628-34.214,22.06L2.125,254h78.75l5.07-16.892 C122.588,225.945,132.216,191,132.216,191h51.034v-18.12l10.529,0.837L206.875,8.861L120.523,2z M175.375,166.606 c-7.757,2.097-13.697,8.53-15.077,16.519h-44.767c-2.957,15.124-14.146,28.884-29.224,35.043c-0.487,0.199-0.992,0.293-1.488,0.293 c-1.552,0-3.023-0.924-3.647-2.449c-0.822-2.013,0.143-4.311,2.156-5.134c14.782-6.038,25.106-20.689,25.106-35.628 c0-0.007,0.002-3.938,0.002-3.938h40.032c5.948,0,11.048-4.654,11.154-10.601c0.108-6.056-4.765-10.996-10.797-11.006l-70.906-0.049 c-2.174,0-3.938-1.763-3.938-3.938s1.763-3.938,3.938-3.938h26.58V49.236c7.999-1.632,14.288-7.837,16.1-15.736h38.987 c1.791,7.83,7.972,13.941,15.789,15.668V166.606z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-100">Registrar Pago</h3>
                    </div>
                    <button type="button" onclick="cerrarModalPago()" class="p-2 text-gray-500 hover:text-gray-300 hover:bg-zinc-800 rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="px-6 py-5 overflow-y-auto flex-1 space-y-5 modal-scroll">
                    {{-- 1. Tipo de pago --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Tipo de pago *</label>
                        <select id="select-tipo-pago" required class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                            <option value="">Seleccionar...</option>
                            <option value="servicio_mensual">Servicio Mensual</option>
                            <option value="pago_vehiculo">Pago por Vehiculo</option>
                        </select>
                    </div>

                    {{-- Servicio Mensual --}}
                    <div id="bloque-servicio-mensual" class="hidden space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Servicio mensual a pagar *</label>
                            <select id="select-servicio-mensual" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                                <option value="">Seleccionar...</option>
                                @foreach($servicios as $s)
                                    <option value="{{ $s->id }}" data-tipo="{{ $s->tipo_calculo }}" data-valor="{{ $s->valor_unitario }}" data-moneda="{{ $s->moneda }}">{{ $s->nombre }} ({{ ucfirst($s->tipo_calculo) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="bloque-monto-fijo" class="hidden">
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Monto a pagar *</label>
                            <input type="number" id="input-monto-servicio" name="monto" step="0.01" min="0" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" placeholder="0.00">
                        </div>
                        <div id="bloque-cantidad-variable" class="hidden">
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Cantidad *</label>
                            <input type="number" id="input-cantidad-variable" min="1" value="1" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                            <p class="text-xs text-gray-500 mt-1.5">Total: <span id="texto-total-variable" class="font-semibold text-gray-300">0</span></p>
                        </div>
                        <div id="bloque-moneda-servicio" class="hidden">
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Moneda</label>
                            <select name="moneda" id="select-moneda-servicio" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                                <option value="ARS">ARS</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                    </div>

                    {{-- Pago por Vehiculo --}}
                    <div id="bloque-pago-vehiculo" class="hidden space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Seleccionar Vehiculo *</label>
                            <select id="select-rodado" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                                <option value="">Seleccionar...</option>
                                @foreach($rodados as $r)
                                    <option value="{{ $r->id }}" data-proveedor="{{ $r->proveedor_id ? '1' : '0' }}">{{ $r->patente ?? 'Sin patente' }} - {{ $r->marca ?? '' }} {{ $r->modelo ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="bloque-servicio-a-pagar" class="hidden">
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Servicio a Pagar *</label>
                            <select id="select-tipo-servicio-vehiculo" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                                <option value="">Seleccionar...</option>
                            </select>
                            <div id="bloque-tipo-otro" class="hidden mt-2">
                                <input type="text" id="input-tipo-otro" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" placeholder="Ingrese el tipo de servicio">
                            </div>
                        </div>
                        <div id="bloque-monto-vehiculo" class="hidden flex gap-3">
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Moneda</label>
                                <select name="moneda" id="select-moneda-vehiculo" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                                    <option value="ARS">ARS</option>
                                    <option value="USD">USD</option>
                                </select>
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Monto *</label>
                                <input type="number" name="monto" id="input-monto-vehiculo" step="0.01" min="0" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    {{-- Toggle Ya pagado / A pagar --}}
                    <div id="bloque-estado-pago" class="hidden pt-3 border-t border-zinc-700/50">
                        <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Estado del pago</label>
                        <div class="flex gap-2 p-1 bg-zinc-800 rounded-xl border border-zinc-700/50">
                            <button type="button" id="btn-ya-pagado" class="flex-1 px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200">Ya pagado</button>
                            <button type="button" id="btn-a-pagar" class="flex-1 px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200">A pagar</button>
                        </div>
                        <input type="hidden" id="estado-pago" value="pendiente">
                    </div>

                    {{-- Ya pagado: fecha + comprobante --}}
                    <div id="bloque-ya-pagado" class="hidden space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Fecha de pago *</label>
                            <input type="date" name="fecha_pago" id="input-fecha-pago" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Comprobante de pago</label>
                            <input type="file" name="comprobante_pago" accept=".pdf,.jpg,.jpeg,.png" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-zinc-700 file:text-gray-300 hover:file:bg-zinc-600 transition-all">
                        </div>
                    </div>

                    {{-- A pagar: fecha vencimiento --}}
                    <div id="bloque-a-pagar" class="hidden">
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Fecha de vencimiento *</label>
                        <input type="date" name="fecha_vencimiento" id="input-fecha-vencimiento" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                    </div>

                    {{-- Factura (opcional) --}}
                    <div id="bloque-factura" class="hidden">
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Adjuntar factura</label>
                        <input type="file" name="factura" id="input-factura" accept=".pdf,.jpg,.jpeg,.png" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-zinc-700 file:text-gray-300 hover:file:bg-zinc-600 transition-all">
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-zinc-800 bg-zinc-900/50 shrink-0">
                    <button type="button" onclick="cerrarModalPago()" class="px-4 py-2.5 text-sm font-medium text-gray-400 hover:text-gray-200 bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 rounded-xl transition-all">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-600/20 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Batch Comprobante -->
    <div id="modal-batch-comprobante" class="hidden fixed inset-0 z-50 modal-backdrop" style="background:rgba(0,0,0,.6); backdrop-filter:blur(4px);">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl w-full max-w-md modal-content">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h3 class="text-lg font-semibold text-white">Adjuntar comprobante de pago</h3>
                            <p class="text-sm text-gray-400 mt-0.5"><span id="modal-batch-count">0</span> registros seleccionados</p>
                        </div>
                        <button onclick="cerrarModalBatch()" class="p-2 rounded-lg text-gray-500 hover:text-gray-300 hover:bg-zinc-800 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <form id="form-batch-comprobante" action="{{ route('rodados.pagos.adjuntar-comprobante-batch') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div id="batch-ids-container"></div>
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Comprobante de pago</label>
                            <div id="batch-dropzone" class="border-2 border-dashed border-zinc-700 rounded-xl p-6 text-center hover:border-blue-500/50 transition-colors cursor-pointer"
                                onclick="document.getElementById('batch-file-input').click()">
                                <svg class="w-8 h-8 mx-auto text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                <p class="text-sm text-gray-400" id="batch-file-label">Click para seleccionar archivo</p>
                                <p class="text-xs text-gray-600 mt-1">PDF, JPG, PNG — max 10 MB</p>
                            </div>
                            <input type="file" id="batch-file-input" name="comprobante_pago" class="hidden" accept=".pdf,.jpg,.jpeg,.png"
                                onchange="actualizarBatchFileLabel(this)">
                        </div>
                        <div class="flex gap-3">
                            <button type="button" onclick="cerrarModalBatch()"
                                class="flex-1 px-4 py-2.5 bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 rounded-xl text-sm font-medium text-gray-300 transition-all">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 rounded-xl text-sm font-medium text-white shadow-lg shadow-emerald-600/20 transition-all">
                                Adjuntar y marcar como pagados
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // === Batch selection ===
        function toggleSelectAllPagos(master) {
            document.querySelectorAll('.pago-pendiente-row').forEach(row => {
                if (row.style.display !== 'none') {
                    const cb = row.querySelector('.pago-checkbox');
                    if (cb) cb.checked = master.checked;
                }
            });
            actualizarBatchCount();
        }

        function actualizarBatchCount() {
            const checked = document.querySelectorAll('.pago-checkbox:checked');
            const count = checked.length;
            document.getElementById('batch-count').textContent = count;
            const btn = document.getElementById('btn-batch-comprobante');
            if (count > 0) {
                btn.classList.remove('hidden');
                btn.classList.add('inline-flex');
            } else {
                btn.classList.add('hidden');
                btn.classList.remove('inline-flex');
            }
            const masterCb = document.getElementById('select-all-pagos');
            const visibleCbs = [...document.querySelectorAll('.pago-pendiente-row')].filter(r => r.style.display !== 'none').map(r => r.querySelector('.pago-checkbox')).filter(Boolean);
            const allChecked = visibleCbs.length > 0 && visibleCbs.every(cb => cb.checked);
            const someChecked = visibleCbs.some(cb => cb.checked);
            masterCb.checked = allChecked;
            masterCb.indeterminate = someChecked && !allChecked;
        }

        function abrirModalBatch() {
            const checked = document.querySelectorAll('.pago-checkbox:checked');
            if (checked.length === 0) return;
            const container = document.getElementById('batch-ids-container');
            container.innerHTML = '';
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'pago_ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });
            document.getElementById('modal-batch-count').textContent = checked.length;
            document.getElementById('batch-file-input').value = '';
            document.getElementById('batch-file-label').textContent = 'Click para seleccionar archivo';
            document.getElementById('modal-batch-comprobante').classList.remove('hidden');
        }

        function cerrarModalBatch() {
            document.getElementById('modal-batch-comprobante').classList.add('hidden');
        }

        function actualizarBatchFileLabel(input) {
            const label = document.getElementById('batch-file-label');
            if (input.files.length > 0) {
                const file = input.files[0];
                if (file.size > 10 * 1024 * 1024) {
                    label.textContent = 'El archivo supera 10 MB';
                    label.classList.add('text-red-400');
                    input.value = '';
                    return;
                }
                label.textContent = file.name;
                label.classList.remove('text-red-400');
            } else {
                label.textContent = 'Click para seleccionar archivo';
                label.classList.remove('text-red-400');
            }
        }

        // === Tab switching ===
        function switchPagosTab(tabName) {
            document.querySelectorAll('.pagos-tab-content').forEach(c => c.classList.add('hidden'));
            document.querySelectorAll('.pagos-tab-button').forEach(b => {
                b.classList.remove('bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-600/20');
                b.classList.add('bg-zinc-800', 'text-gray-400', 'border', 'border-zinc-700/50');
            });
            document.getElementById('pagos-tab-content-' + tabName).classList.remove('hidden');
            const activeBtn = document.getElementById('pagos-tab-' + tabName);
            activeBtn.classList.remove('bg-zinc-800', 'text-gray-400', 'border', 'border-zinc-700/50');
            activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-600/20');
        }

        // === Search ===
        function buscarPago(query, tipo) {
            const q = query.toLowerCase().trim();
            const rowClass = tipo === 'pendiente' ? '.pago-pendiente-row' : '.pago-realizado-row';
            let visible = 0;
            document.querySelectorAll(rowClass).forEach(row => {
                const match = !q || (row.dataset.search || '').includes(q);
                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            const total = document.querySelectorAll(rowClass).length;
            const countEl = document.getElementById(tipo === 'pendiente' ? 'pendientes-count-text' : 'realizados-count-text');
            const label = tipo === 'pendiente' ? 'pagos pendientes' : 'pagos realizados';
            if (countEl) countEl.textContent = visible === total ? `Mostrando ${total} ${label}` : `Mostrando ${visible} de ${total} ${label}`;
            if (tipo === 'pendiente') {
                document.getElementById('select-all-pagos').checked = false;
                actualizarBatchCount();
            }
        }

        // === Modal Nuevo Pago ===
        const OPCS_CON_PROVEEDOR = [
            { v: 'pago_proveedor', t: 'Pago proveedor' },
            { v: 'pago_taller', t: 'Pago taller' },
            { v: 'pago_patente', t: 'Patente' },
            { v: 'pago_seguro', t: 'Seguro' },
            { v: 'pago_vtv', t: 'VTV' },
            { v: 'pago_service', t: 'Pago Service' },
            { v: 'pagos_adicionales', t: 'Otros' }
        ];
        const OPCS_SIN_PROVEEDOR = [
            { v: 'pago_taller', t: 'Pago taller' },
            { v: 'pago_patente', t: 'Patente' },
            { v: 'pago_seguro', t: 'Seguro' },
            { v: 'pago_vtv', t: 'VTV' },
            { v: 'pago_service', t: 'Pago Service' },
            { v: 'pagos_adicionales', t: 'Otros' }
        ];

        function cerrarModalPago() {
            document.getElementById('modal-nuevo-pago').classList.add('hidden');
        }

        function resetModalPago() {
            document.getElementById('select-tipo-pago').value = '';
            document.getElementById('bloque-servicio-mensual').classList.add('hidden');
            document.getElementById('bloque-pago-vehiculo').classList.add('hidden');
            document.getElementById('bloque-estado-pago').classList.add('hidden');
            document.getElementById('bloque-ya-pagado').classList.add('hidden');
            document.getElementById('bloque-a-pagar').classList.add('hidden');
            document.getElementById('bloque-monto-fijo').classList.add('hidden');
            document.getElementById('bloque-cantidad-variable').classList.add('hidden');
            document.getElementById('bloque-moneda-servicio').classList.add('hidden');
            document.getElementById('bloque-servicio-a-pagar').classList.add('hidden');
            document.getElementById('bloque-monto-vehiculo').classList.add('hidden');
            document.getElementById('bloque-tipo-otro').classList.add('hidden');
            document.getElementById('bloque-factura').classList.add('hidden');

            document.getElementById('tipo-pago').value = '';
            document.getElementById('rodado-id').value = '';
            document.getElementById('servicio-usuario-id').value = '';
            document.getElementById('tipo-servicio').value = 'pagos_adicionales';
            document.getElementById('estado-pago').value = 'pendiente';
            document.getElementById('estado-pago').removeAttribute('name');

            document.getElementById('select-servicio-mensual').value = '';
            document.getElementById('select-rodado').value = '';
            document.getElementById('select-tipo-servicio-vehiculo').innerHTML = '<option value="">Seleccionar...</option>';

            document.getElementById('input-monto-servicio').value = '';
            document.getElementById('input-monto-vehiculo').value = '';
            document.getElementById('input-cantidad-variable').value = '1';
            document.getElementById('input-tipo-otro').value = '';
            document.getElementById('input-fecha-pago').value = '';
            document.getElementById('input-fecha-vencimiento').value = '';
            document.getElementById('input-factura').value = '';
            document.getElementById('texto-total-variable').textContent = '0';

            document.getElementById('input-monto-servicio').setAttribute('name', 'monto');
            document.getElementById('input-monto-vehiculo').setAttribute('name', 'monto');
            document.getElementById('rodado-id').setAttribute('name', 'rodado_id');
            document.getElementById('servicio-usuario-id').setAttribute('name', 'servicio_usuario_id');
            document.getElementById('select-moneda-servicio').setAttribute('name', 'moneda');
            document.getElementById('select-moneda-vehiculo').setAttribute('name', 'moneda');
            document.getElementById('input-fecha-pago').setAttribute('name', 'fecha_pago');
            document.getElementById('input-fecha-vencimiento').setAttribute('name', 'fecha_vencimiento');

            document.getElementById('input-fecha-pago').removeAttribute('required');
            document.getElementById('input-fecha-vencimiento').removeAttribute('required');

            const btnYa = document.getElementById('btn-ya-pagado');
            const btnA = document.getElementById('btn-a-pagar');
            btnYa.classList.remove('bg-blue-600', 'text-white');
            btnYa.classList.add('text-gray-500');
            btnA.classList.remove('bg-blue-600', 'text-white');
            btnA.classList.add('text-gray-500');

            const form = document.getElementById('form-nuevo-pago');
            form.querySelectorAll('input[data-variable]').forEach(el => el.remove());
            form.querySelectorAll('input[name="observaciones"]').forEach(el => el.remove());

            document.getElementById('select-moneda-servicio').value = 'ARS';
            document.getElementById('select-moneda-vehiculo').value = 'ARS';
        }

        document.getElementById('select-tipo-pago').addEventListener('change', function() {
            const v = this.value;
            document.getElementById('tipo-pago').value = v;
            document.getElementById('bloque-servicio-mensual').classList.add('hidden');
            document.getElementById('bloque-pago-vehiculo').classList.add('hidden');
            document.getElementById('bloque-estado-pago').classList.add('hidden');
            document.getElementById('bloque-ya-pagado').classList.add('hidden');
            document.getElementById('bloque-a-pagar').classList.add('hidden');
            document.getElementById('bloque-monto-fijo').classList.add('hidden');
            document.getElementById('bloque-cantidad-variable').classList.add('hidden');
            document.getElementById('bloque-moneda-servicio').classList.add('hidden');
            document.getElementById('bloque-servicio-a-pagar').classList.add('hidden');
            document.getElementById('bloque-monto-vehiculo').classList.add('hidden');
            document.getElementById('bloque-tipo-otro').classList.add('hidden');
            document.getElementById('bloque-factura').classList.add('hidden');

            if (v === 'servicio_mensual') {
                document.getElementById('bloque-servicio-mensual').classList.remove('hidden');
            } else if (v === 'pago_vehiculo') {
                document.getElementById('bloque-pago-vehiculo').classList.remove('hidden');
            }
        });

        document.getElementById('select-servicio-mensual').addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            document.getElementById('bloque-monto-fijo').classList.add('hidden');
            document.getElementById('bloque-cantidad-variable').classList.add('hidden');
            document.getElementById('bloque-moneda-servicio').classList.add('hidden');
            document.getElementById('servicio-usuario-id').value = opt.value || '';
            if (!opt.value) return;
            document.getElementById('bloque-estado-pago').classList.remove('hidden');
            document.getElementById('bloque-moneda-servicio').classList.remove('hidden');
            const tipo = opt.dataset.tipo;
            const valor = parseFloat(opt.dataset.valor || 0);
            const moneda = opt.dataset.moneda || 'ARS';
            document.getElementById('select-moneda-servicio').value = moneda;

            if (tipo === 'fijo') {
                document.getElementById('bloque-monto-fijo').classList.remove('hidden');
                const montoInp = document.getElementById('input-monto-servicio');
                montoInp.setAttribute('name', 'monto');
                montoInp.value = valor || '';
            } else {
                document.getElementById('bloque-cantidad-variable').classList.remove('hidden');
                document.getElementById('input-monto-servicio').removeAttribute('name');
                actualizarTotalVariable();
            }
        });

        function actualizarTotalVariable() {
            const sel = document.getElementById('select-servicio-mensual');
            const opt = sel.options[sel.selectedIndex];
            const valor = parseFloat(opt?.dataset.valor || 0);
            const cant = parseInt(document.getElementById('input-cantidad-variable').value || 0, 10);
            const total = valor * cant;
            document.getElementById('texto-total-variable').textContent = total.toFixed(2);
        }
        document.getElementById('input-cantidad-variable').addEventListener('input', actualizarTotalVariable);

        document.getElementById('select-rodado').addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            document.getElementById('rodado-id').value = opt.value || '';
            document.getElementById('bloque-servicio-a-pagar').classList.add('hidden');
            document.getElementById('bloque-monto-vehiculo').classList.add('hidden');
            if (!opt.value) return;
            document.getElementById('bloque-servicio-a-pagar').classList.remove('hidden');
            document.getElementById('bloque-monto-vehiculo').classList.remove('hidden');
            const tieneProveedor = opt.dataset.proveedor === '1';
            const opcs = tieneProveedor ? OPCS_CON_PROVEEDOR : OPCS_SIN_PROVEEDOR;
            const sel = document.getElementById('select-tipo-servicio-vehiculo');
            sel.innerHTML = '<option value="">Seleccionar...</option>' + opcs.map(o => `<option value="${o.v}">${o.t}</option>`).join('');
            document.getElementById('bloque-tipo-otro').classList.add('hidden');
        });

        document.getElementById('select-tipo-servicio-vehiculo').addEventListener('change', function() {
            const v = this.value;
            document.getElementById('tipo-servicio').value = v || 'pagos_adicionales';
            if (v === 'pagos_adicionales') {
                document.getElementById('bloque-tipo-otro').classList.remove('hidden');
            } else {
                document.getElementById('bloque-tipo-otro').classList.add('hidden');
            }
            if (v) {
                document.getElementById('bloque-estado-pago').classList.remove('hidden');
            } else {
                document.getElementById('bloque-estado-pago').classList.add('hidden');
                document.getElementById('bloque-ya-pagado').classList.add('hidden');
                document.getElementById('bloque-a-pagar').classList.add('hidden');
                document.getElementById('bloque-factura').classList.add('hidden');
            }
        });

        document.getElementById('btn-ya-pagado').addEventListener('click', function() {
            document.getElementById('estado-pago').value = 'pagado';
            document.getElementById('bloque-ya-pagado').classList.remove('hidden');
            document.getElementById('bloque-a-pagar').classList.add('hidden');
            document.getElementById('bloque-factura').classList.remove('hidden');
            document.getElementById('input-fecha-vencimiento').removeAttribute('required');
            document.getElementById('input-fecha-pago').setAttribute('required', 'required');
            this.classList.add('bg-blue-600', 'text-white');
            this.classList.remove('text-gray-500');
            document.getElementById('btn-a-pagar').classList.remove('bg-blue-600', 'text-white');
            document.getElementById('btn-a-pagar').classList.add('text-gray-500');
        });

        document.getElementById('btn-a-pagar').addEventListener('click', function() {
            document.getElementById('estado-pago').value = 'pendiente';
            document.getElementById('bloque-ya-pagado').classList.add('hidden');
            document.getElementById('bloque-a-pagar').classList.remove('hidden');
            document.getElementById('bloque-factura').classList.remove('hidden');
            document.getElementById('input-fecha-pago').removeAttribute('required');
            document.getElementById('input-fecha-vencimiento').setAttribute('required', 'required');
            this.classList.add('bg-blue-600', 'text-white');
            this.classList.remove('text-gray-500');
            document.getElementById('btn-ya-pagado').classList.remove('bg-blue-600', 'text-white');
            document.getElementById('btn-ya-pagado').classList.add('text-gray-500');
        });

        document.getElementById('form-nuevo-pago').addEventListener('submit', function(e) {
            const tipoPago = document.getElementById('tipo-pago').value;
            document.getElementById('tipo-servicio').setAttribute('name', 'tipo');
            this.querySelectorAll('input[data-variable]').forEach(el => el.remove());
            this.querySelectorAll('input[name="observaciones"]').forEach(el => el.remove());

            if (tipoPago === 'servicio_mensual') {
                document.getElementById('tipo-servicio').value = 'pagos_adicionales';
                document.getElementById('servicio-usuario-id').setAttribute('name', 'servicio_usuario_id');
                document.getElementById('rodado-id').removeAttribute('name');
                document.getElementById('select-moneda-servicio').setAttribute('name', 'moneda');
                document.getElementById('select-moneda-vehiculo').removeAttribute('name');
                document.getElementById('input-monto-vehiculo').removeAttribute('name');
                const sel = document.getElementById('select-servicio-mensual');
                const opt = sel.options[sel.selectedIndex];
                if (opt && opt.dataset.tipo === 'variable') {
                    const valor = parseFloat(opt.dataset.valor || 0);
                    const cant = parseInt(document.getElementById('input-cantidad-variable').value || 0, 10);
                    document.getElementById('input-monto-servicio').setAttribute('name', 'monto');
                    document.getElementById('input-monto-servicio').value = valor * cant;
                } else {
                    document.getElementById('input-monto-servicio').setAttribute('name', 'monto');
                }
            } else {
                document.getElementById('rodado-id').setAttribute('name', 'rodado_id');
                document.getElementById('servicio-usuario-id').removeAttribute('name');
                document.getElementById('select-moneda-vehiculo').setAttribute('name', 'moneda');
                document.getElementById('select-moneda-servicio').removeAttribute('name');
                document.getElementById('input-monto-vehiculo').setAttribute('name', 'monto');
                document.getElementById('input-monto-servicio').removeAttribute('name');
                document.getElementById('tipo-servicio').value = document.getElementById('select-tipo-servicio-vehiculo').value || 'pagos_adicionales';
                const tipoServ = document.getElementById('select-tipo-servicio-vehiculo').value;
                const otro = document.getElementById('input-tipo-otro').value;
                if (tipoServ === 'pagos_adicionales' && otro) {
                    const obs = document.createElement('input');
                    obs.type = 'hidden';
                    obs.name = 'observaciones';
                    obs.value = otro;
                    this.appendChild(obs);
                }
            }

            const estado = document.getElementById('estado-pago').value;
            document.getElementById('estado-pago').setAttribute('name', 'estado');
            if (estado === 'pagado') {
                document.getElementById('input-fecha-pago').setAttribute('name', 'fecha_pago');
                document.getElementById('input-fecha-vencimiento').removeAttribute('name');
            } else {
                document.getElementById('input-fecha-vencimiento').setAttribute('name', 'fecha_vencimiento');
                document.getElementById('input-fecha-pago').removeAttribute('name');
            }
        });

        // === Toast notifications ===
        function dismissToast(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.remove('toast-enter');
                el.classList.add('toast-exit');
                setTimeout(() => el.remove(), 300);
            }
        }
        document.querySelectorAll('[id^="toast-"]').forEach(toast => {
            setTimeout(() => dismissToast(toast.id), 4000);
        });
    </script>
    @endpush

    <style>
        .animate-in { animation: fadeSlideIn 0.3s ease-out; }
        @keyframes fadeSlideIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        .modal-backdrop { animation: modalFadeIn 0.2s ease-out; }
        @keyframes modalFadeIn { from { opacity: 0; } to { opacity: 1; } }
        .modal-content { animation: modalSlideIn 0.25s ease-out; }
        @keyframes modalSlideIn { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        .modal-scroll::-webkit-scrollbar { width: 6px; }
        .modal-scroll::-webkit-scrollbar-track { background: transparent; }
        .modal-scroll::-webkit-scrollbar-thumb { background: rgba(63, 63, 70, 0.5); border-radius: 3px; }
        .modal-scroll { scrollbar-width: thin; scrollbar-color: rgba(63, 63, 70, 0.5) transparent; }
        @media (max-width: 768px) { .modal-backdrop { padding-left: 1rem !important; padding-top: 1rem !important; } }

        .toast-enter { animation: toastSlideIn 0.4s cubic-bezier(0.21, 1.02, 0.73, 1) forwards; }
        .toast-exit { animation: toastSlideOut 0.3s ease-in forwards; }
        @keyframes toastSlideIn { from { opacity: 0; transform: translateX(100%) scale(0.95); } to { opacity: 1; transform: translateX(0) scale(1); } }
        @keyframes toastSlideOut { from { opacity: 1; transform: translateX(0) scale(1); } to { opacity: 0; transform: translateX(100%) scale(0.95); } }
        .toast-progress { animation: toastProgress 4s linear forwards; }
        @keyframes toastProgress { from { width: 100%; } to { width: 0%; } }
    </style>
</x-administrative-layout>
