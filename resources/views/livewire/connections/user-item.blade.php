<section class="flex flex-col items-start transition duration-150 ease-out hover:bg-gray-500/10">
    <div class="border-t border-base-300 w-full"></div>
    <div class="flex justify-between w-full">
        <div class="flex">
            <div>

                <!-- Avatar -->
                <div
                    class="relative w-16 h-16 m-4 flex flex-shrink-0 items-center justify-center text-2xl bg-primary rounded-full shadow">
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

                <!-- *Status (Not implemented) -->
                <div class="hidden flex items-center justify-center gap-1 my-1 -mt-3">
                    <div
                        class="h-1.5 w-1.5 rounded-full {{ $status == 'Online' ? 'bg-green-600' : ($status == 'Idle' ? 'bg-yellow-500' : 'bg-gray-500') }}">
                    </div>
                    <p class="font-normal text-xs text-gray-500 dark:text-gray-200/90">{{ $status }}</p>
                </div>
            </div>

            <!-- User Information -->
            <div class="flex flex-col mt-4">
                <div class="flex">
                    <h5 class="font-bold">
                        {{ $user->name }}
                    </h5>
                </div>

                <div class="flex gap-1 text-sm font-bold items-center mb-2">
                    <ul class="flex gap-1">
                        @forelse($languages_teach as $code)
                            <li>
                                {{ Str::upper($code) }}
                            </li>
                        @empty
                            <li>
                                ??
                            </li>
                        @endforelse
                    </ul>
                    <span> <x-tabler-transfer class="w-4 h-4 text-gray-500 dark:text-gray-200/40" /> </span>
                    <ul class="flex gap-1">
                        @forelse($languages_learn as $code)
                            <li>
                                {{ Str::upper($code) }}
                            </li>
                        @empty
                            <li>
                                ??
                            </li>
                        @endforelse
                    </ul>
                </div>

                <p class="text-gray-700 dark:text-gray-100/60 font-light">
                    {{ $description }}
                </p>
            </div>
        </div>

        <!-- Button for opening the user's profile -->
        <button wire:click="openProfile"
            class="flex flex-shrink-0 justify-center items-center mx-4 bg-indigo-600 w-12 h-12 my-auto rounded-full transition duration-150 ease-out active:scale-90 active:bg-indigo-700">
            <x-tabler-user-up class="w-7 h-7 text-white" />
        </button>
    </div>
</section>