@extends('layouts.cliente')
@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                <div class="bg-zinc-800/50 rounded-lg p-6">
                    <livewire:client.update-profile-information-form />
                </div>

                <x-section-border />

            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="bg-zinc-800/50 rounded-lg p-6">
                    <livewire:client.update-password-form />
                </div>

                <x-section-border />
            @endif

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())

                <div class="bg-zinc-800/50 rounded-lg p-6">
                    <livewire:client.delete-user-form />
                </div>
            @endif
        </div>
    </div>
@endsection
