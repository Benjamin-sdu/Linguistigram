<div class="m-auto md:p-10 w-full h-full relative overflow-hidden select-none">
    <div class="relative h-full w-full max-w-md max-h-[600px] m-auto md:w-96 md:h-[600px]">
        @foreach ($users as $user)
        <div
            x-data="{
                isSwiping: false,
                swipingLeft: false,
                swipingRight: false,

                swipeRight() {
                    $el.style.transition = 'transform 0.1s ease-out, opacity 0.1s ease-out';
                    $el.style.transform = `translate(100px, -50px) rotate(20deg)`;
                    $el.style.opacity = 0;
                    setTimeout(() => $el.remove(), 150);
                    $dispatch('swipedright');
                    @this.call('follow', {{ $user->id }});
                },

                swipeLeft() {
                    $el.style.transition = 'transform 0.1s ease-out, opacity 0.1s ease-out';
                    $el.style.transform = `translate(-100px, -50px) rotate(-20deg)`;
                    $el.style.opacity = 0;
                    setTimeout(() => $el.remove(), 150);
                    $dispatch('swipedleft');
                }
            }"

            x-init="
                const element = $el;
                const hammertime = new Hammer(element);
                hammertime.get('pan').set({ direction: Hammer.DIRECTION_HORIZONTAL });

                hammertime.on('pan', (event) => {
                    isSwiping = true;
                    if (event.deltaX === 0) return;

                    const dx = Math.max(-40, Math.min(event.deltaX, 40));
                    const rotate = dx / 5;
                    element.style.transition = 'none';
                    element.style.transform = `translateX(${dx}px) rotate(${rotate}deg)`;

                    swipingRight = dx > 20;
                    swipingLeft = dx < -20;
                });

                hammertime.on('panend', (event) => {
                    isSwiping = false;
                    const threshold = 40;
                    const dx = event.deltaX;

                    if (dx > threshold) swipeRight();
                    else if (dx < -threshold) swipeLeft();
                    else {
                        element.style.transition = 'transform 0.25s ease-in-out';
                        element.style.transform = 'translateX(0) rotate(0deg)';
                        setTimeout(() => {
                            element.style.transition = '';
                            swipingLeft = false;
                            swipingRight = false;
                        }, 250);
                    }
                });
            "

            :class="{'cursor-grab': !isSwiping, 'cursor-grabbing': isSwiping}"
            class="absolute inset-0 m-auto transform z-50"
            style="backface-visibility: hidden; transform-style: preserve-3d;">
            <div class="h-full w-full relative bg-[linear-gradient(to_bottom,_#7e22ce_0%,_#7e22ce_50%,_#12003D_83%,_#12003D_100%)] from-purple-600 to-black border border-base-200 shadow-md rounded-xl overflow-hidden">
                <!-- Top section: Profile, Name, Languages -->
                <div class="absolute top-0 left-0 w-full p-5 z-10">
                    <div class="flex items-center gap-4">
                        <!-- Avatar -->
                        <div class="relative w-16 h-16 flex flex-shrink-0 items-center justify-center text-2xl bg-primary rounded-full shadow">
                            @if ($user->getProfilePictureUrl())
                            <img src="{{ $user->getProfilePictureUrl() }}" alt="{{ $user->name }}'s profile"
                                class="w-full h-full rounded-full shadow object-cover">
                            @else
                            <span class="text-2xl font-bold text-primary-content">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                            @endif

                            <img src="{{ $user->getFlagPictureUrl() }}" alt="flag"
                                class="absolute bottom-0 right-0 w-5 h-5 object-cover rounded-full border border-base-100 shadow" />
                        </div>
                        <!-- Name only -->
                        <div>
                            <h4 class="font-bold text-3xl text-white">{{ $user->name }}</h4>
                        </div>
                    </div>
                    <!-- Languages below name/avatar row -->
                    <div class="mt-8 flex flex-wrap gap-2 bg-indigo-600/70 rounded-xl px-4 py-5">
                        <span class="font-semibold">Teaches:</span>
                        @forelse($user->languages_teach ?? [] as $code)
                        <span class="px-2 py-1 bg-blue-900 rounded text-xs">{{ config('languages')[$code] ?? $code }}</span>
                        @empty
                        <span class="italic text-xs">None listed</span>
                        @endforelse
                    </div>
                    <div class="mt-6 flex flex-wrap gap-2 bg-indigo-600/70 rounded-xl px-4 py-5">
                        <span class="font-semibold">Learning:</span>
                        @forelse($user->languages_learn ?? [] as $code)
                        <span class="px-2 py-1 bg-yellow-900 rounded text-xs">{{ config('languages')[$code] ?? $code }}</span>
                        @empty
                        <span class="italic text-xs">None listed</span>
                        @endforelse
                    </div>
                    <!-- Description below languages -->
                    <p class="text-lg line-clamp-3 mt-4">{{ $user->description ?? 'No description yet.' }}</p>
                </div>
                <!-- Swipe indicators -->
                <span
                    x-show="swipingRight"
                    x-transition
                    class="border-2 rounded-md p-1 px-2 border-green-500 text-green-500 text-4xl capitalize font-extrabold top-10 right-5 rotate-12 absolute z-10">
                    FOLLOW
                </span>
                <span
                    x-show="swipingLeft"
                    x-transition
                    class="border-2 rounded-md p-1 px-2 border-red-500 text-red-500 text-4xl capitalize font-extrabold top-10 left-5 -rotate-12 absolute z-10">
                    NEXT PLEASE
                </span>

                <!-- Info & buttons -->
                <section class="absolute inset-x-0 bottom-0 py-2 pointer-events-none">
                    <div class="flex flex-col gap-2.5 mt-auto p-5 text-white">


                        <!-- Centered swipe buttons -->
                        <div class="flex justify-center items-center gap-2 mt-8 pointer-events-auto">
                            <!-- swipe left -->
                            <button @click="swipeLeft" class="rounded-full border-2 group border-red-600 p-3 text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="4"
                                    stroke="currentColor" class="w-9 h-9 group-hover:scale-105 transition-transform">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <!-- swipe right -->
                            <button @click="swipeRight" class="rounded-full border-2 group border-green-600 p-3 text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="4"
                                    stroke="currentColor" class="w-9 h-9 group-hover:scale-105 transition-transform">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        @endforeach
    </div>
</div>