@props(['type' => 'submit'])

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => 'w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-200 transform hover:scale-[1.02] shadow-md text-sm']) }}>
    {{ $slot }}
</button>