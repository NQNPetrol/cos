@props(['id', 'type' => 'text', 'label', 'placeholder' => '', 'required' => false, 'autocomplete' => null, 'viewable' => false])

<div class="mb-5">
    <label for="{{ $id }}" class="block text-sm font-medium text-blue-900 mb-2">
        {{ $label }}
        @if($required) <span class="text-red-500">*</span> @endif
    </label>
    <div class="relative">
        <input 
            id="{{ $id }}"
            type="{{ $type }}"
            {{ $attributes->merge(['class' => 'w-full px-4 py-2.5 border border-gray-300 rounded-lg input-focus transition duration-200 placeholder:text-gray-400 placeholder:text-sm text-sm pr-10']) }}
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            @if($required) required @endif
            @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif>
        
        @if($viewable && ($type === 'password' || $type === 'text'))
            <button 
                type="button" 
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                onclick="togglePasswordVisibility('{{ $id }}', this)"
                title="Mostrar contraseña">
                <svg class="h-5 w-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <svg class="h-5 w-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                </svg>
            </button>
        @endif
    </div>
    
    {{ $slot }}
</div>

@if($viewable)
<script>
function togglePasswordVisibility(inputId, button) {
    const input = document.getElementById(inputId);
    const eyeOpen = button.querySelector('.eye-open');
    const eyeClosed = button.querySelector('.eye-closed');
    
    if (input.type === 'password') {
        input.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
        button.setAttribute('title', 'Ocultar contraseña');
    } else {
        input.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
        button.setAttribute('title', 'Mostrar contraseña');
    }
}
</script>
@endif