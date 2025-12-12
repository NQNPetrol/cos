<div>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-200">Sistemas Registrados</h3>       
            @if(!$mostrarFormulario)
                <button wire:click="mostrarFormularioAgregar"
                        title="Agregar Sistema"
                        class="bg-[#1877f2] hover:bg-[#0866ff] text-white px-3 py-2 rounded-lg text-sm flex items-center space-x-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                    
                </button>
            @endif
        </div>
    </div>

    <!-- Mensajes de éxito/error -->
    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-green-600 text-white rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-3 bg-red-600 text-white rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($mostrarFormulario)
        <div class="mb-6 bg-[#252728] rounded-lg p-4 border border-transparent">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <!-- Sistema -->
                <div>
                    <label class="block text-sm font-semibold text-gray-200 mb-2">Sistema *</label>
                    <select wire:model="nuevoSistemaId"
                            class="w-full bg-transparent border border-gray-300 rounded px-3 py-2 text-gray-200 text-sm modal-select">
                        <option value="">Seleccionar Sistema</option>
                        @foreach($sistemasDisponibles as $sistema)
                            <option value="{{ $sistema->id }}">{{ $sistema->nombre }}</option>
                        @endforeach
                    </select>
                    @error('nuevoSistemaId')
                        <span class="text-red-400 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Fecha Registro -->
                <div>
                    <label class="block text-sm font-semibold text-gray-200 mb-2">Fecha Registro *</label>
                    <input type="date" 
                        wire:model="nuevaFechaRegistro"
                        class="w-full bg-transparent border border-gray-300 rounded px-3 py-2 text-gray-200 text-sm">
                    @error('nuevaFechaRegistro')
                        <span class="text-red-400 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-200 mb-2">Fecha Vto *</label>
                    <input type="date" 
                        wire:model="nuevaFechaVto"
                        class="w-full bg-transparent border border-gray-300 rounded px-3 py-2 text-gray-200 text-sm">
                    @error('nuevaFechaVto')
                        <span class="text-red-400 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- N° Interno -->
                <div>
                    <label class="block text-sm font-semibold text-gray-200 mb-2">N° Interno</label>
                    <input type="number" 
                        wire:model="nuevoNroInterno"
                        class="w-full bg-transparent border border-gray-300 rounded px-3 py-2 text-gray-200 text-sm"
                        placeholder="Opcional">
                    @error('nuevoNroInterno')
                        <span class="text-red-400 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Botones del formulario -->
                <div class="flex space-x-2">
                    <button wire:click="guardarSistema"
                            title="Guardar"
                            class="bg-[#1877f2] hover:bg-[#0866ff] text-white px-4 py-2 rounded text-sm flex items-center space-x-2 transition-colors h-10">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1H2z"/>
                        </svg>
                       
                    </button>
                    <button wire:click="cancelarAgregar"
                            title="Cancelar"
                            class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm transition-colors h-10">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-[#1a1d1f] text-gray-300">
                <tr>
                    <th class="px-4 py-2 text-left">Sistema</th>
                    <th class="px-4 py-2 text-left">Fecha Registro</th>
                    <th class="px-4 py-2 text-left">Fecha Vencimiento</th>
                    <th class="px-4 py-2 text-left">N° Interno</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sistemas as $registro)
                    <tr class="table-row-hover-modal transition-colors">
                        <td class="px-4 py-2 text-gray-300">
                            {{ $registro->sistema->nombre ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-2 text-gray-300">
                            {{ $registro->fecha_registro ? \Carbon\Carbon::parse($registro->fecha_registro)->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="px-4 py-2">
                            @if($registro->fecha_vto)
                                <div>
                                    <span class="{{ $registro->fecha_vto->isPast() ? 'text-red-400' : 'text-green-400' }}">
                                        {{ $registro->fecha_vto->format('d/m/Y') }}
                                    </span>
                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ $registro->info_dias }}
                                    </div>
                                </div>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-4 py-2 text-gray-300">
                            {{ $registro->nro_interno ?? 'N/A' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-gray-300">
                            No hay sistemas registrados para esta patrulla
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $sistemas->links() }}
    </div>
</div>
