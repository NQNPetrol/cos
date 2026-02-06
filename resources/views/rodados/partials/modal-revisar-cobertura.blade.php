<!-- Modal para revisar y aprobar/rechazar cobertura -->
<div id="revisar-cobertura-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center modal-backdrop" style="padding-left: calc(var(--fb-sidebar-width, 240px) + 1rem); padding-top: calc(var(--fb-topbar-height, 60px) + 1.5rem); padding-right: 1rem; padding-bottom: 1rem;" onclick="if(event.target === this) closeRevisarCoberturaModal()">
    <div class="w-full max-w-2xl bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden modal-content flex flex-col" style="max-height: calc(100vh - var(--fb-topbar-height, 60px) - 4rem);" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800 shrink-0">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-cyan-600/10 rounded-lg">
                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-100">Revisar Cobertura</h3>
            </div>
            <button onclick="closeRevisarCoberturaModal()"
                class="p-2 text-gray-500 hover:text-gray-300 hover:bg-zinc-800 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Body -->
        <div class="overflow-y-auto flex-1 px-6 py-5 modal-scroll">
            <div id="revisar-cobertura-content" class="space-y-4">
                <div class="flex items-center justify-center py-8">
                    <div class="flex items-center gap-3 text-gray-400">
                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span class="text-sm">Cargando detalles...</span>
                    </div>
                </div>
            </div>

            <!-- Taller contact actions -->
            <div id="taller-contact-actions" class="hidden mt-5 p-4 bg-emerald-500/5 border border-emerald-500/20 rounded-xl">
                <p class="text-xs font-medium text-emerald-400 uppercase tracking-wider mb-3">Contactar al taller</p>
                <div class="flex gap-2">
                    <a id="taller-whatsapp-link" href="#" target="_blank" class="hidden inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white text-xs font-medium rounded-xl hover:bg-emerald-700 transition-all hover:-translate-y-0.5 shadow-lg shadow-emerald-600/20">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                        WhatsApp
                    </a>
                    <a id="taller-email-link" href="#" class="hidden inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-xs font-medium rounded-xl hover:bg-blue-700 transition-all hover:-translate-y-0.5 shadow-lg shadow-blue-600/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Email
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between px-6 py-4 border-t border-zinc-800 bg-zinc-900/50 shrink-0">
            <button type="button" onclick="closeRevisarCoberturaModal()"
                class="px-4 py-2.5 text-sm font-medium text-gray-400 hover:text-gray-200 bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 rounded-xl transition-all">
                Cerrar
            </button>
            <div class="flex items-center gap-2">
                <button type="button" onclick="rechazarCobertura()" id="btn-rechazar-cobertura"
                    class="px-4 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg shadow-red-600/20 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Rechazar
                </button>
                <button type="button" onclick="aprobarCobertura()" id="btn-aprobar-cobertura"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-lg shadow-emerald-600/20 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Aprobar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Documentacion Unificada -->
<div id="documentacion-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center modal-backdrop" style="padding-left: calc(var(--fb-sidebar-width, 240px) + 1rem); padding-top: calc(var(--fb-topbar-height, 60px) + 1.5rem); padding-right: 1rem; padding-bottom: 1rem;" onclick="if(event.target === this) document.getElementById('documentacion-modal').classList.add('hidden')">
    <div class="w-full max-w-md bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden modal-content" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-600/10 rounded-lg">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-100">Adjuntar Documentación</h3>
                    <p class="text-xs text-gray-500">Suba informe, factura y/o comprobante en un solo paso</p>
                </div>
            </div>
            <button onclick="document.getElementById('documentacion-modal').classList.add('hidden')"
                class="p-2 text-gray-500 hover:text-gray-300 hover:bg-zinc-800 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="documentacion-form" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Body -->
            <div class="px-6 py-5 max-h-[65vh] overflow-y-auto modal-scroll space-y-4">
                <!-- Informe -->
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Informe <span class="text-gray-600 normal-case">(PDF)</span></label>
                    <label class="flex items-center gap-3 w-full px-4 py-3 bg-zinc-800 border border-zinc-700 border-dashed rounded-xl cursor-pointer hover:border-indigo-500/50 transition-all group">
                        <div class="p-1.5 bg-zinc-700 rounded-lg group-hover:bg-indigo-600/10 transition-colors">
                            <svg class="w-4 h-4 text-gray-500 group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        </div>
                        <span class="text-sm text-gray-400 group-hover:text-gray-300" id="doc-informe-label">Seleccionar informe</span>
                        <input type="file" name="informe" accept=".pdf" class="hidden"
                            onchange="this.previousElementSibling.textContent = this.files[0] ? this.files[0].name : 'Seleccionar informe'">
                    </label>
                </div>
                <!-- Factura -->
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Factura</label>
                    <label class="flex items-center gap-3 w-full px-4 py-3 bg-zinc-800 border border-zinc-700 border-dashed rounded-xl cursor-pointer hover:border-indigo-500/50 transition-all group">
                        <div class="p-1.5 bg-zinc-700 rounded-lg group-hover:bg-indigo-600/10 transition-colors">
                            <svg class="w-4 h-4 text-gray-500 group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                        </div>
                        <span class="text-sm text-gray-400 group-hover:text-gray-300">Seleccionar factura</span>
                        <input type="file" name="factura" accept=".pdf,.jpg,.jpeg,.png" class="hidden"
                            onchange="this.previousElementSibling.textContent = this.files[0] ? this.files[0].name : 'Seleccionar factura'">
                    </label>
                </div>
                <!-- Comprobante -->
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Comprobante de Pago</label>
                    <label class="flex items-center gap-3 w-full px-4 py-3 bg-zinc-800 border border-zinc-700 border-dashed rounded-xl cursor-pointer hover:border-indigo-500/50 transition-all group">
                        <div class="p-1.5 bg-zinc-700 rounded-lg group-hover:bg-indigo-600/10 transition-colors">
                            <svg class="w-4 h-4 text-gray-500 group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                        </div>
                        <span class="text-sm text-gray-400 group-hover:text-gray-300">Seleccionar comprobante</span>
                        <input type="file" name="comprobante_pago" accept=".pdf,.jpg,.jpeg,.png" class="hidden"
                            onchange="this.previousElementSibling.textContent = this.files[0] ? this.files[0].name : 'Seleccionar comprobante'">
                    </label>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-zinc-800 bg-zinc-900/50">
                <button type="button" onclick="document.getElementById('documentacion-modal').classList.add('hidden')"
                    class="px-4 py-2.5 text-sm font-medium text-gray-400 hover:text-gray-200 bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 rounded-xl transition-all">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-600/20 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                    Subir
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentTurnoId = null;

    function openRevisarCoberturaModal(turnoId) {
        currentTurnoId = turnoId;
        const modal = document.getElementById('revisar-cobertura-modal');
        const content = document.getElementById('revisar-cobertura-content');
        content.innerHTML = '<div class="flex items-center justify-center py-8"><div class="flex items-center gap-3 text-gray-400"><svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="text-sm">Cargando...</span></div></div>';
        document.getElementById('taller-contact-actions').classList.add('hidden');
        modal.classList.remove('hidden');

        fetch('{{ url("/rodados/turnos") }}' + '/' + turnoId, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(turno => {
            const estadoColor = turno.cobertura_estado === 'aprobada' ? 'emerald' : turno.cobertura_estado === 'rechazada' ? 'red' : 'amber';
            content.innerHTML = `
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-zinc-800/50 rounded-xl border border-zinc-700/30">
                        <div class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Vehículo</div>
                        <div class="text-sm font-medium text-gray-200">${turno.rodado?.patente || 'Sin patente'}</div>
                        <div class="text-xs text-gray-500">${turno.rodado?.marca || ''} ${turno.rodado?.modelo || ''}</div>
                    </div>
                    <div class="p-3 bg-zinc-800/50 rounded-xl border border-zinc-700/30">
                        <div class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Taller</div>
                        <div class="text-sm font-medium text-gray-200">${turno.taller?.nombre || 'N/A'}</div>
                    </div>
                    <div class="p-3 bg-zinc-800/50 rounded-xl border border-zinc-700/30">
                        <div class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Fecha</div>
                        <div class="text-sm font-medium text-gray-200">${turno.fecha_hora || 'N/A'}</div>
                    </div>
                    <div class="p-3 bg-zinc-800/50 rounded-xl border border-zinc-700/30">
                        <div class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Cobertura</div>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-medium bg-${estadoColor}-500/10 text-${estadoColor}-400 border border-${estadoColor}-500/20">
                            <div class="w-1.5 h-1.5 rounded-full bg-${estadoColor}-400"></div>
                            ${turno.cobertura_estado || 'pendiente'}
                        </span>
                    </div>
                </div>
                <div class="p-3 bg-zinc-800/50 rounded-xl border border-zinc-700/30">
                    <div class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Descripción</div>
                    <div class="text-sm text-gray-300">${turno.descripcion || 'Sin descripción'}</div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-zinc-800/50 rounded-xl border border-zinc-700/30">
                        <div class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Encargado Dejar</div>
                        <div class="text-sm text-gray-300">${turno.encargado_dejar || 'N/A'}</div>
                    </div>
                    <div class="p-3 bg-zinc-800/50 rounded-xl border border-zinc-700/30">
                        <div class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Encargado Retirar</div>
                        <div class="text-sm text-gray-300">${turno.encargado_retirar || 'N/A'}</div>
                    </div>
                </div>
                ${turno.partes_afectadas && turno.partes_afectadas.length > 0 ? `
                <div>
                    <div class="text-[10px] text-gray-500 uppercase tracking-wider mb-2">Partes Afectadas</div>
                    <div class="space-y-1.5">
                        ${turno.partes_afectadas.map(p => `
                            <div class="flex items-center justify-between p-2.5 bg-zinc-800/50 rounded-xl border border-zinc-700/30 text-sm">
                                <span class="text-gray-200 font-medium">${p.item || ''}</span>
                                <span class="text-xs text-gray-500">x${p.cantidad || ''} — ${p.descripcion || ''}</span>
                            </div>`).join('')}
                    </div>
                </div>` : ''}
            `;
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = '<div class="text-center py-8"><p class="text-red-400 text-sm">Error al cargar los detalles.</p></div>';
        });
    }

    function closeRevisarCoberturaModal() {
        document.getElementById('revisar-cobertura-modal').classList.add('hidden');
        currentTurnoId = null;
    }

    function aprobarCobertura() {
        if (!currentTurnoId) return;
        fetch(`{{ url('/rodados/turnos') }}/${currentTurnoId}/aprobar-cobertura`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { alert(data.message); showTallerActions(data.taller_whatsapp, data.taller_email); setTimeout(() => location.reload(), 1500); }
            else if (data.error) alert(data.error);
        })
        .catch(error => { console.error('Error:', error); alert('Error al aprobar la cobertura.'); });
    }

    function rechazarCobertura() {
        if (!currentTurnoId) return;
        if (!confirm('¿Rechazar la cobertura? Se notificará al cliente.')) return;
        fetch(`{{ url('/rodados/turnos') }}/${currentTurnoId}/rechazar-cobertura`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { alert(data.message); showTallerActions(data.taller_whatsapp, data.taller_email); setTimeout(() => location.reload(), 1500); }
            else if (data.error) alert(data.error);
        })
        .catch(error => { console.error('Error:', error); alert('Error al rechazar la cobertura.'); });
    }

    function showTallerActions(whatsapp, email) {
        const container = document.getElementById('taller-contact-actions');
        container.classList.remove('hidden');
        const whatsappLink = document.getElementById('taller-whatsapp-link');
        const emailLink = document.getElementById('taller-email-link');
        if (whatsapp) { whatsappLink.href = whatsapp; whatsappLink.classList.remove('hidden'); }
        if (email) { emailLink.href = email; emailLink.classList.remove('hidden'); }
    }

    function openDocumentacionModal(turnoId) {
        const form = document.getElementById('documentacion-form');
        form.action = `{{ url('/rodados/turnos') }}/${turnoId}/documentacion`;
        document.getElementById('documentacion-modal').classList.remove('hidden');
    }
</script>
