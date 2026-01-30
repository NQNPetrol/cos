<div>
    <div class="mb-4">
        <div class="flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-200">Sistemas Registrados</h3>       
            @if(!$mostrarFormulario)
                <button wire:click="mostrarFormularioAgregar"
                        title="Agregar Sistema"
                        class="bg-[#1877f2] hover:bg-[#0866ff] text-white px-2 py-1 rounded text-xs flex items-center space-x-1 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                </button>
            @endif
        </div>
    </div>

    <!-- Mensajes de éxito/error -->
    @if (session()->has('success'))
        <div class="mb-3 p-2 bg-green-600 text-white rounded text-xs">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-3 p-2 bg-red-600 text-white rounded text-xs">
            {{ session('error') }}
        </div>
    @endif

    @if($mostrarFormulario)
        <div class="mb-4 bg-[#1a1d1f] rounded-lg p-3 border border-zinc-700">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-2 items-end">
                <!-- Sistema -->
                <div>
                    <label class="block text-xs font-semibold text-gray-200 mb-1">Sistema *</label>
                    <select wire:model="nuevoSistemaId"
                            class="w-full bg-transparent border border-zinc-500 rounded px-2 py-1 text-gray-200 text-xs modal-select">
                        <option value="">Seleccionar</option>
                        @foreach($sistemasDisponibles as $sistema)
                            <option value="{{ $sistema->id }}">{{ $sistema->nombre }}</option>
                        @endforeach
                    </select>
                    @error('nuevoSistemaId')<span class="text-red-400 text-[10px]">{{ $message }}</span>@enderror
                </div>

                <!-- Fecha Registro -->
                <div>
                    <label class="block text-xs font-semibold text-gray-200 mb-1">F. Registro *</label>
                    <input type="date" 
                        wire:model="nuevaFechaRegistro"
                        class="w-full bg-transparent border border-zinc-500 rounded px-2 py-1 text-gray-200 text-xs">
                    @error('nuevaFechaRegistro')<span class="text-red-400 text-[10px]">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-200 mb-1">F. Vto *</label>
                    <input type="date" 
                        wire:model="nuevaFechaVto"
                        class="w-full bg-transparent border border-zinc-500 rounded px-2 py-1 text-gray-200 text-xs">
                    @error('nuevaFechaVto')<span class="text-red-400 text-[10px]">{{ $message }}</span>@enderror
                </div>

                <!-- N° Interno -->
                <div>
                    <label class="block text-xs font-semibold text-gray-200 mb-1">N° Interno</label>
                    <input type="number" 
                        wire:model="nuevoNroInterno"
                        class="w-full bg-transparent border border-zinc-500 rounded px-2 py-1 text-gray-200 text-xs"
                        placeholder="Opc.">
                </div>

                <!-- Botones del formulario -->
                <div class="flex space-x-1">
                    <button wire:click="guardarSistema"
                            title="Guardar"
                            class="bg-[#1877f2] hover:bg-[#0866ff] text-white px-2 py-1 rounded text-xs transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                        </svg>
                    </button>
                    <button wire:click="cancelarAgregar"
                            title="Cancelar"
                            class="bg-zinc-700 hover:bg-zinc-600 text-white px-2 py-1 rounded text-xs transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <table class="w-full text-xs">
        <thead class="bg-[#1a1d1f] text-gray-300">
            <tr>
                <th class="px-2 py-1.5 text-left font-medium">Sistema</th>
                <th class="px-2 py-1.5 text-left font-medium">Fecha Registro</th>
                <th class="px-2 py-1.5 text-left font-medium">Fecha Vencimiento</th>
                <th class="px-2 py-1.5 text-left font-medium">N° Interno</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sistemas as $registro)
                <tr class="table-row-hover-modal transition-colors">
                    <td class="px-2 py-1.5 text-gray-300">
                        {{ $registro->sistema->nombre ?? 'N/A' }}
                    </td>
                    <td class="px-2 py-1.5 text-gray-300">
                        {{ $registro->fecha_registro ? \Carbon\Carbon::parse($registro->fecha_registro)->format('d/m/Y') : 'N/A' }}
                    </td>
                    <td class="px-2 py-1.5">
                        @if($registro->fecha_vto)
                            <div>
                                <span class="{{ $registro->fecha_vto->isPast() ? 'text-red-400' : 'text-green-400' }}">
                                    {{ $registro->fecha_vto->format('d/m/Y') }}
                                </span>
                                <div class="text-[10px] text-gray-400">
                                    {{ $registro->info_dias }}
                                </div>
                            </div>
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="px-2 py-1.5 text-gray-300">
                        {{ $registro->nro_interno ?? 'N/A' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-2 py-3 text-center text-gray-400 text-xs">
                        No hay sistemas registrados
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-2">
        {{ $sistemas->links() }}
    </div>
</div>
