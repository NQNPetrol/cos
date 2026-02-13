<x-administrative-layout>
    <div class="container mx-auto p-6">
        <div class="flex justify-end mb-4">
            <a href="{{ route('personal.create') }}"
               class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded">
                + Nuevo Personal
            </a>
        </div>
        <livewire:personal.listado />
    </div>
</x-administrative-layout>