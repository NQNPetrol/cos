<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-100 mb-6">
            Dispositivos de la Patrulla: {{ $patrulla->patente }}
        </h1>
        @livewire('dispositivo-patrulla.asignar-dispositivos', ['patrulla' => $patrulla])
    </div>
</x-app-layout>