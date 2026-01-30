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
            <div class="card-hover bg-[#16262e] rounded-2xl overflow-hidden group" data-aos="fade-up" data-aos-delay="0">
                <div class="h-48 overflow-hidden">
                    <img src="{{ asset('hikvision-cameras.jpeg') }}" 
                         alt="Cámaras Hikvision" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-white mb-3">Productos Hikvision</h3>
                    <p class="text-gray-400 mb-4">
                        Cámaras inteligentes con detección de patentes (ANPR), control de acceso facial, domos 360°, bodycams y más.
                    </p>
                    <ul class="text-gray-500 text-sm space-y-2">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Reconocimiento facial avanzado
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Detección de patentes en tiempo real
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Análisis de video inteligente
                        </li>
                    </ul>
                </div>
            </div>
            
            {{-- Service 2: Flota Vehicular --}}
            <div class="card-hover bg-[#16262e] rounded-2xl overflow-hidden group" data-aos="fade-up" data-aos-delay="100">
                <div class="h-48 overflow-hidden bg-[#1b3761] flex items-center justify-center">
                    <svg class="w-24 h-24 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-white mb-3">Flota Vehicular Equipada</h3>
                    <p class="text-gray-400 mb-4">
                        Camionetas de patrullaje con equipamiento completo: cámaras 360°, DVR, Starlink, balizas y más.
                    </p>
                    <ul class="text-gray-500 text-sm space-y-2">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Conectividad Starlink satelital
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Grabación continua DVR
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            GPS en tiempo real
                        </li>
                    </ul>
                </div>
            </div>
            
            {{-- Service 3: HikCentral --}}
            <div class="card-hover bg-[#16262e] rounded-2xl overflow-hidden group" data-aos="fade-up" data-aos-delay="200">
                <div class="h-48 overflow-hidden">
                    <img src="{{ asset('hikcentral-panel-control.png') }}" 
                         alt="HikCentral Panel" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-white mb-3">Servidor HikCentral</h3>
                    <p class="text-gray-400 mb-4">
                        Plataforma centralizada para video en vivo, geolocalización, eventos inteligentes e historial de accesos.
                    </p>
                    <ul class="text-gray-500 text-sm space-y-2">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Video streaming en vivo
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Eventos inteligentes automáticos
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Integración total con COS
                        </li>
                    </ul>
                </div>
            </div>
            
            {{-- Service 4: Drones DJI --}}
            <div class="card-hover bg-[#16262e] rounded-2xl overflow-hidden group" data-aos="fade-up" data-aos-delay="300">
                <div class="h-48 overflow-hidden">
                    <img src="{{ asset('drone-dock.png') }}" 
                         alt="Drones DJI" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-white mb-3">Drones DJI Autónomos</h3>
                    <p class="text-gray-400 mb-4">
                        Drones de vuelo automático con docks, cámaras zoom/térmicas, luces y bocinas para patrullaje aéreo.
                    </p>
                    <ul class="text-gray-500 text-sm space-y-2">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Misiones programadas y a demanda
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Cámaras térmicas de alta calidad
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Video en vivo durante vuelo
                        </li>
                    </ul>
                </div>
            </div>
            
            {{-- Service 5: Monitoreo 24/7 --}}
            <div class="card-hover bg-[#16262e] rounded-2xl overflow-hidden group" data-aos="fade-up" data-aos-delay="400">
                <div class="h-48 overflow-hidden bg-gradient-to-br from-[#1b3761] to-[#203c58] flex items-center justify-center">
                    <div class="text-center">
                        <div class="text-6xl font-bold text-white/90">24/7</div>
                        <div class="text-white/60 mt-2">Monitoreo Continuo</div>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-white mb-3">Monitoreo 24/7</h3>
                    <p class="text-gray-400 mb-4">
                        Vigilancia en tiempo real las 24 horas con soporte de emergencias y respuesta inmediata ante incidentes.
                    </p>
                    <ul class="text-gray-500 text-sm space-y-2">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Centro de monitoreo dedicado
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Soporte técnico de emergencias
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Alertas y notificaciones instantáneas
                        </li>
                    </ul>
                </div>
            </div>
            
            {{-- Service 6: Desarrollo a Medida --}}
            <div class="card-hover bg-[#16262e] rounded-2xl overflow-hidden group" data-aos="fade-up" data-aos-delay="500">
                <div class="h-48 overflow-hidden bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center">
                    <svg class="w-24 h-24 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-white mb-3">Desarrollo a Medida</h3>
                    <p class="text-gray-400 mb-4">
                        Soluciones de software personalizadas para empresas con necesidades específicas de seguridad.
                    </p>
                    <ul class="text-gray-500 text-sm space-y-2">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Análisis de requerimientos
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Integración con sistemas existentes
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Soporte y mantenimiento continuo
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
