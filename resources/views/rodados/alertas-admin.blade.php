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
                        <div class="p-3 bg-gradient-to-br from-amber-600/20 to-amber-400/10 rounded-xl border border-amber-500/20">
                            <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-100 tracking-tight">Alertas</h1>
                            <p class="text-sm text-gray-400 mt-0.5">Crea alertas y recordatorios para el dashboard, notificaciones y correo</p>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="flex flex-wrap gap-3">
                        @php
                            $activas = $alertas->where('activa', true)->count();
                            $inactivas = $alertas->where('activa', false)->count();
                        @endphp
                        <div class="flex items-center gap-2 px-4 py-2 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                            <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                            <span class="text-xs text-gray-400">Total</span>
                            <span class="text-sm font-semibold text-gray-200">{{ $alertas->count() }}</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                            <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                            <span class="text-xs text-gray-400">Activas</span>
                            <span class="text-sm font-semibold text-gray-200">{{ $activas }}</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                            <div class="w-2 h-2 rounded-full bg-zinc-500"></div>
                            <span class="text-xs text-gray-400">Inactivas</span>
                            <span class="text-sm font-semibold text-gray-200">{{ $inactivas }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Banner -->
            <div class="mb-5 p-4 bg-amber-500/5 border border-amber-500/15 rounded-xl">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                    <div class="text-xs text-gray-400 leading-relaxed">
                        <span class="text-amber-400 font-medium">Nota:</span> Los pagos pendientes con fecha de vencimiento y los turnos programados (7 dias de anticipacion) se muestran automaticamente en el dashboard sin necesidad de crear alertas.
                    </div>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                <div class="relative">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" id="busqueda-alerta" placeholder="Buscar alerta..."
                        class="pl-10 pr-4 py-2 bg-zinc-800 border border-zinc-700 rounded-xl text-sm text-gray-200 placeholder-gray-500 focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 w-72 transition-all"
                        oninput="buscarAlerta(this.value)">
                </div>
                <button onclick="abrirNuevaAlerta()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 rounded-xl text-sm font-medium text-white shadow-lg shadow-amber-600/20 hover:-translate-y-0.5 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nueva Alerta
                </button>
            </div>

            <!-- Alertas List -->
            <div class="bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50 overflow-hidden shadow-xl">
                <div class="divide-y divide-zinc-700/30">
                    @forelse($alertas as $alerta)
                    @php
                        $color = $alerta->tipo_color;
                    @endphp
                    <div class="alerta-row group p-5 hover:bg-zinc-700/30 transition-colors duration-150 {{ !$alerta->activa ? 'opacity-50' : '' }}"
                        data-search="{{ strtolower($alerta->titulo . ' ' . ($alerta->descripcion ?? '') . ' ' . $alerta->tipo . ' ' . ($alerta->rodado?->patente ?? '') . ' ' . ($alerta->cliente?->nombre ?? '') . ' ' . ($alerta->servicioUsuario?->nombre ?? '')) }}">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-4 flex-1 min-w-0">
                                <!-- Icon -->
                                <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center bg-gradient-to-br from-{{ $color }}-600/20 to-{{ $color }}-400/10 border border-{{ $color }}-500/20">
                                    @if(in_array($alerta->tipo, ['pago_servicio', 'vencimiento_pago']))
                                        <svg class="w-5 h-5 text-{{ $color }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                                    @elseif($alerta->tipo === 'cobro_cliente')
                                        <svg class="w-5 h-5 text-{{ $color }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @elseif(in_array($alerta->tipo, ['km_vehiculo', 'agendar_turno_km']))
                                        <svg class="w-5 h-5 text-{{ $color }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                                    @elseif(in_array($alerta->tipo, ['vencimiento', 'recordatorio_turno']))
                                        <svg class="w-5 h-5 text-{{ $color }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                    @else
                                        <svg class="w-5 h-5 text-{{ $color }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2.5 flex-wrap">
                                        <h3 class="text-sm font-semibold text-gray-100">{{ $alerta->titulo }}</h3>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-medium bg-{{ $color }}-500/10 text-{{ $color }}-400 border border-{{ $color }}-500/20">
                                            <div class="w-1 h-1 rounded-full bg-{{ $color }}-400"></div>
                                            {{ $alerta->tipo_label }}
                                        </span>
                                        @if($alerta->recurrente)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-medium bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                                            {{ ucfirst($alerta->frecuencia_recurrencia ?? 'mensual') }}
                                        </span>
                                        @endif
                                        @if($alerta->destinatario_tipo && $alerta->destinatario_tipo !== 'admin')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-medium bg-violet-500/10 text-violet-400 border border-violet-500/20">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                                            {{ $alerta->destinatario_tipo === 'ambos' ? 'Admin + Cliente' : 'Cliente' }}
                                        </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1.5 line-clamp-1">{{ $alerta->descripcion ?? 'Sin descripcion' }}</p>
                                    <div class="flex items-center gap-3 mt-2 flex-wrap">
                                        @if($alerta->servicioUsuario)
                                            <span class="inline-flex items-center gap-1.5 text-xs text-gray-400">
                                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                                                {{ $alerta->servicioUsuario->nombre }}
                                            </span>
                                        @endif
                                        @if($alerta->rodado)
                                            <span class="inline-flex items-center gap-1.5 text-xs text-gray-400">
                                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                                                {{ $alerta->rodado->patente ?? $alerta->rodado->display_name }}
                                            </span>
                                        @endif
                                        @if($alerta->cliente)
                                            <span class="inline-flex items-center gap-1.5 text-xs text-gray-400">
                                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                                {{ $alerta->cliente->nombre }}
                                            </span>
                                        @endif
                                        @if($alerta->km_intervalo)
                                            <span class="inline-flex items-center gap-1.5 text-xs text-gray-400">
                                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                                                Cada {{ number_format($alerta->km_intervalo) }} km
                                            </span>
                                        @endif
                                        @if($alerta->dia_mes)
                                            <span class="inline-flex items-center gap-1.5 text-xs text-gray-400">
                                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                                Dia {{ $alerta->dia_mes }} de cada mes
                                            </span>
                                        @endif
                                        @if($alerta->fecha_alerta)
                                            <span class="inline-flex items-center gap-1.5 text-xs text-gray-400">
                                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                                {{ $alerta->fecha_alerta->format('d/m/Y') }}
                                            </span>
                                        @endif
                                        @if($alerta->taller)
                                            <span class="inline-flex items-center gap-1.5 text-xs text-gray-400">
                                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.03-2.51M11.42 15.17l5.03-2.51M11.42 15.17V20.5M2.39 8.12l7.91 3.96m0 0l7.91-3.96M10.3 12.08V6.53l-7.91 3.96v5.54l7.91 3.96 7.91-3.96V10.5l-7.91-3.97z"/></svg>
                                                {{ $alerta->taller->nombre }}
                                                @if($alerta->taller->whatsapp)
                                                    <a href="{{ $alerta->taller->whatsapp_link }}" target="_blank" class="text-green-400 hover:text-green-300" title="WhatsApp">
                                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492l4.624-1.467A11.96 11.96 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75c-2.115 0-4.142-.657-5.855-1.896l-.42-.293-2.744.87.879-2.67-.32-.46A9.723 9.723 0 012.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75z"/></svg>
                                                    </a>
                                                @endif
                                            </span>
                                        @endif
                                        @if($alerta->destinatarioUser)
                                            <span class="inline-flex items-center gap-1.5 text-xs text-violet-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                                                Para: {{ $alerta->destinatarioUser->name }}
                                            </span>
                                        @endif
                                        <span class="inline-flex items-center gap-1.5 text-xs text-gray-500">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ implode(', ', array_map('ucfirst', $alerta->getAcciones())) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-1.5 shrink-0">
                                <form action="{{ route('rodados.alertas-admin.toggle', $alerta) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-150
                                        {{ $alerta->activa
                                            ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20'
                                            : 'bg-zinc-700/50 text-gray-500 border border-zinc-600/30 hover:bg-zinc-700' }}"
                                        title="{{ $alerta->activa ? 'Desactivar' : 'Activar' }}">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $alerta->activa ? 'bg-emerald-400' : 'bg-gray-500' }}"></div>
                                        {{ $alerta->activa ? 'Activa' : 'Inactiva' }}
                                    </button>
                                </form>
                                <form action="{{ route('rodados.alertas-admin.destroy', $alerta) }}" method="POST" class="inline" onsubmit="return confirm('Eliminar esta alerta?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-red-400 hover:bg-red-500/10 transition-all duration-150" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-zinc-700/30 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                            </div>
                            <p class="text-gray-400 font-medium">No hay alertas creadas</p>
                            <p class="text-gray-600 text-sm mt-1">Comienza creando tu primera alerta</p>
                            <button onclick="abrirNuevaAlerta()" class="mt-4 px-4 py-2 bg-amber-600 hover:bg-amber-700 rounded-lg text-sm text-white transition">+ Nueva Alerta</button>
                        </div>
                    </div>
                    @endforelse
                </div>
                @if($alertas->count() > 0)
                <div class="px-5 py-3 bg-zinc-900/30 border-t border-zinc-700/30 flex items-center justify-between">
                    <span class="text-xs text-gray-500" id="alertas-count-text">Mostrando {{ $alertas->count() }} alertas</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- MODAL NUEVA ALERTA (Redesigned)              -->
    <!-- ============================================ -->
    <div id="modal-alerta" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center modal-backdrop" style="padding-left: calc(var(--fb-sidebar-width, 240px) + 1rem); padding-top: calc(var(--fb-topbar-height, 60px) + 1.5rem); padding-right: 1rem; padding-bottom: 1rem;" onclick="if(event.target === this) cerrarModal()">
        <div class="w-full max-w-2xl bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden flex flex-col modal-content" style="max-height: calc(100vh - var(--fb-topbar-height, 60px) - 4rem);" onclick="event.stopPropagation()">
            <form method="POST" action="{{ route('rodados.alertas-admin.store') }}" class="flex flex-col overflow-hidden" id="form-alerta">
                @csrf

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-amber-600/10 rounded-lg">
                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-100">Nueva Alerta</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Configura el tipo, trigger y acciones de la alerta</p>
                        </div>
                    </div>
                    <button type="button" onclick="cerrarModal()" class="p-2 text-gray-500 hover:text-gray-300 hover:bg-zinc-800 rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Body (scrollable) -->
                <div class="px-6 py-5 overflow-y-auto flex-1 space-y-6 modal-scroll">

                    <!-- ========== SECTION 1: TIPO DE ALERTA ========== -->
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-3 uppercase tracking-wider">Tipo de Alerta *</label>
                        <div class="grid grid-cols-2 sm:grid-cols-2 gap-2" id="tipo-selector">
                            <button type="button" onclick="seleccionarTipo('vencimiento')"
                                class="tipo-btn flex flex-col items-center gap-2 p-3 rounded-xl border border-zinc-700/50 bg-zinc-800/50 hover:bg-zinc-700/50 transition-all text-center group"
                                data-tipo="vencimiento">
                                <div class="w-9 h-9 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center group-hover:scale-105 transition-transform">
                                    <svg class="w-4.5 h-4.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                </div>
                                <span class="text-[11px] font-medium text-gray-400 leading-tight">Recordatorio / Vencimiento</span>
                            </button>
                            <button type="button" onclick="seleccionarTipo('cobro_cliente')"
                                class="tipo-btn flex flex-col items-center gap-2 p-3 rounded-xl border border-zinc-700/50 bg-zinc-800/50 hover:bg-zinc-700/50 transition-all text-center group"
                                data-tipo="cobro_cliente">
                                <div class="w-9 h-9 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center group-hover:scale-105 transition-transform">
                                    <svg class="w-4.5 h-4.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <span class="text-[11px] font-medium text-gray-400 leading-tight">Cobro a Cliente</span>
                            </button>
                            <button type="button" onclick="seleccionarTipo('km_vehiculo')"
                                class="tipo-btn flex flex-col items-center gap-2 p-3 rounded-xl border border-zinc-700/50 bg-zinc-800/50 hover:bg-zinc-700/50 transition-all text-center group"
                                data-tipo="km_vehiculo">
                                <div class="w-9 h-9 rounded-lg bg-orange-500/10 border border-orange-500/20 flex items-center justify-center group-hover:scale-105 transition-transform">
                                    <svg class="w-4.5 h-4.5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                                </div>
                                <span class="text-[11px] font-medium text-gray-400 leading-tight">Control por KM</span>
                            </button>
                            <button type="button" onclick="seleccionarTipo('personalizada')"
                                class="tipo-btn flex flex-col items-center gap-2 p-3 rounded-xl border border-zinc-700/50 bg-zinc-800/50 hover:bg-zinc-700/50 transition-all text-center group"
                                data-tipo="personalizada">
                                <div class="w-9 h-9 rounded-lg bg-purple-500/10 border border-purple-500/20 flex items-center justify-center group-hover:scale-105 transition-transform">
                                    <svg class="w-4.5 h-4.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                                </div>
                                <span class="text-[11px] font-medium text-gray-400 leading-tight">Personalizada</span>
                            </button>
                        </div>
                        <input type="hidden" name="tipo" id="tipo-alerta" required>
                    </div>

                    <!-- ========== SECTION 2: CONFIGURACION (Dynamic) ========== -->
                    <div id="config-section" class="hidden space-y-5 animate-in">
                        <div class="h-px bg-zinc-800"></div>

                        <!-- Titulo -->
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Titulo *</label>
                            <input type="text" name="titulo" id="input-titulo" required class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all" placeholder="Nombre de la alerta">
                        </div>

                        <!-- Descripcion -->
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Descripcion</label>
                            <textarea name="descripcion" rows="2" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all" placeholder="Detalle opcional de la alerta"></textarea>
                        </div>

                        <!-- === COBRO CLIENTE: Cliente + Servicio + Dia del mes + Anticipacion === -->
                        <div id="field-cobro-cliente" class="hidden space-y-3">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-2">
                                    <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Cliente a cobrar *</label>
                                    <select name="cliente_id" id="select-cliente" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all">
                                        <option value="">Seleccionar cliente...</option>
                                        @foreach($clientes as $cliente)
                                            <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Dia del mes *</label>
                                    <input type="number" name="dia_mes" id="input-dia-mes" min="1" max="31" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all" placeholder="1-31">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Servicio mensual a cobrar</label>
                                <select name="servicio_usuario_id" id="select-servicio-cobro" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all" onchange="updateServicioCobro(this)">
                                    <option value="">Sin servicio vinculado</option>
                                    @foreach(\App\Models\ServicioUsuario::activos()->orderBy('nombre')->get() as $servicio)
                                        <option value="{{ $servicio->id }}" data-monto="{{ $servicio->valor_unitario }}" data-moneda="{{ $servicio->moneda }}" data-tipo="{{ $servicio->tipo_calculo }}">
                                            {{ $servicio->nombre }} ({{ $servicio->moneda }} ${{ number_format($servicio->valor_unitario, 2, ',', '.') }} - {{ ucfirst($servicio->tipo_calculo) }})
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-[10px] text-gray-600 mt-1">Seleccione el servicio mensual para incluir el monto en alertas y notificaciones</p>
                                <!-- Quantity field for variable services -->
                                <div id="cobro-cantidad-container" class="hidden mt-3">
                                    <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Cantidad *</label>
                                    <input type="number" name="cobro_cantidad" id="input-cobro-cantidad" min="1" value="1"
                                        class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all"
                                        placeholder="Cantidad de unidades" oninput="recalcularCobroTotal()">
                                    <p class="text-[10px] text-gray-600 mt-1">Servicio de tipo variable: ingrese la cantidad para calcular el total a cobrar</p>
                                </div>
                                <div id="servicio-cobro-info" class="hidden mt-2 p-2.5 bg-emerald-500/5 border border-emerald-500/20 rounded-xl">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-xs text-emerald-300" id="servicio-cobro-monto-text"></span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Avisar con anticipacion</label>
                                <input type="number" name="dias_anticipacion" id="input-dias-anticip-cobro" min="1" max="30" value="3" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all" placeholder="3">
                                <p class="text-[10px] text-gray-600 mt-1">Se recordara cobrar al cliente X dias antes del dia indicado, todos los meses</p>
                            </div>
                        </div>

                        <!-- === KM VEHICULO: Vehiculo + KM intervalo + Taller === -->
                        <div id="field-km" class="hidden space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Vehiculo</label>
                                    <select name="rodado_id" id="select-rodado-km" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all" onchange="onVehiculoChange(this.value)">
                                        <option value="">Seleccionar vehiculo...</option>
                                        <option value="all">Todos los vehiculos</option>
                                        @foreach($rodados as $rodado)
                                            <option value="{{ $rodado->id }}"
                                                data-proveedor="{{ $rodado->proveedor_id ?? '' }}"
                                                data-proveedor-nombre="{{ $rodado->proveedor?->nombre ?? '' }}"
                                                data-cliente="{{ $rodado->cliente_id ?? '' }}">
                                                {{ $rodado->patente ?? $rodado->display_name }} {{ $rodado->cliente ? '(' . $rodado->cliente->nombre . ')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Intervalo KM *</label>
                                    <input type="number" name="km_intervalo" id="input-km" min="1" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all" placeholder="Ej: 10000">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Km de anticipacion</label>
                                <input type="number" name="dias_anticipacion" min="1" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all" placeholder="Ej: 500 (avisar 500 km antes)" value="500">
                                <p class="text-[10px] text-gray-600 mt-1">Se disparara la alerta cuando falten estos km para el proximo intervalo</p>
                            </div>

                            <!-- Taller info / selector -->
                            <div id="taller-info-box" class="hidden">
                                <div id="taller-proveedor-info" class="hidden p-3 bg-zinc-800/70 rounded-xl border border-zinc-700/50">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Taller del proveedor</p>
                                            <p class="text-sm text-gray-200 mt-1" id="taller-proveedor-nombre"></p>
                                        </div>
                                        <a href="#" id="taller-wpp-link" target="_blank" class="hidden inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-500/10 text-green-400 border border-green-500/20 rounded-lg text-xs font-medium hover:bg-green-500/20 transition-all">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                                            WhatsApp
                                        </a>
                                    </div>
                                </div>
                                <div id="taller-selector" class="hidden">
                                    <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Taller para service</label>
                                    <select name="taller_id" id="select-taller" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all">
                                        <option value="">Seleccionar taller...</option>
                                        @foreach($talleres as $taller)
                                            <option value="{{ $taller->id }}" data-wpp="{{ $taller->whatsapp_link ?? '' }}">{{ $taller->nombre }} {{ $taller->proveedor ? '(' . $taller->proveedor->nombre . ')' : '' }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-[10px] text-gray-600 mt-1">El vehiculo no tiene proveedor asignado. Selecciona un taller manualmente.</p>
                                </div>
                            </div>
                        </div>

                        <!-- === VENCIMIENTO / PAGO SERVICIO: Fecha + Anticipacion + Servicio opcional + Recurrencia === -->
                        <div id="field-vencimiento" class="hidden space-y-4">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-2">
                                    <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Fecha limite / vencimiento *</label>
                                    <input type="date" name="fecha_alerta" id="input-fecha" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all">
                                    <p class="text-[10px] text-gray-600 mt-1">Fecha en la que vence o se debe cumplir</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Avisar antes</label>
                                    <input type="number" name="dias_anticipacion" id="input-dias-anticipacion" min="1" max="30" value="3" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all" placeholder="3">
                                    <p class="text-[10px] text-gray-600 mt-1">Dias antes</p>
                                </div>
                            </div>
                            <!-- Servicio vinculado (opcional) -->
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Servicio vinculado <span class="text-gray-600 normal-case">(opcional)</span></label>
                                <select name="servicio_usuario_id" id="select-servicio" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all" onchange="onServicioChange()">
                                    <option value="">Sin servicio vinculado</option>
                                    @foreach($servicios as $servicio)
                                        <option value="{{ $servicio->id }}" data-nombre="{{ $servicio->nombre }}">{{ $servicio->nombre }} ({{ $servicio->moneda }} {{ number_format($servicio->valor_unitario, 2) }})</option>
                                    @endforeach
                                </select>
                                <p class="text-[10px] text-gray-600 mt-1">Si vinculas un servicio mensual, la alerta se marcara como recurrente automaticamente</p>
                            </div>
                            <!-- Recurrencia -->
                            <div class="flex items-center gap-4 p-3 bg-zinc-800/50 rounded-xl border border-zinc-700/50" id="recurrencia-box">
                                <label class="flex items-center gap-2.5 cursor-pointer">
                                    <input type="checkbox" name="recurrente" value="1" id="check-recurrente" class="w-4 h-4 rounded bg-zinc-700 border-zinc-600 text-amber-600 focus:ring-amber-500/30" onchange="toggleRecurrencia()">
                                    <span class="text-sm text-gray-300">Alerta recurrente</span>
                                </label>
                                <span id="recurrencia-auto-badge" class="hidden text-[10px] font-medium text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 rounded-lg">Auto (servicio mensual)</span>
                            </div>
                            <div id="field-frecuencia" class="hidden">
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Frecuencia</label>
                                <select name="frecuencia_recurrencia" id="select-frecuencia" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all">
                                    <option value="diaria">Diaria</option>
                                    <option value="semanal">Semanal</option>
                                    <option value="mensual" selected>Mensual</option>
                                </select>
                            </div>
                        </div>

                        <!-- === PERSONALIZADA: Vehiculo, Cliente, Fecha, Recurrencia (all optional) === -->
                        <div id="field-personalizada" class="hidden space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Vehiculo</label>
                                    <select name="rodado_id" id="select-rodado-custom" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all">
                                        <option value="">Ninguno</option>
                                        @foreach($rodados as $rodado)
                                            <option value="{{ $rodado->id }}">{{ $rodado->patente ?? $rodado->display_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Cliente</label>
                                    <select name="cliente_id" id="select-cliente-custom" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all">
                                        <option value="">Ninguno</option>
                                        @foreach($clientes as $cliente)
                                            <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Fecha</label>
                                <input type="date" name="fecha_alerta" id="input-fecha-custom" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all">
                            </div>
                            <div class="flex items-center gap-4 p-3 bg-zinc-800/50 rounded-xl border border-zinc-700/50">
                                <label class="flex items-center gap-2.5 cursor-pointer">
                                    <input type="checkbox" name="recurrente" value="1" id="check-recurrente-custom" class="w-4 h-4 rounded bg-zinc-700 border-zinc-600 text-amber-600 focus:ring-amber-500/30" onchange="toggleRecurrenciaCustom()">
                                    <span class="text-sm text-gray-300">Alerta recurrente</span>
                                </label>
                            </div>
                            <div id="field-frecuencia-custom" class="hidden">
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Frecuencia</label>
                                <select name="frecuencia_recurrencia" id="select-frecuencia-custom" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all">
                                    <option value="diaria">Diaria</option>
                                    <option value="semanal">Semanal</option>
                                    <option value="mensual" selected>Mensual</option>
                                </select>
                            </div>
                        </div>

                        <!-- ========== SECTION 3: DESTINATARIOS ========== -->
                        <div class="h-px bg-zinc-800"></div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-3 uppercase tracking-wider">Destinatario</label>
                            <div class="grid grid-cols-3 gap-2" id="destinatario-selector">
                                <button type="button" onclick="seleccionarDestinatario('admin')"
                                    class="dest-btn flex items-center gap-2 p-2.5 rounded-xl border border-amber-500/30 bg-amber-500/5 text-amber-400 transition-all text-center justify-center"
                                    data-dest="admin">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                    <span class="text-xs font-medium">Solo para mi</span>
                                </button>
                                <button type="button" onclick="seleccionarDestinatario('cliente')"
                                    class="dest-btn flex items-center gap-2 p-2.5 rounded-xl border border-zinc-700/50 bg-zinc-800/50 hover:bg-zinc-700/50 text-gray-400 transition-all text-center justify-center"
                                    data-dest="cliente">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                                    <span class="text-xs font-medium">Para un cliente</span>
                                </button>
                                <button type="button" onclick="seleccionarDestinatario('ambos')"
                                    class="dest-btn flex items-center gap-2 p-2.5 rounded-xl border border-zinc-700/50 bg-zinc-800/50 hover:bg-zinc-700/50 text-gray-400 transition-all text-center justify-center"
                                    data-dest="ambos">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                                    <span class="text-xs font-medium">Ambos</span>
                                </button>
                            </div>
                            <input type="hidden" name="destinatario_tipo" id="input-destinatario-tipo" value="admin">

                            <!-- Client user selector -->
                            <div id="field-destinatario-user" class="hidden mt-3">
                                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Usuario cliente *</label>
                                <select name="destinatario_user_id" id="select-destinatario-user" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all">
                                    <option value="">Seleccionar usuario...</option>
                                    @foreach($usuariosClientes as $uc)
                                        <option value="{{ $uc->id }}">{{ $uc->name }} ({{ $uc->email }}) {{ $uc->clientes->pluck('nombre')->join(', ') }}</option>
                                    @endforeach
                                </select>
                                <p class="text-[10px] text-gray-600 mt-1">La alerta se mostrara en el dashboard y/o notificaciones del usuario seleccionado</p>
                            </div>
                        </div>

                        <!-- ========== SECTION 4: ACCIONES ========== -->
                        <div class="h-px bg-zinc-800"></div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Acciones al dispararse *</label>
                            <div class="space-y-2 p-3 bg-zinc-800/50 rounded-xl border border-zinc-700/50">
                                <label class="flex items-center gap-3 cursor-pointer p-2 rounded-lg hover:bg-zinc-700/30 transition-colors">
                                    <input type="checkbox" name="acciones[]" value="dashboard" checked class="w-4 h-4 rounded bg-zinc-700 border-zinc-600 text-amber-600 focus:ring-amber-500/30">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                                        <div>
                                            <span class="text-sm text-gray-300">Mostrar en Dashboard</span>
                                            <p class="text-[10px] text-gray-600">Aparecera como alerta visible en el panel principal</p>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer p-2 rounded-lg hover:bg-zinc-700/30 transition-colors">
                                    <input type="checkbox" name="acciones[]" value="notificacion" class="w-4 h-4 rounded bg-zinc-700 border-zinc-600 text-amber-600 focus:ring-amber-500/30">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                                        <div>
                                            <span class="text-sm text-gray-300">Notificacion del Sistema</span>
                                            <p class="text-[10px] text-gray-600">Se mostrara en el modal de notificaciones</p>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer p-2 rounded-lg hover:bg-zinc-700/30 transition-colors">
                                    <input type="checkbox" name="acciones[]" value="correo" class="w-4 h-4 rounded bg-zinc-700 border-zinc-600 text-amber-600 focus:ring-amber-500/30">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                                        <div>
                                            <span class="text-sm text-gray-300">Enviar Correo Electronico</span>
                                            <p class="text-[10px] text-gray-600">Se enviara un email con todos los datos de la alerta</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-zinc-800 bg-zinc-900/50 shrink-0">
                    <button type="button" onclick="cerrarModal()" class="px-4 py-2.5 text-sm font-medium text-gray-400 hover:text-gray-200 bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 rounded-xl transition-all">Cancelar</button>
                    <button type="submit" id="btn-crear" class="px-5 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-lg shadow-amber-600/20 hover:-translate-y-0.5 transition-all flex items-center gap-2 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:translate-y-0" disabled>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Crear Alerta
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // ==============================
        // Data from backend
        // ==============================
        const rodadosData = @json($rodadosJson);

        let currentTipo = null;
        let currentDestinatario = 'admin';

        // ==============================
        // SERVICE COBRO SELECTOR
        // ==============================
        // Store current service data for total calculation
        let currentCobroServicio = { monto: 0, moneda: 'ARS', tipo: '' };

        function updateServicioCobro(select) {
            const opt = select.options[select.selectedIndex];
            const info = document.getElementById('servicio-cobro-info');
            const text = document.getElementById('servicio-cobro-monto-text');
            const cantidadContainer = document.getElementById('cobro-cantidad-container');
            const cantidadInput = document.getElementById('input-cobro-cantidad');

            if (opt && opt.value) {
                const monto = parseFloat(opt.dataset.monto || '0');
                const moneda = opt.dataset.moneda || 'ARS';
                const tipo = (opt.dataset.tipo || '').toLowerCase();

                currentCobroServicio = { monto, moneda, tipo };

                if (tipo === 'variable') {
                    // Show quantity input for variable services
                    cantidadContainer.classList.remove('hidden');
                    cantidadInput.disabled = false;
                    if (!cantidadInput.value || cantidadInput.value === '0') {
                        cantidadInput.value = 1;
                    }
                    recalcularCobroTotal();
                } else {
                    // Fixed service: hide quantity, show direct amount
                    cantidadContainer.classList.add('hidden');
                    cantidadInput.disabled = true;
                    text.textContent = `Monto fijo: ${moneda} $${monto.toLocaleString('es-AR', {minimumFractionDigits: 2})}`;
                    info.classList.remove('hidden');
                }
            } else {
                // No service selected
                info.classList.add('hidden');
                cantidadContainer.classList.add('hidden');
                cantidadInput.disabled = true;
                currentCobroServicio = { monto: 0, moneda: 'ARS', tipo: '' };
            }
        }

        function recalcularCobroTotal() {
            const info = document.getElementById('servicio-cobro-info');
            const text = document.getElementById('servicio-cobro-monto-text');
            const cantidad = parseInt(document.getElementById('input-cobro-cantidad').value) || 1;
            const { monto, moneda } = currentCobroServicio;
            const total = monto * cantidad;

            text.textContent = `${moneda} $${monto.toLocaleString('es-AR', {minimumFractionDigits: 2})} × ${cantidad} = Total: ${moneda} $${total.toLocaleString('es-AR', {minimumFractionDigits: 2})}`;
            info.classList.remove('hidden');
        }

        // TIPO SELECTOR
        // ==============================
        function seleccionarTipo(tipo) {
            currentTipo = tipo;
            document.getElementById('tipo-alerta').value = tipo;

            // Update visual state
            document.querySelectorAll('.tipo-btn').forEach(btn => {
                const isActive = btn.dataset.tipo === tipo;
                btn.classList.toggle('border-amber-500/30', isActive);
                btn.classList.toggle('bg-amber-500/5', isActive);
                btn.classList.toggle('border-zinc-700/50', !isActive);
                btn.classList.toggle('bg-zinc-800/50', !isActive);
            });

            // Show config section
            document.getElementById('config-section').classList.remove('hidden');

            // Hide all type-specific fields first
            ['field-cobro-cliente', 'field-km', 'field-vencimiento', 'field-personalizada'].forEach(id => {
                document.getElementById(id).classList.add('hidden');
            });

            // Disable duplicate named fields to prevent conflicts
            disableDuplicateFields();

            // Show relevant fields
            switch (tipo) {
                case 'cobro_cliente':
                    document.getElementById('field-cobro-cliente').classList.remove('hidden');
                    enableFields('field-cobro-cliente');
                    break;
                case 'km_vehiculo':
                    document.getElementById('field-km').classList.remove('hidden');
                    enableFields('field-km');
                    break;
                case 'vencimiento':
                    document.getElementById('field-vencimiento').classList.remove('hidden');
                    enableFields('field-vencimiento');
                    break;
                case 'personalizada':
                    document.getElementById('field-personalizada').classList.remove('hidden');
                    enableFields('field-personalizada');
                    break;
            }

            // Auto-fill titulo suggestions
            autoFillTitulo(tipo);

            // Enable create button
            document.getElementById('btn-crear').disabled = false;
        }

        function disableDuplicateFields() {
            // Disable all select/input in hidden type sections to prevent name conflicts
            ['field-cobro-cliente', 'field-km', 'field-vencimiento', 'field-personalizada'].forEach(id => {
                const el = document.getElementById(id);
                el.querySelectorAll('select, input').forEach(input => {
                    input.disabled = true;
                });
            });
        }

        function enableFields(sectionId) {
            const el = document.getElementById(sectionId);
            el.querySelectorAll('select, input').forEach(input => {
                input.disabled = false;
            });
        }

        function autoFillTitulo(tipo) {
            const titulo = document.getElementById('input-titulo');
            if (titulo.value) return; // Don't override user input

            const suggestions = {
                'cobro_cliente': 'Recordatorio de cobro',
                'km_vehiculo': 'Control de kilometraje - Service',
                'vencimiento': 'Recordatorio',
                'personalizada': '',
            };
            titulo.placeholder = suggestions[tipo] || 'Nombre de la alerta';
        }

        // ==============================
        // DESTINATARIO SELECTOR
        // ==============================
        function seleccionarDestinatario(dest) {
            currentDestinatario = dest;
            document.getElementById('input-destinatario-tipo').value = dest;

            document.querySelectorAll('.dest-btn').forEach(btn => {
                const isActive = btn.dataset.dest === dest;
                if (isActive) {
                    btn.classList.add('border-amber-500/30', 'bg-amber-500/5', 'text-amber-400');
                    btn.classList.remove('border-zinc-700/50', 'bg-zinc-800/50', 'text-gray-400');
                } else {
                    btn.classList.remove('border-amber-500/30', 'bg-amber-500/5', 'text-amber-400');
                    btn.classList.add('border-zinc-700/50', 'bg-zinc-800/50', 'text-gray-400');
                }
            });

            const userField = document.getElementById('field-destinatario-user');
            if (dest === 'cliente' || dest === 'ambos') {
                userField.classList.remove('hidden');
            } else {
                userField.classList.add('hidden');
            }
        }

        // ==============================
        // VEHICULO CHANGE (for KM type)
        // ==============================
        function onVehiculoChange(rodadoId) {
            const infoBox = document.getElementById('taller-info-box');
            const proveedorInfo = document.getElementById('taller-proveedor-info');
            const tallerSelector = document.getElementById('taller-selector');

            if (!rodadoId) {
                infoBox.classList.add('hidden');
                return;
            }

            // "Todos los vehiculos" selected - hide taller section, clear taller
            if (rodadoId === 'all') {
                infoBox.classList.add('hidden');
                document.getElementById('select-taller').value = '';
                document.getElementById('select-taller').disabled = true;
                return;
            }

            const rodado = rodadosData.find(r => r.id == rodadoId);
            if (!rodado) {
                infoBox.classList.add('hidden');
                return;
            }

            infoBox.classList.remove('hidden');

            if (rodado.proveedor_id && rodado.talleres && rodado.talleres.length > 0) {
                // Vehicle has proveedor with talleres
                proveedorInfo.classList.remove('hidden');
                tallerSelector.classList.add('hidden');

                const taller = rodado.talleres[0];
                document.getElementById('taller-proveedor-nombre').textContent =
                    taller.nombre + (rodado.proveedor_nombre ? ' (' + rodado.proveedor_nombre + ')' : '');

                const wppLink = document.getElementById('taller-wpp-link');
                if (taller.whatsapp_link) {
                    wppLink.href = taller.whatsapp_link;
                    wppLink.classList.remove('hidden');
                } else {
                    wppLink.classList.add('hidden');
                }

                // Auto-set taller_id
                document.getElementById('select-taller').value = taller.id;
                document.getElementById('select-taller').disabled = true;
            } else {
                // No proveedor - show taller selector
                proveedorInfo.classList.add('hidden');
                tallerSelector.classList.remove('hidden');
                document.getElementById('select-taller').disabled = false;
            }
        }

        // ==============================
        // RECURRENCE TOGGLES
        // ==============================
        function toggleRecurrencia() {
            const checked = document.getElementById('check-recurrente').checked;
            document.getElementById('field-frecuencia').classList.toggle('hidden', !checked);
        }

        function toggleRecurrenciaCustom() {
            const checked = document.getElementById('check-recurrente-custom').checked;
            document.getElementById('field-frecuencia-custom').classList.toggle('hidden', !checked);
        }

        // ==============================
        // SERVICIO CHANGE (auto-recurrence + auto titulo)
        // ==============================
        function onServicioChange() {
            const select = document.getElementById('select-servicio');
            const selected = select.options[select.selectedIndex];
            const hasServicio = select.value !== '';
            const checkRecurrente = document.getElementById('check-recurrente');
            const badge = document.getElementById('recurrencia-auto-badge');
            const frecuenciaField = document.getElementById('field-frecuencia');
            const selectFrecuencia = document.getElementById('select-frecuencia');

            if (hasServicio) {
                // Auto-set recurrente + mensual
                checkRecurrente.checked = true;
                checkRecurrente.disabled = true;
                badge.classList.remove('hidden');
                frecuenciaField.classList.remove('hidden');
                selectFrecuencia.value = 'mensual';
                selectFrecuencia.disabled = true;

                // Auto-fill titulo
                const titulo = document.getElementById('input-titulo');
                if (selected && selected.dataset.nombre) {
                    if (!titulo.value || titulo.value.startsWith('Recordatorio pago') || titulo.value === '') {
                        titulo.value = 'Recordatorio pago: ' + selected.dataset.nombre;
                    }
                }
            } else {
                // Restore manual control
                checkRecurrente.disabled = false;
                badge.classList.add('hidden');
                selectFrecuencia.disabled = false;
                // Don't uncheck - let user decide
                if (!checkRecurrente.checked) {
                    frecuenciaField.classList.add('hidden');
                }
            }
        }

        // ==============================
        // FORM PRE-SUBMIT: clean up values & re-enable disabled fields
        // ==============================
        document.getElementById('form-alerta')?.addEventListener('submit', function() {
            const rodadoKm = document.getElementById('select-rodado-km');
            if (rodadoKm && rodadoKm.value === 'all') {
                rodadoKm.value = '';
            }

            // Re-enable any disabled fields so their values get submitted
            this.querySelectorAll('select:disabled, input:disabled').forEach(el => {
                el.disabled = false;
            });
        });

        // ==============================
        // MODAL OPEN/CLOSE
        // ==============================
        function abrirNuevaAlerta() {
            document.getElementById('modal-alerta').classList.remove('hidden');
            // Reset form
            document.getElementById('form-alerta').reset();
            currentTipo = null;
            currentDestinatario = 'admin';
            document.getElementById('config-section').classList.add('hidden');
            document.getElementById('btn-crear').disabled = true;
            document.getElementById('input-destinatario-tipo').value = 'admin';

            // Reset tipo buttons
            document.querySelectorAll('.tipo-btn').forEach(btn => {
                btn.classList.remove('border-amber-500/30', 'bg-amber-500/5');
                btn.classList.add('border-zinc-700/50', 'bg-zinc-800/50');
            });

            // Reset destinatario buttons
            seleccionarDestinatario('admin');

            // Hide conditional fields
            ['field-cobro-cliente', 'field-km', 'field-vencimiento', 'field-personalizada', 'field-destinatario-user'].forEach(id => {
                document.getElementById(id).classList.add('hidden');
            });

            // Reset servicio-linked auto-recurrence state
            const badge = document.getElementById('recurrencia-auto-badge');
            if (badge) badge.classList.add('hidden');
            const checkRec = document.getElementById('check-recurrente');
            if (checkRec) { checkRec.disabled = false; checkRec.checked = false; }
            const selFrec = document.getElementById('select-frecuencia');
            if (selFrec) selFrec.disabled = false;

            // Reset cobro cantidad/servicio state
            document.getElementById('cobro-cantidad-container').classList.add('hidden');
            document.getElementById('input-cobro-cantidad').value = 1;
            document.getElementById('input-cobro-cantidad').disabled = true;
            document.getElementById('servicio-cobro-info').classList.add('hidden');
            currentCobroServicio = { monto: 0, moneda: 'ARS', tipo: '' };

            // Disable all type-specific fields
            disableDuplicateFields();
        }

        function cerrarModal() {
            document.getElementById('modal-alerta').classList.add('hidden');
        }

        // ==============================
        // SEARCH
        // ==============================
        function buscarAlerta(query) {
            const q = query.toLowerCase().trim();
            let visible = 0;
            document.querySelectorAll('.alerta-row').forEach(row => {
                const match = !q || (row.dataset.search || '').includes(q);
                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            const total = document.querySelectorAll('.alerta-row').length;
            const el = document.getElementById('alertas-count-text');
            if (el) el.textContent = visible === total ? `Mostrando ${total} alertas` : `Mostrando ${visible} de ${total} alertas`;
        }

        // ==============================
        // TOAST NOTIFICATIONS
        // ==============================
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
