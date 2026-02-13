<div>
    <div class="mb-4">
        <div class="flex justify-between items-center">
            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Documentación
            </h3>
            @if(!$mostrarFormulario)
                <button wire:click="mostrarFormularioAgregar" title="Agregar Documentación"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs flex items-center gap-1.5 transition-all shadow-lg shadow-blue-600/20">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Agregar</span>
                </button>
            @endif
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-3 p-2.5 bg-zinc-800 border border-green-500/30 text-green-400 rounded-xl text-xs flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-3 p-2.5 bg-zinc-800 border border-red-500/30 text-red-400 rounded-xl text-xs flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            {{ session('error') }}
        </div>
    @endif

    @if($mostrarFormulario)
        <div class="mb-4 bg-zinc-800/50 rounded-xl p-4 border border-zinc-700/50">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 items-end">
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Nombre *</label>
                    <select wire:model="nuevoNombre"
                            class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-1.5 text-gray-200 text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                        <option value="">Seleccionar</option>
                        @foreach($opcionesDocumentacion as $opcion)
                            <option value="{{ $opcion }}">{{ $opcion }}</option>
                        @endforeach
                    </select>
                    @error('nuevoNombre')<span class="text-red-400 text-[10px]">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">F. Inicio *</label>
                    <input type="date" wire:model="nuevaFechaInicio"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-1.5 text-gray-200 text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                    @error('nuevaFechaInicio')<span class="text-red-400 text-[10px]">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">F. Vto.</label>
                    <input type="date" wire:model="nuevaFechaVto"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-1.5 text-gray-200 text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Detalles</label>
                    <input type="text" wire:model="nuevosDetalles"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-1.5 text-gray-200 text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30"
                           placeholder="Opcional">
                </div>
                <div class="flex gap-1.5">
                    <button wire:click="guardarDocumentacion" title="Guardar"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </button>
                    <button wire:click="cancelarAgregar" title="Cancelar"
                            class="bg-zinc-700 hover:bg-zinc-600 text-white px-3 py-1.5 rounded-lg text-xs transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-zinc-700/50">
        <table class="w-full text-xs">
            <thead>
                <tr class="border-b border-zinc-700/50">
                    <th class="px-3 py-2.5 text-left text-[11px] font-medium text-gray-400 uppercase tracking-wider">Nombre</th>
                    <th class="px-3 py-2.5 text-left text-[11px] font-medium text-gray-400 uppercase tracking-wider">F. Inicio</th>
                    <th class="px-3 py-2.5 text-left text-[11px] font-medium text-gray-400 uppercase tracking-wider">F. Vencimiento</th>
                    <th class="px-3 py-2.5 text-left text-[11px] font-medium text-gray-400 uppercase tracking-wider">Detalles</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/50">
                @forelse ($documentacion as $doc)
                    <tr class="hover:bg-zinc-700/30 transition-colors">
                        <td class="px-3 py-2 text-gray-200 font-medium">{{ $doc->nombre ?? 'N/A' }}</td>
                        <td class="px-3 py-2 text-gray-300">
                            {{ $doc->fecha_inicio ? $doc->fecha_inicio->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="px-3 py-2">
                            @if($doc->fecha_vto)
                                <div>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium border {{ $doc->esta_vencido ? 'bg-red-500/10 text-red-400 border-red-500/20' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' }}">
                                        {{ $doc->fecha_vto->format('d/m/Y') }}
                                    </span>
                                    <div class="text-[10px] text-gray-500 mt-0.5">{{ $doc->info_dias }}</div>
                                </div>
                            @else
                                <span class="text-gray-500">—</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-gray-300 max-w-[150px] truncate" title="{{ $doc->detalles }}">{{ $doc->detalles ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-3 py-6 text-center text-gray-500 text-xs">
                            No hay documentación registrada
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $documentacion->links() }}
    </div>
</div>
