{{-- Features Section - Interactive Tabs --}}
<section id="features" class="py-20 bg-neutral-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        {{-- Section Header --}}
        <div class="max-w-3xl mx-auto text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl font-semibold text-neutral-900 mb-4">
                Funcionalidades principales
            </h2>
            <p class="text-neutral-600 leading-relaxed">
                Explora las capacidades que COS ofrece para optimizar tus operaciones diarias.
            </p>
        </div>
        
        {{-- Features Grid with Tabs --}}
        <div class="grid lg:grid-cols-12 gap-8" data-aos="fade-up">
            {{-- Tabs Navigation --}}
            <div class="lg:col-span-4">
                <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden">
                    <button id="tab-events" onclick="showFeature('events')" class="feature-tab w-full text-left active">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-primary-50 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-neutral-900">Registro de eventos</div>
                                <div class="text-xs text-neutral-500">Incidencias y novedades</div>
                            </div>
                        </div>
                    </button>
                    
                    <button id="tab-fleet" onclick="showFeature('fleet')" class="feature-tab w-full text-left border-t border-neutral-100">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-neutral-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-neutral-900">Gestión de flotas</div>
                                <div class="text-xs text-neutral-500">Vehículos y rutas</div>
                            </div>
                        </div>
                    </button>
                    
                    <button id="tab-personnel" onclick="showFeature('personnel')" class="feature-tab w-full text-left border-t border-neutral-100">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-neutral-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-neutral-900">Personal</div>
                                <div class="text-xs text-neutral-500">Equipos y turnos</div>
                            </div>
                        </div>
                    </button>
                    
                    <button id="tab-reports" onclick="showFeature('reports')" class="feature-tab w-full text-left border-t border-neutral-100">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-neutral-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-neutral-900">Reportes</div>
                                <div class="text-xs text-neutral-500">Informes y métricas</div>
                            </div>
                        </div>
                    </button>
                    
                    <button id="tab-dashboard" onclick="showFeature('dashboard')" class="feature-tab w-full text-left border-t border-neutral-100">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-neutral-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-neutral-900">Dashboards</div>
                                <div class="text-xs text-neutral-500">Visualización de datos</div>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
            
            {{-- Tab Content --}}
            <div class="lg:col-span-8">
                {{-- Events Content --}}
                <div id="feature-events" class="feature-content bg-white rounded-xl border border-neutral-200 p-6 lg:p-8">
                    <h3 class="text-xl font-semibold text-neutral-900 mb-4">Registro de eventos</h3>
                    <p class="text-neutral-600 mb-6 leading-relaxed">
                        Registra y gestiona todo tipo de eventos, incidencias o novedades que ocurran 
                        en tus operaciones. Cada registro incluye categorización, ubicación, evidencia 
                        fotográfica y seguimiento completo.
                    </p>
                    <div class="grid sm:grid-cols-2 gap-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-neutral-700">Categorización flexible</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-neutral-700">Adjuntos multimedia</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-neutral-700">Geolocalización</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-neutral-700">Exportación PDF</span>
                        </div>
                    </div>
                    <div class="screenshot-frame">
                        <img src="{{ asset('images/placeholder-events.png') }}" alt="Gestión de eventos" class="w-full" onerror="this.src='https://placehold.co/700x350/f5f5f5/a3a3a3?text=Registro+de+Eventos'">
                    </div>
                </div>
                
                {{-- Fleet Content --}}
                <div id="feature-fleet" class="feature-content hidden bg-white rounded-xl border border-neutral-200 p-6 lg:p-8">
                    <h3 class="text-xl font-semibold text-neutral-900 mb-4">Gestión de flotas</h3>
                    <p class="text-neutral-600 mb-6 leading-relaxed">
                        Administra tus vehículos, define rutas y objetivos, y visualiza 
                        la ubicación en tiempo real de toda tu flota en un mapa interactivo.
                    </p>
                    <div class="grid sm:grid-cols-2 gap-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-neutral-700">Tracking en tiempo real</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-neutral-700">Definición de rutas</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-neutral-700">Historial de recorridos</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-neutral-700">Asignación de personal</span>
                        </div>
                    </div>
                    <div class="screenshot-frame">
                        <img src="{{ asset('images/placeholder-fleet.png') }}" alt="Gestión de flotas" class="w-full" onerror="this.src='https://placehold.co/700x350/f5f5f5/a3a3a3?text=Mapa+de+Flotas'">
                    </div>
                </div>
                
                {{-- Personnel Content --}}
                <div id="feature-personnel" class="feature-content hidden bg-white rounded-xl border border-neutral-200 p-6 lg:p-8">
                    <h3 class="text-xl font-semibold text-neutral-900 mb-4">Gestión de personal</h3>
                    <p class="text-neutral-600 mb-6 leading-relaxed">
                        Administra tu equipo de trabajo, asigna turnos, y mantén el control 
                        sobre la disponibilidad y ubicación de tu personal en campo.
                    </p>
                    <div class="grid sm:grid-cols-2 gap-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-neutral-700">Perfiles de empleados</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-neutral-700">Gestión de turnos</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-neutral-700">Control de asistencia</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-neutral-700">Asignación a vehículos</span>
                        </div>
                    </div>
                    <div class="screenshot-frame">
                        <img src="{{ asset('images/placeholder-personnel.png') }}" alt="Gestión de personal" class="w-full" onerror="this.src='https://placehold.co/700x350/f5f5f5/a3a3a3?text=Gestión+de+Personal'">
                    </div>
                </div>
                
                {{-- Reports Content --}}
                <div id="feature-reports" class="feature-content hidden bg-white rounded-xl border border-neutral-200 p-6 lg:p-8">
                    <h3 class="text-xl font-semibold text-neutral-900 mb-4">Reportes e informes</h3>
                    <p class="text-neutral-600 mb-6 leading-relaxed">
                        Genera reportes detallados de eventos, operaciones y métricas. 
                        Exporta en PDF con el formato profesional de tu empresa.
                    </p>
                    <div class="grid sm:grid-cols-2 gap-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-neutral-700">Reportes de eventos</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-neutral-700">Exportación PDF</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-neutral-700">Métricas históricas</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-neutral-700">Plantillas personalizables</span>
                        </div>
                    </div>
                    <div class="screenshot-frame">
                        <img src="{{ asset('images/placeholder-reports.png') }}" alt="Reportes" class="w-full" onerror="this.src='https://placehold.co/700x350/f5f5f5/a3a3a3?text=Generación+de+Reportes'">
                    </div>
                </div>
                
                {{-- Dashboard Content --}}
                <div id="feature-dashboard" class="feature-content hidden bg-white rounded-xl border border-neutral-200 p-6 lg:p-8">
                    <h3 class="text-xl font-semibold text-neutral-900 mb-4">Dashboards analíticos</h3>
                    <p class="text-neutral-600 mb-6 leading-relaxed">
                        Visualiza el estado de tus operaciones con dashboards interactivos. 
                        Gráficos, métricas y KPIs actualizados en tiempo real.
                    </p>
                    <div class="grid sm:grid-cols-2 gap-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-neutral-700">Gráficos interactivos</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-neutral-700">KPIs en tiempo real</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-neutral-700">Filtros por período</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-neutral-700">Exportación de datos</span>
                        </div>
                    </div>
                    <div class="screenshot-frame">
                        <img src="{{ asset('images/placeholder-dashboard.png') }}" alt="Dashboards" class="w-full" onerror="this.src='https://placehold.co/700x350/f5f5f5/a3a3a3?text=Dashboard+Analítico'">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
