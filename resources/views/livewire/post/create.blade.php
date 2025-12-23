<main class="grid grid-cols-12 gap-3 h-full w-full overflow-hidden">
    <div class="col-span-12 lg:col-span-7 lg:col-start-4 flex flex-col h-full overflow-y-auto px-2">
        <h2 class="text-2xl font-bold mb-6">Create Post</h2>
        
        {{-- Description Textarea --}}
        <div class="mb-4">
            <textarea
                wire:model="description"
                id="description"
                rows="6"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                placeholder="Body text"></textarea>
            
            {{-- Post and Cancel Buttons right below textarea --}}
            <div class="flex gap-2 justify-end mt-2">
                <a href="{{ route('home') }}"
                    class="btn btn-sm btn-secondary">
                    Cancel
                </a>
                <button
                    wire:loading.attr='disabled'
                    wire:click="save"
                    type="button"
                    class="btn btn-sm btn-primary">
                    Post
                </button>
            </div>
            
            @error('description')
            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        {{-- Show preview when files are uploaded --}}
        @if (count($media) > 0)
        <div class="flex overflow-x-auto w-full h-96 snap-x snap-mandatory gap-2 mb-4">
            @foreach ($media as $key=> $file)
            <div class="relative w-full h-full shrink-0 snap-always snap-center">
                <img src="{{$file->temporaryUrl()}}" alt="" class="w-full h-full object-contain">
                
                {{-- Remove button --}}
                <button 
                    wire:click="removeMedia({{$key}})"
                    type="button"
                    class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow-lg transition-colors duration-200">
                    <x-tabler-trash class="w-5 h-5" />
                </button>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Upload button - always visible --}}
        <label for="customFileInput" class="flex flex-col gap-3 cursor-pointer items-center my-8">
            <input wire:model.live="newMedia" type="file" multiple accept=".jpg,.png,.jpeg,.mp4,.mov" id="customFileInput" class="sr-only">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-14 h-14">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
            </span>
            <span class="btn btn-md btn-primary">
                {{ count($media) > 0 ? 'Upload more images' : 'Upload images' }}
            </span>
        </label>
    </div>
</main>