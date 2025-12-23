@props([
    'align' => 'end-2 md:start-4
                ltr:origin-bottom-right rtl:origin-bottom-left
                sm:ltr:origin-bottom-left sm:rtl:origin-bottom-right',
    'width' => 'w-48',
    'contentClasses' => 'rounded-md ring-1 ring-black ring-opacity-5 
                    bg-base-300 py-1'
])
<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95" class="absolute z-50 mb-2 bottom-full {{ $width }} rounded-md shadow-lg {{ $align }}" style="display: none;" @click="open = false">
        <div class="{{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>