<div class="bg-gray-900 text-gray-100 p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Registros de Vuelo - Administración</h2>
        <button wire:click="limpiarFiltros" 
                class="px-4 py-2 text-sm font-medium text-gray-200 bg-gray-800 border border-gray-700 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
            Limpiar Filtros
        </button>
    </div>

    <!-- Filtros -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm mb-1">Fecha Desde</label>
            <input type="date" 
                   wire:model.live="fechaDesde"
                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
        </div>
        
        <div>
            <label class="block text-sm mb-1">Fecha Hasta</label>
            <input type="date" 
                   wire:model.live="fechaHasta"
                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm mb-1">Cliente</label>
            <select wire:model.live="clienteId"
                    class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                <option value="">Todos los clientes</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm mb-1">Nombre del Drone</label>
            <select wire:model.live="droneName"
                    class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                <option value="">Todos los drones</option>
                @foreach($drones as $drone)
                    <option value="{{ $drone }}">{{ $drone }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm mb-1">Nombre de Misión</label>
            <select wire:model.live="misionNombre"
                    class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                <option value="">Todas las misiones</option>
                @foreach($misiones as $mision)
                    <option value="{{ $mision->nombre }}">{{ $mision->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Tabla -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-800 text-gray-300">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">
                        <a href="#" wire:click.prevent="sortBy('id')" class="hover:text-blue-400 transition-colors">
                            ID
                            @if($sortField === 'id')
                                @if($sortDirection === 'asc')
                                    &#9650;
                                @else
                                    &#9660;
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">
                        <a href="#" wire:click.prevent="sortBy('mision')" class="hover:text-blue-400 transition-colors">
                            Misión
                            @if($sortField === 'mision')
                                @if($sortDirection === 'asc')
                                    &#9650;
                                @else
                                    &#9660;
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">
                        <a href="#" wire:click.prevent="sortBy('cliente')" class="hover:text-blue-400 transition-colors">
                            Cliente
                            @if($sortField === 'cliente')
                                @if($sortDirection === 'asc')
                                    &#9650;
                                @else
                                    &#9660;
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">
                        <a href="#" wire:click.prevent="sortBy('drone_name')" class="hover:text-blue-400 transition-colors">
                            Drone
                            @if($sortField === 'drone_name')
                                @if($sortDirection === 'asc')
                                    &#9650;
                                @else
                                    &#9660;
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">
                        <a href="#" wire:click.prevent="sortBy('flight_starttime')" class="hover:text-blue-400 transition-colors">
                            Take Off
                            @if($sortField === 'flight_starttime')
                                @if($sortDirection === 'asc')
                                    &#9650;
                                @else
                                    &#9660;
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">
                        <a href="#" wire:click.prevent="sortBy('flight_endtime')" class="hover:text-blue-400 transition-colors">
                            Landing
                            @if($sortField === 'flight_endtime')
                                @if($sortDirection === 'asc')
                                    &#9650;
                                @else
                                    &#9660;
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">
                        <a href="#" wire:click.prevent="sortBy('flight_time')" class="hover:text-blue-400 transition-colors">
                            Duración
                            @if($sortField === 'flight_time')
                                @if($sortDirection === 'asc')
                                    &#9650;
                                @else
                                    &#9660;
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">
                        <a href="#" wire:click.prevent="sortBy('piloto')" class="hover:text-blue-400 transition-colors">
                            Piloto
                            @if($sortField === 'piloto')
                                @if($sortDirection === 'asc')
                                    &#9650;
                                @else
                                    &#9660;
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">
                        <a href="#" wire:click.prevent="sortBy('estado')" class="hover:text-blue-400 transition-colors">
                            Estado
                            @if($sortField === 'estado')
                                @if($sortDirection === 'asc')
                                    &#9650;
                                @else
                                    &#9660;
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Batería</th>
                </tr>
            </thead>
            <tbody>
                @forelse($flightLogs as $log)
                    <tr class="border-b border-gray-700 hover:bg-gray-800 transition-colors">
                        <!-- ID -->
                        <td class="px-4 py-3">
                            <div class="text-gray-200 font-medium">
                                #{{ $log->id }}
                            </div>
                        </td>
                        <!-- Misión -->
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-100">
                                {{ $log->mision->nombre ?? 'N/A' }}
                            </div>
                        </td>
                        
                        <!-- Cliente -->
                        <td class="px-4 py-3">
                            <div class="text-gray-200">
                                {{ $log->mision->cliente->nombre ?? 'Cliente no asignado' }}
                            </div>
                        </td>
                        
                        <!-- Drone -->
                        <td class="px-4 py-3">
                            <div class="text-gray-200">{{ $log->drone_name }}</div>
                        </td>
                        
                        <!-- Takeoff -->
                        <td class="px-4 py-3">
                            @if($log->flight_starttime)
                                <div class="text-gray-200">
                                    {{ $log->flight_starttime->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $log->flight_starttime->format('H:i:s') }}
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">N/A</span>
                            @endif
                        </td>
                        
                        <!-- Landing -->
                        <td class="px-4 py-3">
                            @if($log->flight_endtime)
                                <div class="text-gray-200">
                                    {{ $log->flight_endtime->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $log->flight_endtime->format('H:i:s') }}
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">En vuelo</span>
                            @endif
                        </td>
                        
                        <!-- Duración -->
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $log->duracion_legible }}
                            </span>
                        </td>
                        
                        <!-- Piloto -->
                        <td class="px-4 py-3 text-gray-200">
                            {{ $log->piloto->nombre ?? 'N/A' }}
                        </td>
                        
                        <!-- Estado -->
                        <td class="px-4 py-3">
                            @php
                                $estadoColors = [
                                    'completado' => 'bg-green-100 text-green-800',
                                    'en_proceso' => 'bg-blue-100 text-blue-800', 
                                    'interrumpido' => 'bg-red-100 text-red-800',
                                ];
                                $color = $estadoColors[$log->estado] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                {{ ucfirst(str_replace('_', ' ', $log->estado)) }}
                            </span>
                        </td>
                        
                        <!-- Batería -->
                        <td class="px-4 py-3">
                            @if($log->drone_battery)
                                <div class="flex items-center space-x-2">
                                    <div class="w-16 bg-gray-700 rounded-full h-2">
                                        @php
                                            $batteryPercent = intval(str_replace('%', '', $log->drone_battery));
                                            $batteryColor = $batteryPercent > 50 ? 'bg-green-500' : 
                                                           ($batteryPercent > 20 ? 'bg-yellow-500' : 'bg-red-500');
                                        @endphp
                                        <div class="h-2 rounded-full {{ $batteryColor }}" 
                                             style="width: {{ $batteryPercent }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-300 min-w-8">{{ $log->drone_battery }}</span>
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">N/A</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center px-4 py-8 text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="font-medium">No se encontraron registros de vuelo</p>
                                <p class="text-sm mt-1">Intenta ajustar los filtros aplicados</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($flightLogs->hasPages())
        <div class="mt-6">
            {{ $flightLogs->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
    /* Personalización de la paginación para tema oscuro */
    .bg-gray-900 .pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .bg-gray-900 .pagination li {
        margin: 0 2px;
    }
    
    .bg-gray-900 .pagination a,
    .bg-gray-900 .pagination span {
        display: block;
        padding: 8px 12px;
        border: 1px solid #374151;
        border-radius: 4px;
        color: #d1d5db;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .bg-gray-900 .pagination a:hover {
        background-color: #374151;
        border-color: #4b5563;
    }
    
    .bg-gray-900 .pagination .active span {
        background-color: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }
    
    .bg-gray-900 .pagination .disabled span {
        background-color: #1f2937;
        border-color: #374151;
        color: #6b7280;
        cursor: not-allowed;
    }
</style>
@endpush

