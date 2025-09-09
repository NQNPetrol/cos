<div class="flex flex-col items-center mb-8">
    <div class="flex items-center justify-center mb-4">
        <img src="{{ asset('cyh.png') }}" 
             alt="Logo Centro de Operaciones de Seguridad" 
             class="h-28 w-auto mb-4"
             onerror="this.style.display='none'; document.getElementById('fallbackLogo').style.display='flex';">
        
        <div id="fallbackLogo" class="h-20 w-20 items-center justify-center rounded-full bg-blue-600 hidden">
            <svg class="h-12 w-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
        </div>
    </div>
    <h1 class="text-3xl font-bold text-blue-900 text-center mb-2">Centro de Operaciones</h1>
    <h2 class="text-xl font-semibold text-gray-600 text-center">de Seguridad</h2>
</div>