{{-- Navigation - Clean & Minimal --}}
<nav id="main-nav" class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm transition-all duration-300">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center py-3">
            {{-- Logo --}}
            <a href="{{ route('landing.alt') }}" class="flex items-center mr-6">
                <video 
                    autoplay 
                    loop 
                    muted 
                    playsinline
                    class="h-14 w-auto logo-video"
                    style="min-height: 56px;"
                >
                    <source src="{{ asset('cyh-blue.png') }}" type="image/png">
                </video>
            </a>
            
            {{-- Desktop Navigation --}}
            <div class="hidden md:flex items-center space-x-6">
                <a href="#overview" class="text-sm text-neutral-600 hover:text-neutral-900 transition-colors">
                    Qué es COS
                </a>
                <a href="#features" class="text-sm text-neutral-600 hover:text-neutral-900 transition-colors">
                    Funcionalidades
                </a>
                <a href="#modules" class="text-sm text-neutral-600 hover:text-neutral-900 transition-colors">
                    Módulos
                </a>
                <a href="#contact" class="text-sm text-neutral-600 hover:text-neutral-900 transition-colors">
                    Contacto
                </a>
            </div>
            
            {{-- Spacer --}}
            <div class="flex-grow"></div>
            
            {{-- CTA Buttons --}}
            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ route('login') }}" class="text-sm text-neutral-600 hover:text-neutral-900 transition-colors">
                    Acceder
                </a>
                <a href="#contact" class="text-sm px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    Solicitar información
                </a>
            </div>
            
            {{-- Mobile Menu Button --}}
            <button id="mobile-menu-btn" class="md:hidden p-2 text-neutral-600 hover:text-neutral-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
        
        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-neutral-100">
            <div class="pt-4 space-y-3">
                <a href="#overview" class="block text-sm text-neutral-600 hover:text-neutral-900 py-2">
                    Qué es COS
                </a>
                <a href="#features" class="block text-sm text-neutral-600 hover:text-neutral-900 py-2">
                    Funcionalidades
                </a>
                <a href="#modules" class="block text-sm text-neutral-600 hover:text-neutral-900 py-2">
                    Módulos
                </a>
                <a href="#contact" class="block text-sm text-neutral-600 hover:text-neutral-900 py-2">
                    Contacto
                </a>
                <div class="pt-4 border-t border-neutral-100 space-y-3">
                    <a href="{{ route('login') }}" class="block text-sm text-neutral-600 hover:text-neutral-900 py-2">
                        Acceder
                    </a>
                    <a href="#contact" class="block text-sm px-4 py-2 bg-primary-600 text-white rounded-lg text-center">
                        Solicitar información
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
