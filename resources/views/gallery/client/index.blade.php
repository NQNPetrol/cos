@extends('layouts.cliente')
@section('content')
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
                                    <span class="font-semibold text-gray-200">{{ $galleryData['stats']['total_images'] }}</span> imágenes y 
                                    <span class="font-semibold text-gray-200">{{ $galleryData['stats']['total_videos'] }}</span> videos en 
                                    <span class="font-semibold text-gray-200">{{ $galleryData['stats']['total_missions'] }}</span> misiones
                                </div>
                                <div class="flex space-x-3">
                                    <button type="button" id="clearFilters" 
                                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-800 focus:ring ring-gray-300 transition ease-in-out duration-150">
                                        Limpiar Filtros
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Carrusel de Imágenes -->
                        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-medium text-gray-100">Imágenes</h3>
                                <span class="text-sm text-gray-400" id="imagesCount">
                                    {{ $galleryData['stats']['total_images'] }} imágenes
                                </span>
                            </div>

                            <div class="relative">
                                <!-- Botones de navegación -->
                                <button id="prevImages" class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-4 bg-gray-700 hover:bg-gray-600 text-white rounded-full p-3 z-10 transition-all duration-200 shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>
                                
                                <button id="nextImages" class="absolute right-0 top-1/2 transform -translate-y-1/2 translate-x-4 bg-gray-700 hover:bg-gray-600 text-white rounded-full p-3 z-10 transition-all duration-200 shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>

                                <!-- Contenedor del carrusel -->
                                <div id="imagesCarousel" class="flex space-x-4 overflow-x-auto scrollbar-hide snap-x snap-mandatory py-4 px-2" 
                                     style="scroll-behavior: smooth;">
                                    <!-- Las imágenes se cargarán dinámicamente -->
                                </div>
                            </div>

                            <!-- Indicadores de carga -->
                            <div id="imagesLoading" class="text-center py-8">
                                <div class="inline-flex items-center space-x-2 text-gray-400">
                                    <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                    <span>Cargando imágenes...</span>
                                </div>
                            </div>
                        </div>

                        <!-- Carrusel de Videos -->
                        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-medium text-gray-100">Videos</h3>
                                <span class="text-sm text-gray-400" id="videosCount">
                                    {{ $galleryData['stats']['total_videos'] }} videos
                                </span>
                            </div>

                            <div class="relative">
                                <!-- Botones de navegación -->
                                <button id="prevVideos" class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-4 bg-gray-700 hover:bg-gray-600 text-white rounded-full p-3 z-10 transition-all duration-200 shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>
                                
                                <button id="nextVideos" class="absolute right-0 top-1/2 transform -translate-y-1/2 translate-x-4 bg-gray-700 hover:bg-gray-600 text-white rounded-full p-3 z-10 transition-all duration-200 shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>

                                <!-- Contenedor del carrusel -->
                                <div id="videosCarousel" class="flex space-x-4 overflow-x-auto scrollbar-hide snap-x snap-mandatory py-4 px-2" 
                                     style="scroll-behavior: smooth;">
                                    <!-- Los videos se cargarán dinámicamente -->
                                </div>
                            </div>

                            <!-- Indicadores de carga -->
                            <div id="videosLoading" class="text-center py-8">
                                <div class="inline-flex items-center space-x-2 text-gray-400">
                                    <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                    <span>Cargando videos...</span>
                                </div>
                            </div>
                        </div>

                        <!-- Mensaje cuando no hay resultados -->
                        <div id="noResults" class="hidden text-center py-12 bg-gray-800 rounded-lg border border-gray-700">
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

    <!-- Modal para Imágenes -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 hidden">
        <div class="relative max-w-6xl max-h-full w-full mx-4">
            <div class="bg-gray-900 rounded-lg overflow-hidden shadow-2xl">
                <!-- Header con título y botones -->
                <div class="flex justify-between items-center p-4 border-b border-gray-700">
                    <h3 id="imageModalTitle" class="text-lg font-semibold text-white truncate max-w-2xl"></h3>
                    <div class="flex items-center space-x-3">
                        <button id="downloadImageBtn" class="text-gray-400 hover:text-white transition-colors duration-200" title="Descargar">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </button>
                        <button id="closeImageModalBtn" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Contenido de la imagen -->
                <div class="p-4 flex justify-center items-center max-h-[70vh] overflow-auto">
                    <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Videos -->
    <div id="videoModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 hidden">
        <div class="relative max-w-4xl max-h-full w-full mx-4">
            <div class="bg-gray-900 rounded-lg overflow-hidden shadow-2xl">
                <!-- Header con título y botones -->
                <div class="flex justify-between items-center p-4 border-b border-gray-700">
                    <h3 id="videoModalTitle" class="text-lg font-semibold text-white truncate max-w-2xl"></h3>
                    <div class="flex items-center space-x-3">
                        <button id="downloadVideoBtn" class="text-gray-400 hover:text-white transition-colors duration-200" title="Descargar">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </button>
                        <button id="closeVideoModalBtn" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Contenido del video -->
                <div class="p-4 flex justify-center items-center">
                    <video id="modalVideo" controls class="max-w-full max-h-[70vh] rounded-lg">
                        Tu navegador no soporta el elemento video.
                    </video>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Datos globales para manejar el estado
        const galleryState = {
            allMedia: @json($allMedia),
            visibleImages: [],
            visibleVideos: [],
            currentImageIndex: 0,
            currentVideoIndex: 0,
            itemsPerPage: 10,
            loadedImages: 0,
            loadedVideos: 0
        };

        // Inicialización cuando el DOM está listo
        document.addEventListener('DOMContentLoaded', function() {
            initializeGallery();
            setupEventListeners();
            loadInitialItems();
        });

        function initializeGallery() {
            // Separar imágenes y videos
            galleryState.visibleImages = galleryState.allMedia.filter(media => media.type === 'image');
            galleryState.visibleVideos = galleryState.allMedia.filter(media => media.type === 'video');
            
            // Actualizar contadores
            updateCounters();
        }

        function setupEventListeners() {
            // Navegación de carruseles
            document.getElementById('prevImages').addEventListener('click', () => scrollCarousel('images', -1));
            document.getElementById('nextImages').addEventListener('click', () => scrollCarousel('images', 1));
            document.getElementById('prevVideos').addEventListener('click', () => scrollCarousel('videos', -1));
            document.getElementById('nextVideos').addEventListener('click', () => scrollCarousel('videos', 1));

            // Modales
            document.getElementById('closeImageModalBtn').addEventListener('click', closeImageModal);
            document.getElementById('closeVideoModalBtn').addEventListener('click', closeVideoModal);
            document.getElementById('downloadImageBtn').addEventListener('click', downloadCurrentImage);
            document.getElementById('downloadVideoBtn').addEventListener('click', downloadCurrentVideo);

            // Filtros
            document.getElementById('droneFilter').addEventListener('change', applyFilters);
            document.getElementById('missionFilter').addEventListener('change', applyFilters);
            document.getElementById('clearFilters').addEventListener('click', clearFilters);

            // Cerrar modales con ESC
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeImageModal();
                    closeVideoModal();
                }
            });

            // Cerrar modales al hacer click fuera
            document.getElementById('imageModal').addEventListener('click', (e) => {
                if (e.target.id === 'imageModal') closeImageModal();
            });
            document.getElementById('videoModal').addEventListener('click', (e) => {
                if (e.target.id === 'videoModal') closeVideoModal();
            });

            // Lazy loading al hacer scroll en los carruseles
            const imagesCarousel = document.getElementById('imagesCarousel');
            const videosCarousel = document.getElementById('videosCarousel');
            
            imagesCarousel.addEventListener('scroll', () => handleCarouselScroll('images'));
            videosCarousel.addEventListener('scroll', () => handleCarouselScroll('videos'));
        }

        function loadInitialItems() {
            loadCarouselItems('images', 0, galleryState.itemsPerPage);
            loadCarouselItems('videos', 0, galleryState.itemsPerPage);
        }

        function loadCarouselItems(type, startIndex, count) {
            const carousel = document.getElementById(`${type}Carousel`);
            const loadingElement = document.getElementById(`${type}Loading`);
            const items = type === 'images' ? galleryState.visibleImages : galleryState.visibleVideos;
            
            if (items.length === 0) {
                carousel.innerHTML = '<div class="text-center text-gray-400 py-8">No hay ' + type + ' disponibles</div>';
                loadingElement.classList.add('hidden');
                return;
            }

            const endIndex = Math.min(startIndex + count, items.length);
            
            for (let i = startIndex; i < endIndex; i++) {
                const media = items[i];
                const itemElement = createMediaElement(media, i, type);
                carousel.appendChild(itemElement);
            }

            // Ocultar loading cuando se cargan los primeros items
            if (startIndex === 0) {
                loadingElement.classList.add('hidden');
                carousel.classList.remove('hidden');
            }
        }

        function createMediaElement(media, index, type) {
            const div = document.createElement('div');
            div.className = 'flex-shrink-0 w-64 bg-gray-700 rounded-lg overflow-hidden border border-gray-600 hover:border-blue-500 transition-all duration-300 snap-start';
            div.style.scrollSnapAlign = 'start';

            if (type === 'image') {
                div.innerHTML = `
                    <div class="relative group cursor-pointer" onclick="openImageModal(${index})">
                        <img src="${media.url}" 
                             alt="${media.filename}"
                             class="w-full h-48 object-cover"
                             loading="lazy"
                             onload="handleImageLoad('images')"
                             onerror="handleMediaError(this, 'image')">
                        
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-300 flex items-end">
                            <div class="p-3 w-full transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                <div class="text-white text-sm font-medium truncate">
                                    ${media.patterns.mission}
                                </div>
                                <div class="text-gray-300 text-xs mt-1">
                                    ${media.patterns.prefix} • ${formatTimestamp(media.patterns.timestamp)}
                                </div>
                            </div>
                        </div>

                        <div class="absolute top-2 right-2">
                            <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full">
                                IMG
                            </span>
                        </div>
                    </div>
                `;
            } else {
                div.innerHTML = `
                    <div class="relative group cursor-pointer" onclick="openVideoModal(${index})">
                        <div class="w-full h-48 bg-gray-600 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-300 flex items-end">
                            <div class="p-3 w-full transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                <div class="text-white text-sm font-medium truncate">
                                    ${media.patterns.mission}
                                </div>
                                <div class="text-gray-300 text-xs mt-1">
                                    ${media.patterns.prefix} • ${formatTimestamp(media.patterns.timestamp)}
                                </div>
                            </div>
                        </div>

                        <div class="absolute top-2 right-2">
                            <span class="bg-red-600 text-white text-xs px-2 py-1 rounded-full">
                                VID
                            </span>
                        </div>
                    </div>
                `;
            }

            return div;
        }

        function handleCarouselScroll(type) {
            const carousel = document.getElementById(`${type}Carousel`);
            const items = type === 'images' ? galleryState.visibleImages : galleryState.visibleVideos;
            const loadedCount = type === 'images' ? galleryState.loadedImages : galleryState.loadedVideos;
            
            // Cargar más items cuando el usuario se acerca al final
            const scrollLeft = carousel.scrollLeft;
            const scrollWidth = carousel.scrollWidth;
            const clientWidth = carousel.clientWidth;
            
            if (scrollLeft + clientWidth >= scrollWidth - 100 && loadedCount < items.length) {
                loadCarouselItems(type, loadedCount, galleryState.itemsPerPage);
                
                if (type === 'images') {
                    galleryState.loadedImages += galleryState.itemsPerPage;
                } else {
                    galleryState.loadedVideos += galleryState.itemsPerPage;
                }
            }
        }

        function scrollCarousel(type, direction) {
            const carousel = document.getElementById(`${type}Carousel`);
            const scrollAmount = 300; // Ajusta según el ancho de tus items
            
            carousel.scrollBy({
                left: direction * scrollAmount,
                behavior: 'smooth'
            });
        }

        // Funciones de modales
        function openImageModal(index) {
            const media = galleryState.visibleImages[index];
            galleryState.currentImageIndex = index;
            
            document.getElementById('modalImage').src = media.url;
            document.getElementById('imageModalTitle').textContent = media.filename;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function openVideoModal(index) {
            const media = galleryState.visibleVideos[index];
            galleryState.currentVideoIndex = index;
            
            const video = document.getElementById('modalVideo');
            video.src = media.url;
            document.getElementById('videoModalTitle').textContent = media.filename;
            document.getElementById('videoModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Intentar reproducir automáticamente
            video.play().catch(e => console.log('Autoplay prevented:', e));
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function closeVideoModal() {
            const video = document.getElementById('modalVideo');
            video.pause();
            video.currentTime = 0;
            document.getElementById('videoModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function downloadCurrentImage() {
            const media = galleryState.visibleImages[galleryState.currentImageIndex];
            downloadFile(media.url, media.filename);
        }

        function downloadCurrentVideo() {
            const media = galleryState.visibleVideos[galleryState.currentVideoIndex];
            downloadFile(media.url, media.filename);
        }

        function downloadFile(url, filename) {
            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Funciones de utilidad
        function formatTimestamp(timestamp) {
            if (timestamp !== 'unknown' && timestamp.length === 14) {
                const date = timestamp.substr(0, 8);
                const time = timestamp.substr(8, 6);
                return `${date.substr(6, 2)}/${date.substr(4, 2)}/${date.substr(0, 4)} ${time.substr(0, 2)}:${time.substr(2, 2)}`;
            }
            return 'Fecha desconocida';
        }

        function handleImageLoad(type) {
            // Puedes usar esto para tracking de carga si es necesario
        }

        function handleMediaError(element, type) {
            console.error(`Error loading ${type}:`, element.src);
            element.src = '/placeholder-image.jpg'; // Imagen de placeholder en caso de error
        }

        function updateCounters() {
            document.getElementById('imagesCount').textContent = `${galleryState.visibleImages.length} imágenes`;
            document.getElementById('videosCount').textContent = `${galleryState.visibleVideos.length} videos`;
        }

        function applyFilters() {
            const droneFilter = document.getElementById('droneFilter').value.toLowerCase();
            const missionFilter = document.getElementById('missionFilter').value.toLowerCase();

            galleryState.visibleImages = galleryState.allMedia.filter(media => 
                media.type === 'image' &&
                (!droneFilter || media.patterns.prefix.toLowerCase().includes(droneFilter)) &&
                (!missionFilter || media.patterns.mission.toLowerCase().includes(missionFilter))
            );

            galleryState.visibleVideos = galleryState.allMedia.filter(media => 
                media.type === 'video' &&
                (!droneFilter || media.patterns.prefix.toLowerCase().includes(droneFilter)) &&
                (!missionFilter || media.patterns.mission.toLowerCase().includes(missionFilter))
            );

            // Resetear carruseles
            resetCarousels();
            updateCounters();
            checkEmptyState();
        }

        function resetCarousels() {
            // Limpiar y recargar carruseles
            document.getElementById('imagesCarousel').innerHTML = '';
            document.getElementById('videosCarousel').innerHTML = '';
            document.getElementById('imagesLoading').classList.remove('hidden');
            document.getElementById('videosLoading').classList.remove('hidden');
            
            galleryState.loadedImages = 0;
            galleryState.loadedVideos = 0;
            
            loadInitialItems();
        }

        function clearFilters() {
            document.getElementById('droneFilter').value = '';
            document.getElementById('missionFilter').value = '';
            applyFilters();
        }

        function checkEmptyState() {
            const noResults = document.getElementById('noResults');
            if (galleryState.visibleImages.length === 0 && galleryState.visibleVideos.length === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }
    </script>

    <style>
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        .snap-x {
            scroll-snap-type: x mandatory;
        }
        
        .snap-mandatory {
            scroll-snap-stop: always;
        }
        
        .snap-start {
            scroll-snap-align: start;
        }
    </style>
@endsection