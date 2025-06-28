
<x-app-layout>
    <div class="py-8">
        <livewire:clientes />
    </div>
</x-app-layout>

{{-- <x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Listado de Clientes</h2>

        <div class="overflow-x-auto bg-white shadow rounded-md">
            <form method="POST">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                <input type="checkbox" id="checkAll" class="form-checkbox">
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">ID</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Nombre</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">CUIT</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Domicilio</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Ciudad</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Provincia</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Categoría</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Convenio</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                        @foreach($clientes as $cliente)
                            <tr>
                                <td class="px-4 py-2">
                                    <input type="checkbox" name="selected[]" value="{{ $cliente->id }}" class="rowCheckbox form-checkbox">
                                </td>
                                <td class="px-4 py-2">{{ $cliente->id }}</td>
                                <td class="px-4 py-2">{{ $cliente->nombre }}</td>
                                <td class="px-4 py-2">{{ $cliente->cuit }}</td>
                                <td class="px-4 py-2">{{ $cliente->domicilio }}</td>
                                <td class="px-4 py-2">{{ $cliente->ciudad }}</td>
                                <td class="px-4 py-2">{{ $cliente->provincia }}</td>
                                <td class="px-4 py-2">{{ $cliente->categoria }}</td>
                                <td class="px-4 py-2">{{ $cliente->convenio }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
    </div>

    <script>
        // Seleccionar todos
        document.getElementById('checkAll').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('.rowCheckbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
</x-app-layout> --}}
