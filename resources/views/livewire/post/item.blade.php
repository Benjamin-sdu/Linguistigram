<div class="max-w-lg mx-auto">
    {{-- header --}}
    <header class="flex items-center gap-3 mb-2">
        @if ($post->user->getProfilePictureUrl())
        <img src="{{ $post->user->getProfilePictureUrl() }}" alt="{{ $post->user->name }}'s profile"
            class="w-12 h-12 rounded-full shadow object-cover aspect-square">
        @else
        <span class="text-xl font-bold text-primary-content flex items-center justify-center w-12 h-12 rounded-full">
            {{ strtoupper(substr($post->user->name, 0, 1)) }}
        </span>
        @endif

        <div class="flex justify-between items-center w-full relative">
            <h5 class="font-semibold text-sm">{{ $post->user->name }}</h5>
            <div class="relative">
                <button
                    onclick="toggleMenu({{ $post->id }})"
                    id="dots-btn-{{ $post->id }}"
                    @if(auth()->id() !== $post->user_id) disabled @endif
                    class="dots-btn">
                    <x-tabler-dots />
                </button>
                @if(auth()->id() === $post->user_id)
                <div id="menu-{{ $post->id }}" class="absolute right-0 mt-2 w-32 bg-white border rounded shadow-lg hidden z-10">
                    <form wire:submit.prevent="destroy" onsubmit="return confirm('Are you sure you want to delete this post?');">
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-red-100 text-red-600">
                            Delete
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </header>
    <script>
        function toggleMenu(postId) {
            const menu = document.getElementById('menu-' + postId);
            menu.classList.toggle('hidden');
            document.addEventListener('click', function handler(e) {
                if (!e.target.closest('#dots-btn-' + postId) && !e.target.closest('#menu-' + postId)) {
                    menu.classList.add('hidden');
                    document.removeEventListener('click', handler);
                }
            });
        }
    </script>

    {{-- Description --}}
    @if($post->description)
    <p class="mb-4 text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $post->description }}</p>
    @endif

    {{-- Media Slider (only if more than 1 media) --}}
    @if($post->media->count() > 1)
    <div id="post-slider-{{ $post->id }}" class="keen-slider mb-4">
        @foreach($post->media as $media)
        <div class="keen-slider__slide">
            @if($media->mime === 'image')
            <img src="{{ $media->url }}" alt="Post media" class="w-full h-full object-cover">
            @elseif($media->mime === 'video')
            <video controls class="w-full h-full">
                <source src="{{ $media->url }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            @endif
        </div>
        @endforeach
    </div>
    @elseif($post->media->count() === 1)
    {{-- Single media without slider --}}
    <div class="mb-4">
        @php $media = $post->media->first(); @endphp
        @if($media->mime === 'image')
        <img src="{{ $media->url }}" alt="Post media" class="w-full h-auto object-cover rounded-lg">
        @elseif($media->mime === 'video')
        <video controls class="w-full h-auto rounded-lg">
            <source src="{{ $media->url }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        @endif
    </div>
    @endif

   <footer>
    <div class="flex gap-4 items-center my-2">
        <div class="flex items-center gap-1">
            @auth
            <button wire:click="togglePostLike" class="like-btn" @if(auth()->id() === $post->user_id) disabled @endif>
                @if($post->isLikedBy(auth()->user()))
                    <x-tabler-thumb-up-filled />
                @else
                    <x-tabler-thumb-up />
                @endif
            </button>
            @else
            <button class="like-btn" disabled title="Login to like posts">
                <x-tabler-thumb-up />
            </button>
            @endauth
            <span>{{ $post->likes_count ?? 0 }}</span>
        </div>

        <div class="flex items-center gap-1">
            @auth
            <button onclick="window.location='{{ route('post.show', $post) }}'">
                <x-tabler-message-circle-2 />
            </button>
            @else
            <button disabled title="Login to comment">
                <x-tabler-message-circle-2 />
            </button>
            @endauth
            <span class="ml-2">{{ $post->comments_count ?? 0 }}</span>
        </div>
    </div>
</footer>
</div>