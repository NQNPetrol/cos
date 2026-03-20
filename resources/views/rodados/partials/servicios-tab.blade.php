<div>
    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
        <div class="flex items-center gap-2">
            <button onclick="toggleFiltrosServicios()" id="btn-filtros-servicios"
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
                <input type="text" id="busqueda-servicio" placeholder="Buscar servicio..."
                    class="pl-10 pr-4 py-2 bg-zinc-800 border border-zinc-700 rounded-xl text-sm text-gray-200 placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 w-64 transition-all"
                    oninput="buscarServicio(this.value)">
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="openCreateTurnoModal('turno_service')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-xl text-sm font-medium text-white shadow-lg shadow-blue-600/20 hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Service
            </button>
            <button onclick="openCreateTurnoModal('turno_mecanico')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 rounded-xl text-sm font-medium text-white shadow-lg shadow-amber-600/20 hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-4 h-4" viewBox="-0.5 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.3365 12.3022C8.94652 13.1922 7.10652 13.2022 5.70652 12.3222C4.17652 11.3622 3.48652 9.72224 3.61652 8.14224C3.65652 7.64224 4.25653 7.41224 4.60653 7.76224L6.27653 9.44225C6.49653 9.67225 6.86653 9.67225 7.09653 9.44225L8.97652 7.54224C9.20652 7.31224 9.20652 6.94225 8.97652 6.72225L7.30652 5.04224C6.95652 4.69224 7.18652 4.08224 7.67652 4.04224C8.92652 3.94224 10.2165 4.36224 11.1665 5.32224C13.1365 7.31224 12.8565 10.7022 10.3365 12.3022Z" stroke="currentColor" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13.8966 11.7523L20.2566 18.1223C20.4466 18.3223 20.4466 18.6323 20.2566 18.8223L18.2666 20.8223C18.0666 21.0223 17.7466 21.0223 17.5566 20.8223L11.2266 14.4223" stroke="currentColor" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Taller
            </button>
            <button onclick="openCreateCambioEquipoModal()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-800 border border-zinc-700 hover:bg-zinc-700 rounded-xl text-sm font-medium text-gray-300 hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Cambio Equipo
            </button>
        </div>
    </div>

    <!-- Filtros Panel (collapsible) -->
    <div id="filtros-servicios-panel" class="hidden mb-5 p-4 bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50 animate-in">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Vehículo</label>
                <select id="filtro-servicio-vehiculo" class="w-full rounded-lg bg-zinc-900 border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                    <option value="">Todos los vehículos</option>
                    @foreach($rodados as $rodado)
                        <option value="{{ $rodado->id }}">{{ $rodado->patente ?? 'Sin patente' }} - {{ $rodado->cliente->nombre ?? '' }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Tipo</label>
                <select id="filtro-servicio-tipo" class="w-full rounded-lg bg-zinc-900 border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                    <option value="">Todos los tipos</option>
                    <option value="turno_service">Turno Service</option>
                    <option value="turno_mecanico">Turno Mecánico</option>
                    <option value="cambio_equipo">Cambio Equipo</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Taller</label>
                <select id="filtro-servicio-taller" class="w-full rounded-lg bg-zinc-900 border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                    <option value="">Todos los talleres</option>
                    @foreach($talleres as $taller)
                        <option value="{{ $taller->id }}">{{ $taller->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Estado</label>
                <select id="filtro-servicio-estado" class="w-full rounded-lg bg-zinc-900 border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                    <option value="">Todos los estados</option>
                    <option value="programado">Programado</option>
                    <option value="proximo">Próximo</option>
                    <option value="asistido_a_confirmar">A confirmar</option>
                    <option value="asistido">Asistido</option>
                    <option value="cancelado">Cancelado</option>
                    <option value="perdido">Perdido</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end mt-3">
            <button onclick="limpiarFiltrosServicios()" class="text-xs text-gray-500 hover:text-gray-300 transition">Limpiar filtros</button>
        </div>
    </div>

    <!-- Tabla de servicios -->
    <div class="bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-zinc-700/50">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tipo</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Vehículo</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Fecha</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Taller</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Estado</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Vencimiento</th>
                        <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-700/30">
                    @forelse($todosLosServicios ?? $turnos as $servicio)
                    @php
                        $turno = $servicio['tipo_servicio'] === 'turno' ? $servicio['model'] : null;
                        $cambio = $servicio['tipo_servicio'] === 'cambio_equipo' ? $servicio['model'] : null;
                        $item = $turno ?? $cambio;
                    @endphp
                    <tr class="servicio-row group hover:bg-zinc-700/30 transition-colors duration-150"
                        data-vehiculo="{{ $servicio['rodado']->id }}"
                        data-tipo="{{ $servicio['tipo'] }}"
                        data-taller="{{ $servicio['taller']->id ?? '' }}"
                        data-estado="{{ $servicio['estado'] }}"
                        data-search="{{ strtolower(($servicio['rodado']->patente ?? '') . ' ' . ($servicio['taller']->nombre ?? '') . ' ' . $servicio['tipo']) }}">

                        <!-- Tipo -->
                        <td class="px-5 py-4">
                            <div class="flex flex-col gap-1">
                                @if($servicio['tipo'] === 'turno_service')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20 w-fit">
                                        <div class="w-1.5 h-1.5 rounded-full bg-blue-400"></div>
                                        Service
                                    </span>
                                @elseif($servicio['tipo'] === 'turno_mecanico')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20 w-fit">
                                        <div class="w-1.5 h-1.5 rounded-full bg-amber-400"></div>
                                        Mecánico
                                    </span>
                                    @if($turno && $turno->cobertura_estado === 'aprobada')
                                        <span class="text-[10px] text-emerald-400 font-medium flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                                            Cubre Empresa
                                        </span>
                                    @elseif($turno && $turno->cobertura_estado === 'rechazada')
                                        <span class="text-[10px] text-red-400 font-medium flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Cubre Cliente
                                        </span>
                                    @else
                                        <span class="text-[10px] text-amber-400 font-medium flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                            Cobertura Pendiente
                                        </span>
                                    @endif
                                @elseif($servicio['tipo'] === 'cambio_equipo')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 w-fit">
                                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                                        Cambio Equipo
                                    </span>
                                    @if($cambio)
                                        <span class="text-[10px] text-gray-500">{{ ucfirst(str_replace('_', ' ', $cambio->tipo)) }}</span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-zinc-600/30 text-gray-400 border border-zinc-600/30 w-fit">
                                        <div class="w-1.5 h-1.5 rounded-full bg-gray-400"></div>
                                        Taller
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Vehículo -->
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-zinc-700/60 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-200">{{ $servicio['rodado']->patente ?? 'Sin patente' }}</div>
                                    <div class="text-xs text-gray-500">{{ $servicio['rodado']->marca }} {{ $servicio['rodado']->modelo }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Fecha -->
                        <td class="px-5 py-4">
                            <div class="text-sm text-gray-300">{{ $servicio['fecha_hora']->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $servicio['fecha_hora']->format('H:i') }}hs</div>
                        </td>

                        <!-- Taller -->
                        <td class="px-5 py-4">
                            <span class="text-sm text-gray-300">{{ $servicio['taller']->nombre ?? 'N/A' }}</span>
                        </td>

                        <!-- Estado -->
                        <td class="px-5 py-4">
                            @if($servicio['estado'] === 'programado')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                    Programado
                                </span>
                            @elseif($servicio['estado'] === 'proximo')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                    Próximo
                                </span>
                            @elseif($servicio['estado'] === 'asistido_a_confirmar')
                                <div class="relative estado-confirmar-wrapper">
                                    <button type="button" onclick="toggleEstadoDropdown(this)"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 cursor-pointer hover:bg-yellow-500/20 transition-all animate-pulse-soft">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/></svg>
                                        A confirmar
                                        <svg class="w-3 h-3 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                                    </button>
                                    <div class="estado-dropdown hidden absolute z-50 mt-1 left-0 w-48 bg-zinc-800 border border-zinc-700 rounded-xl shadow-xl overflow-hidden">
                                        <form method="POST" action="{{ route('rodados.turnos.confirmar-estado', $servicio['id']) }}">
                                            @csrf
                                            <input type="hidden" name="estado" value="asistido">
                                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-emerald-400 hover:bg-zinc-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Confirmar asistencia
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('rodados.turnos.confirmar-estado', $servicio['id']) }}">
                                            @csrf
                                            <input type="hidden" name="estado" value="perdido">
                                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-400 hover:bg-zinc-700 transition-colors border-t border-zinc-700/50">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                No asistió
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @elseif($servicio['estado'] === 'asistido')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Asistido
                                </span>
                            @elseif($servicio['estado'] === 'cancelado')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Cancelado
                                </span>
                            @elseif($servicio['estado'] === 'perdido')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-zinc-600/30 text-red-300 border border-zinc-600/30">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Perdido
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-zinc-600/30 text-gray-400 border border-zinc-600/30">
                                    {{ ucfirst($servicio['estado']) }}
                                </span>
                            @endif
                        </td>

                        <!-- Vencimiento -->
                        <td class="px-5 py-4">
                            @if($servicio['fecha_vencimiento_pago'])
                                @php
                                    $diasRestantes = now()->diffInDays($servicio['fecha_vencimiento_pago'], false);
                                @endphp
                                <div class="text-sm text-gray-300">{{ $servicio['fecha_vencimiento_pago']->format('d/m/Y') }}</div>
                                @if($diasRestantes < 0)
                                    <span class="text-[10px] font-semibold text-red-400 flex items-center gap-1 mt-0.5">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                        Vencido ({{ abs(intval($diasRestantes)) }}d)
                                    </span>
                                @elseif($diasRestantes <= 7)
                                    <span class="text-[10px] font-semibold text-amber-400 mt-0.5">{{ intval($diasRestantes) }} días restantes</span>
                                @else
                                    <span class="text-[10px] text-gray-500 mt-0.5">{{ intval($diasRestantes) }} días</span>
                                @endif
                            @else
                                <span class="text-xs text-gray-600">--</span>
                            @endif
                        </td>

                        <!-- Acciones -->
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-0.5 opacity-60 group-hover:opacity-100 transition-opacity">
                                @if($servicio['tipo_servicio'] === 'turno')
                                    <button onclick="openEditTurnoModal({{ $item->id }})"
                                        class="p-2 rounded-lg text-gray-400 hover:text-blue-400 hover:bg-blue-500/10 transition-all" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                                        </svg>
                                    </button>
                                    <button onclick="openVerDocumentosModal({{ $item->id }}, '{{ $turno && $turno->informe_path ? asset('storage/' . $turno->informe_path) : '' }}', '{{ $turno && $turno->factura_path ? asset('storage/' . $turno->factura_path) : '' }}')"
                                        class="p-2 rounded-lg text-gray-400 hover:text-cyan-400 hover:bg-cyan-500/10 transition-all" title="Ver documentos">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </button>
                                    @if($servicio['tipo'] === 'turno_mecanico')
                                        @if($turno && $turno->cobertura_estado === 'aprobada' && $turno->taller)
                                            @if($turno->taller->whatsapp_link)
                                            <a href="{{ $turno->taller->whatsapp_link }}" target="_blank"
                                                class="p-2 rounded-lg text-gray-400 hover:text-green-400 hover:bg-green-500/10 transition-all" title="WhatsApp al taller">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                                            </a>
                                            @endif
                                            @if($turno->taller->mailto_link)
                                            <a href="{{ $turno->taller->mailto_link }}"
                                                class="p-2 rounded-lg text-gray-400 hover:text-sky-400 hover:bg-sky-500/10 transition-all" title="Email al taller">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                                            </a>
                                            @endif
                                        @endif
                                        <button onclick="openAdjuntarFacturaModal('turno', {{ $item->id }}, true)"
                                            class="p-2 rounded-lg text-gray-400 hover:text-emerald-400 hover:bg-emerald-500/10 transition-all" title="Adjuntar factura mano de obra">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/>
                                            </svg>
                                        </button>
                                    @endif
                                    <form action="{{ route('rodados.turnos.destroy', ['turno' => $item]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-2 rounded-lg text-gray-400 hover:text-red-400 hover:bg-red-500/10 transition-all"
                                                onclick="return confirm('¿Eliminar este turno?')" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                            </svg>
                                        </button>
                                    </form>
                                    <button onclick="openDocumentacionModal({{ $item->id }})"
                                        class="{{ $servicio['tipo'] === 'turno_service' ? '' : 'hidden' }} p-2 rounded-lg text-gray-400 hover:text-amber-400 hover:bg-amber-500/10 transition-all" title="Subir documentación">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                        </svg>
                                    </button>
                                    <button onclick="openRevisarCoberturaModal({{ $item->id }})"
                                        class="{{ ($servicio['tipo'] === 'turno_mecanico' && $turno && $turno->cobertura_estado !== 'aprobada' && $turno->cobertura_estado !== 'rechazada') ? '' : 'hidden' }} p-2 rounded-lg text-gray-400 hover:text-purple-400 hover:bg-purple-500/10 transition-all" title="Revisar cobertura">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                                        </svg>
                                    </button>
                                @else
                                    <button onclick="openEditCambioEquipoModal({{ $item->id }})"
                                        class="p-2 rounded-lg text-gray-400 hover:text-blue-400 hover:bg-blue-500/10 transition-all" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                                        </svg>
                                    </button>
                                    <button onclick="openAdjuntarFacturaModal('cambio_equipo', {{ $item->id }})"
                                        class="p-2 rounded-lg text-gray-400 hover:text-emerald-400 hover:bg-emerald-500/10 transition-all" title="Adjuntar factura">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/>
                                        </svg>
                                    </button>
                                    <form action="{{ route('rodados.cambios-equipos.destroy', ['cambio' => $item]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-2 rounded-lg text-gray-400 hover:text-red-400 hover:bg-red-500/10 transition-all"
                                                onclick="return confirm('¿Eliminar este cambio de equipo?')" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 00-7.5 0"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 rounded-full bg-zinc-700/30 flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.42 15.17l-5.08 3.05a.75.75 0 01-1.08-.8l.97-5.67-4.12-4.01a.75.75 0 01.42-1.28l5.69-.83 2.54-5.16a.75.75 0 011.36 0l2.54 5.16 5.69.83a.75.75 0 01.42 1.28l-4.12 4.01.97 5.67a.75.75 0 01-1.08.8l-5.08-3.05z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-400 font-medium">No hay servicios registrados</p>
                                <p class="text-gray-600 text-sm mt-1">Agenda un turno de service o taller</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        @if(count($todosLosServicios) > 0)
        <div class="px-5 py-3 bg-zinc-900/30 border-t border-zinc-700/30 flex items-center justify-between">
            <span class="text-xs text-gray-500" id="servicios-count-text">Mostrando {{ count($todosLosServicios) }} registros</span>
        </div>
        @endif
    </div>
</div>

<!-- Modales -->
@include('rodados.modals.turno-modal')
@include('rodados.modals.cambio-equipo-modal')
@include('rodados.partials.modal-adjuntar-factura')
@include('rodados.partials.modal-revisar-cobertura')

<script>
    function toggleFiltrosServicios() {
        document.getElementById('filtros-servicios-panel').classList.toggle('hidden');
    }

    function limpiarFiltrosServicios() {
        ['filtro-servicio-vehiculo', 'filtro-servicio-tipo', 'filtro-servicio-taller', 'filtro-servicio-estado'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });
        document.getElementById('busqueda-servicio').value = '';
        aplicarFiltrosServicios();
    }

    function buscarServicio(query) {
        const q = query.toLowerCase().trim();
        document.querySelectorAll('.servicio-row').forEach(row => {
            if (!q) { row.style.display = ''; return; }
            const searchData = row.dataset.search || '';
            row.style.display = searchData.includes(q) ? '' : 'none';
        });
        updateServiciosCount();
    }

    document.addEventListener('DOMContentLoaded', function() {
        ['filtro-servicio-vehiculo', 'filtro-servicio-tipo', 'filtro-servicio-taller', 'filtro-servicio-estado'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', aplicarFiltrosServicios);
        });
    });

    function aplicarFiltrosServicios() {
        const vehiculo = document.getElementById('filtro-servicio-vehiculo').value;
        const tipo = document.getElementById('filtro-servicio-tipo').value;
        const taller = document.getElementById('filtro-servicio-taller').value;
        const estado = document.getElementById('filtro-servicio-estado').value;

        document.querySelectorAll('.servicio-row').forEach(fila => {
            const mostrar =
                (vehiculo === '' || fila.dataset.vehiculo === vehiculo) &&
                (tipo === '' || fila.dataset.tipo === tipo) &&
                (taller === '' || fila.dataset.taller === taller) &&
                (estado === '' || fila.dataset.estado === estado);
            fila.style.display = mostrar ? '' : 'none';
        });
        updateServiciosCount();
    }

    function updateServiciosCount() {
        const visible = document.querySelectorAll('.servicio-row:not([style*="display: none"])').length;
        const total = document.querySelectorAll('.servicio-row').length;
        const el = document.getElementById('servicios-count-text');
        if (el) el.textContent = visible === total ? `Mostrando ${total} registros` : `Mostrando ${visible} de ${total} registros`;
    }

    function openCreateTurnoModal(tipo) {
        document.getElementById('turno-form').reset();
        document.getElementById('turno-form').action = '{{ route("rodados.turnos.store") }}';
        document.getElementById('turno-id').value = '';

        const tipoFieldContainer = document.getElementById('turno-tipo-field-container');
        const tipoSelect = document.getElementById('turno-tipo');
        const tipoHidden = document.getElementById('turno-tipo-hidden');

        if (tipo === 'turno_service' || tipo === 'turno_mecanico') {
            if (tipoFieldContainer) tipoFieldContainer.style.display = 'none';
            if (tipoHidden) tipoHidden.value = tipo === 'turno_mecanico' ? 'turno_mecanico' : 'turno_service';
            if (tipoSelect) tipoSelect.value = tipo === 'turno_mecanico' ? 'turno_taller' : tipo;
        } else {
            if (tipoFieldContainer) tipoFieldContainer.style.display = 'block';
            if (tipoHidden) tipoHidden.value = '';
            if (tipoSelect) tipoSelect.value = tipo;
        }

        document.getElementById('turno-modal-title').textContent = 'Nuevo ' + (tipo === 'turno_service' ? 'Turno Service' : tipo === 'turno_mecanico' ? 'Turno Mecánico' : 'Turno al Taller');
        toggleTurnoFields();
        document.getElementById('turno-modal').classList.remove('hidden');
    }

    function openCreateCambioEquipoModal() {
        document.getElementById('cambio-equipo-form').reset();
        document.getElementById('cambio-equipo-form').action = '{{ route("rodados.cambios-equipos.store") }}';
        document.getElementById('cambio-equipo-id').value = '';
        document.getElementById('cambio-equipo-modal-title').textContent = 'Nuevo Cambio de Equipo';
        toggleCambioEquipoFields();
        document.getElementById('cambio-equipo-modal').classList.remove('hidden');
    }

    async function openEditTurnoModal(id) {
        try {
            const baseUrl = '{{ url("/rodados/turnos") }}';
            const response = await fetch(baseUrl + '/' + id, {
                method: 'GET',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!response.ok) throw new Error('Error al cargar los datos del turno');
            const turno = await response.json();

            document.getElementById('turno-id').value = turno.id;
            document.getElementById('turno-form').action = baseUrl + '/' + id;
            document.getElementById('turno-modal-title').textContent = 'Editar Turno';
            document.getElementById('turno-rodado').value = turno.rodado_id || '';
            document.getElementById('turno-taller').value = turno.taller_id || '';
            document.getElementById('turno-fecha-hora').value = turno.fecha_hora || '';

            const tipoFieldContainer = document.getElementById('turno-tipo-field-container');
            const tipoSelect = document.getElementById('turno-tipo');
            const tipoHidden = document.getElementById('turno-tipo-hidden');

            if (tipoFieldContainer) tipoFieldContainer.style.display = 'block';
            let tipoDisplay = turno.tipo === 'turno_mecanico' ? 'turno_taller' : turno.tipo;
            if (tipoSelect) tipoSelect.value = tipoDisplay;
            if (tipoHidden) tipoHidden.value = turno.tipo;

            toggleTurnoFields();

            setTimeout(() => {
                if (turno.tipo === 'turno_service') {
                    const ed = document.getElementById('turno-encargado-dejar-service');
                    const er = document.getElementById('turno-encargado-retirar-service');
                    const ds = document.getElementById('turno-descripcion-service');
                    if (ed) ed.value = turno.encargado_dejar || '';
                    if (er) er.value = turno.encargado_retirar || '';
                    if (ds) ds.value = turno.descripcion || '';
                } else if (turno.tipo === 'turno_mecanico') {
                    const dt = document.getElementById('turno-descripcion-taller');
                    const edt = document.getElementById('turno-encargado-dejar-taller');
                    const ert = document.getElementById('turno-encargado-retirar-taller');

                    if (dt) {
                        const val = (turno.descripcion !== null && turno.descripcion !== undefined) ? String(turno.descripcion) : '';
                        dt.value = val;
                        dt.name = 'descripcion';
                        dt.disabled = false;
                        dt.required = true;
                        setTimeout(() => { if (dt.value !== val) dt.value = val; }, 100);
                    }
                    if (edt) edt.value = turno.encargado_dejar || '';
                    if (ert) ert.value = turno.encargado_retirar || '';

                    let partesAfectadas = turno.partes_afectadas || [];
                    if (partesAfectadas && typeof partesAfectadas === 'object' && !Array.isArray(partesAfectadas)) {
                        partesAfectadas = Object.values(partesAfectadas);
                    }
                    if (!Array.isArray(partesAfectadas)) partesAfectadas = [];

                    const tbody = document.getElementById('partes-afectadas-body');
                    tbody.innerHTML = '';
                    const escapeHtml = (text) => {
                        if (text === null || text === undefined) return '';
                        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
                        return String(text).replace(/[&<>"']/g, m => map[m]);
                    };
                    filaParteCounter = 0;
                    partesAfectadas.forEach((parte) => {
                        filaParteCounter++;
                        const tr = document.createElement('tr');
                        tr.setAttribute('data-parte-index', filaParteCounter);
                        tr.innerHTML = `
                            <td class="px-4 py-2"><input type="text" name="partes_afectadas[${filaParteCounter}][item]" required value="${escapeHtml(parte.item || '')}" class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-2 py-1 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500"></td>
                            <td class="px-4 py-2"><input type="number" name="partes_afectadas[${filaParteCounter}][cantidad]" required min="1" value="${escapeHtml(parte.cantidad || '')}" class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-2 py-1 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500"></td>
                            <td class="px-4 py-2"><input type="text" name="partes_afectadas[${filaParteCounter}][descripcion]" required value="${escapeHtml(parte.descripcion || '')}" class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-2 py-1 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500"></td>
                            <td class="px-4 py-2"><button type="button" onclick="eliminarFilaParte(this)" class="text-red-400 hover:text-red-300"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
                document.getElementById('turno-modal').classList.remove('hidden');
            }, 100);
        } catch (error) {
            console.error('Error:', error);
            alert('Error al cargar los datos del turno.');
        }
    }

    function openEditCambioEquipoModal(id) {
        document.getElementById('cambio-equipo-id').value = id;
        document.getElementById('cambio-equipo-form').action = '{{ url("/rodados/cambios-equipos") }}' + '/' + id;
        document.getElementById('cambio-equipo-modal-title').textContent = 'Editar Cambio de Equipo';
        document.getElementById('cambio-equipo-modal').classList.remove('hidden');
        toggleCambioEquipoFields();
    }

    function openVerDocumentosModal(turnoId, informeUrl, facturaUrl) {
        const container = document.getElementById('ver-docs-container');
        container.innerHTML = '';

        let hasContent = false;

        if (informeUrl) {
            hasContent = true;
            container.innerHTML += `
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                        <h4 class="text-sm font-semibold text-gray-200">Informe</h4>
                        <a href="${informeUrl}" target="_blank" class="ml-auto text-xs text-blue-400 hover:text-blue-300 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                            Abrir
                        </a>
                    </div>
                    <div class="rounded-xl overflow-hidden border border-zinc-700/50 bg-zinc-800">
                        <iframe src="${informeUrl}" class="w-full h-[400px]" frameborder="0"></iframe>
                    </div>
                </div>
            `;
        }

        if (facturaUrl) {
            hasContent = true;
            container.innerHTML += `
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                        <h4 class="text-sm font-semibold text-gray-200">Factura</h4>
                        <a href="${facturaUrl}" target="_blank" class="ml-auto text-xs text-blue-400 hover:text-blue-300 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                            Abrir
                        </a>
                    </div>
                    <div class="rounded-xl overflow-hidden border border-zinc-700/50 bg-zinc-800">
                        <iframe src="${facturaUrl}" class="w-full h-[400px]" frameborder="0"></iframe>
                    </div>
                </div>
            `;
        }

        if (!hasContent) {
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                    <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    <p class="text-sm">No hay documentos adjuntos a este turno.</p>
                </div>
            `;
        }

        document.getElementById('ver-documentos-modal').classList.remove('hidden');
    }
</script>

<!-- Modal Ver Documentos -->
<div id="ver-documentos-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center"
    style="padding-left: calc(var(--fb-sidebar-width, 240px) + 1rem); padding-top: calc(var(--fb-topbar-height, 60px) + 1.5rem); padding-right: 1rem; padding-bottom: 1rem;"
    onclick="if(event.target === this) document.getElementById('ver-documentos-modal').classList.add('hidden')">
    <div class="w-full max-w-3xl bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden flex flex-col"
        style="max-height: calc(100vh - var(--fb-topbar-height, 60px) - 4rem);"
        onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800 shrink-0">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-cyan-600/10 rounded-lg">
                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-100">Documentos del Turno</h3>
            </div>
            <button type="button" onclick="document.getElementById('ver-documentos-modal').classList.add('hidden')" class="p-2 text-gray-500 hover:text-gray-300 hover:bg-zinc-800 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <!-- Body -->
        <div id="ver-docs-container" class="px-6 py-5 overflow-y-auto flex-1 space-y-5" style="scrollbar-width: thin; scrollbar-color: rgba(39,39,42,0.8) transparent;"></div>
    </div>
</div>

<style>
    #ver-docs-container::-webkit-scrollbar { width: 6px; }
    #ver-docs-container::-webkit-scrollbar-track { background: transparent; }
    #ver-docs-container::-webkit-scrollbar-thumb { background: rgba(39,39,42,0.8); border-radius: 3px; }

    @keyframes pulse-soft {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    .animate-pulse-soft { animation: pulse-soft 2s ease-in-out infinite; }
</style>

<script>
    function toggleEstadoDropdown(btn) {
        const wrapper = btn.closest('.estado-confirmar-wrapper');
        const dropdown = wrapper.querySelector('.estado-dropdown');
        const isHidden = dropdown.classList.contains('hidden');

        document.querySelectorAll('.estado-confirmar-wrapper').forEach(w => w.style.zIndex = '');
        document.querySelectorAll('.estado-dropdown').forEach(d => d.classList.add('hidden'));

        if (isHidden) {
            wrapper.style.zIndex = '50';
            dropdown.classList.remove('hidden');
        }
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.estado-confirmar-wrapper')) {
            document.querySelectorAll('.estado-confirmar-wrapper').forEach(w => w.style.zIndex = '');
            document.querySelectorAll('.estado-dropdown').forEach(d => d.classList.add('hidden'));
        }
    });
</script>
