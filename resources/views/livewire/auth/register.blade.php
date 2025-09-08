<div class="flex flex-col gap-6">
    <!-- Header mejorado -->
    <div class="text-center">
        <h1 class="text-2xl font-bold text-white mb-2">Crear una cuenta</h1>
        <p class="text-gray-800">Completa tus datos para registrarte en el sistema</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="rounded-md bg-green-900/20 p-4">
            <p class="text-sm text-green-400">{{ session('status') }}</p>
        </div>
    @endif

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input
            wire:model="name"
            :label="__('Nombre')"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('Tu nombre completo')"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email')"
            type="email"
            required
            autocomplete="email"
            placeholder="email@ejemplo.com"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Contraseña')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Crea una contraseña')"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirmá la contraseña')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Repeti la contraseña')"
            viewable
        />

        <button type="submit" 
                class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-3 px-4 
                       rounded-lg transition duration-200 transform hover:scale-105 shadow-lg">
            {{ __('Registrarse') }}
        </button>
    </form>

    <div class="text-center text-sm text-gray-800">
        {{ __('¿Ya tienes una cuenta?') }}
        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-300 hover:text-blue-800 dark:text-indigo-200 dark:hover:text-indigo-500">
            {{ __('Inicia sesión') }}
        </a>
    </div>
</div>
