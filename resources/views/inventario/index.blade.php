<x-app-layout>
    <div class="container mx-auto p-6">
        <div class="flex justify-end mb-4">
            <a href="{{ route('inventario.index') }}"
               class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded">
                Inventario de dispositivos
            </a>
        </div>
        <livewire:inventario.manage-dispositivos/>
    </div>
</x-app-layout>