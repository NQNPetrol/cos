<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-50 leading-tight">
            {{ __('Flight Logs - Administración') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Componente Livewire -->
            <livewire:flight-logs-admin-table />
        </div>
    </div>
</x-app-layout>

