<x-administrative-layout>
    <div class="py-8">
        <livewire:empresas-asociadas.listado-empresas-asociadas
            :clienteId="$cliente->id ?? null"/>
    </div>
</x-administrative-layout>