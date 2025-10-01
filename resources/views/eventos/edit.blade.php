<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Editar Evento #{{ str_pad($evento->id, 4, '0', STR_PAD_LEFT) }}</h2>
                        <a href="{{ route('eventos.index') }}" class="text-blue-400 hover:text-blue-300 flex items-center">
                            <i class="bi bi-arrow-left mr-2"></i> Volver al listado
                        </a>
                    </div>

                    <div id="debug-output" class="mb-4 p-3 bg-gray-800 text-sm text-gray-300 rounded-md"></div>

                    <form id="evento-edit-form" action="{{ route('eventos.update', $evento) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                                <p class="font-bold">Error</p>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <!-- 1. Categoria -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">1. ¿A qué categoría pertenece el evento? <span class="text-red-500">*</span></h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($categorias as $categoria)
                                <div class="flex items-center">
                                    <input id="categoria-{{ Str::slug($categoria->nombre) }}" name="categoria_id" type="radio"
                                        data-nombre="{{ $categoria->nombre }}"
                                        value="{{ $categoria->id }}" 
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-600" required
                                        @if($evento->categoria_id == $categoria->id) checked @endif>
                                    <label for="categoria-{{ Str::slug($categoria->nombre) }}" class="ml-3 block text-sm font-medium text-gray-300">
                                        {{ $categoria->nombre }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('categoria')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sección 2: Tipo de Evento (dinámico) -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">2. ¿A qué tipo pertenece el evento? <span class="text-red-500">*</span></h3>
                            <div id="tipos-container">
                                @if($evento->categoria)
                                    @foreach($tiposPorCategoria[$evento->categoria->nombre] ?? [] as $tipo)
                                    <div class="flex items-center mb-2">
                                        <input type="radio" id="tipo-{{ Str::slug($tipo) }}" name="tipo" 
                                               value="{{ $tipo }}"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-600" required
                                               @if($evento->tipo == $tipo) checked @endif>
                                        <label for="tipo-{{ Str::slug($tipo) }}" class="ml-3 block text-sm font-medium text-gray-300">
                                            {{ $tipo }}
                                        </label>
                                    </div>
                                    @endforeach
                                @else
                                    <p class="text-gray-400 italic">Seleccione primero una categoría</p>
                                @endif
                            </div>
                            @error('tipo')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sección 3: Fecha y Hora -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">3. Fecha y hora del incidente <span class="text-red-500">*</span></h3>
                            <input type="datetime-local" name="fecha_hora" id="fecha_hora"
                                   class="mt-1 block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2"
                                   value="{{ \Carbon\Carbon::parse($evento->fecha_hora)->format('Y-m-d\TH:i') }}" required>
                            @error('fecha_hora')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sección 4: Coordenadas -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">4. Ubicación <span class="text-red-500">*</span></h3>
                            <div class="mb-4 bg-gray-800 p-3 rounded-md">
                                <p class="text-sm text-gray-300 mb-2">Cómo obtener las coordenadas:</p>
                                <ol class="list-decimal list-inside text-sm text-gray-400 space-y-1">
                                    <li>Entrar a <a href="https://www.google.com/maps" target="_blank" class="text-blue-400 hover:underline">Google Maps</a></li>
                                    <li>Ubicar el punto deseado en el mapa</li>
                                    <li>Apretar click derecho sobre el lugar exacto</li>
                                    <li>Seleccionar la primera opción (copiar coordenadas)</li>
                                    <li>Pegarlas en el campo debajo</li>
                                </ol>
                                <p class="text-sm text-yellow-400 mt-2">Ejemplo: -38.88266056726054, -68.0446703200311</p>
                            </div>
                            <input type="text" name="coordenadas" id="coordenadas" placeholder="Pegue las coordenadas aquí"
                                   class="mt-1 block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2"
                                   value="{{ $evento->latitud }}, {{ $evento->longitud }}" required
                                   pattern="^-?\d{1,3}\.\d+,\s-?\d{1,3}\.\d+$"
                                   title="Formato requerido: latitud, longitud (ejemplo: -38.88266056726054, -68.0446703200311)">
                            @error('coordenadas')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sección 5: Cliente -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">5. Cliente <span class="text-red-500">*</span></h3>
                            <select name="cliente_id" id="cliente_id" required
                                    class="mt-1 block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                                <option value="">Seleccione un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" @if($evento->cliente_id == $cliente->id) selected @endif
                                        data-empresas="{{ $cliente->empresasAsociadas->pluck('nombre', 'id') }}">
                                        {{ $cliente->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sección 5.1: Empresa Asociada -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">5.1 Empresa Asociada al Cliente</h3>
                            <select name="empresa_asociada_id" id="empresa_asociada_id"
                                    class="mt-1 block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                                <option value="">Seleccione una empresa asociada al cliente</option>
                                @foreach($empresas as $empresa)
                                    <option value="{{ $empresa->id }}" @if($evento->empresa_asociada_id == $empresa->id) selected @endif>
                                        {{ $empresa->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('empresa_asociada_id')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sección 6: Supervisor -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">6. Supervisor <span class="text-red-500">*</span></h3>
                            <select name="supervisor_id" id="supervisor_id" required
                                    class="mt-1 block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                                <option value="">Seleccione un supervisor</option>
                                @foreach($supervisores as $supervisor)
                                    <option value="{{ $supervisor->id }}" @if($evento->supervisor_id == $supervisor->id) selected @endif>
                                        {{ $supervisor->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supervisor_id')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-1">7. Descripción <span class="text-red-500">*</span></h3>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Escriba en detalle lo sucedido en el evento o incidente.</label>
                            <textarea name="descripcion" id="descripcion"
                                      class="mt-1 block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">{{ old('descripcion', $evento->descripcion) }}</textarea>
                        </div>

                        <div class="bg-gray-700 p-4 rounded-lg" id="elementos-sustraidos">
                            <h3 class="text-lg font-medium text-white mb-4">7.1 Elementos Sustraídos</h3>
                            <p class="text-sm text-gray-300 mb-4">Complete esta sección solo si el evento involucra elementos sustraídos (opcional).</p>
                            
                            <div id="elementos-container">
                                <div class="elemento-row grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-2">Elemento</label>
                                        <input type="text" name="elementos[]" placeholder="Ej: rueda, batería, linterna..."
                                            class="block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-2">Cantidad</label>
                                        <div class="flex">
                                            <input type="number" name="cantidades[]" min="1" value="1"
                                                class="block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                                            <button type="button" class="remove-elemento-btn ml-2 px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors" title="Eliminar elemento" style="display: none;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" id="add-elemento-btn" 
                                class="mt-2 px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm">
                            <i class="bi bi-plus-circle mr-1"></i> Añadir
                        </button>
                            
                            @error('elementos')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            @error('cantidades')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Sección 7: Observaciones -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">8. Observaciones Adicionales</h3>
                            <textarea name="observaciones" id="observaciones" rows="3"
                                      class="mt-1 block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">{{ old('observaciones', $evento->observaciones) }}</textarea>
                        </div>

                        <!-- Sección 8: Link del Reporte -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">9. Link del reporte</h3>
                            <input type="url" name="url_reporte" id="url_reporte" placeholder="https://drive.google.com/..."
                                   class="mt-1 block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2"
                                   value="{{ $evento->url_reporte }}">
                        </div>

                        <!-- Sección 9: Multimedia -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">10. Multimedia</h3>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Agregar más imágenes (JPG, PNG - Máx. 2MB c/u)</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-600 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="text-sm text-gray-400 text-center">
                                            <label for="media" class="relative cursor-pointer bg-gray-700 rounded-md font-medium text-blue-400 hover:text-blue-300 focus-within:outline-none">
                                                <span>Subir archivos</span>
                                                <input id="media" name="media[]" type="file" class="sr-only" multiple accept="image/jpeg,image/png">
                                            </label>
                                            <p class="text-xs"> mantené Ctrl + click izquierdo para seleccionar multiples</p>
                                        </div>
                                        <p class="text-xs text-gray-400">PNG, JPG hasta 2MB</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Mostrar imágenes existentes -->
                            @if($evento->media->count() > 0)
                            <div class="mt-4">
                                <h4 class="text-md font-medium text-gray-300 mb-2">Imágenes existentes</h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach($evento->media as $media)
                                    <div class="relative group">
                                        <img src="{{ Storage::url($media->file_path) }}" alt="Imagen del evento {{ $media->file_name }}" class="w-full h-32 object-cover rounded-md">
                                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('media.eventos.destroy', $media) }}" onclick="return confirm('¿Estás seguro de eliminar la imagen?')"
                                               class="delete-media-btn text-red-500 hover:text-red-400">
                                                <i class="bi bi-trash text-xl"></i>
                
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <!-- Vista previa de nuevas imágenes -->
                            <div id="preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 hidden">
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex justify-end space-x-4 pt-4">
                            <a href="{{ route('eventos.index') }}" 
                               onclick="return confirm('¿Estás seguro de cancelar los cambios? Los cambios no guardados se perderán.')"
                               class="px-4 py-2 border border-gray-600 rounded-md text-gray-300 bg-gray-700 hover:bg-gray-600">
                               Cancelar
                            </a>

                            <button type="submit" 
                                    id="actualizar-evento-btn"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                <span id="btn-text"><i class="bi bi-save mr-2"></i> Actualizar Evento</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Mapeo de categorías a tipos (debe coincidir con el del controlador)
        const tiposPorCategoria = {
            'Seguridad física': [
                'Robo o intento de robo',
                'Intrusión o acceso no autorizado',
                'Sabotaje o vandalismo',
                'Daños a instalaciones o equipos',
                'Hallazgo de objetos sospechosos'
            ],
            'Incidentes con el personal': [
                'Ausencias o abandono de puesto',
                'Conflictos entre vigiladores',
                'Lesiones o accidentes laborales',
                'Mal uso del uniforme o equipamiento',
                'Incumplimiento de procedimientos'
            ],
            'Seguridad vial y patrullaje': [
                'Accidente vehicular (leve, moderado o grave)',
                'Retención de unidad por autoridad',
                'Falla mecánica en ruta',
                'Vehículo fuera de recorrido',
                'Velocidad inadecuada o uso indebido del móvil'
            ],
            'Tecnológicos/comunicación': [
                'Falla en cámaras o GPS',
                'Cortes de conectividad (Starlink, datos, etc.)',
                'Fallos en sistemas de ronda digital o QR',
                'Problemas con radios o teléfonos',
                'Errores en reportes digitales'
            ],
            'Entorno y contexto': [
                'Cortes de ruta o piquetes',
                'Condiciones climáticas extremas',
                'Riesgos naturales (viento Zonda, tormentas, etc.)',
                'Manifestaciones o bloqueos externos',
                'Animales peligrosos (en zonas rurales)'
            ],
            'Administrativos/reportables al cliente': [
                'Demoras en relevos',
                'Incumplimiento del servicio contratado',
                'Problemas de cobertura',
                'Quejas del cliente',
                'Notificaciones a ART / aseguradoras'
            ]
        };

        // Función para cargar tipos basados en categoría seleccionada
        function cargarTipos(categoria) {
            const tiposContainer = document.getElementById('tipos-container');
            tiposContainer.innerHTML = '';
            
            const tipos = tiposPorCategoria[categoria];
            if (tipos) {
                tipos.forEach(tipo => {
                    const div = document.createElement('div');
                    div.className = 'flex items-center mb-2';
                    
                    const input = document.createElement('input');
                    input.type = 'radio';
                    input.id = `tipo-${tipo.replace(/\s+/g, '-').toLowerCase()}`;
                    input.name = 'tipo';
                    input.value = tipo;
                    input.className = 'h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-600';
                    input.required = true;

                    input.addEventListener('change', toggleElementosSustraidos);
                    
                    // Marcar como seleccionado si coincide con el tipo actual
                    if (tipo === "{{ $evento->tipo }}") {
                        input.checked = true;
                    }
                    
                    const label = document.createElement('label');
                    label.htmlFor = input.id;
                    label.className = 'ml-3 block text-sm font-medium text-gray-300';
                    label.textContent = tipo;
                    
                    div.appendChild(input);
                    div.appendChild(label);
                    tiposContainer.appendChild(div);
                });

                setTimeout(toggleElementosSustraidos, 100);
            } else {
                tiposContainer.innerHTML = '<p class="text-gray-400 italic">Seleccione primero una categoría</p>';
            }
        }

        // Escuchar cambios en la selección de categoría
        document.querySelectorAll('input[name="categoria_id"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const nombreCategoria = this.dataset.nombre;
                cargarTipos(nombreCategoria);
            });
        });

        // Cargar tipos iniciales si hay una categoría seleccionada
        document.addEventListener('DOMContentLoaded', function() {
            const categoriaSeleccionada = document.querySelector('input[name="categoria_id"]:checked');
            if (categoriaSeleccionada) {
                cargarTipos(categoriaSeleccionada.dataset.nombre);
            }
        });

        //mostrar/oculatar elementos
        function toggleElementosSustraidos() {
            const elementosSection = document.getElementById('elementos-sustraidos');
            
            const tiposConElementos = [
                'tipo-robo-o-intento-de-robo',
                'tipo-sabotaje-o-vandalismo',
                'tipo-daños-a-instalaciones-o-equipos',
                'tipo-hallazgo-de-objetos-sospechosos'
            ];
            
            // Verificar si alguno de los tipos relevantes está seleccionado
            let mostrarSeccion = false;
            tiposConElementos.forEach(tipoId => {
                const radioElement = document.getElementById(tipoId);
                if (radioElement && radioElement.checked) {
                    mostrarSeccion = true;
                }
            });
            
            if (mostrarSeccion) {
                elementosSection.classList.remove('hidden');
            } else {
                elementosSection.classList.add('hidden');
                // Limpiar los campos si se oculta la sección
                document.getElementById('elementos-container').innerHTML = `
                    <div class="elemento-row grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Elemento</label>
                            <input type="text" name="elementos[]" placeholder="Ej: rueda, batería, linterna..."
                                class="block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Cantidad</label>
                            <div class="flex items-center">
                                <input type="number" name="cantidades[]" min="1" value="1"
                                    class="block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                                <button type="button" class="remove-elemento-btn ml-2 px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors" title="Eliminar elemento" style="display: none;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }
        }


        // Cargar elementos sustraídos existentes al editar
        document.addEventListener('DOMContentLoaded', function() {
            @if($evento->elementos_sustraidos && $evento->cantidad)
                const elementos = @json($evento->elementos_sustraidos);
                const cantidades = @json($evento->cantidad);
                
                if (elementos && elementos.length > 0) {
                    const container = document.getElementById('elementos-container');
                    container.innerHTML = ''; // Limpiar contenedor
                    
                    elementos.forEach((elemento, index) => {
                        if (elemento && cantidades[index]) {
                            const newRow = document.createElement('div');
                            newRow.className = 'elemento-row grid grid-cols-1 md:grid-cols-2 gap-4 mb-4';
                            newRow.innerHTML = `
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Elemento</label>
                                    <input type="text" name="elementos[]" value="${elemento}" placeholder="Ej: rueda, batería, linterna..."
                                        class="block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Cantidad</label>
                                    <div class="flex items-center">
                                        <input type="number" name="cantidades[]" min="1" value="${cantidades[index]}"
                                            class="block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                                        <button type="button" class="remove-elemento-btn ml-2 px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors" title="Eliminar elemento">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            `;
                            container.appendChild(newRow);
                            
                            // Añadir listener al botón de eliminar
                            const removeBtn = newRow.querySelector('.remove-elemento-btn');
                            removeBtn.addEventListener('click', function() {
                                newRow.remove();
                                updateRemoveButtons();
                            });
                        }
                    });
                    
                    updateRemoveButtons();
                }
            @endif
        });

        // Función para actualizar visibilidad de botones de eliminar
        function updateRemoveButtons() {
            const rows = document.querySelectorAll('.elemento-row');
            rows.forEach((row, index) => {
                const removeBtn = row.querySelector('.remove-elemento-btn');
                if (rows.length > 1) {
                    removeBtn.style.display = 'block';
                } else {
                    removeBtn.style.display = 'none';
                }
            });
        }

        // Función para añadir nueva fila de elemento
        function addElementoRow() {
            const container = document.getElementById('elementos-container');
            const newRow = document.createElement('div');
            newRow.className = 'elemento-row grid grid-cols-1 md:grid-cols-2 gap-4 mb-4';
            newRow.innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Elemento</label>
                    <input type="text" name="elementos[]" placeholder="Ej: rueda, batería, linterna..."
                        class="block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Cantidad</label>
                    <div class="flex items-center">
                        <input type="number" name="cantidades[]" min="1" value="1"
                            class="block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                        <button type="button" class="remove-elemento-btn ml-2 px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors" title="Eliminar elemento">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            container.appendChild(newRow);
            
            // Añadir listener al botón de eliminar
            const removeBtn = newRow.querySelector('.remove-elemento-btn');
            removeBtn.addEventListener('click', function() {
                newRow.remove();
                updateRemoveButtons();
            });
            
            updateRemoveButtons();
        }

        // Event listener para el botón de agregar elemento
        document.getElementById('add-elemento-btn').addEventListener('click', addElementoRow);

        // Campo dinamico de empresa asociada
        const clienteSelect = document.getElementById('cliente_id');
        const empresaSelect = document.getElementById('empresa_asociada_id');

        if (clienteSelect && empresaSelect) {
            clienteSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                
                // Limpiar opciones actuales
                empresaSelect.innerHTML = '<option value="">Seleccione una empresa asociada</option>';
                
                if (this.value) {
                    // Obtener empresas del data-attribute
                    const empresas = JSON.parse(selectedOption.getAttribute('data-empresas') || '{}');
                    
                    // Agregar nuevas opciones
                    Object.entries(empresas).forEach(([id, nombre]) => {
                        const option = document.createElement('option');
                        option.value = id;
                        option.textContent = nombre;
                        empresaSelect.appendChild(option);
                    });
                }
            });

            // Disparar el evento change si hay un cliente seleccionado
            if (clienteSelect.value) {
                clienteSelect.dispatchEvent(new Event('change'));
                
                // Seleccionar la empresa asociada si existe
                const empresaId = "{{ $evento->empresa_asociada_id }}";
                if (empresaId) {
                    setTimeout(() => {
                        empresaSelect.value = empresaId;
                    }, 100);
                }
            }
        }

        // Vista previa de nuevas imágenes
        document.getElementById('media').addEventListener('change', function(e) {
            const previewContainer = document.getElementById('preview-container');
            previewContainer.innerHTML = '';
            previewContainer.classList.add('hidden');
            
            if (this.files.length > 0) {
                previewContainer.classList.remove('hidden');
                
                Array.from(this.files)
                    .filter(file => file.type.match('image.*'))
                    .forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'relative group';
                            
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'h-32 w-full object-cover rounded-md';
                            
                            const removeBtn = document.createElement('button');
                            removeBtn.type = 'button';
                            removeBtn.className = 'absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center';
                            removeBtn.innerHTML = '&times;';
                            removeBtn.onclick = function() {
                                div.remove();
                                if (previewContainer.children.length === 0) {
                                    previewContainer.classList.add('hidden');
                                }
                            };
                        
                            div.appendChild(img);
                            div.appendChild(removeBtn);

                            const fileNumber = document.createElement('div');
                            fileNumber.className = 'absolute bottom-1 left-1 bg-black bg-opacity-70 text-white text-xs px-1 rounded';
                            fileNumber.textContent = `${index + 1}`;
                            div.appendChild(fileNumber);

                            previewContainer.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
            }
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            onsole.log('Formulario enviado');
        });

    </script>
    @endpush
</x-app-layout>