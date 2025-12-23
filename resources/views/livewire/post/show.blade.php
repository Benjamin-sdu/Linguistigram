<div class="fixed inset-0 flex flex-col overflow-hidden">
    {{-- Scrollable content --}}
    <main class="flex-1 overflow-y-auto px-4 py-6 pb-20">
        <div class="max-w-2xl mx-auto">
            <livewire:post.item :post="$post" :key="$post->id" />
            <section class="mt-8">
                {{-- Leave a comment --}}
                <form wire:submit.prevent="addComment" class="grid grid-cols-12 items-start w-full gap-2">
                    @csrf

                    <div class="col-span-10">
                        <x-input-label for="body" :value="__('Leave a comment')" />
                        <x-text-input
                            id="body"
                            wire:model.defer="body"
                            class="block mt-1 w-full"
                            type="text"
                            name="body"
                            :value="old('body')"
                            autocomplete="off" />
                        <div class="h-6 mt-2">
                            <x-input-error :messages="$errors->get('body')" />
                        </div>
                    </div>
                    <div class="col-span-2 flex justify-end">
                        <button type="submit"
                            class="btn btn-sm btn-primary mt-7">
                            Post
                        </button>
                    </div>
                </form>
                <main>
                    @if($comments)

                    @foreach ($comments as $comment)

                    <section class="flex flex-col gap-2">
                        {{-- main comment --}}
                        @include('livewire.post.partials.comment')

                        @if($comment->replies)

                        @foreach ($comment->replies as $reply)

                        {{-- reply comment --}}
                        @include('livewire.post.partials.reply')
                        @endforeach
                        @endif

                    </section>

                    @endforeach

                    @else
                    No comments

                    @endif
                </main>
            </section>
        </div>
    </main>
</div>