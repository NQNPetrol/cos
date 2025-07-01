{{-- <x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button class="ms-4">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout> --}}
<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-900 text-gray-100">
        <div class="w-full max-w-md p-8 rounded-xl shadow-lg bg-gray-800">
            <div class="flex justify-center mb-6">
                {{-- Tu logo SVG --}}
                <img src="{{ asset('images/logo-cos.svg') }}" alt="COS Logo" class="h-16">
            </div>

            <h2 class="text-center text-2xl font-bold mb-6">Bienvenido al Centro de Operaciones de Seguridad</h2>

            @if (session('status'))
                <div class="mb-4 text-green-400">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-300" for="email">Email</label>
                    <input id="email" class="w-full px-4 py-2 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring focus:border-indigo-400" type="email" name="email" required autofocus>
                    @error('email') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-300" for="password">Password</label>
                    <input id="password" class="w-full px-4 py-2 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring focus:border-indigo-400" type="password" name="password" required>
                    @error('password') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center">
                        <input type="checkbox" class="rounded border-gray-600 bg-gray-700 focus:ring-indigo-500" name="remember">
                        <span class="ml-2">Recordarme</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-indigo-400 hover:underline" href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <button type="submit" class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 rounded text-white font-semibold">
                    Ingresar
                </button>
            </form>

            <div class="mt-6 text-center text-sm">
                ¿No tienes cuenta?
                <a href="{{ route('register') }}" class="text-indigo-400 hover:underline">Regístrate</a>
            </div>
        </div>
    </div>
</x-guest-layout>
