@extends('layouts.cliente')
@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-zinc-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg"">
                <div class="p-6 text-gray-100 dark:text-gray-100">

                    <!-- Mensajes de sesión -->
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-800 border border-green-600 text-green-100 rounded-lg flex items-center justify-between shadow-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button type="button" class="text-green-300 hover:text-white" onclick="this.parentElement.style.display='none'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-800 border border-red-600 text-red-100 rounded-lg flex items-center justify-between shadow-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ session('error') }}</span>
                            </div>
                            <button type="button" class="text-red-300 hover:text-white" onclick="this.parentElement.style.display='none'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="mb-6 p-4 bg-blue-800 border border-blue-600 text-blue-100 rounded-lg flex items-center justify-between shadow-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ session('info') }}</span>
                            </div>
                            <button type="button" class="text-blue-300 hover:text-white" onclick="this.parentElement.style.display='none'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-800 border border-red-600 text-red-100 rounded-lg flex items-center justify-between shadow-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <ul class="list-disc list-inside">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="text-red-300 hover:text-white" onclick="this.parentElement.style.display='none'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Registrar Nuevo Evento</h2>
                        <a href="{{ route('client.eventos.index') }}" class="text-blue-400 hover:text-blue-300 flex items-center">
                            <i class="bi bi-arrow-left mr-2"></i> Volver al listado
                        </a>
                    </div>

                    <form action="{{ route('client.eventos.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        <!-- 1. Categoria -->
                        <div class="bg-zinc-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4"> 1. ¿A qué categoría pertenece el evento? <span class="text-red-500">*</span></h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($categorias as $categoria)
                                <div class="flex items-center">
                                    <input id="categoria-{{ Str::slug($categoria->nombre) }}" name="categoria_id" type="radio"
                                        data-nombre="{{ $categoria->nombre }}"
                                        value="{{ $categoria->id }}" 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-zinc-600" required
                                           @if(old('categoria') === $categoria->nombre) checked @endif>
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
                        <div class="bg-zinc-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">2. ¿A qué tipo pertenece el evento? <span class="text-red-500">*</span></h3>
                            <div id="tipos-container">
                                <!-- Opciones se cargarán dinámicamente con JavaScript -->
                                <p class="text-gray-400 italic">Seleccione primero una categoría</p>
                            </div>
                            @error('tipo')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sección 3: Fecha y Hora -->
                        <div class="bg-zinc-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">3. Fecha y hora del incidente <span class="text-red-500">*</span></h3>
                            <input type="datetime-local" name="fecha_hora" id="fecha_hora"
                                   class="mt-1 block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2"
                                   value="{{ old('fecha_hora') }}" required>
                            @error('fecha_hora')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sección 4: Coordenadas -->
                        <div class="bg-zinc-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">4. Ubicación <span class="text-red-500">*</span></h3>
                            <div class="mb-4 bg-zinc-800 p-3 rounded-md">
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
                                   class="mt-1 block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2"
                                   value="{{ old('coordenadas') }}" required
                                   pattern="^-?\d{1,3}\.\d+,\s-?\d{1,3}\.\d+$"
                                   title="Formato requerido: latitud, longitud (ejemplo: -38.88266056726054, -68.0446703200311)">
                            @error('coordenadas')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sección 5: Cliente -->
                        <div class="bg-zinc-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">5. Cliente <span class="text-red-500">*</span></h3>
                            <select name="cliente_id" id="cliente_id" required
                                    class="mt-1 block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                                <option value="">Seleccione un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}"
                                        @if(old('cliente_id') == $cliente->id) selected @endif
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
                        <div class="bg-zinc-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">5.1 Empresa Asociada</h3>
                            <select name="empresa_asociada_id" id="empresa_asociada_id"
                                    class="mt-1 block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                                <option value="">Seleccione una empresa asociada al cliente</option>
                                @foreach($empresas as $empresa)
                                    <option value="{{ $empresa->id }}" @if(old('empresa_asociada_id') == $empresa->id) selected @endif>
                                        {{ $empresa->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('empresa_asociada_id')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sección 6: Supervisor -->
                        <div class="bg-zinc-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">6. Supervisor <span class="text-red-500">*</span></h3>
                            <select name="supervisor_id" id="supervisor_id" required
                                    class="mt-1 block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                                <option value="">Seleccione un supervisor</option>
                                @foreach($supervisores as $supervisor)
                                    <option value="{{ $supervisor->id }}"
                                        @if(old('supervisor_id') == $supervisor->id) selected @endif
                                        data-cliente-id="{{ $supervisor->cliente_id }}">
                                        {{ $supervisor->nombre }} {{ $supervisor->apellido }} - {{ $supervisor->cliente->nombre ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supervisor_id')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-zinc-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-1">7. Descripción <span class="text-red-500">*</span></h3>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Escriba en detalle lo sucedido en el evento o incidente.</label>
                            <textarea name="descripcion" id="descripcion"
                                      class="mt-1 block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">{{ old('observaciones') }}</textarea>
                        </div>
                        <!-- Elementos Evento -->
                        <div class="bg-zinc-700 p-4 rounded-lg" id="elementos-sustraidos" >
                            <h3 class="text-lg font-medium text-white mb-4">7.1 Elementos Involucrados en el evento</h3>
                            <p class="text-sm text-gray-300 mb-4">Complete esta sección solo si el evento involucra elementos sustraídos, encontrados o dañados.</p>
                            
                            <div id="elementos-container">
                                <div class="elemento-row grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 fila-original">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-2">Elemento</label>
                                        <input type="text" name="elementos[]" placeholder="Ej: rueda, batería, linterna..."
                                            class="block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-2">Cantidad</label>
                                        <div class="flex">
                                            <input type="number" name="cantidades[]" min="1" value="1"
                                                class="block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                                            <button type="button" class="remove-elemento-btn ml-2 px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors" title="Eliminar elemento" style="display: none;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            
                            <button type="button" id="add-elemento-btn" 
                                class="mt-2 px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm">
                            <i class="bi bi-plus-circle mr-1"></i> Añadir
                        </button>
                            
                            @error('elementos')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            @error('cantidades')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Personas evento -->
                        <div class="bg-zinc-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">7.2 Personas Involucradas en el Evento</h3>
                           
                                
                            <div id="personas-container">
                                    <!-- Las filas de personas se agregarán dinámicamente aquí -->
                            </div>
                                
                            <button type="button" id="add-persona-btn" 
                                    class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                <i class="bi bi-person-plus mr-2"></i> Agregar Persona
                            </button>
                        </div>

                        <!-- Sección 7: Observaciones -->
                        <div class="bg-zinc-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">8. Observaciones Adicionales</h3>
                            <textarea name="observaciones" id="observaciones" rows="3"
                                      class="mt-1 block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">{{ old('observaciones') }}</textarea>
                        </div>

                        <!-- Sección 8: Link del Reporte -->
                        <div class="bg-zinc-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">9. Link del reporte</h3>
                            <input type="url" name="reporte_url" id="url_reporte" placeholder="https://drive.google.com/..."
                                   class="mt-1 block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2"
                                   value="{{ old('reporte_url') }}">
                        </div>

                        <!-- Sección 9: Multimedia -->
                         <div class="bg-zinc-700 p-4 rounded-lg">
                             <h3 class="text-lg font-medium text-white mb-4">10. Multimedia</h3>
                             <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Imágenes (JPG, PNG - Máx. 2MB c/u)</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-zinc-600 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="text-sm text-gray-400 text-center">
                                            <label for="media" class="relative cursor-pointer bg-zinc-700 rounded-md font-medium text-blue-400 hover:text-blue-300 focus-within:outline-none">
                                                <span>Subir archivos</span>
                                                <input id="media" name="media[]" type="file" class="sr-only" multiple accept="image/jpeg,image/png">
                                            </label>
                                            <p class="text-xs"> mantené Ctrl + click izquierdo para seleccionar multiples</p>
                                        </div>
                                        <p class="text-xs text-gray-400">PNG, JPG hasta 2MB</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Vista previa de imágenes-->
                            <div id="preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 hidden">
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex justify-end space-x-4 pt-4">
                            <button type="reset" class="px-4 py-2 border border-zinc-600 rounded-md text-gray-300 bg-zinc-700 hover:bg-zinc-600">
                                Limpiar formulario
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                <i class="bi bi-save mr-2"></i> Guardar Evento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
<script>
    // Mapeo de categorías a tipos
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
        ],
        'Salud/Emergencias': [
            'Evacuación médica o traslado de emergencia',
            'Agresion fisica por parte de terceros',
            'Traumatismos o lesiones graves durante servicio',
            'Pérdidas de personal durante servicio'
        ]
    };

    // Función para cargar tipos basados en categoría seleccionada
    function cargarTipos(categoria) {
        console.log(categoria);
        const tiposContainer = document.getElementById('tipos-container');
        tiposContainer.innerHTML = '';
        
        const tipos = tiposPorCategoria[categoria];
        tipos.forEach(tipo => {
            const div = document.createElement('div');
            div.className = 'flex items-center mb-2';
            
            const input = document.createElement('input');
            input.type = 'radio';
            input.id = `tipo-${tipo.replace(/\s+/g, '-').toLowerCase()}`;
            input.name = 'tipo';
            input.value = tipo;
            input.className = 'h-4 w-4 text-blue-600 focus:ring-blue-500 border-zinc-600';
            input.required = true;

            input.addEventListener('change', toggleElementosSustraidos);
            
            const label = document.createElement('label');
            label.htmlFor = input.id;
            label.className = 'ml-3 block text-sm font-medium text-gray-300';
            label.textContent = tipo;
            
            div.appendChild(input);
            div.appendChild(label);
            tiposContainer.appendChild(div);
        });

        setTimeout(toggleElementosSustraidos, 100);
    }

    // Función para verificar y mostrar/ocultar elementos sustraídos
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
                <div class="elemento-row grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 fila-original">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Elemento</label>
                        <input type="text" name="elementos[]" placeholder="Ej: rueda, batería, linterna..."
                            class="block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Cantidad</label>
                        <div class="flex">
                            <input type="number" name="cantidades[]" min="1" value="1"
                                class="block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                            <button type="button" class="remove-elemento-btn ml-2 px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors" title="Eliminar elemento" style="display: none;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
    // Ocultar inicialmente la sección de elementos sustraídos
    document.getElementById('elementos-sustraidos').classList.add('hidden');
    
    const categoriaSeleccionada = document.querySelector('input[name="categoria_id"]:checked');
    const tipoSeleccionado = "{{ old('tipo') }}";
    
    if (categoriaSeleccionada) {
        cargarTipos(categoriaSeleccionada.dataset.nombre);
        
        // Esperar un momento para que se carguen los tipos antes de seleccionar
        setTimeout(() => {
            if (tipoSeleccionado) {
                const tipoInput = document.querySelector(`input[name="tipo"][value="${tipoSeleccionado}"]`);
                console.log(tipoSeleccionado);
                if (tipoInput) {
                    tipoInput.checked = true;
                    toggleElementosSustraidos(); // Actualizar visibilidad
                }
            }
            // Verificar visibilidad después de cargar los tipos
            toggleElementosSustraidos();
        }, 100);
    }
});

    // Función para añadir nueva fila de elemento
    function addElementoRow() {
        const container = document.getElementById('elementos-container');
        const newRow = document.createElement('div');
        newRow.className = 'elemento-row grid grid-cols-1 md:grid-cols-2 gap-4 mb-4';
        newRow.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Elemento</label>
                <input type="text" name="elementos[]" placeholder="Ej: rueda, batería, linterna..."
                       class="block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Cantidad</label>
                <div class="flex">
                    <input type="number" name="cantidades[]" min="1" value="1"
                           class="block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
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

    // Función para actualizar visibilidad de botones de eliminar
    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.elemento-row');
        rows.forEach((row) => {
            const removeBtn = row.querySelector('.remove-elemento-btn');
            // Solo mostrar botón de eliminar en filas que NO son la original
            if (!row.classList.contains('fila-original')) {
                removeBtn.style.display = 'block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }

    // Escuchar cambios en la selección de categoría
    document.querySelectorAll('input[name="categoria_id"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const nombreCategoria = this.dataset.nombre;
            cargarTipos(nombreCategoria);
        });
    });

    document.getElementById('add-elemento-btn').addEventListener('click', addElementoRow);

    // Inicializar el formulario si hay valores antiguos
    document.addEventListener('DOMContentLoaded', function() {
        const categoriaSeleccionada = document.querySelector('input[name="categoria_id"]:checked');
        const tipoSeleccionado = "{{ old('tipo') }}";
        
        if (categoriaSeleccionada) {
            cargarTipos(categoriaSeleccionada.value);
            
            // Esperar un momento para que se carguen los tipos antes de seleccionar
            setTimeout(() => {
                if (tipoSeleccionado) {
                    const tipoInput = document.querySelector(`input[name="tipo"][value="${tipoSeleccionado}"]`);
                    console.log(tipoSeleccionado);
                    if (tipoInput) {
                        tipoInput.checked = true;
                        mostrarElementosSustraidos(tipoSeleccionado);
                    }
                }
            }, 100);
        }
        // Cargar elementos sustraídos existentes
        @if(old('elementos'))
            const elementosOld = @json(old('elementos'));
            const cantidadesOld = @json(old('cantidades'));
            
            if (elementosOld && elementosOld.length > 0) {
                const container = document.getElementById('elementos-container');
                container.innerHTML = '';
                
                elementosOld.forEach((elemento, index) => {
                    const newRow = document.createElement('div');
                    newRow.className = 'elemento-row grid grid-cols-1 md:grid-cols-2 gap-4 mb-4';
                    newRow.innerHTML = `
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Elemento</label>
                            <input type="text" name="elementos[]" value="${elemento}" placeholder="Ej: rueda, batería, linterna..."
                                   class="block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Cantidad</label>
                            <div class="flex">
                                <input type="number" name="cantidades[]" min="1" value="${cantidadesOld[index] || 1}"
                                       class="block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                                <button type="button" class="remove-elemento-btn ml-2 px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors" title="Eliminar elemento">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    container.appendChild(newRow);
                });
                updateRemoveButtons();
            }
        @endif
    });

    //vista previa imagenes
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

    //manejo de campo empresa asociada dinamico
    document.addEventListener('DOMContentLoaded', function() {
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
                    
                    // Verificar si el cliente tiene empresas asociadas
                    if (Object.keys(empresas).length === 0) {
                        // Si no tiene empresas, agregar opción indicando que no hay empresas
                        const option = document.createElement('option');
                        option.value = "";
                        option.textContent = "Este cliente no tiene empresas asociadas";
                        option.disabled = true;
                        empresaSelect.appendChild(option);
                    } else {
                    // Agregar nuevas opciones
                        Object.entries(empresas).forEach(([id, nombre]) => {
                            const option = document.createElement('option');
                            option.value = id;
                            option.textContent = nombre;
                            empresaSelect.appendChild(option);
                        });
                    }
                }
            });

            // Disparar el evento change si hay un cliente seleccionado (para edit)
            if (clienteSelect.value) {
                clienteSelect.dispatchEvent(new Event('change'));
                
                // Seleccionar la empresa asociada si existe (para edit)
                const empresaId = "{{ old('empresa_asociada_id') }}";
                if (empresaId) {
                    setTimeout(() => {
                        empresaSelect.value = empresaId;
                    }, 100);
                }
            }
        }
    });

    let personaCount = 0;

    function addPersonaRow() {
        personaCount++;
        const container = document.getElementById('personas-container');
        
        const newRow = document.createElement('div');
        newRow.className = 'persona-row bg-zinc-800 p-4 rounded-lg mb-4';
        newRow.id = `persona-row-${personaCount}`;
        
        newRow.innerHTML = `
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-md font-medium text-white">Persona #${personaCount}</h4>
                <button type="button" class="remove-persona-btn px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors text-sm">
                    <i class="bi bi-trash mr-1"></i> Eliminar
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Persona <span class="text-red-500">*</span></label>
                    <select name="personas_tipo[]" class="persona-tipo-select block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2" required>
                        <option value="">Seleccione un tipo</option>
                        <option value="afectado/victima">Afectado/Víctima</option>
                        <option value="sospechoso">Sospechoso</option>
                    </select>
                </div>
            </div>
            
            <!-- Campos para Afectado/Víctima -->
            <div class="campos-afectado hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Nombre</label>
                        <input type="text" name="personas_nombre[]" class="block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2" placeholder="Nombre completo">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Documento</label>
                        <select name="personas_tipo_doc[]" class="block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                            <option value="">Seleccione tipo</option>
                            <option value="DNI">DNI</option>
                            <option value="Pasaporte">Pasaporte</option>
                            <option value="Cédula">Cédula</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Número de Documento</label>
                        <input type="number" name="personas_nro_doc[]" class="block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2" placeholder="Número de documento">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Teléfono</label>
                        <input type="text" name="personas_nro_telefono[]" class="block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2" placeholder="Número de teléfono">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Relación con el Evento</label>
                    <textarea name="personas_relacion_evento[]" rows="2" class="block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2" placeholder="Describa la relación de esta persona con el evento"></textarea>
                </div>
            </div>
            
            <!-- Campos para Sospechoso -->
            <div class="campos-sospechoso hidden">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Descripción Física</label>
                    <textarea name="personas_descripcion_fisica[]" rows="2" class="block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2" placeholder="Describa las características físicas del sospechoso"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Comportamiento Observado</label>
                    <textarea name="personas_comportamiento_observado[]" rows="2" class="block w-full rounded-md bg-zinc-600 border-zinc-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2" placeholder="Describa el comportamiento observado del sospechoso"></textarea>
                </div>
            </div>
        `;
        
        container.appendChild(newRow);

        const tipoSelect = newRow.querySelector('.persona-tipo-select');
        tipoSelect.addEventListener('change', function() {
            toggleCamposPersona(this);
        });

        const removeBtn = newRow.querySelector('.remove-persona-btn');
        removeBtn.addEventListener('click', function() {
            newRow.remove();
            renumberPersonas();
        });
    }

    function toggleCamposPersona(selectElement) {
        const row = selectElement.closest('.persona-row');
        const camposAfectado = row.querySelector('.campos-afectado');
        const camposSospechoso = row.querySelector('.campos-sospechoso');
        
        // Ocultar todos los campos primero
        camposAfectado.classList.add('hidden');
        camposSospechoso.classList.add('hidden');
        
        // Mostrar campos según el tipo seleccionado
        if (selectElement.value === 'afectado/victima') {
            camposAfectado.classList.remove('hidden');
        } else if (selectElement.value === 'sospechoso') {
            camposSospechoso.classList.remove('hidden');
        }
    }

    function renumberPersonas() {
        const rows = document.querySelectorAll('.persona-row');
        rows.forEach((row, index) => {
            const title = row.querySelector('h4');
            title.textContent = `Persona #${index + 1}`;
        });
        personaCount = rows.length;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Añadir event listener al botón de agregar persona
        document.getElementById('add-persona-btn').addEventListener('click', addPersonaRow);
        
        // Cargar personas existentes si hay valores antiguos
        @if(old('personas_tipo'))
            const personasTipo = @json(old('personas_tipo'));
            personasTipo.forEach((tipo, index) => {
                addPersonaRow();
                
                // Esperar un momento para que se cree la fila
                setTimeout(() => {
                    const rows = document.querySelectorAll('.persona-row');
                    const currentRow = rows[rows.length - 1];
                    
                    // Establecer valores
                    const tipoSelect = currentRow.querySelector('.persona-tipo-select');
                    tipoSelect.value = tipo;
                    toggleCamposPersona(tipoSelect);
                    
                    // Establecer otros valores según el tipo
                    if (tipo === 'afectado/victima') {
                        currentRow.querySelector('input[name="personas_nombre[]"]').value = "{{ old('personas_nombre.' + index, '') }}";
                        currentRow.querySelector('select[name="personas_tipo_doc[]"]').value = "{{ old('personas_tipo_doc.' + index, '') }}";
                        currentRow.querySelector('input[name="personas_nro_doc[]"]').value = "{{ old('personas_nro_doc.' + index, '') }}";
                        currentRow.querySelector('input[name="personas_nro_telefono[]"]').value = "{{ old('personas_nro_telefono.' + index, '') }}";
                        currentRow.querySelector('textarea[name="personas_relacion_evento[]"]').value = "{{ old('personas_relacion_evento.' + index, '') }}";
                    } else if (tipo === 'sospechoso') {
                        currentRow.querySelector('textarea[name="personas_descripcion_fisica[]"]').value = "{{ old('personas_descripcion_fisica.' + index, '') }}";
                        currentRow.querySelector('textarea[name="personas_comportamiento_observado[]"]').value = "{{ old('personas_comportamiento_observado.' + index, '') }}";
                    }
                }, 100);
            });
        @endif
    });
</script>
@endpush
@endsection