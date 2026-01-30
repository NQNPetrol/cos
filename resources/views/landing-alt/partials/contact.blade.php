{{-- Contact Section - Clean Form --}}
<section id="contact" class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid lg:grid-cols-2 gap-12">
            {{-- Contact Info --}}
            <div data-aos="fade-right">
                <h2 class="text-3xl font-semibold text-neutral-900 mb-4">
                    Solicita más información
                </h2>
                <p class="text-neutral-600 leading-relaxed mb-8">
                    Contáctanos para conocer cómo COS puede adaptarse a las 
                    necesidades de tu empresa. Te responderemos a la brevedad.
                </p>
                
                {{-- Contact Details --}}
                <div class="space-y-6 mb-8">
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-neutral-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <svg class="w-5 h-5 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-neutral-500">Email</div>
                            <a href="mailto:info@cyhsur.com" class="text-neutral-900 hover:text-primary-600 transition-colors">
                                info@cyhsur.com
                            </a>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-neutral-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <svg class="w-5 h-5 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-neutral-500">Teléfono</div>
                            <a href="tel:+5491112345678" class="text-neutral-900 hover:text-primary-600 transition-colors">
                                +54 9 11 1234-5678
                            </a>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-neutral-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <svg class="w-5 h-5 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-neutral-500">Ubicación</div>
                            <span class="text-neutral-900">Buenos Aires, Argentina</span>
                        </div>
                    </div>
                </div>
                
                {{-- Company info --}}
                <div class="p-4 bg-neutral-50 rounded-lg border border-neutral-100">
                    <div class="flex items-center">
                        <video 
                            autoplay 
                            loop 
                            muted 
                            playsinline
                            class="h-8 w-auto mr-3 logo-video"
                        >
                            <source src="{{ asset('CYH.mp4') }}" type="video/mp4">
                        </video>
                        <div>
                            <div class="font-medium text-neutral-900 text-sm">CYHSUR SA</div>
                            <div class="text-xs text-neutral-500">Soluciones tecnológicas</div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Contact Form --}}
            <div data-aos="fade-left">
                <div class="bg-neutral-50 rounded-xl p-6 lg:p-8 border border-neutral-100">
                    <form id="contact-form" class="space-y-5">
                        @csrf
                        
                        {{-- Name --}}
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-neutral-700 mb-1.5">
                                Nombre completo <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="nombre" 
                                name="nombre" 
                                required
                                class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-lg text-neutral-900 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                                placeholder="Tu nombre"
                            >
                        </div>
                        
                        {{-- Email & Phone --}}
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label for="email" class="block text-sm font-medium text-neutral-700 mb-1.5">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    required
                                    class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-lg text-neutral-900 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                                    placeholder="tu@email.com"
                                >
                            </div>
                            <div>
                                <label for="telefono" class="block text-sm font-medium text-neutral-700 mb-1.5">
                                    Teléfono
                                </label>
                                <input 
                                    type="tel" 
                                    id="telefono" 
                                    name="telefono"
                                    class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-lg text-neutral-900 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                                    placeholder="+54 9 11..."
                                >
                            </div>
                        </div>
                        
                        {{-- Company & Position --}}
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label for="empresa" class="block text-sm font-medium text-neutral-700 mb-1.5">
                                    Empresa
                                </label>
                                <input 
                                    type="text" 
                                    id="empresa" 
                                    name="empresa"
                                    class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-lg text-neutral-900 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                                    placeholder="Nombre de tu empresa"
                                >
                            </div>
                            <div>
                                <label for="cargo" class="block text-sm font-medium text-neutral-700 mb-1.5">
                                    Cargo
                                </label>
                                <input 
                                    type="text" 
                                    id="cargo" 
                                    name="cargo"
                                    class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-lg text-neutral-900 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                                    placeholder="Tu cargo"
                                >
                            </div>
                        </div>
                        
                        {{-- Message --}}
                        <div>
                            <label for="mensaje" class="block text-sm font-medium text-neutral-700 mb-1.5">
                                Mensaje <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="mensaje" 
                                name="mensaje" 
                                rows="4" 
                                required
                                class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-lg text-neutral-900 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors resize-none"
                                placeholder="¿En qué podemos ayudarte?"
                            ></textarea>
                        </div>
                        
                        {{-- Terms --}}
                        <div class="flex items-start">
                            <input 
                                type="checkbox" 
                                id="acepta_terminos" 
                                name="acepta_terminos" 
                                required
                                class="mt-0.5 w-4 h-4 text-primary-600 border-neutral-300 rounded focus:ring-primary-500"
                            >
                            <label for="acepta_terminos" class="ml-2 text-sm text-neutral-600">
                                Acepto los <a href="#" class="text-primary-600 hover:underline">términos y condiciones</a> 
                                y la <a href="#" class="text-primary-600 hover:underline">política de privacidad</a>.
                                <span class="text-red-500">*</span>
                            </label>
                        </div>
                        
                        {{-- Submit --}}
                        <button 
                            type="submit" 
                            class="w-full px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm font-medium flex items-center justify-center"
                        >
                            Enviar mensaje
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                        
                        {{-- Form Message --}}
                        <div id="form-message" class="hidden"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
