<!-- Modal para crear/editar vehículo -->
<div id="vehiculo-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center modal-backdrop" style="padding-left: calc(var(--fb-sidebar-width, 240px) + 1rem); padding-top: calc(var(--fb-topbar-height, 60px) + 1.5rem); padding-right: 1rem; padding-bottom: 1rem;" onclick="if(event.target === this) document.getElementById('vehiculo-modal').classList.add('hidden')">
    <div class="w-full max-w-xl bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden modal-content" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-600/10 rounded-lg">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                    </svg>
                </div>
                <h3 id="vehiculo-modal-title" class="text-lg font-semibold text-gray-100">Nuevo Vehículo</h3>
            </div>
            <button onclick="document.getElementById('vehiculo-modal').classList.add('hidden')"
                class="p-2 text-gray-500 hover:text-gray-300 hover:bg-zinc-800 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-5 max-h-[65vh] overflow-y-auto modal-scroll">
            <form id="vehiculo-form" method="POST" action="{{ route('rodados.store') }}">
                @csrf
                <input type="hidden" id="vehiculo-id" name="id">
                @method('POST')

                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Tipo de Vehículo *</label>
                            <select id="vehiculo-tipo" name="tipo_vehiculo" required
                                class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                                <option value="">Seleccione un tipo</option>
                                @foreach(\App\Models\Rodado::getTiposVehiculo() as $tipo)
                                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Marca *</label>
                            <select id="vehiculo-marca" name="marca" required
                                class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                                <option value="">Seleccione una marca</option>
                                @foreach(\App\Models\Rodado::getMarcas() as $marca)
                                    <option value="{{ $marca }}">{{ $marca }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Modelo *</label>
                            <input type="text" id="vehiculo-modelo" name="modelo" required placeholder="Ej: Hilux 4x4"
                                class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Año *</label>
                            <input type="number" id="vehiculo-año" name="año" required min="1900" max="{{ date('Y') + 1 }}" placeholder="{{ date('Y') }}"
                                class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Cliente *</label>
                        <select id="vehiculo-cliente" name="cliente_id" required
                            class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                            <option value="">Seleccione un cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Patente</label>
                            <input type="text" id="vehiculo-patente" name="patente" placeholder="Ej: AB123CD"
                                class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all font-mono uppercase">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Proveedor <span class="text-gray-600 normal-case">(opcional)</span></label>
                            <select id="vehiculo-proveedor" name="proveedor_id"
                                class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                                <option value="">Vehículo propio</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-zinc-800 bg-zinc-900/50">
            <button type="button" onclick="document.getElementById('vehiculo-modal').classList.add('hidden')"
                class="px-4 py-2.5 text-sm font-medium text-gray-400 hover:text-gray-200 bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 rounded-xl transition-all">
                Cancelar
            </button>
            <button type="submit" form="vehiculo-form"
                class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-600/20 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Guardar
            </button>
        </div>
    </div>
</div>

<script>
    document.getElementById('vehiculo-form')?.addEventListener('submit', function(e) {
        const tipoVehiculo = document.getElementById('vehiculo-tipo')?.value;
        const marca = document.getElementById('vehiculo-marca')?.value;
        const modelo = document.getElementById('vehiculo-modelo')?.value;
        const patente = document.getElementById('vehiculo-patente')?.value;
        const cliente = document.getElementById('vehiculo-cliente')?.value;

        if (!tipoVehiculo) { e.preventDefault(); alert('Por favor, seleccione un tipo de vehículo.'); return false; }
        if (!marca) { e.preventDefault(); alert('Por favor, seleccione una marca.'); return false; }
        if (!modelo || modelo.trim() === '') { e.preventDefault(); alert('Por favor, ingrese el modelo del vehículo.'); return false; }
        if (!patente || patente.trim() === '') { e.preventDefault(); alert('Por favor, ingrese la patente del vehículo.'); return false; }
        if (!cliente) { e.preventDefault(); alert('Por favor, seleccione un cliente.'); return false; }

        const patenteRegex = /^[A-Z0-9]{6,10}$/i;
        if (!patenteRegex.test(patente.replace(/\s/g, ''))) {
            if (!confirm('El formato de la patente no parece válido. ¿Desea continuar de todas formas?')) { e.preventDefault(); return false; }
        }

        const id = document.getElementById('vehiculo-id')?.value;
        if (id) {
            this.action = '{{ route("rodados.update", ":id") }}'.replace(':id', id);
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden'; methodInput.name = '_method'; methodInput.value = 'PUT';
            this.appendChild(methodInput);
        }
    });
</script>
