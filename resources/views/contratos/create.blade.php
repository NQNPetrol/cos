<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-zinc-900 text-gray-50 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6">Crear Contrato</h2>

        @if ($errors->any())
            <div class="bg-red-600 text-white p-4 mb-4 rounded">
                <strong>¡Error!</strong> Corrige los campos indicados.<br>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <livewire:contratos.create/>
    </div>
</x-app-layout>
