<x-app-layout>
    <div class="container mx-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('patrullas.index') }}" 
               class="flex items-center text-blue-400 hover:text-blue-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver a Patrullas
            </a>
            <h1 class="text-2xl font-bold text-gray-100">Patrulla: {{ $patrulla->patente }}</h1>
        </div>
        
        @livewire('dispositivo-patrulla.asignar-dispositivos', ['patrulla' => $patrulla])
    </div>
</x-app-layout>