<div>
    <div class="mb-4">
        <div class="flex justify-between items-center">
            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Sistemas Registrados
            </h3>
            @if(!$mostrarFormulario)
                <button wire:click="mostrarFormularioAgregar" title="Agregar Sistema"
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
                    <label class="block text-xs font-medium text-gray-400 mb-1">Sistema *</label>
                    <select wire:model="nuevoSistemaId"
                            class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-1.5 text-gray-200 text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                        <option value="">Seleccionar</option>
                        @foreach($sistemasDisponibles as $sistema)
                            <option value="{{ $sistema->id }}">{{ $sistema->nombre }}</option>
                        @endforeach
                    </select>
                    @error('nuevoSistemaId')<span class="text-red-400 text-[10px]">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">F. Registro *</label>
                    <input type="date" wire:model="nuevaFechaRegistro"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-1.5 text-gray-200 text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                    @error('nuevaFechaRegistro')<span class="text-red-400 text-[10px]">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">F. Vto *</label>
                    <input type="date" wire:model="nuevaFechaVto"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-1.5 text-gray-200 text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                    @error('nuevaFechaVto')<span class="text-red-400 text-[10px]">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">N° Interno</label>
                    <input type="number" wire:model="nuevoNroInterno"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-1.5 text-gray-200 text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30"
                           placeholder="Opc.">
                </div>
                <div class="flex gap-1.5">
                    <button wire:click="guardarSistema" title="Guardar"
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
                    <th class="px-3 py-2.5 text-left text-[11px] font-medium text-gray-400 uppercase tracking-wider">Sistema</th>
                    <th class="px-3 py-2.5 text-left text-[11px] font-medium text-gray-400 uppercase tracking-wider">F. Registro</th>
                    <th class="px-3 py-2.5 text-left text-[11px] font-medium text-gray-400 uppercase tracking-wider">F. Vencimiento</th>
                    <th class="px-3 py-2.5 text-left text-[11px] font-medium text-gray-400 uppercase tracking-wider">N° Interno</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/50">
                @forelse ($sistemas as $registro)
                    <tr class="hover:bg-zinc-700/30 transition-colors">
                        <td class="px-3 py-2 text-gray-200 font-medium">{{ $registro->sistema->nombre ?? 'N/A' }}</td>
                        <td class="px-3 py-2 text-gray-300">
                            {{ $registro->fecha_registro ? \Carbon\Carbon::parse($registro->fecha_registro)->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="px-3 py-2">
                            @if($registro->fecha_vto)
                                <div>
                                    @php $vencido = $registro->fecha_vto->isPast(); @endphp
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium border {{ $vencido ? 'bg-red-500/10 text-red-400 border-red-500/20' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' }}">
                                        {{ $registro->fecha_vto->format('d/m/Y') }}
                                    </span>
                                    <div class="text-[10px] text-gray-500 mt-0.5">{{ $registro->info_dias }}</div>
                                </div>
                            @else
                                <span class="text-gray-500">—</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-gray-300">{{ $registro->nro_interno ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-3 py-6 text-center text-gray-500 text-xs">
                            No hay sistemas registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $sistemas->links() }}
    </div>
</div>
