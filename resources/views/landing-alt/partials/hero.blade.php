{{-- Hero Section - Clean & Informative --}}
<section class="pt-24 pb-16 bg-gradient-to-b from-neutral-50 to-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            {{-- Text Content --}}
            <div data-aos="fade-right">
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-primary-50 text-primary-700 text-sm mb-6">
                    <span class="w-2 h-2 bg-primary-500 rounded-full mr-2"></span>
                    Plataforma de gestión integral
                </div>
                
                <h1 class="text-4xl lg:text-5xl font-semibold text-neutral-900 leading-tight mb-6">
                    Centro de Operaciones 
                    <span class="text-primary-600">Simplificado</span>
                </h1>
                
                <p class="text-lg text-neutral-600 leading-relaxed mb-8">
                    COS centraliza la gestión de tus operaciones en una única plataforma. 
                    Diseñado para empresas que buscan eficiencia, visibilidad y control 
                    sobre sus procesos operativos.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#overview" class="inline-flex items-center justify-center px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm font-medium">
                        Conocer más
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </a>
                    <a href="#contact" class="inline-flex items-center justify-center px-6 py-3 border border-neutral-300 text-neutral-700 rounded-lg hover:border-neutral-400 hover:bg-neutral-50 transition-colors text-sm font-medium">
                        Solicitar demo
                    </a>
                </div>
            </div>
            
            {{-- Logo/Video Display --}}
            <div class="flex justify-center" data-aos="fade-left">
                <div class="relative">
                    {{-- Decorative background --}}
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-100 to-neutral-100 rounded-3xl transform rotate-3"></div>
                    
                    {{-- Video container --}}
                    <div class="relative bg-white rounded-2xl p-8 shadow-sm border border-neutral-100">
                        <video 
                            autoplay 
                            loop 
                            muted 
                            playsinline
                            class="w-64 h-auto mx-auto logo-video"
                        >
                            <source src="{{ asset('CYH.mp4') }}" type="video/mp4">
                        </video>
                        <p class="text-center text-sm text-neutral-500 mt-4">
                            Centro de Operaciones de Seguridad
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Simple stats --}}
        <div class="mt-16 pt-8 border-t border-neutral-100">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8" data-aos="fade-up">
                <div class="text-center">
                    <div class="text-2xl font-semibold text-neutral-900">100%</div>
                    <div class="text-sm text-neutral-500 mt-1">Web-based</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-semibold text-neutral-900">24/7</div>
                    <div class="text-sm text-neutral-500 mt-1">Disponibilidad</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-semibold text-neutral-900">Multi</div>
                    <div class="text-sm text-neutral-500 mt-1">Usuario</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-semibold text-neutral-900">Seguro</div>
                    <div class="text-sm text-neutral-500 mt-1">Roles y permisos</div>
                </div>
            </div>
        </div>
    </div>
</section>
