{{-- Trusted Companies Section --}}
<section id="empresas" class="py-20 bg-[#0e1321] relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-blue-500 font-semibold text-sm uppercase tracking-wider">Empresas que Confían en Nosotros</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-white mt-4">
                Líderes en Seguridad Eligen CYHSUR
            </h2>
        </div>
        
        {{-- Companies Carousel --}}
        @php
            // Obtener todos los archivos de imagen del directorio brands
            $brandsPath = public_path('brands');
            $brandLogos = [];
            
            if (is_dir($brandsPath)) {
                $files = scandir($brandsPath);
                foreach ($files as $file) {
                    // Filtrar solo archivos de imagen
                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    if (in_array($extension, ['png', 'jpg', 'jpeg', 'gif', 'svg', 'webp'])) {
                        $brandLogos[] = $file;
                    }
                }
            }
            
            // Repetir logos para llenar el carrusel (mínimo 8 slides para un carrusel fluido)
            $minSlides = 8;
            $repeatedLogos = $brandLogos;
            while (count($repeatedLogos) < $minSlides && count($brandLogos) > 0) {
                $repeatedLogos = array_merge($repeatedLogos, $brandLogos);
            }
        @endphp
        
        <div class="companies-swiper overflow-hidden" data-aos="fade-up" data-aos-delay="100">
            <div class="swiper-wrapper items-center">
                {{-- Company Logos - Dinámico desde /public/brands --}}
                @forelse($repeatedLogos as $logo)
                    @php
                        // Logos que necesitan menos filtro (más visibles)
                        $logosSinFiltro = ['inca'];
                        $nombreLogo = strtolower(pathinfo($logo, PATHINFO_FILENAME));
                        $necesitaMenosFiltro = false;
                        foreach ($logosSinFiltro as $logoEspecial) {
                            if (str_contains($nombreLogo, $logoEspecial)) {
                                $necesitaMenosFiltro = true;
                                break;
                            }
                        }
                    @endphp
                    <div class="swiper-slide !w-auto">
                        <div class="px-10 py-6">
                            <div class="h-28 w-52 flex items-center justify-center">
                                @if($necesitaMenosFiltro)
                                    <img src="{{ asset('brands/' . $logo) }}" 
                                         alt="{{ pathinfo($logo, PATHINFO_FILENAME) }}" 
                                         class="max-h-28 max-w-full object-contain filter grayscale brightness-150 opacity-70 hover:opacity-100 transition-opacity">
                                @else
                                    <img src="{{ asset('brands/' . $logo) }}" 
                                         alt="{{ pathinfo($logo, PATHINFO_FILENAME) }}" 
                                         class="max-h-28 max-w-full object-contain filter brightness-0 invert opacity-60 hover:opacity-100 transition-opacity">
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide !w-auto">
                        <div class="px-10 py-6">
                            <div class="h-28 w-52 flex items-center justify-center text-gray-400 font-bold text-lg">
                                Sin logos
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        
        {{-- Advantages Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-16" data-aos="fade-up" data-aos-delay="200">
            <div class="text-center p-6 bg-white/5 rounded-xl hover:bg-white/10 transition-colors">
                <div class="w-16 h-16 mx-auto mb-4 bg-blue-600/20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" viewBox="0 0 512 512" fill="currentColor" class="text-blue-500">
                        <path class="fill-blue-500" d="M153.527,138.934c-0.29,0-0.581,0.088-0.826,0.258L0.641,242.995C0.238,243.27,0,243.721,0,244.213v27.921 c0,0.484,0.238,0.943,0.641,1.21l152.06,103.811c0.246,0.17,0.536,0.258,0.826,0.258c0.238,0,0.468-0.064,0.686-0.169 c0.484-0.258,0.782-0.758,0.782-1.306v-44.478c0-0.476-0.238-0.936-0.641-1.202L48.769,258.166l105.585-72.068 c0.403-0.282,0.641-0.734,0.641-1.226V140.41c0-0.548-0.298-1.049-0.782-1.299C153.995,138.991,153.765,138.934,153.527,138.934z"></path>
                        <path class="fill-blue-500" d="M511.358,242.995l-152.06-103.803c-0.246-0.169-0.536-0.258-0.827-0.258c-0.238,0-0.467,0.056-0.685,0.177 c-0.484,0.25-0.782,0.751-0.782,1.299v44.478c0,0.484,0.238,0.936,0.641,1.21l105.586,72.068l-105.586,72.092 c-0.403,0.266-0.641,0.725-0.641,1.217v44.462c0,0.548,0.298,1.049,0.782,1.306c0.218,0.105,0.448,0.169,0.685,0.169 c0.291,0,0.581-0.088,0.827-0.258l152.06-103.811c0.404-0.267,0.642-0.726,0.642-1.21v-27.921 C512,243.721,511.762,243.27,511.358,242.995z"></path>
                        <path class="fill-blue-500" d="M325.507,114.594h-42.502c-0.629,0-1.186,0.395-1.387,0.984l-96.517,279.885 c-0.153,0.443-0.08,0.943,0.194,1.322c0.278,0.387,0.722,0.621,1.198,0.621h42.506c0.625,0,1.182-0.395,1.387-0.992l96.513-279.868 c0.153-0.452,0.081-0.952-0.193-1.339C326.427,114.828,325.982,114.594,325.507,114.594z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Integración Total</h3>
                <p class="text-gray-400 text-sm">Un solo software para todos tus sistemas de seguridad</p>
            </div>
            
            <div class="text-center p-6 bg-white/5 rounded-xl hover:bg-white/10 transition-colors">
                <div class="w-16 h-16 mx-auto mb-4 bg-blue-600/20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Tiempo Real</h3>
                <p class="text-gray-400 text-sm">Monitoreo y respuesta inmediata 24/7</p>
            </div>
            
            <div class="text-center p-6 bg-white/5 rounded-xl hover:bg-white/10 transition-colors">
                <div class="w-16 h-16 mx-auto mb-4 bg-blue-600/20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" viewBox="-5.5 0 32 32" fill="currentColor">
                        <path class="fill-blue-500" d="M4.040 20.12v0c-0.88 0-1.84-0.36-2.56-0.92-0.64-0.56-1.44-1.52-1.44-3.2 0-2.76 2.28-4 3.76-4.12 1.8-0.16 4.24 1.36 6.8 3.12 2.52-1.68 4.88-3.2 6.68-3.12 1.44 0.080 3.6 1.24 3.6 4.12 0 2.8-2.12 4-3.52 4.12-0.080 0-0.16 0-0.24 0-1.76 0-4.080-1.48-6.52-3.12-2.52 1.64-4.76 3.12-6.56 3.12zM4.040 13.56c-0.040 0-0.080 0-0.12 0-0.6 0.040-2.2 0.6-2.2 2.44 0 2 1.48 2.36 2.36 2.44 1.16 0.12 3.12-1.2 5.040-2.44-2-1.24-3.88-2.44-5.080-2.44zM12.080 16c1.96 1.32 4 2.52 5.12 2.44 0.68-0.040 2-0.64 2-2.44 0-1.84-1.32-2.4-2.040-2.44-1.16-0.080-3.16 1.16-5.080 2.44z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Escalable</h3>
                <p class="text-gray-400 text-sm">Desde pequeñas empresas hasta grandes operaciones</p>
            </div>
            
            <div class="text-center p-6 bg-white/5 rounded-xl hover:bg-white/10 transition-colors">
                <div class="w-16 h-16 mx-auto mb-4 bg-blue-600/20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" viewBox="0 0 512 512" fill="currentColor">
                        <path class="fill-blue-500" d="M379.734355,174.506667 C373.121022,106.666667 333.014355,0 209.067688,0 C85.1210217,0 45.014355,106.666667 38.4010217,174.506667 C15.2012632,183.311569 -0.101643453,205.585799 0.000508304259,230.4 L0.000508304259,260.266667 C0.000508304259,293.256475 26.7445463,320 59.734355,320 C92.7241638,320 119.467688,293.256475 119.467688,260.266667 L119.467688,230.4 C119.360431,206.121456 104.619564,184.304973 82.134355,175.146667 C86.4010217,135.893333 107.307688,42.6666667 209.067688,42.6666667 C310.827688,42.6666667 331.521022,135.893333 335.787688,175.146667 C313.347976,184.324806 298.68156,206.155851 298.667688,230.4 L298.667688,260.266667 C298.760356,283.199651 311.928618,304.070103 332.587688,314.026667 C323.627688,330.88 300.801022,353.706667 244.694355,360.533333 C233.478863,343.50282 211.780225,336.789048 192.906491,344.509658 C174.032757,352.230268 163.260418,372.226826 167.196286,392.235189 C171.132153,412.243552 188.675885,426.666667 209.067688,426.666667 C225.181549,426.577424 239.870491,417.417465 247.041022,402.986667 C338.561022,392.533333 367.787688,345.386667 376.961022,317.653333 C401.778455,309.61433 418.468885,286.351502 418.134355,260.266667 L418.134355,230.4 C418.23702,205.585799 402.934114,183.311569 379.734355,174.506667 Z M76.8010217,260.266667 C76.8010217,269.692326 69.1600148,277.333333 59.734355,277.333333 C50.3086953,277.333333 42.6676884,269.692326 42.6676884,260.266667 L42.6676884,230.4 C42.6676884,224.302667 45.9205765,218.668499 51.2010216,215.619833 C56.4814667,212.571166 62.9872434,212.571166 68.2676885,215.619833 C73.5481336,218.668499 76.8010217,224.302667 76.8010217,230.4 L76.8010217,260.266667 Z M341.334355,230.4 C341.334355,220.97434 348.975362,213.333333 358.401022,213.333333 C367.826681,213.333333 375.467688,220.97434 375.467688,230.4 L375.467688,260.266667 C375.467688,269.692326 367.826681,277.333333 358.401022,277.333333 C348.975362,277.333333 341.334355,269.692326 341.334355,260.266667 L341.334355,230.4 Z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Soporte Dedicado</h3>
                <p class="text-gray-400 text-sm">Equipo técnico disponible siempre</p>
            </div>
        </div>
    </div>
</section>
