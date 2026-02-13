<!-- Modal de Detalle del Vehículo (solo lectura) -->
<div id="detalle-vehiculo-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center modal-backdrop" style="padding-left: calc(var(--fb-sidebar-width, 240px) + 1rem); padding-top: calc(var(--fb-topbar-height, 60px) + 1.5rem); padding-right: 1rem; padding-bottom: 1rem;" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="w-full max-w-2xl bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden modal-content" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-600/10 rounded-lg">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-100">Datos del Vehículo</h3>
            </div>
            <button onclick="document.getElementById('detalle-vehiculo-modal').classList.add('hidden')"
                class="p-2 text-gray-500 hover:text-gray-300 hover:bg-zinc-800 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-5 max-h-[70vh] overflow-y-auto modal-scroll">
            <!-- Patente destacada -->
            <div class="text-center mb-5">
                <span id="detalle-patente" class="inline-block text-3xl font-bold font-mono tracking-widest text-blue-400 bg-blue-600/10 border border-blue-500/20 px-6 py-2 rounded-xl"></span>
            </div>

            <!-- Datos en grid -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-zinc-800/50 rounded-xl p-3 border border-zinc-700/30">
                    <span class="text-[10px] text-gray-500 uppercase tracking-wider">Marca</span>
                    <p id="detalle-marca" class="text-sm font-medium text-gray-200 mt-0.5"></p>
                </div>
                <div class="bg-zinc-800/50 rounded-xl p-3 border border-zinc-700/30">
                    <span class="text-[10px] text-gray-500 uppercase tracking-wider">Modelo</span>
                    <p id="detalle-modelo" class="text-sm font-medium text-gray-200 mt-0.5"></p>
                </div>
                <div class="bg-zinc-800/50 rounded-xl p-3 border border-zinc-700/30">
                    <span class="text-[10px] text-gray-500 uppercase tracking-wider">Tipo</span>
                    <p id="detalle-tipo" class="text-sm font-medium text-gray-200 mt-0.5"></p>
                </div>
                <div class="bg-zinc-800/50 rounded-xl p-3 border border-zinc-700/30">
                    <span class="text-[10px] text-gray-500 uppercase tracking-wider">Año</span>
                    <p id="detalle-año" class="text-sm font-medium text-gray-200 mt-0.5"></p>
                </div>
                <div class="bg-zinc-800/50 rounded-xl p-3 border border-zinc-700/30">
                    <span class="text-[10px] text-gray-500 uppercase tracking-wider">Cliente</span>
                    <p id="detalle-cliente" class="text-sm font-medium text-gray-200 mt-0.5"></p>
                </div>
                <div class="bg-zinc-800/50 rounded-xl p-3 border border-zinc-700/30">
                    <span class="text-[10px] text-gray-500 uppercase tracking-wider">Proveedor</span>
                    <p id="detalle-proveedor" class="text-sm font-medium text-gray-200 mt-0.5"></p>
                </div>
            </div>

            <!-- Imágenes -->
            <div id="detalle-imagenes-section">
                <h4 class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-3">Imágenes del Vehículo</h4>
                <div class="grid grid-cols-2 gap-3" id="detalle-imagenes-grid">
                    <!-- Filled dynamically -->
                </div>
                <p id="detalle-sin-imagenes" class="hidden text-sm text-gray-600 text-center py-4">No hay imágenes disponibles</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-zinc-800 bg-zinc-900/50">
            <button type="button" onclick="document.getElementById('detalle-vehiculo-modal').classList.add('hidden')"
                class="px-4 py-2.5 text-sm font-medium text-gray-400 hover:text-gray-200 bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 rounded-xl transition-all">
                Cerrar
            </button>
        </div>
    </div>
</div>

@php
    $vehiculosJson = $rodados->map(function($r) {
        return [
            'id' => $r->id,
            'patente' => $r->patente,
            'marca' => $r->marca,
            'modelo' => $r->modelo,
            'tipo_vehiculo' => $r->tipo_vehiculo,
            'año' => $r->año,
            'cliente' => $r->cliente->nombre ?? 'N/A',
            'proveedor' => $r->proveedor->nombre ?? 'Propio',
            'imagen_frente' => $r->imagen_frente_path ? '/storage/' . $r->imagen_frente_path : null,
            'imagen_costado_izq' => $r->imagen_costado_izq_path ? '/storage/' . $r->imagen_costado_izq_path : null,
            'imagen_costado_der' => $r->imagen_costado_der_path ? '/storage/' . $r->imagen_costado_der_path : null,
            'imagen_dorso' => $r->imagen_dorso_path ? '/storage/' . $r->imagen_dorso_path : null,
        ];
    })->keyBy('id');
@endphp

<script>
    // Vehicle data passed from PHP
    const vehiculosData = @json($vehiculosJson);

    function openDetalleVehiculoModal(id) {
        const v = vehiculosData[id];
        if (!v) return;

        document.getElementById('detalle-patente').textContent = v.patente || 'Sin patente';
        document.getElementById('detalle-marca').textContent = v.marca;
        document.getElementById('detalle-modelo').textContent = v.modelo;
        document.getElementById('detalle-tipo').textContent = v.tipo_vehiculo;
        document.getElementById('detalle-año').textContent = v.año;
        document.getElementById('detalle-cliente').textContent = v.cliente;
        document.getElementById('detalle-proveedor').textContent = v.proveedor;

        const grid = document.getElementById('detalle-imagenes-grid');
        const sinImg = document.getElementById('detalle-sin-imagenes');
        grid.innerHTML = '';

        const imageLabels = { imagen_frente: 'Frente', imagen_costado_izq: 'Costado Izquierdo', imagen_costado_der: 'Costado Derecho', imagen_dorso: 'Dorso' };
        let hasImages = false;

        Object.entries(imageLabels).forEach(([key, label]) => {
            if (v[key]) {
                hasImages = true;
                grid.innerHTML += `
                    <div class="relative group">
                        <span class="text-[10px] text-gray-500 uppercase tracking-wider">${label}</span>
                        <img src="${v[key]}" alt="${label}" class="mt-1 w-full h-32 object-cover rounded-xl border border-zinc-700">
                        <a href="${v[key]}" download class="absolute bottom-2 right-2 p-1.5 bg-zinc-900/80 rounded-lg text-gray-300 hover:text-white opacity-0 group-hover:opacity-100 transition-opacity" title="Descargar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </a>
                    </div>
                `;
            }
        });

        sinImg.classList.toggle('hidden', hasImages);
        grid.classList.toggle('hidden', !hasImages);

        document.getElementById('detalle-vehiculo-modal').classList.remove('hidden');
    }
</script>
