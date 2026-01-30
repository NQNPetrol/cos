{{-- COS Software Features Section --}}
<section id="cos-software" class="py-24 bg-gradient-to-b from-[#0e1321] to-[#16262e] relative overflow-hidden">
    {{-- Background Pattern --}}
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%233b82f6\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Section Header --}}
        <div class="text-center mb-16" data-aos="fade-up">
            <div class="inline-flex items-center gap-2 bg-blue-600/20 text-blue-400 px-4 py-2 rounded-full text-sm font-medium mb-6">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Software COS
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-6">
                Todo tu Centro de Operaciones<br>
                <span class="text-gradient">en una Sola Plataforma</span>
            </h2>
            <p class="text-gray-400 text-lg max-w-3xl mx-auto">
                COS integra todos tus sistemas de seguridad, permitiéndote gestionar eventos, supervisar personal, 
                controlar patrullas, operar drones y analizar datos desde un solo lugar.
            </p>
        </div>
        
        {{-- Features Container --}}
        <div class="lg:grid lg:grid-cols-12 lg:gap-8" data-aos="fade-up" data-aos-delay="100">
            {{-- Tabs Navigation --}}
            <div class="lg:col-span-4 mb-8 lg:mb-0">
                <div class="space-y-3">
                    <button class="feature-tab w-full text-left px-5 py-4 rounded-xl transition-all duration-300 bg-blue-600 tab-active flex items-center gap-4" data-target="feature-eventos">
                        <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-white">Gestión de Eventos</div>
                            <div class="text-sm text-white/70">Registro y seguimiento</div>
                        </div>
                    </button>
                    
                    <button class="feature-tab w-full text-left px-5 py-4 rounded-xl transition-all duration-300 bg-[#262c35] hover:bg-[#2a3140] flex items-center gap-4" data-target="feature-patrullas">
                        <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-200">Flota y Patrullas</div>
                            <div class="text-sm text-gray-500">Tracking en tiempo real</div>
                        </div>
                    </button>
                    
                    <button class="feature-tab w-full text-left px-5 py-4 rounded-xl transition-all duration-300 bg-[#262c35] hover:bg-[#2a3140] flex items-center gap-4" data-target="feature-hikvision">
                        <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-200">Integración Hikvision</div>
                            <div class="text-sm text-gray-500">ANPR y reconocimiento</div>
                        </div>
                    </button>
                    
                    <button class="feature-tab w-full text-left px-5 py-4 rounded-xl transition-all duration-300 bg-[#262c35] hover:bg-[#2a3140] flex items-center gap-4" data-target="feature-drones">
                        <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-200">Operaciones con Drones</div>
                            <div class="text-sm text-gray-500">Misiones y flight logs</div>
                        </div>
                    </button>
                    
                    <button class="feature-tab w-full text-left px-5 py-4 rounded-xl transition-all duration-300 bg-[#262c35] hover:bg-[#2a3140] flex items-center gap-4" data-target="feature-dashboards">
                        <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-200">Dashboards Analíticos</div>
                            <div class="text-sm text-gray-500">Métricas y reportes</div>
                        </div>
                    </button>
                </div>
            </div>
            
            {{-- Feature Content --}}
            <div class="lg:col-span-8">
                {{-- Feature: Eventos --}}
                <div id="feature-eventos" class="feature-content block">
                    <div class="bg-[#1a1d1f] rounded-2xl overflow-hidden border border-white/5">
                        <div class="aspect-video bg-[#262c35] overflow-hidden">
                            <video 
                                autoplay 
                                loop 
                                muted 
                                playsinline
                                class="w-full h-full object-cover"
                            >
                                <source src="{{ asset('eventos.mp4') }}" type="video/mp4">
                            </video>
                        </div>
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-white mb-4">Gestión de Eventos de Seguridad</h3>
                            <p class="text-gray-400 mb-6">
                                Registra, clasifica y da seguimiento a todo tipo de eventos: robos, vandalismos, intrusiones, 
                                emergencias y más. Cada evento queda documentado con evidencia multimedia y geolocalización.
                            </p>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-sm">Categorización</div>
                                        <div class="text-gray-500 text-xs">Múltiples tipos de eventos</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-sm">Evidencia</div>
                                        <div class="text-gray-500 text-xs">Fotos, videos y documentos</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-sm">Geolocalización</div>
                                        <div class="text-gray-500 text-xs">Ubicación exacta del evento</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-sm">Reportes PDF</div>
                                        <div class="text-gray-500 text-xs">Exportación profesional</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Feature: Patrullas --}}
                <div id="feature-patrullas" class="feature-content hidden">
                    <div class="bg-[#1a1d1f] rounded-2xl overflow-hidden border border-white/5">
                        <div class="aspect-video bg-[#262c35] flex items-center justify-center">
                            <svg class="w-32 h-32 text-white/10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                            </svg>
                        </div>
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-white mb-4">Flota y Patrullas</h3>
                            <p class="text-gray-400 mb-6">
                                Visualiza la ubicación de todas tus patrullas en tiempo real sobre un mapa interactivo. 
                                Controla objetivos, recorridos y estado de cada vehículo.
                            </p>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-sm">Mapa en Tiempo Real</div>
                                        <div class="text-gray-500 text-xs">GPS tracking continuo</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-sm">Rutas y Recorridos</div>
                                        <div class="text-gray-500 text-xs">Historial de movimientos</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-sm">Estado de Vehículos</div>
                                        <div class="text-gray-500 text-xs">Documentación y estado</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-sm">Objetivos</div>
                                        <div class="text-gray-500 text-xs">Asignación de servicios</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Feature: Hikvision --}}
                <div id="feature-hikvision" class="feature-content hidden">
                    <div class="bg-[#1a1d1f] rounded-2xl overflow-hidden border border-white/5">
                        <div class="aspect-video bg-[#262c35] overflow-hidden">
                            <video 
                                autoplay 
                                loop 
                                muted 
                                playsinline
                                class="w-full h-full object-cover"
                            >
                                <source src="{{ asset('anpr-1.mp4') }}" type="video/mp4">
                            </video>
                        </div>
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-white mb-4">Integración Hikvision</h3>
                            <p class="text-gray-400 mb-6">
                                Recibe eventos inteligentes de cámaras Hikvision: detección de patentes, reconocimiento facial, 
                                cruce de línea y más. Todo sincronizado con tu centro de operaciones.
                            </p>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-sm">ANPR</div>
                                        <div class="text-gray-500 text-xs">Lectura automática de patentes</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-sm">Reconocimiento Facial</div>
                                        <div class="text-gray-500 text-xs">Detección de rostros</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-sm">Eventos Inteligentes</div>
                                        <div class="text-gray-500 text-xs">Análisis de video IA</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-sm">Sincronización</div>
                                        <div class="text-gray-500 text-xs">Datos en tiempo real</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Feature: Drones --}}
                <div id="feature-drones" class="feature-content hidden">
                    <div class="bg-[#1a1d1f] rounded-2xl overflow-hidden border border-white/5">
                        <div class="aspect-video bg-[#262c35] overflow-hidden">
                            <video 
                                autoplay 
                                loop 
                                muted 
                                playsinline
                                class="w-full h-full object-cover"
                            >
                                <source src="{{ asset('streaming.mp4') }}" type="video/mp4">
                            </video>
                        </div>
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-white mb-4">Operaciones con Drones</h3>
                            <p class="text-gray-400 mb-6">
                                Lanza misiones de drones a demanda, visualiza el video en vivo durante el vuelo, 
                                gestiona flight logs y configura zonas de no vuelo desde COS.
                            </p>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-sm">Misiones a Demanda</div>
                                        <div class="text-gray-500 text-xs">Lanzamiento instantáneo</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-sm">Video en Vivo</div>
                                        <div class="text-gray-500 text-xs">Streaming HD durante vuelo</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-sm">Flight Logs</div>
                                        <div class="text-gray-500 text-xs">Historial completo</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-sm">No-Fly Zones</div>
                                        <div class="text-gray-500 text-xs">Configuración de restricciones</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Feature: Dashboards --}}
                <div id="feature-dashboards" class="feature-content hidden">
                    <div class="bg-[#1a1d1f] rounded-2xl overflow-hidden border border-white/5">
                        <div class="aspect-video bg-[#262c35] overflow-hidden">
                            <video 
                                autoplay 
                                loop 
                                muted 
                                playsinline
                                class="w-full h-full object-cover"
                            >
                                <source src="{{ asset('dashboard-1.mp4') }}" type="video/mp4">
                            </video>
                        </div>
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-white mb-4">Dashboards Analíticos</h3>
                            <p class="text-gray-400 mb-6">
                                Visualiza métricas en tiempo real e históricas. Toma decisiones informadas con 
                                dashboards personalizables y reportes exportables.
                            </p>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-sm">KPIs en Tiempo Real</div>
                                        <div class="text-gray-500 text-xs">Métricas actualizadas</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-sm">Gráficos Interactivos</div>
                                        <div class="text-gray-500 text-xs">Visualización de datos</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-sm">Reportes PDF</div>
                                        <div class="text-gray-500 text-xs">Exportación profesional</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-sm">Filtros Avanzados</div>
                                        <div class="text-gray-500 text-xs">Por fecha, zona, tipo</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
