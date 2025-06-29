<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-50 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex items-center justify-center h-[70vh] bg-gray-700">
        <div class="text-center bg-gray-900 p-8 rounded-lg shadow-lg max-w-lg">
            <h1 class="text-3xl font-bold text-emerald-400 mb-4">🚧 En Construcción 🚧</h1>
            <p class="text-gray-300 mb-6">
                Estamos trabajando para traerte un panel de control con gráficos e indicadores en tiempo real.
            </p>
            <p class="text-gray-400">
                ¡Vuelve pronto para ver las novedades!
            </p>
        </div>
    </div>
</x-app-layout>
