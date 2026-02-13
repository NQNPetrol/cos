<x-administrative-layout>
    <div class="py-6">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header mejorado -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-blue-600/20 rounded-lg">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h1 class="text-2xl font-semibold text-gray-100">Calendario de Rodados</h1>
                        </div>
                        <p class="text-sm text-gray-400 ml-11">Visualiza turnos, cambios de equipos y pagos en el calendario</p>
                    </div>
                    <a href="{{ route('rodados.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-700 hover:bg-zinc-600 border border-zinc-600 hover:border-zinc-500 rounded-lg font-medium text-sm text-gray-100 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver a Gestión
                    </a>
                </div>

                <!-- Mensajes mejorados -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-900/30 border border-green-800/50 text-green-300 rounded-lg flex items-center gap-3 animate-in fade-in slide-in-from-top duration-300">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-900/30 border border-red-800/50 text-red-300 rounded-lg flex items-center gap-3 animate-in fade-in slide-in-from-top duration-300">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
            </div>

            <!-- Componente Livewire del Calendario -->
            @livewire('rodados.calendario-rodados')
        </div>
    </div>
</x-administrative-layout>
