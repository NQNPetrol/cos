{{-- Footer - Clean & Minimal --}}
<footer class="bg-neutral-900 text-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        {{-- Main Footer --}}
        <div class="py-12 grid grid-cols-1 md:grid-cols-4 gap-8">
            {{-- Brand --}}
            <div class="md:col-span-2">
                <div class="flex items-center mb-4">
                    <video 
                        autoplay 
                        loop 
                        muted 
                        playsinline
                        class="h-8 w-auto filter brightness-0 invert logo-video"
                    >
                        <source src="{{ asset('CYH.mp4') }}" type="video/mp4">
                    </video>
                </div>
                <p class="text-neutral-400 text-sm leading-relaxed max-w-md">
                    COS es una plataforma de gestión operacional desarrollada por CYHSUR SA. 
                    Diseñada para centralizar y simplificar las operaciones de tu empresa.
                </p>
            </div>
            
            {{-- Links --}}
            <div>
                <h4 class="font-medium text-white mb-4">Navegación</h4>
                <ul class="space-y-2">
                    <li>
                        <a href="#overview" class="text-sm text-neutral-400 hover:text-white transition-colors">
                            Qué es COS
                        </a>
                    </li>
                    <li>
                        <a href="#features" class="text-sm text-neutral-400 hover:text-white transition-colors">
                            Funcionalidades
                        </a>
                    </li>
                    <li>
                        <a href="#modules" class="text-sm text-neutral-400 hover:text-white transition-colors">
                            Módulos
                        </a>
                    </li>
                    <li>
                        <a href="#contact" class="text-sm text-neutral-400 hover:text-white transition-colors">
                            Contacto
                        </a>
                    </li>
                </ul>
            </div>
            
            {{-- Contact --}}
            <div>
                <h4 class="font-medium text-white mb-4">Contacto</h4>
                <ul class="space-y-2">
                    <li>
                        <a href="mailto:info@cyhsur.com" class="text-sm text-neutral-400 hover:text-white transition-colors">
                            info@cyhsur.com
                        </a>
                    </li>
                    <li>
                        <a href="tel:+5491112345678" class="text-sm text-neutral-400 hover:text-white transition-colors">
                            +54 9 11 1234-5678
                        </a>
                    </li>
                    <li class="text-sm text-neutral-400">
                        Buenos Aires, Argentina
                    </li>
                </ul>
            </div>
        </div>
        
        {{-- Bottom Bar --}}
        <div class="py-6 border-t border-neutral-800">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-neutral-500">
                    &copy; {{ date('Y') }} CYHSUR SA. Todos los derechos reservados.
                </p>
                <div class="flex items-center gap-6">
                    <a href="#" class="text-sm text-neutral-500 hover:text-neutral-300 transition-colors">
                        Términos
                    </a>
                    <a href="#" class="text-sm text-neutral-500 hover:text-neutral-300 transition-colors">
                        Privacidad
                    </a>
                    <a href="{{ route('login') }}" class="text-sm text-neutral-500 hover:text-neutral-300 transition-colors">
                        Acceso
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
