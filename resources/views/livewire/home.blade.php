<div class="w-full">
 
    @guest
        @include('components.guest-banner')
    @endguest

    {{-- main --}}
    <section class="mt-5 space-y-4 p-2">


        @if ($posts)

        @foreach ($posts as $post)

        <livewire:post.item wire:key="post-{{$post->id}}" :post="$post" />

        @endforeach

        @else

        <p class="font-bol flex justify-center">No posts</p>

        @endif

    </section>
</div>