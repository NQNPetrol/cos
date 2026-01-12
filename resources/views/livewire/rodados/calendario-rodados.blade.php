<div>
    <div class="max-w-[1920px] mx-auto">
        <!-- Calendar Controls -->
        <div class="bg-zinc-800 rounded-xl border border-zinc-700 shadow-xl p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- View Mode Buttons -->
                <div class="flex gap-2">
                    <button 
                        type="button"
                        wire:click="setViewMode('month')"
                        class="px-4 py-2 rounded-lg transition-all duration-200 font-medium {{ $viewMode === 'month' ? 'bg-blue-600 text-white shadow-lg' : 'bg-zinc-700 text-gray-300 hover:bg-zinc-600 hover:-translate-y-0.5' }}"
                    >
                        Mensual
                    </button>
                    <button 
                        type="button"
                        wire:click="setViewMode('week')"
                        class="px-4 py-2 rounded-lg transition-all duration-200 font-medium {{ $viewMode === 'week' ? 'bg-blue-600 text-white shadow-lg' : 'bg-zinc-700 text-gray-300 hover:bg-zinc-600 hover:-translate-y-0.5' }}"
                    >
                        Semanal
                    </button>
                    <button 
                        type="button"
                        wire:click="setViewMode('day')"
                        class="px-4 py-2 rounded-lg transition-all duration-200 font-medium {{ $viewMode === 'day' ? 'bg-blue-600 text-white shadow-lg' : 'bg-zinc-700 text-gray-300 hover:bg-zinc-600 hover:-translate-y-0.5' }}"
                    >
                        Diario
                    </button>
                </div>

                <!-- Navigation -->
                <div class="flex items-center gap-4">
                    <button 
                        type="button"
                        wire:click="previousPeriod"
                        class="p-2 rounded-lg bg-zinc-700 hover:bg-zinc-600 text-gray-300 transition-all duration-200 hover:-translate-y-0.5"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button 
                        type="button"
                        wire:click="goToToday"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 font-medium"
                    >
                        Hoy
                    </button>
                    <button 
                        type="button"
                        wire:click="nextPeriod"
                        class="p-2 rounded-lg bg-zinc-700 hover:bg-zinc-600 text-gray-300 transition-all duration-200 hover:-translate-y-0.5"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>

                <!-- Current Period Display -->
                <div class="text-center">
                    @php
                        $date = \Carbon\Carbon::parse($currentDate);
                    @endphp
                    @if($viewMode === 'month')
                        <h2 class="text-2xl font-semibold text-gray-100">
                            {{ $date->locale('es')->translatedFormat('F Y') }}
                        </h2>
                    @elseif($viewMode === 'week')
                        <h2 class="text-2xl font-semibold text-gray-100">
                            Semana del {{ $date->copy()->startOfWeek()->locale('es')->translatedFormat('d') }} al {{ $date->copy()->endOfWeek()->locale('es')->translatedFormat('d F Y') }}
                        </h2>
                    @else
                        <h2 class="text-2xl font-semibold text-gray-100">
                            {{ $date->locale('es')->translatedFormat('l, d F Y') }}
                        </h2>
                    @endif
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div class="bg-zinc-800 rounded-xl border border-zinc-700 shadow-xl p-4 mb-6">
            <div class="flex flex-wrap items-center gap-4">
                <span class="text-sm font-medium text-gray-300">Leyenda:</span>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-blue-600"></div>
                    <span class="text-sm text-gray-400">Turno Service</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-orange-500"></div>
                    <span class="text-sm text-gray-400">Turno Mecánico</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-green-500"></div>
                    <span class="text-sm text-gray-400">Cambio de Equipo</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-red-500"></div>
                    <span class="text-sm text-gray-400">Pago</span>
                </div>
            </div>
        </div>

        <!-- Month View -->
        @if($viewMode === 'month')
            <div wire:key="month-view" class="bg-zinc-800 rounded-xl border border-zinc-700 shadow-xl overflow-hidden">
                <!-- Weekday Headers -->
                <div class="grid grid-cols-7 border-b border-zinc-700">
                    @foreach(['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'] as $day)
                        <div class="p-4 text-center font-semibold text-gray-300 bg-zinc-900/50 uppercase text-sm">
                            {{ $day }}
                        </div>
                    @endforeach
                </div>

                <!-- Calendar Days -->
                <div class="grid grid-cols-7">
                    @foreach($this->getDaysInMonth() as $day)
                        @php
                            $currentDateObj = \Carbon\Carbon::parse($currentDate);
                            $isCurrentMonth = $day->month === $currentDateObj->month;
                            $isToday = $day->isToday();
                            $dateEvents = $this->getEventsForDate($day);
                        @endphp
                        <div 
                            class="min-h-[120px] p-2 border-r border-b border-zinc-700 {{ $isCurrentMonth ? 'bg-zinc-800' : 'bg-zinc-900/30' }} hover:bg-zinc-700/50 transition-colors cursor-pointer"
                            wire:click="selectDate('{{ $day->format('Y-m-d') }}')"
                        >
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium {{ $isToday ? 'bg-blue-600 text-white rounded-full w-7 h-7 flex items-center justify-center font-bold' : ($isCurrentMonth ? 'text-gray-200' : 'text-gray-500') }}">
                                    {{ $day->day }}
                                </span>
                            </div>
                            <div class="space-y-1">
                                @foreach($dateEvents->take(3) as $event)
                                    <div 
                                        class="text-xs px-2 py-1 rounded truncate cursor-pointer transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5" 
                                        style="background-color: {{ $event['color'] }}; color: white;"
                                        wire:click.stop="selectEvent('{{ $event['tipo'] }}', '{{ str_replace($event['tipo'] . '_', '', $event['id']) }}')"
                                        title="{{ $event['title'] }}"
                                    >
                                        {{ $event['title'] }}
                                    </div>
                                @endforeach
                                @if($dateEvents->count() > 3)
                                    <div class="text-xs text-gray-400 px-2 py-1">
                                        +{{ $dateEvents->count() - 3 }} más
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Week View -->
        @if($viewMode === 'week')
            <div wire:key="week-view" class="bg-zinc-800 rounded-xl border border-zinc-700 shadow-xl overflow-hidden">
                <!-- Weekday Headers -->
                <div class="grid grid-cols-7 border-b border-zinc-700">
                    @foreach($this->getDaysInWeek() as $day)
                        <div class="p-4 text-center border-r border-zinc-700 {{ $day->isToday() ? 'bg-blue-600/20' : 'bg-zinc-900/50' }}">
                            <div class="font-semibold text-gray-300 uppercase text-sm mb-1">{{ $day->locale('es')->translatedFormat('D') }}</div>
                            <div class="text-2xl font-bold {{ $day->isToday() ? 'text-blue-400' : 'text-gray-200' }}">{{ $day->day }}</div>
                        </div>
                    @endforeach
                </div>

                <!-- Week Days Content -->
                <div class="grid grid-cols-7">
                    @foreach($this->getDaysInWeek() as $day)
                        @php
                            $isToday = $day->isToday();
                            $dateEvents = $this->getEventsForDate($day);
                        @endphp
                        <div class="min-h-[400px] p-4 border-r border-zinc-700 {{ $isToday ? 'bg-blue-600/10' : 'bg-zinc-800' }}">
                            <div class="space-y-2">
                                @foreach($dateEvents as $event)
                                    <div 
                                        class="p-3 rounded-lg text-white cursor-pointer transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5"
                                        style="background-color: {{ $event['color'] }};"
                                        wire:click="selectEvent('{{ $event['tipo'] }}', '{{ str_replace($event['tipo'] . '_', '', $event['id']) }}')"
                                    >
                                        <div class="font-semibold text-sm">{{ $event['title'] }}</div>
                                        <div class="text-xs mt-1 opacity-90">
                                            {{ $event['date']->format('H:i') }}
                                        </div>
                                    </div>
                                @endforeach
                                @if($dateEvents->isEmpty())
                                    <p class="text-sm text-gray-500 text-center mt-4">Sin eventos</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Day View -->
        @if($viewMode === 'day')
            <div wire:key="day-view" class="bg-zinc-800 rounded-xl border border-zinc-700 shadow-xl overflow-hidden">
                <!-- Day Header -->
                <div class="p-6 border-b border-zinc-700 bg-zinc-900/50">
                    <h3 class="text-2xl font-bold text-gray-100">
                        {{ \Carbon\Carbon::parse($currentDate)->locale('es')->translatedFormat('l, d F Y') }}
                    </h3>
                </div>

                <!-- Day Content -->
                <div class="p-6">
                    @php
                        $currentDateObj = \Carbon\Carbon::parse($currentDate);
                        $dateEvents = $this->getEventsForDate($currentDateObj);
                    @endphp
                    @if($dateEvents->count() > 0)
                        <div class="space-y-4">
                            @foreach($dateEvents as $event)
                                <div 
                                    class="p-4 rounded-lg text-white cursor-pointer transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5"
                                    style="background-color: {{ $event['color'] }};"
                                    wire:click="selectEvent('{{ $event['tipo'] }}', '{{ str_replace($event['tipo'] . '_', '', $event['id']) }}')"
                                >
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-bold text-lg">{{ $event['title'] }}</h4>
                                            <p class="text-sm mt-1 opacity-90">
                                                {{ $event['date']->format('d/m/Y H:i') }}
                                            </p>
                                            @if(isset($event['rodado']))
                                                <p class="text-sm mt-1 opacity-90">Vehículo: {{ $event['rodado'] }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            @if(isset($event['estado']))
                                                <span class="text-xs bg-white/20 px-2 py-1 rounded capitalize">{{ $event['estado'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-400">No hay eventos para este día</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Modal de detalles del evento -->
    @if($showModal && $selectedEvent)
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-in fade-in duration-200" wire:click="closeModal">
            <div class="relative w-full max-w-2xl bg-zinc-800 border border-zinc-700 rounded-xl shadow-2xl max-h-[90vh] overflow-hidden animate-in zoom-in-95 duration-200" wire:click.stop>
                <!-- Header del modal -->
                <div class="flex justify-between items-center p-6 border-b border-zinc-700 bg-zinc-900/50">
                    <h3 class="text-xl font-semibold text-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Detalles del Evento
                    </h3>
                    <button wire:click="closeModal"
                        class="p-2 text-gray-400 hover:text-gray-200 hover:bg-zinc-700 rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Contenido del modal -->
                <div class="overflow-y-auto max-h-[calc(90vh-180px)]">
                    <div class="p-6 space-y-4">
                        @php
                            $eventData = null;
                            if ($selectedEvent['tipo'] === 'turno') {
                                $turno = \App\Models\TurnoRodado::with(['rodado.cliente', 'rodado.proveedor', 'taller'])->find($selectedEvent['id']);
                                if ($turno) {
                                    $eventData = [
                                        'tipo' => 'turno',
                                        'id' => $turno->id,
                                        'tipo_servicio' => $turno->tipo,
                                        'fecha_hora' => $turno->fecha_hora->format('Y-m-d H:i'),
                                        'rodado' => $turno->rodado->display_name,
                                        'taller' => $turno->taller->nombre ?? 'N/A',
                                        'descripcion' => $turno->descripcion,
                                        'estado' => $turno->estado,
                                    ];
                                }
                            } elseif ($selectedEvent['tipo'] === 'cambio_equipo') {
                                $cambio = \App\Models\CambioEquipoRodado::with(['rodado.cliente', 'rodado.proveedor', 'taller'])->find($selectedEvent['id']);
                                if ($cambio) {
                                    $eventData = [
                                        'tipo' => 'cambio_equipo',
                                        'id' => $cambio->id,
                                        'tipo_cambio' => $cambio->tipo,
                                        'fecha_hora_estimada' => $cambio->fecha_hora_estimada->format('Y-m-d H:i'),
                                        'rodado' => $cambio->rodado->display_name,
                                        'taller' => $cambio->taller->nombre ?? 'N/A',
                                        'motivo' => $cambio->motivo,
                                    ];
                                }
                            } elseif ($selectedEvent['tipo'] === 'pago') {
                                $pago = \App\Models\PagoServiciosRodado::with(['rodado.cliente', 'rodado.proveedor', 'proveedor'])->find($selectedEvent['id']);
                                if ($pago) {
                                    $eventData = [
                                        'tipo' => 'pago',
                                        'id' => $pago->id,
                                        'tipo_pago' => $pago->tipo,
                                        'fecha_pago' => \Carbon\Carbon::parse($pago->fecha_pago)->format('Y-m-d'),
                                        'rodado' => $pago->rodado->display_name,
                                        'moneda' => $pago->moneda ?? 'ARS',
                                        'monto' => $pago->monto,
                                        'estado' => $pago->factura_path ? 'pagado' : 'pendiente',
                                    ];
                                }
                            }
                        @endphp

                        @if($eventData)
                            @if($eventData['tipo'] === 'turno')
                                <div class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="p-4 bg-zinc-900/50 rounded-lg border border-zinc-700">
                                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Vehículo</label>
                                            <p class="text-gray-100 font-medium">{{ $eventData['rodado'] }}</p>
                                        </div>
                                        <div class="p-4 bg-zinc-900/50 rounded-lg border border-zinc-700">
                                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Taller</label>
                                            <p class="text-gray-100 font-medium">{{ $eventData['taller'] }}</p>
                                        </div>
                                    </div>
                                    <div class="p-4 bg-zinc-900/50 rounded-lg border border-zinc-700">
                                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Fecha y Hora</label>
                                        <p class="text-gray-100 font-medium">{{ $eventData['fecha_hora'] }}</p>
                                    </div>
                                    <div class="p-4 bg-zinc-900/50 rounded-lg border border-zinc-700">
                                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Tipo</label>
                                        <p class="text-gray-100 font-medium">{{ $eventData['tipo_servicio'] === 'turno_service' ? 'Turno Service' : 'Turno Mecánico' }}</p>
                                    </div>
                                    @if($eventData['descripcion'])
                                    <div class="p-4 bg-zinc-900/50 rounded-lg border border-zinc-700">
                                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Descripción</label>
                                        <p class="text-gray-100">{{ $eventData['descripcion'] }}</p>
                                    </div>
                                    @endif
                                    <div class="p-4 bg-zinc-900/50 rounded-lg border border-zinc-700">
                                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Estado</label>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $eventData['estado'] === 'completado' || $eventData['estado'] === 'atendido' ? 'bg-green-900/30 text-green-400 border border-green-800/50' : ($eventData['estado'] === 'cancelado' ? 'bg-red-900/30 text-red-400 border border-red-800/50' : 'bg-yellow-900/30 text-yellow-400 border border-yellow-800/50') }}">
                                            {{ ucfirst($eventData['estado']) }}
                                        </span>
                                    </div>
                                </div>
                            @elseif($eventData['tipo'] === 'cambio_equipo')
                                <div class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="p-4 bg-zinc-900/50 rounded-lg border border-zinc-700">
                                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Vehículo</label>
                                            <p class="text-gray-100 font-medium">{{ $eventData['rodado'] }}</p>
                                        </div>
                                        <div class="p-4 bg-zinc-900/50 rounded-lg border border-zinc-700">
                                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Taller</label>
                                            <p class="text-gray-100 font-medium">{{ $eventData['taller'] }}</p>
                                        </div>
                                    </div>
                                    <div class="p-4 bg-zinc-900/50 rounded-lg border border-zinc-700">
                                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Fecha y Hora Estimada</label>
                                        <p class="text-gray-100 font-medium">{{ $eventData['fecha_hora_estimada'] }}</p>
                                    </div>
                                    <div class="p-4 bg-zinc-900/50 rounded-lg border border-zinc-700">
                                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Tipo</label>
                                        <p class="text-gray-100 font-medium">{{ ucfirst(str_replace('_', ' ', $eventData['tipo_cambio'])) }}</p>
                                    </div>
                                    @if($eventData['motivo'])
                                    <div class="p-4 bg-zinc-900/50 rounded-lg border border-zinc-700">
                                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Motivo</label>
                                        <p class="text-gray-100">{{ $eventData['motivo'] }}</p>
                                    </div>
                                    @endif
                                </div>
                            @elseif($eventData['tipo'] === 'pago')
                                <div class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="p-4 bg-zinc-900/50 rounded-lg border border-zinc-700">
                                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Vehículo</label>
                                            <p class="text-gray-100 font-medium">{{ $eventData['rodado'] }}</p>
                                        </div>
                                        <div class="p-4 bg-zinc-900/50 rounded-lg border border-zinc-700">
                                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Tipo</label>
                                            <p class="text-gray-100 font-medium">{{ ucfirst(str_replace('_', ' ', $eventData['tipo_pago'])) }}</p>
                                        </div>
                                    </div>
                                    <div class="p-4 bg-zinc-900/50 rounded-lg border border-zinc-700">
                                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Fecha de Pago</label>
                                        <p class="text-gray-100 font-medium">{{ $eventData['fecha_pago'] }}</p>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="p-4 bg-zinc-900/50 rounded-lg border border-zinc-700">
                                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Moneda</label>
                                            <p class="text-gray-100 font-medium">{{ $eventData['moneda'] }}</p>
                                        </div>
                                        <div class="p-4 bg-zinc-900/50 rounded-lg border border-zinc-700">
                                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Monto</label>
                                            <p class="text-gray-100 font-medium text-lg">{{ $eventData['moneda'] === 'USD' ? 'USD$' : '$' }}{{ number_format($eventData['monto'], 2) }}</p>
                                        </div>
                                    </div>
                                    <div class="p-4 bg-zinc-900/50 rounded-lg border border-zinc-700">
                                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Estado</label>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $eventData['estado'] === 'pagado' ? 'bg-green-900/30 text-green-400 border border-green-800/50' : ($eventData['estado'] === 'vencido' ? 'bg-red-900/30 text-red-400 border border-red-800/50' : 'bg-yellow-900/30 text-yellow-400 border border-yellow-800/50') }}">
                                            {{ ucfirst($eventData['estado']) }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="p-4 bg-red-900/30 border border-red-800/50 rounded-lg text-red-300">
                                <p>Error al cargar los detalles del evento.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer del modal con acciones -->
                <div class="flex justify-end gap-3 p-6 border-t border-zinc-700 bg-zinc-900/50">
                    @if($eventData && $eventData['tipo'] === 'turno' && $eventData['estado'] !== 'cancelado')
                        <button 
                            onclick="cancelarTurno({{ $eventData['id'] }})"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancelar Turno
                        </button>
                        <button 
                            onclick="abrirReprogramarTurno({{ $eventData['id'] }})"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Reprogramar Turno
                        </button>
                    @elseif($eventData && $eventData['tipo'] === 'pago')
                        <a href="{{ route('rodados.index') }}#pagos"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Ver en Pagos
                        </a>
                    @endif
                    <button 
                        wire:click="closeModal"
                        class="px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg font-medium transition-all duration-200"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function cancelarTurno(id) {
        if (!id) {
            alert('Error: ID de turno inválido.');
            return;
        }

        if (!confirm('¿Está seguro de cancelar este turno?')) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            alert('Error: Token de seguridad no encontrado. Por favor, recarga la página.');
            return;
        }

        const url = `{{ route("rodados.turnos.cancelar", ["turno" => ":id"]) }}`.replace(':id', encodeURIComponent(id));

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken.content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || `HTTP error! status: ${response.status}`);
                });
            }
            if (response.redirected) {
                window.location.href = response.url;
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (data) {
                if (data.success) {
                    alert('Turno cancelado exitosamente.');
                    window.location.reload();
                } else if (data.error) {
                    alert('Error: ' + data.error);
                }
            }
        })
        .catch(error => {
            console.error('Error al cancelar turno:', error);
            alert('Error al cancelar el turno: ' + (error.message || 'Error desconocido'));
        });
    }

    function abrirReprogramarTurno(id) {
        if (!id) {
            alert('Error: ID de turno inválido.');
            return;
        }

        const now = new Date();
        const defaultDate = now.toISOString().slice(0, 16);
        
        const nuevaFecha = prompt('Ingrese la nueva fecha y hora (formato: YYYY-MM-DDTHH:mm):\nEjemplo: ' + defaultDate, defaultDate);
        if (!nuevaFecha) return;

        const fechaRegex = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/;
        if (!fechaRegex.test(nuevaFecha)) {
            alert('Error: Formato de fecha inválido. Use el formato YYYY-MM-DDTHH:mm (ejemplo: 2024-12-25T14:30)');
            return;
        }

        const fechaInput = new Date(nuevaFecha);
        if (isNaN(fechaInput.getTime())) {
            alert('Error: Fecha no válida.');
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            alert('Error: Token de seguridad no encontrado. Por favor, recarga la página.');
            return;
        }

        const url = `{{ route("rodados.turnos.reprogramar", ["turno" => ":id"]) }}`.replace(':id', encodeURIComponent(id));

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken.content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                fecha_hora: nuevaFecha
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || `HTTP error! status: ${response.status}`);
                });
            }
            if (response.redirected) {
                window.location.href = response.url;
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (data) {
                if (data.success) {
                    alert('Turno reprogramado exitosamente.');
                    window.location.reload();
                } else if (data.error) {
                    alert('Error: ' + data.error);
                }
            }
        })
        .catch(error => {
            console.error('Error al reprogramar turno:', error);
            alert('Error al reprogramar el turno: ' + (error.message || 'Error desconocido'));
        });
    }
</script>
@endpush

