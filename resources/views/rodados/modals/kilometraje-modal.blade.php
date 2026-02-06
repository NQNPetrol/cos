<!-- Modal para registrar kilometraje -->
<div id="kilometraje-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center modal-backdrop" style="padding-left: calc(var(--fb-sidebar-width, 240px) + 1rem); padding-top: calc(var(--fb-topbar-height, 60px) + 1.5rem); padding-right: 1rem; padding-bottom: 1rem;" onclick="if(event.target === this) closeKilometrajeModal()">
    <div class="w-full max-w-md bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden modal-content" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-emerald-600/10 rounded-lg">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-100">Registrar Kilometraje</h3>
            </div>
            <button onclick="closeKilometrajeModal()"
                class="p-2 text-gray-500 hover:text-gray-300 hover:bg-zinc-800 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-5 max-h-[65vh] overflow-y-auto modal-scroll">
            <form id="kilometraje-form" method="POST" action="{{ route('rodados.kilometraje.store') }}">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Vehículo *</label>
                        <select id="kilometraje-rodado" name="rodado_id" required
                            class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                            <option value="">Seleccione un vehículo</option>
                            @foreach($rodados as $rodado)
                                <option value="{{ $rodado->id }}">
                                    {{ $rodado->patente ?? 'Sin patente' }}
                                    @if($rodado->kilometrajeActual)
                                        (Actual: {{ number_format($rodado->kilometrajeActual->kilometraje, 0, ',', '.') }} km)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Kilometraje *</label>
                        <div class="relative">
                            <input type="number" id="kilometraje-valor" name="kilometraje" required min="0" placeholder="Ej: 45000"
                                class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 pr-12 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                            <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-500 font-medium">km</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Fecha de Registro *</label>
                        <input type="date" id="kilometraje-fecha" name="fecha_registro" required value="{{ date('Y-m-d') }}"
                            class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Observaciones <span class="text-gray-600 normal-case">(opcional)</span></label>
                        <textarea id="kilometraje-observaciones" name="observaciones" rows="2" placeholder="Notas adicionales..."
                            class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all resize-none"></textarea>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-zinc-800 bg-zinc-900/50">
            <button type="button" onclick="closeKilometrajeModal()"
                class="px-4 py-2.5 text-sm font-medium text-gray-400 hover:text-gray-200 bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 rounded-xl transition-all">
                Cancelar
            </button>
            <button type="submit" form="kilometraje-form"
                class="px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-lg shadow-emerald-600/20 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Guardar
            </button>
        </div>
    </div>
</div>

<script>
    function closeKilometrajeModal() {
        document.getElementById('kilometraje-modal').classList.add('hidden');
        document.getElementById('kilometraje-form').reset();
    }
    function openKilometrajeModal(rodadoId = null) {
        if (rodadoId) document.getElementById('kilometraje-rodado').value = rodadoId;
        document.getElementById('kilometraje-modal').classList.remove('hidden');
    }
</script>
