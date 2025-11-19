<div>
    <div class="mb-4">
        <div class="flex justify-between items-center">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-100">Documentación</h3>
            </div>
            @if(!$mostrarFormulario)
                <button wire:click="mostrarFormularioAgregar"
                        title="Agregar Documentación"
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

    <!-- Formulario para agregar nueva documentación -->
    @if($mostrarFormulario)
        <div class="mb-6 bg-gray-800 rounded-lg p-3 border border-gray-800">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <!-- Nombre -->
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Nombre *</label>
                    <select wire:model="nuevoNombre"
                            class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-gray-200 text-sm">
                        <option value="">Seleccionar tipo</option>
                        @foreach($opcionesDocumentacion as $opcion)
                            <option value="{{ $opcion }}">{{ $opcion }}</option>
                        @endforeach
                    </select>
                    @error('nuevoNombre')
                        <span class="text-red-400 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Fecha Inicio -->
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Fecha Inicio *</label>
                    <input type="date" 
                           wire:model="nuevaFechaInicio"
                           class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-gray-200 text-sm">
                    @error('nuevaFechaInicio')
                        <span class="text-red-400 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Fecha Vencimiento -->
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Fecha Vto.</label>
                    <input type="date" 
                           wire:model="nuevaFechaVto"
                           class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-gray-200 text-sm"
                           placeholder="Opcional">
                    @error('nuevaFechaVto')
                        <span class="text-red-400 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Detalles -->
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Detalles</label>
                    <input type="text" 
                           wire:model="nuevosDetalles"
                           class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-gray-200 text-sm"
                           placeholder="Detalles opcionales">
                    @error('nuevosDetalles')
                        <span class="text-red-400 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Botones del formulario -->
                <div class="flex space-x-2">
                    <button wire:click="guardarDocumentacion"
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
                    <th class="px-4 py-2 text-left">Nombre</th>
                    <th class="px-4 py-2 text-left">Fecha Inicio</th>
                    <th class="px-4 py-2 text-left">Fecha Vencimiento</th>
                    <th class="px-4 py-2 text-left">Detalles</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($documentacion as $doc)
                    <tr class="border-b border-gray-700 hover:bg-gray-800">
                        <td class="px-4 py-2 text-gray-200">
                            {{ $doc->nombre ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-2 text-gray-300">
                            {{ $doc->fecha_inicio ? $doc->fecha_inicio->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="px-4 py-2 text-gray-300">
                            @if($doc->fecha_vto)
                                <span class="{{ $doc->fecha_vto->isPast() ? 'text-red-400' : 'text-green-400' }}">
                                    {{ $doc->fecha_vto->format('d/m/Y') }}
                                </span>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-4 py-2 text-gray-300">
                            {{ $doc->detalles ?? 'N/A' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-gray-400">
                            No hay documentación registrada para esta patrulla
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $documentacion->links() }}
    </div>
</div>