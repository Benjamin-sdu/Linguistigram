@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'text-base-content border border-base-100 bg-base-300 rounded-lg placeholder-base-content placeholder-opacity-50
                           transition duration-150 ease-out
                           focus:ring-primary focus:outline-none focus:ring-2']) }}>
