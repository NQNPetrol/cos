<div class="min-h-screen flex items-center justify-center bg-gray-900 text-gray-100">
    <div class="w-full max-w-md p-8 rounded-xl shadow-lg bg-slate-800">
        
        {{-- Logo SVG --}}
        <div class="flex justify-center mb-6">
            {{-- <img src="{{ asset('images/logo-cos.svg') }}" alt="COS Logo" class="h-16"> --}}
            <x-application-mark class="h-16 w-auto" />
        </div>

        {{-- Título y Descripción --}}
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold">{{ __('Log in to your account') }}</h2>
            <p class="text-gray-400 mt-1">{{ __('Enter your email and password below to log in') }}</p>
        </div>

        {{-- Session Status --}}
        <x-auth-session-status class="mb-4 text-green-400 text-center" :status="session('status')" />

        {{-- Formulario de Login --}}
        <form wire:submit="login" class="space-y-5">
            {{-- Email Address --}}
            <div>
                <label class="block mb-1 text-sm text-gray-300" for="email">{{ __('Email address') }}</label>
                <input id="email" type="email" wire:model="email"
                       class="w-full px-4 py-2 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring focus:border-indigo-400"
                       placeholder="email@example.com" required autofocus autocomplete="email">
                @error('email') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Password --}}
            <div>
                <label class="block mb-1 text-sm text-gray-300" for="password">{{ __('Password') }}</label>
                <input id="password" type="password" wire:model="password"
                       class="w-full px-4 py-2 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring focus:border-indigo-400"
                       placeholder="{{ __('Password') }}" required autocomplete="current-password">
                @error('password') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Remember Me y Forgot --}}
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center">
                    <input type="checkbox" wire:model="remember"
                           class="rounded border-gray-600 bg-gray-700 focus:ring-indigo-500">
                    <span class="ml-2">{{ __('Remember me') }}</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="text-indigo-400 hover:underline" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                    class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 rounded text-white font-semibold transition">
                {{ __('Log in') }}
            </button>
        </form>

        {{-- Register Link --}}
        @if (Route::has('register'))
            <div class="mt-6 text-center text-sm text-gray-400">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}" class="text-indigo-400 hover:underline">
                    {{ __('Sign up') }}
                </a>
            </div>
        @endif
    </div>
</div>

