<!-- Modal reutilizable para adjuntar factura -->
<div id="adjuntar-factura-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center modal-backdrop" style="padding-left: calc(var(--fb-sidebar-width, 240px) + 1rem); padding-top: calc(var(--fb-topbar-height, 60px) + 1.5rem); padding-right: 1rem; padding-bottom: 1rem;" onclick="if(event.target === this) closeAdjuntarFacturaModal()">
    <div class="w-full max-w-md bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden modal-content" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-violet-600/10 rounded-lg">
                    <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                </div>
                <h3 id="adjuntar-factura-modal-title" class="text-lg font-semibold text-gray-100">Adjuntar Documentación</h3>
            </div>
            <button onclick="closeAdjuntarFacturaModal()"
                class="p-2 text-gray-500 hover:text-gray-300 hover:bg-zinc-800 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-5 max-h-[65vh] overflow-y-auto modal-scroll">
            <form id="adjuntar-factura-form" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="adjuntar-factura-tipo" name="tipo">
                <input type="hidden" id="adjuntar-factura-id" name="id">

                <div class="space-y-5">
                    <!-- Factura -->
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Factura *</label>
                        <label class="flex items-center gap-3 w-full px-4 py-3 bg-zinc-800 border border-zinc-700 border-dashed rounded-xl cursor-pointer hover:border-violet-500/50 hover:bg-zinc-800/80 transition-all group" id="factura-upload-label">
                            <div class="p-2 bg-zinc-700 rounded-lg group-hover:bg-violet-600/10 transition-colors">
                                <svg class="w-5 h-5 text-gray-500 group-hover:text-violet-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-300 group-hover:text-gray-200" id="factura-file-label">Seleccionar archivo de factura</p>
                                <p class="text-xs text-gray-600">PDF, JPG, PNG - Máx. 10MB</p>
                            </div>
                            <input type="file" id="adjuntar-factura-file" name="factura" accept=".pdf,.jpg,.jpeg,.png" required class="hidden"
                                onchange="handleFileSelect(this, 'factura-file-label', 'factura-preview')">
                        </label>
                        <div id="factura-preview" class="mt-2"></div>
                    </div>

                    <!-- Comprobante de Pago -->
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Comprobante de Pago <span class="text-gray-600 normal-case">(opcional)</span></label>
                        <label class="flex items-center gap-3 w-full px-4 py-3 bg-zinc-800 border border-zinc-700 border-dashed rounded-xl cursor-pointer hover:border-violet-500/50 hover:bg-zinc-800/80 transition-all group">
                            <div class="p-2 bg-zinc-700 rounded-lg group-hover:bg-violet-600/10 transition-colors">
                                <svg class="w-5 h-5 text-gray-500 group-hover:text-violet-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-300 group-hover:text-gray-200" id="comprobante-file-label">Seleccionar comprobante</p>
                                <p class="text-xs text-gray-600">PDF, JPG, PNG - Máx. 10MB</p>
                            </div>
                            <input type="file" id="adjuntar-comprobante-file" name="comprobante_pago" accept=".pdf,.jpg,.jpeg,.png" class="hidden"
                                onchange="handleFileSelect(this, 'comprobante-file-label', 'comprobante-preview')">
                        </label>
                        <div id="comprobante-preview" class="mt-2"></div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-zinc-800 bg-zinc-900/50">
            <button type="button" onclick="closeAdjuntarFacturaModal()"
                class="px-4 py-2.5 text-sm font-medium text-gray-400 hover:text-gray-200 bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 rounded-xl transition-all">
                Cancelar
            </button>
            <button type="submit" form="adjuntar-factura-form"
                class="px-5 py-2.5 text-sm font-medium text-white bg-violet-600 hover:bg-violet-700 rounded-xl shadow-lg shadow-violet-600/20 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Guardar
            </button>
        </div>
    </div>
</div>

<script>
    function handleFileSelect(input, labelId, previewId) {
        const label = document.getElementById(labelId);
        const preview = document.getElementById(previewId);
        if (!preview) return;
        preview.innerHTML = '';

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            const allowedExtensions = ['.pdf', '.jpg', '.jpeg', '.png'];
            const fileExtension = '.' + file.name.split('.').pop().toLowerCase();

            if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
                alert('Solo se permiten PDF, JPG, JPEG y PNG.'); input.value = ''; return;
            }
            if (file.size > 10 * 1024 * 1024) {
                alert('Máximo 10MB.'); input.value = ''; return;
            }

            if (label) label.textContent = file.name;

            const reader = new FileReader();
            reader.onerror = function() { alert('Error al leer el archivo.'); };
            reader.onload = function(e) {
                if (file.type.startsWith('image/')) {
                    preview.innerHTML = `<img src="${e.target.result}" class="max-w-full max-h-32 rounded-xl border border-zinc-700">`;
                } else {
                    preview.innerHTML = `<div class="flex items-center gap-2 p-2.5 bg-zinc-800 rounded-xl border border-zinc-700 text-xs text-gray-400"><svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>${file.name} (${(file.size / 1024).toFixed(1)} KB)</div>`;
                }
            };
            reader.readAsDataURL(file);
        } else {
            if (label) label.textContent = labelId.includes('factura') ? 'Seleccionar archivo de factura' : 'Seleccionar comprobante';
        }
    }

    function openAdjuntarFacturaModal(tipo, id) {
        const modal = document.getElementById('adjuntar-factura-modal');
        const form = document.getElementById('adjuntar-factura-form');
        document.getElementById('adjuntar-factura-tipo').value = tipo;
        document.getElementById('adjuntar-factura-id').value = id;

        if (tipo === 'turno') form.action = '{{ route("rodados.turnos.adjuntar-factura", ":id") }}'.replace(':id', id);
        else if (tipo === 'cambio_equipo') form.action = '{{ route("rodados.cambios-equipos.adjuntar-factura", ":id") }}'.replace(':id', id);
        else if (tipo === 'pago') form.action = '{{ route("rodados.pagos.adjuntar-factura", ":id") }}'.replace(':id', id);

        modal.classList.remove('hidden');
    }

    function closeAdjuntarFacturaModal() {
        document.getElementById('adjuntar-factura-modal').classList.add('hidden');
        document.getElementById('adjuntar-factura-form').reset();
        document.getElementById('factura-preview').innerHTML = '';
        document.getElementById('comprobante-preview').innerHTML = '';
        document.getElementById('factura-file-label').textContent = 'Seleccionar archivo de factura';
        document.getElementById('comprobante-file-label').textContent = 'Seleccionar comprobante';
    }

    document.getElementById('adjuntar-factura-form')?.addEventListener('submit', function(e) {
        const facturaInput = document.getElementById('adjuntar-factura-file');
        const tipo = document.getElementById('adjuntar-factura-tipo')?.value;
        const id = document.getElementById('adjuntar-factura-id')?.value;

        if (!tipo || !id) { e.preventDefault(); alert('Error: Información del servicio inválida.'); return false; }
        if (!facturaInput?.files?.[0]) { e.preventDefault(); alert('Seleccione un archivo de factura.'); return false; }

        const file = facturaInput.files[0];
        if (file.size > 10 * 1024 * 1024) { e.preventDefault(); alert('Factura demasiado grande. Máx. 10MB.'); return false; }

        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        const ext = '.' + file.name.split('.').pop().toLowerCase();
        if (!allowedTypes.includes(file.type) && !['.pdf', '.jpg', '.jpeg', '.png'].includes(ext)) {
            e.preventDefault(); alert('Tipo de archivo no permitido.'); return false;
        }

        const comprobanteInput = document.getElementById('adjuntar-comprobante-file');
        if (comprobanteInput?.files?.[0]) {
            const cf = comprobanteInput.files[0];
            if (cf.size > 10 * 1024 * 1024) { e.preventDefault(); alert('Comprobante demasiado grande. Máx. 10MB.'); return false; }
            const cExt = '.' + cf.name.split('.').pop().toLowerCase();
            if (!allowedTypes.includes(cf.type) && !['.pdf', '.jpg', '.jpeg', '.png'].includes(cExt)) {
                e.preventDefault(); alert('Tipo de comprobante no permitido.'); return false;
            }
        }
    });
</script>
