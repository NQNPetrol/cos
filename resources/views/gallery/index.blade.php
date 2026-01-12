<x-app-layout>
    <div class="py-6">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header tipo Flytbase -->
            <div class="mb-6">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h1 class="text-2xl font-semibold text-gray-100">Galería</h1>
                </div>
                <p class="text-sm text-gray-400 ml-9">Visualiza todas tus imágenes y videos de drones, cámaras de seguridad y eventos</p>
            </div>

            <!-- Barra de Filtros Horizontal tipo Flytbase -->
            <div class="bg-zinc-800 rounded-lg border border-zinc-700 p-4 mb-6">
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Búsqueda -->
                    <div class="flex-1 min-w-[200px]">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" 
                                   id="searchInput" 
                                   placeholder="Buscar por nombre de archivo..." 
                                   class="w-full bg-zinc-700 border border-zinc-600 rounded-md py-2 pl-10 pr-3 text-sm text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Filtro Tipo de Media -->
                    <div class="relative">
                        <select id="typeFilter" 
                                class="bg-zinc-700 border border-zinc-600 rounded-md py-2 px-3 pr-8 text-sm text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer"
                                style="appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                            <option value="">Tipo de media</option>
                            <option value="image">Imágenes</option>
                            <option value="video">Videos</option>
                        </select>
                        <svg class="absolute right-2 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>

                    <!-- Filtro Fecha -->
                    <div class="relative">
                        <select id="dateFilter" 
                                class="bg-zinc-700 border border-zinc-600 rounded-md py-2 px-3 pr-8 text-sm text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer"
                                style="appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                            <option value="">Fecha</option>
                            <option value="today">Hoy</option>
                            <option value="week">Esta semana</option>
                            <option value="month">Este mes</option>
                            <option value="year">Este año</option>
                            <option value="custom">Personalizada</option>
                        </select>
                        <svg class="absolute right-2 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                    
                    <!-- Input de fecha personalizada (oculto por defecto) -->
                    <div id="customDateContainer" class="hidden relative">
                        <input type="date" 
                               id="customDateInput" 
                               class="bg-zinc-700 border border-zinc-600 rounded-md py-2 px-3 text-sm text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full min-w-[160px]">
                    </div>

                    <!-- Filtro Cliente -->
                    <div class="relative">
                        <select id="clientFilter" 
                                class="bg-zinc-700 border border-zinc-600 rounded-md py-2 px-3 pr-8 text-sm text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer"
                                style="appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                            <option value="">Cliente</option>
                            @php
                                $allClients = [];
                                foreach($galleryData['drones'] as $drone) {
                                    foreach($drone['clients'] as $client) {
                                        $allClients[$client['name']] = $client['name'];
                                    }
                                }
                            @endphp
                            @foreach($allClients as $client)
                                <option value="{{ $client }}" {{ request('client') == $client ? 'selected' : '' }}>
                                    {{ ucfirst($client) }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="absolute right-2 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>

                    <!-- Filtro Misión -->
                    <div class="relative">
                        <select id="missionFilter" 
                                class="bg-zinc-700 border border-zinc-600 rounded-md py-2 px-3 pr-8 text-sm text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer"
                                style="appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                            <option value="">Misión</option>
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
                                <option value="{{ $mission }}" {{ request('mission') == $mission ? 'selected' : '' }}>
                                    {{ $mission }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="absolute right-2 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>

                    <!-- Filtro Origen/Drone -->
                    <div class="relative">
                        <select id="droneFilter" 
                                class="bg-zinc-700 border border-zinc-600 rounded-md py-2 px-3 pr-8 text-sm text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer"
                                style="appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                            <option value="">Origen</option>
                            @foreach($galleryData['drones'] as $drone)
                                <option value="{{ $drone['name'] }}" {{ request('drone') == $drone['name'] ? 'selected' : '' }}>
                                    {{ $drone['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="absolute right-2 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>

                    <!-- Botón Reset -->
                    <button id="resetFilters" 
                            class="px-4 py-2 bg-zinc-600 hover:bg-zinc-500 text-gray-100 text-sm rounded-md transition-colors duration-150">
                        Reset
                    </button>

                    <!-- Contador de archivos -->
                    <div class="text-sm text-gray-400 ml-auto">
                        <span id="fileCount">{{ $galleryData['stats']['total_images'] + $galleryData['stats']['total_videos'] }}</span> archivos
                    </div>
                </div>
            </div>

            <!-- Barra de acciones de selección múltiple (oculta por defecto) -->
            <div id="selectionBar" class="hidden fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-zinc-800 border border-zinc-700 rounded-lg px-6 py-3 shadow-xl z-40">
                <div class="flex items-center gap-4">
                    <span id="selectedCount" class="text-sm text-gray-300 font-medium">0 seleccionados</span>
                    <button id="downloadSelected" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md transition-colors duration-150">
                        Descargar seleccionados
                    </button>
                    <button id="clearSelection" 
                            class="px-4 py-2 bg-zinc-600 hover:bg-zinc-500 text-gray-100 text-sm rounded-md transition-colors duration-150">
                        Limpiar selección
                    </button>
                </div>
            </div>

            <!-- Grid de Media agrupado por fecha -->
            <div id="mediaContainer" class="space-y-8">
                @php
                    // Recopilar todo el media
                    $allMedia = [];
                    foreach($galleryData['drones'] as $drone) {
                        foreach($drone['clients'] as $client) {
                            foreach($client['missions'] as $mission) {
                                foreach($mission['media'] as $media) {
                                    $media['drone'] = $drone['name'];
                                    $media['client'] = $client['name'];
                                    $media['mission'] = $mission['name'];
                                    $allMedia[] = $media;
                                }
                            }
                        }
                    }
                    
                    // Ordenar por timestamp más reciente
                    usort($allMedia, function($a, $b) {
                        $tsA = $a['patterns']['timestamp'] ?? '00000000000000';
                        $tsB = $b['patterns']['timestamp'] ?? '00000000000000';
                        return $tsB <=> $tsA;
                    });
                    
                    // Agrupar por fecha
                    $groupedByDate = [];
                    foreach($allMedia as $media) {
                        $timestamp = $media['patterns']['timestamp'] ?? 'unknown';
                        $date = null;
                        
                        // Intentar usar timestamp del nombre del archivo
                        if ($timestamp !== 'unknown' && strlen($timestamp) === 14) {
                            try {
                                $date = \Carbon\Carbon::createFromFormat('YmdHis', $timestamp);
                            } catch (\Exception $e) {
                                // Si falla, intentar usar fecha de modificación del archivo
                                if (isset($media['last_modified'])) {
                                    $date = \Carbon\Carbon::createFromTimestamp($media['last_modified']);
                                }
                            }
                        } else {
                            // Si no hay timestamp válido, usar fecha de modificación del archivo
                            if (isset($media['last_modified'])) {
                                $date = \Carbon\Carbon::createFromTimestamp($media['last_modified']);
                            }
                        }
                        
                        if ($date) {
                            $dateKey = $date->format('Y-m-d');
                            $dateLabel = $date->format('d M, Y');
                        } else {
                            $dateKey = 'unknown';
                            $dateLabel = 'Fecha desconocida';
                        }
                        
                        if (!isset($groupedByDate[$dateKey])) {
                            $groupedByDate[$dateKey] = [
                                'label' => $dateLabel,
                                'media' => []
                            ];
                        }
                        $groupedByDate[$dateKey]['media'][] = $media;
                    }
                @endphp

                @foreach($groupedByDate as $dateKey => $dateGroup)
                    <div class="date-group" data-date="{{ $dateKey }}">
                        <!-- Header de fecha -->
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-200">{{ $dateGroup['label'] }}</h2>
                            <span class="text-sm text-gray-400">{{ count($dateGroup['media']) }} archivos</span>
                        </div>
                        
                        <!-- Grid de 6-7 columnas -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 gap-3">
                            @foreach($dateGroup['media'] as $index => $media)
                                @php
                                    // Obtener la fecha del media para el atributo data-date
                                    $mediaDate = 'unknown';
                                    $mediaTimestamp = $media['patterns']['timestamp'] ?? 'unknown';
                                    if ($mediaTimestamp !== 'unknown' && strlen($mediaTimestamp) === 14) {
                                        try {
                                            $mediaDateObj = \Carbon\Carbon::createFromFormat('YmdHis', $mediaTimestamp);
                                            $mediaDate = $mediaDateObj->format('Y-m-d');
                                        } catch (\Exception $e) {
                                            if (isset($media['last_modified'])) {
                                                $mediaDateObj = \Carbon\Carbon::createFromTimestamp($media['last_modified']);
                                                $mediaDate = $mediaDateObj->format('Y-m-d');
                                            }
                                        }
                                    } else {
                                        if (isset($media['last_modified'])) {
                                            $mediaDateObj = \Carbon\Carbon::createFromTimestamp($media['last_modified']);
                                            $mediaDate = $mediaDateObj->format('Y-m-d');
                                        }
                                    }
                                @endphp
                                <div class="media-item group relative bg-zinc-700 rounded-lg overflow-hidden border border-zinc-600 hover:border-blue-500 hover:shadow-lg transition-all duration-200 cursor-pointer"
                                     data-drone="{{ $media['drone'] }}"
                                     data-client="{{ $media['client'] }}"
                                     data-mission="{{ $media['mission'] }}"
                                     data-type="{{ $media['type'] }}"
                                     data-filename="{{ $media['filename'] }}"
                                     data-url="{{ $media['url'] }}"
                                     data-index="{{ $index }}"
                                     data-date="{{ $mediaDate }}">
                                    
                                    <!-- Checkbox para selección múltiple (solo imágenes) -->
                                    @if($media['type'] === 'image')
                                        <div class="absolute top-2 left-2 z-10">
                                            <input type="checkbox" 
                                                   class="media-checkbox w-5 h-5 rounded border-zinc-500 bg-zinc-700 text-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 cursor-pointer"
                                                   data-url="{{ $media['url'] }}"
                                                   data-filename="{{ $media['filename'] }}">
                                        </div>
                                    @endif
                                    
                                    @if($media['type'] === 'image')
                                        <!-- Card de Imagen -->
                                        <div class="relative aspect-square">
                                            <img src="{{ $media['url'] }}" 
                                                 alt="{{ $media['filename'] }}"
                                                 class="w-full h-full object-cover"
                                                 loading="lazy">
                                            
                                            <!-- Overlay de información -->
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/0 to-black/0 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                <div class="absolute bottom-0 left-0 right-0 p-2 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-200">
                                                    <div class="text-white text-xs font-medium truncate">
                                                        {{ $media['mission'] }}
                                                    </div>
                                                    <div class="text-gray-300 text-[10px] mt-0.5 truncate">
                                                        {{ $media['drone'] }}
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Badge de tipo -->
                                            <div class="absolute top-2 right-2">
                                                <span class="bg-blue-600 text-white text-[10px] px-1.5 py-0.5 rounded">
                                                    IMG
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Card de Video -->
                                        <div class="relative aspect-square bg-zinc-600 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                            </svg>
                                            
                                            <!-- Overlay de información -->
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/0 to-black/0 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                <div class="absolute bottom-0 left-0 right-0 p-2 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-200">
                                                    <div class="text-white text-xs font-medium truncate">
                                                        {{ $media['mission'] }}
                                                    </div>
                                                    <div class="text-gray-300 text-[10px] mt-0.5 truncate">
                                                        {{ $media['drone'] }}
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Badge de tipo -->
                                            <div class="absolute top-2 right-2">
                                                <span class="bg-red-600 text-white text-[10px] px-1.5 py-0.5 rounded">
                                                    VID
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Mensaje cuando no hay resultados -->
            <div id="noResults" class="hidden text-center py-16">
                <svg class="w-20 h-20 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-xl font-medium text-gray-300 mb-2">No se encontraron resultados</h3>
                <p class="text-gray-400">Intenta ajustar los filtros para ver más contenido.</p>
            </div>
        </div>
    </div>

    <!-- Modal Lightbox tipo Flytbase -->
    <div id="galleryModal" class="fixed bg-black/95 z-[999] hidden gallery-modal-container">
        <style>
            /* Modal responsive - respeta sidebar y top bar del layout modern-ui */
            .gallery-modal-container {
                /* Usar variables CSS del modern-ui si están disponibles, sino valores por defecto */
                top: var(--fb-topbar-height, 60px);
                left: var(--fb-sidebar-width, 240px);
                right: 0;
                bottom: 0;
            }
            
            /* En pantallas medianas, el sidebar puede estar colapsado */
            @media (max-width: 1024px) {
                .gallery-modal-container {
                    left: 0;
                }
            }
            
            /* En móviles, ocupar toda la pantalla */
            @media (max-width: 768px) {
                .gallery-modal-container {
                    top: 0;
                    left: 0;
                }
            }
        </style>
        <div class="flex h-full">
            <!-- Área principal de imagen/video -->
            <div class="flex-1 flex flex-col relative">
                <!-- Header del modal -->
                <div class="flex justify-between items-center p-4 border-b border-zinc-700">
                    <h3 id="modalTitle" class="text-lg font-semibold text-white truncate max-w-2xl">
                        <!-- Título dinámico -->
                    </h3>
                    <div class="flex items-center gap-3">
                        <!-- Botón descargar -->
                        <button id="downloadCurrent" 
                                class="p-2 text-gray-400 hover:text-white transition-colors duration-150"
                                title="Descargar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </button>
                        <!-- Botón cerrar -->
                        <button id="closeModalBtn" 
                                class="p-2 text-gray-400 hover:text-white transition-colors duration-150">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Contenido de imagen/video -->
                <div class="flex-1 flex items-center justify-center p-8 relative overflow-hidden">
                    <!-- Botón anterior -->
                    <button id="prevBtn" 
                            class="absolute left-4 top-1/2 transform -translate-y-1/2 p-3 bg-zinc-800/80 hover:bg-zinc-700 rounded-full text-white transition-all duration-150 z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    
                    <!-- Imagen/Video -->
                    <div class="max-w-full max-h-full">
                        <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg hidden">
                        <video id="modalVideo" src="" controls class="max-w-full max-h-full rounded-lg hidden"></video>
                    </div>
                    
                    <!-- Botón siguiente -->
                    <button id="nextBtn" 
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 p-3 bg-zinc-800/80 hover:bg-zinc-700 rounded-full text-white transition-all duration-150 z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>

                <!-- Carousel de thumbnails inferior -->
                <div class="border-t border-zinc-700 p-4">
                    <div id="thumbnailCarousel" class="flex gap-2 overflow-x-auto pb-2">
                        <!-- Thumbnails se insertarán aquí dinámicamente -->
                    </div>
                </div>
            </div>

            <!-- Sidebar de metadatos -->
            <div class="w-80 bg-zinc-900 border-l border-zinc-700 overflow-y-auto">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-white mb-6">Detalles</h3>
                    
                    <div id="modalMetadata" class="space-y-6">
                        <!-- Los metadatos se insertarán aquí dinámicamente -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Estado global
        let allMediaItems = [];
        let currentMediaIndex = 0;
        let selectedMedia = new Set();
        let filteredMedia = [];

        // Inicializar cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            initializeGallery();
        });

        function initializeGallery() {
            // Recopilar todos los media items
            const mediaElements = document.querySelectorAll('.media-item');
            allMediaItems = Array.from(mediaElements).map((el, index) => ({
                element: el,
                url: el.dataset.url,
                filename: el.dataset.filename,
                type: el.dataset.type,
                drone: el.dataset.drone,
                client: el.dataset.client,
                mission: el.dataset.mission,
                date: el.dataset.date,
                index: index
            }));
            
            filteredMedia = [...allMediaItems];
            
            // Configurar eventos de click en media items
            setupMediaClicks();
            
            // Configurar filtros
            setupFilters();
            
            // Configurar selección múltiple
            setupMultiSelect();
            
            // Configurar modal
            setupModal();
            
            // Configurar navegación del modal
            setupModalNavigation();
        }

        function setupMediaClicks() {
            document.querySelectorAll('.media-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    // No abrir modal si se clickeó el checkbox
                    if (e.target.type === 'checkbox' || e.target.closest('.media-checkbox')) {
                        return;
                    }
                    
                    // Encontrar el índice en filteredMedia
                    const index = filteredMedia.findIndex(m => m.element === this);
                    if (index !== -1) {
                        openModal(index);
                    } else {
                        // Si no está en filteredMedia, buscar en allMediaItems
                        const allIndex = allMediaItems.findIndex(m => m.element === this);
                        if (allIndex !== -1) {
                            // Reconstruir filteredMedia con todos los visibles
                            rebuildFilteredMedia();
                            const newIndex = filteredMedia.findIndex(m => m.element === this);
                            if (newIndex !== -1) {
                                openModal(newIndex);
                            }
                        }
                    }
                });
            });
        }
        
        function rebuildFilteredMedia() {
            filteredMedia = Array.from(document.querySelectorAll('.media-item:not([style*="display: none"])'))
                .map(el => {
                    const found = allMediaItems.find(m => m.element === el);
                    return found;
                })
                .filter(m => m !== undefined);
        }

        function setupFilters() {
            const searchInput = document.getElementById('searchInput');
            const typeFilter = document.getElementById('typeFilter');
            const dateFilter = document.getElementById('dateFilter');
            const customDateContainer = document.getElementById('customDateContainer');
            const customDateInput = document.getElementById('customDateInput');
            const clientFilter = document.getElementById('clientFilter');
            const missionFilter = document.getElementById('missionFilter');
            const droneFilter = document.getElementById('droneFilter');
            const resetBtn = document.getElementById('resetFilters');
            
            const filterFunction = () => applyFilters();
            
            searchInput.addEventListener('input', filterFunction);
            typeFilter.addEventListener('change', filterFunction);
            
            // Manejar cambio en filtro de fecha
            dateFilter.addEventListener('change', function() {
                if (this.value === 'custom') {
                    customDateContainer.classList.remove('hidden');
                    customDateInput.focus();
                } else {
                    customDateContainer.classList.add('hidden');
                    customDateInput.value = '';
                }
                filterFunction();
            });
            
            // Aplicar filtro cuando cambia la fecha personalizada
            customDateInput.addEventListener('change', filterFunction);
            
            clientFilter.addEventListener('change', filterFunction);
            missionFilter.addEventListener('change', filterFunction);
            droneFilter.addEventListener('change', filterFunction);
            
            resetBtn.addEventListener('click', () => {
                searchInput.value = '';
                typeFilter.value = '';
                dateFilter.value = '';
                customDateInput.value = '';
                customDateContainer.classList.add('hidden');
                clientFilter.value = '';
                missionFilter.value = '';
                droneFilter.value = '';
                applyFilters();
            });
        }

        function applyFilters() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const typeFilter = document.getElementById('typeFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;
            const customDateInput = document.getElementById('customDateInput');
            const clientFilter = document.getElementById('clientFilter').value;
            const missionFilter = document.getElementById('missionFilter').value;
            const droneFilter = document.getElementById('droneFilter').value;
            
            const now = new Date();
            let dateStart = null;
            let dateEnd = null;
            let customDate = null;
            
            if (dateFilter === 'today') {
                dateStart = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                dateEnd = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59);
            } else if (dateFilter === 'week') {
                dateStart = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
            } else if (dateFilter === 'month') {
                dateStart = new Date(now.getFullYear(), now.getMonth(), 1);
            } else if (dateFilter === 'year') {
                dateStart = new Date(now.getFullYear(), 0, 1);
            } else if (dateFilter === 'custom' && customDateInput.value) {
                // Fecha personalizada: filtrar solo ese día específico
                customDate = customDateInput.value; // Formato YYYY-MM-DD
                dateStart = new Date(customDate + 'T00:00:00');
                dateEnd = new Date(customDate + 'T23:59:59');
            }
            
            let visibleCount = 0;
            const dateGroups = document.querySelectorAll('.date-group');
            let hasVisibleGroups = false;
            
            dateGroups.forEach(group => {
                const mediaItems = group.querySelectorAll('.media-item');
                let groupVisible = false;
                
                mediaItems.forEach(item => {
                    const filename = item.dataset.filename.toLowerCase();
                    const type = item.dataset.type;
                    const client = item.dataset.client.toLowerCase();
                    const mission = item.dataset.mission.toLowerCase();
                    const drone = item.dataset.drone.toLowerCase();
                    const date = item.dataset.date;
                    
                    let show = true;
                    
                    // Filtro de búsqueda
                    if (searchTerm && !filename.includes(searchTerm)) {
                        show = false;
                    }
                    
                    // Filtro de tipo
                    if (typeFilter && type !== typeFilter) {
                        show = false;
                    }
                    
                    // Filtro de fecha
                    if (dateFilter && date !== 'unknown') {
                        try {
                            if (dateFilter === 'custom' && customDate) {
                                // Filtro de fecha personalizada: debe coincidir exactamente
                                if (date !== customDate) {
                                    show = false;
                                }
                            } else if (dateStart) {
                                // Filtros de rango (hoy, semana, mes, año)
                                const itemDate = new Date(date + 'T00:00:00');
                                if (itemDate < dateStart) {
                                    show = false;
                                }
                                // Si hay dateEnd (fecha personalizada o hoy), verificar también el límite superior
                                if (dateEnd && itemDate > dateEnd) {
                                    show = false;
                                }
                            }
                        } catch (e) {
                            show = false;
                        }
                    }
                    
                    // Filtro de cliente
                    if (clientFilter && !client.includes(clientFilter.toLowerCase())) {
                        show = false;
                    }
                    
                    // Filtro de misión
                    if (missionFilter && !mission.includes(missionFilter.toLowerCase())) {
                        show = false;
                    }
                    
                    // Filtro de drone/origen
                    if (droneFilter && !drone.includes(droneFilter.toLowerCase())) {
                        show = false;
                    }
                    
                    if (show) {
                        item.style.display = 'block';
                        visibleCount++;
                        groupVisible = true;
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                // Mostrar/ocultar grupo de fecha
                if (groupVisible) {
                    group.style.display = 'block';
                    hasVisibleGroups = true;
                } else {
                    group.style.display = 'none';
                }
            });
            
            // Actualizar contador
            document.getElementById('fileCount').textContent = visibleCount;
            
            // Mostrar/ocultar mensaje de no resultados
            const noResults = document.getElementById('noResults');
            const mediaContainer = document.getElementById('mediaContainer');
            
            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
                mediaContainer.classList.add('hidden');
            } else {
                noResults.classList.add('hidden');
                mediaContainer.classList.remove('hidden');
            }
            
            // Actualizar filteredMedia
            rebuildFilteredMedia();
        }

        function setupMultiSelect() {
            const checkboxes = document.querySelectorAll('.media-checkbox');
            const selectionBar = document.getElementById('selectionBar');
            const selectedCount = document.getElementById('selectedCount');
            const downloadSelected = document.getElementById('downloadSelected');
            const clearSelection = document.getElementById('clearSelection');
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        selectedMedia.add(this.dataset.url);
                    } else {
                        selectedMedia.delete(this.dataset.url);
                    }
                    
                    updateSelectionBar();
                });
            });
            
            downloadSelected.addEventListener('click', () => {
                downloadSelectedImages();
            });
            
            clearSelection.addEventListener('click', () => {
                selectedMedia.clear();
                checkboxes.forEach(cb => cb.checked = false);
                updateSelectionBar();
            });
        }

        function updateSelectionBar() {
            const selectionBar = document.getElementById('selectionBar');
            const selectedCount = document.getElementById('selectedCount');
            
            if (selectedMedia.size > 0) {
                selectionBar.classList.remove('hidden');
                selectedCount.textContent = `${selectedMedia.size} seleccionados`;
            } else {
                selectionBar.classList.add('hidden');
            }
        }

        function downloadSelectedImages() {
            selectedMedia.forEach(url => {
                const link = document.createElement('a');
                link.href = url;
                link.download = url.split('/').pop();
                link.target = '_blank';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        }

        function setupModal() {
            const closeBtn = document.getElementById('closeModalBtn');
            const modal = document.getElementById('galleryModal');
            const downloadBtn = document.getElementById('downloadCurrent');
            
            closeBtn.addEventListener('click', closeModal);
            downloadBtn.addEventListener('click', downloadCurrentMedia);
            
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
            
            // Cerrar con ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        }

        function setupModalNavigation() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            
            prevBtn.addEventListener('click', () => navigateModal(-1));
            nextBtn.addEventListener('click', () => navigateModal(1));
            
            // Navegación con teclado
            document.addEventListener('keydown', function(e) {
                const modal = document.getElementById('galleryModal');
                if (modal.classList.contains('hidden')) return;
                
                if (e.key === 'ArrowLeft') {
                    navigateModal(-1);
                } else if (e.key === 'ArrowRight') {
                    navigateModal(1);
                }
            });
        }

        function openModal(index) {
            // Asegurar que filteredMedia esté actualizado
            if (filteredMedia.length === 0) {
                rebuildFilteredMedia();
            }
            
            if (index < 0 || index >= filteredMedia.length) return;
            
            currentMediaIndex = index;
            const media = filteredMedia[index];
            
            const modal = document.getElementById('galleryModal');
            const modalImage = document.getElementById('modalImage');
            const modalVideo = document.getElementById('modalVideo');
            const modalTitle = document.getElementById('modalTitle');
            const metadata = document.getElementById('modalMetadata');
            
            modalTitle.textContent = media.filename;
            
            if (media.type === 'image') {
                modalImage.src = media.url;
                modalImage.alt = media.filename;
                modalImage.classList.remove('hidden');
                modalVideo.classList.add('hidden');
                if (modalVideo.src) modalVideo.pause();
            } else {
                modalVideo.src = media.url;
                modalVideo.classList.remove('hidden');
                modalImage.classList.add('hidden');
            }
            
            // Actualizar metadatos
            updateMetadata(media);
            
            // Actualizar carousel de thumbnails
            updateThumbnailCarousel();
            
            // Mostrar modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('galleryModal');
            const modalVideo = document.getElementById('modalVideo');
            
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            
            if (modalVideo.src) {
                modalVideo.pause();
                modalVideo.src = '';
            }
        }

        function navigateModal(direction) {
            const newIndex = currentMediaIndex + direction;
            
            if (newIndex >= 0 && newIndex < filteredMedia.length) {
                openModal(newIndex);
            }
        }

        function updateMetadata(media) {
            const metadata = document.getElementById('modalMetadata');
            
            // Formatear fecha
            let dateStr = 'Fecha desconocida';
            let timeStr = '';
            if (media.date && media.date !== 'unknown') {
                try {
                    const date = new Date(media.date);
                    dateStr = date.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
                    timeStr = date.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
                } catch (e) {}
            }
            
            metadata.innerHTML = `
                <div>
                    <label class="text-xs text-gray-400 uppercase tracking-wide">Nombre</label>
                    <p class="text-sm text-gray-200 mt-1 break-all">${media.filename}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-400 uppercase tracking-wide">Tipo</label>
                    <p class="text-sm text-gray-200 mt-1">${media.type === 'image' ? 'Imagen' : 'Video'}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-400 uppercase tracking-wide">Origen</label>
                    <p class="text-sm text-gray-200 mt-1">${media.drone || 'Desconocido'}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-400 uppercase tracking-wide">Cliente</label>
                    <p class="text-sm text-gray-200 mt-1">${media.client || 'Desconocido'}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-400 uppercase tracking-wide">Misión</label>
                    <p class="text-sm text-gray-200 mt-1">${media.mission || 'Desconocida'}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-400 uppercase tracking-wide">Fecha</label>
                    <p class="text-sm text-gray-200 mt-1">${dateStr}</p>
                </div>
                ${timeStr ? `
                <div>
                    <label class="text-xs text-gray-400 uppercase tracking-wide">Hora</label>
                    <p class="text-sm text-gray-200 mt-1">${timeStr}</p>
                </div>
                ` : ''}
            `;
        }

        function updateThumbnailCarousel() {
            const carousel = document.getElementById('thumbnailCarousel');
            carousel.innerHTML = '';
            
            filteredMedia.forEach((media, index) => {
                const thumbnail = document.createElement('div');
                thumbnail.className = `flex-shrink-0 w-20 h-20 rounded overflow-hidden border-2 cursor-pointer transition-all duration-150 ${
                    index === currentMediaIndex ? 'border-blue-500' : 'border-transparent hover:border-zinc-600'
                }`;
                
                if (media.type === 'image') {
                    thumbnail.innerHTML = `<img src="${media.url}" alt="${media.filename}" class="w-full h-full object-cover">`;
                } else {
                    thumbnail.innerHTML = `
                        <div class="w-full h-full bg-zinc-700 flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    `;
                }
                
                thumbnail.addEventListener('click', () => openModal(index));
                carousel.appendChild(thumbnail);
            });
        }

        function downloadCurrentMedia() {
            const media = filteredMedia[currentMediaIndex];
            const link = document.createElement('a');
            link.href = media.url;
            link.download = media.filename;
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</x-app-layout>
