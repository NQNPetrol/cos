<x-app-layout>
    <div class="py-8">
        @isset($cliente)
        <livewire:empresas-asociadas.listado-empresas-asociadas/>
        @endisset
    </div>
</x-app-layout>