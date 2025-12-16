<!-- Modal reutilizable para adjuntar factura -->
<div id="adjuntar-factura-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-gray-800 border-gray-700">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 id="adjuntar-factura-modal-title" class="text-lg font-medium text-gray-100">Adjuntar Factura</h3>
                <button onclick="closeAdjuntarFacturaModal()"
                    class="text-gray-400 hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="adjuntar-factura-form" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="adjuntar-factura-tipo" name="tipo">
                <input type="hidden" id="adjuntar-factura-id" name="id">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Factura (PDF, JPG, PNG) *</label>
                        <input type="file" id="adjuntar-factura-file" name="factura" accept=".pdf,.jpg,.jpeg,.png" required
                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500"
                            onchange="previewFile(this, 'factura-preview')">
                        <div id="factura-preview" class="mt-2"></div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Comprobante de Pago (PDF, JPG, PNG) (opcional)</label>
                        <input type="file" id="adjuntar-comprobante-file" name="comprobante_pago" accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500"
                            onchange="previewFile(this, 'comprobante-preview')">
                        <div id="comprobante-preview" class="mt-2"></div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeAdjuntarFacturaModal()"
                        class="px-4 py-2 bg-gray-700 text-gray-300 rounded-md hover:bg-gray-600 transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openAdjuntarFacturaModal(tipo, id) {
        const modal = document.getElementById('adjuntar-factura-modal');
        const form = document.getElementById('adjuntar-factura-form');
        const tipoInput = document.getElementById('adjuntar-factura-tipo');
        const idInput = document.getElementById('adjuntar-factura-id');
        
        tipoInput.value = tipo;
        idInput.value = id;

        // Establecer la acción del formulario según el tipo
        if (tipo === 'turno') {
            form.action = '{{ route("rodados.turnos.adjuntar-factura", ":id") }}'.replace(':id', id);
        } else if (tipo === 'cambio_equipo') {
            form.action = '{{ route("rodados.cambios-equipos.adjuntar-factura", ":id") }}'.replace(':id', id);
        } else if (tipo === 'pago') {
            form.action = '{{ route("rodados.pagos.adjuntar-factura", ":id") }}'.replace(':id', id);
        }

        modal.classList.remove('hidden');
    }

    function closeAdjuntarFacturaModal() {
        document.getElementById('adjuntar-factura-modal').classList.add('hidden');
        document.getElementById('adjuntar-factura-form').reset();
        document.getElementById('factura-preview').innerHTML = '';
        document.getElementById('comprobante-preview').innerHTML = '';
    }

    function previewFile(input, previewId) {
        const preview = document.getElementById(previewId);
        if (!preview) return;
        
        preview.innerHTML = '';
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validar tipo de archivo
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            const allowedExtensions = ['.pdf', '.jpg', '.jpeg', '.png'];
            const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
            
            if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
                alert('Tipo de archivo no permitido. Solo se permiten PDF, JPG, JPEG y PNG.');
                input.value = '';
                return;
            }
            
            // Validar tamaño (10MB máximo)
            const maxSize = 10 * 1024 * 1024; // 10MB en bytes
            if (file.size > maxSize) {
                alert('El archivo es demasiado grande. El tamaño máximo permitido es 10MB.');
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            
            reader.onerror = function() {
                alert('Error al leer el archivo.');
                preview.innerHTML = '';
            };
            
            reader.onload = function(e) {
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'max-w-xs max-h-48 rounded-md border border-gray-600';
                    preview.appendChild(img);
                } else {
                    const div = document.createElement('div');
                    div.className = 'p-2 bg-gray-700 rounded-md text-sm text-gray-300';
                    div.textContent = `Archivo: ${file.name} (${(file.size / 1024).toFixed(2)} KB)`;
                    preview.appendChild(div);
                }
            };
            
            reader.readAsDataURL(file);
        }
    }

    // Validaciones del formulario de adjuntar factura
    document.getElementById('adjuntar-factura-form')?.addEventListener('submit', function(e) {
        const facturaInput = document.getElementById('adjuntar-factura-file');
        const tipo = document.getElementById('adjuntar-factura-tipo')?.value;
        const id = document.getElementById('adjuntar-factura-id')?.value;

        if (!tipo || !id) {
            e.preventDefault();
            alert('Error: Información del servicio inválida.');
            return false;
        }

        if (!facturaInput || !facturaInput.files || !facturaInput.files[0]) {
            e.preventDefault();
            alert('Por favor, seleccione un archivo de factura.');
            return false;
        }

        const file = facturaInput.files[0];
        const maxSize = 10 * 1024 * 1024; // 10MB
        
        if (file.size > maxSize) {
            e.preventDefault();
            alert('El archivo de factura es demasiado grande. El tamaño máximo permitido es 10MB.');
            return false;
        }

        // Validar tipo de archivo
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        const allowedExtensions = ['.pdf', '.jpg', '.jpeg', '.png'];
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        
        if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
            e.preventDefault();
            alert('Tipo de archivo no permitido para la factura. Solo se permiten PDF, JPG, JPEG y PNG.');
            return false;
        }

        // Validar comprobante si está presente
        const comprobanteInput = document.getElementById('adjuntar-comprobante-file');
        if (comprobanteInput && comprobanteInput.files && comprobanteInput.files[0]) {
            const comprobanteFile = comprobanteInput.files[0];
            
            if (comprobanteFile.size > maxSize) {
                e.preventDefault();
                alert('El archivo de comprobante es demasiado grande. El tamaño máximo permitido es 10MB.');
                return false;
            }

            if (!allowedTypes.includes(comprobanteFile.type) && !allowedExtensions.includes('.' + comprobanteFile.name.split('.').pop().toLowerCase())) {
                e.preventDefault();
                alert('Tipo de archivo no permitido para el comprobante. Solo se permiten PDF, JPG, JPEG y PNG.');
                return false;
            }
        }
    });
</script>

