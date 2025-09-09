@props(['href'])

<a 
    href="{{ $href }}"
    {{ $attributes->merge(['class' => 'text-blue-500 hover:text-blue-700 font-medium underline transition duration-200']) }}>
    {{ $slot }}
</a>