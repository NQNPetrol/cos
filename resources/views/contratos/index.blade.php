<x-app-layout>
    <div class="py-8 mx-auto px-4">
        <div class="flex justify-end mb-4">
            <a href="{{ route('contratos.create') }}"
               class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded">
                + Nuevo Contrato
            </a>
        </div>

        <livewire:contratos.index />
    </div>
</x-app-layout>