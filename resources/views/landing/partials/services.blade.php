{{-- Services Section --}}
<section id="servicios" class="py-24 bg-[#0e1321] relative overflow-hidden">
    {{-- Background decoration --}}
    <div class="absolute top-0 left-0 w-96 h-96 bg-blue-600/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-600/5 rounded-full blur-3xl"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Section Header --}}
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-blue-500 font-semibold text-sm uppercase tracking-wider">Nuestros Servicios</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mt-4 mb-6">
                Soluciones Integrales de Seguridad
            </h2>
            <p class="text-gray-400 text-lg max-w-3xl mx-auto">
                Ofrecemos un ecosistema completo de productos, servicios y tecnología para proteger lo que más importa.
            </p>
        </div>
        
        {{-- Services Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {{-- Service 1: Productos Hikvision --}}
            <div class="card-hover bg-[#16262e] rounded-2xl overflow-hidden group cursor-pointer" 
                 data-aos="fade-up" data-aos-delay="0"
                 onclick="openServiceModal('hikvision')">
                <div class="h-48 overflow-hidden relative">
                    <img src="{{ asset('hikvision-cameras.jpeg') }}" 
                         alt="Cámaras Hikvision" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-6">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-xl font-bold text-white mb-3">Productos Hikvision</h3>
                            <p class="text-gray-400">
                                Cámaras inteligentes con detección de patentes (ANPR), control de acceso facial, domos 360°, bodycams y más.
                            </p>
                        </div>
                        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600/20 flex items-center justify-center group-hover:bg-blue-600 transition-colors">
                            <svg class="w-4 h-4 text-blue-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
            
            {{-- Service 2: Flota Vehicular --}}
            <div class="card-hover bg-[#16262e] rounded-2xl overflow-hidden group cursor-pointer" 
                 data-aos="fade-up" data-aos-delay="100"
                 onclick="openServiceModal('flota')">
                <div class="h-48 overflow-hidden">
                    <img src="{{ asset('camioneta.png') }}" 
                         alt="Flota Vehicular" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-6">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-xl font-bold text-white mb-3">Flota Vehicular Equipada</h3>
                            <p class="text-gray-400">
                                Camionetas de patrullaje con equipamiento completo: cámaras 360°, DVR, Starlink, balizas y más.
                            </p>
                        </div>
                        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600/20 flex items-center justify-center group-hover:bg-blue-600 transition-colors">
                            <svg class="w-4 h-4 text-blue-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
            
            {{-- Service 3: HikCentral --}}
            <div class="card-hover bg-[#16262e] rounded-2xl overflow-hidden group cursor-pointer" 
                 data-aos="fade-up" data-aos-delay="200"
                 onclick="openServiceModal('hikcentral')">
                <div class="h-48 overflow-hidden">
                    <img src="{{ asset('hikcentral-panel-control.png') }}" 
                         alt="HikCentral Panel" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-6">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-xl font-bold text-white mb-3">Servidor HikCentral</h3>
                            <p class="text-gray-400">
                                Plataforma centralizada para video en vivo, geolocalización, eventos inteligentes e historial de accesos.
                            </p>
                        </div>
                        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600/20 flex items-center justify-center group-hover:bg-blue-600 transition-colors">
                            <svg class="w-4 h-4 text-blue-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
            
            {{-- Service 4: Drones DJI --}}
            <div class="card-hover bg-[#16262e] rounded-2xl overflow-hidden group cursor-pointer" 
                 data-aos="fade-up" data-aos-delay="300"
                 onclick="openServiceModal('drones')">
                <div class="h-48 overflow-hidden">
                    <img src="{{ asset('drone-dock.png') }}" 
                         alt="Drones DJI" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-6">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-xl font-bold text-white mb-3">Drones DJI Autónomos</h3>
                            <p class="text-gray-400">
                                Drones de vuelo automático con docks, cámaras zoom/térmicas, luces y bocinas para patrullaje aéreo.
                            </p>
                        </div>
                        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600/20 flex items-center justify-center group-hover:bg-blue-600 transition-colors">
                            <svg class="w-4 h-4 text-blue-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
            
            {{-- Service 5: Monitoreo 24/7 --}}
            <div class="card-hover bg-[#16262e] rounded-2xl overflow-hidden group" 
                 data-aos="fade-up" data-aos-delay="400">
                <div class="h-48 overflow-hidden">
                    <img src="{{ asset('monitoreo.webp') }}" 
                         alt="Monitoreo 24/7" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-white mb-3">Monitoreo 24/7</h3>
                    <p class="text-gray-400">
                        Vigilancia en tiempo real las 24 horas con soporte de emergencias y respuesta inmediata ante incidentes.
                    </p>
                </div>
            </div>
            
            {{-- Service 6: Desarrollo a Medida --}}
            <div class="card-hover bg-[#16262e] rounded-2xl overflow-hidden group" 
                 data-aos="fade-up" data-aos-delay="500">
                <div class="h-48 overflow-hidden">
                    <img src="{{ asset('dev.jpg') }}" 
                         alt="Desarrollo a Medida" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-white mb-3">Desarrollo a Medida</h3>
                    <p class="text-gray-400">
                        Soluciones de software personalizadas para empresas con necesidades específicas de seguridad.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Service Modals --}}
    <div id="service-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeServiceModal()"></div>
        
        {{-- Modal Content --}}
        <div class="relative bg-[#1a1d1f] rounded-2xl max-w-5xl w-full max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="service-modal-content">
            {{-- Close Button --}}
            <button onclick="closeServiceModal()" class="absolute top-4 right-4 z-10 text-gray-400 hover:text-white transition-colors bg-black/30 rounded-full p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            {{-- Modal Body --}}
            <div class="flex flex-col lg:flex-row">
                {{-- Left: Text Content --}}
                <div class="lg:w-1/2 p-8 overflow-y-auto max-h-[80vh]">
                    <div class="mb-4">
                        <span class="text-blue-500 text-sm font-semibold uppercase tracking-wider" id="modal-category">Categoría</span>
                    </div>
                    <h2 class="text-3xl font-bold text-white mb-4" id="modal-title">Título del Servicio</h2>
                    <div class="text-gray-300 space-y-4" id="modal-description">
                        <!-- Content will be injected here -->
                    </div>
                    
                    {{-- Features List --}}
                    <div class="mt-6" id="modal-features">
                        <!-- Features will be injected here -->
                    </div>
                    
                    {{-- CTA --}}
                    <div class="mt-8">
                        <a href="#contacto" onclick="closeServiceModal()" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            Solicitar Información
                        </a>
                    </div>
                </div>
                
                {{-- Right: Image Carousel --}}
                <div class="lg:w-1/2 bg-[#1a1d1f] relative flex items-center justify-center p-6">
                    <div class="w-full h-full relative" id="modal-carousel">
                        {{-- Carousel Images --}}
                        <div class="carousel-images relative w-full h-full flex items-center justify-center" id="carousel-container">
                            <!-- Images will be injected here -->
                        </div>
                        
                        {{-- Carousel Navigation Dots --}}
                        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 flex gap-2 pb-2" id="carousel-dots">
                            <!-- Dots will be injected here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Service data
        const servicesData = {
            hikvision: {
                category: 'Productos',
                title: 'Productos Hikvision',
                description: `
                    <p>Ofrecemos una amplia gama de productos hikvision originales diseñados para satisfacer las necesidades más exigentes de seguridad.</p>
                    <p>Entre ellos cámaras IP de alta resolución, sistemas de reconocimiento facial, cámaras ANPR para detección automática de patentes, controles de acceso biométricos, domos PTZ con zoom óptico, bodycams profesionales y mucho más.</p>
                    <p>Nos encargamos de la instalacion y mantenimiento.</p>
                `,
                features: [
                    'Cámaras con resolución hasta 4K Ultra HD',
                    'Reconocimiento facial con IA avanzada',
                    'Detección de patentes ANPR en tiempo real',
                    'Camaras domo PTZ giran 360º altas distancias',
                    'Control de acceso facial y biométrico',
                    'Bodycams con GPS y almacenamiento seguro'
                ],
                images: [
                    '{{ asset("BODYCAM.png") }}',
                    '{{ asset("ANPR.png") }}',
                    '{{ asset("DOOR.png") }}'
                ]
            },
            flota: {
                category: 'Servicios',
                title: 'Flota Vehicular Equipada',
                description: `
                    <p>Proveemos vehículos de patrullaje completamente equipados con la más avanzada tecnología de seguridad y comunicaciones. Cada unidad está diseñada para maximizar la eficiencia operativa y la seguridad del personal.</p>
                    <p>Nuestras camionetas incluyen sistemas de cámaras 360° con grabación continua, conectividad Starlink para cobertura en zonas remotas, GPS de alta precisión, sistema de balizas y señalización, y un robusto sistema DVR para almacenamiento de evidencia.</p>
                    <p>Ofrecemos opciones de alquiler o venta, con planes de mantenimiento preventivo incluidos.</p>
                `,
                features: [
                    'Cámaras 360° con visión nocturna',
                    'DVR con almacenamiento de 30 días',
                    'Conectividad Starlink satelital',
                    'GPS tracking en tiempo real',
                    'Sistema de balizas LED de emergencia',
                    'Radio comunicación encriptada'
                ],
                images: [
                    '{{ asset("service-flota-1.jpg") }}',
                    '{{ asset("service-flota-2.jpg") }}',
                    '{{ asset("service-flota-3.jpg") }}'
                ]
            },
            hikcentral: {
                category: 'Software',
                title: 'Servidor HikCentral Professional',
                description: `
                    <p>HikCentral Professional es la plataforma de gestión de video más completa del mercado. Permite centralizar todas las cámaras, controles de acceso y alarmas de tu organización en una única interfaz intuitiva.</p>
                    <p>Con capacidad para gestionar miles de dispositivos simultáneamente, HikCentral ofrece streaming de video en vivo, reproducción de grabaciones, mapas interactivos con geolocalización de dispositivos, gestión de eventos inteligentes y generación de reportes automatizados.</p>
                    <p>Incluimos instalación, configuración, capacitación y soporte técnico continuo.</p>
                `,
                features: [
                    'Gestión centralizada de miles de cámaras',
                    'Video en vivo y reproducción de grabaciones',
                    'Mapas interactivos con ubicación de dispositivos',
                    'Eventos inteligentes automáticos',
                    'Integración con control de acceso',
                    'Reportes y estadísticas avanzadas'
                ],
                images: [
                    '{{ asset("service-hikcentral-1.jpg") }}',
                    '{{ asset("service-hikcentral-2.jpg") }}',
                    '{{ asset("service-hikcentral-3.jpg") }}'
                ]
            },
            drones: {
                category: 'Tecnología',
                title: 'Drones DJI Autónomos',
                description: `
                    <p>Implementamos soluciones de patrullaje aéreo con drones DJI de última generación. Nuestros sistemas incluyen drones con capacidad de vuelo autónomo, docks de carga automática y software de gestión de misiones.</p>
                    <p>Los drones están equipados con cámaras de alta resolución con zoom óptico, sensores térmicos para detección nocturna, luces LED de alta potencia, bocinas para comunicación remota y transmisión de video en tiempo real.</p>
                    <p>Ofrecemos servicio completo: equipamiento, instalación de docks, configuración de rutas, capacitación de operadores y mantenimiento preventivo.</p>
                `,
                features: [
                    'Vuelo autónomo con rutas programadas',
                    'Docks de carga y despegue automático',
                    'Cámaras con zoom óptico 200x',
                    'Sensores térmicos de alta sensibilidad',
                    'Transmisión de video HD en tiempo real',
                    'Integración completa con COS'
                ],
                images: [
                    '{{ asset("service-drones-1.jpg") }}',
                    '{{ asset("service-drones-2.jpg") }}',
                    '{{ asset("service-drones-3.jpg") }}'
                ]
            }
        };
        
        let currentSlide = 0;
        let slideInterval = null;
        let currentImages = [];
        
        function openServiceModal(serviceKey) {
            const service = servicesData[serviceKey];
            if (!service) return;
            
            // Populate modal content
            document.getElementById('modal-category').textContent = service.category;
            document.getElementById('modal-title').textContent = service.title;
            document.getElementById('modal-description').innerHTML = service.description;
            
            // Populate features
            const featuresHtml = `
                <h4 class="text-lg font-semibold text-white mb-3">Características principales:</h4>
                <ul class="space-y-2">
                    ${service.features.map(feature => `
                        <li class="flex items-start gap-2 text-gray-400">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            ${feature}
                        </li>
                    `).join('')}
                </ul>
            `;
            document.getElementById('modal-features').innerHTML = featuresHtml;
            
            // Setup carousel
            currentImages = service.images;
            currentSlide = 0;
            setupCarousel();
            
            // Show modal with animation
            const modal = document.getElementById('service-modal');
            const modalContent = document.getElementById('service-modal-content');
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            
            // Trigger animation
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
            
            // Start auto-slide
            startAutoSlide();
        }
        
        function closeServiceModal() {
            const modal = document.getElementById('service-modal');
            const modalContent = document.getElementById('service-modal-content');
            
            // Animate out
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            
            // Stop auto-slide
            stopAutoSlide();
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }, 300);
        }
        
        function setupCarousel() {
            const container = document.getElementById('carousel-container');
            const dotsContainer = document.getElementById('carousel-dots');
            
            // Create images
            container.innerHTML = currentImages.map((src, index) => `
                <div class="carousel-slide absolute inset-0 transition-opacity duration-700 flex items-center justify-center ${index === 0 ? 'opacity-100' : 'opacity-0'}">
                    <img src="${src}" alt="Imagen ${index + 1}" class="max-w-full max-h-full object-contain rounded-lg" onerror="this.src='https://via.placeholder.com/800x600/1f2937/6b7280?text=Imagen+${index + 1}'">
                </div>
            `).join('');
            
            // Create dots
            dotsContainer.innerHTML = currentImages.map((_, index) => `
                <button class="w-2.5 h-2.5 rounded-full transition-colors ${index === 0 ? 'bg-blue-500' : 'bg-white/30 hover:bg-white/50'}" onclick="goToSlide(${index}, event)"></button>
            `).join('');
        }
        
        function goToSlide(index, event) {
            if (event) event.stopPropagation();
            
            const slides = document.querySelectorAll('.carousel-slide');
            const dots = document.querySelectorAll('#carousel-dots button');
            
            slides.forEach((slide, i) => {
                slide.classList.toggle('opacity-100', i === index);
                slide.classList.toggle('opacity-0', i !== index);
            });
            
            dots.forEach((dot, i) => {
                dot.classList.toggle('bg-blue-500', i === index);
                dot.classList.toggle('bg-white/50', i !== index);
            });
            
            currentSlide = index;
        }
        
        function nextSlide() {
            const nextIndex = (currentSlide + 1) % currentImages.length;
            goToSlide(nextIndex);
        }
        
        function startAutoSlide() {
            slideInterval = setInterval(() => {
                nextSlide();
            }, 3500);
        }
        
        function stopAutoSlide() {
            if (slideInterval) {
                clearInterval(slideInterval);
                slideInterval = null;
            }
        }
        
        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeServiceModal();
            }
        });
    </script>
</section>
