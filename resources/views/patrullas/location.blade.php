<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-600 text-white p-4 rounded-lg mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif
            <!-- Header -->
            <div class="bg-slate-900 overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <div class="p-6 text-gray-100">
                    <!-- Header -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-3xl font-bold text-white mb-2">Vehículos Móviles</h2>
                                <p class="text-sm text-gray-300">Seguimiento y localización de patrullas en tiempo real</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contenedor del Mapa -->
                    <div class="bg-gray-800 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Mapa de Localización</h3>
                        <div class="h-96 bg-gray-700 rounded-lg flex items-center justify-center">
                            <div class="text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p>Mapa de Google Maps (área para implementación)</p>
                                <p class="text-sm mt-2">Filtros: Por implementar</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Vehículos Móviles -->
                    <div class="bg-gray-700 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-600">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Código
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Dominio
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Región
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Estado
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-600">
                                    @forelse($mobileVehicles as $vehicle)
                                        <tr class="hover:bg-gray-750 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-white">{{ $vehicle->mobile_vehicle_index_code }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-blue-400 font-medium">{{ $vehicle->mobile_vehicle_name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-300">{{ $vehicle->region_index_code }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-medium rounded {{ $vehicle->status == 1 ? 'bg-green-900/30 text-green-300 border border-green-600/50' : 'bg-red-900/30 text-red-300 border border-red-600/50' }}">
                                                    {{ $vehicle->status == 1 ? 'ACTIVO' : 'INACTIVO' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-gray-400">
                                                No hay vehículos móviles registrados.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Paginación -->
                    @if($mobileVehicles->hasPages())
                        <div class="mt-6">
                            {{ $mobileVehicles->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>