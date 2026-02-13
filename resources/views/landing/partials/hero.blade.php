{{-- Hero Section --}}
<section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden">
    {{-- Background Video/Image --}}
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('patrolling_drones.jpg') }}" 
             alt="Drone patrullando" 
             class="w-full h-full object-cover">
        <div class="hero-overlay absolute inset-0"></div>
    </div>
    
    {{-- Animated particles/grid background --}}
    <div class="absolute inset-0 z-0 opacity-30">
        <div class="absolute inset-0" style="background-image: radial-gradient(rgba(59, 130, 246, 0.3) 1px, transparent 1px); background-size: 50px 50px;"></div>
    </div>
    
    {{-- Content --}}
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 text-center">
        {{-- Logo animado --}}
        <div class="mb-0 flex justify-center" data-aos="fade-down">
            <img src="{{ asset('cyh-white.png') }}" alt="CYH Logo" class="h-48 sm:h-64 lg:h-80 xl:h-96 w-auto floating">
        </div>
        
        {{-- Main Title --}}
        <h1 class="hero-title text-4xl sm:text-5xl lg:text-7xl font-extrabold text-white mb-6 leading-tight">
            SOLUCIONES DE TECNOLOGIA<br>
            <span class="text-gradient">Y DE SEGURIDAD</span>
        </h1>
        
        {{-- Subtitle --}}
        <p class="hero-subtitle text-xl sm:text-2xl text-gray-300 max-w-3xl mx-auto mb-10 leading-relaxed">
            Tenemos lo que necesitas para mejorar la seguridad de tu empresa con tecnología. 
            <span class="text-blue-400 font-semibold">Drones</span>, 
            <span class="text-blue-400 font-semibold">cámaras</span>, 
            <span class="text-blue-400 font-semibold">patrullas</span> y 
            <span class="text-blue-400 font-semibold">eventos</span> 
            en tiempo real.
        </p>
        
        {{-- CTA Buttons --}}
        <div class="hero-cta flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
            <a href="#contacto" 
               class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-bold text-lg transition-all hover:shadow-xl hover:shadow-blue-600/30 pulse-glow">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Solicitar Demo Gratuita
                </span>
            </a>
            <a href="#cos-software" 
               class="w-full sm:w-auto bg-white/10 hover:bg-white/20 text-white px-8 py-4 rounded-xl font-bold text-lg transition-all border border-white/20 hover:border-white/40 backdrop-blur-sm">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Conocer COS
                </span>
            </a>
        </div>
        
        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto" data-aos="fade-up" data-aos-delay="200">
            <div class="glass rounded-xl p-6 text-center">
                <div class="text-3xl sm:text-4xl font-bold text-blue-400 mb-2">24/7</div>
                <div class="text-gray-400 text-sm">Monitoreo Continuo</div>
            </div>
            <div class="glass rounded-xl p-6 text-center">
                <div class="text-3xl sm:text-4xl font-bold text-blue-400 mb-2">100%</div>
                <div class="text-gray-400 text-sm">Integración Total</div>
            </div>
            <div class="glass rounded-xl p-6 text-center">
                <div class="text-3xl sm:text-4xl font-bold text-blue-400 mb-2">+50</div>
                <div class="text-gray-400 text-sm">Clientes Activos</div>
            </div>
            <div class="glass rounded-xl p-6 text-center">
                <div class="text-3xl sm:text-4xl font-bold text-blue-400 mb-2">∞</div>
                <div class="text-gray-400 text-sm">Escalabilidad</div>
            </div>
        </div>
    </div>
    
    {{-- Scroll Indicator --}}
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 scroll-indicator">
        <a href="#servicios" class="flex flex-col items-center text-gray-400 hover:text-white transition-colors">
            <span class="text-sm mb-2">Descubre más</span>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </a>
    </div>
</section>
