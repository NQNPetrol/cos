{{-- Footer --}}
<footer class="bg-[#0a0d14] border-t border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Main Footer --}}
        <div class="py-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            {{-- Brand Column --}}
            <div class="lg:col-span-1">
                <a href="{{ route('landing') }}" class="inline-block mb-2">
                    <!-- Logo CYH con CSS - Doble borde y efecto 3D -->
                    <div class="relative p-1" style="filter: drop-shadow(2px 2px 1px rgba(100, 100, 120, 0.5));">
                        <!-- Borde exterior -->
                        <div class="border-2 border-white/90 p-0.5">
                            <!-- Borde interior -->
                            <div class="border-2 border-white/90 px-6 py-2">
                                <!-- Texto CYH -->
                                <span class="text-white font-bold text-3xl tracking-[0.25em]" style="font-family: 'Arial Black', 'Helvetica Bold', sans-serif; text-shadow: 2px 2px 1px rgba(100, 100, 120, 0.4);">
                                    CYH
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                <p class="text-gray-400 text-sm mb-6">
                    Soluciones integrales de seguridad y tecnología. 
                    Centro de Operaciones de Seguridad (COS) que integra todos tus sistemas en una sola plataforma.
                </p>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-lg bg-white/5 hover:bg-blue-600 flex items-center justify-center text-gray-400 hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-lg bg-white/5 hover:bg-blue-600 flex items-center justify-center text-gray-400 hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-lg bg-white/5 hover:bg-blue-600 flex items-center justify-center text-gray-400 hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            {{-- Products Column --}}
            <div>
                <h3 class="text-white font-semibold mb-6">Productos</h3>
                <ul class="space-y-4">
                    <li><a href="#servicios" class="text-gray-400 hover:text-blue-400 transition-colors text-sm">Cámaras Hikvision</a></li>
                    <li><a href="#servicios" class="text-gray-400 hover:text-blue-400 transition-colors text-sm">Flota Vehicular</a></li>
                    <li><a href="#servicios" class="text-gray-400 hover:text-blue-400 transition-colors text-sm">HikCentral Server</a></li>
                    <li><a href="#servicios" class="text-gray-400 hover:text-blue-400 transition-colors text-sm">Drones DJI</a></li>
                    <li><a href="#servicios" class="text-gray-400 hover:text-blue-400 transition-colors text-sm">Monitoreo 24/7</a></li>
                </ul>
            </div>
            
            {{-- Software Column --}}
            <div>
                <h3 class="text-white font-semibold mb-6">Software COS</h3>
                <ul class="space-y-4">
                    <li><a href="#cos-software" class="text-gray-400 hover:text-blue-400 transition-colors text-sm">Gestión de Eventos</a></li>
                    <li><a href="#cos-software" class="text-gray-400 hover:text-blue-400 transition-colors text-sm">Control de Patrullas</a></li>
                    <li><a href="#cos-software" class="text-gray-400 hover:text-blue-400 transition-colors text-sm">Operaciones con Drones</a></li>
                    <li><a href="#cos-software" class="text-gray-400 hover:text-blue-400 transition-colors text-sm">Integración Hikvision</a></li>
                    <li><a href="#cos-software" class="text-gray-400 hover:text-blue-400 transition-colors text-sm">Dashboards y Reportes</a></li>
                </ul>
            </div>
            
            {{-- Contact Column --}}
            <div>
                <h3 class="text-white font-semibold mb-6">Contacto</h3>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <a href="mailto:ventas@cyhsur.com" class="text-gray-400 hover:text-blue-400 transition-colors text-sm">
                            ventas@cyhsur.com
                        </a>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <a href="tel:+5491112345678" class="text-gray-400 hover:text-blue-400 transition-colors text-sm">
                            +54 9 11 1234-5678
                        </a>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-gray-400 text-sm">
                            Buenos Aires, Argentina
                        </span>
                    </li>
                </ul>
                
                {{-- CTA --}}
                <a href="#contacto" class="inline-flex items-center gap-2 mt-6 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Solicitar Demo
                </a>
            </div>
        </div>
        
        {{-- Bottom Footer --}}
        <div class="py-6 border-t border-white/5">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-gray-500 text-sm">
                    © {{ date('Y') }} CYHSUR SA. Todos los derechos reservados.
                </p>
                <div class="flex items-center gap-6">
                    <a href="#" class="text-gray-500 hover:text-gray-300 text-sm transition-colors">Términos y Condiciones</a>
                    <a href="#" class="text-gray-500 hover:text-gray-300 text-sm transition-colors">Política de Privacidad</a>
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-300 text-sm transition-colors">Acceso Clientes</a>
                </div>
            </div>
        </div>
    </div>
</footer>
