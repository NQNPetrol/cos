{{-- Navigation Bar --}}
<nav id="landing-nav" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            {{-- Logo --}}
            <a href="{{ route('landing') }}" class="flex items-center space-x-3">
                <img src="{{ asset('cyh-white.png') }}" alt="CYHSUR" class="h-10 w-auto">
                <span class="text-xl font-bold text-white hidden sm:block">CYHSUR</span>
            </a>
            
            {{-- Desktop Navigation --}}
            <div class="hidden md:flex items-center space-x-8">
                <a href="#servicios" class="text-gray-300 hover:text-white transition-colors font-medium">Servicios</a>
                <a href="#cos-software" class="text-gray-300 hover:text-white transition-colors font-medium">COS Software</a>
                <a href="#tour" class="text-gray-300 hover:text-white transition-colors font-medium">Tour del Producto</a>
                <a href="#contacto" class="text-gray-300 hover:text-white transition-colors font-medium">Contacto</a>
            </div>
            
            {{-- CTA Buttons --}}
            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors font-medium">
                    Iniciar Sesión
                </a>
                <a href="#contacto" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-semibold transition-all hover:shadow-lg hover:shadow-blue-600/30">
                    Solicitar Demo
                </a>
            </div>
            
            {{-- Mobile Menu Button --}}
            <button id="mobile-menu-btn" class="md:hidden text-white p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
        
        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden pb-6">
            <div class="flex flex-col space-y-4 pt-4 border-t border-white/10">
                <a href="#servicios" class="text-gray-300 hover:text-white transition-colors font-medium py-2">Servicios</a>
                <a href="#cos-software" class="text-gray-300 hover:text-white transition-colors font-medium py-2">COS Software</a>
                <a href="#tour" class="text-gray-300 hover:text-white transition-colors font-medium py-2">Tour del Producto</a>
                <a href="#contacto" class="text-gray-300 hover:text-white transition-colors font-medium py-2">Contacto</a>
                <hr class="border-white/10">
                <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors font-medium py-2">
                    Iniciar Sesión
                </a>
                <a href="#contacto" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg font-semibold text-center transition-all">
                    Solicitar Demo
                </a>
            </div>
        </div>
    </div>
</nav>
