<div>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <!-- Header con botón volver -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-100">Imagen del Vehículo</h2>
                                <p class="text-sm text-gray-400 mt-1">Detalles del evento de cruce capturado</p>
                            </div>
                            
                            <div>
                                <button wire:click="backToList" 
                                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-800 focus:ring ring-gray-300 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                    Volver al Listado
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido Principal -->
                    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                        @if($record)
                            <!-- Imagen o Mensaje de No Disponible -->
                            <div class="text-center">
                                @if($hasImage && $imageData)
                                    <div class="mb-6">
                                        <h3 class="text-lg font-medium text-gray-300 mb-4">Imagen Capturada</h3>
                                        <div class="bg-black rounded-lg p-4 border border-gray-600 inline-block max-w-full">
                                            <img 
                                                src="{{ $imageData }}" 
                                                alt="Vehículo {{ $plateNo }}"
                                                class="mx-auto max-w-full h-auto max-h-96 rounded-lg shadow-lg"
                                            >
                                        </div>
                                    </div>
                                
                                @else
                                    <!-- Mensaje de Imagen No Disponible -->
                                    <div class="py-12 text-center">
                                        <svg class="w-24 h-24 mx-auto text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <h3 class="text-2xl font-medium text-gray-300 mb-2">Imagen no disponible</h3>
                                        <p class="text-gray-400 text-lg max-w-2xl mx-auto mb-6">
                                            Este evento no tiene imagen capturada o la imagen ya no está disponible en el sistema.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- Estado de carga o error -->
                            <div class="text-center py-12">
                                <svg class="animate-spin w-12 h-12 mx-auto text-blue-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v4m0 12v4m8-10h-4M6 12H2m15.364-7.364l-2.828 2.828M7.464 17.536l-2.828 2.828m12.728 0l-2.828-2.828M7.464 6.464L4.636 3.636"/>
                                </svg>
                                <p class="text-gray-400">Cargando información del evento...</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>