<div>
    <div class="mb-4">
        <div class="flex justify-between items-center">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-100">Sistemas Registrados</h3>
                    
            </div>
            @if(!$mostrarFormulario)
                <button wire:click="mostrarFormularioAgregar"
                        title="Agregar Sistema"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm flex items-center space-x-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                    
                </button>
            @endif
        </div>
    </div>

    <!-- Mensajes de éxito/error -->
    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-gray-600 text-white rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-3 bg-gray-600 text-white rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($mostrarFormulario)
        <div class="mb-6 bg-gray-800 rounded-lg p-3 border border-gray-800">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <!-- Sistema -->
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Sistema *</label>
                    <select wire:model="nuevoSistemaId"
                            class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-gray-200 text-sm">
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
                    <label class="block text-sm text-gray-300 mb-1">Fecha Registro *</label>
                    <input type="date" 
                        wire:model="nuevaFechaRegistro"
                        class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-gray-200 text-sm">
                    @error('nuevaFechaRegistro')
                        <span class="text-red-400 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- N° Interno -->
                <div>
                    <label class="block text-sm text-gray-300 mb-1">N° Interno</label>
                    <input type="number" 
                        wire:model="nuevoNroInterno"
                        class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-gray-200 text-sm"
                        placeholder="Opcional">
                    @error('nuevoNroInterno')
                        <span class="text-red-400 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Botones del formulario -->
                <div class="flex space-x-2">
                    <button wire:click="guardarSistema"
                            title="Guardar"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm flex items-center space-x-2 transition-colors h-10">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1H2z"/>
                        </svg>
                       
                    </button>
                    <button wire:click="cancelarAgregar"
                            title="Cancelar"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm transition-colors h-10">
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
            <thead class="bg-gray-800 text-gray-300">
                <tr>
                    <th class="px-4 py-2 text-left">Sistema</th>
                    <th class="px-4 py-2 text-left">Fecha Registro</th>
                    <th class="px-4 py-2 text-left">N° Interno</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sistemas as $registro)
                    <tr class="border-b border-gray-700 hover:bg-gray-800">
                        <td class="px-4 py-2 text-gray-200">
                            {{ $registro->sistema->nombre ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-2 text-gray-300">
                            {{ $registro->fecha_registro ? \Carbon\Carbon::parse($registro->fecha_registro)->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="px-4 py-2 text-gray-300">
                            {{ $registro->nro_interno ?? 'N/A' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-4 text-center text-gray-400">
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
