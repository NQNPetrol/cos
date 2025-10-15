<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100 dark:text-gray-100">
                    <div class="space-y-8">
                        <!-- Header -->
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-100">Galería</h2>
                                <p class="text-sm text-gray-400 mt-1">Imágenes y videos capturados por nuestros drones durante misiones</p>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                            <h3 class="text-lg font-medium text-gray-100 mb-4">Filtros</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Filtro por Drone -->
                                <div>
                                    <label for="droneFilter" class="block text-sm font-medium text-gray-300 mb-2">
                                        Drone
                                    </label>
                                    <select id="droneFilter" name="drone" 
                                            class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Todos los drones</option>
                                        @foreach($galleryData['drones'] as $drone)
                                            <option value="{{ $drone['name'] }}" 
                                                    {{ request('drone') == $drone['name'] ? 'selected' : '' }}>
                                                {{ $drone['name'] }} ({{ $drone['stats']['images'] + $drone['stats']['videos'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filtro por Cliente -->
                                <div>
                                    <label for="clientFilter" class="block text-sm font-medium text-gray-300 mb-2">
                                        Cliente
                                    </label>
                                    <select id="clientFilter" name="client" 
                                            class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Todos los clientes</option>
                                        @php
                                            $allClients = [];
                                            foreach($galleryData['drones'] as $drone) {
                                                foreach($drone['clients'] as $client) {
                                                    $allClients[$client['name']] = $client['name'];
                                                }
                                            }
                                        @endphp
                                        @foreach($allClients as $client)
                                            <option value="{{ $client }}" 
                                                    {{ request('client') == $client ? 'selected' : '' }}>
                                                {{ ucfirst($client) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filtro por Misión -->
                                <div>
                                    <label for="missionFilter" class="block text-sm font-medium text-gray-300 mb-2">
                                        Misión
                                    </label>
                                    <select id="missionFilter" name="mission" 
                                            class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Todas las misiones</option>
                                        @php
                                            $allMissions = [];
                                            foreach($galleryData['drones'] as $drone) {
                                                foreach($drone['clients'] as $client) {
                                                    foreach($client['missions'] as $mission) {
                                                        $allMissions[$mission['name']] = $mission['name'];
                                                    }
                                                }
                                            }
                                        @endphp
                                        @foreach($allMissions as $mission)
                                            <option value="{{ $mission }}" 
                                                    {{ request('mission') == $mission ? 'selected' : '' }}>
                                                {{ $mission }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="flex justify-between items-center mt-6">
                                <div class="text-sm text-gray-400">
                                    Mostrando 
                                    <span class="font-semibold text-gray-200">{{ $galleryData['stats']['total_images'] }}</span> imágenes y 
                                    <span class="font-semibold text-gray-200">{{ $galleryData['stats']['total_videos'] }}</span> videos en 
                                    <span class="font-semibold text-gray-200">{{ $galleryData['stats']['total_missions'] }}</span> misiones
                                </div>
                                <div class="flex space-x-3">
                                    <button type="button" id="clearFilters" 
                                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-800 focus:ring ring-gray-300 transition ease-in-out duration-150">
                                        Limpiar Filtros
                                    </button>
                                    <button type="button" id="applyFilters" 
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 transition ease-in-out duration-150">
                                        Aplicar Filtros
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Grid de Imágenes -->
                        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-medium text-gray-100">Media</h3>
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-400" id="mediaCount">
                                        {{ $galleryData['stats']['total_images'] + $galleryData['stats']['total_videos'] }} elementos
                                    </span>
                                    <div class="flex items-center space-x-2">
                                        <button id="gridView" class="p-2 rounded bg-gray-700 text-gray-300 hover:bg-gray-600 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                            </svg>
                                        </button>
                                        <button id="listView" class="p-2 rounded text-gray-500 hover:bg-gray-600 hover:text-gray-300 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Grid de Media -->
                            <div id="mediaGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                                @php
                                    Log::info('VISTA GALLERY: Iniciando renderizado de vista');
                                    $allMedia = [];
                                    foreach($galleryData['drones'] as $drone) {
                                        foreach($drone['clients'] as $client) {
                                            foreach($client['missions'] as $mission) {
                                                $allMedia = array_merge($allMedia, $mission['media']);
                                            }
                                        }
                                    }
                                    Log::info("VISTA GALLERY: Total media a renderizar: " . count($allMedia));
                                    // Ordenar por timestamp más reciente
                                    usort($allMedia, function($a, $b) {
                                        return $b['patterns']['timestamp'] <=> $a['patterns']['timestamp'];
                                    });
                                    Log::info("VISTA GALLERY: Media ordenada por timestamp");
                                @endphp

                                @foreach($allMedia as $index => $media)
                                    @php
                                        Log::info("VISTA GALLERY: Renderizando media {$index}/" . count($allMedia) . " - {$media['filename']}");
                                        Log::info("VISTA GALLERY: URL: {$media['url']}");
                                       
                                        
                                        Log::info("VISTA GALLERY: Tipo: {$media['type']}");
                                    @endphp
                                    <div class="media-item bg-gray-700 rounded-lg overflow-hidden border border-gray-600 hover:border-blue-500 transition-all duration-300" 
                                         data-drone="{{ $media['patterns']['prefix'] }}"
                                         data-client="{{ $galleryService->extractClientFromMission($media['patterns']['mission']) }}"
                                         data-mission="{{ $media['patterns']['mission'] }}"
                                         data-type="{{ $media['type'] }}">
                                        
                                        @if($media['type'] === 'image')
                                            <!-- Tarjeta de Imagen -->
                                            <div class="relative group">
                                                
                                                <img src="{{ $media['url'] }}" 
                                                     alt="{{ $media['filename'] }}"
                                                     class="w-full h-48 object-cover cursor-pointer"
                                                     loading="lazy">
                                                
                                                <!-- Overlay de información -->
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-300 flex items-end">
                                                    <div class="p-3 w-full transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                                        <div class="text-white text-sm font-medium truncate">
                                                            {{ $media['patterns']['mission'] }}
                                                        </div>
                                                        <div class="text-gray-300 text-xs mt-1">
                                                            {{ $media['patterns']['prefix'] }} • 
                                                            @if($media['patterns']['timestamp'] !== 'unknown' && strlen($media['patterns']['timestamp']) === 14)
                                                                {{ \Carbon\Carbon::createFromFormat('YmdHis', $media['patterns']['timestamp'])->format('d/m/Y H:i') }}
                                                            @else
                                                                Fecha desconocida
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Badge de tipo -->
                                                <div class="absolute top-2 right-2">
                                                    <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full">
                                                        IMG
                                                    </span>
                                                </div>
                                            </div>

                                        @else
                                            <!-- Tarjeta de Video -->
                                            <div class="relative group">
                                                <div class="w-full h-48 bg-gray-600 flex items-center justify-center cursor-pointer"
                                                     onclick="openModal('{{ $media['url'] }}', '{{ $media['filename'] }}', 'video')">
                                                    <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                
                                                <!-- Overlay de información -->
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-300 flex items-end">
                                                    <div class="p-3 w-full transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                                        <div class="text-white text-sm font-medium truncate">
                                                            {{ $media['patterns']['mission'] }}
                                                        </div>
                                                        <div class="text-gray-300 text-xs mt-1">
                                                            {{ $media['patterns']['prefix'] }} •
                                                            <div class="text-gray-300 text-xs mt-1">
                                                            {{ $media['patterns']['prefix'] }} • 
                                                            @if($media['patterns']['timestamp'] !== 'unknown' && strlen($media['patterns']['timestamp']) === 14)
                                                                {{ \Carbon\Carbon::createFromFormat('YmdHis', $media['patterns']['timestamp'])->format('d/m/Y H:i') }}
                                                            @else
                                                                Fecha desconocida
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Badge de tipo -->
                                                <div class="absolute top-2 right-2">
                                                    <span class="bg-red-600 text-white text-xs px-2 py-1 rounded-full">
                                                        VID
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Mensaje cuando no hay resultados -->
                            <div id="noResults" class="hidden text-center py-12">
                                <svg class="w-16 h-16 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-300 mb-2">No se encontraron resultados</h3>
                                <p class="text-gray-400">Intenta ajustar los filtros para ver más contenido.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para visualización -->
    <div id="galleryModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 hidden">
        <div class="relative max-w-6xl max-h-full w-full mx-4">
            <div class="bg-gray-900 rounded-lg overflow-hidden shadow-2xl">
                <!-- Header con título y botón cerrar -->
                <div class="flex justify-between items-center p-4 border-b border-gray-700">
                    <h3 id="modalTitle" class="text-lg font-semibold text-white truncate max-w-2xl">
                        <!-- Título dinámico se insertará aquí -->
                    </h3>
                    <button id="closeModalBtn" class="text-gray-400 hover:text-white transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Contenido de la imagen -->
                <div class="p-4 flex justify-center items-center max-h-[70vh] overflow-auto">
                    <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
                </div>
            </div>
        </div>
    </div>
    <script>

        // Modal functions
        function openModal(url, filename, type = 'image') {
            console.log('OPEN MODAL CALLED:', { url, filename, type });
            
            const modal = document.getElementById('galleryModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            
            if (!modal || !modalImage || !modalTitle) {
                console.error('Elementos del modal no encontrados');
                return;
            }

            modalImage.src = url;
            modalImage.alt = filename;
            modalTitle.textContent = filename;
            
            // Mostrar modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            console.log('Modal abierto correctamente');
        }

        function closeModal() {
            console.log('CLOSE MODAL CALLED');
            const modal = document.getElementById('galleryModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // ✅ APPROACH COMPLETAMENTE NUEVO - DEBUG DETALLADO
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🔄 DOM Cargado - Iniciando configuración de galería');
            
            // 1. Configurar cierre del modal
            const closeBtn = document.getElementById('closeModalBtn');
            if (closeBtn) {
                closeBtn.addEventListener('click', closeModal);
            }

            // 2. DEBUG DETALLADO DE LAS IMÁGENES
            function debugImages() {
                console.log('🔍 INICIANDO DEBUG DE IMÁGENES...');
                
                // Todas las imágenes en media-items
                const allImages = document.querySelectorAll('.media-item img');
                console.log(`📸 Total imágenes encontradas: ${allImages.length}`);
                
                allImages.forEach((img, index) => {
                    console.log(`Imagen ${index + 1}:`, {
                        src: img.src,
                        alt: img.alt,
                        classList: img.classList,
                        style: img.style.cssText,
                        parent: img.parentElement?.tagName,
                        computedStyle: window.getComputedStyle(img).cursor
                    });
                    
                    // Verificar si hay overlays que bloqueen
                    const parent = img.parentElement;
                    if (parent) {
                        console.log(`Parent:`, {
                            tag: parent.tagName,
                            classList: parent.classList,
                            hasOverlay: parent.querySelector('.absolute') !== null
                        });
                    }
                });
            }

            // 3. CONFIGURACIÓN DE CLICKS - VERSIÓN ROBUSTA
            function setupGalleryClicks() {
                console.log('🎯 Configurando clicks de galería...');
                
                // Opción A: Click en el CONTENEDOR de la imagen (más confiable)
                const mediaItems = document.querySelectorAll('.media-item');
                console.log(`Encontrados ${mediaItems.length} media-items`);
                
                mediaItems.forEach((item, index) => {
                    const img = item.querySelector('img');
                    const mediaType = item.dataset.type;
                    
                    if (img && mediaType === 'image') {
                        console.log(`Configurando media-item ${index + 1}:`, img.alt);
                        
                        // Hacer TODO el item clickeable
                        item.style.cursor = 'pointer';
                        item.title = 'Click para ampliar';
                        
                        // Agregar event listener al ITEM completo
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            console.log(`CLICK EN MEDIA-ITEM ${index + 1}:`, {
                                target: e.target.tagName,
                                alt: img.alt,
                                src: img.src
                            });
                            
                            openModal(img.src, img.alt, 'image');
                        });
                        
                        // También agregar listener a la imagen por si acaso
                        img.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            console.log(`CLICK DIRECTO EN IMAGEN ${index + 1}`);
                            openModal(img.src, img.alt, 'image');
                        });
                    }
                });
                
                // Opción B: También configurar clicks directos en imágenes
                const directImages = document.querySelectorAll('.media-item img');
                directImages.forEach((img, index) => {
                    img.style.cursor = 'pointer';
                    
                    img.addEventListener('click', function(e) {
                        e.stopPropagation(); // Prevenir que llegue al media-item
                        console.log(`📸 CLICK DIRECTO EN IMAGEN ${index + 1}`);
                    });
                });
            }

            // 4. EJECUTAR CONFIGURACIÓN
            setTimeout(() => {
                debugImages(); // Primero debuggear
                setupGalleryClicks(); // Luego configurar clicks
                
                console.log('Configuración de galería completada');
                
                // Verificación final
                const clickableItems = document.querySelectorAll('.media-item[style*="cursor: pointer"]');
                console.log(`${clickableItems.length} elementos configurados como clickeables`);
            }, 1000);

            // 5. Cerrar modal con ESC y click fuera
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal();
                }
            });

            const modal = document.getElementById('galleryModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeModal();
                    }
                });
            }

            // 6. Inicializar filtros
            filterMedia();
        });

        // Funciones de filtros (mantener igual)
        function filterMedia() {
            const droneFilter = document.getElementById('droneFilter').value.toLowerCase();
            const clientFilter = document.getElementById('clientFilter').value.toLowerCase();
            const missionFilter = document.getElementById('missionFilter').value.toLowerCase();
            
            const mediaItems = document.querySelectorAll('.media-item');
            let visibleCount = 0;
            
            mediaItems.forEach(item => {
                const drone = item.dataset.drone.toLowerCase();
                const client = item.dataset.client.toLowerCase();
                const mission = item.dataset.mission.toLowerCase();
                
                const showItem = 
                    (!droneFilter || drone.includes(droneFilter)) &&
                    (!clientFilter || client.includes(clientFilter)) &&
                    (!missionFilter || mission.includes(missionFilter));
                
                item.style.display = showItem ? 'block' : 'none';
                if (showItem) visibleCount++;
            });
            
            const noResults = document.getElementById('noResults');
            const mediaCount = document.getElementById('mediaCount');
            
            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
                document.getElementById('mediaGrid').classList.add('hidden');
            } else {
                noResults.classList.add('hidden');
                document.getElementById('mediaGrid').classList.remove('hidden');
            }
            
            mediaCount.textContent = visibleCount + ' elementos';
        }

        document.getElementById('applyFilters').addEventListener('click', function() {
            const drone = document.getElementById('droneFilter').value;
            const client = document.getElementById('clientFilter').value;
            const mission = document.getElementById('missionFilter').value;
            
            const params = new URLSearchParams();
            if (drone) params.append('drone', drone);
            if (client) params.append('client', client);
            if (mission) params.append('mission', mission);
            
            window.location.href = '{{ route("gallery.index") }}?' + params.toString();
        });

        document.getElementById('clearFilters').addEventListener('click', function() {
            window.location.href = '{{ route("gallery.index") }}';
        });

        document.getElementById('droneFilter').addEventListener('change', filterMedia);
        document.getElementById('clientFilter').addEventListener('change', filterMedia);
        document.getElementById('missionFilter').addEventListener('change', filterMedia);

        // Vista de grid/list
        document.getElementById('gridView').addEventListener('click', function() {
            document.getElementById('mediaGrid').classList.remove('grid-cols-1');
            document.getElementById('mediaGrid').classList.add('grid-cols-1', 'sm:grid-cols-2', 'md:grid-cols-3', 'lg:grid-cols-4', 'xl:grid-cols-5');
            this.classList.add('bg-gray-700', 'text-gray-300');
            document.getElementById('listView').classList.remove('bg-gray-700', 'text-gray-300');
        });

        document.getElementById('listView').addEventListener('click', function() {
            document.getElementById('mediaGrid').classList.remove('sm:grid-cols-2', 'md:grid-cols-3', 'lg:grid-cols-4', 'xl:grid-cols-5');
            document.getElementById('mediaGrid').classList.add('grid-cols-1');
            this.classList.add('bg-gray-700', 'text-gray-300');
            document.getElementById('gridView').classList.remove('bg-gray-700', 'text-gray-300');
        });
    </script>

</x-app-layout>