{{-- Product Tour Section --}}
<section id="tour" class="py-24 bg-[#0e1321] relative overflow-hidden">
    {{-- Background decoration --}}
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-blue-600/5 rounded-full blur-3xl"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Section Header --}}
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-blue-500 font-semibold text-sm uppercase tracking-wider">Tour del Producto</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mt-4 mb-6">
                Explora las Funcionalidades de COS
            </h2>
            <p class="text-gray-400 text-lg max-w-3xl mx-auto">
                Conoce en detalle cada módulo de nuestra plataforma. Haz clic en cualquier tarjeta para ver más información.
            </p>
        </div>
        
        {{-- Tour Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Tour Card 1: Dashboard de Eventos --}}
            <div class="group cursor-pointer" data-aos="fade-up" data-aos-delay="0" onclick="openTourModal('eventos')">
                <div class="bg-[#16262e] rounded-2xl overflow-hidden border border-white/5 hover:border-blue-500/30 transition-all duration-300 card-hover">
                    <div class="aspect-video bg-gradient-to-br from-[#1b3761] to-[#203c58] relative overflow-hidden">
                        <img src="{{ asset('cos-misiones-dron.png') }}" alt="Dashboard de Eventos" class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#16262e] via-transparent to-transparent"></div>
                        <div class="absolute top-4 right-4 w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-600/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white">Dashboard de Eventos</h3>
                        </div>
                        <p class="text-gray-400 text-sm">Visualiza, registra y gestiona todos los eventos de seguridad desde un panel centralizado.</p>
                    </div>
                </div>
            </div>
            
            {{-- Tour Card 2: Mapa de Patrullas --}}
            <div class="group cursor-pointer" data-aos="fade-up" data-aos-delay="100" onclick="openTourModal('patrullas')">
                <div class="bg-[#16262e] rounded-2xl overflow-hidden border border-white/5 hover:border-blue-500/30 transition-all duration-300 card-hover">
                    <div class="aspect-video bg-gradient-to-br from-[#1b3761] to-[#203c58] relative overflow-hidden flex items-center justify-center">
                        <svg class="w-24 h-24 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                        <div class="absolute top-4 right-4 w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-600/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white">Mapa Interactivo</h3>
                        </div>
                        <p class="text-gray-400 text-sm">Ubicación en tiempo real de patrullas, vehículos y personal de seguridad en un mapa dinámico.</p>
                    </div>
                </div>
            </div>
            
            {{-- Tour Card 3: Control de Drones --}}
            <div class="group cursor-pointer" data-aos="fade-up" data-aos-delay="200" onclick="openTourModal('drones')">
                <div class="bg-[#16262e] rounded-2xl overflow-hidden border border-white/5 hover:border-blue-500/30 transition-all duration-300 card-hover">
                    <div class="aspect-video bg-gradient-to-br from-[#1b3761] to-[#203c58] relative overflow-hidden">
                        <img src="{{ asset('drone-streaming-1.png') }}" alt="Control de Drones" class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#16262e] via-transparent to-transparent"></div>
                        <div class="absolute top-4 right-4 w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-600/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white">Panel de Drones</h3>
                        </div>
                        <p class="text-gray-400 text-sm">Lanza misiones, visualiza streaming en vivo y gestiona toda tu flota de drones.</p>
                    </div>
                </div>
            </div>
            
            {{-- Tour Card 4: Integración Hikvision --}}
            <div class="group cursor-pointer" data-aos="fade-up" data-aos-delay="300" onclick="openTourModal('hikvision')">
                <div class="bg-[#16262e] rounded-2xl overflow-hidden border border-white/5 hover:border-blue-500/30 transition-all duration-300 card-hover">
                    <div class="aspect-video bg-gradient-to-br from-[#1b3761] to-[#203c58] relative overflow-hidden">
                        <img src="{{ asset('hikvision-access-control.jpg') }}" alt="Integración Hikvision" class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#16262e] via-transparent to-transparent"></div>
                        <div class="absolute top-4 right-4 w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-600/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white">Alertas Hikvision</h3>
                        </div>
                        <p class="text-gray-400 text-sm">Recibe y gestiona eventos inteligentes de cámaras: ANPR, rostros, intrusiones.</p>
                    </div>
                </div>
            </div>
            
            {{-- Tour Card 5: Reportes y Analytics --}}
            <div class="group cursor-pointer" data-aos="fade-up" data-aos-delay="400" onclick="openTourModal('reportes')">
                <div class="bg-[#16262e] rounded-2xl overflow-hidden border border-white/5 hover:border-blue-500/30 transition-all duration-300 card-hover">
                    <div class="aspect-video bg-gradient-to-br from-[#1b3761] to-[#203c58] relative overflow-hidden flex items-center justify-center">
                        <svg class="w-24 h-24 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <div class="absolute top-4 right-4 w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-600/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white">Reportes y Analytics</h3>
                        </div>
                        <p class="text-gray-400 text-sm">Genera reportes profesionales en PDF con estadísticas y análisis de datos.</p>
                    </div>
                </div>
            </div>
            
            {{-- Tour Card 6: Galería Multimedia --}}
            <div class="group cursor-pointer" data-aos="fade-up" data-aos-delay="500" onclick="openTourModal('galeria')">
                <div class="bg-[#16262e] rounded-2xl overflow-hidden border border-white/5 hover:border-blue-500/30 transition-all duration-300 card-hover">
                    <div class="aspect-video bg-gradient-to-br from-[#1b3761] to-[#203c58] relative overflow-hidden flex items-center justify-center">
                        <svg class="w-24 h-24 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <div class="absolute top-4 right-4 w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-600/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white">Galería Multimedia</h3>
                        </div>
                        <p class="text-gray-400 text-sm">Accede a todas las imágenes y videos capturados por drones y cámaras.</p>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- CTA --}}
        <div class="text-center mt-12" data-aos="fade-up">
            <a href="#contacto" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-bold transition-all hover:shadow-xl hover:shadow-blue-600/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                Solicitar Demo Personalizada
            </a>
        </div>
    </div>
    
    {{-- Tour Modal --}}
    <div id="tour-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="bg-[#1a1d1f] rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-white/10">
                <h3 id="modal-title" class="text-xl font-bold text-white">Título</h3>
                <button onclick="closeTourModal()" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="modal-content" class="p-6 overflow-y-auto max-h-[70vh]">
                {{-- Content will be inserted here --}}
            </div>
            <div class="p-6 border-t border-white/10 flex justify-end gap-4">
                <button onclick="closeTourModal()" class="px-6 py-2 text-gray-400 hover:text-white transition-colors">
                    Cerrar
                </button>
                <a href="#contacto" onclick="closeTourModal()" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                    Solicitar Demo
                </a>
            </div>
        </div>
    </div>
    
    <script>
        const tourData = {
            eventos: {
                title: 'Dashboard de Eventos de Seguridad',
                content: `
                    <div class="space-y-6">
                        <p class="text-gray-300">El Dashboard de Eventos es el corazón de COS, permitiéndote registrar, categorizar y dar seguimiento a todos los incidentes de seguridad.</p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">Registro de Eventos</h4>
                                <p class="text-gray-400 text-sm">Documenta robos, vandalismos, intrusiones, emergencias y cualquier incidente con evidencia multimedia.</p>
                            </div>
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">Categorización</h4>
                                <p class="text-gray-400 text-sm">Clasifica eventos por tipo, gravedad, zona y estado para un seguimiento eficiente.</p>
                            </div>
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">Geolocalización</h4>
                                <p class="text-gray-400 text-sm">Cada evento queda vinculado a su ubicación exacta en el mapa.</p>
                            </div>
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">Reportes PDF</h4>
                                <p class="text-gray-400 text-sm">Genera reportes profesionales con toda la información del evento.</p>
                            </div>
                        </div>
                    </div>
                `
            },
            patrullas: {
                title: 'Mapa Interactivo de Patrullas',
                content: `
                    <div class="space-y-6">
                        <p class="text-gray-300">Visualiza la ubicación de toda tu flota de seguridad en tiempo real sobre un mapa interactivo.</p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">GPS en Tiempo Real</h4>
                                <p class="text-gray-400 text-sm">Tracking continuo de cada vehículo y unidad de patrullaje.</p>
                            </div>
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">Historial de Rutas</h4>
                                <p class="text-gray-400 text-sm">Consulta los recorridos realizados por cada patrulla.</p>
                            </div>
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">Estado de Flota</h4>
                                <p class="text-gray-400 text-sm">Documentación, vencimientos y estado operativo de vehículos.</p>
                            </div>
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">Asignación de Objetivos</h4>
                                <p class="text-gray-400 text-sm">Define y asigna servicios y zonas a cada patrulla.</p>
                            </div>
                        </div>
                    </div>
                `
            },
            drones: {
                title: 'Panel de Control de Drones',
                content: `
                    <div class="space-y-6">
                        <p class="text-gray-300">Gestiona toda tu flota de drones DJI desde un panel centralizado. Lanza misiones, visualiza streaming y revisa flight logs.</p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">Misiones a Demanda</h4>
                                <p class="text-gray-400 text-sm">Lanza vuelos instantáneos o programados desde la plataforma.</p>
                            </div>
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">Streaming HD</h4>
                                <p class="text-gray-400 text-sm">Video en vivo durante todo el vuelo con cámara térmica y zoom.</p>
                            </div>
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">Flight Logs</h4>
                                <p class="text-gray-400 text-sm">Registro completo de todas las operaciones de vuelo.</p>
                            </div>
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">No-Fly Zones</h4>
                                <p class="text-gray-400 text-sm">Configura zonas de restricción de vuelo en el mapa.</p>
                            </div>
                        </div>
                    </div>
                `
            },
            hikvision: {
                title: 'Integración con Hikvision',
                content: `
                    <div class="space-y-6">
                        <p class="text-gray-300">Recibe eventos inteligentes de todas tus cámaras Hikvision directamente en COS.</p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">ANPR</h4>
                                <p class="text-gray-400 text-sm">Lectura automática de patentes con registro de ingresos y egresos.</p>
                            </div>
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">Reconocimiento Facial</h4>
                                <p class="text-gray-400 text-sm">Detección e identificación de rostros registrados.</p>
                            </div>
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">Eventos IA</h4>
                                <p class="text-gray-400 text-sm">Cruce de línea, intrusión, merodeo y más eventos inteligentes.</p>
                            </div>
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">Sincronización</h4>
                                <p class="text-gray-400 text-sm">Todos los datos de HikCentral disponibles en COS.</p>
                            </div>
                        </div>
                    </div>
                `
            },
            reportes: {
                title: 'Reportes y Analytics',
                content: `
                    <div class="space-y-6">
                        <p class="text-gray-300">Toma decisiones informadas con dashboards interactivos y reportes profesionales exportables.</p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">KPIs en Tiempo Real</h4>
                                <p class="text-gray-400 text-sm">Métricas actualizadas de todos los módulos del sistema.</p>
                            </div>
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">Gráficos Interactivos</h4>
                                <p class="text-gray-400 text-sm">Visualización de datos con filtros por fecha, zona y tipo.</p>
                            </div>
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">Exportación PDF</h4>
                                <p class="text-gray-400 text-sm">Genera reportes profesionales para presentaciones.</p>
                            </div>
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">Históricos</h4>
                                <p class="text-gray-400 text-sm">Análisis de tendencias y comparativas temporales.</p>
                            </div>
                        </div>
                    </div>
                `
            },
            galeria: {
                title: 'Galería Multimedia',
                content: `
                    <div class="space-y-6">
                        <p class="text-gray-300">Accede a todas las imágenes y videos capturados por drones, cámaras y dispositivos de seguridad.</p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">Capturas de Drones</h4>
                                <p class="text-gray-400 text-sm">Fotos y videos de todas las misiones de vuelo.</p>
                            </div>
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">Evidencia de Eventos</h4>
                                <p class="text-gray-400 text-sm">Material multimedia vinculado a cada incidente.</p>
                            </div>
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">Descarga</h4>
                                <p class="text-gray-400 text-sm">Exporta archivos en alta calidad cuando lo necesites.</p>
                            </div>
                            <div class="bg-[#262c35] p-4 rounded-lg">
                                <h4 class="font-semibold text-white mb-2">Organización</h4>
                                <p class="text-gray-400 text-sm">Filtros por fecha, tipo de dispositivo y evento.</p>
                            </div>
                        </div>
                    </div>
                `
            }
        };
        
        function openTourModal(key) {
            const modal = document.getElementById('tour-modal');
            const title = document.getElementById('modal-title');
            const content = document.getElementById('modal-content');
            
            if (tourData[key]) {
                title.textContent = tourData[key].title;
                content.innerHTML = tourData[key].content;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        }
        
        function closeTourModal() {
            const modal = document.getElementById('tour-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
        
        // Close modal on backdrop click
        document.getElementById('tour-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeTourModal();
            }
        });
        
        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeTourModal();
            }
        });
    </script>
</section>
