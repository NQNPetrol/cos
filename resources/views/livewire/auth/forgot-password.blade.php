<div class="flex flex-col gap-6">
    <x-auth-header 
        :title="__('Recuperar contraseña')" 
        :description="__('Ingresa tu email para recibir un correo de recuperación')" 
    />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <!-- Email Address -->
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Email</label>
            <input 
                type="email" 
                wire:model="email"
                class="block w-full rounded-md border-0 py-2 px-3 text-white bg-gray-800 shadow-sm ring-1 ring-inset ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                placeholder="email@ejemplo.com"
                required
            />
        </div>

        <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
            {{ __('Enviar correo de recuperación') }}
        </button>
    </form>

    <div class="text-center text-sm text-gray-400">
        {{ __('O volver al') }}
        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-300 hover:text-blue-800 dark:text-indigo-200 dark:hover:text-indigo-500">
            {{ __('inicio de sesión') }}
        </a>
    </div>
</div>